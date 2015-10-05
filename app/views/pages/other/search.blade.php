@extends("layout.master")
@include("_partials.html.default_meta")
@section("page") admission @stop
@section("stylesheet")
	<!-- Stylesheets -->
@stop
@section("bootdata")
	<!-- Boot data -->
@stop

@section("content")

	<!-- Begin header -->
	<div class="container-fluid nopadding parallax-window" style="background-image:url({{ URL::to($header->image) }});">
		@if(isset($header))
			<div class="bg-olay light black-background">&nbsp;</div>
		@endif
		<div class="col-md-12">
			<div class="container">
				<div class="col-md-12">
					<div class="content-section p-v-100">
						<div class="content-section-heading">
							<div class="content-section-title white-text">
								Admission
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

						<span class="font-bg size-128 white-background alt-text bold t-70 r-70"><i class="fa fa-heart-o"></i></span>
						<span class="font-bg size-128 white-background alt-text bold b-40 l-20 rotate-45deg"><i class="fa fa-soccer-ball-o"></i></span>

						<div class="content-section-body p-h-40">

							<gcse:search></gcse:search>
							
						</div>

					</div>

				</div>
			</div>
		</div>
		<!-- End intro -->

	</div>
	<!-- End page content -->

@stop

@section("javascript")
	<script type="text/javascript">
		$(function(){
			searchJS.init();
		});
	</script>
@stop