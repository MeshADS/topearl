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
	<div class="container-fluid nopadding alt-background messages-page">

		<div class="col-md-12 nopadding">

			<div class="container p-h-30 white-background nopadding">

				@include("_partials.html.myAccountMenu")
				
				<!-- Begin Modals -->
					<!-- Begin Read Modal -->
						<div class='myModal grow' id='markReadModal'>
							<div class='black-background bg-olay myModal-olay'>&nbsp;</div>
							<div class='myModal-inner'>
								<div class='myModal-dialog myModal-sm'>
									<div class='myModal-content'>
										{{ Form::open(["url"=>"myaccount/messages/mark/read", "method"=>"put"]) }}
											
											<div class='myModal-body p-v-20'>
												<p class="text-center">	
													Mark selected items as read?
												</p>
											</div>
											<div class='myModal-footer alt-background'>
												<div class="bulk_checked_list hide">
													<!-- Contain selected items -->
												</div>
												<button type="submit" class="pull-right btn green2-background hoverable bordered white-link btn-sm">Yes</button>
												<a href="#" class="pull-right btn white-background hoverable bordered gray-link btn-sm m-r-5" data-dismiss="myModal">No</a>
											</div>
										{{ Form::close() }}
									</div>						
								</div>				
							</div>
						</div>
								
					<!-- End Read Modal -->
					<!-- Begin Unread Modal -->
						<div class='myModal grow' id='markUnreadModal'>
							<div class='black-background bg-olay myModal-olay'>&nbsp;</div>
							<div class='myModal-inner'>
								<div class='myModal-dialog myModal-sm'>
									<div class='myModal-content'>
										{{ Form::open(["url"=>"myaccount/messages/mark/unread", "method"=>"put"]) }}
											<div class='myModal-heading'>
												<h4 class='myModal-title text-center uppercase xs-text bold'>
													Attention
												</h4>
												<a href='#' data-dismiss='myModal' class='myModal-close' title='Close'><i class='fa fa-times'></i></a>
											</div>
											<div class='myModal-body'>
												<p class="text-center">													
													Mark selected items as unread?
												</p>
											</div>
											<div class='myModal-footer'>
												<div class="bulk_checked_list hide">
													<!-- Contain selected items -->
												</div>
												<button type="submit" class="pull-right btn green2-background hoverable bordered white-link btn-sm">Yes</button>
												<a href="#" class="pull-right btn white-background hoverable bordered gray-link btn-sm m-r-5" data-dismiss="myModal">No</a>
											</div>
										{{ Form::close() }}
									</div>						
								</div>				
							</div>
						</div>								
					<!-- End Unread Modal -->
				<!-- End Modals -->

				<div class="col-md-9 m-t-70">
					<div class="container-fluid">
						<div class="row">
							<div class="col-md-12">
								<div class="spanned_element m-b-10">
									<div class="dropdown pull-left">
									  <button class="btn white-background bordered hoverable flat-it dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
									    Sort By
									    <span class="caret"></span>
									  </button>
									  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
									    <li><a href="{{ URL::to('myaccount/messages?sort=newest') }}">Newest</a></li>
									    <li><a href="{{ URL::to('myaccount/messages?sort=oldest') }}">Oldest</a></li>
									    <li><a href="{{ URL::to('myaccount/messages?sort=unread') }}">Unread</a></li>
									  </ul>
									</div>

									<!-- Single button -->
									<div class="btn-group pull-right bulkActionElement hide">
									  <button type="button" class="btn white-background bordered hoverable flat-it dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									    Action <span class="caret"></span>
									  </button>
									  <ul class="dropdown-menu">
									    <li><a href="#" data-toggle="myModal" data-target="#markReadModal">Mark Read</a></li>
									    <li><a href="#" data-toggle="myModal" data-target="#markUnreadModal">Mark Unread</a></li>
									  </ul>
									</div>
								</div>								
							</div>
						</div>
						<table class="table" cellspacing="0" width="100%">
						    <thead>
						        <tr>
						            <th>
						            	<input type="checkbox" name="master_checkbox" id="master_checkbox">
						            </th>
						            <th class="bold uppercase">Sender</th>
						            <th class="bold uppercase">Subject</th>
						            <th class="bold uppercase">Date</th>
						        </tr>
						    </thead>
						    <tbody>
						    	@foreach($list as $item)
							        <tr class="{{ ( $item->pivot->state == 0 ) ? ' unread alt-background ' : ' white-background ' }} hoverable">
							            <td>
							            	<input type="checkbox" name="checkbox[]" value="{{ $item->id }}" class="table_checkbox">
							            </td>
							            <td class="capitalize xs-text">
					    					<a href="{{ URL::to('myaccount/messages/'.$item->id) }}" class="no-text-decoration gray-link spanned_element h-30 p-v-5 elipssis">
							            		{{ $item->sender->first_name }} {{ $item->sender->last_name }}					    						
					    					</a>
							            </td>
							            <td class="xs-text">
							            	<a href="{{ URL::to('myaccount/messages/'.$item->id) }}" class="no-text-decoration gray-link spanned_element h-30 p-v-5 elipssis">
							            		{{ substr(strip_tags($item->body), 0, 76) }}
							            	</a>
							            </td>
							            <td class="uppercase xs-text">{{ date("d M Y, h:iA", strtotime($item->created_at)) }}</td>
							        </tr>
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