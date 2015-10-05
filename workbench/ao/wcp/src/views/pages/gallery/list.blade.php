@extends('wcp::layout.master')
@section('jumbotron_breadcrumb')
	<ul class="breadcrumb">
	    <li>
	      <a href="{{ URL::to('admin') }}"><i class="fa fa-home"></i></a>
	    </li>
	    <li><a href="{{ URL::to('admin/gallery') }}" class="active">Gallery</a>
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

				{{-- Begin Create Gallery Modal --}}
					<div class="modal fade slide-down disable-scroll" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">

						<div class="modal-dialog">

							<div class="modal-content">
								{{ Form::open(["url"=>"admin/gallery", "role"=>"form"]) }}
									<div class="modal-header">
										<button class="close" data-dismiss="modal" aria-label="close">
											<span aria-hidden="true"><i class="fa fa-times"></i></span>
										</button>
										<h4 class="modal-title">
											New Album Form
										</h4>
									</div>

									<div class="modal-body">

										<div class="form-group form-group-default m-t-10">
											{{ Form::label("title", "Title") }}
											{{ Form::text("title", "", ["class"=>"form-control", "placeholder"=>"Enter album title here"]) }}
										</div>

										<div class="form-group form-group-default m-t-10">
											{{ Form::label("category", "Category") }}
											{{ Form::select("category", $categories, "", ["class"=>"form-control"]) }}
										</div>

										<div class="form-group form-group-default m-t-10">
											{{ Form::label("description", "Description") }}
											{{ Form::textarea("description", "", ["class"=>"form-control", "placeholder"=>"Enter gallery description here (Optional)"]) }}
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
				{{-- End Create Gallery Modal --}}

				{{-- Begin Edit Gallery Modal --}}
					@foreach($list as $item)
						<div class="modal fade slide-down disable-scroll" id="editModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">

							<div class="modal-dialog">

								<div class="modal-content">
									{{ Form::open(["url"=>"admin/gallery/".$item->id, "role"=>"form", "method"=>"put", "enctype"=>"multipart/form-data"]) }}
										<div class="modal-header">
											<button class="close" data-dismiss="modal" aria-label="close">
												<span aria-hidden="true"><i class="fa fa-times"></i></span>
											</button>
											<h4 class="modal-title">
												Edit Album Form
											</h4>
										</div>

										<div class="modal-body">

											<div class="form-group form-group-default m-t-10">
												<label for="title">Title</label>
												<input class="form-control" placeholder="Enter album title here" name="title" type="text" value="{{ $item->title }}" id="title">
											</div>

											<div class="form-group form-group-default m-t-10">
												<label for="category">Category</label>
												<select class="form-control" id="category" name="category">
													@foreach($categories as $k => $v)
														<option value="{{ $k }}" <?php if($item->category_id == $k){ echo 'selected'; } ?> >{{ $v }}</option>
													@endforeach
												</select>
											</div>

											<div class="form-group form-group-default m-t-10">
												<label for="description">Description</label>
												<textarea class="form-control" placeholder="Enter gallery description here (Optional)" name="description" cols="50" rows="10" id="description">{{ $item->description }}</textarea>
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
				{{-- End Edit Gallery Modal --}}

				{{-- Begin Delete Gallery Modal --}}
					@foreach($list as $item)
						<div class="modal fade slide-down disable-scroll" id="deleteModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $item->id }}" aria-hidden="true">

							<div class="modal-dialog modal-sm">
 
								<div class="modal-content">
									{{ Form::open(["url"=>"admin/gallery/".$item->id, "role"=>"form", "method"=>"delete"]) }}
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
												Are you sure you want to delete this item, you will lose all data asscotiated with this header.
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
				{{-- End Delete Gallery Modal --}}

				{{-- Begin Bulk Delete Gallery Modal --}}
					<div class="modal fade slide-down disable-scroll" id="bulkDeleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">

						<div class="modal-dialog modal-sm">

							<div class="modal-content">
								{{ Form::open(["url"=>"admin/gallery/bulk", "role"=>"form", "method"=>"delete"]) }}
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
				{{-- End Bulk Delete Gallery Modal --}}

			{{-- End Modals --}}
		</div>

		<div class="col-md-12">

			<div class="container-fluid">

				@foreach(array_chunk($list->all(), 4) as $row)

					@foreach($row as $item)

						<div class="col-md-3">

							<div class="panel panel-default">

								<div class="panel-heading">
									<h4 class="panel-title">
										<input type="checkbox" name="checkbox[]" value="{{ $item->id }}" class="table_checkbox">&nbsp;<a href="{{ URL::to('admin/gallery/'.$item->id) }}">{{ $item->title }}</a>
									</h4>
									<div class="panel-controls">
										<ul>
											<li>
							                    <div class="dropdown">
							                        <a id="portlet-settings" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
							                            <i class="portlet-icon portlet-icon-settings "></i>
							                        </a>

							                        <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="portlet-settings">
							                            <li>
							                            	<a href="{{ URL::to('admin/gallery/'.$item->id.'/upload') }}" class="portlet-collapse" data-toggle="none"><i class="fa fa-upload"></i>&nbsp;Upload</a>
										                </li>
										                <li>
										                	<a href="#" class="portlet-collapse" data-toggle="modal" data-target="#editModal{{ $item->id }}"><i class="fa fa-pencil"></i>&nbsp;Edit</a>
										                </li>
										                <li>
										                	<a href="#" class="portlet-close" data-toggle="modal" data-target="#deleteModal{{ $item->id }}"><i class="fa fa-times"></i>&nbsp;Delete</a>
										                </li>  
							                        </ul>
							                    </div>
							                </li>
							            </ul>
									</div>
								</div>

								<div class="panel-body">
									<?php
										$thumbnail = (!empty($item->photo->thumbnail)) ? $item->photo->thumbnail : "http://placehold.it/480x320";
									?>
									<a href="{{ URL::to('admin/gallery/'.$item->id) }}"><img src="{{ URL::to($thumbnail) }}" style="max-width:100%;"></a>
								</div>

							</div>

						</div>

					@endforeach

				@endforeach

			</div>

		</div>

	</div>
	
@stop

@section("javascript")
	{{ HTML::script("assets/wcp/plugins/masonry/masonry.pkgd.min.js") }}
	{{ HTML::script("assets/wcp/plugins/masonry/imagesloaded.pkgd.min.js") }}
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