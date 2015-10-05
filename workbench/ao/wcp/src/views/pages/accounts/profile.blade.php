@extends('wcp::layout.master')
@section('jumbotron_breadcrumb')
	<ul class="breadcrumb">
	    <li>
	      <a href="{{ URL::to('admin') }}"><i class="fa fa-home"></i></a>
	    </li>
	    <li>
	      <a href="{{ URL::to('admin/accounts') }}">Accounts</a>
	    </li>
	    <li><a href="{{ Request::url() }}" class="active">Profile</a>
	    </li>
	</ul>
@stop
@section('jumbotron')
	@include('wcp::_partials.html.jumbotron')
@stop
@section("stylesheet")

	{{ HTML::style('assets/wcp/plugins/croppic/assets/css/croppic.css') }}

	<style type="text/css">
		.nopadding{
			padding: 0px 0px !important;
		}
		.nomargin{
			margin: 0px 0px !important;
		}
		#newAvatarCrop {
			width: 256px;
			height: 256px;
			position:relative; /* or fixed or absolute */
		}
		.newAvatarTriger{
			position:absolute;
			right:0px;
			top:0px;
			width:36px;
			height:36px;
			line-height:36px;
			text-align:center;
			background-color:#000;
			color:#fff;
			font-size:14px;
			text-decoration: none;
			opacity:0.5;
		}
		.newAvatarTriger:focus, .newAvatarTriger:hover, .newAvatarTriger:active{
			text-decoration: none;
			color:#fff;
			opacity: 1;
		}
		.new-element-dl-btn{
			position:absolute;
			right:0px;
			bottom:17px;
			width:30px;
			height:30px;
			line-height:30px;
			text-align:center;
			background-color: #ccc;
			color:#fff;
			border-radius:30px;
			-moz-border-radius:30px;
			-ms-border-radius:30px;
			-o-border-radius:30px;
			-webkit-border-radius:30px;
		}
		.new-element-dl-btn:hover, .new-element-dl-btn:focus, .new-element-dl-btn:visited{
			color:#fff;
		}
		.listValues >.row{
			position:relative;
			float:left;
			width:100%;
		}

		.listValues >.row .delMessage{
			position: absolute;
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
			background-color: #fff;
			color: #ff0000;
			font-size: 1em;
			font-style: italic;
			opacity:0.8;
			box-sizing: border-box;
			-moz-box-sizing: border-box;
			-webkit-box-sizing: border-box;
			-ms-box-sizing: border-box;
			-o-box-sizing: border-box;
			display:none;
		}
	</style>

@stop
@section("bootdata")
	<script type="text/javascript">
        window.Site = window.Site || {};
        //Config
        Site.data = Site.data || {};
        Site.data.user = {{ json_encode($item) }};
    </script>
