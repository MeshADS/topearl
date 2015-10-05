@extends("layout.master")
@include("_partials.html.default_meta")
@section("page") our_calendar @stop
@section("stylesheet")
	<!-- Stylesheet -->
@stop
@section("bootdata")
	<script type="text/javascript">
		window.Site = window.Site || {};
		//Bootstrap data
		Site.data = Site.data || {};
		Site.data.events = '{{ json_encode($events) }}';
		Site.data.nextEvent = '{{ json_encode($nextEvent) }}';
		Site.data.calendar = '{{ json_encode($calendar) }}';
	</script>
@stop

@section("content")
	<!-- Begin page content -->
	<div class="container-fluid nopadding">
		
		<div class="col-md-12 nopadding">
			<div class="content-section m-v-0">
				<div class="content-section-heading">
					<div class="content-section-body white-background">
						<div class="calendar parallax-window" style="background-image:url({{ ( isset($pageImages['Background']) ) ? URL::to($pageImages['Background']->image) : 'http://placehold.it/1920x1280' }});">
							<!-- Calendar Goes Hear-->
							<div class="bg-olay black-background">&nbsp;</div>
							<div class="loader">
								<div class="bg-olay black-background">&nbsp;</div>
								<div class="iconholder m-text">
									<span class="spinner animated infinite bounce green-text"><i class="fa fa-circle"></i></span>
									<br>Loading Calender
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
	<!-- End page content -->

@stop

@section("javascript")
	<script type="text/javascript">
		$(function(){
			calendarJS.init();
		});
	</script>
@stop