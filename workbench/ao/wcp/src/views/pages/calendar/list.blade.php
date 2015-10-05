@extends('wcp::layout.master')
@section("stylesheet")
	<script type="text/javascript">
		window.Site = window.Site || {};
		//Config
		Site.Config = Site.Config || {};
		Site.Config.env = "{{ App::environment() }}";
		Site.Config.url = "{{ URL::to('admin') }}";	
		//Boot data
		Site.data = Site.data || {};
		Site.data.events = '{{ json_encode($list) }}';
		Site.data.token = '{{ Session::token() }}';
	</script>
@stop
@section('content')

	{{-- Begin Create Gallery Modal --}}
		<div class="modal fade slide-down disable-scroll" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">

			<div class="modal-dialog">

				<div class="modal-content">
					{{ Form::open(["url"=>"admin/gallery", "role"=>"form", "id"=>"createForm"]) }}
						<div class="modal-header">
							{{-- <button class="close" data-dismiss="modal" aria-label="close">
								<span aria-hidden="true"><i class="fa fa-times"></i></span>
							</button> --}}
							<h4 class="modal-title">
								New Event Form
							</h4>
						</div>

						<div class="modal-body">

							<div class="form-group" id="createMessageBox">
								
							</div>

							<div class="form-group form-group-default m-t-10">
								{{ Form::label("title", "Title") }}
								{{ Form::text("title", "", ["class"=>"form-control", "placeholder"=>"Enter album title here"]) }}
							</div>

							<div class="form-group form-group-default m-t-10">
								{{ Form::label("category_id", "Category") }}
								{{ Form::select("category_id", $categories, "", ["class"=>"form-control"]) }}
							</div>

						</div>

						<div class="modal-footer">
							{{ Form::hidden("schedule_starts", "", ["id"=>"schedule_starts"]) }}
							{{ Form::hidden("schedule_ends", "", ["id"=>"schedule_ends"]) }}
							<button type="submit" class="btn btn-warning pull-right">
								Save
							</button>
							<button type="button" class="btn btn-default pull-right m-r-10" id="closeCreateModal">
								Close
							</button>
						</div>
					{{ Form::close() }}

				</div>

			</div>

		</div>
	{{-- End Create Gallery Modal --}}

	{{-- Begin Edit Gallery Modal --}}
			<div class="modal fade slide-down disable-scroll" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">

				<div class="modal-dialog">

					<div class="modal-content">
						{{ Form::open(["url"=>"admin/gallery", "role"=>"form", "method"=>"put", "id"=>"editForm"]) }}
							<div class="modal-header">
								{{-- <button class="close" data-dismiss="modal" aria-label="close">
									<span aria-hidden="true"><i class="fa fa-times"></i></span>
								</button> --}}
								<h4 class="modal-title">
									Edit Event Form
								</h4>
							</div>

							<div class="modal-body">

								<a href="javascript:;" class="btn btn-danger btn-sm" id="deleteModalBtn" title="Delete">
									<i class="pg-trash"></i>
								</a>

								<div class="form-group form-group-default m-t-10">
									{{ Form::label("title", "Title") }}
									{{ Form::text("title", "", ["class"=>"form-control", "placeholder"=>"Enter album title here"]) }}
								</div>
								<div class="form-group form-group-default m-t-10">
									{{ Form::label("category_id", "Category") }}
									{{ Form::select("category_id", $categories, "", ["class"=>"form-control"]) }}
								</div>
							</div>

							<div class="modal-footer">
								{{ Form::hidden("schedule_starts", "", ["id"=>"schedule_starts"]) }}
								{{ Form::hidden("schedule_ends", "", ["id"=>"schedule_ends"]) }}
								<input type="hidden" name="id" id="id" value="">
								<input type="hidden" name="index" id="index" value="">

								<button type="submit" class="btn btn-warning pull-right">
									Save
								</button>
								<button type="button" class="btn btn-default pull-right m-r-10" id="closeEditModal">
									Close
								</button>
							</div>
						{{ Form::close() }}

					</div>

				</div>

			</div>
	{{-- End Edit Gallery Modal --}}

	{{-- Begin Delete Gallery Modal --}}
		<div class="modal fade slide-down disable-scroll" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">

			<div class="modal-dialog modal-sm">

				<div class="modal-content">
					{{ Form::open(["url"=>"", "role"=>"form", "method"=>"delete", "id"=>"deleteForm"]) }}
						<div class="modal-header">
							{{-- <button class="close" data-dismiss="modal" aria-label="close">
								<span aria-hidden="true"><i class="fa fa-times"></i></span>
							</button> --}}
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
							<input type="hidden" name="id" id="id" value="">
							<input type="hidden" name="index" id="index" value="">
							<button type="submit" class="btn btn-warning pull-right">
								Delete
							</button>
							<button type="button" class="btn btn-default pull-right m-r-10">
								Close
							</button>
						</div>
					{{ Form::close() }}

				</div>

			</div>

		</div>
	{{-- End Delete Gallery Modal --}}

	<div class="col-md-12">
		<div class="calendar">
		<!-- START CALENDAR HEADER-->
		<div class="calendar-header">
		  <div class="drager">
		    <div class="years" id="years"></div>
		  </div>
		</div>
		<div class="options">
		  <div class="months-drager drager">
		    <div class="months" id="months"></div>
		  </div>
		  <h4 class="semi-bold date" id="currentDate">&amp;</h4>
		  <div class="drager week-dragger">
		    <div class="weeks-wrapper" id="weeks-wrapper">
		    </div>
		  </div>
		</div>
		<!-- START CALENDAR GRID-->
		<div id="calendar" class="calendar-container">
		</div>
		<!-- END CALENDAR GRID-->
		</div>
	</div>
	
@stop

@section("javascript")
	{{ HTML::script("assets/wcp/plugins/jquery-ui-touch/jquery.ui.touch-punch.min.js") }}
	{{ HTML::script("assets/wcp/plugins/moment/moment.min.js") }}
	{{ HTML::script("assets/wcp/plugins/moment/moment-with-locales.min.js") }}
	{{ HTML::script("assets/wcp/plugins/hammer.min.js") }}
	{{ HTML::script("assets/wcp/pages/js/pages.calendar.min.js") }}
	{{ HTML::script("assets/wcp//js/calendar.js") }}
@stop