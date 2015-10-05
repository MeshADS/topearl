<div class="header-top white-background alt">
	<div class="container p-h-10">
		<div class="row">
			<div class="col-md-12">
				<ul class="top-item pull-left">
					@if(isset($sitedata["Footer"]["socials"]))
					<?php $header_socials = explode(",", $sitedata["Footer"]["socials"]["body_notags"]);?>						
						@foreach($header_socials as $hs)
							<?php $hs = trim($hs);  ?>
							<?php $hs = explode(" ", $hs); ?>
							<li class="m-r-5">
								<a href="{{ strtolower($hs[1]) }}" target="_blank" class="{{ strtolower($hs[0]) }}-background hoverable radius text-center wh-30 lh-30 pull-left">
									<i class="white-text fa fa-{{ strtolower($hs[0]) }} fa-2"></i>
								</a>
							</li>
						@endforeach
					@endif
				</ul>
				<ul class="top-item pull-right">
					<li class="m-r-5">
						@if(!Sentry::check())
							<a href="{{ URL::to('auth/login') }}" class="link no-text-decoration green2-link uppercase xxs-text" title="Login">
								<i class="fa fa-user"></i><span class="bold visible-lg-inline visible-md-inline">&nbsp;Login</span>
							</a>
						@endif
						@if(Sentry::check())
							<a href="javascript:;" class="link no-text-decoration green2-link uppercase xxs-text" title="{{ $userdata->first_name }}">
								<img src="{{ (!is_null($userdata->avatar)) ? URL::to($userdata->avatar) : Config::get('settings.avatar') }}" width="20" height="20" class="round" alt="{{ $userdata->first_name }}">
								<span class="bold visible-lg-inline visible-md-inline">
									{{ $userdata->first_name }}
								</span>
								&nbsp;<i class="fa fa-caret-down"></i>
							</a>
							<ul class="submenu">
								<li class="">
									<a href="{{ URL::to('myaccount') }}" class="green-link white-background hoverable">My Account</a>
								</li>
								<li class="">
									<a href="{{ URL::to('myaccount/messages') }}" class="green-link white-background hoverable">
										Messages										
										{{ ($userdata->unreadMessages > 0) ? '<span class="badge xs-text bold alt white-link">'.$userdata->unreadMessages.'</span>' : '' }}
									</a>
								</li>
								<li class="alt-background alt hoverable">
									<a href="{{ URL::to('myaccount/logout') }}" class="green-link">Log Out</a>
								</li>
							</ul>
						@endif
					</li>
					<li class="m-r-5">
						<a href="{{ URL::to('about_us/calendar') }}" class="link no-text-decoration green2-link uppercase xxs-text" title="Calendar">
							<i class="fa fa-calendar"></i><span class="bold visible-lg-inline visible-md-inline">&nbsp;Calendar</span>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<!-- Begin Header Modals -->
	
<!-- End Header Modals -->