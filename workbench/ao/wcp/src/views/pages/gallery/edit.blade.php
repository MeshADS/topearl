@extends('wcp::layout.master')
@section('jumbotron_breadcrumb')
	<ul class="breadcrumb">
	    <li>
	      <a href="{{ URL::to('admin') }}"><i class="fa fa-home"></i></a>
	    </li>
	    <li><a href="{{ URL::to('admin/gallery') }}">Gallery</a>
	    </li>
	    <li><a href="{{ URL::to('admin/gallery/'.$item->id) }}">{{ ucwords($item->title) }}</a>
	    </li>
	    <li><a href="{{ Request::url() }}" class="active">Upload</a>
	    </li>
	</ul>
@stop
@section('jumbotron')
	@include('wcp::_partials.html.jumbotron')
@stop
@section("stylesheet")
	{{ HTML::style('assets/wcp/plugins/dropzone/css/dropzone.css') }}
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
					<a href="{{ URL::to('admin/gallery/'.$item->id) }}" class="btn btn-info btn-sm m-r-10"><i class="fa fa-chevron-left"></i>&nbsp;{{ $item->title }}</a>
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

			{{-- End Modals --}}
		</div>

		<div class="col-md-12">

			<div class="container-fluid">

				<div class="col-md-12">
					<p>
						<small>Maximum allowed file size is 1200kb.</small>
					</p>
				</div>

				<div class="col-md-12">

					{{ Form::open(["url"=>Request::url(), "class"=>"dropzone", "enctype"=>"multipart/form-data"]) }}

						<div class="fallback">
							<div class="form-group form-group-default">
								{{ Form::label("file", "Select Files") }}
								{{ Form::file("file", "", ["class"=>"form-control"]) }}
								<button type="submit" class="btn btn-warning btn-block m-t-10">Upload</button>
							</div>
						</div>
						<input type="hidden" name="gallery_id" value="{{ $item->id }}">
					{{ Form::close() }}

				</div>

			</div>

		</div>

	</div>
	
@stop

@section("javascript")
	{{ HTML::script("assets/wcp/plugins/dropzone/dropzone.min.js") }}
@stop