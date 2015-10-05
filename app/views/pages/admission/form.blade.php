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
				@if(isset($view_message) && $view_message["type"] == "application")
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
							{{ Form::open(["url"=>"admission/form", "role"=>"form", "method"=>"post", "class"=>"application-form", "id"=>"application-form"]) }}
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
										<div class="col-md-12">
											<div class="form-group">
												{{ Form::label("childs_nickname", "Nickname") }}
												{{ Form::text("childs_nickname", "", ["class"=>"form-control", "placeholder"=>""]) }}
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												{{ Form::label("childs_age", "Age (in years)") }}
												{{ Form::number("childs_age", "", ["class"=>"form-control", "placeholder"=>"", "min"=>"0"]) }}
												@if ($errors->has('childs_age'))
													<label id="childs_age-error" class="error" for="childs_age">{{ $errors->first('childs_age') }}</label>
												@endif
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												{{ Form::label("childs_sex", "Sex") }}
												{{ Form::select("childs_sex", [""=>"Select here", "Male"=>"Male", "Female"=>"Female"], "", ["class"=>"form-control", "placeholder"=>""]) }}
												@if ($errors->has('childs_sex'))
													<label id="childs_sex-error" class="error" for="childs_sex">{{ $errors->first('childs_sex') }}</label>
												@endif
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												{{ Form::label("childs_birthday", "Birthday") }}
												{{ Form::text("childs_birthday", "", ["class"=>"form-control datepicker", "placeholder"=>"mm/dd/yyyy"]) }}
												@if ($errors->has('childs_birthday'))
													<label id="childs_birthday-error" class="error" for="childs_birthday">{{ $errors->first('childs_birthday') }}</label>
												@endif
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												{{ Form::label("address", "Address") }}
												{{ Form::text("address", "", ["class"=>"form-control", "placeholder"=>""]) }}
												@if ($errors->has('address'))
													<label id="address-error" class="error" for="address">{{ $errors->first('address') }}</label>
												@endif
											</div>
										</div>
									</div>
								</fieldset>
								<!-- Child's Academic History -->
								<fieldset class="m-b-30">
									<legend class="green-text">Child's Academic Information</legend>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												{{ Form::label("current_school", "Current School") }}
												{{ Form::text("current_school", "", ["class"=>"form-control", "placeholder"=>""]) }}
											</div>											
										</div>
										<div class="col-md-6">
											<div class="form-group">
												{{ Form::label("current_class", "Current Class") }}
												{{ Form::text("current_class", "", ["class"=>"form-control", "placeholder"=>""]) }}
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												{{ Form::label("previous_schools", "Previous Schools Attended") }}
												<br><small><strong><i class="fa fa-info-circle"></i></strong> One school per-line.</small>
												{{ Form::textarea("previous_schools", "", ["class"=>"form-control noresize", "rows"=>"5"]) }}
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												{{ Form::label("starting_on", "When Do You Intend To Start With Us") }}
												 <i class='fa fa-calendar'></i>
												{{ Form::text("starting_on", "", ["class"=>"form-control datepicker", "placeholder"=>"mm/dd/yyyy"]) }}
												@if ($errors->has('starting_on'))
													<label id="starting_on-error" class="error" for="starting_on">{{ $errors->first('starting_on') }}</label>
												@endif
											</div>
										</div>
									</div>
								</fieldset>
								<!-- Mothers Information -->
								<fieldset class="m-b-30">
									<legend class="purple-text">Mother's Information</legend>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												{{ Form::label("mothers_name", "Name") }}
												{{ Form::text("mothers_name", "", ["class"=>"form-control", "placeholder"=>"First and last name"]) }}
												@if ($errors->has('mothers_name'))
													<label id="mothers_name-error" class="error" for="mothers_name">{{ $errors->first('mothers_name') }}</label>
												@endif
											</div>											
										</div>
										<div class="col-md-12">
											<div class="form-group">
												{{ Form::label("mothers_occupation", "Ocupation") }}
												{{ Form::text("mothers_occupation", "", ["class"=>"form-control"]) }}
												@if ($errors->has('mothers_occupation'))
													<label id="mothers_occupation-error" class="error" for="mothers_occupation">{{ $errors->first('mothers_occupation') }}</label>
												@endif
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												{{ Form::label("mothers_homephone", "Home Phone") }}
												{{ Form::text("mothers_homephone", "", ["class"=>"form-control", "placeholder"=>"E.G. +234 800 0000 000"]) }}
												@if ($errors->has('mothers_homephone'))
													<label id="mothers_homephone-error" class="error" for="mothers_homephone">{{ $errors->first('mothers_homephone') }}</label>
												@endif
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												{{ Form::label("mothers_workphone", "Work Phone") }}
												{{ Form::text("mothers_workphone", "", ["class"=>"form-control", "placeholder"=>"E.G. +234 800 0000 000"]) }}
												@if ($errors->has('mothers_workphone'))
													<label id="mothers_workphone-error" class="error" for="mothers_workphone">{{ $errors->first('mothers_workphone') }}</label>
												@endif
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												{{ Form::label("mothers_mobilephone", "Mobile Phone") }}
												{{ Form::text("mothers_mobilephone", "", ["class"=>"form-control", "placeholder"=>"E.G. +234 800 0000 000"]) }}
												@if ($errors->has('mothers_mobilephone'))
													<label id="mothers_mobilephone-error" class="error" for="mothers_mobilephone">{{ $errors->first('mothers_mobilephone') }}</label>
												@endif
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												{{ Form::label("mothers_email", "Email Address") }}
												{{ Form::email("mothers_email", "", ["class"=>"form-control noresize", "placeholder"=>""]) }}
												@if ($errors->has('mothers_email'))
													<label id="mothers_email-error" class="error" for="mothers_email">{{ $errors->first('mothers_email') }}</label>
												@endif
											</div>
										</div>
									</div>
								</fieldset>
								<!-- Father's Information -->
								<fieldset>
									<legend class="blue-text">Father's Information</legend>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												{{ Form::label("fathers_name", "Name") }}
												{{ Form::text("fathers_name", "", ["class"=>"form-control", "placeholder"=>"First and last name"]) }}
												@if ($errors->has('fathers_name'))
													<label id="fathers_name-error" class="error" for="fathers_name">{{ $errors->first('fathers_name') }}</label>
												@endif
											</div>											
										</div>
										<div class="col-md-12">
											<div class="form-group">
												{{ Form::label("fathers_occupation", "Ocupation") }}
												{{ Form::text("fathers_occupation", "", ["class"=>"form-control"]) }}
												@if ($errors->has('fathers_occupation'))
													<label id="fathers_occupation-error" class="error" for="fathers_occupation">{{ $errors->first('fathers_occupation') }}</label>
												@endif
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												{{ Form::label("fathers_homephone", "Home Phone") }}
												{{ Form::text("fathers_homephone", "", ["class"=>"form-control", "placeholder"=>"E.G. +234 800 0000 000"]) }}
												@if ($errors->has('fathers_homephone'))
													<label id="fathers_homephone-error" class="error" for="fathers_homephone">{{ $errors->first('fathers_homephone') }}</label>
												@endif
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												{{ Form::label("fathers_workphone", "Work Phone") }}
												{{ Form::text("fathers_workphone", "", ["class"=>"form-control", "placeholder"=>"E.G. +234 800 0000 000"]) }}
												@if ($errors->has('fathers_workphone'))
													<label id="fathers_workphone-error" class="error" for="fathers_workphone">{{ $errors->first('fathers_workphone') }}</label>
												@endif
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												{{ Form::label("fathers_mobilephone", "Mobile Phone") }}
												{{ Form::text("fathers_mobilephone", "", ["class"=>"form-control", "placeholder"=>"E.G. +234 800 0000 000"]) }}
												@if ($errors->has('fathers_mobilephone'))
													<label id="fathers_mobilephone-error" class="error" for="fathers_mobilephone">{{ $errors->first('fathers_mobilephone') }}</label>
												@endif
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												{{ Form::label("fathers_email", "Email Address") }}
												{{ Form::email("fathers_email", "", ["class"=>"form-control noresize", "placeholder"=>""]) }}
												@if ($errors->has('fathers_email'))
													<label id="fathers_email-error" class="error" for="fathers_email">{{ $errors->first('fathers_email') }}</label>
												@endif
											</div>
										</div>
									</div>
								</fieldset>
								<!-- Submit Button -->
								<div class="row">
									<div class="col-md-12 text-left alt-background">
										<button type="submit" class="btn btn-lg orange-background hoverable m-v-40">
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