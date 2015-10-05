@extends('wcp::layout.master')
@section('jumbotron_breadcrumb')
	<ul class="breadcrumb">
	    <li>
	      <a href="{{ URL::to('admin') }}"><i class="fa fa-home"></i></a>
	    </li>
	    <li><a href="{{ URL::to('admin/posts') }}" class="">Posts</a>
	    </li>
	    <li><a href="{{ Request::url() }}" class="active">{{ $item->title }}</a>
	    </li>
	</ul>
@stop
@section('jumbotron')
	@include('wcp::_partials.html.jumbotron')
@stop
@section("stylesheet")
	{{ HTML::style('assets/wcp/plugins/fancyBox/source/jquery.fancybox.css') }}
	{{ HTML::style('assets/wcp/plugins/fancyBox/source/helpers/jquery.fancybox-buttons.css') }}
@stop
@section('content')

	<div class="content-fluid">

		<div class="col-md-12">
			<div class="panel panel-transparent">
				<div class="panel-body">
					<a href="{{ URL::to('admin/posts') }}" class="btn btn-default btn-sm m-r-10"><i class="fa fa-chevron-left"></i>&nbsp;List</a>

					<a href="{{ Request::url().'/edit' }}" class="btn btn-complete btn-sm m-r-10"><i class="fa fa-edit"></i>&nbsp;Edit</a>

					<a href="{{ Request::url().'/comments' }}" class="btn btn-info btn-sm m-r-10" title="Comments"><i class="pg-comment"></i>&nbsp;{{ count($item->comments) }}</a>

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

				<div class="container-fluid">

					{{-- Begin Title and Body --}}
					<div class="col-md-8">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h2 class="panel-title">
									{{ $item->title }}
								</h2>
							</div>
							<div class="panel-body">
								<p>
									{{ $item->body }}
								</p>
							</div>
						</div>
					</div>
					{{-- End Title and Body --}}

					{{-- Begin Caption, Image, Category, Publish state --}}
					<div class="col-md-4">

						<div class="panel panel-default">
							<div class="panel-heading">
								<h5 class="panel-title">
									State
								</h5>
							</div>
							<div class="panel-body">
								@if($item->publish_state === 0)
									<button class="btn btn-block btn-danger">
										<i class="fa fa-ban"></i>&nbsp;Unpublished
									</button>
								@else
									<button class="btn btn-block btn-success">
										<i class="fa fa-check-circle-o"></i>&nbsp;Published
									</button>
								@endif
							</div>
						</div>

						<div class="panel panel-default">
							<div class="panel-heading">
								<h5 class="panel-title">
									Image
								</h5>
							</div>
							<div class="panel-body">
								<a href="{{ URL::to($item->image) }}" class="fancyboxGroup">
									<img src="{{ URL::to($item->thumbnail) }}" style="width:100%;">
								</a>
							</div>
						</div>

						<div class="panel panel-default">
							<div class="panel-heading">
								<h5 class="panel-title">
									caption
								</h5>
							</div>
							<div class="panel-body">
								<p>{{ $item->caption }}</p>
							</div>
						</div>

						<div class="panel panel-default">
							<div class="panel-heading">
								<h5 class="panel-title">
									Category
								</h5>
							</div>
							<div class="panel-body">
								<p>{{ $item->category->name  }}</p>
							</div>
						</div>

					</div>
					{{-- End Caption, Image, Category, Publish state --}}
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
		});
	</script>
@stop