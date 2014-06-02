<?php 
/*
 * @package		Loitd
 * @author		Tran Duc Loi
 * @copyright	Copyright (c) 2013 - 2014, Loitd, Inc.
 * @license		http://www.opensource.org/licenses/mit-license.php
 * @link		http://loitd.com
 * @since		Version 1.0
*/
class Charge extends BaseController{
	private $wsclient;

	public function __construct() {
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->beforeFilter('auth', array('only'=>array('index')));
		//init ws client
		
	}

	public function index()
	{
		if(Secu::checkdata())
		{
			$data = array('cap'	=> URL::route("captcha"));
			return View::make('charge.index', $data);
		} else {
			return Redirect::to(URL::action('Charge@index'))
				->with('message', 'Lỗi: Đã có lỗi không toàn vẹn dữ liệu xảy ra. Thông tin cảnh báo đã được gửi tới ban quản trị để kiểm tra tài khoản của bạn.');
		}
		
	}

	public function store()
	{
		//return Redirect::to(URL::action('Charge@index'))->with('message', 'Giao dịch gạch thẻ thất bại. Xin hãy thử lại. Mã GD: ' . 0);
		//validate inputs
		$message = array(
			'ncc.required' 			=> 'Nhà cung cấp là trường bắt buộc.',
			'pincode.required' 		=> 'Mã thẻ là trường bắt buộc.',
			'serial.required' 		=> 'Serial thẻ là trường bắt buộc.',
			'captcha.required' 		=> 'Mã bảo vệ là trường bắt buộc.',
			'captcha.captcha' 		=> 'Mã bảo vệ không khớp.',
			'alpha_dash' 			=> 'Dữ liệu không hợp lệ vì có các ký tự đặc biệt.',
			'alpha_num' 			=> 'Dữ liệu không hợp lệ.',
			'numeric' 				=> 'Dữ liệu không hợp lệ.',
			'between' 				=> 'Dữ liệu phải có từ :min đến :max ký tự.',
		);
		# receive input and begin validate
		$rules = array(
			'captcha'		=> 'required|captcha|AlphaDash',
			'ncc'			=> 'required|AlphaDash',
			'pincode' 		=> 'required|numeric',
			'serial' 		=> 'required|AlphaDash'
		);

		$validator = Validator::make(Input::all(), $rules, $message);

		if ($validator->fails()) {
			return Redirect::to(URL::action('Charge@index'))
				->withErrors($validator)
				->withInput(Input::all());
			
		} else {
			//validate ok
			return $this->charging(Input::get('serial'), Input::get('pincode'), 10000, Input::get('ncc'));
		}
	}

	public function create()
	{
		//var_dump($this->login());
		//var_dump($this->logout());
		//var_dump($this->changePassword('ctxarivaf'));
		return($this->charging());
		//if(self::login()){ var_dump(self::changePassword('123456a@')); }
		
	}

	private function login()
	{
		$wsclient = new SoapClient(WS_CHARGING);
		//now encrypt password
		$rsa = new ClsCryptor();
		
		//$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_OAEP);
		$rsa->GetpublicKeyFrompemFile(CHG_PUBLIC_KEYPATH);
		$enc_pass = base64_encode($rsa->encrypt(CHG_PASS));

		$loginObj = $wsclient->login(CHG_USER, $enc_pass, CHG_PARTNERID);
		
		//now decrypt sessionid received with private key
		$rsa->GetPrivatekeyFrompemFile(CHG_PRIVATE_KEYPATH);
		$realsessionid = $rsa->decrypt(base64_decode($loginObj->sessionid));

		//save the login result into DB
		$chgproc = new Chgprocedure;
		$chgproc->type = 'LOGIN_PROCEDURE';
		$chgproc->sessionid = $realsessionid;
		$chgproc->status = $loginObj->status;
		$chgproc->message = $loginObj->message;
		$chgproc->save();
		//return result to caller
		if ($loginObj->status == 1) {
			return true;
		} else {
			return false;
		}
	}

