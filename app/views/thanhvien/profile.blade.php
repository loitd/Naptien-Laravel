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
        <h3 class="section-title"><i class="fa fa-plus-circle fa-1x"></i> Thông tin thành viên</h3>
      </div>

      <div class="row">
        @if(Session::has('message'))
          <p class="alert alert-info">{{Session::get('message')}}</p>
        @endif

        <div class="col-sm-offset-3 col-sm-6"><!-- col outside -->
          {{Form::open(array(
            "route"        => "thanhvien/profile",
            "autocomplete" => "off",
            "method"       => "POST",
            "class"        => "form-horizontal",
            "role"         => "form"
          ))}}

          <div class="form-group">
            {{Form::label('email', 'Email', array('class'=>'col-sm-4 control-label'))}}
            <div class="col-sm-8">
            <input class="form-control" id="email" type="text" placeholder="{{$email}}" disabled>
            </div>
          </div>
          
          <div class="form-group">
            {{Form::label('point', 'Số điểm', array('class'=>'col-sm-4 control-label'))}}
            <div class="col-sm-8">
            <input class="form-control" id="point" type="text" placeholder="{{$point}}" disabled>
            </div>
          </div>



          <div class="form-group">
            {{Form::label('password', 'Mật khẩu cũ', array('class'=>'col-sm-4 control-label'))}}
            <div class="col-sm-8">
            {{Form::password('password', array('class'=>'form-control'))}}
            <div style="color: red; font-style: italic;">{{$errors->first('password')}}</div>
            </div>
          </div>

          <div class="form-group">
            {{Form::label('newpassword', 'Mật khẩu mới', array('class'=>'col-sm-4 control-label'))}}
            <div class="col-sm-8">
            {{Form::password('newpassword', array('class'=>'form-control'))}}
            <div style="color: red; font-style: italic;">{{$errors->first('newpassword')}}</div>
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
                onclick="document.getElementById('cap').src= './../captcha?timestamp=' + new Date().getTime()">
                <i class="fa fa-refresh fa-1x"></i></a>
              </div>
          </div>

          <div class="form-group">
          <div class="col-sm-offset-4 col-sm-8">
            <div style="color: red; font-style: italic;">{{$errors->first('captcha')}}</div>
          </div>
          </div>
          
          <div class="col-sm-offset-4 col-sm-8">
          {{ Form::submit('Cập nhật', array('class' => 'btn btn-primary')) }}
          </div>
          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
          {{Form::close()}}
        </div><!-- end col out side -->
      </div><!-- end row -->
    </div><!-- end container -->
  </div><!-- end showcase -->
@stop