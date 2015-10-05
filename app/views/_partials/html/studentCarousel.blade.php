<ul class="owlslideholder basicowlcarousel">
	@foreach($students as $student)
		<li class="slide_item">
			<ul class="student_item hide-overflow radius p-h-0 p-b-40">
				<li><img src="{{ URL::to($student->image) }}" class="fullwidth"></li>
				<li class="blue-background p-v-20 p-h-20 bold uppercase xs-text white-text">
					{{ $student->title }}
				</li>
			</ul>
		</li>
	@endforeach
</ul>