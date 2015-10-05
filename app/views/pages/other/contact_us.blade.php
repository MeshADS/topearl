@extends("layout.master")
@include("_partials.html.default_meta")
@section("page") contact_us @stop
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
			<div class="bg-olay black-background">&nbsp;</div>
		@endif
		<div class="col-md-12">
			<div class="container">
				<div class="col-md-12">
					<div class="content-section p-v-80">
						<div class="content-section-heading">
							<div class="content-section-title white-text">
								Contact Us
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
		
		<!-- Begin contact form/info -->
		<div class="col-md-12 nopadding alt-background">
			<div class="container nopadding">
				<div class="col-md-6 nopadding">

					<div class="content-section p-v-20 m-v-0 white-background">

						<div class="content-section-body p-h-40 p-v-35">

							<ul class="contact-list">
								<li>
									<span class="sec-icon green-text"><i class="fa fa-phone"></i></span><br>
									<?php
										$phone = (isset($pagedata[$pageslug]["phone"]["body"])) ? explode(",", strip_tags($pagedata[$pageslug]["phone"]["body"])) : [];
									?>
									@foreach($phone as $ph)
										<a href="tel:{{ trim($ph) }}" class="black-text">{{ str_replace(" ", "-", trim($ph)) }}</a><br>
									@endforeach
								</li>
								<li>
									<span class="sec-icon green-text"><i class="fa fa-at"></i></span><br>
									<?php
										$emails = (isset($pagedata[$pageslug]["email"]["body"])) ? explode(",", strip_tags($pagedata[$pageslug]["email"]["body"])) : [] ;
									?>
									@foreach($emails as $em)
										<a href="mailto:{{ trim($em) }}" class="black-text">{{ trim($em) }}</a><br>
									@endforeach
								</li>
								<li>
									<span class="sec-icon green-text"><i class="fa fa-map-marker"></i></span><br>
									{{ (isset($pagedata[$pageslug]["address"]["body"])) ? str_replace("/n", "<br>", strip_tags($pagedata[$pageslug]["address"]["body"])) : '' }}
								</li>
								<li>
									<h6 class="s-text bold black-text uppercase">We Are Social!</h6>
									<?php
										$socials = isset($pagedata[$pageslug]["socials"]["body"]) ? explode(",", strip_tags($pagedata[$pageslug]["socials"]["body"])) : [];
									?>
									@foreach($socials as $social)

										<?php $social_segments = explode(" ", strip_tags(trim($social))) ?>
										<a href="{{ (isset($social_segments[1])) ? $social_segments[1] : '#' }}" 
											class="{{ (isset($social_segments[0])) ? strtolower(trim($social_segments[0])) : '#' }}-background hoverable socials m-r-10 no-text-decoration hide-overflow radius" 
											title="{{ (isset($social_segments[0])) ? $social_segments[0] : '#' }}"
											target="_blank">
											<i class="white-text fa fa-{{ (isset($social_segments[0])) ? strtolower(trim($social_segments[0])) : '#' }}"></i>
										</a>
									@endforeach
								</li>
							</ul>
							
						</div>

					</div>

				</div>
				<div class="col-md-6 alt-background">

					<div class="content-section p-v-20 m-b-0">

						<div class="content-section-heading">
							<div class="content-section-title">
								Contact Form
							</div>
							<div class="content-section-divider orange-background">&nbsp;</div>
						</div>

						<div class="content-section-body p-v-0">

							@if(isset($view_message) && $view_message["type"] == "contact_form")
								@section("top-notification")
									<p class='alert alert-{{ $view_message["level"] }} alert-dismissable m-v-0 flat-it text-center'>
										<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										{{ $view_message["message"] }}
									</p>
								@stop
							@endif

							{{ Form::open(["url"=>"contact_us/send", "id"=>"contactForm"]) }}

							<div class="form-group">
								{{ Form::label("name", "Name") }}
								{{ Form::text("name", "", ["class"=>"form-control flat-it"]) }}
								@if ($errors->has('name'))
									<label id="name-error" class="error" for="name">{{ $errors->first('name') }}</label>
								@endif
							</div>

							<div class="form-group">
								{{ Form::label("email", "Email") }}
								{{ Form::email("email", "", ["class"=>"form-control flat-it"]) }}
								@if ($errors->has('email'))
									<label id="email-error" class="error" for="email">{{ $errors->first('email') }}</label>
								@endif
							</div>

							<div class="form-group">
								{{ Form::label("comment", "Comment") }}
								{{ Form::textarea("comment", "", ["class"=>"form-control flat-it noresize", "rows"=>"4"]) }}
								@if ($errors->has('comment'))
									<label id="comment-error" class="error" for="comment">{{ $errors->first('comment') }}</label>
								@endif
							</div>

							<div class="form-group m-b-0">
								<button type="submit" class="btn btn-block btn-lg green-background hoverable flat-it">
									<span class="white-text bold">Send</span>
								</button>
							</div>
								
							{{ Form::close() }}

						</div>

					</div>

				</div>
			</div>
		</div>
		<!-- End contact form/info -->

		<!-- Begin map -->
		<div class="col-md-12 nopadding">
			<div class="colored-line">&nbsp;</div>
			<div class="map-canvas alt-background" id="map-canvas">
				<iframe src="https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d1987.910509806373!2d7.036012!3d4.800763!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sng!4v1441643543551" style="width:100%; height:100%;" frameborder="0" style="border:0" allowfullscreen></iframe>
			</div>

		</div>
		<!-- End map -->

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