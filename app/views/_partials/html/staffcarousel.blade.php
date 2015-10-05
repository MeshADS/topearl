<ul class="owlslideholder basicowlcarousel">
	@foreach($staff as $st)
		<li class="slide_item">
			<ul class="staff_slider_item">
				<li>
					<img src="{{ URL::to($st->thumbnail) }}" class="fullwidth">
				</li>
				<li class="green-background p-h-20 m-v-0">
					<h4 class="p-v-20 white-text m-text capitalize">
						{{ $st->name }}
					</h4>
				</li>
				<li  class="green-background p-h-20 m-v-0 alt">
					<h4 class="white-text xs-text uppercase">
						{{ $st->office }}
					</h4>
				</li>
			</ul>
		</li>
	@endforeach
</ul>
<div class="text-center m-v-30 spanned_element">
	<a href="{{ URL::to('about_us/management') }}" class="no-text-decoration p-v-10 p-h-10 green-background hoverable"><span class="white-text">View More</span></a>
</div>