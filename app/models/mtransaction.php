<?php 

/**
* Object for BuyItem of download softpin
*/
class BuyItem
{
	public $productId;
	public $quantity;
	function __construct($p='', $q=''){
		$this->productId 	= $p;
		$this->quantity		= $q;
	}
}

/**
* 
*/
class Mtransaction extends Eloquent{
	protected $table 		= 'transactions';
	protected $primaryKey	= 'transid';
	public $incrementing	= false; //the very important key
	public $timestamps 		= true; //will use updated_at and created_at automatically from laravel

	private $wsclient;
	//banking functions
	public function deposit($transid, $txnAmt, $fee, $username, $bankid, $resUrl = URL_RESPONSE)
	{
		$wsclient 			= new SoapClient(WS_BANKING);
		//$stan 				= $this->banking_lib->makeStan();
		$stan 				= substr(gettimeofday()['sec'], 4);
		$termtxndatetime	= date('YmdHis');
		//$txnAmt				= 10000; //so tien
		//$fee 				= '10'; // fee dich vu
		//$username			= 'NGUYEN THI HUE';
		$IssuerID			= 'Megacard'; //ten cong ty
		//$transid			= $this->makeStan();
		//$bankid				= '99008';
		//$resUrl				= URL_RESPONSE;
		$macinput			= MERCHANT_ID . $stan . $termtxndatetime . $txnAmt . $fee . $username . $IssuerID . $transid . $bankid . $resUrl;
		$mac				= $this->mDESMAC_3des($macinput, MERCHANT_SEND_KEY);
		//return $mac;
		$res_deposit = $wsclient->Deposit(array(
			'merchantid'		=> MERCHANT_ID,
			'stan'				=> $stan,
			'termtxndatetime'	=> $termtxndatetime,
			'txnAmount'			=> $txnAmt,
			'fee'				=> $fee,
			'userName'			=> $username,
			'IssuerID'			=> $IssuerID,
			'tranID'			=> $transid, 
			'bankID'			=> $bankid,
			'respUrl'			=> $resUrl,	//res url
			'mac'				=> $mac)
		);
		//return $res_deposit->DepositResult;

		//check MAC return
		$macreturn			= $res_deposit->DepositResult->responsecode . 
		$res_deposit->DepositResult->tranid . $res_deposit->DepositResult->status;
		if ($res_deposit->DepositResult->mac == $this->mDESMAC_3des($macreturn, MERCHANT_RECEIVE_KEY)) {
			//MAC is corrected -> return the value
			return $res_deposit->DepositResult;
		} else {
			//if MAC is not correct then return NULL
			return NULL;	
		}

	}

	//confirm transaction with bank gw if the transaction is OK
	public function confirm($tranid, $txnAmount, $confirmCode)
	{
		$wsclient 			= new SoapClient(WS_BANKING);
		$data2confirm = array(
			'merchantcode' 	=> MERCHANT_ID,
			'tranid' 		=> $tranid,
			'txnAmount'		=> $txnAmount,
			'confirmCode'	=> $confirmCode,
			'mackey' 		=> $this->mDESMAC_3des(MERCHANT_ID . $tranid . $txnAmount . $confirmCode, MERCHANT_SEND_KEY)
		);
		$res_confirm		= $wsclient->comfirm($data2confirm);
		return $res_confirm->comfirmResult;
	}

	public function getStatus($tranid='', $txnAmount, $confirmCode)
	{
		$wsclient 			= new SoapClient(WS_BANKING);
		$res_status			= $wsclient->getStatus(array(
			'tranid'		=> $tranid,
			'merchantcode'	=> MERCHANT_ID,
			'mackey'		=> $this->mDESMAC_3des(MERCHANT_ID . $tranid . $txnAmount . $confirmCode, MERCHANT_SEND_KEY)
		));
		return $res_status->getStatusResult;
	}

	//topup for mobile
	public function mtopup($transid, $sdt, $amount)
	{
		$wsclient			= new SoapClient(WS_TOPUP);
		//do the login
		$token 				= $this->topup_login();
		//do the topup
		$topup_result = $wsclient->partnerDirectTopupMobile(
			MERCHANT_USERNAME, 
			$sdt, 
			$amount, 
			$transid, 
			$token
		);
		//return the result
		return $topup_result;

	}

	//topup for game
	public function gtopup($transid, $sdt, $provider, $amount)
	{
		$wsclient			= new SoapClient(WS_TOPUP);
		//do the login
		$token 				= $this->topup_login();
		//do the topup
		$topup_result = $wsclient->partnerDirectTopupGame(
			MERCHANT_USERNAME,
			$provider,
			$sdt, //targetAccount
			$amount, 
			$transid, 
			$token
		);
		//return the result
		return $topup_result;

	}

	//download mobile card
	public function softpin($transid, $productid, $quantity)
	{
		$wsclient 		= new SoapClient(WS_TOPUP);
		$token			= $this->topup_login();
		$buyItem		= new BuyItem($productid, $quantity);
		$softpin_result = $wsclient->partnerDownloadSoftpinV10(
			MERCHANT_USERNAME,
			array($buyItem),
			$transid,
			MERCHANT_KBT,
			$token
		);
		return $softpin_result;
	}

	private function topup_login()
	{
		$wsclient			= new SoapClient(WS_TOPUP);		
		$login_obj = $wsclient->signInAsPartner(MERCHANT_USERNAME, MERCHANT_PASSWORD);
		return $login_obj->token;
	}


	//supporting functions
	public function makeStan()
	{
		return date('YmdHis') . NOS . rand(1000, 9999) . round(microtime(true) * 1000);
	}

	public function mDESMAC_3des($input, $key)
	{
		$input = sha1($input);
		$len = strlen($input);
	
		$td = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_ECB, '');
		$blocksize = mcrypt_enc_get_block_size($td);
		$keysize = mcrypt_enc_get_key_size($td);
		$iv_size = mcrypt_enc_get_iv_size($td);
		//$iv = "hywebpg5";
		$input_len = strlen($input);
		$padsize = $blocksize-($input_len%$blocksize);
		@mcrypt_generic_init($td, $key, $iv);
		$MacDes = bin2hex(mcrypt_generic($td, $this->hex2bin($input)));
		return strtoupper($MacDes);
	}
	
	public function hex2bin($str)
	{
		$bin = "";
		$i = 0;
		do {
			$bin .= chr(hexdec($str{$i}.$str{($i + 1)}));
			$i += 2;
		} while ($i < strlen($str));
		return $bin;
	}

	public function tdes($what2do, $input, $key)
	{
		//encryption 3TDES. Open the cipher.
		$td 		= mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_ECB, '');
		//get block size
		$blocksize	= mcrypt_enc_get_block_size($td); 
		//get key size
		$keysize 	= mcrypt_enc_get_key_size($td);
		//get iv size
		$ivsize		= mcrypt_enc_get_iv_size($td);
		//create the iv
		$iv 		= mcrypt_create_iv($ivsize, MCRYPT_RAND);
		//get input size
		$inputsize	= strlen($input);
		//calculate pad size
		$padsize	= $blocksize - ($inputsize%$blocksize);
		//init encryption
		@mcrypt_generic_init($td, $key, $iv);
		if($what2do == 'EN'){
			//encrypt data
			$output	= mcrypt_generic($td, $input);	
		} elseif ($what2do == 'DE') {
			//decrypt data. 
			$output	= mdecrypt_generic($td, $input);
		}
		//release encryption handler
		mcrypt_generic_deinit($td);
		//close module
		mcrypt_module_close($td);
		//return what we need
		return $output;
	}



	//class
}