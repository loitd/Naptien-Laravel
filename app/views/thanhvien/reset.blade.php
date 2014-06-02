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
        <h3 class="section-title"><i class="fa fa-user fa-1x"></i> Quên mật khẩu?</h3>
      </div>

      <div class="row">
        <div class="col-sm-offset-3 col-sm-6"><!-- col outside -->
          {{Form::open(array(
          	"route"        => "thanhvien/index",
          	"autocomplete" => "off",
            "method"       => "POST",
            "class"        => "form-horizontal",
            "role"         => "form"
          ))}}

          <div class="form-group">
            {{Form::label('email', 'Email', array('class'=>'col-sm-4 control-label'))}}
            <div class="col-sm-8">
            {{Form::text('email', Input::old('email'), array('class'=>'form-control', 'placeholder'=>'email@email.com'))}}
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
          
          <div class="form-group">
          <div style="color: red; font-style: italic;">{{$errors->first('resetfail')}}</div>
          </div>

          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

          <div class="col-sm-offset-4 col-sm-8">
          {{ Form::submit('Login', array('class' => 'btn btn-primary')) }}
          </div>


          {{Form::close()}}
        </div><!-- end col out side -->
      </div><!-- end row -->
    </div><!-- end container -->
  </div><!-- end showcase -->
@stop