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
					<div class="col-md-8 nopadding">
						@foreach( array_chunk($list->all(), 3 ) as $row )
							<div class="container-fluid nopadding categories-row">
								@foreach($row as $col)
									<div class="col-md-4 m-v-30">
										<ul class="categories-item">
											<li>
												<a href="{{ URL::to('news/in/'.$col->slug) }}" class="no-text-decoration">
													@if(isset($col->post['thumbnail']))
														<img src="{{ URL::to($col->post['thumbnail']) }}" class="fullwidth radius">
													@else
														<h4 class="uppercase green2-background p-v-40 m-v-0 text-center xl-text white-text radius">
															{{ substr($col->name, 0, 1) }}
														</h4>
													@endif
												</a>
											</li>
											<li>
												<a href="{{ URL::to('news/in/'.$col->slug) }}">
													<h4 class="m-v-5">
														{{ $col->name }}
													</h4>
												</a>
											</li>
											<li>
												<h6 class="grey-text bold xs-text m-v-5">
													{{ count($col->posts) }} Post(s)
												</h6>
											</li>
										</ul>
									</div>
								@endforeach
							</div>
						@endforeach

						<div class="pagination-container text-center">
							{{ $list->appends(Request::except("page"))->links() }}
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
	<script type="text/javascript">
		
	</script>
@stop