@extends("layout.master")
@include("_partials.html.default_meta")
@section("page") gallery @stop
@section("stylesheet")
	<!-- Stylesheet -->
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
				@foreach(array_chunk($list->photoset, 6) as $row)
					<div class="row album-list">
						@foreach($row as $col)

							<div class="col-md-2">
								<div class="album radius m-t-30">
									<a href="{{ URL::to('gallery/'.$col->id) }}">
										<img src="https://farm{{ $col->farm }}.staticflickr.com/{{ $col->server }}/{{ $col->primary }}_{{ $col->secret }}_q.jpg">
										<div class="title">
											<div class="bg gray2-background hoverable">&nbsp;</div>
											<h5 class="uppercase">{{ $col->title->_content }}</h5>
										</div>
									</a>
								</div>
							</div>

						@endforeach
					</div>
				@endforeach
				<div class="pagination-container p-v-50 text-center">
					@if($list->page > 1)
						<a href="{{ URL::to('gallery?page='.($list->page - 1)) }}" class="gray2-background hoverable m-r-20 p-v-10 p-h-10 radius bold no-text-decoration">
							<span class="white-text"><i class="fa fa-chevron-left"></i>&nbsp;Previous</span>
						</a>
					@endif
					<span class="m-r-20">
						Page {{$list->page}} of {{$list->pages}}
					</span>
					@if($list->page < $list->pages)
					<a href="{{ URL::to('gallery?page='.($list->page + 1)) }}" class="gray2-background hoverable p-v-10 p-h-10 radius bold no-text-decoration">
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
	<script type="text/javascript">
		
	</script>
@stop