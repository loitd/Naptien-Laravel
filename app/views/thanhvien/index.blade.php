@extends('layout.default')

@section('head')
	@parent
	<title>Thành viên</title> 
	<!-- My custome - Must be at lower than other default to allow overwrite -->
@stop



@section('content')
	<div id="showcase">
    <div class="container">
      <div class="section-header">
        <h3 class="section-title"><i class="fa fa-user fa-1x"></i> Đăng nhập</h3>
      </div>

      <div class="row">
      
        @if(Session::has('message'))
          <p class="alert alert-info">{{Session::get('message')}}</p>
        @endif

        <div class="col-sm-offset-3 col-sm-6"><!-- col outside -->
          {{Form::open(array(
          	"route"        => "thanhvien/index",
          	"autocomplete" => "off",
            "method"       => "POST",
            "class"        => "form-horizontal",
            "role"         => "form"
          ))}}

          <div class="form-group">
            <div class="col-sm-12">
            {{Form::text('email', Input::old('email'), array('class'=>'form-control', 'placeholder'=>'Email'))}}
            <div style="color: red; font-style: italic;">{{$errors->first('email')}}</div>
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-12">
            {{Form::password('password', array('class'=>'form-control', 'placeholder'=>'Mật khẩu'))}}
            <div style="color: red; font-style: italic;">{{$errors->first('password')}}</div>
            </div>
            
          </div>

          <div class="form-group">
          
              <div class="col-sm-7">
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
            <div class="col-sm-12">
              <div style="color: red; font-style: italic;">{{$errors->first('captcha')}}</div>
              <div style="color: red; font-style: italic;">{{$errors->first('loginfail')}}</div>
            </div>
          </div>

          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
          
          <div class="form-group">
            <div class="col-sm-12">
            {{ Form::submit('Login', array('class' => 'btn btn-primary')) }}
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-12">
              <a href="{{URL::route('thanhvien/getremind')}}">Quên mật khẩu?</a>
              <a href="{{URL::route('thanhvien/register')}}">Chưa có tài khoản?</a>
            </div>
          </div>

          

          {{Form::close()}}
        </div><!-- end col out side -->
      </div><!-- end row -->
    </div><!-- end container -->
  </div><!-- end showcase -->
@stop