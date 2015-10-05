@extends('wcp::layout.master')
@section('jumbotron_breadcrumb')
	<ul class="breadcrumb">
	    <li>
	      <a href="{{ URL::to('admin') }}"><i class="fa fa-home"></i></a>
	    </li>
	    <li><a href="{{ URL::to('admin/admission') }}" class="">Admission</a>
	    </li>
	    <li><a href="{{ URL::to('admin/admission/'.$item->id) }}" class="">{{ $item->title }}</a>
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
	{{ HTML::style('assets/wcp/plugins/bootstrap-datepicker/css/datepicker3.css') }}
@stop
@section('content')

	<div class="content-fluid">

		<div class="col-md-12">
			<div class="panel panel-transparent">
				<div class="panel-body">
					<span class="p-l-10 p-r-10 p-b-10 p-t-10 m-r-10">
						<input type="checkbox" name="master_checkbox" id="master_checkbox">
					</span>
					<a href="{{ URL::to('admin/admission') }}" class="btn btn-default btn-sm m-r-10"><i class="fa fa-chevron-left"></i><i class="fa fa-chevron-left"></i>&nbsp;List</a>
					<a href="{{ URL::to('admin/admission/'.$item->id) }}" class="btn btn-complete btn-sm m-r-10"><i class="fa fa-chevron-left"></i>&nbsp;View</a>
				</div>
			</div>
		</div>

		<div class="col-md-12">

			{{ Form::open(["url"=>"admin/admission/".$item->id, "method"=>"put", "enctype"=>"multipart/form-data"]) }}
				<div class="container-fluid">

					<div class="col-md-8">

						<div class="form-group form-group-default">

							{{ Form::label("title", "Title")}}
							{{ Form::text("title", $item->title, ["class"=>"form-control"]) }}

						</div>

						<div class="form-group">
							{{ Form::textarea("description", $item->description, ["class"=>"form-control", "id"=>"description", "rows"=>"6"]) }}

						</div>

					</div>

					<div class="col-md-4">

						<div class="form-group form-group-default">
							{{ Form::label("image", "Select Image") }}
							{{ Form::file("image", "", ["class"=>"form-control"]) }}
							<small>Dimention 1024px by 640px</small>
						</div>

						<div class="form-group form-group-default">
							{{ Form::label('contact1', "Contact 1") }}
					        <select class="full-width" name="contact1" id="contact1" class="form-control" data-init-plugin="select2">
					        	@foreach($contacts as $key => $group)
						            <optgroup label="{{ ucwords(str_replace('-', ' ', $key)) }}">
						            	@foreach($group as $contact)
						                	<option value="{{ $contact->id }}" <?php if($contact->id == $item->contact1) { echo "selected"; } ?> >{{ $contact->data }}</option>
						                @endforeach
						            </optgroup>
					            @endforeach
					        </select>
						</div>

						<div class="form-group form-group-default">
							{{ Form::label('contact2', "Contact 2") }}
					        <select class="full-width" name="contact2" id="contact2" class="form-control" data-init-plugin="select2">
					        	@foreach($contacts as $key => $group)
						            <optgroup label="{{ ucwords(str_replace('-', ' ', $key)) }}">
						            	@foreach($group as $contact)
						                	<option value="{{ $contact->id }}" <?php if($contact->id == $item->contact2) { echo "selected"; } ?> >{{ $contact->data }}</option>
						                @endforeach
						            </optgroup>
					            @endforeach
					        </select>
						</div>

						<div class="form-group form-group-default">
							{{ Form::label('class_id', "Class") }}
							{{ Form::select("class_id", $classes, $item->class_id, ["class"=>"form-control"]) }}
						</div>

						<div class="input-group date myDatepicker m-b-10">
							<?php $open_date = date("m/d/Y", strtotime($item->open_date)); ?>
						    {{ Form::text("open_date", $open_date, ["class"=>"form-control", "placeholder"=>"Open date"]) }}
						    <span class="input-group-addon"><i class="fa fa-calendar"></i>
						    </span>
						</div>

						<div class="input-group date myDatepicker m-b-10">
							<?php $close_date = date("m/d/Y", strtotime($item->close_date)); ?>
						    {{ Form::text("close_date", $close_date, ["class"=>"form-control", "placeholder"=>"Close date"]) }}
						    <span class="input-group-addon"><i class="fa fa-calendar"></i>
						    </span>
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
	{{ HTML::script('assets/wcp/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}

	<script type="text/javascript">
		$(function(){

			$('#description').summernote();

			$('.myDatepicker').datepicker();

		});
	</script>
@stop