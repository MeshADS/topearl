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
	<div class="container-fluid nopadding alt-background overview-board">

		<div class="col-md-12 nopadding">

			<div class="container p-h-30 white-background nopadding">

				@include("_partials.html.myAccountMenu")
				<div class="hide">
					<div id="newAvatarCrop"></div>
				</div>

				<div class="col-md-9 m-t-70">
					<div class="container-fluid">
						<!-- Begin Row -->
						<div class="row">
							<div class="col-md-2">
								<div class="spanned_element">
									<img src="{{ (!is_null($userdata->avatar)) ? URL::to($userdata->avatar) : UR::to(Config::get('settings.avatar')) }}" alt="Avatar" class="fullwidth" id="account_avatar">
									<a href="javascript:;"
										title="Change avatar" class="newAvatarTriger" id="newAvatarTriger">
										<i class="fa fa-pencil"></i>
									</a>		
								</div>
							</div>
							<div class="col-md-10">
								<div class="spanned_element">
									<a href="{{ URL::to('myaccount/settings') }}" class="pull-right gray-text visible-lg visible-md" title="settings"><i class="fa fa-pencil"></i></a>									
									<h4 class="black-text m-text bold">{{ $userdata->first_name." ".$userdata->last_name }}</h4>
									<h4 class="gray-text xs-text">{{ $userdata->email }}</h4>
									<h4 class="gray-text xs-text">{{ "<span class='bold'>".$userdata->phone->name."<span> ".$userdata->phone->number }}</h4>
									<a href="{{ URL::to('myaccount/settings') }}" class="pull-left gray-text visible-sm visible-xs" title="settings"><i class="fa fa-pencil"></i></a>									
								</div>
							</div>
						</div>
						<!-- End Row -->
						<!-- Begin Row -->
						<div class="row m-t-30">
							<!-- Begin Message Item -->
							<div class="col-md-6">
								<div class="spanned_element white-background hoverable p-h-15 board-item m-b-30">
									<h4 class="gray-text capitalize s-text m-t-15">
										Messages
										<span class="capitalize xs-text green2-text bold">
											({{ $userdata->unreadMessages }} Unread)
										</span>
									</h4>
									<a href="{{ URL::to('myaccount/messages') }}" class="pull-left xs-text bold m-b-10 btn btn-sm flat-it green2-background white-link hoverable">
										Read Messages&nbsp;<i class="fa fa-caret-right"></i>
									</a>
								</div>
							</div>
							<!-- End Message Item -->
							<!-- Begin Programs Item -->
							<div class="col-md-6">
								<div class="spanned_element white-background hoverable p-h-15 board-item m-b-30">
									<h4 class="gray-text capitalize s-text m-t-15">
										My Programs
										(<span class="bold xs-text green2-text">{{ $userdata->programsCount }}</span>)
									</h4>
									<a href="{{ URL::to('myaccount/programs') }}" class="pull-left xs-text bold m-b-10 btn btn-sm flat-it green2-background white-link hoverable">
										View&nbsp;<i class="fa fa-caret-right"></i>
									</a>
								</div>
							</div>
							<!-- End Programs Item -->
						</div>
						<!-- End Row -->
						<!-- Begin Row -->
						<div class="row">
							<!-- Begin Message Item -->
							<div class="col-md-6">
								<div class="spanned_element white-background hoverable p-h-15 board-item m-b-30">
									<h4 class="gray-text capitalize s-text m-t-15">
										Results
										(<span class="bold xs-text green2-text">{{ $userdata->resultsCount }}</span>)
									</h4>
									<a href="{{ URL::to('myaccount/results') }}" class="pull-left xs-text bold m-b-10 btn btn-sm flat-it green2-background white-link hoverable">
										View Results&nbsp;<i class="fa fa-caret-right"></i>
									</a>
								</div>
							</div>
							<!-- End Message Item -->
							<!-- Begin Programs Item -->
							<div class="col-md-6">
								<div class="spanned_element white-background hoverable p-h-15 board-item m-b-30">
									<h4 class="gray-text capitalize s-text m-t-15">
										Awards
										(<span class="bold xs-text green2-text">{{ $userdata->awardsCount }}</span>)
									</h4>
									<a href="{{ URL::to('myaccount/programs') }}" class="pull-left xs-text bold m-b-10 btn btn-sm flat-it green2-background white-link hoverable">
										View&nbsp;<i class="fa fa-caret-right"></i>
									</a>
								</div>
							</div>
							<!-- End Programs Item -->
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