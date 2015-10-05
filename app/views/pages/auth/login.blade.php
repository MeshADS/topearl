@extends("layout.master")
@section("title") Forgot Password :: {{ $basicdata->fullname }} @stop
@include("_partials.html.default_meta")
@section("page") auth @stop
@section("stylesheet")
	<!-- Stylesheet -->
@stop
@section("bootdata")
	<!-- Boot data -->
@stop

@section("content")
	<!-- Begin summary -->
	<div class="container-fluid nopadding alt-background alt">

		<div class="col-md-12 nopadding">

			<div class="container p-h-30 nopadding">

					<div class="col-md-6 col-md-offset-3">
						<div class="spanned_element white-background bordered m-v-100 p-h-40">
							<h4 class="myModal-title p-v-10 normal text-center capitalize m-v-10">
								Login?
							</h4>
							{{ Form::open(["url"=>"api/auth/login", "name"=>"login-form", "id"=>"login-form"]) }}
								<div class="spanned_element alert-container">
									<!-- Alert goes here -->
								</div>
								<div class="form-group">
									{{ Form::label("email", "Email") }}
									{{ Form::text("email", "", ["class"=>"form-control", "placeholder"=>""]) }}
								</div>
								<div class="form-group">
									{{ Form::label("password", "Password") }}
									<input type="password" name="password" id="password" class="password form-control" placeholder="&#149;&#149;&#149;&#149;&#149;&#149;&#149;&#149;&#149;">
									<div class="spanned_element">
										<a href="{{ URL::to('auth/forgotPassword') }}" class="xs-text">Forgot Password?</a>										
									</div>
								</div>
								<div class="form-group text-right">
									<input type="checkbox" name="remember" id="remember" value="1">
									{{ Form::label("remember", "Remember Me?") }}
								</div>
								<div class="form-group">
									<input type="hidden" name="return" id="return" value="{{ Input::get('return', URL::to('myaccount')) }}">
									<button type="submit" class="btn btn-block green-background hoverable white-link pull-right flat-it m-b-40">Login</button>
								</div>
							{{ Form::close() }}
						</div>
					</div>				

			</div>

		</div>
		

	</div>
	<!-- End summary -->

@stop

@section("javascript")
	<!-- Javascript -->
	<script type="text/javascript">
		$(function(){
		});
	</script>
@stop