@extends('wcp::layout.master')
@section('jumbotron_breadcrumb')
	<ul class="breadcrumb">
	    <li>
	      <a href="{{ URL::to('admin') }}"><i class="fa fa-home"></i></a>
	    </li>
	    <li><a href="{{ URL::to('admin/basic_info') }}" class="active">Basic Info</a>
	    </li>
	</ul>
@stop
@section('jumbotron')
	@include('wcp::_partials.html.jumbotron')
@stop
@section("stylesheet")
{{ HTML::style('assets/wcp/plugins/fancyBox/source/jquery.fancybox.css') }}
{{ HTML::style('assets/wcp/plugins/fancyBox/source/helpers/jquery.fancybox-buttons.css') }}
<style type="text/css">
	.nopadding{
		padding: 0px 0px !important;
	}
</style>
@stop
@section('content')

	<div class="content-fluid">

			{{-- Begin Modals --}}

				{{-- Begin Edit Logo Modal --}}
					<div class="modal fade slide-down disable-scroll" id="editLogoModal" tabindex="-1" role="dialog" aria-labelledby="editLogosModalLabel" aria-hidden="true">

						<div class="modal-dialog">

							<div class="modal-content">
								{{ Form::open(["url"=>"admin/basic_info/".$basic_info->id."/logo", "role"=>"form", "method"=>"put", "enctype"=>"multipart/form-data"]) }}
									<div class="modal-header">
										<button class="close" data-dismiss="modal" aria-label="close">
											<span aria-hidden="true"><i class="fa fa-times"></i></span>
										</button>
										<h4 class="modal-title">
											Edit Logo Form
										</h4>
									</div>

									<div class="modal-body">

										<div class="form-group form-group-default m-t-10">
											{{ Form::label("image", "Image") }}
											{{ Form::file("image", "", ["class"=>"form-control"]) }}
											<strong>
												<small id="logoEditDimensions"></small>
											</strong>
										</div>
									</div>

									<div class="modal-footer">
										<input type="hidden" name="type" value="" id="logoEditType">
										<button type="submit" class="btn btn-warning pull-right">
											Upload
										</button>
										<a href="#" class="btn btn-default pull-right m-r-10" data-dismiss="modal">
											Cancel
										</a>
									</div>
								{{ Form::close() }}

							</div>

						</div>

					</div>
				{{-- End Edit Logo Modal --}}

				{{-- Begin Edit Basic Info Modal --}}
					<div class="modal fade slide-down disable-scroll" id="basicinfoModal" tabindex="-1" role="dialog" aria-labelledby="basicinfoModalLabel" aria-hidden="true">

						<div class="modal-dialog">

							<div class="modal-content">
								{{ Form::open(["url"=>"admin/basic_info/".$basic_info->id, "role"=>"form", "method"=>"put"]) }}

									<div class="modal-header">
										<button class="close" data-dismiss="modal" aria-label="close">
											<span aria-hidden="true"><i class="fa fa-times"></i></span>
										</button>
										<h4 class="modal-title">
											Edit Basic Info
										</h4>
									</div>

									<div class="modal-body">

										<div class="form-group form-group-default m-t-10">
											{{ Form::label("shortname", "Short Name") }}
											{{ Form::text("shortname", $basic_info->shortname, ["class"=>"form-control", "placeholder"=>"Enter a short name here"]) }}
										</div>

										<div class="form-group form-group-default m-t-10">
											{{ Form::label("fullname", "Full Name") }}
											{{ Form::text("fullname", $basic_info->fullname, ["class"=>"form-control", "placeholder"=>"Enter a full name here"]) }}
										</div>

										<div class="form-group form-group-default m-t-10">
											{{ Form::label("description", "Caption") }}
											{{ Form::textarea("description", $basic_info->description, ["class"=>"form-control", "placeholder"=>"Enter description here"]) }}
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
				{{-- End Edit Basic Info Modal--}}

			{{-- End Modals --}}
		</div>

		<div class="col-md-12">

			<div class="panel panel-default">

				<div class="panel-heading">
					<h4 class="panel-title">Basic Info</h4>
					<div class="panel-controls">
						<ul>
							<li>
								<a href="#" data-toggle="modal" data-target="#basicinfoModal">
									<i class="fa fa-pencil"></i>
								</a>
							</li>
						</ul>
					</div>
				</div>

				<div class="panel-body">

					<ul class="list-unstyled">

						<li>
							<h6><strong>SHORT NAME</strong></h6>
							<p>
								{{ $basic_info->shortname }}
							</p>
						</li>

						<li>
							<h6><strong>FULL NAME</strong></h6>
							<p>
								{{ $basic_info->fullname }}
							</p>
						</li>

						<li>
							<h6><strong>DESCRIPTION</strong></h6>
							<p>
								{{ $basic_info->description }}
							</p>
						</li>

					</ul>

				</div>

			</div>

			<div class="panel panel-transparent">

				<div class="panel-body nopadding">

					<div class="row nopadding">

						<div class="col-md-4 text-center">

							<div class="panel panel-default">

								<div class="panel-heading">
									<h4 class="panel-title">Logo</h4>

									<div class="panel-controls">
										<ul>
											<li>
												<a href="#" class="editLogoModal" data-logo-type="logo" data-size-w="128" data-size-h="128" data-toggle="" data-target="">
													<i class="fa fa-pencil"></i>
												</a>
											</li>
										</ul>
									</div>
								</div>

								<div class="panel-body">

									<div class="container-fluid">

										<div calss="col-md-4 text-center">

											<a href="{{ URL::to($basic_info->logo) }}" class="fancyboxGroup" rel="group">
												<img src="{{ URL::to($basic_info->logo) }}" style="max-width:100%;" class="">
											</a>

										</div>

									</div>

								</div>

							</div>

						</div>


						<div class="col-md-4 text-center">

							<div class="panel panel-default">

								<div class="panel-heading">
									<h4 class="panel-title">Logo (Small)</h4>

									<div class="panel-controls">
										<ul>
											<li>
												<a href="#" class="editLogoModal" data-logo-type="logo_sm" data-size-w="36" data-size-h="36" data-toggle="" data-target="logo_sm">
													<i class="fa fa-pencil"></i>
												</a>
											</li>
										</ul>
									</div>
								</div>

								<div class="panel-body">

									<div class="container-fluid">

										<div calss="col-md-4 text-center">

											<a href="{{ URL::to($basic_info->logo_sm) }}" class="fancyboxGroup" rel="group">
												<img src="{{ URL::to($basic_info->logo_sm) }}" style="max-width:100%;" class="">
											</a>

										</div>

									</div>

								</div>

							</div>

						</div>


						<div class="col-md-4 text-center">

							<div class="panel panel-default">

								<div class="panel-heading">
									<h4 class="panel-title">Logo (Large)</h4>

									<div class="panel-controls">
										<ul>
											<li>
												<a href="#" class="editLogoModal" data-logo-type="logo_2x" data-size-w="512" data-size-h="512" data-toggle="" data-target="">
													<i class="fa fa-pencil"></i>
												</a>
											</li>
										</ul>
									</div>
								</div>

								<div class="panel-body">

									<div class="container-fluid">

										<div calss="col-md-4 text-center">

											<a href="{{ URL::to($basic_info->logo_2x) }}" class="fancyboxGroup" rel="group">
												<img src="{{ URL::to($basic_info->logo_2x) }}" style="max-width:100%;" class="">
											</a>

										</div>

									</div>

								</div>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>
	
@stop

@section("javascript")
	{{ HTML::script("assets/wcp/plugins/fancyBox/lib/jquery.mousewheel-3.0.6.pack.js") }}
	{{ HTML::script("assets/wcp/plugins/fancyBox/source/jquery.fancybox.pack.js") }}
	{{ HTML::script("assets/wcp/plugins/fancyBox/source/helpers/jquery.fancybox-buttons.js") }}
	<script type="text/javascript">
		$(function(){
			$("a.fancyboxGroup").fancybox({
				'transitionIn'	:	'elastic',
				'transitionOut'	:	'elastic',
				'speedIn'		:	600, 
				'speedOut'		:	200, 
				'overlayShow'	:	false
			});

			$(".editLogoModal").on("click", function(event){
				var logoType = $(this).attr("data-logo-type"),
						   w = $(this).attr("data-size-w"),
						   h = $(this).attr("data-size-h");
				$("#logoEditType").val(logoType);
				$("#logoEditDimensions").text("Dimension: "+w+"px by"+h+"px");
	            $("#editLogoModal").modal("show");
	            event.preventDefault();
	        });
		});
	</script>
@stop