@extends('wcp::layout.master')
@section('jumbotron_breadcrumb')
	<ul class="breadcrumb">
	    <li>
	      <a href="{{ URL::to('admin') }}"><i class="fa fa-home"></i></a>
	    </li>
	    <li><a href="{{ URL::to('admin/content_data') }}" class="active">Content Data</a>
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
						<input type="checkbox" id="master_checkbox">
					</span>
					<a href="{{ URL::to('admin/content_data/create') }}" class="btn btn-default btn-sm m-r-10"><i class="pg-plus"></i>&nbsp;Create</a>
					<button type="button" data-toggle="modal" data-target="#bulkDeleteModal" id="bulkDeleteBtn" class="btn btn-danger btn-sm hide"><i class="fa fa-ban"></i>&nbsp;Delete</button>
				</div>
			</div>

			{{-- Begin Modals --}}

				{{-- Begin Delete Data Modal --}}
					@foreach($list as $item)
						<div class="modal fade slide-down disable-scroll" id="deleteModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $item->id }}" aria-hidden="true">

							<div class="modal-dialog modal-sm">

								<div class="modal-content">
									{{ Form::open(["url"=>"admin/content_data/".$item->id, "role"=>"form", "method"=>"delete"]) }}
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

						<div class="modal-dialog modal-sm">

							<div class="modal-content">

								{{ Form::open(["url"=>"admin/content_data/bulk", "role"=>"form", "method"=>"delete"]) }}

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

			@foreach( array_chunk($list->all(), 2) as $row)
				<!-- Begin Row -->
				<div class="container-fluid" style="padding-left:0px; padding-right:0px;">
					@foreach($row as $item)
						{{-- Begin Column --}}
						<div class="col-md-6">
							{{-- Begin Panel --}}
							<div class="panel panel-default">
								{{-- Begin Panel Heading --}}
								<div class="panel-heading">
									{{-- Begin Panel Controls --}}
									<div class="panel-controls">
										<ul>
											<li><a href="{{ URL::to('admin/content_data/'.$item->id.'/edit') }}" class="portlet-collapse"><i class="fa fa-pencil"></i></a>
							                </li>
							                <li><a href="#" class="portlet-close" data-toggle="modal" data-target="#deleteModal{{ $item->id }}"><i class="fa fa-times"></i></a>
							                </li>
										</ul>
									</div>
									{{-- End Panel Controls --}}
									{{-- Begin Panel Title --}}
									<h4 class="panel-title">
										<span class="p-l-10 p-r-10 p-b-10 p-t-10">
											<input type="checkbox" class="table_checkbox" value="{{ $item->id }}">
											<span>{{ (isset($item->page->id)) ? $item->page->name : $item->page_id }}</span>
										</span>
									</h4>
									{{-- End Panel Title --}}
								</div>
								{{-- End Panel Heading --}}

								<div class="panel-body">
									<a href="{{ URL::to('admin/content_data/'.$item->id) }}"><h2>{{ $item->title }}</h2></a>
								</div>
							</div>
							{{-- End Panel --}}
						</div>
						{{-- End Column --}}
					@endforeach
				</div>
				<!-- End Row -->
			@endforeach
			<div class="container-fluid" style="padding-left:0px; padding-right:0px;">
				<div class="col-md-12 text-center">
					{{ $list->links() }}
				</div>
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