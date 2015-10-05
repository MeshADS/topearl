<div class="col-md-4 right-pane">
	<div class="container-fluid nopadding p-t-40">
		<!-- Bein search form -->
		<div class="col-md-12 nopadding sect">
			<div class="content-section m-b-0">
				<div class="content-section-heading">
					<div class="content-section-title">
						<span class="xs-text">Search</span>
					</div>
					<div class="content-section-divider gray-background"></div>
				</div>
				<div class="content-section-body m-b-0">
					<?php $search_text = Input::get("search", ""); ?>
					{{ Form::open(["url"=>"news", "method"=>"get"]) }}
						<div class="form-group m-b-0">
							{{ Form::text("search", $search_text, ["class"=>"form-control flat-it", "placeholder"=>"Search from here...", "id"=>"news-search"]) }}
						</div>
					{{ Form::close() }}
				</div>
			</div>
		</div>
		<!-- End search form -->

		<!-- Bein most recent -->
		<div class="col-md-12 nopadding sect">
			<div class="content-section">
				<div class="content-section-heading">
					<div class="content-section-title">
						<span class="xs-text">Most Recent</span>
					</div>
					<div class="content-section-divider gray-background"></div>
				</div>
				<div class="content-section-body">
					<ul class="latestnews_list">
						@foreach($latestnews as $ln)
							<li class="latestnews_item p-t-30 p-v-10">
								<a href="{{ URL::to('news/'.$ln->slug) }}">
									<h4>
										<img src="{{ URL::to($ln->thumbnail) }}">
										{{ $ln->title }}
									</h4>
								</a>
								<div class="meta">
									<a href="{{ URL::to('news/in/'.$ln->category->slug) }}" class="bold xs-text">
										<i class="fa fa-folder"></i>&nbsp;{{ $ln->category->name }}
									</a>
									<span class="pull-right">{{ date('d M Y', strtotime($ln->created_at)) }}</span>
								</div>
							</li>
						@endforeach
					</ul>
				</div>
			</div>
		</div>
		<!-- End most recent -->

		<!-- Begin categories -->
		<div class="col-md-12 nopadding sect">
			<div class="content-section">
				<div class="content-section-heading">
					<div class="content-section-title">
						<span class="xs-text">Categories</span>
					</div>
					<div class="content-section-divider gray-background"></div>
				</div>
				<div class="content-section-body">
					<ul class="categories_list">
						<?php $i = 1; ?>
						@foreach($pane_categories as $pn_c)
							<li class="categories_item p-v-10">
								<a href="{{ URL::to('news/in/'.$pn_c->slug) }}">
									<h6 class="bold">
										{{ $pn_c->name }}
										<span class="pull-right">({{ count($pn_c->posts) }})</span>
									</h6>
								</a>
							</li>
							<?php $i++; ?>
							<?php if($i > 5) break; ?>
						@endforeach
						@if(count($pane_categories) > 5)
							<li class="categories_item p-v-10 text-center">
								<a href="{{ URL::to('news/categories') }}" class="bold xs-text uppercase m-t-10">More&nbsp;<i class="fa fa-chevron-down"></i></a>
							</li>
						@endif
					</ul>
				</div>
			</div>
		</div>
		<!-- End categories -->

		<!-- Begin nl form -->
		<div class="col-md-12 nopadding">
			<div class="content-section">
				<div class="content-section-heading">
					<div class="content-section-title">
						<span class="xs-text">Newsletter Sign Up</span>
					</div>
					<div class="content-section-divider gray-background"></div>
				</div>
				<div class="content-section-body">
					<p class="xs-text">
						Fill in the form fields below to signu up for our newsletter updates.
					</p>
					{{ Form::open(["url"=>"news", "method"=>"get"]) }}
						<div class="form-group">
							{{ Form::text("name", "", ["class"=>"form-control radius2 input-lg flat-it", "placeholder"=>"Your name..."]) }}
						</div>
						<div class="form-group">
							{{ Form::email("email", "", ["class"=>"form-control radius2 input-lg flat-it", "placeholder"=>"Your email..."]) }}
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary btn-lg flat-it btn-block">Submit</button>
						</div>
					{{ Form::close() }}
				</div>
			</div>
		</div>
		<!-- End nl form -->

	</div>
</div>