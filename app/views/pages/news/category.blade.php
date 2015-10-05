@extends("layout.master")
@include("_partials.html.default_meta")
@section("page") news @stop
@section("stylesheet")
	<!-- Stylesheet -->
@stop
@section("bootdata")
	<!-- Boot data -->
@stop

@section("content")

	@include("_partials.html.news_header")

	<div class="colored-line">&nbsp;</div>
	<!-- Begin summary -->
	<div class="container-fluid nopadding alt-background">

		<div class="col-md-12 nopadding">

			<div class="container white-background p-h-30">
				<div class="row">
					<!-- Begin list -->
					<div class="col-md-8">
						<div class="content-section p-t-40 p-h-0">
							<div class="content-section-heading">
								<div class="content-section-title">
									<span class="gray-text"><i class="fa fa-folder"></i>&nbsp;{{ $category->name }}</span>
								</div>
								<div class="content-section-divider gray-background"></div>
							</div>
							<div class="content-section-body p-h-0">
								<ul class="news-list">
									@foreach($list as $item)
										<li class="news-item p-v-30">
											<ul class="news-item-data">
												<li class="title">
													<a href="{{ URL::to('news/'.$item->slug) }}"><h4 class="l-text capitalize">{{ $item->title }}</h4></a>
												</li>

												<li class="img m-b-20">
													<a href="{{ URL::to('news/'.$item->slug) }}">
														<img src="{{ URL::to($item->image) }}" class="fullwidth radius">
													</a>
												</li>

												<li class="meta m-b-10 p-b-10">

													<a href="{{ URL::to('news/in/'.$item->category->slug) }}" class="bold">
														<i class="fa fa-folder"></i> {{ $item->category->name }}
													</a>

													<span class="pull-right">{{ date('d M Y', strtotime($item->created_at)) }}</span>

												</li>

												<li class="caption">
													<p class="s-text gray-text">
														{{ $item->caption }}
													</p>
												</li>

												<li class="link">
													<a href="{{ URL::to('news/'.$item->slug) }}" class="btn btn-primary btn-sm">
														Read&nbsp;<i class="fa fa-chevron-right"></i>
													</a>
												</li>


											</ul>
										</li>
									@endforeach
								</ul>
								<div class="pagination-container p-v-50 text-center">
									{{ $list->appends(Request::except('page'))->links() }}
								</div>
							</div>
						</div>
					</div>
					<!-- End list -->
					<!-- Begin right pane -->
					@include("_partials.html.news_right_pane")
					<!-- End right pane -->
				</div>
			</div>

		</div>
		

	</div>
	<!-- End summary -->

@stop

@section("javascript")
	<!-- Javascript -->
	{{ HTML::script('assets/site/plugins/jquery.highlight/jquery.highlight-5.js') }}
	<script type="text/javascript">
		newsJs.init()
	</script>
@stop