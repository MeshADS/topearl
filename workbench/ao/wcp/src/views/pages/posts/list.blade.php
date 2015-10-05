@extends('wcp::layout.master')
@section('jumbotron_breadcrumb')
	<ul class="breadcrumb">
	    <li>
	      <a href="{{ URL::to('admin') }}"><i class="fa fa-home"></i></a>
	    </li>
	    <li><a href="{{ URL::to('admin/posts') }}" class="active">Posts</a>
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
					<a href="{{ URL::to('admin/posts/create') }}" class="btn btn-default btn-sm m-r-10"><i class="pg-plus"></i>&nbsp;Create</a>
					<button type="button" data-toggle="modal" data-target="#bulkDeleteModal" id="bulkDeleteBtn" class="btn btn-danger btn-sm hide"><i class="fa fa-ban"></i>&nbsp;Delete</button>
				</div>
			</div>

			{{-- Begin Modals --}}

				{{-- Begin Bulk Delete Staff Modal --}}
					<div class="modal fade slide-down disable-scroll" id="bulkDeleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">

						<div class="modal-dialog modal-sm">

							<div class="modal-content">
								{{ Form::open(["url"=>"admin/posts/bulk", "role"=>"form", "method"=>"delete"]) }}
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
											Are you sure you want to delete these posts, you will lose all data asscotiated with them.
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
				{{-- End Bulk Delete Staff Modal --}}

				{{-- Begin Delete Modal --}}
					@foreach($list as $item)
						<div class="modal fade slide-down disable-scroll" id="deleteModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">

							<div class="modal-dialog modal-sm">

								<div class="modal-content">
									{{ Form::open(["url"=>"admin/posts/".$item->id, "role"=>"form", "method"=>"delete"]) }}
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
												Are you sure you want to delete this post? You will lose all data asscotiated with it.
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
					@endforeach
				{{-- End Delete Modal --}}

			{{-- End Modals --}}
		</div>

		<div class="col-md-12">
			{{-- Begin List Container --}}
			<div class="container-fluid">
				@foreach(array_chunk($list->all(), 3) as $row)
					{{-- Begin Row --}}
					<div class="row">
						@foreach($row as $item)
							{{-- Begin Item --}}
							<div class="col-md-4">
								{{-- Begin Panel --}}
								<div class="panel panel-default">
									{{-- Begin Panel Heading --}}
									<div class="panel-heading">
										{{-- Begin control --}}
										<div class="panel-controls">
											<ul>
								                <li>
								                	<a href="{{ URL::to('admin/posts/'.$item->id) }}" class="portlet-maximize" data-toggle="" title="Read"><i class="fa fa-eye"></i></a>
								                </li>
								                <li>
								                	<a href="{{ URL::to('admin/posts/'.$item->id.'/edit') }}" class="portlet-maximize" data-toggle="" title="Edit"><i class="fa fa-pencil"></i></a>
								                </li>
								                <li>
								                	<a href="{{ URL::to('admin/posts/'.$item->id.'/comments') }}" class="portlet-refresh" data-toggle="" title="Comments"><i class="pg-comment"></i></a>
								                </li>
								                <li>
								                	<a href="#" class="portlet-close" data-toggle="modal" data-target="#deleteModal{{ $item->id }}" title="Delete"><i class="portlet-icon portlet-icon-close"></i></a>
								                </li>
											</ul>
										</div>
										{{-- End Control --}}
										{{-- Begin Title --}}
										<div class="panel-title">
											<input type="checkbox" class="table_checkbox" value="{{ $item->id }}">
											{{ $item->title }}
										</div>
										{{-- End Title --}}
									</div>
									{{-- End Panel Heading --}}
									{{-- Begin Panel Body --}}
									<div class="panel-body">
										{{ HTML::image($item->thumbnail, "", ["style"=>"width:100%;", "class"=>"m-b-10"]) }}
										<p>{{ $item->caption }}</p>
									</div>
									{{-- End Panel Body --}}
								</div>
								{{-- End Panel --}}
							</div>
							{{-- EEnd Item --}}
						@endforeach
					</div>
					{{-- End Row --}}
				@endforeach
			</div>
			{{-- End List Container --}}
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