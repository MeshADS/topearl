<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no'>
		<meta name="author" content='@yield("author")'>
		<meta name="description" content='@yield("description")'>
		<title>
			@yield("title")
		</title>
		<!-- Core CSS -->
		{{ HTML::style("assets/site/plugins/font-awesome/css/font-awesome.css") }}
		{{ HTML::style("assets/site/plugins/owlcarousel/assets/owl.carousel.css") }}
		{{ HTML::style("assets/site/plugins/animatecss/animate.css") }}
		{{ HTML::style("assets/site/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css") }}
		<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,400italic,500,700' rel='stylesheet' type='text/css'>
		<!-- Add fancybox -->
		{{ HTML::style("assets/site/plugins/fancybox/source/jquery.fancybox.css") }}
		<!-- Optionally add helpers - button, thumbnail and/or media -->
		{{ HTML::style("assets/site/plugins/fancybox/source/helpers/jquery.fancybox-buttons.css") }}
		{{ HTML::style("assets/site/plugins/fancybox/source/helpers/jquery.fancybox-thumbs.css") }}
		<!-- Frontend CSS -->
		@if(App::environment() == 'local')
			{{ HTML::style("assets/site/build/style.css") }}
		@else
			{{ HTML::style("assets/site/css/style.min.css") }}
		@endif
		<!-- Page Level CSS -->
	@yield("stylesheet")
		<script type="text/javascript">
			window.Site = window.Site || {};
			// Config
			Site.Config = Site.Config || {};
			Site.Config.env = "{{ App::environment() }}";
			Site.Config.url = "{{ Config::get('app.url') }}";
			Site.Config.token = "{{ Session::token() }}";
			// Data
			Site.data = Site.data || {};
		</script>
		<!-- Boot Data -->
		@yield("bootdata")
	</head>
	<body>
		<!-- Begin notification -->
		@section("top-notification")
			@if(isset($view_message))
				@if($view_message["type"] == "page")
					<p class='alert alert-{{ $view_message["level"] }} alert-dismissable m-v-0 flat-it text-center'>
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						{{ $view_message["message"] }}
					</p>
				@endif
			@endif
		@stop
		<!-- End notification -->
		<!-- Begin Header -->
			@include("_partials.html.header")
		<!-- End Header -->

		<!-- Begin Content -->
		<div class="site-container @yield('page')">
			@yield("content")
		</div>
		<!-- End Content -->

		<!-- Begin Footer -->
			@include("_partials.html.footer")
		<!-- End Footer -->

		<!-- Core Javascript -->
		{{ HTML::script("assets/site/plugins/jquery/jquery-1.9.1.min.js") }}
		{{ HTML::script("assets/site/plugins/jquery.easing/jquery.easing.min.js") }}
		{{ HTML::script("assets/site/plugins/bootstrap/javascripts/bootstrap.min.js") }}
		{{ HTML::script("assets/site/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") }}
		{{ HTML::script("assets/site/plugins/owlcarousel/owl.carousel.min.js") }}
		{{ HTML::script("assets/site/plugins/scrollReveal/scrollReveal.min.js") }}
		{{ HTML::script("assets/site/plugins/parallax.js/parallax.min.js") }}
		{{ HTML::script("assets/site/plugins/masonry/masonry.pkgd.min.js") }}
		{{ HTML::script("assets/site/plugins/imagesloaded/imagesloaded.pkgd.min.js") }}
		{{ HTML::script("assets/site/plugins/moment/moment.min.js") }}
		{{ HTML::script("assets/site/plugins/livestamp/livestamp.min.js") }}
		{{ HTML::script('assets/site/plugins/fancybox/lib/jquery.mousewheel-3.0.6.pack.js') }}
		{{ HTML::script('assets/site/plugins/fancybox/source/jquery.fancybox.pack.js') }}
		{{ HTML::script('assets/site/plugins/fancybox/source/helpers/jquery.fancybox-buttons.js') }}
		{{ HTML::script('assets/site/plugins/fancybox/source/helpers/jquery.fancybox-media.js') }}
		{{ HTML::script('assets/site/plugins/fancybox/source/helpers/jquery.fancybox-thumbs.js') }}
		{{ HTML::script('assets/site/plugins/jquery-validation/jquery.validate.min.js') }}
		<!-- Frontend Javascript -->
		@if(App::environment() == 'local')
			{{ HTML::script("assets/site/build/dist.js") }}
		@else
			{{ HTML::script("assets/site/js/script.min.js") }}
		@endif
		<!-- Page level javascript -->
		@yield("javascript")
	</body>
</html>