<ul class="owlslideholder basicowlcarousel">
	@foreach($latestposts as $latestpost)
		<li class="slide-item p-b-30">
			<h6 class="s-text uppercase visble-sm visible-xs">
				<a href="{{ URL::to('news/'.$latestpost->slug) }}" class="green-link">
					{{ $latestpost->title }}
				</a>
			</h6>
			<h6 class="s-text uppercase visible-lg visible-md h-50">
				<a href="{{ URL::to('news/'.$latestpost->slug) }}" class="green-link">
					{{ $latestpost->title }}
				</a>
			</h6>
			<img src="{{ URL::to($latestpost->thumbnail) }}" class="fullwidth m-b-10">
			<p class="xs-text gray-text">
				{{ $latestpost->caption }}...
			</p>
			<a href="{{ URL::to('news/'.$latestpost->slug) }}" class="bold orange-text s-text pull-right m-v-5 m-r-10">
				Read More <i class="fa fa-caret-right"></i>
			</a>
		</li>
	@endforeach
</ul>
<div class="text-center m-v-30 spanned_element">
	<a href="{{ URL::to('news') }}" class="no-text-decoration p-v-10 p-h-10 green-background hoverable"><span class="white-text">View More</span></a>
</div>