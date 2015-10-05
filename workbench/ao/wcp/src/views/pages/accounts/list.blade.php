@extends('wcp::layout.master')
@section('jumbotron_breadcrumb')
	<ul class="breadcrumb">
	    <li>
	      <a href="{{ URL::to('admin') }}"><i class="fa fa-home"></i></a>
	    </li>
	    <li><a href="{{ Request::url() }}" class="active">Accounts</a>
	    </li>
	</ul>
@stop
@section('jumbotron')
	@include('wcp::_partials.html.jumbotron')
@stop
@section("stylesheet")
	{{ HTML::style("assets/wcp/plugins/jquery-datatable/media/css/jquery.dataTables.css") }}
	{{ HTML::style("assets/wcp/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css") }}
	{{ HTML::style("assets/wcp/plugins/datatables-responsive/css/datatables.responsive.css") }}
@stop
@section('content')

	<div class="content-fluid">

		<div class="col-md-12">
			<div class="panel panel-transparent">
				<div class="panel-body">

					<a href="{{ URL::to('admin/accounts/create') }}" class="btn btn-default btn-sm m-r-10"><i class="pg-plus"></i>&nbsp;Create</a>

					<button type="button" data-toggle="modal" data-target="#bulkDeleteModal" id="bulkDeleteBtn" class="btn btn-danger btn-sm hide"><i class="fa fa-ban"></i>&nbsp;Delete</button>

				</div>
			</div>

			{{-- Begin Modals --}}
				{{-- Begin Item Modal --}}
				@foreach($list as $item)
					{{-- Begin Edit Account Modal --}}
						<div class="modal fade slide-down disable-scroll" id="editModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">

							<div class="modal-dialog">

								<div class="modal-content">
									{{ Form::open(["url"=>"admin/accounts/".$item->id, "role"=>"form", "method"=>"put"]) }}
										<div class="modal-header">
											<button class="close" data-dismiss="modal" aria-label="close">
												<span aria-hidden="true"><i class="fa fa-times"></i></span>
											</button>
											<h4 class="modal-title">
												Edit Form
											</h4>
										</div>

										<div class="modal-body">

											<div class="form-group form-group-default">
												{{ Form::label("first_name", "First Name") }}
												{{ Form::text("first_name", $item->first_name, ["class"=>"form-control", "placeholder"=>"Enter first name"]) }}
											</div>

											<div class="form-group form-group-default">
												{{ Form::label("last_name", "Last Name") }}
												{{ Form::text("last_name", $item->last_name, ["class"=>"form-control", "placeholder"=>"Enter last name"]) }}
											</div>

											<div class="form-group form-group-default">
												{{ Form::label("email", "Email") }}
												{{ Form::text("email", $item->email, ["class"=>"form-control", "placeholder"=>"Enter email"]) }}
											</div>

											<div class="form-group form-group-default">
									          	{{ Form::label("phone_id", "Primary Phone") }}
									          	<select name="phone_id" class="form-control" id="phone_id">
									          		<option value="">Select Phone Number</option>
									          		@foreach($item->phonenumbers as $phonenumber)
									          			<option value="{{ $phonenumber->id }}" {{ ($item->phone->id == $phonenumber->id) ? ' selected ' : ' ' }}>
									          				{{$phonenumber->name}} - {{$phonenumber->number}}
									          			</option>
									          		@endforeach
									          	</select>
									        </div>

											<div class="form-group form-group-default">
									          	{{ Form::label("group", "Group") }}
									          	{{ Form::select("group", $groups, $item->groups[0]->id, ["class"=>"form-control"]) }}
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
					{{-- End Edit Account Modal --}}

					{{-- Begin Password Account Modal --}}
						<div class="modal fade slide-down disable-scroll" id="passwordModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel{{ $item->id }}" aria-hidden="true">

							<div class="modal-dialog">

								<div class="modal-content">
									{{ Form::open(["url"=>"admin/accounts/".$item->id."/change_password", "role"=>"form", "method"=>"put"]) }}
										<div class="modal-header">
											<button class="close" data-dismiss="modal" aria-label="close">
												<span aria-hidden="true"><i class="fa fa-times"></i></span>
											</button>
											<h4 class="modal-title">
												Change Password
											</h4>
										</div>

										<div class="modal-body">

											<div class="form-group form-group-default">
												{{ Form::label("password", "Password") }}
												<input type="password" name="password" class="form-control" id="password" placeholder="&#149;&#149;&#149;&#149;&#149;&#149;&#149;&#149;">
											</div>

											<div class="form-group form-group-default">
												{{ Form::label("password_confirmation", "Repeat Password") }}
												<input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="&#149;&#149;&#149;&#149;&#149;&#149;&#149;&#149;">
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
					{{-- End Password Account Modal --}}

					@if($item->activated != 1)
						{{-- Begin Activate Account Modal --}}
							<div class="modal fade slide-down disable-scroll" id="activateModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="activateModalLabel{{ $item->id }}" aria-hidden="true">

									<div class="modal-dialog modal-sm">

										<div class="modal-content">
											{{ Form::open(["url"=>"admin/accounts/".$item->id."/activate", "role"=>"form", "method"=>"put"]) }}
												<div class="modal-header">
													<button class="close" data-dismiss="modal" aria-label="close">
														<span aria-hidden="true"><i class="fa fa-times"></i></span>
													</button>
													<h4 class="modal-title">
														<strong>Attention</strong>
													</h4>
												</div>

												<div class="modal-body">


													<p class="text-center">
														Are you sure you want to activate this account? It will become accessible.
													</p>

												</div>

												<div class="modal-footer">
													<button type="submit" class="btn btn-warning pull-right">
														Activate
													</button>
													<a href="#" class="btn btn-default pull-right m-r-10" data-dismiss="modal">
														Cancel
													</a>
												</div>
											{{ Form::close() }}

										</div>

									</div>

							</div>
						{{-- End Activate Account Modal --}}
					@endif

					{{-- Begin Delete Account Modal --}}
						<div class="modal fade slide-down disable-scroll" id="deleteModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $item->id }}" aria-hidden="true">

								<div class="modal-dialog modal-sm">

									<div class="modal-content">
										{{ Form::open(["url"=>"admin/accounts/".$item->id, "role"=>"form", "method"=>"delete"]) }}
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
													Are you sure you want to delete this item, you will lose all data asscotiated with this category.
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
					{{-- End Delete Account Modal --}}
				@endforeach
				{{-- End Item Modal --}}

				{{-- Begin Bulk Delete Account Modal --}}
					<div class="modal fade slide-down disable-scroll" id="bulkDeleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">

						<div class="modal-dialog modal-sm">

							<div class="modal-content">
								{{ Form::open(["url"=>"admin/accounts/bulk", "role"=>"form", "method"=>"delete"]) }}
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
				{{-- End Bulk Delete Account Modal --}}

			{{-- End Modals --}}
		</div>

		<div class="col-md-12">

			{{ Form::open(["url"=>"admin/accounts/bulk"]) }}

				<table id="myDataTable" class="table table-hover" cellspacing="0" width="100%">
				    <thead>
				        <tr>
				            <th>
				            	<input type="checkbox" name="master_checkbox" id="master_checkbox">
				            </th>
				            <th>First Name</th>
				            <th>Last Name</th>
				            <th>Email</th>
				            <th>Group</th>
				            <th>Status</th>
				            <th>&nbsp;</th>
				        </tr>
				    </thead>

				    <tbody>
				    	@foreach($list as $item)
					        <tr>
					            <td>
					            	<input type="checkbox" name="checkbox[]" value="{{ $item->id }}" class="table_checkbox">
					            </td>
					            <td>
					            	{{ $item->first_name }}
					            </td>
					            <td>{{ $item->last_name }}</td>
					            <td>{{ $item->email }}</td>
					            <td> 
					            	@if(count($item->groups) > 0)
										{{ $item->groups[0]->name }}
					            	@endif
					            </td>
					            <td>
					            	{{ ($item->activated === 1) ? "Activated" : "Not Activated"; }}
					            </td>
					            <td>
					            	<div class="btn-group dropdown-default">
									    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"> Actions <span class="caret"></span> </a>
									    <ul class="dropdown-menu ">
									        <li>
									        	<a href="#" data-target="#editModal{{ $item->id }}" data-toggle="modal">
									        		Edit
									        	</a>
									        </li>
									        <li>
									        	<a href="#" data-target="#passwordModal{{ $item->id }}" data-toggle="modal">
									        		Change Password
									        	</a>
									        </li>
									        <li>
									        	<a href="{{ URL::to('admin/accounts/'.$item->id) }}">
									        		Profile
									        	</a>
									        </li>
									        @if($item->activated != 1)
										        <li>
										        	<a href="#" data-target="#activateModal{{ $item->id }}" data-toggle="modal">
										        		Activate
										        	</a>
										        </li>
									        @endif
									        <li>
									        	<a href="#" data-target="#deleteModal{{ $item->id }}" data-toggle="modal">
									        		Delete Account
									        	</a>
									        </li>
									    </ul>
									</div>
					            </td>
					        </tr>
				        @endforeach
				    </tbody>
				</table>

			{{ Form::close() }}

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

		});
	</script>
@stop