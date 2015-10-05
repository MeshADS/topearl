@extends("layout.master")
@include("_partials.html.default_meta")
@section("page") about_us @stop
@section("stylesheet")
	<!-- Stylesheet -->
@stop
@section("bootdata")
	<!-- Boot data -->
@stop

@section("content")
	<!-- Begin header -->
	<div class="container-fluid nopadding parallax-window" style="background-image:url({{ URL::to(@$header->image) }});">
		@if(isset($header))
			<div class="bg-olay light black-background">&nbsp;</div>
		@endif
		<div class="col-md-12">
			<div class="container">
				<div class="col-md-12">
					<div class="content-section p-v-80">
						<div class="content-section-heading">
							<div class="content-section-title white-text">
								About Us
							</div>
							@if(!empty(@$header->caption))
								<div class="content-section-caption white-text">
									{{ @$header->caption }}
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
	<!-- Begin summary -->
	<div class="container-fluid nopadding">
		
		<!-- Begin summary -->
		<div class="col-md-12 nopadding alt-background">
			<div class="container nopadding">
				<div class="col-md-12">

					<div class="content-section p-v-50 p-h-30">

						@if(isset($pagedata["about-us"]["summary"]))
							<div class="content-section-body p-h-0 p-v-20">
								<div class="row">									
									{{ (isset($pagedata["about-us"]["summary"]["body_formatted"])) ? @$pagedata["about-us"]["summary"]["body_formatted"] : '' }}
								</div>
							</div>
						@endif

					</div>

				</div>
			</div>
		</div>
		<!-- End summary -->
				
		<!-- Begin staff carousel -->
		<div class="col-md-12 nopadding white-background">
			<div class="container nopadding">
				<div class="col-md-12">
					<div class="content-section">

						<div class="content-section-heading">
							<div class="content-section-title">Management</div>
							<div class="content-section-caption">Know More About Our Work Force</div>
							<div class="content-section-divider green-background">&nbsp;</div>
						</div>

						<div class="content-section-body p-v-40">
							@include("_partials.html.staffcarousel")
						</div>

					</div>

				</div>
			</div>
		</div>
		<!-- End staff carousel -->
		
		<!-- Begin calendar carousel -->
		<div class="col-md-12 nopadding alt-background">
			
			<div class="container nopadding green-background z-index-100">
				<div class="col-md-12">
					<div class="content-section">

						<div class="content-section-heading">
							<div class="content-section-title white-text">Our Calendar</div>
							<div class="content-section-caption white-text">Keep Up With Our Events</div>
							<div class="content-section-divider white-background">&nbsp;</div>
						</div>

						<div class="content-section-body  p-v-40">
							@include("_partials.html.calendarcarousel")
						</div>

					</div>

				</div>
			</div>
		</div>
		<!-- End calendar carousel -->

	</div>
	<!-- End summary -->

@stop

@section("javascript")
	<!-- Javascript -->
	<script type="text/javascript">
		
	</script>
@stop