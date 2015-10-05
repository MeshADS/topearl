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
				
				<!-- Begin Modals -->
					
				<!-- End Modals -->

				<div class="col-md-9 m-t-70">
					<div class="container-fluid">
						<table class="table table-striped" cellspacing="0" width="100%">
						    <tbody>
						    	@foreach($list as $item)
							        <tr>
							            <td class="capitalize xs-text bold gray-text">
							            	{{ $item->title }}
							            </td>
							            <td>
					    					<a href="{{ URL::to('myaccount/awards/download/'.$item->id ) }}" 
					    						class="btn btn-sm pull-right flat-it green-background hoverable white-link"
					    						target="_blank">
							            		Download&nbsp;<i class="fa fa-download"></i>			    						
					    					</a>
							            </td>
							        </tr>
						        @endforeach
						    </tbody>
						</table>
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