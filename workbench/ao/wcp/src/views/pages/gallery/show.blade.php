@extends('wcp::layout.master')
@section('jumbotron_breadcrumb')
	<ul class="breadcrumb">
	    <li>
	      <a href="{{ URL::to('admin') }}"><i class="fa fa-home"></i></a>
	    </li>
	    <li><a href="{{ URL::to('admin/gallery') }}">Gallery</a>
	    </li>
	    <li><a href="{{ Request::url() }}" class="active">{{ ucwords($item->title) }}</a>
	    </li>
	</ul>
@stop
@section('jumbotron')
	@include('wcp::_partials.html.jumbotron')
@stop
@section("stylesheet")
	{{ HTML::style('assets/wcp/plugins/fancyBox/source/jquery.fancybox.css') }}
	{{ HTML::style('assets/wcp/plugins/fancyBox/source/helpers/jquery.fancybox-buttons.css') }}
@stop
@section('content')

	<div class="content-fluid">

		<div class="col-md-12">
			<div class="panel panel-transparent">
				<div class="panel-body">
					<span class="p-l-10 p-r-10 p-b-10 p-t-10 m-r-10">
						<input type="checkbox" name="master_checkbox" id="master_checkbox">
					</span>
					<a href="{{ URL::to('admin/gallery') }}" class="btn btn-default btn-sm m-r-10"><i class="fa fa-chevron-left"></i>&nbsp;Albums</a>
					<a href="{{ URL::to('admin/gallery/'.$item->id.'/upload') }}" class="btn btn-info btn-sm m-r-10"><i class="fa fa-upload"></i>&nbsp;Upload</a>
					<button type="button" data-toggle="modal" data-target="#editAlbumModal" class="btn btn-complete btn-sm m-r-10"><i class="fa fa-pencil"></i>&nbsp;Edit</button>
					<button type="button" data-toggle="modal" data-target="#bulkDeleteModal" id="bulkDeleteBtn" class="btn btn-danger btn-sm hide"><i class="fa fa-ban"></i>&nbsp;Delete</button>
				</div>
			</div>

			{{-- Begin Modals --}}

				{{-- Begin Edit Gallery Album Modal --}}
					<div class="modal fade slide-down disable-scroll" id="editAlbumModal" tabindex="-1" role="dialog" aria-labelledby="editAlbumModal" aria-hidden="true">

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
				{{-- End Edit Gallery Album Modal --}}

				{{-- Begin Edit Photo Modal --}}
					@foreach($item->photos as $photo)
						<div class="modal fade slide-down disable-scroll" id="editPhotoModal{{ $photo->id }}" tabindex="-1" role="dialog" aria-labelledby="editPhotoModalLabel{{ $photo->id }}" aria-hidden="true">

							<div class="modal-dialog">

								<div class="modal-content">
									{{ Form::open(["url"=>"admin/gallery/".$item->id."/".$photo->id, "role"=>"form", "method"=>"put"]) }}
										<div class="modal-header">
											<button class="close" data-dismiss="modal" aria-label="close">
												<span aria-hidden="true"><i class="fa fa-times"></i></span>
											</button>
											<h4 class="modal-title">
												Edit Photo Form
											</h4>
										</div>

										<div class="modal-body">

											<div class="form-group form-group-default m-t-10">
												<label for="caption">Caption</label>
												<input class="form-control" placeholder="Enter photo caption here (Optional)" name="caption" type="text" value="{{ $photo->caption }}" id="caption">
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
				{{-- End Edit Photo Modal --}}

				{{-- Begin Delete Photo Modal --}}
					@foreach($item->photos as $photo)
						<div class="modal fade slide-down disable-scroll" id="deleteModal{{ $photo->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $photo->id }}" aria-hidden="true">

							<div class="modal-dialog modal-sm">
 
								<div class="modal-content">
									{{ Form::open(["url"=>"admin/gallery/".$item->id."/".$photo->id, "role"=>"form", "method"=>"delete"]) }}
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
				{{-- End Delete Photo Modal --}}

				{{-- Begin Bulk Delete Photos Modal --}}
					<div class="modal fade slide-down disable-scroll" id="bulkDeleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">

						<div class="modal-dialog modal-sm">

							<div class="modal-content">
								{{ Form::open(["url"=>"admin/gallery/bulk/".$item->id, "role"=>"form", "method"=>"delete"]) }}
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
				{{-- End Bulk Delete Photos Modal --}}

			{{-- End Modals --}}
		</div>

		<div class="col-md-12">

			<div class="container-fluid">

				@foreach(array_chunk($item->photos->all(), 6) as $row)

					@foreach($row as $photo)

						<div class="col-md-2">

							<div class="panel panel-default">

								<div class="panel-heading">
									<h4 class="panel-title">
										<input type="checkbox" name="checkbox[]" value="{{ $photo->id }}" class="table_checkbox">&nbsp;<a href="{{ URL::to('admin/gallery/'.$photo->id) }}">{{ $photo->title }}</a>
									</h4>
									<div class="panel-controls">
										<ul>
							                <li><a href="#" class="portlet-collapse" data-toggle="modal" data-target="#editPhotoModal{{ $photo->id }}"><i class="fa fa-pencil"></i></a>
							                </li>
							                <li><a href="#" class="portlet-close" data-toggle="modal" data-target="#deleteModal{{ $photo->id }}"><i class="fa fa-times"></i></a>
							                </li>
							            </ul>
									</div>
								</div>

								<div class="panel-body">
									<a href="{{ URL::to($photo->image) }}" class="fancyboxGroup" rel="group" title="{{ $photo->caption }}"><img src="{{ URL::to($photo->thumbnail) }}" style="max-width:100%;"></a>
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
	{{ HTML::script("assets/wcp/plugins/fancyBox/lib/jquery.mousewheel-3.0.6.pack.js") }}
	{{ HTML::script("assets/wcp/plugins/fancyBox/source/jquery.fancybox.pack.js") }}
	{{ HTML::script("assets/wcp/plugins/fancyBox/source/helpers/jquery.fancybox-buttons.js") }}
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

			$("a.fancyboxGroup").fancybox({
				'transitionIn'	:	'elastic',
				'transitionOut'	:	'elastic',
				'speedIn'		:	600, 
				'speedOut'		:	200, 
				'overlayShow'	:	false
			});

		});
	</script>
@stop