@extends('wcp::layout.master')
@section('jumbotron_breadcrumb')
	<ul class="breadcrumb">
	    <li>
	      <a href="{{ URL::to('admin') }}"><i class="fa fa-home"></i></a>
	    </li>
	    <li><a href="{{ URL::to('admin/content_data') }}" class="active">Content Data</a>
	    </li>
	    <li><a href="{{ URL::to('admin/content_data/create') }}" class="active">Create</a>
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
				</div>
			</div>
		</div>

		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-body">
					{{ Form::open(["url"=>"admin/content_data", ""=>""]) }}
						
						<div class="form-group">
							{{ Form::label('title', 'Title') }}	
							{{ Form::text('title', '', ['class'=>'form-control']) }}
						</div>

						<div class="form-group">
							{{ Form::label('page_id', 'Page') }}	
							{{ Form::select('page_id', $pages, "", ['class'=>'form-control']) }}
						</div>

						<div class="form-group">
							{{ Form::label('body', 'Body') }}
							{{ Form::textarea('body', '', ['class'=>'form-control', 'id'=>'cd_body', "rows"=>"10"]) }}
						</div>

						<div class="form-group m-t-80">
							<button type="submit" class="btn btn-success"><i class="fa fa-save"></i>&nbsp;Save</button>
						</div>

					{{ Form::close() }}				
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