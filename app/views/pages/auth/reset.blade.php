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
								Reset Your Password?
							</h4>
							{{ Form::open(["url"=>"api/auth/reset/".$key, "name"=>"reset-form", "id"=>"reset-form"]) }}
								<div class="spanned_element alert-container">
									<!-- Alert goes here -->
								</div>
								<div class="form-group">
									{{ Form::label("password", "New Password") }}
									<input type="password" name="password" id="password" class="form-control flat-it password" placeholder="&#149;&#149;&#149;&#149;&#149;&#149;&#149;&#149;">
								</div>
								<div class="form-group">
									{{ Form::label("password_confirmation", "Repeat New Password") }}
									<input type="password" name="password_confirmation" id="password_confirmation" class="form-control flat-it password" placeholder="&#149;&#149;&#149;&#149;&#149;&#149;&#149;&#149;">
									<div class="spanned_element">
										<a href="{{ URL::to('auth/login') }}" class="xs-text">Forgot Password?</a>										
									</div>
								</div>
								<div class="form-group">
									<input type="hidden" name="key" id="resetKey" value="{{ $key }}">
									<button type="submit" class="btn btn-block green-background hoverable white-link pull-right flat-it m-b-40">Reset</button>
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