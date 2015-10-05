@extends('wcp::layout.master')
@section('jumbotron_breadcrumb')
	<ul class="breadcrumb">
	    <li>
	      <a href="{{ URL::to('admin') }}"><i class="fa fa-home"></i></a>
	    </li>
	    <li><a href="{{ URL::to('admin/forms') }}">Forms</a>
	    </li>
	    <li><a href="{{ Request::url() }}" class="active">{{$form->name}} - Elements</a>
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
	<style type="text/css" media="screen">
		.new-element-dl-btn{
			position:absolute;
			right:0px;
			bottom:17px;
			width:30px;
			height:30px;
			line-height:30px;
			text-align:center;
			background-color: #ccc;
			color:#fff;
			border-radius:30px;
			-moz-border-radius:30px;
			-ms-border-radius:30px;
			-o-border-radius:30px;
			-webkit-border-radius:30px;
		}
		.new-element-dl-btn:hover, .new-element-dl-btn:focus, .new-element-dl-btn:visited{
			color:#fff;
		}
		.listValues >.row{
			position:relative;
			float:left;
			width:100%;
		}

		.listValues >.row .delMessage{
			position: absolute;
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
			background-color: #fff;
			color: #ff0000;
			font-size: 1em;
			font-style: italic;
			opacity:0.8;
			box-sizing: border-box;
			-moz-box-sizing: border-box;
			-webkit-box-sizing: border-box;
			-ms-box-sizing: border-box;
			-o-box-sizing: border-box;
			display:none;
		}
	</style>
