@extends('layout.default')

@section('head')
	@parent
	<title>Napgame Index</title> 
	<!-- My custome - Must be at lower than other default to allow overwrite -->
    
@stop



@section('content')
	<div id="showcase">
    <div class="container">
      <div class="section-header">
        
      </div>

      <div class="row">

        <div class="col-md-6 col-sm-12 col-xs-12 text-center"  id="slide-image">
          <a href="#" class="img-showcase"><img class="img-responsive img-rounded" src="img/02.jpg"></a>
        </div>

        <div class="col-md-6 col-sm-12 col-xs-12" id="form">
        	{{Form::open(array('url'=>URL::route("napgame/index"), 'method'=>'post', 'class'=>'form-horizontal', 'role'=>'form'))}}

        		<div class="form-group">
	              {{Form::label('target_account', 'Tài khoản game', array('class'=>'col-sm-4 control-label'))}}
	              <div class="col-sm-8">
	              {{Form::text('target_account', Input::old('target_account'), array('class'=>'form-control', 'placeholder'=>'abc_game'))}}
	              </div>
	              <div style="color: red; font-style: italic;">{{$errors->first('target_account')}}</div>
	            </div>
        	
	            <div class="form-group">
	            	{{Form::label('nph', 'Nhà phát hành', array('class'=>'col-sm-4 control-label'))}}
	            	<div class="col-sm-8">
	              	{{Form::select('nph', array(	'VTC' 		=> 'VTC', 
	              									'FPT' 		=> 'FPT GATE', 
	              									'VinaGame' 	=> 'VinaGame Zing', 
	              									'Net2E' 	=> 'Net2E Oncash'), 
	              							 Input::old('nph'), 
	              							 array('class' => 'form-control')) }}
	              	</div>
	            </div>

	            <div class="form-group">
	            	{{Form::label('sotien', 'Số tiền', array('class'=>'col-sm-4 control-label'))}}
	            	<div class="col-sm-8">
	              	{{Form::select('sotien', array(	'10000' => '10.000 VNĐ', 
	              									'20000' => '20.000 VNĐ', 
	              									'30000' => '30.000 VNĐ', 
	              									'50000' => '50.000 VNĐ',
	              									'100000' => '100.000 VNĐ',
	              									'200000' => '200.000 VNĐ',
	              									'300000' => '300.000 VNĐ',
	              									'500000'=> '500.000 VNĐ'), 
	              							 Input::old('sotien'), 
	              							 array('class' => 'form-control')) }}
	              	</div>	              	
	            </div>

	            <div class="form-group">
	            	{{Form::label('nh', 'Chọn ngân hàng', array('class'=>'col-sm-4 control-label'))}}
	            	<div class="col-sm-8">
	              	{{Form::select('nh',	array(	BANKID_VCB	 			=> 'Vietcombank', 
	              									BANKID_OCEANBANK	 	=> 'Ocean Bank', 
	              									BANKID_MARITIMEBANK	 	=> 'Maritime Bank', 
	              									BANKID_VIETINBANK		=> 'Vietinbank'), 
	              							Input::old('nh'), 
	              							array('class' => 'form-control')) }}
	              	</div>	              							
	            </div>

	            <div class="form-group">
	              {{Form::label('email', 'Email', array('class'=>'col-sm-4 control-label'))}}
	              <div class="col-sm-8">
	              {{Form::text('email', Input::old('email'), array('class'=>'form-control', 'placeholder'=>'abc@napthe.info'))}}
	              </div>	              
	              <div style="color: red; font-style: italic;">{{$errors->first('email')}}</div>
	            </div>

	            <div class="form-group">
            		{{Form::label('captcha', 'Mã bảo vệ', array('class'=>'col-sm-4 control-label'))}}
	              
	                <div class="col-sm-3">
	              		{{Form::text('captcha', Input::old(''), array('class'=>'form-control', 'placeholder'=>'Mã bảo vệ'))}}	
	              	</div>
	              
	              	<div class="col-sm-3">
	              		<img id="cap" src="{{$cap}}">
	              	</div>

	              	<div class="col-sm-2">
	              		<a id="rez" class="btn btn-info" href="#" 
	              		onclick="document.getElementById('cap').src= 'captcha?timestamp=' + new Date().getTime()">
	              		<i class="fa fa-refresh fa-1x"></i></a>
	              	</div>
	            </div>

	            <div class="form-group">
	            <div style="color: red; font-style: italic;">{{$errors->first('captcha')}}</div>
	            </div>

	            <div class="col-sm-offset-4 col-sm-8">
	            {{ Form::submit('Thanh toán', array('class' => 'btn btn-primary')) }}
	            </div>

          	{{Form::close()}}

          	<!-- will be used to show any messages -->
			@if (Session::has('message'))
				<div class="alert alert-info">{{ Session::get('message') }}</div>
			@endif

        </div><!-- col-md-6 -->

      </div><!-- end row -->
    </div><!-- end container -->
  </div><!-- end showcase -->
@stop