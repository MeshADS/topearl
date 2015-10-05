
<div class="col-xs-12 nopadding">
	<div class="spanned_element gray2-background">
		<div class="dropdown visible-sm visible-xs p-h-10 p-v-15">
		  <a id="dLabel" class="white-link bold no-text-decoration" data-target="#" href="http://example.com" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
		    My Account
		    <span class="caret"></span>
		  </a>

		  <ul class="dropdown-menu" aria-labelledby="dLabel">
		   	<li class="white-background hoverable {{ ( $current_menu == 1 ) ? ' alt ' : '' }}">
				<a href="{{ URL::to('myaccount') }}">
					Overview					
				</a>
			</li>
			<li class="white-background hoverable {{ ( $current_menu == 2 ) ? ' alt ' : '' }}">
				<a href="{{ URL::to('myaccount/programs') }}">
					Programs					
				</a>
			</li>
			<li class="white-background hoverable {{ ( $current_menu == 3 ) ? ' alt ' : '' }}">
				<a href="{{ URL::to('myaccount/messages') }}">
					Messages		
					{{ ($userdata->unreadMessages > 0) ? '<span class="badge xs-text bold alt white-link red-background">'.$userdata->unreadMessages.'</span>' : '' }}			
				</a>
			</li>
			<li class="white-background hoverable {{ ( $current_menu == 4 ) ? ' alt ' : '' }}">
				<a href="{{ URL::to('myaccount/awards') }}">
					Awards					
				</a>
			</li>
			<li class="white-background hoverable {{ ( $current_menu == 5 ) ? ' alt ' : '' }}">
				<a href="{{ URL::to('myaccount/results') }}">
					Results					
				</a>
			</li>
			<li class="white-background hoverable {{ ( $current_menu == 6 ) ? ' alt ' : '' }}">
				<a href="{{ URL::to('myaccount/settings') }}">
					Settings					
				</a>
			</li>
			<li class="white-background hoverable {{ ( $current_menu == 7 ) ? ' alt ' : '' }}">
				<a href="{{ URL::to('myaccount/contact-list') }}">
					Contact List					
				</a>
			</li>
			<li class="white-background hoverable">
				<a href="{{ URL::to('myaccount/logout') }}">
					Log out					
				</a>
			</li>
		  </ul>
		</div>		
	</div>	
</div>
<div class="col-md-3">
	<h4 class="uppercase bold p-h-10 m-t-40 visible-lg visible-md">My Account</h4>
	<ul class="list-unstyled my_account_menu visible-lg visible-md">
		<li class="white-background hoverable {{ ( $current_menu == 1 ) ? ' alt ' : '' }}">
			<a href="{{ URL::to('myaccount') }}">
				Overview					
			</a>
		</li>
		<li class="white-background hoverable {{ ( $current_menu == 2 ) ? ' alt ' : '' }}">
			<a href="{{ URL::to('myaccount/programs') }}">
				Programs					
			</a>
		</li>
		<li class="white-background hoverable {{ ( $current_menu == 3 ) ? ' alt ' : '' }}">
			<a href="{{ URL::to('myaccount/messages') }}">
				Messages		
				{{ ($userdata->unreadMessages > 0) ? '<span class="badge xs-text bold alt white-link red-background">'.$userdata->unreadMessages.'</span>' : '' }}			
			</a>
		</li>
		<li class="white-background hoverable {{ ( $current_menu == 4 ) ? ' alt ' : '' }}">
			<a href="{{ URL::to('myaccount/awards') }}">
				Awards					
			</a>
		</li>
		<li class="white-background hoverable {{ ( $current_menu == 5 ) ? ' alt ' : '' }}">
			<a href="{{ URL::to('myaccount/results') }}">
				Results					
			</a>
		</li>
		<li class="white-background hoverable {{ ( $current_menu == 6 ) ? ' alt ' : '' }}">
			<a href="{{ URL::to('myaccount/settings') }}">
				Settings					
			</a>
		</li>
		<li class="white-background hoverable {{ ( $current_menu == 7 ) ? ' alt ' : '' }}">
			<a href="{{ URL::to('myaccount/contact-list') }}">
				Contact List					
			</a>
		</li>
		<li class="white-background hoverable">
			<a href="{{ URL::to('myaccount/logout') }}">
				Log out					
			</a>
		</li>
	</ul>
</div>