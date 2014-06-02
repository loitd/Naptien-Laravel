@extends('layout.default')

@section('head')
	@parent
	<title>Errors page</title> 
	<!-- My custome - Must be at lower than other default to allow overwrite -->
@stop



@section('content')
	<div id="showcase">
    <div class="container">
      <div class="section-header">
        <h3 class="section-title" style="color: orange;">
          <i class="fa fa-exclamation-circle fa-1x"></i> Có lỗi xảy ra trong quá trình xử lý
        </h3>
      </div>

      <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12"  id="slide-image">
          <p style="color: red;">{{Session::get('message')}}</p>
          <p class="alert alert-info">
          Hãy đọc kỹ <a href="#">hướng dẫn</a> và <a href="#">điều khoản sử dụng</a> trước khi tiến hành giao dịch.</p>
        </div>

      </div><!-- end row -->
    </div><!-- end container -->
  </div><!-- end showcase -->
@stop