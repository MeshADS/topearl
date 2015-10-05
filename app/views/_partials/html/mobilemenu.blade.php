<!-- Begin toggler -->
<div class="mobilemenu-toggler-container visible-sm visible-xs">
	<a href="#" class="toggler" id="mobile-menu-toggler">
		<span>&nbsp;</span>
	</a>
</div>
<div class="mobile-menu visible-sm visible-xs">
	<!-- Begin menu list -->
	<ul class="menu-list" id="mobile-menu-list">
		<!-- Colored line -->
		<div class="colored-line">&nbsp;</div>
		@foreach($menus as $mmn)
			<li class="menu-item {{ (count($mmn->submenus) > 0) ? 'has-sub' : '' }} {{ ( trim($page) == $mmn->slug )? 'active' : '' }}">
				<a href="javascript:;">{{ $mmn->title }}{{ (count($mmn->submenus) > 0) ? '&nbsp;<i class="fa fa-caret-right"></i>' : ''}}</a>
				@if(count($mmn->submenus) > 0)
					<div class="sub-menu">
						<ul class="menu-list">
						@foreach($mmn->submenus as $smn)
							<li class="menu-item"><a href="{{ $smn->url }}">{{ $smn->title }}</a></li>
						@endforeach
						</ul>
					</div>
				@endif
			</li>
		@endforeach
	</ul>
</div>