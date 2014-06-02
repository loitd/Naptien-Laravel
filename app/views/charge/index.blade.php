@extends('layout.default')

@section('head')
	@parent
	<title>Charging</title> 
	<!-- My custome - Must be at lower than other default to allow overwrite -->
@stop



@section('content')
	<div id="showcase">
    <div class="container">
      <div class="section-header">
        
      </div>

      <div class="row">
      	
      	@if(Session::has('message'))
       		<p class="alert alert-info">{{Session::get('message')}}</p>
        @endif

        <div class="col-md-6 col-sm-12 col-xs-12 text-center"  id="slide-image">
          <a href="#" class="img-showcase"><img class="img-responsive img-rounded" src="img/01.jpg"></a>
        </div>

        <div class="col-md-6 col-sm-12 col-xs-12" id="form">
        	{{Form::open(array('url'=>URL::action("Charge@index"), 'method'=>'post', 'class'=>'form-horizontal', 'role'=>'form'))}}

        		<div class="form-group">
	              {{Form::label('email', 'Email', array('class'=>'col-sm-4 control-label'))}}
	              <div class="col-sm-8">
	              <input class="form-control" id="email" type="text" placeholder="{{Auth::user()->email}}" disabled>
	              </div>
	            </div>

	            <div class="form-group">
		            {{Form::label('point', 'Số điểm', array('class'=>'col-sm-4 control-label'))}}
		            <div class="col-sm-8">
		            <input class="form-control" id="point" type="text" placeholder="{{Auth::user()->point}}" disabled>
		            </div>
		        </div>
        	
	            <div class="form-group">
	            	{{Form::label('ncc', 'Nhà cung cấp', array('class'=>'col-sm-4 control-label'))}}
	            	<div class="col-sm-8">
	              	{{Form::select('ncc', array(	'VTT' => 'Viettel',
	              									'VNP' => 'Vinaphone', 
	              									'VMS' => 'MobiFone', 
	              									'VTC' => 'VTC Vcoin',
	              									'FPT' => 'FPT Gate',
	              									'ONC' => 'Oncash',
	              									'MGC' => 'Megacard'), 
	              							 Input::old('ncc'), 
	              							 array('class' => 'form-control')) }}
	              	<div style="color: red; font-style: italic;">{{$errors->first('ncc')}}</div>
	              	</div>
	            </div>

	            <div class="form-group">
	              {{Form::label('pincode', 'Mã thẻ', array('class'=>'col-sm-4 control-label'))}}
	              <div class="col-sm-8">
	              {{Form::text('pincode', Input::old('pincode'), array('class'=>'form-control', 'placeholder'=>'123456'))}}
	              <div style="color: red; font-style: italic;">{{$errors->first('pincode')}}</div>
	              </div>
	              
	            </div>

	            <div class="form-group">
	              {{Form::label('serial', 'Serial thẻ', array('class'=>'col-sm-4 control-label'))}}
	              <div class="col-sm-8">
	              {{Form::text('serial', Input::old('serial'), array('class'=>'form-control', 'placeholder'=>'AB123456'))}}
	              <div style="color: red; font-style: italic;">{{$errors->first('serial')}}</div>
	              </div>
	              
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
	            <div class="col-sm-offset-4 col-sm-8">
	            	<div style="color: red; font-style: italic;">{{$errors->first('captcha')}}</div>
	            </div>
	            </div>

	            <div class="col-sm-offset-4 col-sm-8">
	            {{ Form::submit('Thanh toán', array('class' => 'btn btn-primary')) }}
	            </div>

          	{{Form::close()}}

        </div><!-- col-md-6 -->

      </div><!-- end row -->
    </div><!-- end container -->
  </div><!-- end showcase -->
@stop