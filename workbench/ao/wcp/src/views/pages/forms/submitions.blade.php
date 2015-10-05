@extends('wcp::layout.master')
@section('jumbotron_breadcrumb')
	<ul class="breadcrumb">
	    <li>
	      <a href="{{ URL::to('admin') }}"><i class="fa fa-home"></i></a>
	    </li>
	    <li><a href="{{ URL::to('admin/forms') }}">Forms</a>
	    </li>
	    <li><a href="{{ Request::url() }}" class="active">{{$form->name}} - Submitions</a>
	    </li>
	</ul>
@stop
@section('jumbotron')
	@include('wcp::_partials.html.jumbotron')
@stop
@section("stylesheet")
	{{ HTML::style("assets/wcp/plugins/jquery-datatable/media/css/jquery.dataTables.css") }}
	{{ HTML::style("assets/wcp/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css") }}
	{{ HTML::style("assets/wcp/plugins/datatables-responsive/css/datatables.responsive.css") }}
@stop
@section('content')

	<div class="content-fluid">

		<div class="col-md-12">
			<div class="btn-group dropdown-default pull-left">
			    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"> Submitions <span class="caret"></span> </a>
			    <ul class="dropdown-menu ">
			        <li>
			        	<a href="{{ URL::to('admin/forms/'.$form->id.'/elements') }}">Elements</a>
			        </li>				       
			    </ul>
			</div>	    			
		</div>

		<div class="col-md-12">
			<div class="panel panel-transparent">
				<div class="panel-body">
					<button type="button" data-toggle="modal" data-target="#bulkDeleteModal" id="bulkDeleteBtn" class="btn btn-danger btn-sm hide pull-left"><i class="fa fa-ban"></i>&nbsp;Delete</button>
					@if(\Input::has('filter'))
						<a href="{{ Request::url() }}" class="btn btn-complete btn-sm pull-right m-l-10">
							<i class="fa fa-times"></i>&nbsp;Clear filter
						</a>
					@endif
					<div class="btn-group dropdown-default pull-right">
					    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"> Filter <span class="caret"></span> </a>
					    <ul class="dropdown-menu ">
					        <li><a href="{{ Request::url() }}?filter=asc">Older To Newer</a>
					        </li>
					        <li><a href="{{ Request::url() }}?filter=desc">Newer To Older</a>
					        </li>
					        <li><a href="#" data-toggle="modal" data-target="#customFilterModal">Custom</a>
					        </li>					       
					    </ul>
					</div>
				</div>
			</div>

			{{-- Begin Modals --}}

				{{-- Begin Custom Filter Modal --}}
					<div class="modal fade slide-down disable-scroll" id="customFilterModal" tabindex="-1" role="dialog" aria-labelledby="customfilterModalLabel" aria-hidden="true">

						<div class="modal-dialog">
							{{ Form::open(["url"=>Request::url(), "method"=>"get"]) }}
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" data-dismiss="modal" aria-label="close">
											<span aria-hidden="true"><i class="fa fa-times"></i></span>
										</button>
										<h4 class="modal-title bold">
											Custom Filter
										</h4>
									</div>

									<div class="modal-body">

										<div class="form-group form-group-default">
											{{ Form::label("from", "From") }}
											{{ Form::text("from", $customFilter["from"], ["class"=>"form-control", "placeholder"=>"E.g. mm/dd/yyyy"]) }}
										</div>

										<div class="form-group form-group-default">
											{{ Form::label("to", "To") }}
											{{ Form::text("to", $customFilter["to"], ["class"=>"form-control", "placeholder"=>"E.g. mm/dd/yyyy"]) }}
										</div>

										<div class="form-group form-group-default">
											{{ Form::label("filter", "Filter") }}
											{{ Form::select("filter", ["asc"=>"Older To Newer", "desc"=>"Newer To Older"], $customFilter["filter"] , ["class"=>"form-control"]) }}
										</div>

									</div>

									<div class="modal-footer">
										<button type="submit" class="btn btn-warning">Filter</button>
										<a href="#" class="btn btn-default pull-right m-r-10" data-dismiss="modal">
											Close
										</a>
									</div>
								</div>
							{{ Form::close() }}
						</div>

					</div>
				{{-- End Custom Filter Modal --}}

				{{-- Begin View Modal --}}
					@foreach($submitions as $item)
						<?php $item->data = unserialize($item->data) ?>
						<div class="modal fade slide-down disable-scroll" id="viewModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel{{ $item->id }}" aria-hidden="true">

							<div class="modal-dialog modal-lg">

								<div class="modal-content">
									<div class="modal-header">
										<button class="close" data-dismiss="modal" aria-label="close">
											<span aria-hidden="true"><i class="fa fa-times"></i></span>
										</button>
										<h4 class="modal-title">
											{{ date("D dS, M Y", strtotime($item->created_at))." <strong>@</strong> ".date("h:ia", strtotime($item->created_at)) }}
										</h4>
									</div>

									<div class="modal-body p-t-20">

										@foreach($item->data as $key => $value)

											<div class="panel panel-default">

												<div class="panel-heading">
													<h4 class="panel-title">
														{{ ucwords(str_replace("-", " ", $key)) }}
													</h4>													
												</div>

												<div class="panel-body">
													<div class="row">
														<div class="col-md-12">
															@if(is_array($value))
																<ul class="list-inline">
																	@foreach($value as $v)
																		<li>
																			{{ $v }}
																		</li>
																	@endforeach
																</ul>
															@else
																{{ $value }}
															@endif
														</div>														
													</div>
												</div>

												
											</div>

										@endforeach										

									</div>

									<div class="modal-footer">
										<a href="#" class="btn btn-default pull-right m-r-10" data-dismiss="modal">
											Close
										</a>
									</div>
								</div>

							</div>

						</div>
					@endforeach
				{{-- End View Modal --}}

				{{-- Begin Delete Form Modal --}}
					@foreach($submitions as $item)
						<div class="modal fade slide-down disable-scroll" id="deleteModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $item->id }}" aria-hidden="true">

							<div class="modal-dialog">

								<div class="modal-content">
									{{ Form::open(["url"=>"admin/forms/".$form->id."/submitions/".$item->id, "role"=>"form", "method"=>"delete"]) }}
										<div class="modal-header">
											<button class="close" data-dismiss="modal" aria-label="close">
												<span aria-hidden="true"><i class="fa fa-times"></i></span>
											</button>
											<h4 class="modal-title">
												<strong>Attention</strong>
											</h4>
										</div>

										<div class="modal-body">

											<p>
												Are you sure you want to delete this item?
											</p>

										</div>

										<div class="modal-footer">
											<button type="submit" class="btn btn-warning pull-right">
												Delete
											</button>
											<a href="#" class="btn btn-default pull-right m-r-10" data-dismiss="modal">
												Cancel
											</a>
										</div>
									{{ Form::close() }}

								</div>

							</div>

						</div>
					@endforeach
				{{-- End Delete Form Modal --}}

				{{-- Begin Bulk Delete Form Modal --}}
					<div class="modal fade slide-down disable-scroll" id="bulkDeleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">

						<div class="modal-dialog">

							<div class="modal-content">
								{{ Form::open(["url"=>"admin/forms/".$form->id."/submitions/bulk/", "role"=>"form", "method"=>"delete"]) }}
									<div class="modal-header">
										<button class="close" data-dismiss="modal" aria-label="close">
											<span aria-hidden="true"><i class="fa fa-times"></i></span>
										</button>
										<h4 class="modal-title">
											<strong>Attention</strong>
										</h4>
									</div>

									<div class="modal-body">

										<p>
											Are you sure you want to delete these items, you will lose all data asscotiated with them.
										</p>

									</div>

									<div class="modal-footer">
										<div class="hide bulk_delete_list">

										</div>
										<button type="submit" class="btn btn-warning pull-right">
											Bulk Delete
										</button>
										<a href="#" class="btn btn-default pull-right m-r-10" data-dismiss="modal">
											Cancel
										</a>
									</div>
								{{ Form::close() }}

							</div>

						</div>

					</div>
				{{-- End Bulk Delete Form Modal --}}

			{{-- End Modals --}}
		</div>

		<div class="col-md-12">

			{{ Form::open(["url"=>"admin/forms/bulk"]) }}

				<table id="myDataTable" class="table table-hover" cellspacing="0" width="100%">
				    <thead>
				        <tr>
				            <th>
				            	<input type="checkbox" name="master_checkbox" id="master_checkbox">
				            </th>
				            <th>Date</th>
				            <th>&nbsp;</th>
				        </tr>
				    </thead>

				    <tbody>
				    	@foreach($submitions as $item)
					        <tr>
					            <td>
					            	<input type="checkbox" name="checkbox[]" value="{{ $item->id }}" class="table_checkbox">
					            </td>
					            <td>{{ date("D dS, M Y", strtotime($item->created_at))." <strong>@</strong> ".date("h:ia", strtotime($item->created_at)) }}</td>
					            <td>
					            	<div class="btn-group dropdown-default">
									    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"> Actions <span class="caret"></span> </a>
									    <ul class="dropdown-menu ">
									        <li><a href="#" data-target="#viewModal{{ $item->id }}" data-toggle="modal">View</a>
									        </li>
									        <li><a href="#" data-target="#deleteModal{{ $item->id }}" data-toggle="modal">Delete</a>
									        </li>
									    </ul>
									</div>
					            </td>
					        </tr>
				        @endforeach
				    </tbody>
				</table>

			{{ Form::close() }}

		</div>

	</div>
	
