@extends("layout.master")
@include("_partials.html.default_meta")
@section("page") programs @stop
@section("stylesheet")
	<!-- Stylesheet -->
@stop
@section("bootdata")
	<!-- Boot data -->
@stop

@section("content")
	<!-- Begin header -->
	<div class="container-fluid nopadding parallax-window" data-parallax="scroll" data-speed="0.5" data-image-src="{{ URL::to(@$header->image) }}">
		@if(isset($header))
			<div class="bg-olay light black-background">&nbsp;</div>
		@endif
		<div class="col-md-12">
			<div class="container">
				<div class="col-md-12">
					<div class="content-section p-v-80">
						<div class="content-section-heading">
							<div class="content-section-title white-text">
								Programs
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

	<!-- Begin main content -->
	<div class="container-fluid nopadding">
		<!-- Begin intro -->
		<div class="col-md-12 nopadding white-background">
			<div class="container nopadding">
				<div class="row">
					<div class="col-md-12">
						<div class="content-section">
							@if(isset($pagedata["programs"]["intro"]))
								<div class="content-section-body p-v-40 p-h-40">
									<div class="row text-center gray-text">
										{{ (isset($pagedata["programs"]["intro"]["body_formatted"])) ? $pagedata["programs"]["intro"]["body_formatted"] : '' }}						
									</div>
								</div>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End intro -->
		@foreach($sections as $section)
		<!-- Begin section -->
			<div class="col-md-6 nopadding">
				<!-- <div class="colored-line">&nbsp;</div> -->
				<div class="spanned_element" id="{{ Slugify::slugify($section->title) }}" style="background: url({{ URL::to($section->background) }}) center center no-repeat; background-size:cover;">
					<div class="black-background dark bg-olay">&nbsp;</div>
						<div class="content-section">
							<!-- Section Heading -->
							<div class="content-section-heading p-v-20 p-h-30">
								<div class="content-section-title white-text normal s-text">
									{{ $section->title }}
								</div>
								@if(!empty($section->caption))
									<div class="content-section-caption white-text">
										{{ $section->caption }}
									</div>
								@endif
								<div class="content-section-divider green2-background">&nbsp;</div>
							</div>
							<!-- Section Body -->
							<div class="content-section-body m-b-20">
								<div class="container-fluid">
									<div class="col-md-4 text-left nopadding">
										<img src="{{ URL::to(@$section->image->image) }}" class="fullwidth" alt="{{ $section->title }}">
									</div>
									<div class="col-md-8 nopadding">
										<ul class="list-unstyled p-h-0 p-v-0">
											@foreach($section->list as $item)
												<li class="m-b-0">
													<a href="#" data-toggle="myModal" data-target="#programModal{{ $item->id }}" class="white-text no-text-decoration s-text normal">
														<i class="green-text fa fa-caret-right"></i>&nbsp;{{ $item->title }}
													</a>
												</li>
												<div class="myModal rotateIn" id="programModal{{ $item->id }}">
													<div class="myModal-inner">
														<div class="black-background light bg-olay myModal-olay">&nbsp;</div>
														<div class="myModal-dialog">
															<div class="myModal-content">
																<div class="myModal-heading">
																	<h4 class="myModal-title bold uppercase xs-text">
																		{{ $item->title }}
																	</h4>
																	<a href="#" class="myModal-close" data-dismiss="myModal"><i class="fa fa-times"></i></a>
																</div>
																<div class="myModal-body">
																	<p class="spanned_element gray-text s-text m-b-0">
																		<img src="{{ URL::to($item->image) }}" alt="{{ $item->title }}" class="w-200 p-r-5 p-b-5 spanned_element">
																		{{ $item->caption }}
																	</p>
																</div>
																<div class="myModal-footer">
																	<a href="#" data-dismiss="myModal" data-toggle="nextMyModal" data-target="#applicationModal{{ $item->id }}" class="btn green-background hoverable flat-it pull-right white-link">Apply Now</a>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="myModal slideDown" id="applicationModal{{ $item->id }}">
													<div class="myModal-inner">
														<div class="black-background light bg-olay myModal-olay">&nbsp;</div>
														<div class="myModal-dialog">
															<div class="myModal-content">
																<div class="myModal-heading">
																	<h4 class="myModal-title bold uppercase xs-text p-h-25 m-v-20">
																		{{ $item->title }} <span class="green-text">(Application&nbsp;Form)</span>
																	</h4>
																	<a href="#" class="myModal-close" data-dismiss="myModal"><i class="fa fa-times"></i></a>
																</div>
																<div class="myModal-body p-h-25 p-v-20">
																	{{ Form::open(["url"=>"programs/apply"]) }}
																		<div class="form-group">
																			{{ Form::label("first_name", "First Name") }}
																			{{ Form::text("first_name", "", ["class"=>"form-control flat-it"]) }}
																		</div>
																		<div class="form-group">
																			{{ Form::label("last_name", "Last Name") }}
																			{{ Form::text("last_name", "", ["class"=>"form-control flat-it"]) }}
																		</div>
																		<div class="form-group">
																			{{ Form::label("email", "Email") }}
																			{{ Form::text("email", "", ["class"=>"form-control flat-it"]) }}
																		</div>
																		<div class="form-group">
																			{{ Form::label("phone", "Phone") }}
																			{{ Form::text("phone", "", ["class"=>"form-control flat-it"]) }}
																		</div>
																		<div class="form-group">
																			{{ Form::label("comment", "Comment") }}
																			{{ Form::textarea("comment", "", ["class"=>"form-control flat-it noresize", "rows"=>"3"]) }}
																		</div>
																		<div class="form-group">
																			<input type="hidden" name="program" value="{{ $item->title }}">
																			<button type="submit" class="btn green-background hoverable flat-it pull-left white-link">Apply</button>
																		</div>
																	{{ Form::close() }}
																</div>
															</div>
														</div>
													</div>
												</div>
											@endforeach
										</ul>
									</div>
								</div>
							</div>
						</div>
				</div>
			</div>
		<!-- End section -->
		@endforeach
	</div>
	<!-- End main content -->

@stop

@section("javascript")
	<script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDrhaMKcj3239_zB6LvzwpxZotoH47TUBU">
    </script>
	<!-- Javascript -->
	<script type="text/javascript">
		$(function(){
			contactJS.init();
		});
	</script>
@stop