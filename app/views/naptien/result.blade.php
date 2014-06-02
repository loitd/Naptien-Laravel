@extends('layout.default')

@section('head')
	@parent
	<title>Naptien Index</title> 
	<!-- My custome - Must be at lower than other default to allow overwrite -->
@stop



@section('content')
	<div id="showcase">
    <div class="container">
      <div class="section-header">
        <h3 class="section-title"><i class="fa fa-info-circle fa-1x"></i> Thông tin giao dịch</h3>
      </div>

      <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12"  id="slide-image">
          <p>Sau đây là kết quả thực hiện giao dịch của quý khách. 
          Một email với nội dung tương đương đã được gửi đến theo địa chỉ quý khách đã cung cấp. 
          Hãy giữ lại email này trong trường hợp có khiếu nại, chúng tôi cần được cung cập thông tin để xử lý phản ánh của quý khách.
          </p>
          <p class="alert alert-info">
          Hãy đọc kỹ <a href="#">hướng dẫn</a> và <a href="#">điều khoản sử dụng</a> trước khi tiến hành giao dịch.</p>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12" id="form">
        	{{Form::open(array('url'=>'', 'method'=>'post', 'class'=>'form-horizontal'))}}
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
		           	<div class="col-sm-10"><p class="form-control-static">{{{$sdt}}}</p></div>
	            </div>

	            <div class="form-group">
	              {{Form::label('nph', 'Nhà phát hành', array('class'=>'col-sm-2 control-label'))}}
		           	<div class="col-sm-10"><p class="form-control-static">{{{$nph}}}</p></div>
	            </div>
        	
	            <div class="form-group">
	              {{Form::label('email', 'Email nhận thông tin', array('class'=>'col-sm-2 control-label'))}}
	              	<div class="col-sm-10"><p class="form-control-static">{{{$email}}}</p></div>
	            </div>

	            <div class="form-group">
	              {{Form::label('x', 'Ngân hàng đã giao dịch', array('class'=>'col-sm-2 control-label'))}}
	              <div class="col-sm-10"><p class="form-control-static">{{{$banked}}}</p></div>
	            </div>

	            <div class="form-group">
	              {{Form::label('x', 'Kết quả giao dịch ngân hàng', array('class'=>'col-sm-2 control-label'))}}
	              <div class="col-sm-10"><p class="form-control-static">{{{$bankresult}}}</p></div>
	            </div>

	            <div class="form-group">
	              {{Form::label('x', 'Kết quả giao dịch Topup', array('class'=>'col-sm-2 control-label'))}}
	              <div class="col-sm-10"><p class="form-control-static">{{{$topupresult}}}</p></div>
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