<!-- Begin fancybox inline content -->
	@foreach($students as $s_m)
		<div style="display:none;" rel="group">
			{{-- Content --}}
			<div id="studentModal{{$s_m->id}}">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							{{-- Name --}}
							<section>
								<h4 class="m-text red-text uppercase bold">{{ $s_m->title }}</h4>
							</section>
							{{-- Info --}}
							<section>
								<p class="s-text">
									<img src="{{ URL::to($s_m->image) }}" class="p-b-10 p-r-10 visible-lg visible-md" style="width:256px; float: left;">
									{{ $s_m->caption }}
								</p>
							</section>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endforeach
<!-- End fancybox inline content -->

<ul class="owlslideholder basicowlcarousel">
	@foreach($students as $student)
		<li class="slide_item">
			<ul class="student_item hide-overflow radius p-h-0 p-b-40">
				<li><img src="{{ URL::to($student->image) }}" class="fullwidth"></li>
				<li class="blue-background p-v-20 p-h-20 bold uppercase xs-text white-text">
					{{ $student->title }}
				</li>
				<li class="blue-background p-v-20 p-h-20 uppercase xs-text white-text">
					<a href="#studentModal{{$student->id}}" rel="group" class="no-text-decoration fancyboxBtn">
						<span class="p-v-10 p-h-15 orange-background bordered white-text radius uppercase xs-text">More Info</span>
					</a>
				</li>
			</ul>
		</li>
	@endforeach
</ul>