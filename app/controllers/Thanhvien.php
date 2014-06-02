<?php 
/*
 * @package		Loitd
 * @author		Tran Duc Loi
 * @copyright	Copyright (c) 2013 - 2014, Loitd, Inc.
 * @license		http://www.opensource.org/licenses/mit-license.php
 * @link		http://loitd.com
 * @since		Version 1.0
*/

class Thanhvien extends BaseController 
{
	
	public function __construct() {
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->beforeFilter('auth', array('only'=>array('viewprofile', 'updateprofile', 'logout')));
	}

	/*
		The login page
	*/
	public function index()
	{
		///only allow login page if guest
		if(Auth::guest()) {
			return View::make('thanhvien.index', array('cap'=>URL::route("captcha")));
		} else {
			//if not redirect to profile page
			return Redirect::to(URL::action('Thanhvien@viewprofile'));
		}
		
	}

	//do the login here
	public function store()
	{
		//validate inputs
		$message = array(
			'email.required' 	=> 'Email là trường bắt buộc.',
			'email' 			=> 'Email không hợp lệ.',
			'password.required' => 'Mật khẩu là trường bắt buộc.',
			'captcha.required' 	=> 'Mã bảo vệ là trường bắt buộc.',
			'captcha.captcha' 	=> 'Mã bảo vệ không khớp.',
			'alpha_dash' 		=> 'Dữ liệu không hợp lệ vì có các ký tự đặc biệt.',
			'alpha_num' 		=> 'Dữ liệu không hợp lệ.',
			'between' 			=> 'Dữ liệu phải có từ :min đến :max ký tự.',
		);
		# receive input and begin validate
		$rules = array(
			'captcha'	=> 'required|captcha|AlphaDash',
			'email'		=> 'required|email',
			'password'	=> 'required|AlphaDash|between:6,12',
		);

		$validator = Validator::make(Input::all(), $rules, $message);

		if ($validator->fails()) {
			# code...
			return Redirect::to(URL::route('thanhvien/index'))
						->withErrors($validator)
						->withInput(Input::all());
		} else {
			//now check login credentials
			$cred = array(
				'email'		=> Input::get('email'),
				'password'	=> Input::get('password'),
				'active'	=> 1, //user must be activated before
			);
			if (Auth::attempt($cred, false)) //do the login and NOT remember (it safer)
			{
				// the auth.attempt event will be fired
				//redirect to charge page
				//var_dump($cred); die();
				return Redirect::to(URL::action('Charge@index'));
				//die('logged');
			} else {
				//invalid
				$messageBag = new Illuminate\Support\MessageBag;
				$messageBag->add('loginfail', 'Email/Mật khẩu không đúng');
				return Redirect::to(URL::route('thanhvien/index'))
							->withErrors($messageBag)
							->withInput(Input::all());
			}


		}
	}

	/*
		The register page
	*/

	public function register()
	{
		return View::make('thanhvien.reg', array('cap'=>URL::route("captcha")));
	}

	public function doreg()
	{
		//validate inputs
		$message = array(
			'email.required' 	=> 'Email là trường bắt buộc.',
			'email' 			=> 'Email không hợp lệ.',
			'unique' 			=> 'Email đã có trên hệ thống.',
			'password.required' => 'Mật khẩu là trường bắt buộc.',
			'password.confirmed' => 'Mật khẩu không giống với đã nhập trước đó.',
			'password_confirmation.required' => 'Mật khẩu là trường bắt buộc.',
			'captcha.required' 	=> 'Mã bảo vệ là trường bắt buộc.',
			'captcha.captcha' 	=> 'Mã bảo vệ không khớp.',
			'alpha_dash' 		=> 'Dữ liệu không hợp lệ vì có các ký tự đặc biệt.',
			'alpha_num' 		=> 'Dữ liệu không hợp lệ.',
			'between' 			=> 'Dữ liệu phải có từ :min đến :max ký tự.',
		);
		# receive input and begin validate
		$rules = array(
			'captcha'	=> 'required|captcha|AlphaDash',
			'email'		=> 'required|email|unique:users',
			'password'	=> 'required|AlphaDash|between:6,12|confirmed',
			'password_confirmation' => 'required|AlphaDash|between:6,12'
		);

		$validator = Validator::make(Input::all(), $rules, $message);

		if ($validator->fails()) {
			# code...
			return Redirect::to(URL::route('thanhvien/register'))
						->withErrors($validator)
						->withInput(Input::all());
		} else {
			//validation ok
			$email 			= Input::get('email');
			$ph1 			= array($email, 0); //current point = 0
			$a 				= $this->make_activatecode(8);
			//
			$u = new User;
			$u->email 		= Input::get('email');
			$u->password 	= Hash::make(Input::get('password'));
			$u->active 		= 0; //not active
			$u->activate_code	= $a;
			$u->point 		= 0;
			$u->ph1			= Secu::lencode($ph1);
			$u->save();
			//send email to user 
			Mail::send('emails.auth.activate', 
						array('email'=>$email, 'link'=>URL::route('thanhvien/activate', $a)), 
						function($message) use ($email) {
							$message->from(ADMIN_EMAIL, SITE_NAME)
									->to($email)
									->bcc(ADMIN_EMAIL)
									->subject("Hãy kích hoạt tài khoản đăng ký tại " . SITE_NAME);
						});

			//return to login page with message
			return Redirect::to(URL::route('thanhvien/index'))->with('message', 'Bạn đã đăng ký thành công. Hãy kích hoạt tài khoản với đường dẫn đã gửi về mail của bạn và đăng nhập tại đây.');
		}
	}