@stop
@section('content')

	<div class="content-fluid">

		<div class="col-md-12">
			<div class="btn-group dropdown-default pull-left">
			    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"> Elements <span class="caret"></span> </a>
			    <ul class="dropdown-menu ">
			        <li>
			        	<a href="{{ URL::to('admin/forms/'.$form->id.'/submitions') }}">Submitions</a>
			        </li>				       
			    </ul>
			</div>	    			
		</div>

		<div class="col-md-12">
			<div class="panel panel-transparent">
				<div class="panel-body">
					<button type="button" data-toggle="modal" data-target="#addModal" class="btn btn-default btn-sm m-r-10"><i class="pg-plus"></i>&nbsp;Add</button>
					<button type="button" data-toggle="modal" data-target="#bulkDeleteModal" id="bulkDeleteBtn" class="btn btn-danger btn-sm hide"><i class="fa fa-ban"></i>&nbsp;Delete</button>
				</div>
			</div>

			{{-- Begin Modals --}}

				{{-- Begin Create Form Modal --}}
					<div class="modal fade slide-down disable-scroll" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">

						<div class="modal-dialog">

							<div class="modal-content">
									<div class="modal-header">
										<button class="close" data-dismiss="modal" aria-label="close">
											<span aria-hidden="true"><i class="fa fa-times"></i></span>
										</button>
										<h4 class="modal-title">
											<strong>Select Element Type</strong> 
										</h4>
									</div>

									<div class="modal-body p-t-30">
										<div class="row">
											@foreach($elementtypes as $key => $elementtype)
												<div class="col-md-6">																								
													<button type="button" 
															class="btn btn-lg btn-block btn-default m-b-15"
															data-toggle="modal"
															data-target="#{{$key}}ElementModal"
															data-dismiss="modal">
														{{ $elementtype }}
													</button>
												</div>											
											@endforeach
										</div>
									</div>

									<div class="modal-footer">
										<!-- Empty footer -->
									</div>
							</div>

						</div>

					</div>
				{{-- End Create Form Modal --}}

				{{-- Begin New Element Form Modal --}}
					@foreach($elementtypes as $k => $elementtype)
						<div class="modal fade slide-down disable-scroll" id="{{$k}}ElementModal" tabindex="-1" role="dialog" aria-labelledby="{{ $k }}ElementModalLabel" aria-hidden="true">

							<div class="modal-dialog">

								<div class="modal-content">
									{{ Form::open(["url"=>"admin/forms/".$form->id."/elements/".$k, "role"=>"form", "class"=>"newform".$k]) }}
									<div class="modal-header">
										<button class="close" data-dismiss="modal" aria-label="close">
											<span aria-hidden="true"><i class="fa fa-times"></i></span>
										</button>
										<h4 class="modal-title">New {{ $elementtype }}</h4>
									</div>

									<div class="modal-body">

										@include("wcp::_partials.forms.builder.".$k)

									</div>

									<div class="modal-footer">
										<!-- Empty footer -->
									</div>
									{{ Form::close() }}

								</div>

							</div>

						</div>
					@endforeach
				{{-- End New Element Form Modal --}}

				{{-- Begin Edit Form Modal --}}
					@foreach($elements as $item)
						<div class="modal fade slide-down disable-scroll" id="editModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">

							<div class="modal-dialog">

								<div class="modal-content">
									{{ Form::open(["url"=>"admin/forms/".$form->id."/elements/".$item->id, "role"=>"form", "method"=>"put"]) }}
										<div class="modal-header">
										<button class="close" data-dismiss="modal" aria-label="close">
											<span aria-hidden="true"><i class="fa fa-times"></i></span>
										</button>
										<h4 class="modal-title">
											Edit {{ str_replace("-", " ", ucwords($item->type)) }} Element
										</h4>
									</div>

									<div class="modal-body">

										@include("wcp::_partials.forms.builder.edit.".$item->type)

									</div>

									<div class="modal-footer">
										<!-- Empty footer -->
									</div>
									{{ Form::close() }}

								</div>

							</div>

						</div>
					@endforeach
				{{-- End Edit Form Modal --}}

				{{-- Begin Delete Form Modal --}}
					@foreach($elements as $item)
						<div class="modal fade slide-down disable-scroll" id="deleteModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $item->id }}" aria-hidden="true">

							<div class="modal-dialog">

								<div class="modal-content">
									{{ Form::open(["url"=>"admin/forms/".$form->id."/elements/".$item->id, "role"=>"form", "method"=>"delete"]) }}
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
												Are you sure you want to delete this item, you will lose all data asscotiated with it.
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
								{{ Form::open(["url"=>"admin/forms/".$form->id."/elements/bulk", "role"=>"form", "method"=>"delete"]) }}
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
				            <th>Name</th>
				            <th>Type</th>
				            <th>Group</th>
				            <th>Position</th>
				            <th>&nbsp;</th>
				        </tr>
				    </thead>

				    <tbody>
				    	@foreach($elements as $item)
					        <tr>
					            <td>
					            	<input type="checkbox" name="checkbox[]" value="{{ $item->id }}" class="table_checkbox">
					            </td>
					            <td>{{ (strlen($item->name) > 0) ? ucwords($item->name) : ucwords(str_replace('-', ' ', $item->type)) }}</td>
					            <td>{{ ucwords(str_replace('-', ' ', $item->type)) }}</td>
					            <td>{{ (strlen($item->groupie) > 0) ? ucwords($item->groupie) : 'None' }}</td>
					            <td>{{ $item->position }}</td>
					            <td>
					            	<div class="btn-group dropdown-default">
									    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"> Actions <span class="caret"></span> </a>
									    <ul class="dropdown-menu ">
									        <li><a href="#" data-target="#editModal{{ $item->id }}" data-toggle="modal">Edit</a>
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

@section("javascript")
	{{ HTML::script("assets/wcp/plugins/boostrap-form-wizard/js/jquery.bootstrap.wizard.min.js") }}
	{{ HTML::script("assets/wcp/plugins/jquery-validation/js/jquery.validate.min.js") }}
	{{ HTML::script("assets/wcp/js/form_builder.js") }}
	<script type="text/javascript">
		$(function(){
			form_builder.init();
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