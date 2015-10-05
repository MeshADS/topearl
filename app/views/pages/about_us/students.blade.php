@extends("layout.master")
@include("_partials.html.default_meta")
@section("page") team @stop
@section("stylesheet")
	<!-- Add fancybox -->
	{{ HTML::style("assets/site/plugins/fancybox/source/jquery.fancybox.css") }}
	<!-- Optionally add helpers - button, thumbnail and/or media -->
	{{ HTML::style("assets/site/plugins/fancybox/source/helpers/jquery.fancybox-buttons.css") }}
	{{ HTML::style("assets/site/plugins/fancybox/source/helpers/jquery.fancybox-thumbs.css") }}
@stop
@section("bootdata")
	<!-- Boot data -->
@stop

@section("content")
	<!-- Begin header -->
	<div class="container-fluid nopadding parallax-window" style="background-image:url({{ (isset($header->image)) ? URL::to($header->image) : '' }});">
		@if(isset($header))
			<div class="bg-olay light black-background">&nbsp;</div>
		@endif
		<div class="col-md-12">
			<div class="container">
				<div class="col-md-12">
					<div class="content-section p-v-100">
						<div class="content-section-heading">
							<div class="content-section-title white-text">
								Our Students
							</div>
							@if(!empty($header->caption))
								<div class="content-section-caption white-text">
									{{ $header->caption }}
								</div>
							@endif
							<div class="content-section-divider white-background">&nbsp;</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End header -->

	<div class="colored-line">&nbsp;</div>
	<!-- Begin page content -->
	<div class="container-fluid nopadding">
		
		<!-- Begin intro -->
		<div class="col-md-12 nopadding alt-background">
			<div class="container nopadding white-background">
				<div class="col-md-12">

					<div class="content-section p-v-50">

						@if(isset($pagedata["students-page-list"]["intro"]))

							<span class="font-bg size-128 white-background alt-text bold t-70 r-70"><i class="fa fa-heart-o"></i></span>
							<span class="font-bg size-128 white-background alt-text bold b-40 l-20 rotate-45deg"><i class="fa fa-soccer-ball-o"></i></span>

							<div class="content-section-body p-h-40">
								<div class="row">
									{{ (isset($pagedata["students-page-list"]["intro"]["body_formatted"])) ? $pagedata["students-page-list"]["intro"]["body_formatted"] : '' }}					
								</div>
							</div>

						@endif

					</div>

				</div>
			</div>
		</div>
		<!-- End intro -->
		<!-- Begin Students Section -->
		<div class="col-md-12 nopadding white-background">
			<div class="container nopadding alt-background">
				<div class="col-md-12">
					<span class="font-bg size-128 blue-text light t-15 l-15 rotate-315deg z-index-0"><i class="fa fa-paw"></i></span>

					<div class="content-section">

						<div class="content-section-heading">
							<div class="content-section-title">Students</div>
							<div class="content-section-caption">Our Student Classification System</div>
							<div class="content-section-divider blue-background">&nbsp;</div>
						</div>

						<div class="content-section-body p-t-40 p-b-0">
							@include("_partials.html.studentCarousel2")
						</div>

					</div>

				</div>
			</div>
		</div>
		<!-- End Students Section -->

	</div>
	<!-- End page content -->

@stop

@section("javascript")
	{{ HTML::script('assets/site/plugins/fancybox/lib/jquery.mousewheel-3.0.6.pack.js') }}
	{{ HTML::script('assets/site/plugins/fancybox/source/jquery.fancybox.pack.js') }}
	{{ HTML::script('assets/site/plugins/fancybox/source/helpers/jquery.fancybox-buttons.js') }}
	<!-- Add mousewheel plugin (this is optional) -->
	{{ HTML::script('assets/site/plugins/fancybox/source/helpers/jquery.fancybox-media.js') }}
	{{ HTML::script('assets/site/plugins/fancybox/source/helpers/jquery.fancybox-thumbs.js') }}
	<script type="text/javascript">
		$(function(){
			studentJS.init();
		});
	</script>
@stop