@stop

@section("javascript")	<script type="text/javascript">
		$(function(){

			$("#master_checkbox").click(function(event){

				var checked = 0;

				if ($(this).is(":checked")) {

					$(".table_checkbox").prop("checked", true);
					$(".bulk_delete_list").html("");
					$(".table_checkbox").each(function(){
						var item_id = $(this).val();
						$("<input type='hidden' name='list[]' value='"+item_id+"' id='item"+item_id+"'>").prependTo(".bulk_delete_list");
					});
				}
				else{

					$(".table_checkbox").prop("checked", false);
					$(".bulk_delete_list").html("");
				}

				$(".table_checkbox").each(function(){

					if ($(this).is(":checked")) {
						checked ++;
					};

				});

				if (checked > 0) {
					// There are checked
					$("#bulkDeleteBtn").removeClass("hide");
				}
				else{
					// No checked
					if (!$("#bulkDeleteBtn").hasClass("hide")) {
						$("#bulkDeleteBtn").addClass("hide");
					};
				}
				
			});

			$(".table_checkbox").click(function(){

				var item_id = $(this).val(),
					checked = 0;

				if (!$(this).is(":checked")) {

					$("#master_checkbox").prop("checked", false);

					$(".bulk_delete_list #item"+item_id+"").remove();
				}
				else{

					$("<input type='hidden' name='list[]' value='"+item_id+"' id='item"+item_id+"'>").prependTo(".bulk_delete_list");
				}

				$(".table_checkbox").each(function(){

					if ($(this).is(":checked")) {
						checked ++;
					};

				});

				if (checked > 0) {
					// There are checked
					$("#bulkDeleteBtn").removeClass("hide");
				}
				else{
					// No checked
					if (!$("#bulkDeleteBtn").hasClass("hide")) {
						$("#bulkDeleteBtn").addClass("hide");
					};
				}
			});

		});
	</script>
@stop