	/*
		Activate
	*/
	public function activate($code)
	{
		$u 	= User::where('activate_code', '=', $code)->where('active', '=', 0);

		if($u->count()){
			$u 	= $u->first();
			$u->active 	= 1;
			$u->activate_code = '';
			if ($u->save()) {
				return Redirect::to(URL::route('thanhvien/index'))->with('message', 'Tài khoản của bạn đã được kích hoạt. Hãy đăng nhập tại đây.');								
			}
		} else {
			return Redirect::to(URL::route('thanhvien/index'))->with('message', 'Không thể kích hoạt tài khoản của bạn. Vui lòng thử lại.');
		}
	}

	/*
		The view profile page
	*/

	public function viewprofile()
	{
		//onlu accessible if logged in
		//get the curent point
		//1. Check data interg
		$email = Auth::user()->email;
		if(Secu::checkdata())
		{
			//now do
			$x = User::where('email','=',$email)->first();
			$data = array(
				'point'	=> $x->point,
				'email'	=> $email,
				'cap'	=> URL::route("captcha"),
			);
			return View::make('thanhvien.profile', $data);
		} else {
			return Redirect::to(URL::action('Thanhvien@viewprofile'))
				->with('message', 'Lỗi: Đã có lỗi không toàn vẹn dữ liệu xảy ra. Thông tin cảnh báo đã được gửi tới ban quản trị để kiểm tra tài khoản của bạn.');
		}
	}

	public function updateprofile()
	{
		//validate inputs
		$message = array(
			'password.required' 	=> 'Mật khẩu là trường bắt buộc.',
			'newpassword.required' 	=> 'Mật khẩu mới là trường bắt buộc.',
			'captcha.required' 		=> 'Mã bảo vệ là trường bắt buộc.',
			'captcha.captcha' 		=> 'Mã bảo vệ không khớp.',
			'alpha_dash' 			=> 'Dữ liệu không hợp lệ vì có các ký tự đặc biệt.',
			'alpha_num' 			=> 'Dữ liệu không hợp lệ.',
			'between' 				=> 'Dữ liệu phải có từ :min đến :max ký tự.',
		);
		# receive input and begin validate
		$rules = array(
			'captcha'		=> 'required|captcha|AlphaDash',
			'password'		=> 'required|AlphaDash|between:6,12',
			'newpassword' 	=> 'required|AlphaDash|between:6,12'
		);

		$validator = Validator::make(Input::all(), $rules, $message);

		if ($validator->fails()) {
			# code...
			return Redirect::to(URL::route('thanhvien/profile'))
						->withErrors($validator)
						->withInput(Input::all());
		} else {
			//now begin update
			$email = Auth::user()->email;
			$x = User::where('email','=',$email)->first();
			
			if(Hash::check(Input::get('password'),$x->password)) {
				$x->password = Hash::make(Input::get('newpassword'));
				$x->save();
				return Redirect::to(URL::action('Thanhvien@viewprofile'))->with('message', 'Đã cập nhật mật khẩu mới.');
			} else {
				return Redirect::to(URL::action('Thanhvien@viewprofile'))->with('message', 'Mật khẩu không đúng.');
			}
		}
	}

	/*
		Logout
	*/
	public function logout()
	{
		//Session::flush();
		Auth::logout();
		return Redirect::to(URL::action('Thanhvien@index'))->with('message', 'Bạn đã đăng xuất.');
	}

	private function make_activatecode($length = 6)
	{
		$c 	= '1234567890abcdefghiklopqrstuvABCDEFGHIJKLMOPQRSTUV_';
		$r 	= '';
		for ($i=0; $i < $length; $i++) { 
			$r .= $c[rand(0, strlen($c)-1)];
		}
		return $r;
	}


	
}