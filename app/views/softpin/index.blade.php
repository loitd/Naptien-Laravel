@extends('layout.default')

@section('head')
	@parent
	<title>Softpin Index</title> 
	<!-- My custome - Must be at lower than other default to allow overwrite -->
    <script src="{{SITE_URL}}/js/softpin.js"></script>
    
@stop



@section('content')
	<div id="showcase">
    <div class="container">
      <div class="section-header">
        
      </div>

      <div class="row">

        <div class="col-md-6 col-sm-12 col-xs-12 text-center"  id="slide-image">
          <a href="#" class="img-showcase"><img class="img-responsive img-rounded" src="img/03.jpg"></a>
        </div>

        <div class="col-md-6 col-sm-12 col-xs-12" id="form">
        	{{Form::open(array('url'=>URL::action('Softpin@index'), 'method'=>'post', 'class'=>'form-horizontal', 'role'=>'form'))}}
        	
	            <div class="form-group">
	            	{{Form::label('nph', 'Chọn loại thẻ', array('class'=>'col-sm-4 control-label'))}}
	            	<div class="col-sm-8">
	              	{{Form::select('nph', array(	'' 	=> 'Hãy chọn loại thẻ muốn mua'), 
	              							 Input::old('nph'), 
	              							 array('class' => 'form-control')) }}
	              	</div>
	              	<div style="color: red; font-style: italic;">{{$errors->first('nph')}}</div>
	            </div>

	            <div class="form-group">
	            	{{Form::label('sotien', 'Chọn mệnh giá', array('class'=>'col-sm-4 control-label'))}}
	              	<div class="col-sm-8">
	              	{{Form::select('sotien',	array(	''	 				=> 'Hãy chọn mệnh giá thẻ'), 
	              							Input::old('sotien'), 
	              							array('class' => 'form-control')) }}
	              	</div>
	              	<div style="color: red; font-style: italic;">{{$errors->first('sotien')}}</div>
	            </div>

	            <div class="form-group">
	              {{Form::label('quantity', 'Số lượng thẻ mua', array('class'=>'col-sm-4 control-label'))}}
	              <div class="col-sm-8">
	              {{Form::text('quantity', Input::old('quantity'), array('class'=>'form-control', 'placeholder'=>'1'))}}
	              </div>
	              <div style="color: red; font-style: italic;">{{$errors->first('quantity')}}</div>
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
	              {{Form::text('email', Input::old('email'), array('class'=>'form-control', 'placeholder'=>'abc@gmail.com'))}}
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

	            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

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