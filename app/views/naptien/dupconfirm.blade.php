@extends('layout.default')

@section('head')
	@parent
	<title>Naptien Index</title> 
@stop



@section('content')
	<div id="showcase">
    <div class="container">
      <div class="section-header">
        <h3 class="section-title"><i class="fa fa-info-circle fa-1x"></i> Thông tin giao dịch</h3>
      </div>

      <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12"  id="slide-image">
          <p>{{{$what}}}
          </p>
          <p class="alert alert-info">
          Hãy đọc kỹ <a href="#">hướng dẫn</a> và <a href="#">điều khoản sử dụng</a> trước khi tiến hành giao dịch.</p>
        </div>

      </div><!-- end row -->
    </div><!-- end container -->
  </div><!-- end showcase -->
@stop