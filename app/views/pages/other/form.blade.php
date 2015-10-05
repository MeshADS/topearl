@extends("layout.master")
@include("_partials.html.default_meta")
@section("page") {{ $page }} @stop
@section("stylesheet")
	<!-- Stylesheet -->
@stop
@section("bootdata")
	<script type="text/javascript">
		window.Site = window.Site || {};
		//Bootstrap data
		Site.data = Site.data || {};
		Site.data.form = {{ json_encode($form->toArray()) }};
	</script>
@stop

@section("content")

	@include("_partials.html.page_header")

	<div class="colored-line">&nbsp;</div>
	<!-- Begin page content -->
	<div class="container-fluid nopadding">
		<div class="form_holder">
			
			@if(isset($view_message) && $view_message["type"] == "application")
				@section("top-notification")
					<p class='alert alert-{{ $view_message["level"] }} alert-dismissable m-v-0 flat-it text-center'>
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						{{ $view_message["message"] }}
					</p>
				@stop
			@endif

			{{ Form::open(["url" => Request::url(), "role"=>"form", "method"=>"post", "class"=>$form->slug, "id"=>$form->slug]) }}
				<!-- Begin intro -->
				<div class="col-md-12 nopadding alt-background  hide-overflow">
					<div class="container nopadding white-background">
						<div class="col-md-12 p-h-0">
							<div class="content-section p-t-50 m-b-0">
								<div class="content-section-body p-h-30 force_text" id="form_fields">
									
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- End intro -->
				<!-- Begin intro -->
				<div class="col-md-12 nopadding white-background  hide-overflow">
					<div class="container nopadding alt-background">
						<div class="col-md-12 p-h-0">
							<div class="content-section">
								<div class="content-section-body p-h-30 force_text" id="form_button">
									<!-- Submit Button -->								
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- End intro -->
			{{ Form::close() }}

			<div class="loader">
				<div class="bg-olay gray2-background">&nbsp;</div>
				<div class="iconholder">
					<span class="spinner animated infinite bounce blue-text"><i class="fa fa-circle"></i></span>
					<br>Loading Form...
				</div>
			</div>
		</div>
		

	</div>
	<!-- End page content -->

@stop

@section("javascript")
	{{ HTML::script('assets/site/plugins/countdown/dest/jquery.countdown.min.js') }}
	<!-- Javascript -->
	<script type="text/javascript">
		$(function(){
			formJS.init();
		});
	</script>
@stop