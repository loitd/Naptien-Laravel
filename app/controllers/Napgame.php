<?php 
/*
 * @package		Loitd
 * @author		Tran Duc Loi
 * @copyright	Copyright (c) 2013 - 2014, Loitd, Inc.
 * @license		http://www.opensource.org/licenses/mit-license.php
 * @link		http://loitd.com
 * @since		Version 1.0
*/

class Napgame extends BaseController{

	//when index form is get
	public function index()
	{
		return View::make('napgame.index', array('cap'=>URL::route("captcha")));
	}

	//when index form is posted
	public function store()
	{
		$message = array(
			'email.required' 			=> 'Email là trường bắt buộc.',
			'nh.required' 				=> 'Ngân hàng là trường bắt buộc.',
			'sotien.required' 			=> 'Số tiền là trường bắt buộc.',
			'nph.required' 				=> 'Nhà phát hành là trường bắt buộc.',
			'target_account.required' 	=> 'Tài khoản nạp là trường bắt buộc.',
			'alpha_dash' 				=> 'Dữ liệu không hợp lệ.',
			'alpha_num' 				=> 'Dữ liệu không hợp lệ.',
			'captcha.required' 			=> 'Mã bảo vệ là trường bắt buộc.',
			'captcha.captcha' 			=> 'Mã bảo vệ không khớp.',
			'numeric'	=> 'Dữ liệu bắt buộc là số.',
			'email'		=> 'Dữ liệu không đúng định dạng email.',
			'max'		=> 'Độ dài phải nằm trong khoảng cho phép'
		);
		# receive input and begin validate
		$rules = array(
			'captcha'			=> 'required|captcha|AlphaNum',
			'target_account'	=> 'required|AlphaDash|max:30',
			'nph'				=> 'required|AlphaDash',
			'sotien'			=> 'required|numeric',
			'nh'				=> 'required|AlphaDash',
			'email'				=> 'required|email|max:40'
		);

		$validator = Validator::make(Input::all(), $rules, $message);

		if ($validator->fails()) {
			# code...
			return Redirect::to('napgame')
						->withErrors($validator)
						->withInput(Input::all());
		} else {
			//store to transactions table
			$trans = new Mtransaction;
			//make the transID for this transaction
			$transid 				= $trans->makeStan();
			//create a record in DB
			$trans->type 			= 'GTOPUP';
			$trans->target_account	= Input::get('target_account');
			$trans->provider		= Input::get('nph');
			$trans->bankid			= Input::get('nh');
			$trans->email_sent		= Input::get('email');
			$trans->status 			= 99;
			$trans->bank_status		= null;
			$trans->bank_response	= null; //null because this phase is not calling to the bank gw
			$trans->amount			= Input::get('sotien');
			$trans->transid 		= $transid;
			$trans->save();
			//get the save ID of record
			$theID					= $trans->transid; //get the save ID of record
			Session::flash('message', 'Giao dịch đã được ghi nhận và đang xử lý');
			//calling to ebank gw
			$bankresult = $trans->deposit($transid, Input::get('sotien'), '1000', 'GUEST', Input::get('nh'));
			if (is_null($bankresult)) {
				$bank_log = null; //error while calling to ws -> no response from WS
				Session::flash('message', 'Giao dịch sang ngân hàng có lỗi. Hãy liên hệ quản trị để biết thêm chi tiết.');
				return Redirect::to('naptien');
			} else {
				//there is something returned from WS
				$bank_log	= 	'status: ' 			. $bankresult->status . 
								'. tranid: ' 		. $bankresult->tranid .
								'. responsecode: ' 	. $bankresult->responsecode .
								'. descriptionvn: ' . $bankresult->descriptionvn .
								'. URL: ' 			. (isset($bankresult->url) ? $bankresult->url : null);	
				//now update the trans to the banking status phase in DB event there is response or NOT
				$trans1					= Mtransaction::find($theID);
				$trans1->status 		= 98; //=>means bank ws is called, dont care about the response is NULL or NOT
				$trans1->bank_status	= $bankresult->responsecode;
				$trans1->bank_response 	= $bank_log;
				$trans1->save();
				//Session::flash('imessage', $bank_log); //log to the session
				//now check the result and return to cus
				if ($bankresult->responsecode == '00') {
					# code...
					//update transaction status to 97
					$trans1->status 	= 97; //->means calling to bank gw is successfully and user is redirected.
					$trans1->save();
					//redirect to the bank site
					return Redirect::to($bankresult->url);
				} else {
					Session::flash('message', 'Giao dịch sang ngân hàng có lỗi. Hãy liên hệ quản trị để biết thêm chi tiết.');
					return Redirect::to('naptien');
				}
			}
		}

	}

	

	public function create()
	{
		
	}









	//end class
}