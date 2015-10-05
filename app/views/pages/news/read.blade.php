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
						<ul class="news-item p-v-40">

							<li class="title">
								<a href="{{ URL::to('news/'.$item->slug) }}"><h4 class="l-text capitalize">{{ $item->title }}</h4></a>
							</li>
							<li class="title">
								<ul class="list-inline m-b-0 m-t-0">
									<li class="text-center bold" title="Tweet this">
										<div class="spanned_element m-v-5">&nbsp;</div>
										<span style="display:inline-block;"
											class="gray-text s-text uppercase bold">
											SHARE
										</span>
									</li>
									<li class="text-center bold" title="Tweet this">
										<div class="spanned_element m-v-5">{{ $item->twitterCount }}</div>
										<a href="https://twitter.com/intent/tweet?original_referer={{ urlencode(Request::url()) }}&text={{ urlencode($item->title)}}&tw_p=tweetbutton&url={{ urlencode(Request::url()) }}"
										style="display:inline-block;"
										class="twitter-background s-text hoverable radius no-text-decoration w-35 h-35 lh-35 text-center" target="_blank">
											<i class="fa fa-twitter white-text"></i>
										</a>
									</li>
									<li class="text-center bold" title="Share on Facebook">
										<div class="spanned_element m-v-5">{{ $item->facebookCount }}</div>
										<a href="http://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::url()) }}" 
										style="display:inline-block;"
										class="facebook-background s-text hoverable radius no-text-decoration w-35 h-35 lh-35 text-center" target="_blank">
											<i class="fa fa-facebook white-text"></i>
										</a>
									</li>
									<li class="text-center bold" title="Share on Google Plus">
										<div class="spanned_element m-v-5">{{ $item->plusCount }}</div>
										<a href="https://plus.google.com/share?url={{ urlencode(Request::url()) }}" 
										style="display:inline-block;"
										class="red-background s-text radius hoverable no-text-decoration w-35 h-35 lh-35 text-center" target="_blank">
											<i class="fa fa-google-plus white-text"></i>
										</a>
									</li>
									<li class="text-center bold" title="Email a friend">
										<div class="spanned_element m-v-5">&nbsp;</div>
										<a href="mailto:?subject={{ $item->title }}&body={{ $item->caption.'... Read more at '.Request::url() }}"
										style="display:inline-block;"
										class="green-background s-text radius hoverable no-text-decoration w-35 h-35 lh-35 text-center" target="_blank">
											<i class="fa fa-envelope-o white-text"></i>
										</a>
									</li>
								</ul>
							</li>

							<li class="img">
								<img src="{{ URL::to($item->image) }}" class="radius fullwidth">
							</li>

							<li class="meta m-b-10">
								<a href="{{ URL::to('news/in/'.$item->category->slug) }}" class="xs-text bold">
									<i class="fa fa-folder"></i>&nbsp;{{ $item->category->name }}
								</a>

								<span class="pull-right">
									{{ date('d M Y', strtotime($item->created_at)) }}
								</span>
							</li>

							<li class="body">
								{{ $item->body }}
							</li>

						</ul>
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