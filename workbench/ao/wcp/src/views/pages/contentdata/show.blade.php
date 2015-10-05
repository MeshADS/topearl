@extends('wcp::layout.master')
@section('jumbotron_breadcrumb')
	<ul class="breadcrumb">
	    <li>
	      <a href="{{ URL::to('admin') }}"><i class="fa fa-home"></i></a>
	    </li>
	    <li><a href="{{ URL::to('admin/content_data') }}" class="active">Content Data</a>
	    </li>
	    <li><a href="{{ Request::url() }}" class="active">{{ $item->title }}</a>
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
					<a href="{{ URL::to('admin/content_data') }}" class="btn btn-default btn-sm m-r-10"><i class="fa fa-chevron-left"></i>&nbsp;List</a>

					<a href="{{ URL::to('admin/content_data/'.$item->id.'/edit') }}" class="btn btn-info btn-sm m-r-10"><i class="fa fa-pencil"></i>&nbsp;Edit</a>

					<a href="#" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm"><i class="fa fa-ban"></i>&nbsp;Delete</a>
				</div>
			</div>

			{{-- Begin Delete Header Modal --}}
				<div class="modal fade slide-down disable-scroll" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">

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
										Are you sure you want to delete this item?
									</p>

								</div>

								<div class="modal-footer">
									<input type="hidden" name="return" value="{{ URL::to('admin/content_data') }}">
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
			{{-- End Delete Header Modal --}}
		</div>

		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						{{ $item->title }} / <small>{{ (isset($item->page->id)) ? ucwords($item->page->name) : $item->page_id }}</small>
					</h4>
				</div>
				<div class="panel-body">
						{{ $item->body }}
				</div>
			</div>
		</div>

	</div>
	
@stop

@section("javascript")
{{ HTML::script('assets/wcp/plugins/summernote/js/summernote.min.js') }}
<script type="text/javascript">
	$(function(){
		$('#cd_body').summernote();
	});
</script>
@stop