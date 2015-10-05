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

	@include("_partials.html.page_header")

	<div class="colored-line">&nbsp;</div>
	<!-- Begin page content -->
	<div class="container-fluid nopadding">
		
		<!-- Begin intro -->
		<div class="col-md-12 nopadding alt-background  hide-overflow">
			<div class="container nopadding white-background">
				@if(isset($view_message) && $view_message["type"] == "afterschool")
					@section("top-notification")
						<p class='alert alert-{{ $view_message["level"] }} alert-dismissable m-v-0 flat-it text-center'>
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							{{ $view_message["message"] }}
						</p>
					@stop
				@endif
				<div class="col-md-12">

					<div class="content-section p-v-50">

						<div class="content-section-body p-h-20 force_text">
							{{ Form::open(["url"=>"admission/afterschool", "role"=>"form", "method"=>"post", "class"=>"afterschool-application-form", "id"=>"afterschool-application-form"]) }}
								<!-- Child Basic data -->
								<fieldset class="m-b-30">
									<span class="font-bg size-128 white-background alt-text bold t-70 r-70"><i class="fa fa-heart-o"></i></span>
									<span class="font-bg size-128 white-background alt-text bold b-40 l-20 rotate-45deg"><i class="fa fa-soccer-ball-o"></i></span>
									<legend class="orange-text">Child Basic Data</legend>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												{{ Form::label("childs_name", "Name") }}
												{{ Form::text("childs_name", "", ["class"=>"form-control", "placeholder"=>""]) }}
												@if ($errors->has('childs_name'))
													<label id="childs_name-error" class="error" for="childs_name">{{ $errors->first('childs_name') }}</label>
												@endif
											</div>											
										</div>
										<div class="col-md-6">
											<div class="form-group">
												{{ Form::label("childs_surname", "Surname") }}
												{{ Form::text("childs_surname", "", ["class"=>"form-control", "placeholder"=>""]) }}
												@if ($errors->has('childs_surname'))
													<label id="childs_surname-error" class="error" for="childs_surname">{{ $errors->first('childs_surname') }}</label>
												@endif
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												{{ Form::label("dob", "Date Of Birth") }}
												{{ Form::text("dob", "", ["class"=>"form-control datepicker", "placeholder"=>"mm/dd/yyyy"]) }}
												@if ($errors->has('dob'))
													<label id="dob-error" class="error" for="dob">{{ $errors->first('dob') }}</label>
												@endif
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												{{ Form::label("childs_sex", "Sex") }}
												{{ Form::select("childs_sex", [""=>"Select here", "Male"=>"Male", "Female"=>"Female"], "", ["class"=>"form-control", "placeholder"=>""]) }}
												@if ($errors->has('childs_sex'))
													<label id="childs_sex-error" class="error" for="childs_sex">{{ $errors->first('childs_sex') }}</label>
												@endif
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												{{ Form::label("address", "Home Address") }}
												{{ Form::text("address", "", ["class"=>"form-control", "placeholder"=>""]) }}
												@if ($errors->has('address'))
													<label id="address-error" class="error" for="address">{{ $errors->first('address') }}</label>
												@endif
											</div>
										</div>
									</div>
								</fieldset>
								<!-- Parent Information -->
								<fieldset class="m-b-30">
									<legend class="green-text">Parent's Information</legend>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												{{ Form::label("parents_name", "Name") }}
												{{ Form::text("parents_name", "", ["class"=>"form-control", "placeholder"=>"First and last name"]) }}
												@if ($errors->has('parents_name'))
													<label id="parents_name-error" class="error" for="parents_name">{{ $errors->first('parents_name') }}</label>
												@endif
											</div>											
										</div>
										<div class="col-md-6">
											<div class="form-group">
												{{ Form::label("parents_occupation", "Ocupation") }}
												{{ Form::text("parents_occupation", "", ["class"=>"form-control"]) }}
												@if ($errors->has('parents_occupation'))
													<label id="parents_occupation-error" class="error" for="parents_occupation">{{ $errors->first('parents_occupation') }}</label>
												@endif
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												{{ Form::label("work_address", "Work Address") }}
												{{ Form::text("work_address", "", ["class"=>"form-control", "placeholder"=>""]) }}
												@if ($errors->has('work_address'))
													<label id="work_address-error" class="error" for="work_address">{{ $errors->first('work_address') }}</label>
												@endif
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												{{ Form::label("parents_phone", "Phone Number") }}
												{{ Form::text("parents_phone", "", ["class"=>"form-control", "placeholder"=>"E.G. +234 800 0000 000"]) }}
												@if ($errors->has('parents_phone'))
													<label id="parents_phone-error" class="error" for="parents_phone">{{ $errors->first('parents_phone') }}</label>
												@endif
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												{{ Form::label("parents_email", "Email Address") }}
												{{ Form::email("parents_email", "", ["class"=>"form-control noresize", "placeholder"=>""]) }}
												@if ($errors->has('parents_email'))
													<label id="parents_email-error" class="error" for="parents_email">{{ $errors->first('parents_email') }}</label>
												@endif
											</div>
										</div>
									</div>
								</fieldset>
								<!-- Clubs Information -->
								<fieldset class="m-b-30">
									<legend class="green-text">
										Select Clubs
										<p class="gray-text xxs-text bold"><small>Please tick the club(s) you are interested in (you are only allowed 2 clubs per term):</small></p>
									</legend>
									<div class="row m-b-20">
										<div class="col-md-12 nopadding nomargin">
											{{ Form::text("checkboxes", "", ["class"=>"checkboxes w-0 h-0 nopadding nomargin hide-overflow", "style"=>"opacity:0;"]) }}
										</div>
										<div class="col-md-6">
											<div class="form-group">
												{{ Form::checkbox("pythagoras_corner", "1", false, ["class"=>"club_checks w-30 h-30"]) }}&nbsp;&nbsp;
												{{ Form::label("pythagoras_corner", "Pythagoras Corner ( Maths club) age 3-10") }}
											</div>
										</div>
										<div class="col-md-6">
											<div>
												<div class="form-group">
													{{ Form::checkbox("gardening", "1", false, ["class"=>"club_checks w-30 h-30"]) }}&nbsp;&nbsp;
													{{ Form::label("gardening", "Gardening age 3-10") }}
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div>
												<div class="form-group">
													{{ Form::checkbox("ict_whiz", "1", false, ["class"=>"club_checks w-30 h-30"]) }}&nbsp;&nbsp;
													{{ Form::label("ict_whiz", "ICT Whiz  age 3-10") }}
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div>
												<div class="form-group">
													{{ Form::checkbox("lady_class", "1", false, ["class"=>"club_checks w-30 h-30"]) }}&nbsp;&nbsp;
													{{ Form::label("lady_class", "Lady Class ( Finishing Class for Girls) age 3-10") }}
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div>
												<div class="form-group">
													{{ Form::checkbox("dance", "1", false, ["class"=>"club_checks w-30 h-30"]) }}&nbsp;&nbsp;
													{{ Form::label("dance", "Dance age 2-10") }}
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div>
												<div class="form-group">
													{{ Form::checkbox("taekwondo", "1", false, ["class"=>"club_checks w-30 h-30"]) }}&nbsp;&nbsp;
													{{ Form::label("taekwondo", "Taekwondo age 3-10") }}
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div>
												<div class="form-group">
													{{ Form::checkbox("book_club", "1", false, ["class"=>"club_checks w-30 h-30"]) }}&nbsp;&nbsp;
													{{ Form::label("book_club", "Book Club 3- 10") }}
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div>
												<div class="form-group">
													{{ Form::checkbox("science_club", "1", false, ["class"=>"club_checks w-30 h-30"]) }}&nbsp;&nbsp;
													{{ Form::label("science_club", "Science Club 4-10") }}
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div>
												<div class="form-group">
													{{ Form::checkbox("music", "1", false, ["class"=>"club_checks w-30 h-30"]) }}&nbsp;&nbsp;
													{{ Form::label("music", "Music age 3-10") }}
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div>
												<div class="form-group">
													{{ Form::checkbox("drama_public_speaking", "1", false, ["class"=>"club_checks w-30 h-30"]) }}&nbsp;&nbsp;
													{{ Form::label("drama_public_speaking", "Drama/ Public Speaking age 2-10") }}
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div>
												<div class="form-group">
													{{ Form::checkbox("cheerleading", "1", false, ["class"=>"club_checks w-30 h-30"]) }}&nbsp;&nbsp;
													{{ Form::label("cheerleading", "Cheerleading age 3-10") }}
												</div>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												{{ Form::label("starting_on", "When would you like to start with us?") }}
												{{ Form::text("starting_on", "", ["class"=>"form-control datepicker", "placeholder"=>"mm/dd/yyyy"]) }}
												@if ($errors->has('starting_on'))
													<label id="starting_on-error" class="error" for="starting_on">{{ $errors->first('starting_on') }}</label>
												@endif
											</div>
											<div class="form-group">
												{{ Form::label("comments", "Additional Comment") }}
												{{ Form::textarea("comments", "", ["class"=>"form-control noresize", "placeholder"=>"Have anything you would like to let us know?", "rows"=>"3"]) }}
												@if ($errors->has('comments'))
													<label id="comments-error" class="error" for="comments">{{ $errors->first('comments') }}</label>
												@endif
											</div>
										</div>
									</div>
								</fieldset>
								<!-- Submit Button -->
								<div class="row">
									<div class="col-md-12 text-left alt-background">
										<button type="submit" class="btn btn-lg green2-background hoverable m-v-40">
											<span class="white-text">Submit</span>
										</button>
									</div>
								</div>
							{{ Form::close() }}
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
			admissionJS.init();
		});
	</script>
@stop