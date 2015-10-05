@extends('wcp::layout.master')
@section('jumbotron_breadcrumb')
	<ul class="breadcrumb">
	    <li>
	      <a href="{{ URL::to('admin') }}"><i class="fa fa-home"></i></a>
	    </li>
	    <li><a href="{{ URL::to('admin/categories') }}" class="active">Categories</a>
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
			<div class="panel panel-transparent">
				<div class="panel-body">
					<a href="{{ URL::to('admin/posts') }}" class="btn btn-default btn-sm m-r-10"><i class="fa fa-chevron-left"></i><i class="fa fa-chevron-left"></i>&nbsp;List</a>

					<a href="{{ URL::to('admin/posts/'.$item->id) }}" class="btn btn-complete btn-sm m-r-10"><i class="fa fa-chevron-left"></i>&nbsp;Read</a>

					<button type="button" data-toggle="modal" data-target="#createModal" class="btn btn-info btn-sm m-r-10"><i class="pg-plus"></i>&nbsp;New</button>

					<button type="button" data-toggle="modal" data-target="#bulkDeleteModal" id="bulkDeleteBtn" class="btn btn-danger btn-sm hide"><i class="fa fa-ban"></i>&nbsp;Delete</button>
				</div>
			</div>

			{{-- Begin Modals --}}

				{{-- Begin Create Category Modal --}}
					<div class="modal fade slide-down disable-scroll" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">

						<div class="modal-dialog">

							<div class="modal-content">
								{{ Form::open(["url"=>Request::url(), "role"=>"form"]) }}
									<div class="modal-header">
										<button class="close" data-dismiss="modal" aria-label="close">
											<span aria-hidden="true"><i class="fa fa-times"></i></span>
										</button>
										<h4 class="modal-title">
											Comment Form
										</h4>
									</div>

									<div class="modal-body">

										<div class="form-group form-group-default">
											{{ Form::label("name", "Name") }}
											{{ Form::text("name", "", ["class"=>"form-control", "placeholder"=>"Enter name here.."]) }}
										</div>

										<div class="form-group">
											{{ Form::label("message", "Comment") }}
											{{ Form::textarea("message", "", ["class"=>"form-control", "placeholder"=>"Type your comment here..", "rows"=>"4"]) }}
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
				{{-- End Create Category Modal --}}

				{{-- Begin Edit Category Modal --}}
					@foreach($item->comments as $comment)
						<div class="modal fade slide-down disable-scroll" id="editModal{{ $comment->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $comment->id }}" aria-hidden="true">

							<div class="modal-dialog">

								<div class="modal-content">
									{{ Form::open(["url"=>"admin/posts/".$item->id."/comments/".$comment->id, "role"=>"form", "method"=>"put"]) }}
										<div class="modal-header">
											<button class="close" data-dismiss="modal" aria-label="close">
												<span aria-hidden="true"><i class="fa fa-times"></i></span>
											</button>
											<h4 class="modal-title">
												Edit Comment Form
											</h4>
										</div>

										<div class="modal-body">

											<div class="form-group form-group-default">
												<label for="name">Name</label>
												<input class="form-control" placeholder="Enter name here.." name="name" type="text" value="{{ $comment->name }}" id="name">
											</div>

											<div class="form-group">
												<label for="message">Comment</label>
												<textarea class="form-control" placeholder="Type your comment here.." rows="4" name="message" cols="50" id="message">{{ $comment->message }}</textarea>
											</div>

											<div class="form-group form-group-default">
												<label for="publish">Visible</label>
												@if($comment->publish === 0)
													<input type="checkbox" name="publish" value="1" class="switchery" data-color="#f8d053">
												@else
													<input type="checkbox" name="publish" value="1" class="switchery" data-color="#f8d053" checked>
												@endif
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
				{{-- End Edit Category Modal --}}

				{{-- Begin Delete Category Modal --}}
					@foreach($item->comments as $comment)
						<div class="modal fade slide-down disable-scroll" id="deleteModal{{ $comment->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $comment->id }}" aria-hidden="true">

							<div class="modal-dialog modal-sm">

								<div class="modal-content">
									{{ Form::open(["url"=>"admin/posts/".$item->id."/comments/".$comment->id, "role"=>"form", "method"=>"delete"]) }}
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
												Are you sure you want to delete this item? you will lose all data asscotiated with it.
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
				{{-- End Delete Category Modal --}}

				{{-- Begin Bulk Delete Category Modal --}}
					<div class="modal fade slide-down disable-scroll" id="bulkDeleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">

						<div class="modal-dialog modal-sm">

							<div class="modal-content">
								{{ Form::open(["url"=>"admin/posts/".$item->id."/comments/bulk", "role"=>"form", "method"=>"delete"]) }}
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
				{{-- End Bulk Delete Category Modal --}}

			{{-- End Modals --}}
		</div>

		<div class="col-md-12">

			{{ Form::open(["url"=>"admin/posts/".$item->id."/comments/bulk"]) }}

				<table id="myDataTable" class="table table-hover" cellspacing="0" width="100%">
				    <thead>
				        <tr>
				            <th>
				            	<input type="checkbox" name="master_checkbox" id="master_checkbox">
				            </th>
				            <th>Name</th>
				            <th>Email</th>
				            <th>Message</th>
				            <th>Posted on</th>
				            <th>Visibility</th>
				            <th>&nbsp;</th>
				        </tr>
				    </thead>

				    <tbody>
				    	@foreach($item->comments as $comment)
					        <tr>
					            <td>
					            	<input type="checkbox" name="checkbox[]" value="{{ $comment->id }}" class="table_checkbox">
					            </td>
					            <?php
					            	$type = ($comment->publish === 1) ? "Moderator" : "Reader";
					            ?>
					            <td>{{ $comment->name }} <small><i>({{ ucfirst($type) }})</i></small></td>
					            <td>{{ $comment->email }}</td>
					            <td>{{ strip_tags($comment->message) }}</td>
					            <td>{{ date("d/m/Y", strtotime($comment->created_at)) }}</td>
					            <td>
					            	@if($comment->publish === 0)
					            		<button type="button" class="btn btn-danger btn-sm ">Hidden</button>
					            	@else
					            		<button type="button" class="btn btn-success btn-sm">Visible</button>
					            	@endif
					            </td>
					            <td>
					            	<div class="btn-group dropdown-default">
									    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"> Actions <span class="caret"></span> </a>
									    <ul class="dropdown-menu ">
									        <li><a href="#" data-target="#editModal{{ $comment->id }}" data-toggle="modal">Edit</a>
									        </li>
									        <li><a href="#" data-target="#deleteModal{{ $comment->id }}" data-toggle="modal">Delete</a>
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

		<div class="col-md-12">
			
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