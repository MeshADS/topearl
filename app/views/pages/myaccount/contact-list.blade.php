@extends("layout.master")
@section("title") My Account :: {{ $basicdata->fullname }} @stop
@include("_partials.html.default_meta")
@section("page") myaccount @stop
@section("stylesheet")
	{{ HTML::style('assets/site/plugins/croppic/assets/css/croppic.css') }}
	<!-- Stylesheet -->
@stop
@section("bootdata")
	<!-- Boot data -->
	<script type="text/javascript">
		//Data
		Site.data = Site.data || {};
		Site.data.userdata = {{ json_encode($userdata) }};
	</script>
@stop

@section("content")
	<!-- Begin summary -->
	<div class="container-fluid nopadding alt-background">

		<div class="col-md-12 nopadding">

			<div class="container p-h-30 white-background nopadding">

				@include("_partials.html.myAccountMenu")
				
				<!-- Begin Modals -->
					<!-- Begin Create Modal -->
						<div class='myModal slideDown' id='createModal'>
							<div class='black-background bg-olay myModal-olay'>&nbsp;</div>
							<div class='myModal-inner'>
								<div class='myModal-dialog'>
									<div class='myModal-content'>
										{{ Form::open(["url"=>"myaccount/contact-list"]) }}
											<div class='myModal-heading p-h-20 p-v-5'>
												<h4 class='myModal-title text-center uppercase xs-text bold'>
													New
												</h4>
												<a href='#' data-dismiss='myModal' class='myModal-close' title='Close'><i class='fa fa-times'></i></a>
											</div>
											<div class='myModal-body p-h-20 p-v-20 alt-background'>
												<div class="form-group form-group-default">
													{{ Form::label("name", "Name") }}
													{{ Form::text("name", "", ["class"=>"form-control", "placeholder"=>"E.g. Home Phone, Mobile, Glo Number, etc."]) }}								
												</div>
												<div class="form-group form-group-default">
													{{ Form::label("number", "Number") }}
													{{ Form::text("number", "", ["class"=>"form-control", "placeholder"=>"E.g. +2348030000000"]) }}								
												</div>
												<div class="form-group m-v-0">
													<input type="checkbox" name="make_primary" class="make_primary" id="make_primary" value="1" class="switchery">							
													{{ Form::label("make_primary", "Make Primary?") }}
												</div>
											</div>
											<div class='myModal-footer p-h-20'>
												<div class="bulk_checked_list hide">
													<!-- Contain selected items -->
												</div>
												<button type="submit" class="pull-right btn green2-background hoverable bordered white-link btn-sm">Save</button>
												<a href="#" class="pull-right btn white-background hoverable bordered gray-link btn-sm m-r-5" data-dismiss="myModal">Cancel</a>
											</div>
										{{ Form::close() }}
									</div>						
								</div>				
							</div>
						</div>								
					<!-- End Create Modal -->
				<!-- End Modals -->

				<div class="col-md-9 m-t-70">
					<div class="container-fluid">
						<div class="row">
							<div class="col-md-12">
								<div class="spanned_element m-b-10">

									<button type="button" data-toggle="myModal" data-target="#createModal" class="pull-left m-r-5 btn white-background bordered hoverable">New</button>						

									<div class="dropdown pull-left">
									  <button class="btn white-background bordered hoverable flat-it dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
									    Sort By
									    <span class="caret"></span>
									  </button>
									  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
									    <li><a href="{{ URL::to('myaccount/contact-list?sort=a-z') }}">A - Z</a></li>
									    <li><a href="{{ URL::to('myaccount/contact-list?sort=z-a') }}">Z - A</a></li>
									    <li><a href="{{ URL::to('myaccount/contact-list') }}">Default</a></li>
									  </ul>
									</div>				
								</div>		
							</div>
						</div>
						<table class="table table-striped" cellspacing="0" width="100%">
						    <thead>
						        <tr>
						            <th class="bold uppercase">Name</th>
						            <th class="bold uppercase">Number</th>
						            <th class="">&nbsp;</th>
						        </tr>
						    </thead>
						    <tbody>
						    	@foreach($list as $item)
							        <tr>
							            <td class="capitalize xs-text">
					    					<h6 class="no-text-decoration gray-link spanned_element h-30 p-v-5 elipssis m-v-0">
							            		{{ $item->name }}
					    						{{ ( $item->id == $userdata->phone_id ) ? ' <i class="fa fa-check"></i> ' : '' }}
					    					</h6>
							            </td>
							            <td class="xs-text">
							            	<h6 class="no-text-decoration gray-link spanned_element h-30 p-v-5 elipssis m-v-0">
							            		{{ $item->number }}
							            	</h6>
							            </td>
							            <td class="xs-text">
											<!-- Single button -->
											<div class="btn-group pull-right">
											  <button type="button" class="btn white-background bordered hoverable flat-it dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											    Action <span class="caret"></span>
											  </button>
											  <ul class="dropdown-menu flat-it">
											    	<li><a href="#" data-toggle="myModal" data-target="#editModal{{ $item->id }}">Edit</a></li>
						            			@if(count($list) > 1)
											    	<li><a href="#" data-toggle="myModal" data-target="#deleteModal{{ $item->id }}">Delete</a></li>
												@endif
											  </ul>
											</div>
							            </td>
							        </tr>
							        <!-- Beding Edit Modal -->
					        			<div class='myModal slideUp' id='editModal{{ $item->id }}'>
					        				<div class='black-background bg-olay myModal-olay'>&nbsp;</div>
					        				<div class='myModal-inner'>
					        					<div class='myModal-dialog'>
					        						<div class='myModal-content'>
					        							{{ Form::open(["url"=>"myaccount/contact-list/".$item->id, "method"=>"put"]) }}
															<div class="myModal-heading p-h-20 p-v-5">
																<h4 class="myModal-title text-center uppercase xs-text bold">
																	Edit
																</h4>
																<a href="#" data-dismiss="myModal" class="myModal-close" title="Close"><i class="fa fa-times"></i></a>
															</div>
															<div class="myModal-body p-h-20 p-v-20 alt-background">
																<div class="form-group form-group-default">
																	<label for="name">Name</label>
																	<input class="form-control" placeholder="E.g. Home Phone, Mobile, Glo Number, etc." name="name" type="text" value="{{ $item->name }}" id="name">								
																</div>
																<div class="form-group form-group-default">
																	<label for="number">Number</label>
																	<input class="form-control" placeholder="E.g. +2348030000000" name="number" type="text" value="{{ $item->number }}" id="number">								
																</div>
																<div class="form-group m-v-0">
																	<input type="checkbox" name="make_primary" class="make_primary" id="make_primary" value="1" {{ ($item->id == $userdata->phone->id) ? ' checked ' : ' ' }}>
																	<label for="make_primary">Make Primary?</label>
																</div>
															</div>
															<div class="myModal-footer p-h-20">
																<button type="submit" class="pull-right btn green2-background hoverable bordered white-link btn-sm">Save</button>
																<a href="#" class="pull-right btn white-background hoverable bordered gray-link btn-sm m-r-5" data-dismiss="myModal">Cancel</a>
															</div>
														{{ Form::close() }}
					        						</div>						
					        					</div>				
					        				</div>
					        			</div>
				        			<!-- End Edit Modal -->

									<!-- Begin Delete Modal -->
										<div class='myModal grow' id='deleteModal{{ $item->id }}'>
											<div class='black-background bg-olay myModal-olay'>&nbsp;</div>
											<div class='myModal-inner'>
												<div class='myModal-dialog myModal-sm'>
													<div class='myModal-content'>
														{{ Form::open(["url"=>"myaccount/contact-list/".$item->id, "method"=>"delete"]) }}
															<div class='myModal-body p-v-40'>
																<p class="text-center">													
																	Are you sure you want to delete thit item?
																</p>
															</div>
															<div class='myModal-footer alt-background'>
																<button type="submit" class="pull-right btn green2-background hoverable bordered white-link btn-sm">Yes</button>
																<a href="#" class="pull-right btn white-background hoverable bordered gray-link btn-sm m-r-5" data-dismiss="myModal">No</a>
															</div>
														{{ Form::close() }}
													</div>						
												</div>				
											</div>
										</div>								
									<!-- End Delete Modal -->
							        		
						        @endforeach
						    </tbody>
						</table>
						<!-- Begin Row -->
							<div class="row">
								<div class="col-md-12 text-center">
									{{ $list->appends(Request::except('page'))->links() }}
								</div>
							</div>
						<!-- End Row -->
					</div>
				</div>

			</div>

		</div>
		

	</div>
	<!-- End summary -->

@stop

@section("javascript")
	{{ HTML::script('assets/site/plugins/croppic/croppic.js') }}
	<!-- Javascript -->
	<script type="text/javascript">
		$(function(){
			myaccount.init();
		});
	</script>
@stop