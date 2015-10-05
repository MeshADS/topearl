<ul class="owlslideholder calendarcarousel basicowlcarousel">
	@foreach($events as $event)
		<li class="slide_item">
			<div class="content-section p-h-20 p-v-20 green2-background">

				<span class="font-bg xl-text light white-text bold t-20 r-20"><i class="fa fa-calendar"></i></span>

				<div class="content-section-heading">
					<div class="content-section-title white-text">
						{{ date('d M', strtotime($event->schedule_starts)) }}<br>
						{{ date('Y', strtotime($event->schedule_starts)) }}
					</div>
					<div class="content-section-divider white-background">&nbsp;</div>
				</div>

				<div class="content-section-body p-v-10">
					<!-- <a href="{{ URL::to('our_calendar/'.$event->category->slug) }}" class="white-text uppercase radius xs-text m-v-10 no-text-decoration calendar_item_category">
						{{ $event->category->name }}
					</a> -->
					<h4 class="white-text capitalize s-text m-v-0 h-60">{{$event->title}}</h4>
				</div>

			</div>
		</li>
	@endforeach
</ul>
<div class="text-center m-v-30 spanned_element">
	<a href="{{ URL::to('about_us/calendar') }}" class="no-text-decoration p-v-10 p-h-10 green2-background hoverable"><span class="white-text">View More</span></a>
</div>