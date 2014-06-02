<?php 
/**
* 
*/

include('Crypt/RSA.php');

class Secu extends Eloquent{
	public static function maketoken($size = 6) //default length = 5
	{
		$thetoken = rand(100000, 999999);
		Session::put('LToken', $thetoken);
		return $thetoken;
	}

	//for captcha
	public static function lencode($input)
	{
		//concat 5 inputs because $input can be inserted as array
		$toaes = '';
		for ($i=0; $i < count($input); $i++) { 
			# code...
			$toaes .= $input[$i] . ';.;';
		}

		//aes the result
		$afteraes = Crypt::encrypt($toaes);

		//return
		return $afteraes;

	}

	//
	public static function ldecode($input)
	{
		try{
			$beforeaes =  Crypt::decrypt($input);
			//split input
			$output = explode(';.;', $beforeaes);
			//return
			return $output;
		} catch(Exception $e){
			return NULL;
		}
	}

	public static function checkdata()
	{
		$x = Auth::user();
		$email = $x->email;
		if ($x->count()) {
			$x = $x->first();
			$xx= Secu::ldecode($x->ph1);
			$pf= $x->point;

			if(count($xx) == 3){
				$emailx = $xx[0]; //decoded email
				$px 	= $xx[1]; //decoded point
				
				if ($x->email == $emailx && $pf == $px) {
					return true;
				} else {
					//code is not broken
					$x->point = $px;
					$x->email = $emailx;
					//save the point
					$x->save();
					//fail. Now send email
					Mail::send('emails.warning', array('email'=>$emailx, 'point'=>$pf, 'px'=>$px), function($message) {
						$message->from(ADMIN_EMAIL, SITE_NAME)
								->to(ADMIN_EMAIL)
								->subject('Cảnh báo không toàn vẹn dữ liệu');
					});

				}
			} else {
				//code is break, reset point to 0
				$x->point 	= 0;
				//calculate new hash code
				$ph1 		= array($email, 0); //current point = 0
				$x->ph1		= Secu::lencode($ph1);
				$x->save();
				//fail. Now send email
				Mail::send('emails.warning', array('email'=>$email, 'point'=>$pf, 'px'=>0), function($message) {
					$message->from('loitd@naptien.info', SITE_NAME)
							->to(ADMIN_EMAIL)
							->subject('Cảnh báo không toàn vẹn dữ liệu');
				});
			}
			
		} 

		return false;
	}

  //make transid for charging trans
	public static function makeTransID()
	{
		$r = date('YmdHis') . rand(10, 99) . round(microtime(true) * 1000);
		return CHG_PARTNERCODE . '_' . $r;
	}

  //you must check inter before using this function
  public static function changebalace($amount, $belowzero = false)
  {
  	$x = Auth::user(); //get current user
    if (!$belowzero) {
      if(($x->point + $amount) < 0){
        return false;
      } else {
        $x          = Auth::user();
        $x->point   = $x->point + $amount;
        $ph1        = array($x->email, $x->point);
        $x->ph1     = Secu::lencode($ph1);
        $x->save();    
        return true;
      }
    }
    
  }


  //end class
}



