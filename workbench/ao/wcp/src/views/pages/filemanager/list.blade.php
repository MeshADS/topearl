@extends('wcp::layout.master')
@section('jumbotron_breadcrumb')
	<ul class="breadcrumb">
	    <li>
	      <a href="{{ URL::to('admin') }}"><i class="fa fa-home"></i></a>
	    </li>
	    <li><a href="{{ Request::url() }}" class="active">File Manager</a>
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
					<input type="checkbox" name="master_checkbox" id="master_checkbox">
					<button type="button" data-toggle="modal" data-target="#createModal" class="btn btn-default btn-sm m-r-10"><i class="pg-plus"></i>&nbsp;New</button>
					<button type="button" data-toggle="modal" data-target="#bulkDeleteModal" id="bulkDeleteBtn" class="btn btn-danger btn-sm hide"><i class="fa fa-ban"></i>&nbsp;Delete</button>
				</div>
			</div>

			{{-- Begin Modals --}}

				{{-- Begin Create File Manager Modal --}}
					<div class="modal fade slide-down disable-scroll" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">

						<div class="modal-dialog">

							<div class="modal-content">
								{{ Form::open(["url"=>"admin/filemanager", "role"=>"form", "enctype"=>"multipart/form-data"]) }}
									<div class="modal-header">
										<button class="close" data-dismiss="modal" aria-label="close">
											<span aria-hidden="true"><i class="fa fa-times"></i></span>
										</button>
										<h4 class="modal-title">
											New File
										</h4>
									</div>

									<div class="modal-body">

										<div class="form-group form-group-default m-t-10">
											{{ Form::label("file", "Select file") }}
											{{ Form::file("file", "", ["class"=>"form-control", "placeholder"=>"E.g. Home, About Us, Contact"]) }}
										</div>

										<div class="form-group form-group-default m-t-10">
											{{ Form::label("type_id", "Type") }}
											{{ Form::select("type_id", $typeoptions, "", ["class"=>"form-control"]) }}
										</div>

										<div class="form-group form-group-default m-t-10">
											{{ Form::label("downloadable", "Downloadable?") }}
											<input type="checkbox" name="downloadable" value="1" class="switchery" data-color="#f8d053"><br>
											<small><i>Check this field if you want this file to be downloaded.</i></small>
										</div>

									</div>

									<div class="modal-footer">
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
				{{-- End Create File Manager Modal --}}

				{{-- Begin Create File Manager Modal --}}
					@foreach($list as $item)
						<div class="modal fade slide-down disable-scroll" id="editModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">

							<div class="modal-dialog">

								<div class="modal-content">
									{{ Form::open(["url"=>"admin/filemanager/".$item->id, "role"=>"form", "method"=>"put"]) }}
										<div class="modal-header">
											<button class="close" data-dismiss="modal" aria-label="close">
												<span aria-hidden="true"><i class="fa fa-times"></i></span>
											</button>
											<h4 class="modal-title">
												Update File
											</h4>
										</div>

										<div class="modal-body">

											<div class="form-group form-group-default m-t-10">
												{{ Form::label("type_id", "Type") }}
												<select class="form-control" id="type_id" name="type_id">
													@foreach($typeoptions as $k => $v)
														<option value="{{$k}}" {{ ($item->type_id == $k) ? "selected" : "" }}>{{$v}}</option>
													@endforeach
												</select>
											</div>

											<div class="form-group form-group-default m-t-10">
												{{ Form::label("downloadable", "Downloadable?") }}
												<input type="checkbox" name="downloadable" value="1" class="switchery" data-color="#f8d053" {{ ($item->downloadable == 1) ? "checked" : "" }}><br>
												<small><i>Check this field if you want this file to be downloaded.</i></small>
											</div>

										</div>

										<div class="modal-footer">
											<button type="submit" class="btn btn-warning pull-right">
												Update
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
				{{-- End Create File Manager Modal --}}

				{{-- Begin Delete File Manager Modal --}}
					@foreach($list as $item)
						<div class="modal fade slide-down disable-scroll" id="deleteModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $item->id }}" aria-hidden="true">

							<div class="modal-dialog modal-sm">

								<div class="modal-content">
									{{ Form::open(["url"=>"admin/filemanager/".$item->id, "role"=>"form", "method"=>"delete"]) }}
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
												Are you sure you want to delete this item, you will lose all data asscotiated with it.
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
				{{-- End Delete File Manager Modal --}}

				{{-- Begin File Download Links Modal --}}
					@foreach($list as $item)
						@if(!is_null($item->downloadable))
							<div class="modal fade slide-down disable-scroll" id="downloadURLModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="downloadURLModalLabel{{ $item->id }}" aria-hidden="true">

								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button class="close" data-dismiss="modal" aria-label="close">
												<span aria-hidden="true"><i class="fa fa-times"></i></span>
											</button>
											<h4 class="modal-title">
												<strong>Download Link</strong>
											</h4>
										</div>

										<div class="modal-body">
											<p style="margin:30px 0px;" class="text-center">
												<a href="{{ URL::to('download/file/'.$item->downloadkey) }}" target="_blank">
													{{ URL::to('download/file/'.$item->downloadkey) }}
												</a>
											</p>
										</div>
									</div>
								</div>

							</div>
						@endif
					@endforeach
				{{-- End File Download Links Modal --}}

				{{-- Begin Bulk Delete File Manager Modal --}}
					<div class="modal fade slide-down disable-scroll" id="bulkDeleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">

						<div class="modal-dialog">

							<div class="modal-content">
								{{ Form::open(["url"=>"admin/filemanager/bulk", "role"=>"form", "method"=>"delete"]) }}
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
				{{-- End Bulk Delete File Manager Modal --}}

			{{-- End Modals --}}
		</div>

		<div class="col-md-12">

	    	@foreach(array_chunk($list->all(), 4) as $row)
	    		<div class="row">
	    			@foreach($row as $item)
	    				<?php
	    					$item->info = unserialize($item->info);
	    					$fileinfo = "";
	    					foreach ($item->info as $k => $v) {
	    						$fileinfo .= "<li><strong>".ucwords(str_replace("_", " ", $k))."</strong>: ".$v."</li>";
	    					}
	    					$fileinfo = "<ul class=\"list-unstyled\">".$fileinfo."</ul>";
	    				?>
	    				<div class="col-md-3">
	    					<div class="panel panel-default" data-toggle="popover" 
	    						 data-content='{{ $fileinfo }}' title="File Info">
	    						<div class="panel-heading" style="padding-top:10px;">
	    							<h4 class="panel-title">
	    								<input type="checkbox" name="checkbox[]" value="{{ $item->id }}" class="table_checkbox">
	    							</h4>
	    							<div class="panel-controls">
										<ul>
							                <li><a href="#" class="portlet-collapse" data-toggle="modal" data-target="#editModal{{ $item->id }}"><i class="fa fa-pencil"></i></a>
							                </li>
							                <li><a href="#" class="portlet-close" data-toggle="modal" data-target="#deleteModal{{ $item->id }}"><i class="fa fa-times"></i></a>
							                </li>
							                @if(!is_null($item->downloadable))
								                <li>
								                	<a href="#" class="portlet-close" data-toggle="modal" data-target="#downloadURLModal{{ $item->id }}" title="View download link"><i class="fa fa-link"></i></a>
								                </li>
							                @endif
							            </ul>
									</div>
	    						</div>	    						
		    					<div class="panel-body">
		    						<img src="{{ URL::to($item->thumbnail) }}" style="width:100%;">	    						
	    							<h6 class="panel-title bold" style="font-size:12px; margin-top:5px;">
	    								{{ $item->name }}
	    							</h6>
		    					</div>    						
	    					</div>    					
	    				</div>
	    			@endforeach
	    		</div>
	        @endforeach

		</div>

		@if(count($list) < 1)
			<div class="col-md-12">
				<h4 class="text-center">-- No file was found --</h4>
			</div>
		@endif

		<div class="col-md-12">
			{{ $list->links() }}			
		</div>

	</div>
	
@stop

@section("javascript")	<script type="text/javascript">
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

			$(function () {
			  $('[data-toggle="popover"]').popover({
			  	trigger:"hover",
			  	html:true,
			  	placement:"auto"
			  })
			})

		});
	</script>
@stop