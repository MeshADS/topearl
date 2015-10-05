@extends('wcp::layout.master')
@section('jumbotron_breadcrumb')
	<ul class="breadcrumb">
	    <li>
	      <a href="{{ URL::to('admin') }}"><i class="fa fa-home"></i></a>
	    </li>
	    <li><a href="{{ URL::to('admin/posts') }}" class="">Posts</a>
	    </li>
	    <li><a href="{{ Request::url() }}" class="active">Create</a>
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
					<span class="p-l-10 p-r-10 p-b-10 p-t-10 m-r-10">
						<input type="checkbox" name="master_checkbox" id="master_checkbox">
					</span>
					<a href="{{ URL::to('admin/posts') }}" class="btn btn-default btn-sm m-r-10"><i class="fa fa-chevron-left"></i>&nbsp;List</a>
					<button type="button" data-toggle="modal" data-target="#bulkDeleteModal" id="bulkDeleteBtn" class="btn btn-danger btn-sm hide"><i class="fa fa-ban"></i>&nbsp;Delete</button>
				</div>
			</div>
		</div>

		<div class="col-md-12">

			{{ Form::open(["url"=>"admin/posts", "enctype"=>"multipart/form-data"]) }}
				<div class="container-fluid">

					<div class="col-md-8">

						<div class="form-group form-group-default">

							{{ Form::label("title", "Title")}}
							{{ Form::text("title", "", ["class"=>"form-control"]) }}

						</div>

						<div class="form-group">
							{{ Form::textarea("body", "", ["class"=>"form-control", "id"=>"body", "rows"=>"6"]) }}

						</div>

					</div>

					<div class="col-md-4">

						<div class="form-group form-group-default">
							{{ Form::label("image", "Select Image") }}
							{{ Form::file("image", "", ["class"=>"form-control"]) }}
						</div>

						<div class="form-group">

							<label for="caption">Caption
								<span data-toggle="tooltip" data-placement="top" title="This helps to boost search engine optimisation and also will serve as an introduction for your post.">
									<i class="fa fa-question-circle"></i>
								</span> 
							</label>
							{{ Form::textarea("caption", "", ["class"=>"form-control", "id"=>"caption", "placeholder"=>"Enter your caption here...", "rows"=>"4"]) }}
						</div>

						<div class="form-group form-group-default">

							{{ Form::label('category_id', "Category") }}
							{{ Form::select("category_id", $categories, "", ["class"=>"form-control"]) }}
						</div>


						<div class="form-group">
							{{ Form::label("publish", "Publish now?") }}
							<input type="checkbox" name="publish" value="1" class="switchery" data-color="#f8d053">							
						</div>

						<div class="form-group">

							<button type="submit" class="btn btn-warning btn-lg btn-block">Post</button>

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