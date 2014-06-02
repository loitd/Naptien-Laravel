@extends('layout.default')

@section('head')
	@parent
	<title>Naptien Index</title> 
	<!-- My custome - Must be at lower than other default to allow overwrite -->
    <script src="{{SITE_URL}}/js/confirm.js"></script>
@stop



@section('content')
	<div id="showcase">
    <div class="container">
      <div class="section-header">
        <h3 class="section-title"><i class="fa fa-info-circle fa-1x"></i> Thông tin giao dịch</h3>
      </div>

      <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12"  id="slide-image">
          <p>Sau đây là thông tin giao dịch cần được xử lý. 
          Hãy kiểm tra kỹ và bấm nút <b>"Cập nhật & Tiếp tục"</b> để thực hiện giao dịch. 
          </p>
          <p class="alert alert-info">
          Lưu ý bạn không được <b>refresh</b> trang này vì trang web này chỉ hiển thị <b>một lần duy nhất</b> cho một giao dịch.
          Hãy đọc kỹ <a href="#">hướng dẫn</a> và <a href="#">điều khoản sử dụng</a> trước khi tiến hành giao dịch.</p>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12" id="form">
        	{{Form::open(array('url'=>'banking', 'method'=>'post', 'class'=>'form-horizontal'))}}

        		{{Form::hidden('LToken', Secu::maketoken(), array('class'=>'form-control'))}}
        		
        		<div class="form-group">
	              {{Form::label('x', 'Mã giao dịch', array('class'=>'col-sm-2 control-label'))}}
	              <div class="col-sm-10"><p class="form-control-static">{{{$transid}}}</p></div>
	            </div>

	            <div class="form-group">
	              {{Form::label('x', 'Loại giao dịch', array('class'=>'col-sm-2 control-label'))}}
	              <div class="col-sm-10"><p class="form-control-static">{{{$transtype}}}</p></div>
	            </div>

	            <div class="form-group">
	              {{Form::label('sdt', 'Tài khoản nhận tiền', array('class'=>'col-sm-2 control-label'))}}
	              <div class="col-sm-3">
	              	{{Form::text('sdt', $sdt, array('class'=>'form-control'))}}
	              	<div style="color: red; font-style: italic;">{{$errors->first('sdt')}}</div>
	              </div>
	            </div>

	            <div class="form-group">
	              {{Form::label('nph', 'Nhà phát hành', array('class'=>'col-sm-2 control-label'))}}
	              <div class="col-sm-10"><p class="form-control-static">{{{$nph}}}</p></div>
	            </div>
        	
	            <div class="form-group">
	              {{Form::label('email', 'Email nhận thông tin', array('class'=>'col-sm-2 control-label'))}}
	              <div class="col-sm-3">
	              	{{Form::text('email', $email, array('class'=>'form-control'))}}
	              	<div style="color: red; font-style: italic;">{{$errors->first('email')}}</div>
	              </div>
	            </div>

	            <div class="form-group">
	              {{Form::label('x', 'Ngân hàng đã giao dịch', array('class'=>'col-sm-2 control-label'))}}
	              <div class="col-sm-10"><p class="form-control-static">{{{$banked}}}</p></div>
	            </div>

	            <div class="form-group">
	              {{Form::label('x', 'Trạng thái giao dịch', array('class'=>'col-sm-2 control-label'))}}
	              <div class="col-sm-10"><p class="form-control-static">{{{$status}}}</p></div>
	            </div>

	            {{-- Hidden input in form --}}
	            {{Form::hidden('transid', $transid, array('class'=>'form-control'))}}
	            {{Form::hidden('responCode', $responCode, array('class'=>'form-control'))}}
	            {{Form::hidden('mac', $mac, array('class'=>'form-control'))}}



	            {{ Form::submit('Cập nhật & Tiếp tục', array('class' => 'col-sm-offset-2 btn btn-primary')) }}

	            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

          	{{Form::close()}}

          	<!-- will be used to show any messages -->
			@if (Session::has('message'))
				<!-- <div class="alert alert-info">{{-- Session::get('message') --}}</div> -->
			@endif

        </div><!-- col-md-6 -->

      </div><!-- end row -->
    </div><!-- end container -->
  </div><!-- end showcase -->
@stop