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
								Forgot Your Password?
							</h4>
							{{ Form::open(["url"=>"login", "name"=>"forgot-form", "id"=>"forgot-form"]) }}
								<div class="spanned_element alert-container">
									<!-- Alert goes here -->
								</div>
								<div class="form-group">
									{{ Form::label("email", "Email") }}
									{{ Form::text("email", "", ["class"=>"form-control flat-it", "placeholder"=>""]) }}
									<div class="spanned_element m-b-15">
										<a href="{{ URL::to('auth/login') }}" class="xs-text">Forgot Password?</a>										
									</div>
								</div>
								<div class="form-group">
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