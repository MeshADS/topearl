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
						<div class="row">
							<div class="col-md-12">
								<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
								  @foreach($list as $item)
									  <div class="panel panel-default">
										    <div class="panel-heading" role="tab" id="headingOne">
										      <h4 class="panel-title">
										        <a role="button" class="bold xs-text" data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $item->id }}" aria-expanded="true" aria-controls="collapseOne">
										          {{ $item->program->name }} - {{ $item->semester->name }} ({{ $item->year }})
										        </a>
										      </h4>
										    </div>
										    <div id="collapse{{ $item->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
										      <div class="panel-body nopadding">
										      		<table class="table m-b-0" cellspacing="0" width="100%">
													    <tbody>
													    	@foreach($item->resultslist as $item2)
														        <tr>
														            <td class="capitalize xs-text bold gray-text">
														            	{{ $item2->name }}
														            </td>
														            <td>
												    					{{ $item2->value }}
														            </td>
														        </tr>
													        @endforeach
													    </tbody>
													</table>
										      </div>
										    </div>
									  </div>
								  @endforeach
								</div>															
							</div>							
						</div>
						
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