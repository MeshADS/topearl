<header>
	@yield("top-notification")
	<!-- Begin Search Overlay -->
		@include("_partials.html.search_overlay")
	<!-- End Search Overlay -->
	
	<!-- Top Of header -->
	@include("_partials.html.header_top")
	<!-- Logo -->
	<ul class="logo-container m-v-30">
		<li class="logo">
			<a href="{{ URL::to('') }}" class="visible-lg-inline visible-md-inline"><img src="{{ URL::to($basicdata->logo) }}"></a>
			<a href="{{ URL::to('') }}" class="visible-sm-inline visible-xs-inline"><img src="{{ URL::to($basicdata->logo_sm) }}" class="sm"></a>
		</li>
	</ul>
	<!-- Large Screen Menu -->
	@include("_partials.html.menu")
	<!-- Mobile Menu -->
	@include("_partials.html.mobilemenu")
	<!-- Page Menu -->
	@yield("pagemenu")
	<div class="colored-line">&nbsp;</div>
</header>