@stop
@section('content')

	{{-- Begin Edit Avatar Modal --}}
		<div class="modal fade slide-down disable-scroll" id="editAvatarModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">

			<div class="modal-dialog modal-sm">

				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="close">
							<span aria-hidden="true"><i class="fa fa-times"></i></span>
						</button>
						<h4 class="modal-title">
							New Avatar
						</h4>
					</div>

					<div class="modal-body">

						<div id="newAvatarCrop"></div>

					</div>

					<div class="modal-footer">
						<!-- Empty footer -->
					</div>
				</div>

			</div>

		</div>
	{{-- End Edit Avatar Modal --}}

	{{-- Begin Edit Account Modal --}}
		<div class="modal fade slide-down disable-scroll" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">

			<div class="modal-dialog">

				<div class="modal-content">
					{{ Form::open(["url"=>"admin/accounts/".$item->id, "role"=>"form", "method"=>"put"]) }}
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="close">
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
							<button type="submit" class="btn btn-success pull-right">
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

	{{-- Begin New Message Modal --}}
		<div class="modal fade slide-down disable-scroll" id="newMessageModal" tabindex="-1" role="dialog" aria-labelledby="newMessageModalLabel" aria-hidden="true">

			<div class="modal-dialog">

				<div class="modal-content">
					{{ Form::open(["url"=>"admin/accounts/".$item->id."/message", "role"=>"form"]) }}
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="close">
								<span aria-hidden="true"><i class="fa fa-times"></i></span>
							</button>
							<h4 class="modal-title">
								New Message
							</h4>
						</div>

						<div class="modal-body">

							<div class="form-group m-t-15">

								{{ Form::textarea("body", "", 
												 [
												  "class"=>"form-control",
												  "rows"=>"5",
												  "placeholder"=>"Type your message here!"
												 ]) }}
								
							</div>

						</div>

						<div class="modal-footer">
							<input type="hidden" name="sender_id" value="{{ Sentry::getUser()->id }}">
							<button type="submit" class="btn btn-success pull-right">
								Send&nbsp;<i class="fa fa-send"></i>
							</button>
							<a href="#" class="btn btn-default pull-right m-r-10" data-dismiss="modal">
								Cancel
							</a>
						</div>
					{{ Form::close() }}

				</div>

			</div>

		</div>
	{{-- End New Message Modal --}}

	{{-- Begin New Mail Modal --}}
		<div class="modal fade slide-down disable-scroll" id="newMailModal" tabindex="-1" role="dialog" aria-labelledby="newMailModalLabel" aria-hidden="true">

			<div class="modal-dialog">

				<div class="modal-content">
					{{ Form::open(["url"=>"admin/accounts/".$item->id."/mail", "role"=>"form"]) }}
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="close">
								<span aria-hidden="true"><i class="fa fa-times"></i></span>
							</button>
							<h4 class="modal-title">
								New Mail
							</h4>
						</div>

						<div class="modal-body">

							<div class="form-group form-group-default">
								{{ Form::label("subject", "Subject") }}
								{{ Form::text("subject", "", ["class"=>"form-control", "placeholder"=>"Enter your subject."]) }}
							</div>

							<div class="form-group m-t-15">

								{{ Form::textarea("body", "", 
												 [
												  "class"=>"form-control",
												  "rows"=>"5",
												  "placeholder"=>"Type your message here!"
												 ]) }}
								
							</div>

						</div>

						<div class="modal-footer">
							<input type="hidden" name="sender_id" value="{{ Sentry::getUser()->id }}">
							<button type="submit" class="btn btn-success pull-right">
								Send&nbsp;<i class="fa fa-send"></i>
							</button>
							<a href="#" class="btn btn-default pull-right m-r-10" data-dismiss="modal">
								Cancel
							</a>
						</div>
					{{ Form::close() }}

				</div>

			</div>

		</div>
	{{-- End New Mail Modal --}}

	{{-- Begin New Text Modal --}}
		<div class="modal fade slide-down disable-scroll" id="newTextModal" tabindex="-1" role="dialog" aria-labelledby="newTextModalLabel" aria-hidden="true">

			<div class="modal-dialog">

				<div class="modal-content">
					{{ Form::open(["url"=>"admin/accounts/".$item->id."/mail", "role"=>"form"]) }}
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="close">
								<span aria-hidden="true"><i class="fa fa-times"></i></span>
							</button>
							<h4 class="modal-title">
								New Text
							</h4>
						</div>

						<div class="modal-body m-t-15">

							<div class="form-group form-group-default">
								{{ Form::label("phonenumber", "Phone Number") }}
								<select name="phonenumber" id="phonenumber" class="form-control">
									<option value="">Select Phone Number</option>
					          		@foreach($item->phonenumbers as $phonenumber)
					          			<option value="{{ $phonenumber->id }}" {{ ($item->phone->id == $phonenumber->id) ? ' selected ' : ' ' }}>
					          				{{$phonenumber->name}} - {{$phonenumber->number}}
					          			</option>
					          		@endforeach
								</select>
							</div>

							<div class="form-group m-t-15">

								{{ Form::textarea("body", "", 
												 [
												  "class"=>"form-control",
												  "rows"=>"5",
												  "placeholder"=>"Type your message here!"
												 ]) }}
								
							</div>

						</div>

						<div class="modal-footer">
							<input type="hidden" name="sender_id" value="{{ Sentry::getUser()->id }}">
							<button type="submit" class="btn btn-success pull-right">
								Send&nbsp;<i class="fa fa-send"></i>
							</button>
							<a href="#" class="btn btn-default pull-right m-r-10" data-dismiss="modal">
								Cancel
							</a>
						</div>
					{{ Form::close() }}

				</div>

			</div>

		</div>
	{{-- End New Text Modal --}}

	{{-- Begin New Program Modal --}}
		<div class="modal fade slide-down disable-scroll" id="newProgramModal" tabindex="-1" role="dialog" aria-labelledby="newProgramModalLabel" aria-hidden="true">

			<div class="modal-dialog">

				<div class="modal-content">
					{{ Form::open(["url"=>"admin/accounts/".$item->id."/programs", "role"=>"form"]) }}
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="close">
								<span aria-hidden="true"><i class="fa fa-times"></i></span>
							</button>
							<h4 class="modal-title">
								Add To Program
							</h4>
						</div>

						<div class="modal-body m-t-20">
							<div class="form-group form-group-default">
								{{ Form::label("program_id", "Program") }}
								<select name="program_id" id="program_id" name="program_id" class="program_id form-control">
									<option value="">Select program</option>
									@foreach($programs as $program)
										<option value="{{ $program->id }}"> {{ $program->name }} </option>
									@endforeach
								</select>
							</div>
						</div>

						<div class="modal-footer">
							<button type="submit" class="btn btn-success pull-right">
								Add
							</button>
							<a href="#" class="btn btn-default pull-right m-r-10" data-dismiss="modal">
								Cancel
							</a>
						</div>
					{{ Form::close() }}

				</div>

			</div>

		</div>
	{{-- End New Program Modal --}}

	{{-- Begin New Phone number Modal --}}
		<div class="modal fade slide-down disable-scroll" id="newPhoneNumberModal" tabindex="-1" role="dialog" aria-labelledby="newPhoneNumberModalLabel" aria-hidden="true">

			<div class="modal-dialog">

				<div class="modal-content">
					{{ Form::open(["url"=>"admin/accounts/".$item->id."/phonenumber", "role"=>"form"]) }}
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="close">
								<span aria-hidden="true"><i class="fa fa-times"></i></span>
							</button>
							<h4 class="modal-title">
								Add Phone Number
							</h4>
						</div>

						<div class="modal-body m-t-20">
							<div class="form-group form-group-default">
								{{ Form::label("name", "Name") }}
								{{ Form::text("name", "", ["class"=>"form-control", "placeholder"=>"E.g. Home Phone, Mobile, Glo Number, etc."]) }}								
							</div>
							<div class="form-group form-group-default">
								{{ Form::label("number", "Number") }}
								{{ Form::text("number", "", ["class"=>"form-control", "placeholder"=>"E.g. +2348030000000"]) }}								
							</div>
							<div class="form-group">
								{{ Form::label("make_primary", "Make Primary For Account?") }}
								<input type="checkbox" name="make_primary" value="1" class="switchery">							
							</div>
						</div>

						<div class="modal-footer">
							<button type="submit" class="btn btn-success pull-right">
								Add
							</button>
							<a href="#" class="btn btn-default pull-right m-r-10" data-dismiss="modal">
								Cancel
							</a>
						</div>
					{{ Form::close() }}

				</div>

			</div>

		</div>
	{{-- End New Phone number Modal --}}

	{{-- Begin New Award Modal --}}
		<div class="modal fade slide-down disable-scroll" id="newAwardModal" tabindex="-1" role="dialog" aria-labelledby="newAwardModalLabel" aria-hidden="true">

			<div class="modal-dialog">

				<div class="modal-content">
					{{ Form::open(["url"=>"admin/accounts/".$item->id."/awards", "role"=>"form", "enctype"=>"multipart/form-data"]) }}
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="close">
								<span aria-hidden="true"><i class="fa fa-times"></i></span>
							</button>
							<h4 class="modal-title">
								Publish Award
							</h4>
						</div>

						<div class="modal-body">
							<div class="form-group form-group-default m-t-10">
								{{ Form::label("file", "PDF File") }}
								{{ Form::file("file", "", ["class"=>"form-control", "placeholder"=>"Select image to upload"]) }}
								<small class="bold">Downloadable PDF of certificate.</small>
							</div>

							<div class="form-group form-group-default m-t-10">
								{{ Form::label("title", "Title") }}
								{{ Form::text("title", "", ["class"=>"form-control", "placeholder"=>"E.g. Complition certificate."]) }}
							</div>

							<div class="form-group form-group-default m-t-10">
								{{ Form::label("year", "Year") }}
								<select name="year" id="year" name="year" class="year form-control">
									<option value="">Select Year</option>
									@for($i = date("Y"); $i >= 2000; $i--)
										<option value="{{ $i }}"> {{ $i }} </option>
									@endfor
								</select>
							</div>

							<div class="form-group form-group-default">
								{{ Form::label("Program", "Program") }}
								<select name="program_id" id="program_id" name="program_id" class="program_id form-control">
									<option value="">Select program</option>
									@foreach($programs as $program)
										<option value="{{ $program->id }}"> {{ $program->name }} </option>
									@endforeach
								</select>
							</div>
						</div>

						<div class="modal-footer">
							<button type="submit" class="btn btn-success pull-right">
								Publish
							</button>
							<a href="#" class="btn btn-default pull-right m-r-10" data-dismiss="modal">
								Cancel
							</a>
						</div>
					{{ Form::close() }}
				</div>
			</div>
		</div>
	{{-- End New Award Modal --}}

	{{-- Begin New Result Modal --}}
		<div class="modal fade slide-down disable-scroll" id="newResultModal" tabindex="-1" role="dialog" aria-labelledby="newResultModalLabel" aria-hidden="true">

			<div class="modal-dialog">

				<div class="modal-content">
					{{ Form::open(["url"=>"admin/accounts/".$item->id."/results", "role"=>"form"]) }}
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="close">
								<span aria-hidden="true"><i class="fa fa-times"></i></span>
							</button>
							<h4 class="modal-title">
								Post Result
							</h4>
						</div>

						<div class="modal-body">

							@include("wcp::_partials.forms.user_result");

						</div>

						<div class="modal-footer">
							<!-- Empty Footer -->
						</div>
					{{ Form::close() }}

				</div>

			</div>

		</div>
	{{-- End New Result Modal --}}

	<div class="container-fluid">
		<!-- Begin Row -->
		<div class="row">
			<!-- Begin Column -->
			<div class="col-md-3">
				<!-- Avatar -->
				<div class="panel panel-transparent">
					<div class="panel-body nopadding">
						<a href="javascript:;"
							title="Change avatar" class="newAvatarTriger" id="newAvatarTriger">
							<i class="fa fa-pencil"></i>
						</a>
						<img src="{{ (!is_null($item->avatar)) ?  URL::to($item->avatar) : URL::to(Config::get('settings.avatar')) }}" alt="Avatar" id="account_avatar" class="img-responsive">
					</div>
				</div>
				<!-- Avatar -->
			</div>
			<!-- End Column -->
			<div class="col-md-9">
				<!-- Beging Basic Account Data -->
				<div class="panel panel-default" style="margin-bottom:0px;">
					<div class="panel-heading">
						<div class="panel-controls">
							<ul>
								<li>
				                	<a href="#" class="portlet-collapse" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i></a>
				                </li>
				            </ul>
						</div>
					</div>
					<div class="panel-body">
						<h4 class="nomargin">
							{{ $item->first_name." ".$item->last_name }}
							<div class="btn-group dropdown-default">
							    <a class="btn btn-success dropdown-toggle" data-toggle="dropdown" href="#"> <i class="fa fa-send"></i>&nbsp;Send <span class="caret"></span> </a>
							    <ul class="dropdown-menu ">
							        <li><a href="#" data-toggle="modal" data-target="#newMessageModal" >Message</a>
							        </li>
							        <li><a href="#" data-toggle="modal" data-target="#newMailModal">Mail</a>
							        </li>
							       <!--  <li><a href="#" data-toggle="modal" data-target="#newTextModal">Text</a>
							        </li> -->
							    </ul>
							</div>
						</h4>
						<h5 class="nomargin" style="font-weight:400;">{{ $item->email }}</h5>
						@if(count($item->phone) > 0)
							<h6 class="nomargin bold" style="color:#999;"><span class="bold">{{ $item->phone->name }}</span> -  {{ $item->phone->number }}</h6>
						@endif
						<h6 class="bold m-t-20">{{ $item->group->name }}</h6>
					</div>
				</div>
				<!-- End Basic Account Data -->
				<!-- Beging Phone Numbers -->
				<div class="panel panel-transparent panel-js">
					<div class="panel-heading">
						<h4 class="panel-title">
							<i class="fa fa-phone"></i>&nbsp;Phone Numbers	
						</h4>
						<div class="panel-controls">
							<ul>
								<li>
				                	<a href="#" class="portlet-collapse" title="Add to a program" data-toggle="modal" data-target="#newPhoneNumberModal"><i class="fa fa-plus"></i></a>
				                </li>
				                <li>
				                	<a href="#" class="portlet-collapse" data-toggle="collapse"><i class="portlet-icon portlet-icon-collapse"></i></a>
				                </li>
				            </ul>
						</div>
					</div>
					<div class="panel-body nopadding">
						<table id="myDataTable" class="table table-hover" cellspacing="0" width="100%">
						    <tbody>
						    	@foreach($item->phonenumbers as $phonenumber)
							        <tr>
							            <td>
							            	@if($phonenumber->id == $item->phone->id)
							            		<i class="fa fa-check" style="color:#10CFBD;"></i>
							            	@endif
							            	<span class="bold">{{ $phonenumber->name }}</span> - {{ $phonenumber->number }}
							            	@if($phonenumber->id == $item->phone->id)
							            		<small style="color:#aaa;"><i>(Primary)</i></small>
							            	@endif
							            </td>
							            <td>
							            	<div class="btn-group dropdown-default pull-right">
											    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"> Action <span class="caret"></span> </a>
											    <ul class="dropdown-menu">
											        <li><a href="#" data-toggle="modal" data-target="#editPhoneModal{{ $phonenumber->id }}">Edit</a>
											        </li>
											        <li><a href="#" data-toggle="modal" data-target="#deletePhoneModal{{ $phonenumber->id }}">Delete</a>
											        </li>
											    </ul>
											</div>
							            </td>
										{{-- Begin Edit Phone Number Modal --}}
											<div class="modal fade slide-down disable-scroll" id="editPhoneModal{{ $phonenumber->id }}" tabindex="-1" role="dialog" aria-labelledby="editPhoneModalLabel{{ $phonenumber->id }}" aria-hidden="true">

												<div class="modal-dialog">

													<div class="modal-content">
														{{ Form::open(["url"=>"admin/accounts/".$item->id."/phonenumber/".$phonenumber->id, "role"=>"form", "method"=>"put"]) }}
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal" aria-label="close">
																	<span aria-hidden="true"><i class="fa fa-times"></i></span>
																</button>
																<h4 class="modal-title">
																	Edit Phone Number
																</h4>
															</div>

															<div class="modal-body m-t-20">
																<div class="form-group form-group-default">
																	<label for="name">Name</label>
																	<input class="form-control" placeholder="E.g. Home Phone, Mobile, Glo Number, etc." name="name" type="text" value="{{ $phonenumber->name }}" id="name">								
																</div>
																<div class="form-group form-group-default">
																	<label for="number">Number</label>
																	<input class="form-control" placeholder="E.g. +2348030000000" name="number" type="text" value="{{ $phonenumber->number }}" id="number">								
																</div>
																<div class="form-group">
																	<label for="make_primary">Make Primary For Account?</label>
																	<input type="checkbox" name="make_primary" value="1" class="switchery" {{ ($phonenumber->id == $item->phone->id) ? ' checked ' : ' ' }} >																	
																</div>
															</div>

															<div class="modal-footer">
																<button type="submit" class="btn btn-success pull-right">
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
										{{-- End Edit Phone Number Modal --}}
										{{-- Begin Delete Phone Number Modal --}}
											<div class="modal fade slide-down disable-scroll" id="deletePhoneModal{{ $phonenumber->id }}" tabindex="-1" role="dialog" aria-labelledby="deletePhoneModalLabel{{ $phonenumber->id }}" aria-hidden="true">
												<div class="modal-dialog modal-sm">
													<div class="modal-content">
														{{ Form::open(["url"=>"admin/accounts/".$item->id."/phonenumber/".$phonenumber->id, "role"=>"form", "method"=>"delete"]) }}
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal" aria-label="close">
																	<span aria-hidden="true"><i class="fa fa-times"></i></span>
																</button>
																<h4 class="modal-title">
																	<strong>Attention</strong>
																</h4>
															</div>

															<div class="modal-body">

																<p>
																	Are you sure you want to delete this item.
																</p>

															</div>

															<div class="modal-footer">
																<button type="submit" class="btn btn-danger pull-right">
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
										{{-- End Delete PhoneNumber Modal --}}
							        </tr>
						        @endforeach
						    </tbody>
						</table>
					</div>
				</div>
				<!-- End Phone Numbers -->
				<!-- Beging PhoneNumbers -->
				<div class="panel panel-default panel-js">
					<div class="panel-heading">
						<h4 class="panel-title">
							<i class="fa fa-graduation-cap"></i>&nbsp;Programs	
						</h4>
						<div class="panel-controls">
							<ul>
								<li>
				                	<a href="#" title="Add to a program" data-toggle="modal" data-target="#newProgramModal"><i class="fa fa-plus"></i></a>
				                </li>
				                 <li>
				                	<a href="#" class="portlet-collapse" data-toggle="collapse"><i class="portlet-icon portlet-icon-collapse"></i></a>
				                </li>
				            </ul>
						</div>
					</div>
					<div class="panel-body">
						@foreach(array_chunk($item->programs->all(), 3) as $row)
							<div class="row">
								@foreach($row as $program)
									<div class="col-md-4">
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title" title="{{ $program->type->title }}">
													{{ $program->name }}
												</a>
												</h4>
												<div class="panel-controls">
													<ul>
										                <li><a href="#" class="portlet-close" data-toggle="modal" data-target="#removeProgramModal{{ $program->id }}"><i class="fa fa-times"></i></a>
										                </li>
										            </ul>
												</div>
											</div>
											<div class="panel-body">
												<img src="{{ URL::to($program->image) }}" class="img-responsive">
											</div>
										</div>
									</div>
									{{-- Begin Delete Program Modal --}}
										<div class="modal fade slide-down disable-scroll" id="removeProgramModal{{ $program->id }}" tabindex="-1" role="dialog" aria-labelledby="removeProgramModalLabel{{ $program->id }}" aria-hidden="true">

											<div class="modal-dialog modal-sm">

												<div class="modal-content">
													{{ Form::open(["url"=>"admin/accounts/".$item->id."/programs/".$program->id, "role"=>"form", "method"=>"delete"]) }}
														<div class="modal-header">
															<button type="button" class="close" data-dismiss="modal" aria-label="close">
																<span aria-hidden="true"><i class="fa fa-times"></i></span>
															</button>
															<h4 class="modal-title">
																<strong>Attention</strong>
															</h4>
														</div>

														<div class="modal-body">

															<p>
																Are you sure you want to delete this item.
															</p>

														</div>

														<div class="modal-footer">
															<button type="submit" class="btn btn-danger pull-right">
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
									{{-- End Delete Program Modal --}}
								@endforeach
							</div>
						@endforeach
					</div>
				</div>
				<!-- End Programs -->
				<!-- Beging Results -->
				<div class="panel panel-default panel-js">
					<div class="panel-heading">
						<h4 class="panel-title">
							<i class="fa fa-list-ul"></i>&nbsp;Results	
						</h4>
						<div class="panel-controls">
							<ul>
								<li>
				                	<a href="#" class="portlet-collapse" title="Post new result" data-toggle="modal" data-target="#newResultModal"><i class="fa fa-plus"></i></a>
				                </li>
				                 <li>
				                	<a href="#" class="portlet-collapse" data-toggle="collapse"><i class="portlet-icon portlet-icon-collapse"></i></a>
				                </li>
				            </ul>
						</div>
					</div>
					<div class="panel-body">
						<div class="row">
							@foreach($item->results as $result)
								<div class="co-md-12">
									<div class="panel panel-default">
										<div class="panel-heading" style="min-height: 20px; padding-bottom:0px;">
											<h4 class="panel-title" title="{{ $result->title }}">
												{{ $result->program->name }}
												-
												{{ $result->semester->name }} 
												({{ $result->year }})
											</a>
											</h4>
											<div class="panel-controls">
												<ul>
									                <li><a href="#" class="portlet-edit" data-toggle="modal" data-target="#editResultModal{{ $result->id }}"><i class="fa fa-pencil"></i></a>
									                </li>
									                <li><a href="#" class="portlet-close" data-toggle="modal" data-target="#removeResultModal{{ $result->id }}"><i class="fa fa-times"></i></a>
									                </li>
									                <li><a href="#" class="portlet-expand" data-toggle="modal" data-target="#viewResultModal{{ $result->id }}"><i class="fa fa-list"></i></a>
									                </li>
									            </ul>
											</div>
										</div>
										<div class="panel-body">
											<!-- Empty body -->
										</div>
									</div>
								</div>
								{{-- Begin Edit Result Modal --}}
									<div class="modal fade slide-down disable-scroll" id="editResultModal{{ $result->id }}" tabindex="-1" role="dialog" aria-labelledby="editResultModalLabel{{ $result->id }}" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content">
												{{ Form::open(["url"=>"admin/accounts/".$item->id."/results/".$result->id, "role"=>"form", "method"=>"put"]) }}
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-label="close">
															<span aria-hidden="true"><i class="fa fa-times"></i></span>
														</button>
														<h4 class="modal-title">
															<strong>Edit Result</strong>
														</h4>
													</div>

													<div class="modal-body">
														@include("wcp::_partials.forms.user_result_edit")
													</div>

													<div class="modal-footer">
														<!-- Empty Footer -->
													</div>
												{{ Form::close() }}
											</div>
										</div>
									</div>
								{{-- End Edit Result Modal --}}

								{{-- Begin View Result Modal --}}
									<div class="modal fade slide-down disable-scroll" id="viewResultModal{{ $result->id }}" tabindex="-1" role="dialog" aria-labelledby="viewResultModalLabel{{ $result->id }}" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-label="close">
														<span aria-hidden="true"><i class="fa fa-times"></i></span>
													</button>
													<h4 class="modal-title">
														<strong>
															{{ $result->program->name }}
															-
															{{ $result->semester->name }} 
															({{ $result->year }})
														</strong>
													</h4>
												</div>

												<div class="modal-body">
													<table id="myDataTable" class="table table-hover" cellspacing="0" width="100%">

													    <tbody>
													    	@foreach($result->resultslist as $resultlistitem)
														        <tr>
														            <td>{{ $resultlistitem->name }}</td>
														            <td>{{ $resultlistitem->value }}</td>
														        </tr>
													        @endforeach
													    </tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								{{-- End View Result Modal --}}

								{{-- Begin Delete Result Modal --}}
									<div class="modal fade slide-down disable-scroll" id="removeResultModal{{ $result->id }}" tabindex="-1" role="dialog" aria-labelledby="removeResultModalLabel{{ $result->id }}" aria-hidden="true">

										<div class="modal-dialog modal-sm">

											<div class="modal-content">
												{{ Form::open(["url"=>"admin/accounts/".$item->id."/results/".$result->id, "role"=>"form", "method"=>"delete"]) }}
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-label="close">
															<span aria-hidden="true"><i class="fa fa-times"></i></span>
														</button>
														<h4 class="modal-title">
															<strong>Attention</strong>
														</h4>
													</div>

													<div class="modal-body">
														<p>
															Are you sure you want to delete this item.
														</p>
													</div>

													<div class="modal-footer">
														<button type="submit" class="btn btn-danger pull-right">
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
								{{-- End Delete Result Modal --}}
							@endforeach
						</div>
					</div>
				</div>
				<!-- End Results -->
				<!-- Beging Awards -->
				<div class="panel panel-default panel-js">
					<div class="panel-heading">
						<h4 class="panel-title">
							<i class="fa fa-trophy"></i>&nbsp;Awards	
						</h4>
						<div class="panel-controls">
							<ul>
								<li>
				                	<a href="#" class="portlet-collapse" title="Give an award" data-toggle="modal" data-target="#newAwardModal"><i class="fa fa-plus"></i></a>
				                </li>
				                 <li>
				                	<a href="#" class="portlet-collapse" data-toggle="collapse"><i class="portlet-icon portlet-icon-collapse"></i></a>
				                </li>
				            </ul>
						</div>
					</div>
					<div class="panel-body">
						<div class="row">
							@foreach($item->awards as $award)
								<div class="co-md-12">
									<div class="panel panel-default">
										<div class="panel-heading" style="min-height: 20px; padding-bottom:0px;">
											<h4 class="panel-title" title="{{ $award->title }}">
												{{ $award->title }} ({{ $award->year }})
											</a>
											</h4>
											<div class="panel-controls">
												<ul>
									                <li><a href="#" class="portlet-edit" data-toggle="modal" data-target="#editAwardModal{{ $award->id }}"><i class="fa fa-pencil"></i></a>
									                </li>
									                <li><a href="#" class="portlet-close" data-toggle="modal" data-target="#removeAwardModal{{ $award->id }}"><i class="fa fa-times"></i></a>
									                </li>
									            </ul>
											</div>
										</div>
										<div class="panel-body">
											<small class="bold" style="color:#999;">{{ $award->program->name }}</small>
										</div>
									</div>
								</div>
								{{-- Begin Edit Award Modal --}}
									<div class="modal fade slide-down disable-scroll" id="editAwardModal{{ $award->id }}" tabindex="-1" role="dialog" aria-labelledby="editAwardModalLabel{{ $award->id }}" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content">
												{{ Form::open(["url"=>"admin/accounts/".$item->id."/awards/".$award->id, "role"=>"form", "method"=>"put", "enctype"=>"multipart/form-data"]) }}
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-label="close">
															<span aria-hidden="true"><i class="fa fa-times"></i></span>
														</button>
														<h4 class="modal-title">
															<strong>Edit Award</strong>
														</h4>
													</div>

													<div class="modal-body">
														<div class="form-group form-group-default m-t-10">
															<label for="file">PDF File</label>
															<input name="file" type="file" id="file">
															<small class="bold">Downloadable PDF of certificate.</small>
														</div>

														<div class="form-group form-group-default m-t-10">
															<label for="title">Title</label>
															<input class="form-control" placeholder="E.g. Complition certificate." name="title" type="text" value="{{ $award->title }}" id="title">
														</div>

														<div class="form-group form-group-default m-t-10">
															<label for="year">Year</label>
															<select name="year" id="year" class="year form-control">
																<option value="">Select Year</option>
																@for($i = date("Y"); $i >= 2000; $i--)
																	<option value="{{ $i }}" {{ ($i == $award->year) ? ' selected ' : '' }} > {{ $i }} </option>
																@endfor
															</select>
														</div>

														<div class="form-group form-group-default">
															{{ Form::label("Program", "Program") }}
															<select name="program_id" id="program_id" name="program_id" class="program_id form-control">
																<option value="">Select program</option>
																@foreach($programs as $program)
																	<option value="{{ $program->id }}"  {{ ($program->id == $award->program_id) ? ' selected ' : '' }} > {{ $program->name }} </option>
																@endforeach
															</select>
														</div>
													</div>

													<div class="modal-footer">
														<button type="submit" class="btn btn-success pull-right">
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
								{{-- End Edit Award Modal --}}

								{{-- Begin Delete Award Modal --}}
									<div class="modal fade slide-down disable-scroll" id="removeAwardModal{{ $award->id }}" tabindex="-1" role="dialog" aria-labelledby="removeAwardModalLabel{{ $award->id }}" aria-hidden="true">

										<div class="modal-dialog modal-sm">

											<div class="modal-content">
												{{ Form::open(["url"=>"admin/accounts/".$item->id."/awards/".$award->id, "role"=>"form", "method"=>"delete"]) }}
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-label="close">
															<span aria-hidden="true"><i class="fa fa-times"></i></span>
														</button>
														<h4 class="modal-title">
															<strong>Attention</strong>
														</h4>
													</div>

													<div class="modal-body">
														<p>
															Are you sure you want to delete this item.
														</p>
													</div>

													<div class="modal-footer">
														<button type="submit" class="btn btn-danger pull-right">
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
								{{-- End Delete Award Modal --}}
							@endforeach
						</div>
					</div>
				</div>
				<!-- End Awards -->
			</div>			
		</div>
		<!-- End Row -->
	</div>
	
