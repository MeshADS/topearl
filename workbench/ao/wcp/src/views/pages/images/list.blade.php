@extends('wcp::layout.master')
@section('jumbotron_breadcrumb')
	<ul class="breadcrumb">
	    <li>
	      <a href="{{ URL::to('admin') }}"><i class="fa fa-home"></i></a>
	    </li>
	    <li><a href="{{ URL::to('admin/images') }}" class="active">Images</a>
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

			<?php
				$link_types = [
								""=>"Select link type",
								"1" => "Text",
								"2" => "Button",
								];
			?>

				{{-- Begin Create Image Modal --}}
					<div class="modal fade slide-down disable-scroll" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">

						<div class="modal-dialog">

							<div class="modal-content">
								{{ Form::open(["url"=>"admin/images", "role"=>"form", "enctype"=>"multipart/form-data"]) }}
									<div class="modal-header">
										<button class="close" data-dismiss="modal" aria-label="close">
											<span aria-hidden="true"><i class="fa fa-times"></i></span>
										</button>
										<h4 class="modal-title">
											New Image Form
										</h4>
									</div>

									<div class="modal-body">

										<div class="form-group form-group-default m-t-10">
											{{ Form::label("image", "Image") }}
											{{ Form::file("image", "", ["class"=>"form-control"]) }}
										</div>

										<div class="form-group form-group-default m-t-10">
											{{ Form::label("title", "Title") }}
											{{ Form::text("title", "", ["class"=>"form-control", "placeholder"=>"Enter title here"]) }}
										</div>

										<div class="form-group form-group-default m-t-10">
											{{ Form::label("group_id", "Group") }}
											{{ Form::select("group_id", $groups, "", ["class"=>"form-control", "placeholder"=>"E.g. home, about, contact"]) }}
										</div>

										<div class="form-group form-group-default m-t-10">
											{{ Form::label("caption", "Caption") }}
											{{ Form::textarea("caption", "", ["class"=>"form-control", "placeholder"=>"Enter your caption here (Optional)"]) }}
										</div>

										<div class="form-group form-group-default m-t-10">
											{{ Form::label("order", "Order") }}
											{{ Form::number("order", "", ["class"=>"form-control", "placeholder"=>"E.g. 1, 2, 3. (Optional)", "min"=>"0"]) }}
											<small>This will determine this images position if in a slide</small>
										</div>

										<div class="panel panel-transparent">
											<div class="panel-heading" style="padding-left:0px; padding-right:0px;">
												<h4 Class="panel-title">
													Link (Optional)
												</h4>
											</div>
											<div class="panel-body" style="padding-left:0px; padding-right:0px;">
												<div class="form-group form-group-default m-t-10">
													{{ Form::label("link_url", "URL") }}
													{{ Form::text("link_url", "", ["class"=>"form-control", "placeholder"=>"E.g. http://domain.com"]) }}
												</div>
												<div class="form-group form-group-default m-t-10">
													{{ Form::label("link_title", "Title") }}
													{{ Form::text("link_title", "", ["class"=>"form-control", "placeholder"=>"E.g. Home (Requires a link)"]) }}
												</div>
												<div class="form-group form-group-default m-t-10">
													{{ Form::label("link_type", "Type") }}
													{{ Form::select("link_type", $link_types, "", ["class"=>"form-control"]) }}
													<small>Requires a link title.</small>
												</div>
												<div class="form-group form-group-default m-t-10">
													{{ Form::label("link_color", "Color") }}
													{{ Form::select("link_color", $coloroptions, "", ["class"=>"form-control"]) }}
													<small>Requires a link title.</small>
												</div>
											</div>
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
				{{-- End Create Image Modal --}}

				{{-- Begin Preview Image Modal --}}
					@foreach($list as $item)
						<div class="modal fade fill-in" id="previewModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel{{ $item->id }}" aria-hidden="true">
							<button class="close" data-dismiss="modal" aria-label="close">
								<span aria-hidden="true"><i class="fa fa-times"></i></span>
							</button>

							<div class="modal-dialog">

								<div class="modal-content">
									{{ Form::open(["url"=>"admin/images/".$item->id, "role"=>"form", "method"=>"put", "enctype"=>"multipart/form-data"]) }}
										<div class="modal-header">
											{{-- Empty image --}}
										</div>

										<div class="modal-body">

											<ul class="list-unstyled">

												<li>
													<h4><strong>{{ $item->page }}</strong></h4>
												</li>

												<li>
													<img src="{{ URL::to($item->image) }}" style="max-width:100%;">
												</li>

												@if(!empty($item->caption))
													<li>
														<p>
															{{ $item->caption }}
														</p>
													</li>
												@endif

											</ul>
											
										</div>

										<div class="modal-footer">
											{{-- Empty footer --}}
										</div>
									{{ Form::close() }}

								</div>

							</div>

						</div>
					@endforeach
				{{-- End Preview Image Modal --}}

				{{-- Begin Edit Image Modal --}}
					@foreach($list as $item)
						<div class="modal fade slide-down disable-scroll" id="editModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">

							<div class="modal-dialog">

								<div class="modal-content">
									{{ Form::open(["url"=>"admin/images/".$item->id, "role"=>"form", "method"=>"put", "enctype"=>"multipart/form-data"]) }}
										<div class="modal-header">
											<button class="close" data-dismiss="modal" aria-label="close">
												<span aria-hidden="true"><i class="fa fa-times"></i></span>
											</button>
											<h4 class="modal-title">
												Edit Image Form
											</h4>
										</div>

										<div class="modal-body">

											<div class="form-group form-group-default m-t-10">
												{{ Form::label("image", "Image") }}
												{{ Form::file("image", "", ["class"=>"form-control"]) }}
											</div>

											<div class="form-group form-group-default m-t-10">
												<label for="title">Title</label>
												<input class="form-control" placeholder="Enter title here" name="title" type="text" value="{{ $item->title }}" id="title">
											</div>

											<div class="form-group form-group-default m-t-10">
												<label for="group_id">Group</label>
												<select name="group_id" id="group_id" class="form-control">
													@foreach($groups as $k => $v)
														<option value="{{ $k }}" {{ ($k == $item->group_id) ? ' selected ' : '' }} >{{ $v }}</option>
													@endforeach
												</select>
											</div>

											<div class="form-group form-group-default m-t-10">
												<label for="caption">Caption</label>
												<textarea class="form-control" placeholder="Enter your caption here (Optional)" name="caption" cols="50" rows="10" id="caption">{{ $item->caption }}</textarea>
											</div>

											<div class="form-group form-group-default m-t-10">
												<label for="order">Order</label>
												<input class="form-control" placeholder="E.g. 1, 2, 3. (Optional)" min="0" name="order" type="number" value="{{ $item->order }}" id="order">
												<small>This will determine this images position if in a slide</small>
											</div>

											<div class="panel panel-transparent">
												<div class="panel-heading" style="padding-left:0px; padding-right:0px;">
													<h4 class="panel-title">
														Link (Optional)
													</h4>
												</div>
												<div class="panel-body" style="padding-left:0px; padding-right:0px;">
													<div class="form-group form-group-default m-t-10">
														<label for="link_url">URL</label>
														<input class="form-control" placeholder="E.g. http://domain.com" name="link_url" type="text" value="{{ $item->link_url }}" id="link_url">
													</div>
													<div class="form-group form-group-default m-t-10">
														<label for="link_title">Title</label>
														<input class="form-control" placeholder="E.g. Home (Requires a link)" name="link_title" type="text" value="{{ $item->link_title }}" id="link_title">
													</div>
													<div class="form-group form-group-default m-t-10">
														<label for="link_type">Type</label>
														<select class="form-control" id="link_type" name="link_type">
															@foreach($link_types as $k => $v)
																<option value="{{ $k }}" <?php if($k == $item->link_type) echo "selected=\"selected\""; ?>>{{ $v }}</option>
															@endforeach
														</select>
														<small>Requires a link title.</small>
													</div>
													<div class="form-group form-group-default m-t-10">
														<label for="link_color">Color</label>
														<select class="form-control" id="link_color" name="link_color">
															@foreach($coloroptions as $k => $v)
																<option value="{{ $k }}" {{ ($item->link_color == $k) ? 'selected' : '' }}>{{ $v }}</option>
															@endforeach
														</select>
													</div>
												</div>
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
				{{-- End Edit Image Modal --}}

				{{-- Begin Delete Image Modal --}}
					@foreach($list as $item)
						<div class="modal fade slide-down disable-scroll" id="deleteModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $item->id }}" aria-hidden="true">

							<div class="modal-dialog modal-sm">
 
								<div class="modal-content">
									{{ Form::open(["url"=>"admin/images/".$item->id, "role"=>"form", "method"=>"delete"]) }}
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
												Are you sure you want to delete this item, you will lose all data asscotiated with this image.
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
				{{-- End Delete Image Modal --}}

				{{-- Begin Bulk Delete Image Modal --}}
					<div class="modal fade slide-down disable-scroll" id="bulkDeleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">

						<div class="modal-dialog modal-sm">

							<div class="modal-content">
								{{ Form::open(["url"=>"admin/images/bulk", "role"=>"form", "method"=>"delete"]) }}
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
				{{-- End Bulk Delete Image Modal --}}

			{{-- End Modals --}}
		</div>

		<div class="col-md-12">

			<div class="container-fluid masonryContainer">

				@foreach(array_chunk($list->all(), 4) as $row)

					<div class="row">
						
						@foreach($row as $item)

							<div class="col-md-3 item">

								<div class="panel panel-default">

									<div class="panel-heading">
										<h4 class="panel-title">
											<input type="checkbox" name="checkbox[]" value="{{ $item->id }}" class="table_checkbox">&nbsp;<a href="#" data-toggle="modal" data-target="#previewModal{{ $item->id }}">
											{{ (isset($item->group->id)) ? $item->group->name : $item->group_id }} / {{ $item->title }}
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
										<a href="#" data-toggle="modal" data-target="#previewModal{{ $item->id }}"><img src="{{ URL::to($item->image) }}" style="max-width:100%;"></a>
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
	{{ HTML::script("assets/wcp/plugins/masonry/masonry.pkgd.min.js") }}
	{{ HTML::script("assets/wcp/plugins/masonry/imagesloaded.pkgd.min.js") }}
	<script type="text/javascript">
		$(function(){

			var masonryContainer = $(".masonryContainer");

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