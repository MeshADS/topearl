@extends('wcp::layout.master')
@section('jumbotron_breadcrumb')
	<ul class="breadcrumb">
	    <li>
	      <a href="{{ URL::to('admin') }}"><i class="fa fa-home"></i></a>
	    </li>
	    <li>
	      <a href="{{ URL::to('admin/menubuilder') }}">Menu Builder</a>
	    </li>
	    <li><a href="{{ Request::url() }}" class="active">{{ $parent->title }} - Submenus</a>
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
					<button type="button" data-toggle="modal" data-target="#createModal" class="btn btn-default btn-sm m-r-10"><i class="pg-plus"></i>&nbsp;New</button>
					<button type="button" data-toggle="modal" data-target="#bulkDeleteModal" id="bulkDeleteBtn" class="btn btn-danger btn-sm hide"><i class="fa fa-ban"></i>&nbsp;Delete</button>
				</div>
			</div>

			{{-- Begin Modals --}}

				{{-- Begin Create Menu Builder Modal --}}
					<div class="modal fade slide-down disable-scroll" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">

						<div class="modal-dialog">

							<div class="modal-content">
								{{ Form::open(["url"=>"admin/menubuilder", "role"=>"form"]) }}
									<div class="modal-header">
										<button class="close" data-dismiss="modal" aria-label="close">
											<span aria-hidden="true"><i class="fa fa-times"></i></span>
										</button>
										<h4 class="modal-title">
											New Menu
										</h4>
									</div>

									<div class="modal-body">

										<div class="form-group form-group-default m-t-10">
											{{ Form::label("title", "Title") }}
											{{ Form::text("title", "", ["class"=>"form-control", "placeholder"=>"E.g. Home, About Us, Contact"]) }}
										</div>

										<div class="form-group form-group-default m-t-10">
											{{ Form::label("url", "URL") }}
											{{ Form::textarea("url", "", ["class"=>"form-control", "placeholder"=>"E.g. http://acornsandoaks.org/about_us"]) }}
										</div>

										<div class="form-group form-group-default m-t-10">
											{{ Form::label("position", "Position") }}
											{{ Form::number("position", "", ["class"=>"form-control", "placeholder"=>"Value mus be numeric.", "min"=>"0"]) }}
										</div>

										<div class="form-group form-group-default m-t-10">
											{{ Form::label("ext", "External?") }}
											<input type="checkbox" name="ext" value="1" class="switchery" data-color="#f8d053"><br>
											<small><i>Check this field if the url redirects to a page outside of your domain.</i></small>

										</div>

									</div>

									<div class="modal-footer">
										<input type="hidden" name="isslave" value="1">
										<input type="hidden" name="master_id" value="{{ $parent->id }}">
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
				{{-- End Create Menu Builder Modal --}}

				{{-- Begin Edit Menu Builder Modal --}}
					@foreach($list as $item)
						<div class="modal fade slide-down disable-scroll" id="editModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">

							<div class="modal-dialog">

								<div class="modal-content">
									{{ Form::open(["url"=>"admin/menubuilder/".$item->id, "role"=>"form", "method"=>"put"]) }}
										<div class="modal-header">
										<button class="close" data-dismiss="modal" aria-label="close">
											<span aria-hidden="true"><i class="fa fa-times"></i></span>
										</button>
										<h4 class="modal-title">
											Edit Menu
										</h4>
									</div>

									<div class="modal-body">

										<div class="form-group form-group-default m-t-10">
											<label for="title">Title</label>
											<input class="form-control" placeholder="E.g. Home, About Us, Contact" name="title" type="text" value="{{ $item->title }}" id="title">
										</div>

										<div class="form-group form-group-default m-t-10">
											<label for="url">URL</label>
											<textarea class="form-control" placeholder="E.g. http://acornsandoaks.org/about_us" name="url" cols="50" rows="10" id="url">{{ $item->url }}</textarea>
										</div>

										<div class="form-group form-group-default m-t-10">
											<label for="position">Position</label>
											<input class="form-control" placeholder="Value mus be numeric." min="0" name="position" type="number" value="{{ $item->position }}" id="position">
										</div>

										<div class="form-group form-group-default m-t-10">
											<label for="ext">External?</label>
											<input type="checkbox" name="ext" value="1" class="switchery" data-color="#f8d053"
												{{ ( $item->ext == 1 ) ? 'checked' : '' }}
											><br>
											<small><i>Check this field if the url redirects to a page outside of your domain.</i></small>

										</div>

									</div>

									<div class="modal-footer">
										<input type="hidden" name="isslave" value="1">
										<input type="hidden" name="master_id" value="{{ $parent->id }}">
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
				{{-- End Edit Menu Builder Modal --}}

				{{-- Begin Delete Menu Builder Modal --}}
					@foreach($list as $item)
						<div class="modal fade slide-down disable-scroll" id="deleteModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $item->id }}" aria-hidden="true">

							<div class="modal-dialog">

								<div class="modal-content">
									{{ Form::open(["url"=>"admin/menubuilder/".$item->id, "role"=>"form", "method"=>"delete"]) }}
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
				{{-- End Delete Menu Builder Modal --}}

				{{-- Begin Bulk Delete Menu Builder Modal --}}
					<div class="modal fade slide-down disable-scroll" id="bulkDeleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">

						<div class="modal-dialog">

							<div class="modal-content">
								{{ Form::open(["url"=>"admin/menubuilder/bulk", "role"=>"form", "method"=>"delete"]) }}
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
				{{-- End Bulk Delete Menu Builder Modal --}}

			{{-- End Modals --}}
		</div>

		<div class="col-md-12">

			{{ Form::open(["url"=>"admin/menubuilder/bulk"]) }}

				<table id="myDataTable" class="table table-hover" cellspacing="0" width="100%">
				    <thead>
				        <tr>
				            <th>
				            	<input type="checkbox" name="master_checkbox" id="master_checkbox">
				            </th>
				            <th>Title</th>
				            <th>Slug</th>
				            <th>URL</th>
				            <th>External</th>
				            <th>Position</th>
				            <th>&nbsp;</th>
				        </tr>
				    </thead>

				    <tbody>
				    	@foreach($list as $item)
					        <tr>
					            <td>
					            	<input type="checkbox" name="checkbox[]" value="{{ $item->id }}" class="table_checkbox">
					            </td>
					            <td>{{ $item->title }}</td>
					            <td>{{ $item->slug }}</td>
					            <td>{{ $item->url }}</td>
					            <td>
					            	{{ ($item->ext == 1) ? 'Yes' : 'No' }}
					            </td>
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