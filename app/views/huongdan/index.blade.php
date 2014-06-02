@extends('layout.default')

@section('head')
	@parent
	<title>Naptien Index</title> 
	<!-- My custome - Must be at lower than other default to allow overwrite -->
    
    <link href="{{SITE_URL}}/css/blog.css" rel="stylesheet" media="screen"><!-- My custome CSS -->
@stop



@section('content')
	<div class="container">
		<div class="row">
			<div class="col-sm-8 blog-main">
				<div class="blog-post">
					<h2 class="blog-post-title">{{$title}}</h2>
					{{$content}}
				</div><!-- .blog-post -->
			</div><!-- .blog-main -->

			<div class="col-sm-3 col-sm-offset-1 blog-sidebar">
          		<div class="sidebar-module sidebar-module-inset">
          			<h4>Về chúng tôi</h4>
            		<p>Etiam porta <em>sem malesuada magna</em> mollis euismod. Cras mattis consectetur purus sit amet fermentum. Aenean lacinia bibendum nulla sed consectetur.</p>
          		</div>

          		<div class="sidebar-module">
          			<ol class="list-unstyled">
	                	<li><a href="{{SITE_URL}}/huongdan"><i class="fa fa-plus-square"></i> Hướng dẫn</a></li>
	                	<li><a href="{{SITE_URL}}/tintuc"><i class="fa fa-plus-square"></i> Tin tức</a></li>
	                </ol>
          		</div>
          	</div><!-- .blog-sidebar -->
		</div>
	</div>
	
	
@stop