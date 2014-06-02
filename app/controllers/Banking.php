<?php 
/*
 * @package		Loitd
 * @author		Tran Duc Loi
 * @copyright	Copyright (c) 2013 - 2014, Loitd, Inc.
 * @license		http://www.opensource.org/licenses/mit-license.php
 * @link		http://loitd.com
 * @since		Version 1.0
*/

class Banking extends BaseController{

	//receive trans result from IBANKING - Banking Result - the GET method
	public function index()
	{
		//init transaction model
		$trans 			= new Mtransaction;
		//first get all the params from Banking
		$transid 		= Input::get('transid');
		$responCode 	= Input::get('responCode');
		$mac 			= Input::get('mac');
		//
		$isthere		= Mtransaction::find($transid);

		$checkme		= $this->check($transid, $responCode, $mac, $isthere);

		if($checkme == 'thefuckistrue'){
			// now display to user
			$data2display = array(
				'transid'	=> $isthere->transid,
				'transtype'	=> Config::get('carray.transType')[$isthere->type],
				'sdt'		=> $isthere->target_account,
				'nph' 		=> $isthere->provider,
				'email'		=> $isthere->email_sent,
				'banked'	=> Config::get('carray.bankCode')[$isthere->bankid],
				'status'	=> Config::get('carray.transStatus')['S'.$isthere->status],
				//for the hidden
				'transid'	=> $transid,
				'responCode'=> $responCode,
				'mac'		=> $mac
			);
			return View::make('naptien.confirm', $data2display);
		} else {
			return $checkme;
		}

	}

	//handle the post data from the confirm.blade view
	public function store()
	{
		$message = array(
			'sdt.required' 			=> 'Số điện thoại là trường bắt buộc.',
			'email.required' 		=> 'Email là trường bắt buộc.',
			'transid.required' 		=> 'Mã giao dịch là trường bắt buộc.',
			'responCode.required' 	=> 'Mã trả về là trường bắt buộc.',
			'mac.required' 			=> 'Mac là trường bắt buộc.',
			'numeric'	=> 'Dữ liệu bắt buộc là số.',
			'email'		=> 'Dữ liệu không đúng định dạng email.',
			'between'	=> 'Độ dài phải nằm trong khoảng :min đến :max'
		);
		# receive input and begin validate
		$rules = array(
			'LToken'	=> 'required|numeric|min:100000|max:999999',
			'sdt'		=> 'required|max:30',
			'email'		=> 'required|email|max:40',
			'transid'	=> 'required',
			'responCode'=> 'required',
			'mac'		=> 'required'
		);
		$validator = Validator::make(Input::all(), $rules, $message);

		//get all the hidden POST data
		$transid 		= Input::get('transid');
		$responCode 	= Input::get('responCode');
		$mac 			= Input::get('mac');
		
		if ($validator->fails()) {
			# code...
			return Redirect::to('banking?transid=' . $transid .'&responCode=' . $responCode . '&mac=' . $mac)
						->withErrors($validator)
						->withInput(Input::all());
		} else {
			//update to DB
			$isthere 		= Mtransaction::find($transid); //the transaction
			//$checkme	= $this->check($transid, $responCode, $mac, $isthere);
			$checkme	= $this->recheck($transid, $responCode, $mac, $isthere);

			if ($checkme == 'thefuckistrue') {
				//update status to 95 -> user confirmed
				$isthere->status 	= 95;
				//update what user changed
				$isthere->target_account 	= Input::get('sdt');
				$isthere->email_sent 		= Input::get('email');
				$isthere->save();
				//do what transaction indicate
				switch ($isthere->type) {
					case 'MTOPUP':
						return $this->mtopup($isthere);
						break;
					case 'GTOPUP':
						return $this->gtopup($isthere);
						break;
					case 'MSOFTPIN':
						return $this->msoftpin($isthere);
						break;
					case 'GSOFTPIN':
						return $this->gsoftpin($isthere);
						break;
					default:
						# code...
						break;
				}
			} else {
				return $checkme;
			}


		}

	}

	public function create()
	{
		

	}

