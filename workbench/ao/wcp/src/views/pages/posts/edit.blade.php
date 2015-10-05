@extends('wcp::layout.master')
@section('jumbotron_breadcrumb')
	<ul class="breadcrumb">
	    <li>
	      <a href="{{ URL::to('admin') }}"><i class="fa fa-home"></i></a>
	    </li>
	    <li><a href="{{ URL::to('admin/posts') }}" class="">Posts</a>
	    </li>
	    <li><a href="{{ URL::to('admin/posts/'.$item->id) }}" class="">{{ $item->title }}</a>
	    </li>
	    <li><a href="{{ Request::url() }}" class="active">Edit</a>
	    </li>
	</ul>
@stop
@section('jumbotron')
	@include('wcp::_partials.html.jumbotron')
@stop
@section("stylesheet")
	{{ HTML::style('assets/wcp/plugins/summernote/css/summernote.css') }}
@stop
@section('content')

	<div class="content-fluid">

		<div class="col-md-12">
			<div class="panel panel-transparent">
				<div class="panel-body">
					<a href="{{ URL::to('admin/posts') }}" class="btn btn-default btn-sm m-r-10"><i class="fa fa-chevron-left"></i><i class="fa fa-chevron-left"></i>&nbsp;List</a>

					<a href="{{ URL::to('admin/posts/'.$item->id) }}" class="btn btn-complete btn-sm m-r-10"><i class="fa fa-chevron-left"></i>&nbsp;View</a>

					<button type="button" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm"><i class="fa fa-ban"></i>&nbsp;Delete</button>

				</div>
			</div>
		</div>

		{{-- Begin Modals --}}
			{{-- Begin Delete Icon Modal --}}
				<div class="modal fade slide-down disable-scroll" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $item->id }}" aria-hidden="true">

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
										Are you sure you want to delete this article? You will lose all data asscotiated with it.
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
			{{-- End Delete Icon Modal --}}
		{{-- End Modal --}}

		<div class="col-md-12">

			{{ Form::open(["url"=>"admin/posts/".$item->id, "enctype"=>"multipart/form-data", "method"=>"put"]) }}
				<div class="container-fluid">

					<div class="col-md-8">

						<div class="form-group form-group-default">

							{{ Form::label("title", "Title")}}
							{{ Form::text("title", $item->title, ["class"=>"form-control"]) }}

						</div>

						<div class="form-group">
							{{ Form::textarea("body", $item->body, ["class"=>"form-control", "id"=>"body", "rows"=>"6"]) }}

						</div>

					</div>

					<div class="col-md-4">

						<div class="form-group form-group-default">
							{{ Form::label("image", "Select Image") }}
							{{ Form::file("image", "", ["class"=>"form-control"]) }}
							<small class="m-t-10">
								Select a new image to change current image.
							</small>
						</div>

						<div class="form-group">

							<label for="caption">Caption
								<span data-toggle="tooltip" data-placement="top" title="This helps to boost search engine optimisation and also will serve as an introduction for your post.">
									<i class="fa fa-question-circle"></i>
								</span> 
							</label>
							{{ Form::textarea("caption", $item->caption, ["class"=>"form-control", "id"=>"caption", "placeholder"=>"Enter your caption here...", "rows"=>"4"]) }}
						</div>

						<div class="form-group form-group-default">

							{{ Form::label('category_id', "Category") }}
							{{ Form::select("category_id", $categories, $item->category->id, ["class"=>"form-control"]) }}
						</div>


						<div class="form-group">
							{{ Form::label("publish", "Publish now?") }}

							@if($item->publish_state === 0)
								<input type="checkbox" name="publish" value="1" class="switchery" data-color="#f8d053">
							@else
								<input type="checkbox" name="publish" value="1" class="switchery" data-color="#f8d053" checked>
							@endif
						</div>

						<div class="form-group">

							<button type="submit" class="btn btn-warning btn-lg btn-block">Save</button>

						</div>

					</div>
				</div>
			{{ Form::close() }}

		</div>

	</div>
	
@stop

@section("javascript")

	{{ HTML::script('assets/wcp/plugins/summernote/js/summernote.min.js') }}

	<script type="text/javascript">
		$(function(){

			$('#body').summernote();

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