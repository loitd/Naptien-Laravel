<?php 
/*
 * @package		Loitd
 * @author		Tran Duc Loi
 * @copyright	Copyright (c) 2013 - 2014, Loitd, Inc.
 * @license		http://www.opensource.org/licenses/mit-license.php
 * @link		http://loitd.com
 * @since		Version 1.0
*/

class Softpin extends BaseController{
	
	public function index()
	{
		//$data = array();
		return View::make('softpin.index', array('cap'=>URL::route("captcha")));
	}

	public function show($what)
	{
		if ($what == 'providers') {
			$provider = array();
			$providers = Provider::where('status', '=', 1)->distinct()->get(array('provider', 'provider_name'));
			foreach ($providers as $p) {
				$provider[$p->provider]=$p->provider_name;
			}
			
			return json_encode($provider);

		} else {
			$amount = array();
			$amounts = Provider::where('provider', '=', $what)->get();
			foreach ($amounts as $a => $value) {
				$amount[$value->productid] = $value->product_name;
			}
			return json_encode($amount);
		}
	}

	public function store()
	{
		//
		$message = array(
			'nph.required' 		=> 'Loại thẻ là trường bắt buộc.',
			'sotien.required' 	=> 'Số tiền là trường bắt buộc.',
			'nh.required' 		=> 'Ngân hàng là trường bắt buộc.',
			'email.required' 	=> 'Email là trường bắt buộc.',
			'quantity.required' => 'Số lượng thẻ là trường bắt buộc.',
			'captcha.required' 	=> 'Mã bảo vệ là trường bắt buộc.',
			'captcha.captcha' 	=> 'Mã bảo vệ không khớp.',
			'quantity.numeric'	=> 'Số lượng thẻ mua bắt buộc là số.',
			'email'				=> 'Dữ liệu không đúng định dạng email.',
			'between'			=> 'Độ dài phải nằm trong khoảng :min đến :max',
			'quantity.max'		=> 'Số lượng thẻ mua một lần không được vượt quá :max.',
			'quantity.min'		=> 'Số lượng thẻ mua một lần phải lớn hơn hoặc bằng :min.',
			'email.max'			=> 'Độ dài ký tự email không được vượt quá :max.',
			'alpha_dash' 		=> 'Dữ liệu không hợp lệ.',
			'alpha_num' 		=> 'Dữ liệu không hợp lệ.',
		);
		# receive input and begin validate
		$rules = array(
			'captcha'	=> 'required|captcha|AlphaDash',
			'nph'		=> 'required|AlphaDash',
			'sotien'	=> 'required|AlphaDash',
			'quantity'	=> 'required|numeric|min:1|max:10',
			'nh'		=> 'required|AlphaDash',
			'email'		=> 'required|email|max:40'
		);

		$validator = Validator::make(Input::all(), $rules, $message);

		if ($validator->fails()) {
			# code...
			return Redirect::to('softpin')
						->withErrors($validator)
						->withInput(Input::all());
		} else {
			//first store data in the DBs
			$t 	= new Mtransaction;
			//create the transid
			$transid	= $t->makeStan(); //this ID will be use to get trans infor
			//calculate the total amount to charge
			$p 					= Provider::find(Input::get('sotien'));
			$amount				= $p->menhgia * Input::get('quantity');
			//store the record
			$t->transid			= $transid;
			$t->type 			= 'SOFTPIN';
			$t->target_account	= NULL;
			$t->provider 		= Input::get('nph');
			$t->bankid			= Input::get('nh');
			$t->email_sent		= Input::get('email');
			$t->status 			= 99;
			$t->product_request	= Input::get('sotien');
			$t->quantity_request= Input::get('quantity');
			$t->amount 			= $amount;
			$t->save();
			//calling to ebank gw
			$bankresult = $t->deposit($transid, $amount, '0', 'GUEST', Input::get('nh'), URL_RESPONSES);
			if (is_null($bankresult)) {
				$bank_log = null; //error while calling to ws -> no response from WS
				Session::flash('message', '0. Giao dịch sang ngân hàng có lỗi. Hãy liên hệ quản trị để biết thêm chi tiết.');
				return Redirect::to('naptien');
			} else {
				//there is something returned from WS
				$bank_log	= 	'status: ' 			. $bankresult->status . 
								'. tranid: ' 		. $bankresult->tranid .
								'. responsecode: ' 	. $bankresult->responsecode .
								'. descriptionvn: ' . $bankresult->descriptionvn .
								'. URL: ' 			. (isset($bankresult->url) ? $bankresult->url : null);	
				//now update the trans to the banking status phase in DB event there is response or NOT
				$trans1					= Mtransaction::find($transid);
				//return $theID . var_dump($trans1);
				//die();
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
					return Redirect::to('softpin');
				}
			}
		} //if validator valid
	}

	//create
	public function create()
	{
		
	}

}