	/*
		- check that the banking was successful via transaction status
		- Topup for mobile
		- Send mail with result to cus
	*/
	private function mtopup($isthere)
	{
		//check if the banking is ok?
		if ($isthere->status == 95) {
			//do the topup
			$trans 	= new Mtransaction;
			$topup 	= $trans->mtopup($isthere->transid, $isthere->target_account, $isthere->amount);
			//update the database with the result
			$isthere->telco_transid 	= $topup->epayTransID;
			$isthere->topup_status		= $topup->errorCode;
			$isthere->topup_response	= $topup->errorMessage;
			//update current balance to DB
			$isthere->merchant_balance	= $topup->merchantBalance;
			//change status to 94 - done topup
			$isthere->status = 94;
			$isthere->save();
			//display the result for customers

			$data2display 		= array(
				'transid'		=> $isthere->transid,
				'transtype'		=> Config::get('carray.transType')[$isthere->type],
				'sdt'			=> $isthere->target_account,
				'nph'			=> $isthere->provider,
				'email'			=> $isthere->email_sent,
				'banked'		=> Config::get('carray.bankCode')[$isthere->bankid],
				'status'		=> Config::get('carray.transStatus')['S'.$isthere->status],
				'bankresult'	=> 'Thành công',
				'topupresult'	=> Config::get('carray.topupResult')[$topup->errorCode]
				
			);

			//email to user
			Mail::send('naptien.email', $data2display, function($message) use ($isthere){
				$message->from(ADMIN_EMAIL, SITE_NAME);
				$message->to(ADMIN_EMAIL)->bcc($isthere->email_sent)->subject(SITE_NAME. ': Email thông báo chi tiết giao dịch');
			});
			//return the view to display
			return View::make('naptien.result', $data2display);

		} else {
			return false;
		}
	}

	/*
		- check that the banking was successful via transaction status
		- Topup for game
		- Send mail with result to cus
	*/
	private function gtopup($isthere)
	{
		//check if the banking is ok?
		if ($isthere->status == 95) {
			//do the topup
			$trans 	= new Mtransaction;
			$topup 	= $trans->gtopup($isthere->transid, $isthere->target_account, $isthere->provider, $isthere->amount);
			//update the database with the result
			$isthere->telco_transid 	= $topup->epayTransID;
			$isthere->topup_status		= $topup->errorCode;
			$isthere->topup_response	= $topup->errorMessage;
			//update current balance to DB
			$isthere->merchant_balance	= $topup->merchantBalance;
			//change status to 94 - done topup
			$isthere->status = 94;
			$isthere->save();
			//display the result for customers

			$data2display 		= array(
				'transid'		=> $isthere->transid,
				'transtype'		=> Config::get('carray.transType')[$isthere->type],
				'sdt'			=> $isthere->target_account,
				'nph'			=> $isthere->provider,
				'email'			=> $isthere->email_sent,
				'banked'		=> Config::get('carray.bankCode')[$isthere->bankid],
				'status'		=> Config::get('carray.transStatus')['S'.$isthere->status],
				'bankresult'	=> 'Thành công',
				'topupresult'	=> Config::get('carray.topupResult')[$topup->errorCode]
				
			);

			//email to user
			Mail::send('naptien.email', $data2display, function($message) use ($isthere){
				$message->from(ADMIN_EMAIL, SITE_NAME);
				$message->to(ADMIN_EMAIL)->bcc($isthere->email_sent)->subject(SITE_NAME. ': Email thông báo chi tiết giao dịch');
			});
			//return the view to display
			return View::make('naptien.result', $data2display);

		} else {
			return false;
		}
	}


