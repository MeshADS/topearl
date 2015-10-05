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
	<!-- Begin fancybox inline content -->
		@foreach($management as $mngt)
			<div style="display:none;" rel="group">
				{{-- Content --}}
				<div id="staffModal{{$mngt->id}}">
					<div class="container">
						<div class="row">
							<div class="col-md-12">
								{{-- Name --}}
								<section>
									<h4 class="m-text red-text uppercase bold">{{ $mngt->name }}</h4>
								</section>
								{{-- Info --}}
								<section>
									<p class="s-text">
										<img src="{{ URL::to($mngt->image) }}" class="p-b-10 p-r-10 visible-lg visible-md" style="width:256px; float: left;">
										{{ $mngt->description }}
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
	<div class="container-fluid nopadding parallax-window" style="background-image:url({{ (isset($header->image)) ? URL::to($header->image) : '' }});">
		<div class="bg-olay light black-background">&nbsp;</div>		
		<div class="col-md-12">
			<div class="container">
				<div class="col-md-12">
					<div class="content-section p-v-80">
						<div class="content-section-heading">
							<div class="content-section-title white-text">
								Management
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

						@if(isset($pagedata["management"]["intro"]))
							<span class="font-bg size-128 white-background alt-text bold t-70 r-70"><i class="fa fa-heart-o"></i></span>
							<span class="font-bg size-128 white-background alt-text bold b-40 l-20 rotate-45deg"><i class="fa fa-soccer-ball-o"></i></span>

							<div class="content-section-body p-h-40">
									<?php $pmar = explode("<br>", $pagedata["management"]["intro"]["body_notags"]) ?>
									@if(count($pmar)>1)
										<div class="row">
											@foreach($pmar as $pmi)
												<div class="col-md-6">
													<p class="p-v-20 s-text">
														{{$pmi}}
													</p>
												</div>
											@endforeach									
										</div>
									@else
										<p class="p-h-50  p-v-20 s-text text-center">
											{{ $pagedata["management"]["intro"]["body_notags"] }}
										</p>
									@endif
							</div>
						@endif

					</div>

				</div>
			</div>
		</div>
		<!-- End intro -->

		<!-- Begin list of team members -->
		<div class="col-md-12 nopadding white-background">
			<div class="container p-v-15 alt-background">
				@foreach(array_chunk($management->all(), 4) as $row)
					<div class="row">
						@foreach($row as $item)
							<div class="col-md-3">
								<ul class="staff_slider_item hide-overflow radius p-v-15">
									<li>
										<img src="{{ URL::to($item->thumbnail) }}" class="fullwidth">
									</li>
									<li class="p-h-15 p-v-10 green-background alt white-text capitalize m-text">
										{{ $item->name }}
									</li>
									<li class="p-h-15 p-v-10 green-background white-text uppercase xs-text">
										{{ $item->office }}
									</li>
									<li class="p-h-15 p-v-20 green-background alt text-right">
										<a href="#staffModal{{$item->id}}" rel="group" class="no-text-decoration teamBioBtn">
											<span class="p-v-10 p-h-15 orange-background bordered white-text radius uppercase xs-text">Read Bio</span>
										</a>
									</li>
								</ul>
							</div>
						@endforeach
					</div>
				@endforeach
			</div>
		</div>
		<!-- End list of team members -->

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
			teamJS.init();
		});
	</script>
@stop