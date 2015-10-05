<!-- Begin header -->
<div class="container-fluid nopadding parallax-window" style="background-image:url({{ URL::to(@$header->image) }});">
	@if(isset($header))
		<div class="bg-olay light black-background">&nbsp;</div>
	@endif
	<div class="col-md-12">
		<div class="container">
			<div class="col-md-12">
				<div class="content-section p-v-100">
					<div class="content-section-heading">
						<div class="content-section-title white-text" data-sr="enter bottom, move 20px, reset, opacity 0">
						@if(isset($header->title))
							{{ $header->title }}
						@endif
						</div>
						@if(!empty($header->caption))
							<div class="content-section-caption white-text" data-sr="enter bottom, move 20px, reset, opacity 0">
								{{ $header->caption }}
							</div>
						@endif
						<div class="content-section-divider white-background" data-sr="enter left, move 50px, reset, opacity 0.3">&nbsp;</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End header -->