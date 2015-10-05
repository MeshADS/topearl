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
	<div class="container-fluid nopadding alt-background overview-board">

		<div class="col-md-12 nopadding">

			<div class="container p-h-30 white-background nopadding">

				@include("_partials.html.myAccountMenu")
				<div class="hide">
					<div id="newAvatarCrop"></div>
				</div>

				<div class="col-md-9 m-t-70">
					<div class="container-fluid">
						@foreach(array_chunk($list->all(), 3) as $row)
							<!-- Begin Row -->
							<div class="row">
								@foreach($row as $item)
									<div class="col-md-4">
										<div class="spanned_element alt-background hoverable bordered radius hide-overflow">
											<img src="{{ URL::to($item->image) }}" alt="{{ $item->name }}" class="fullwidth">
											<h4 class="xs-text uppercase gray2-text p-h-15 bold">
												{{ $item->name }}
											</h4>
										</div>
									</div>
								@endforeach
							</div>
							<!-- End Row -->
						@endforeach
						<!-- Begin Row -->
							<div class="row">
								<div class="col-md-12 text-center">
									{{ $list->appends(Request::except('page'))->links() }}
								</div>
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