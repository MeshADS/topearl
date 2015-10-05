@extends("layout.master")
@include("_partials.html.default_meta")
@section("page") admission @stop
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

	<!-- Begin fancybox inline content -->
		@foreach($list as $a_m)
			<div style="display:none;" rel="group">
				{{-- Content --}}
				<div id="admissionModal{{$a_m->id}}">
					<div class="container">
						<div class="row">
							<div class="col-md-12">
								{{-- Name --}}
								<section>
									<h4 class="m-text red-text uppercase bold m-b-30">{{ $a_m->aclass->name }}</h4>
								</section>
								{{-- Contact --}}
								<section>
									<h6 class="s-text red-text uppercase bold m-b-0">Contact Us</h6>
									<ul class="list-unstyled">
										<li class="green-text bold">{{ $a_m->contactdata1->data }}</li>
										<li class="orange-text bold">{{ $a_m->contactdata2->data }}</li>
									</ul>
								</section>
								{{-- Info --}}
								<section>
									<p class="s-text">
										<img src="{{ URL::to($a_m->image) }}" class="p-b-10 p-r-10 radius" style="width:256px; float: left;">
										{{ $a_m->description }}
									</p>
								</section>
							</div>
						</div>
					</div>
				</div>
			</div>
		@endforeach
	<!-- End fancybox inline content -->

	<!-- Begin header -->
	<div class="container-fluid nopadding parallax-window" style="background-image:url({{ URL::to($header->image) }});">
		@if(isset($header))
			<div class="bg-olay light gray2-background alt">&nbsp;</div>
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

						<div class="content-section-body p-h-40 force_text">
								{{ (isset($pagedata["admission"]["intro"]["body"])) ? $pagedata["admission"]["intro"]["body"] : '' }}
						</div>

					</div>

				</div>
			</div>
		</div>
		<!-- End intro -->
		@if(count($list) > 0)
			<!-- Begin students -->
				<div class="col-md-12 nopadding white-background">
					<div class="container p-v-15 alt-background">
						<div class="container-fluid p-b-40">
							<div class="col-md-12">
								<div class="row masonryContainer">
									@foreach($list as $item)
										<div class="col-md-4 admission_item item admission_item p-v-50 green-background bordered hoverable radius hide-overflow class{{ $item->aclass->id }}">
											<h4 class="uppercase s-text white-text">
												{{ $item->title }}
											</h4>
											<img src="{{ URL::to( $item->thumbnail ) }}" class="fullwidth p-b-10">
											<div class="row">
												<div class="col-md-12 text-left white-text">
													<strong>Class:</strong>&nbsp;{{ $item->aclass->name }}
												</div>
												<div class="col-md-12 text-left white-text">
													<strong>Closes On:</strong>&nbsp;{{ date("d M Y", strtotime($item->close_date)) }}
												</div>
												<div class="col-md-12 text-left p-v-20">
													<a href="#admissionModal{{$item->id}}" rel="group" class="fancyboxBtn radius p-v-5 p-h-10 orange-background hoverable xs-text uppercase no-text-decoration" rel="group">
														<span class="white-text">View Info</span>
													</a>
												</div>
											</div>
										</div>
									@endforeach

								</div>
							</div>
						</div>
					</div>
				</div>
			<!-- End students -->
		@endif

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
			admissionJS.init();
		});
	</script>
@stop