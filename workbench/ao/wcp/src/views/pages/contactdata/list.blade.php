@extends('wcp::layout.master')
@section('jumbotron_breadcrumb')
	<ul class="breadcrumb">
	    <li>
	      <a href="{{ URL::to('admin') }}"><i class="fa fa-home"></i></a>
	    </li>
	    <li><a href="{{ URL::to('admin/contact_data') }}" class="active">Contact Data</a>
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
	{{ HTML::style("assets/wcp/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css") }}
@stop
@section('content')

	<div class="content-fluid">

		<div class="col-md-12">
			<div class="panel panel-transparent">
				<div class="panel-body">
					<button type="button" data-toggle="modal" data-target="#createModal" class="btn btn-default btn-sm m-r-10"><i class="pg-plus"></i>&nbsp;New</button>
					<button type="button" data-toggle="modal" data-target="#bulkDeleteModal" id="bulkDeleteBtn" class="btn btn-danger btn-sm hide"><i class="fa fa-ban"></i>&nbsp;Delete</button>
				</div>
			</div>

			{{-- Begin Modals --}}

				{{-- Begin Create Data Modal --}}
					<div class="modal fade slide-down disable-scroll" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">

						<div class="modal-dialog">

							<div class="modal-content">
								{{ Form::open(["url"=>"admin/contact_data", "role"=>"form"]) }}
									<div class="modal-header">
										<button class="close" data-dismiss="modal" aria-label="close">
											<span aria-hidden="true"><i class="fa fa-times"></i></span>
										</button>
										<h4 class="modal-title">
											New Data Form
										</h4>
									</div>

									<div class="modal-body">

										<div class="form-group form-group-default m-t-10">
											{{ Form::label("name", "Name") }}
											{{ Form::text("name", "", ["class"=>"form-control", "placeholder"=>"Enter contact data name here.."]) }}
										</div>

										<div class="form-group form-group-default">
											{{ Form::label("type", "Type") }}
											{{ Form::select("type", $selectoptions, "", ["class"=>"form-control"]) }}
										</div>

										<div class="form-group form-group-default">
									        {{ Form::label("icon", "Icon") }}
									        <select class="full-width" name="icon" class="form-control" data-init-plugin="none">
									        	@foreach($icons as $key => $group)
										            <optgroup label="{{ ucwords(str_replace('-', ' ', $key)) }}">
										            	@foreach($group as $icon)
										                	<option value="{{ $icon->id }}">{{ $icon->name }}</option>
										                @endforeach
										            </optgroup>
									            @endforeach
									        </select>
									    </div>

									   <div class="form-group form-group-default m-t-10">
											{{ Form::label("data", "Data") }}
											{{ Form::text("data", "", ["class"=>"form-control", "placeholder"=>"Enter contact data here.."]) }}
										</div>

										<div class="form-group form-group-default m-t-10 color-picker-input">
											{{ Form::label("color", "Color") }}
											{{ Form::text("color", "", ["class"=>"form-control", "placeholder"=>"Select a color (Optional)"]) }}
										</div>

									</div>

									<div class="modal-footer">
										<button type="submit" class="btn btn-warning pull-right">
											Save
										</button>
										<a href="#" class="btn btn-default pull-right m-r-10" data-dismiss="modal">
											Cancel
										</a>
									</div>
								{{ Form::close() }}

							</div>

						</div>

					</div>
				{{-- End Create Data Modal --}}

				{{-- Begin Edit Data Modal --}}
					@foreach($list as $item)
						<div class="modal fade slide-down disable-scroll" id="editModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">

							<div class="modal-dialog">

								<div class="modal-content">
									{{ Form::open(["url"=>"admin/contact_data/".$item->id, "role"=>"form", "method"=>"put"]) }}
										<div class="modal-header">
											<button class="close" data-dismiss="modal" aria-label="close">
												<span aria-hidden="true"><i class="fa fa-times"></i></span>
											</button>
											<h4 class="modal-title">
												Edit Data Form
											</h4>
										</div>

										<div class="modal-body">

										<div class="form-group form-group-default m-t-10">
											<label for="name">Name</label>
											<input class="form-control" placeholder="Enter contact data name here.." name="name" type="text" value="{{ $item->name }}" id="name">
										</div>

										<div class="form-group form-group-default">
											<label for="type">Type</label>
											<select class="form-control" id="type" name="type">
												@foreach($selectoptions as $k => $v)
													<option value="{{ $k }}" <?php if ($item->type == $k) { echo 'selected'; } ?> >{{ $v }}</option>
												@endforeach
											</select>
										</div>

										<div class="form-group form-group-default">
									        <label for="icon">Icon</label>
									         <select class="full-width" name="icon" class="form-control" data-init-plugin="none">
										      	@foreach($icons as $key => $group)
										            <optgroup label="{{ ucwords(str_replace('-', ' ', $key)) }}">
										            	@foreach($group as $icon)
										                	<option value="{{ $icon->id }}" <?php if ($item->icon_id == $icon->id) { echo 'selected'; } ?>>{{ $icon->name }}</option>
										                @endforeach
										            </optgroup>
									            @endforeach
									        </select>
									    </div>

									   <div class="form-group form-group-default m-t-10">
											<label for="data">Data</label>
											<input class="form-control" placeholder="Enter contact data here.." name="data" type="text" value="{{ $item->data }}" id="data">
										</div>

										<div class="form-group form-group-default m-t-10 color-picker-input colorpicker-element">
											<label for="color">Color</label>
											<input class="form-control" placeholder="Select a color (Optional)" name="color" type="text" value="{{ $item->color }}" id="color">
										</div>

									</div>
										<div class="modal-footer">
											<button type="submit" class="btn btn-warning pull-right">
												Save
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
				{{-- End Edit Data Modal --}}

				{{-- Begin Delete Data Modal --}}
					@foreach($list as $item)
						<div class="modal fade slide-down disable-scroll" id="deleteModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $item->id }}" aria-hidden="true">

							<div class="modal-dialog">

								<div class="modal-content">
									{{ Form::open(["url"=>"admin/contact_data/".$item->id, "role"=>"form", "method"=>"delete"]) }}
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
												Are you sure you want to delete this item, you will lose all data asscotiated with this contact data.
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
				{{-- End Delete Data Modal --}}

				{{-- Begin Bulk Delete Data Modal --}}
					<div class="modal fade slide-down disable-scroll" id="bulkDeleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">

						<div class="modal-dialog">

							<div class="modal-content">

								{{ Form::open(["url"=>"admin/contact_data/bulk", "role"=>"form", "method"=>"delete"]) }}

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
				{{-- End Bulk Delete Data Modal --}}

			{{-- End Modals --}}
		</div>

		<div class="col-md-12">

			{{ Form::open(["url"=>"admin/contact_data/bulk"]) }}

				<table id="myDataTable" class="table table-hover" cellspacing="0" width="100%">
				    <thead>
				        <tr>
				            <th>
				            	<input type="checkbox" name="master_checkbox" id="master_checkbox">
				            </th>
				            <th>Name</th>
				            <th>Type</th>
				            <th>Data</th>
				            <th>Icon</th>
				            <th>Color</th>
				            <th>&nbsp;</th>
				        </tr>
				    </thead>

				    <tbody>
				    	@foreach($list as $item)
					        <tr>
					            <td>
					            	<input type="checkbox" name="checkbox[]" value="{{ $item->id }}" class="table_checkbox">
					            </td>
					            <td>{{ $item->name }}</td>
					            <td>{{ ucwords($item->type) }}</td>
					            <td>{{ $item->data }}</td>
					            <td><i class="{{ $item->icon->code }}"></i></td>
					            <td>
					            	<?php 
					            		$color = (!empty($item->color)) ? $item->color : ((!empty($item->icon->color)) ? $item->icon->color : "");
					            	?>
					            	{{ $color }} <span style="display:inline-block; background-color:{{ $color }}; width:10px; height:10px;">&nbsp;</span>
					            </td>
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
{{ HTML::script("assets/wcp/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js") }}
<script type="text/javascript">
	$(function(){

		$('.color-picker-input').colorpicker();

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