@stop

@section("javascript")
	{{ HTML::script('assets/wcp/plugins/croppic/assets/js/jquery.mousewheel.min.js') }}
	{{ HTML::script('assets/wcp/plugins/croppic/croppic.js') }}
	{{ HTML::script("assets/wcp/plugins/boostrap-form-wizard/js/jquery.bootstrap.wizard.min.js") }}
	{{ HTML::script("assets/wcp/js/user_results.js") }}
	<script type="text/javascript">
		$(function(){
			var url = Site.Config.url;
			var user = Site.data.user;
			var cropperOptions = {
				modal:true,
				zoomFactor:20,
				rotateControls:false,
				customUploadButtonId:'newAvatarTriger',
				uploadUrl:url+'/api/user/uploadAvatar',
				cropUrl:url+'/api/user/cropAvatar',
				uploadData:{
					"user": user.id,
					"_token": Site.Config.token
				},
				cropData:{
					"user": user.id,
					"_token": Site.Config.token
				},
				onAfterImgCrop: function(data){
					$("#account_avatar").prop("src", url+"/"+data.url);
					 $('body').pgNotification({
	                    message:data.message,
	                    style:'flip',
	                    type: data.level,
	                    timeout:7000,
	                    showClose:true
	                }).show();
					resetCroper();
				},
				onError: function(errormsg){
					 $('body').pgNotification({
	                    message:errormsg,
	                    style:'flip',
	                    type: 'danger',
	                    timeout:7000,
	                    showClose:true
	                }).show();
				}
			};
			var cropper = new Croppic('newAvatarCrop', cropperOptions);

			function resetCroper(){
				cropper.reset();
			}

			user_result.init();
		});
	</script>
@stop