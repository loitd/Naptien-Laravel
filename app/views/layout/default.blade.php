<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <!-- Bootstrap -->  
    {{HTML::style(SITE_URL . '/css/bootstrap.min.css')}}
    {{HTML::style(SITE_URL . '/css/style.css')}}
    {{HTML::style(SITE_URL . '/css/blog.css')}}
    <!-- jQuery 1.10.2 -->
    <script src="{{SITE_URL}}/js/jquery-2.0.3.min.js"></script> 
    <!-- Include all compiled plugins (below), or include individual files as needed -->  
    <script src="{{SITE_URL}}/js/bootstrap.min.js"></script>   
    <link rel='shortcut icon' href='{{SITE_URL}}/img/N.ico' type='image/x-icon'/>   
    
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->  
    <!--[if lt IE 9]>  
      <script src="assets/js/html5shiv.js"></script>  
      <script src="assets/js/respond.min.js"></script>  
    <![endif]-->

    @section('head')
    	{{-- include your head here with title and custome css, js --}}
    @show
</head>
<body>
	<!-- Navigations -->
    <nav class="navbar" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
            <span><i class="fa fa-cloud-download"></i> Toggle me</span>
          </button>
          <a href="{{SITE_URL}}" class="navbar-brand"><strong><i class="fa fa-globe fa-spin fa-1x"></i> Nạp tiền</strong></a>
        </div>

        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li class="active"><a href="{{URL::route("naptien/index")}}"><i class="fa fa-phone fa-1x"></i> Nạp tiền điện thoại</a></li>
            <li><a href="{{URL::route("napgame/index")}}"><i class="fa fa-gamepad fa-1x"></i> Nạp tiền game</a></li>
            <li><a href="{{URL::action('Softpin@index')}}"><i class="fa fa-download fa-1x"></i> Mã thẻ</a></li>
            <li><a href="{{URL::action('Charge@index')}}"><i class="fa fa-download fa-1x"></i> Charging</a></li>
            
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-info fa-1x"></i> Hỗ trợ <b class="caret"></b>
              </a>
              <ul class="dropdown-menu">
                <li><a href="{{SITE_URL}}/huongdan"><i class="fa fa-camera-retro fa-1x"></i> Hướng dẫn</a></li>
                <li><a href="{{SITE_URL}}/tintuc"><i class="fa fa-info fa-1x"></i> Tin tức</a></li>
              </ul>
            </li><!-- dropdown -->

            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user fa-1x"></i> Tài khoản <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="{{URL::route('thanhvien/register')}}"><i class="fa fa-plus-circle fa-1x"></i> Đăng ký</a></li>
                @if(Auth::check())
                  <li><a href="{{URL::route('thanhvien/profile')}}"><i class="fa fa-user fa-1x"></i> {{Auth::user()->email}}</a></li>
                  <li><a href="{{URL::route('thanhvien/logout')}}"><i class="fa fa-plus-circle fa-1x"></i> Logout</a></li>
                @elseif(Auth::guest())
                  <li><a href="{{URL::route('thanhvien/index')}}"><i class="fa fa-user fa-1x"></i> Đăng nhập</a></li>
                @endif
              </ul>
            </li><!-- dropdown -->

          </ul>
        </div>
      </div><!-- end container -->
    </nav>

	@yield('content')

	<div id="feature" style="margin-top: 20px;">
    <div class="container">
      <div class="section-header">
        <h3 class="section-title"><i class="fa fa-info-circle fa-1x"></i> Tin tức nổi bật</h3>
      </div>

      
      <div class="row">
        
        @for($i = 0; $i < 3; $i++)
          <div class="col-md-4 col-sm-6 col-xs-12">
            <h5><a href="{{SITE_URL}}/tintuc/{{Wppost::get_footer_post($i)['post']->ID}}">
              {{Wppost::get_footer_post($i)['post']->post_title}}</a>
            </h5>
            <a href="#" class="img-showcase">
              <img class=" post-thumb img-responsive img-thumbnail" src="{{Wppost::get_footer_post($i)['thumb']}}">
            </a>
            <p style="font-style: italic;">
              {{Wppost::get_footer_post($i)['post']->post_excerpt}}
            </p>
          </div>
        @endfor

      </div><!-- end row -->
    </div><!-- end container -->
  </div><!-- end feature -->

  <footer id="footer" style="margin-top: 20px; border-top: 1px solid #ddd; padding-top: 20px; padding-bottom: 20px;">
    <div class="container">
      <div class="row credits">
        
        <div class="row social">
          <div class="col-sm-12">
            <a href="#"><i class="fa fa-adjust fa-1x"></i></a>
            <a href="#"><i class="fa fa-youtube-play fa-1x"></i></a>
            <a href="#"><i class="fa fa-google-plus fa-1x"></i></a>
            <a href="#"><i class="fa fa-facebook fa-1x"></i></a>
            <a href="#"><i class="fa fa-flickr fa-1x"></i></a>
          </div>  
        </div><!-- row social -->

        <div class="row copyright" style="font-size: 85%; font-weight: bold;">
          <div class="col-sm-12">
            2013 Nạp tiền. All rights reserved. Theme by <a href="http://www.loitd.com">Tran Duc Loi</a>. 
          </div>  
        </div><!-- row copyright -->
        
      </div><!-- credit -->
    </div>
  </footer>

  <!-- On the net -->
  <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
  <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300italic,400' rel='stylesheet' type='text/css'>

</body>
</html>