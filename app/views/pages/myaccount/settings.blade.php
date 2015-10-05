@extends("layout.master")
@section("title") My Account :: {{ $basicdata->fullname }} @stop
@include("_partials.html.default_meta")
@section("page") myaccount @stop
@section("stylesheet")
	{{ HTML::style('assets/site/plugins/croppic/assets/css/croppic.css') }}
	<!-- Stylesheet -->
@stop
@section("bootdata")
	<!-- Boot data -->
	<script type="text/javascript">
		//Data
		Site.data = Site.data || {};
		Site.data.userdata = {{ json_encode($userdata) }};
	</script>
@stop

@section("content")
	<!-- Begin summary -->
	<div class="container-fluid nopadding alt-background">

		<div class="col-md-12 nopadding">

			<div class="container p-h-30 white-background nopadding">

				@include("_partials.html.myAccountMenu")
				<div class="hide">
					<div id="newAvatarCrop"></div>
				</div>
				<!-- Begin Modals -->
					<!-- Begin Change Email Modal -->
						<div class='myModal slideDown' id='changeEmail'>
							<div class='black-background bg-olay myModal-olay'>&nbsp;</div>
							<div class='myModal-inner'>
								<div class='myModal-dialog myModal-sm'>
									<div class='myModal-content'>
										{{ Form::open(["url"=>"myaccount/settings/email", "method"=>"put"]) }}
											<div class='myModal-heading p-h-20'>
												<h4 class='myModal-title p-v-5 text-center gray-text uppercase xs-text'>
													Change Email
												</h4>
												<a href='#' data-dismiss='myModal' class='myModal-close' title='Close'><i class='fa fa-times'></i></a>
											</div>
											<div class='myModal-body p-h-20'>
												<div class="form-group">
													{{ Form::label("current_email", "Current Email") }}
													{{ Form::text("current_email", "", ["class"=>"form-control flat-it"]) }}								
												</div>
												<div class="form-group">
													{{ Form::label("password", "Password") }}
													<input type="password" name="current_password" id="password" class="form-control flat-it password" placeholder="&#149;&#149;&#149;&#149;&#149;&#149;&#149;&#149;">
												</div>
												<div class="form-group">
													{{ Form::label("new_email", "New Email") }}
													{{ Form::text("new_email", "", ["class"=>"form-control flat-it"]) }}								
												</div>
											</div>
											<div class='myModal-footer p-h-20'>
												<button type="submit" class="btn btn-block green2-background flat-it hoverable white-link">Save</button>
											</div>
										{{ Form::close() }}
									</div>						
								</div>				
							</div>
						</div>								
					<!-- End Change Email Modal -->
					<!-- Begin Change Password Modal -->
						<div class='myModal slideDown' id='changePassword'>
							<div class='black-background bg-olay myModal-olay'>&nbsp;</div>
							<div class='myModal-inner'>
								<div class='myModal-dialog myModal-sm'>
									<div class='myModal-content'>
										{{ Form::open(["url"=>"myaccount/settings/password", "method"=>"put"]) }}
											<div class='myModal-heading p-h-20'>
												<h4 class='myModal-title p-v-5 text-center gray-text uppercase xs-text'>
													Change Password
												</h4>
												<a href='#' data-dismiss='myModal' class='myModal-close' title='Close'><i class='fa fa-times'></i></a>
											</div>
											<div class='myModal-body p-h-20'>
												<div class="form-group">
													{{ Form::label("current_email", "Current Email") }}
													{{ Form::text("current_email", "", ["class"=>"form-control flat-it"]) }}								
												</div>
												<div class="form-group">
													{{ Form::label("current_password", "Current Password") }}
													<input type="password" name="current_password" id="password" class="form-control flat-it password" placeholder="&#149;&#149;&#149;&#149;&#149;&#149;&#149;&#149;">
												</div>
												<div class="form-group">
													{{ Form::label("password", "New Password") }}
													<input type="password" name="new_password" id="password" class="form-control flat-it password" placeholder="&#149;&#149;&#149;&#149;&#149;&#149;&#149;&#149;">
												</div>
												<div class="form-group">
													{{ Form::label("password_confirmation", "Repeat New Password") }}
													<input type="password" name="new_password_confirmation" id="password" class="form-control flat-it password" placeholder="&#149;&#149;&#149;&#149;&#149;&#149;&#149;&#149;">
												</div>
											</div>
											<div class='myModal-footer p-h-20'>
												<button type="submit" class="btn btn-block green2-background flat-it hoverable white-link">Save</button>
											</div>
										{{ Form::close() }}
									</div>						
								</div>				
							</div>
						</div>								
					<!-- End Change Password Modal -->
				<!-- End Modals -->

				<div class="col-md-9 m-t-70">
					<div class="container-fluid">
						<!-- Begin Row -->
						<div class="row">
							<div class="col-md-2">
								<div class="spanned_element">
									<img src="{{ (!is_null($userdata->avatar)) ? URL::to($userdata->avatar) : UR::to(Config::get('settings.avatar')) }}" alt="Avatar" class="fullwidth" id="account_avatar">
									<a href="javascript:;"
										title="Change avatar" class="newAvatarTriger" id="newAvatarTriger">
										<i class="fa fa-pencil"></i>
									</a>		
								</div>
							</div>
							<div class="col-md-10">
								<div class="spanned_element">
									<h4 class="black-text m-text bold">{{ $userdata->first_name." ".$userdata->last_name }}</h4>
									<h4 class="gray-text xs-text">{{ $userdata->email }}</h4>
									<h4 class="gray-text xs-text">{{ "<span class='bold'>".$userdata->phone->name."<span> ".$userdata->phone->number }}</h4>
								</div>
							</div>
						</div>
						<!-- End Row -->
						<!-- Begin Row -->
						<div class="row">
							{{ Form::open(["url"=>"myaccount/settings", "method"=>"put"]) }}
								@if(@$view_message["type"] == "settings.basicInfo")
									<div class="col-md-12 nopadding">										
										<div class="spanned_element">
											<p class="alert alert-{{ $view_message['level'] }} alert-dismissable flat-it m-t-50 m-b-0">
												<a href="#" class="close" data-dismiss="alert"><i class="fa fa-times"></i></a>										
												{{ $view_message['message'] }}
											</p>
										</div>
									</div>
								@endif
								<div class="col-md-12">
									<div class="row">			
										<fieldset class="m-t-20">
											<div class="col-md-12">
												<legend>Basic Info</legend>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													{{ Form::label("first_name", "First Name") }}
													{{ Form::text("first_name", $userdata->first_name, ["class"=>"form-control flat-it", "placeholder"=>"First name"]) }}
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													{{ Form::label("last_name", "Last Name") }}
													{{ Form::text("last_name", $userdata->last_name, ["class"=>"form-control flat-it", "placeholder"=>"Last name"]) }}
												</div>
											</div>
											<div class="col-md-12">
												<div class="form-group">
													{{ Form::label("phone_id", "Primary Phone") }}
										          	<select name="phone_id" class="form-control flat-it" id="phone_id">
										          		<option value="">Select Phone Number</option>
										          		@foreach($userdata->phonenumbers as $phonenumber)
										          			<option value="{{ $phonenumber->id }}" {{ ($userdata->phone->id == $phonenumber->id) ? ' selected ' : ' ' }}>
										          				{{$phonenumber->name}} - {{$phonenumber->number}}
										          			</option>
										          		@endforeach
										          	</select>
												</div>
											</div>
											<div class="col-md-12">
												<div class="form-group m-b-40">
										          	<button type="submit" class="btn green2-background hoverable white-link flat-it">Update</button>
												</div>
											</div>
										</fieldset>
									</div>
								</div>
								@if(@$view_message["type"] == "settings.change")
									<div class="col-md-12">										
										<div class="spanned_element">
											<p class="alert alert-{{ $view_message['level'] }} alert-dismissable flat-it m-t-0 m-b-15">
												<a href="#" class="close" data-dismiss="alert"><i class="fa fa-times"></i></a>										
												{{ $view_message['message'] }}
											</p>
										</div>
									</div>
								@endif
								<div class="col-md-6">
									<a href="#" 
										class="btn btn-block btn-lg flat-it white-link green2-background hoverable m-b-40"
										data-toggle="myModal"
										data-target="#changeEmail">
										Change Email
									</a>
								</div>
								<div class="col-md-6">
									<a href="#" 
										class="btn btn-block btn-lg flat-it white-link green2-background hoverable m-b-40"
										data-toggle="myModal"
										data-target="#changePassword">
										Change Password
									</a>
								</div>
							{{ Form::close() }}
						</div>
						<!-- End Row -->
					</div>
				</div>

			</div>

		</div>
		

	</div>
	<!-- End summary -->

@stop

@section("javascript")
	{{ HTML::script('assets/site/plugins/croppic/croppic.js') }}
	<!-- Javascript -->
	<script type="text/javascript">
		$(function(){
			myaccount.init();
		});
	</script>
@stop