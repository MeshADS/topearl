@extends('wcp::layout.master')
@section('jumbotron_breadcrumb')
	<ul class="breadcrumb">
	    <li>
	      <a href="{{ URL::to('admin') }}"><i class="fa fa-home"></i></a>
	    </li>
	    <li><a href="{{ URL::to('admin/programs') }}" class="active">Programs</a>
	    </li>
	</ul>
@stop
@section('jumbotron')
	@include('wcp::_partials.html.jumbotron')
@stop
@section("stylesheet")
@stop
@section('content')

	<div class="content-fluid">

		<div class="col-md-12">
			<div class="panel panel-transparent">
				<div class="panel-body">
					<span class="p-l-10 p-r-10 p-b-10 p-t-10 m-r-10">
						<input type="checkbox" name="master_checkbox" id="master_checkbox">
					</span>
					<button type="button" data-toggle="modal" data-target="#createModal" class="btn btn-default btn-sm m-r-10"><i class="pg-plus"></i>&nbsp;New</button>
					<button type="button" data-toggle="modal" data-target="#bulkDeleteModal" id="bulkDeleteBtn" class="btn btn-danger btn-sm hide"><i class="fa fa-ban"></i>&nbsp;Delete</button>
				</div>
			</div>

			{{-- Begin Modals --}}

				{{-- Begin Create Program Modal --}}
					<div class="modal fade slide-down disable-scroll" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">

						<div class="modal-dialog">

							<div class="modal-content">
								{{ Form::open(["url"=>"admin/programs", "role"=>"form", "enctype"=>"multipart/form-data"]) }}
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="close">
											<span aria-hidden="true"><i class="fa fa-times"></i></span>
										</button>
										<h4 class="modal-title">
											New Program Form
										</h4>
									</div>

									<div class="modal-body">

										<div class="form-group form-group-default m-t-10">
											{{ Form::label("image", "Image") }}
											{{ Form::file("image", "", ["class"=>"form-control", "placeholder"=>"Select image to upload"]) }}
											<small class="bold">Width:640px - Height:480px</small>
										</div>

										<div class="form-group form-group-default m-t-10">
											{{ Form::label("name", "Name") }}
											{{ Form::text("name", "", ["class"=>"form-control", "placeholder"=>"Enter program name here.."]) }}
										</div>

										<div class="form-group form-group-default">
											{{ Form::label("type_id", "Type") }}
											{{ Form::select("type_id", $selectoptions, "", ["class"=>"form-control"]) }}
										</div>

										<div class="form-group form-group-default m-t-10">
											{{ Form::label("position", "Order") }}
											{{ Form::number("position", "", ["class"=>"form-control", "placeholder"=>"E.g. 1, 2, 3. etc."]) }}
											<small class="bold">Item position when listed.</small>
										</div>

										<div class="form-group form-group-default m-t-10">
											{{ Form::label("description", "Description") }}
											{{ Form::textarea("description", "", ["class"=>"form-control", "rows"=>"3", "placeholder"=>"Enter program description here.."]) }}
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
				{{-- End Create Program Modal --}}

				{{-- Begin Edit Program Modal --}}
					@foreach($list as $item)
						<div class="modal fade slide-down disable-scroll" id="editModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">

							<div class="modal-dialog">

								<div class="modal-content">
									{{ Form::open(["url"=>"admin/programs/".$item->id, "role"=>"form", "method"=>"put", "enctype"=>"multipart/form-data"]) }}
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="close">
												<span aria-hidden="true"><i class="fa fa-times"></i></span>
											</button>
											<h4 class="modal-title">
												Edit Program Form
											</h4>
										</div>

										<div class="modal-body">

											<div class="form-group form-group-default m-t-10">
												<label for="image">Image</label>
												<input name="image" type="file" id="image">
												<small class="bold">Width:640px - Height:480px</small>
											</div>

											<div class="form-group form-group-default m-t-10">
												<label for="name">Name</label>
												<input class="form-control" placeholder="Enter program name here.." name="name" type="text" value="{{ $item->name }}" id="name">
											</div>

											<div class="form-group form-group-default">
												<label for="type_id">Type</label>
												<select class="form-control" id="type_id" name="type_id">
													@foreach($selectoptions as $k => $v)
														<option value="{{ $k }}" {{ ($k == $item->type_id) ? 'selected' : '' }} >{{ $v }}</option>
													@endforeach
												</select>
											</div>

											<div class="form-group form-group-default m-t-10">
												<label for="position">Order</label>
												<input class="form-control" placeholder="E.g. 1, 2, 3. etc." name="position" type="number" value="{{ $item->position }}" id="position">
												<small class="bold">Item position when listed.</small>
											</div>

											<div class="form-group form-group-default m-t-10">
												<label for="description">Description</label>
												<textarea class="form-control" rows="3" placeholder="Enter program description here.." name="description" cols="50" id="description">{{ $item->description }}</textarea>
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
				{{-- End Edit Program Modal --}}

				{{-- Begin Delete Program Modal --}}
					@foreach($list as $item)
						<div class="modal fade slide-down disable-scroll" id="deleteModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $item->id }}" aria-hidden="true">

							<div class="modal-dialog modal-sm">

								<div class="modal-content">
									{{ Form::open(["url"=>"admin/programs/".$item->id, "role"=>"form", "method"=>"delete"]) }}
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="close">
												<span aria-hidden="true"><i class="fa fa-times"></i></span>
											</button>
											<h4 class="modal-title">
												<strong>Attention</strong>
											</h4>
										</div>

										<div class="modal-body">

											<p>
												Are you sure you want to delete this item, you will lose all data asscotiated with this program.
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
				{{-- End Delete Program Modal --}}

				{{-- Begin Bulk Delete Program Modal --}}
					<div class="modal fade slide-down disable-scroll" id="bulkDeleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">

						<div class="modal-dialog modal-sm">

							<div class="modal-content">
								{{ Form::open(["url"=>"admin/programs/bulk", "role"=>"form", "method"=>"delete"]) }}
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="close">
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
				{{-- End Bulk Delete Program Modal --}}

			{{-- End Modals --}}
		</div>

		<div class="col-md-12">
			<div class="container-fluid masonryContainer">
				@foreach(array_chunk($list->all(), 3) as $row)
					<div class="row">
						@foreach($row as $item)
							<div class="col-md-4 item">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title" title="{{ $item->type->title }}">
											<input type="checkbox" name="checkbox[]" value="{{ $item->id }}" class="table_checkbox">&nbsp;<a href="#" data-toggle="modal" data-target="#previewModal{{ $item->id }}">
											{{ $item->name }}
										</a>
										</h4>
										<div class="panel-controls">
											<ul>
								                <li><a href="#" class="portlet-collapse" data-toggle="modal" data-target="#editModal{{ $item->id }}"><i class="fa fa-pencil"></i></a>
								                </li>
								                <li><a href="#" class="portlet-close" data-toggle="modal" data-target="#deleteModal{{ $item->id }}"><i class="fa fa-times"></i></a>
								                </li>
								            </ul>
										</div>
									</div>
									<div class="panel-body">
										<img src="{{ URL::to($item->image) }}" class="img-responsive">
									</div>
								</div>
							</div>
						@endforeach
					</div>
				@endforeach
			</div>
		</div>

	</div>
	
@stop

@section("javascript")
	<script type="text/javascript">
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