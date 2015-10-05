@extends("layout.master")
@include("_partials.html.default_meta")
@section("page") home @stop
@section("stylesheet")
	<!-- Stylesheet -->
@stop
@section("bootdata")
	<script type="text/javascript">
		window.Site = window.Site || {};
		//Config
		Site.Config = Site.Config || {};
		Site.Config.env = "{{ App::environment() }}";
		Site.Config.url = "{{ Config::get('app.url') }}";
		Site.Config.apiUrl = Site.Config.url+"/api/";
		Site.Config.tplUrl = Site.Config.url+"/assets/js/frontend/templates/";
		Site.Config.rsrc = Site.Config.url+"/assets/js/frontend/src";		
		//Bootstrap data
		Site.data = Site.data || {};
		Site.data.nextEvent = '{{ json_encode($nextEvent) }}';
	</script>
@stop

@section("content")
	<!-- Begin slider -->
	<div class="heroslider-container">
		<div class="container">
			<div class="col-md-12 nopadding">
				<div class="heroslider-nav">

					<a href="#" class="nav-item prev flat-it"><span class="icon white-text"><i class="fa fa-chevron-left"></i></span></a>

					<a href="#" class="nav-item next flat-it"><span class="icon white-text"><i class="fa fa-chevron-right"></i></span></a>

				</div>
				<ul class="heroslider">
					@foreach($sliders as $slider)
						<li class="heroslider-item">
							@if(!empty($slider->link_url) && empty($slider->link_title))
							<a href="{{ $slider->link_url }}">
								<img src="{{ $slider->image }}" class="heroslider-image  {{ (!empty($slider->mobile_image)) ? 'visible-lg visible-md' : '' }}">
								@if(!empty($slider->mobile_image))
									<img src="{{ $slider->mobile_image }}" class="heroslider-image visible-sm visible-xs">
								@endif
							</a>
							@else
								<img src="{{ $slider->image }}" class="heroslider-image {{ (!empty($slider->mobile_image)) ? 'visible-lg visible-md' : '' }}">
								@if(!empty($slider->mobile_image))
									<img src="{{ $slider->mobile_image }}" class="heroslider-image visible-sm visible-xs">
								@endif
							@endif
							
							@if(!empty($slider->caption) || !empty($slider->link_title))
							<div class="heroslider-caption">
								<ul>
									@if(!empty($slider->caption))
										<li class="caption thin-font m-text">{{ $slider->caption }}</li>
									@endif
									@if(!empty($slider->link_url))
										<li class="link">
											@if( $slider->link_type == 1 )
												<a href="{{ $slider->link_url }}" class="xs-text black-link">{{ $slider->link_title }}</a>
											@else
												<a href="{{ $slider->link_url }}" class="btn black-background hoverable white-link btn-sm">
													{{ $slider->link_title }}
												</a>
											@endif
										</li>
									@endif
								</ul>
								<div class="dark bg-olay green-background">&nbsp;</div>
							</div>
							@endif
							<div class="green3-background dark bg-olay heroslider-item-mask">&nbsp;</div>
						</li>
					@endforeach
				</ul>
			</div>
		</div>
	</div>
	<!-- End slider -->

	<div class="colored-line">&nbsp;</div>

	<div class="container-fluid nopadding">
		<!-- Begin welcome -->
		<div class="col-md-12 nopadding white-background">
			<div class="container nopadding">
				<div class="row">
					<div class="col-md-12">
						<div class="content-section">
							@if(isset($pagedata["home"]["welcome"]))
								<div class="content-section-body p-h-40">
									<div class="row text-center gray2-text">
										<div class="col-md-12">
											<h4 class="l-text uppercase green2-text m-b-20">Welcome</h4>
										</div>
										{{ (isset($pagedata["home"]["welcome"]["body_formatted"])) ? $pagedata["home"]["welcome"]["body_formatted"] : '' }}						
									</div>
								</div>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End welcome -->
		<!-- Begin Programs -->
		<div class="col-md-12 nopadding alt hide-overflow">
			<div class="spanned_element green2-background hoverable">
				<div class="container nopadding">
					<div class="col-md-12">

						<div class="content-section p-v-0">
						
							<div class="content-section-heading" data-sr="enter right, hustle 50px, opacity 0.3, reset">
								<div class="content-section-title text-center bold nomargin white-text">What We Offer</div>
							</div>

						</div>

					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12 nopadding hoverable hide-overflow what-we-offer parallax-window" data-parallax="scroll" data-speed="0.6" data-image-src="{{ (isset($pageImages['Event Counter'])) ? URL::to($pageImages['What We Offer']->image) : 'http://placehold.it/1920x1280' }}">
			<div class="bg-olay green3-background dark">&nbsp;</div>
			<div class="container nopadding">
				<div class="col-md-8 col-md-offset-2">

					<div class="content-section nomargin p-b-40" data-sr="enter left, hustle 50px, opacity 0.3, vFactor 0.1, reset">
		
						<div class="content-section-body p-h-40">
							<div class="row">
								<div class="col-md-6 text-center">
									<a href="{{ @$pageImages['Certificate Programs']->link_url }}" class="green-link no-text-decoration">
										<img src="{{ URL::to(@$pageImages['Certificate Programs']->image) }}" alt="Certificate Programs" class="fullwidth m-t-40">
										<h4 class="s-text bold uppercase text-center m-t-0">Certificate Programs</h4>
									</a>
								</div>	
								<div class="col-md-6 text-center">
									<a href="{{ @$pageImages['Diploma Programs']->link_url }}" class="green-link no-text-decoration">
										<img src="{{ URL::to(@$pageImages['Diploma Programs']->image) }}" alt="Diploma Programs" class="fullwidth m-t-40">
										<h4 class="s-text bold uppercase text-center m-t-0">Diploma Programs</h4>
									</a>
								</div>			
							</div>
						</div>

					</div>

				</div>
			</div>
		</div>
		<!-- End Programs -->
		<!-- Begin Next Event -->
		<div class="col-md-12 nopadding parallax-window hide-overflow" data-parallax="scroll" data-speed="0.7" data-image-src="{{ (isset($pageImages['Event Counter'])) ? URL::to($pageImages['Event Counter']->image) : 'http://placehold.it/1920x1280' }}">
			<div class="bg-olay black-background light">&nbsp;</div>
			<div class="container nopadding">
				<div class="col-md-12 p-v-80">
					<h4 class="capitalize s-text bold text-center white-text">Next In Our Calendar</h4>
					<h4 class="uppercase l-text bold text-center white-text" id="next-event-title">&nbsp;</h4>
					<ul class="nexteventcountdown"></ul>
				</div>
			</div>
		</div>
		<!-- End Next Event -->
		<!-- Begin events countdown -->
		<div class="col-md-12 nopadding white-background hide-overflow">
			
			<div class="container nopadding green-background z-index-100">
				<div class="col-md-12">
					<div class="content-section">
						<div class="content-section-heading">
							<div class="content-section-title white-text">Our Calendar</div>
							<div class="content-section-caption white-text">Keep Up With Our Events</div>
							<div class="content-section-divider white-background">&nbsp;</div>
						</div>

						<div class="content-section-body">
							@include("_partials.html.calendarcarousel")
						</div>

					</div>

				</div>
			</div>
		</div>
		<!-- End events countdown -->
		@if(count($latestposts) > 0)
		<!-- Begin latest posts -->
			<div class="col-md-12 nopadding alt-background hide-overflow">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<div class="content-section">
								<div class="content-section-heading">
									<div class="content-section-title">News</div>
									<div class="content-section-caption">Latest Updates From The Blog</div>
									<div class="content-section-divider orange-background">&nbsp;</div>
								</div>
								<div class="content-section-body p-v-0">
									<div class="container-fluid nopadding">
										<div class="col-md-12 nopadding">
											@include("_partials.html.lastestnewscarousel")
										</div>
									</div>
								</div>
							</div>
						</div>						
					</div>
				</div>
			</div>
		<!-- End latest posts -->
		@endif
		<!-- Begin staff -->
		<div class="col-md-12 nopadding white-background">
			<div class="container nopadding">
				<div class="col-md-12">
					<span class="font-bg xxl-text green-text light t-15 l-15 rotate-315deg z-index-0"><i class="fa fa-shield"></i></span>

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
		<!-- End staff -->
	</div>

@stop

@section("javascript")
	{{ HTML::script('assets/site/plugins/countdown/dest/jquery.countdown.min.js') }}
	<!-- Javascript -->
	<script type="text/javascript">
		$(function(){
			homeJS.init();
		});
	</script>
@stop