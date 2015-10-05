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
	<div class="container-fluid nopadding alt-background messages-page">

		<div class="col-md-12 nopadding">

			<div class="container p-h-30 white-background nopadding">

				@include("_partials.html.myAccountMenu")
				
				<!-- Begin Modals -->
					
				<!-- End Modals -->

				<div class="col-md-9 m-t-70">
					<div class="container-fluid nopadding">
						<div class="row">
							<div class="col-md-2 col-xs-4">
								<div class="spanned_element">
									<img src="{{ (!is_null($item->sender->avatar)) ? URL::to($item->sender->avatar) : URL::to(Config::get('settings.avatar')) }}" alt="Avatar" class="fullwidth">
								</div>								
							</div>
							<div class="col-md-10 col-xs-8">
								<h4 class="gray-text xs-text text-right">{{ date("d M Y", strtotime($item->created_at)) }}</h4>
								<h4 class="black-text s-text bold">{{ $item->sender->first_name." ".$item->sender->last_name }}</h4>					
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<p class="spanned_element m-t-10">
						{{ $item->body }}
					</p>
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