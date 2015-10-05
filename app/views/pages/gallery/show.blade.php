@extends("layout.master")
@include("_partials.html.default_meta")
@section("page") gallery @stop
@section("stylesheet")
	<!-- Add fancybox -->
	{{ HTML::style("assets/site/plugins/fancybox/source/jquery.fancybox.css") }}
	<!-- Optionally add helpers - button, thumbnail and/or media -->
	{{ HTML::style("assets/site/plugins/fancybox/source/helpers/jquery.fancybox-buttons.css") }}
	{{ HTML::style("assets/site/plugins/fancybox/source/helpers/jquery.fancybox-thumbs.css") }}
@stop
@section("bootdata")
	<!-- Boot data -->
@stop

@section("content")

	@include("_partials.html.gallery_header")

	<div class="colored-line">&nbsp;</div>
	<!-- Begin summary -->
	<div class="container-fluid nopadding alt-background">

		<div class="col-md-12 nopadding">

			<div class="container p-h-30 white-background">
				<!-- Begin list -->
				@foreach(array_chunk($album->photo, 6) as $row)
					<div class="row album-list">
						@foreach($row as $col)

							<div class="col-md-2">
								<div class="album radius m-t-30">
									<a href="https://farm{{ $col->farm }}.staticflickr.com/{{ $col->server }}/{{ $col->id }}_{{ $col->secret }}_b.jpg" class="fancyboxBtn" rel="group" title="{{ str_replace('-', ' ', $col->title) }}">
										<img src="https://farm{{ $col->farm }}.staticflickr.com/{{ $col->server }}/{{ $col->id }}_{{ $col->secret }}_n.jpg">
									</a>
								</div>
							</div>

						@endforeach
					</div>
				@endforeach
				<div class="pagination-container p-v-50 text-center">
					@if($album->page > 1)
						<a href="{{ Request::url().'?page='.($album->page - 1) }}" class="green2-background hoverable m-r-20 p-v-10 p-h-10 radius bold no-text-decoration">
							<span class="white-text"><i class="fa fa-chevron-left"></i>&nbsp;Previous</span>
						</a>
					@endif
					<span class="m-r-20">
						Page {{$album->page}} of {{$album->pages}}
					</span>
					@if($album->page < $album->pages)
					<a href="{{ Request::url().'?page='.($album->page + 1) }}" class="green2-background hoverable p-v-10 p-h-10 radius bold no-text-decoration">
						<span class="white-text">Next&nbsp;<i class="fa fa-chevron-right"></i></span>
					</a>
					@endif
				</div>
				<!-- End list -->
			</div>

		</div>
		

	</div>
	<!-- End summary -->

@stop

@section("javascript")
	<!-- Javascript -->
	{{ HTML::script('assets/site/plugins/fancybox/lib/jquery.mousewheel-3.0.6.pack.js') }}
	{{ HTML::script('assets/site/plugins/fancybox/source/jquery.fancybox.pack.js') }}
	{{ HTML::script('assets/site/plugins/fancybox/source/helpers/jquery.fancybox-buttons.js') }}
	<!-- Add mousewheel plugin (this is optional) -->
	{{ HTML::script('assets/site/plugins/fancybox/source/helpers/jquery.fancybox-media.js') }}
	{{ HTML::script('assets/site/plugins/fancybox/source/helpers/jquery.fancybox-thumbs.js') }}
	<script type="text/javascript">
		$(function(){
			galleryJS.init();
		});
	</script>
@stop