	/*
	process the checking procedure
	Will return 'thefuckistrue' if data is OK
	or will return the view of error page
	*/
	private function check($transid = '', $responCode = '', $mac = '', $isthere)
	{
		//init transaction model
		$trans 			= new Mtransaction;
		//now check the response with MAC - prevent fake request
		$calculatedMAC	= $trans->mDESMAC_3des($transid . $responCode, MERCHANT_RECEIVE_KEY);
		if ($calculatedMAC == $mac) {
			//the data is right -> check if the transid is in the DB and has the status is 97
			//$isthere	= Mtransaction::find($transid);
			if ($isthere->status == 97) {
				//the transaction exists and is waiting result from banking
				//now update the DB
				$isthere->bank_status = $responCode;
				$isthere->save();
				//now if the banking transaction is success then display transaction detail to user
				if($responCode == '00'){
					//re-confirm with bankpayment GW
					$confirm_res  = $trans->confirm($transid, $isthere->amount, $responCode);
					//now update new response code to the REAL bank status
					$isthere->bank_status	= $confirm_res->responsecode;
					$isthere->bank_response	= 	'responsecode: '	.$confirm_res->responsecode .
												'. tranid: '		.$confirm_res->tranid.
												'. descriptionen:'	.$confirm_res->descriptionen;
					$isthere->save();
					//return checking result base on response code
					if ($confirm_res->responsecode == '00') { //MUST EQUAL '00' TO DISPLAY THE FORM
						//change transaction status to 96 (this trans is checked)
						$isthere->status 	= 96;
						$isthere->save();
						//bankgw confirm that the transaction is successfull
						return 'thefuckistrue';	//return to caller
					} else {
						//bankgw confirm that the transaction is NOT successfully
						//return the transaction is fail to cus
						return View::make('naptien.notransaction', 
						array('what'=>'2: Giao dịch trừ tiên tại ngân hàng thất bại. Vui lòng thử lại hoặc liên hệ quản trị viên để được hỗ trợ.'));
					}
					
				} else {
					//here, the info is right but the banking transaction is fail
					return View::make('naptien.notransaction', 
						array('what'=>'1: Giao dịch trừ tiên tại ngân hàng thất bại. Vui lòng thử lại hoặc liên hệ quản trị viên để được hỗ trợ.'));
				}
				
			} else if ($isthere->status == 95){ 
				//this transaction is confirmed with banking before
				//Now we will get the status of the first confirm. If it is OK then the status must be 95
				return 'thefuckistrue';
				//if the status is not 95 -> the transaction with the first confirm with bank gw must be ERROR
			} else {
				//the data is fake or the transaction is processed
				return View::make('naptien.notransaction', 
						array('what'=>'2: Giao dịch yêu cầu không tồn tại trên hệ thống. Vui lòng liên hệ quản trị viên để được hỗ trợ.'));
			}
		} else {
			//the data is fake. Return the view only
			return View::make('naptien.notransaction', 
						array('what'=>'1: Giao dịch yêu cầu xử lý không tồn tại trên hệ thống. Vui lòng liên hệ quản trị viên để được hỗ trợ.'));
		}
	}

	/*
		- to re-check because BANKGW does not allow 2 confirm
	*/
	private function recheck($transid='', $responCode='', $mac='', $isthere)
	{
		//init transaction model
		$trans 			= new Mtransaction;
		//now check the response with MAC - prevent fake request
		$calculatedMAC	= $trans->mDESMAC_3des($transid . $responCode, MERCHANT_RECEIVE_KEY);
		if ($calculatedMAC == $mac) {
			//the data is right -> check if the transid is in the DB and has the status is 97
			//$isthere	= Mtransaction::find($transid);
			if ($isthere->status == 96 || $isthere->status == 95) { //96 means this trans is checked ok there. Now just do the trans
				//now if the banking transaction is success then display transaction detail to user
				if($responCode == '00'){
					//bankgw confirm that the transaction is successfull
					return 'thefuckistrue';	//return to caller
				} else {
					//here, the info is right but the banking transaction is fail
					return View::make('naptien.notransaction', 
						array('what'=>'1.: Giao dịch trừ tiên tại ngân hàng thất bại. Vui lòng thử lại hoặc liên hệ quản trị viên để được hỗ trợ.'));
				}
				
			} else {
				//the data is fake or the transaction is processed
				return View::make('naptien.notransaction', 
						array('what'=>'2.: Giao dịch yêu cầu không tồn tại trên hệ thống. Vui lòng liên hệ quản trị viên để được hỗ trợ.'));
			}
		} else {
			//the data is fake. Return the view only
			return View::make('naptien.notransaction', 
						array('what'=>'1.: Giao dịch yêu cầu xử lý không tồn tại trên hệ thống. Vui lòng liên hệ quản trị viên để được hỗ trợ.'));
		}
	}

	/*
		- Get transaction status
	*/
	private function getStatus($isthere)
	{
		$trans1 = new Mtransaction;
		$res = $trans1->getStatus($isthere->transid, $isthere->amount, $isthere->bank_status);
		return $res;
	}

















	//class banking
}