	private function logout()
	{
		$wsclient = new SoapClient(WS_CHARGING);
		//get the sessionid
		$login 	= Chgprocedure::where('type','=','LOGIN_PROCEDURE')->orderby('id', 'desc')->first();
		
		if(!is_null($login) && !is_null($login->sessionid))
		{
			$logoutObj = $wsclient->logout(CHG_USER, CHG_PARTNERID, md5($login->sessionid));
			//save logout status to DB
			$logout = new Chgprocedure;
			$logout->type = 'LOGOUT_PROCEDURE';
			$logout->status = $logoutObj->status;
			$logout->message = $logoutObj->message;
			$logout->save();
			//return to caller
			if ($logoutObj->status == 1) {
				//update sessionid for login
				$login->sessionid = null;
				$login->save();
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	}

	private function changePassword($new_pass)
	{
		$wsclient 	= new SoapClient(WS_CHARGING);
		$login 		= Chgprocedure::where('type','=','LOGIN_PROCEDURE')->orderby('id', 'desc')->first();
		if (is_null($login) || is_null($login->sessionid)) {
			//now re-login again because you have just logout
			$this->login();
		} 
		//not logout before
		$login 	= Chgprocedure::where('type','=','LOGIN_PROCEDURE')->orderby('id', 'desc')->first();
		//1. Prepare data to be sent
		$trans 		= Secu::makeTransID();
		$key 		= $this->Hextobyte($login->sessionid);
		$tdes 		= new TriptDes($key);
		$oldpass 	= $this->ByteToHex($tdes->encrypt(CHG_PASS));
		$newpass 	= $this->ByteToHex($tdes->encrypt($new_pass));
		
		$changeresp = $wsclient->changepassword($trans, CHG_USER, CHG_PARTNERID, $oldpass, $newpass, md5($login->sessionid));
		//save to DB
		$chgpass 			= new Chgprocedure;
		$chgpass->type 		= 'CHANGE_PASSWORD';
		$chgpass->transid 	= $trans;
		$chgpass->status 	= $changeresp->status;
		$chgpass->message 	= $changeresp->message;
		$chgpass->sessionid = $login->sessionid;
		$chgpass->save();
		//return to caller
		if ($changeresp->status == 1) {
			return true;
		} else {
			return false;
		}
	}

	private function changempin($new_mpin)
	{

	}

	/*
		- serial
		- pincode
		- amount
		- ncc
	*/
	public function charging($serial = 'AB123456789', $pincode = '123456789', $amount = 10000, $ncc= 'VTT')
	{
		$wsclient 	= new SoapClient(WS_CHARGING);
		$login 		= Chgprocedure::where('type','=','LOGIN_PROCEDURE')->orderby('id', 'desc')->first();
		if (is_null($login) || is_null($login->sessionid)) {
			//now re-login again because you have just logout
			$this->login();
		} 
		//not logout before
		$login 	= Chgprocedure::where('type','=','LOGIN_PROCEDURE')->orderby('id', 'desc')->first();
		//1. Prepare data to be sent
		$key 		= $this->Hextobyte($login->sessionid);
		$trans 		= Secu::makeTransID();
		$tdes 		= new TriptDes($key);
		$mpin 		= $this->ByteToHex($tdes->encrypt(CHG_MPIN));
		$carddat 	= $this->ByteToHex($tdes->encrypt($serial . ':' . $pincode . ':' . $amount . ':' . $ncc));
		$chgstatus 	= 0;

		$chg 		= $wsclient->cardcharging($trans, CHG_USER, CHG_PARTNERID, $mpin, 'GUEST', $carddat, md5($login->sessionid));
		//check if session timeout
		if($chg->status == 7 || $chg->status == 3)
		{
			$this->login();
			$chg 		= $wsclient->cardcharging($trans, CHG_USER, CHG_PARTNERID, $mpin, 'GUEST', $carddat, md5($login->sessionid));
		} 
		//save to DB
		$email 				= Auth::user()->email;
		$charge 	= new Chgprocedure;
		$charge->type 		= 'CHARGING';
		$charge->email 		= $email;
		$charge->status 	= $chg->status;
		$charge->amount		= $amount;
		$charge->ncc 		= $ncc;
		$charge->pincode 	= $pincode;
		$charge->serial 	= $serial;
		$charge->message 	= $chg->message;
		$realamount = $tdes->decrypt($this->Hextobyte($chg->responseamount));
		$charge->response_amount = $realamount;
		$charge->transid 		 = $trans;
		$charge->telco_transid 	 = $chg->transid;
		$charge->save();

		if($chg->status == 1){ //ghach the OK -> check data -> cong diem
			Secu::checkdata();
			$doit = Secu::changebalace($realamount, false);
			if($doit) {
				return Redirect::to(URL::action('Charge@index'))
					->with('message', 'Giao dịch thành công. Mã GD: ' . $trans . '. Cảm ơn bạn đã sử dụng dịch vụ.');
				} else {
					return Redirect::to(URL::action('Charge@index'))
						->with('message', 'Giao dịch cộng điểm thất bại. Xin hãy liên hệ với ban quản trị để xử lý. Mã GD: ' . $trans);
				}
			//send email to cús
			$data2display 		= array(
				'transid'		=> $trans,
				'transtype'		=> 'CHARGING',
				'email'			=> $email,
				'pincode'		=> $pincode,
				'serial'		=> $serial,
				'amount'		=> $realamount,
				'charge'		=> 'Thành công',
				'addpoint'		=> $doit ? 'Thành công' : 'Thất bại'
				
			);
			Mail::send('charge.email', $data2display, function($message) use ($email){
				$message->from(ADMIN_EMAIL, SITE_NAME);
				$message->to(ADMIN_EMAIL)->bcc($email)->subject(SITE_NAME. ': Email thông báo chi tiết giao dịch');
			});
		} else if($chg->status == 4 || $chg->status == 50 || $chg->status == 51 || $chg->status == 52 || $chg->status == 53 || $chg->status == -3)
		{
			return Redirect::to(URL::action('Charge@index'))
						->with('message', 'Thẻ không đúng hoặc không sử dụng được (do đã bị khóa, chưa kích hoạt, serial và mã thẻ không khớp). 
							Giao dịch ghạch thẻ thất bại. Mã GD: ' . $trans);
		} else if($chg->status == 13 || $chg->status == 10 || $chg->status == 11)
		{
			return Redirect::to(URL::action('Charge@index'))
						->with('message', 'Hệ thống gián đoạn tại nhà mạng. Giao dịch gạch thẻ thất bại. Xin hãy thử lại sau. Mã GD: ' . $trans);
		} else if($chg->status == 99)
		{
			return Redirect::to(URL::action('Charge@index'))
						->with('message', 'Giao dịch chưa xác định kết quả. Xin hãy liên hệ ban quản trị để xử lý. Mã GD: ' . $trans);
		} else {
			return Redirect::to(URL::action('Charge@index'))
					->with('message', 'Giao dịch gạch thẻ thất bại. Xin hãy thử lại. Mã GD: ' . $trans);
		}
		
		//return $chg;
	}

	private function Hextobyte($strHex){
        $string='';
        for ($i=0; $i < strlen($strHex)-1; $i+=2)
        {
                $string .= chr(hexdec($strHex[$i].$strHex[$i+1]));
        }
        return  $string;
	}
	private function ByteToHex($strHex){
        return   bin2hex ($strHex);
	}

	

//end class
}

//for RSA encryption
class ClsCryptor{
    private  $RsaPublicKey;
    private  $RsaPrivateKey;
    private  $TripDesKey;
    public function GetpublicKeyFromCertFile($filePath)
    {
          $fp=fopen($filePath,"r");
          $pub_key=fread($fp,filesize($filePath));
          fclose($fp);
          openssl_get_publickey($pub_key);

          $this-> RsaPublicKey = $pub_key;
    }

    public function GetpublicKeyFrompemFile($filePath)
    {
          $fp=fopen($filePath,"r");
          $pub_key=fread($fp,filesize($filePath));
          fclose($fp);
          openssl_get_publickey($pub_key);
          //print_r($pub_key);
          $this-> RsaPublicKey = $pub_key;
          //print_r($this-> RsaPublicKey);
    }

    public function GetPrivatekeyFrompemFile($filePath)
    {
          $fp=fopen($filePath,"r");
          $pub_key=fread($fp,filesize($filePath));
          fclose($fp);
          $this-> RsaPrivateKey = $pub_key;


    }
   public function GetPrivate_Public_KeyFromPfxFile($filePath,$Passphase)
    {
           $p12cert = array();
           $fp=fopen($filePath,"r");
           $p12buf = fread($fp, filesize($filePath));
           fclose($fp);
           openssl_pkcs12_read($p12buf, $p12cert, $Passphase);
           $this-> RsaPrivateKey =  $p12cert['pkey'];
           $this-> RsaPublicKey = $p12cert['cert'];

    }

    function encrypt($source)
    {
        //path holds the certificate path present in the system
        $pub_key = $this -> RsaPublicKey;
        //$source="sumanth";
        $j=0;
        $x=strlen($source)/10;
        $y=floor($x);
        $crt='';
        //print_r($pub_key) ;
        for($i=0;$i<$y;$i++)
        {
        $crypttext='';

        openssl_public_encrypt(substr($source,$j,10),$crypttext,$pub_key);$j=$j+10;
        $crt.=$crypttext;
        $crt.=":::";
        }
        if((strlen($source)%10)>0)
        {
        openssl_public_encrypt(substr($source,$j),$crypttext,$pub_key);
        $crt.=$crypttext;
        }
        return($crt);

    }
    //Decryption with private key
    function decrypt($crypttext)
    {
        $priv_key = $this -> RsaPrivateKey;
        $tt=explode(":::",$crypttext);
        $cnt=count($tt);
        $i=0;
        $str='';
        while($i<$cnt)
        {
        openssl_private_decrypt($tt[$i],$str1,$priv_key);
        $str.=$str1;
        $i++;
        }
        return $str;
    }
}

class TriptDes{
   private $DessKey;
   public function TriptDes($key){
   $this->DessKey= $key;
   }
   public function decrypt($text) {
          $key = $this->DessKey;
          $size = mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_ECB);
          $iv = mcrypt_create_iv($size, MCRYPT_RAND);
          $decrypted = mcrypt_decrypt(MCRYPT_3DES, $key, $text, MCRYPT_MODE_ECB,$iv);
          return rtrim($this->pkcs5_unpad($decrypted) );
   }

   public function encrypt($text) {
          $key = $this->DessKey;
          $text=$this->pkcs5_pad($text,8);  // AES?16????????
          $size = mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_ECB);
          $iv = mcrypt_create_iv($size, MCRYPT_RAND);
          $bin = pack('H*', bin2hex($text) );
          $encrypted = mcrypt_encrypt(MCRYPT_3DES, $key, $bin, MCRYPT_MODE_ECB,$iv);
          return $encrypted;
   }

   function pkcs5_pad($text, $blocksize) {
            $pad = $blocksize - (strlen($text) % $blocksize);
            return $text . str_repeat(chr($pad), $pad);
   }

   function pkcs5_unpad($text) {
            $pad = ord($text{strlen($text)-1});
            if ($pad > strlen($text)) return false;
            if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
            return substr($text, 0, -1 * $pad);
   }

}