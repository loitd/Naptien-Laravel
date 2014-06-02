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
				@for ($i = 0; $i < count($post); $i++)
				    <div class="blog-post">
						<h4 class="blog-post-title">
							<a href="{{SITE_URL}}/tintuc/{{$post[$i]->ID}}">
								{{$post[$i]->post_title}}
							</a>
						</h4>
						<a href="{{SITE_URL}}/tintuc/{{$post[$i]->ID}}">
							<img class="post-thumb img-responsive img-thumbnail" 
							src="{{Wppost::get_post_thumb($post[$i]->ID)}}">
						</a>
						<p>{{$post[$i]->post_excerpt}}</p>
					</div><!-- .blog-post -->
				@endfor

				<div style="text-align: center;">{{$post->links()}}</div>
				
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