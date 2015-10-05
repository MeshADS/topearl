<!-- Begin header -->
<div class="container-fluid nopadding parallax-window" data-parallax="scroll" data-speed="0.7" data-image-src="{{ (isset($header->image)) ? URL::to($header->image) : 'http://placehold.it/1280x640' }}">
	<div class="col-md-12 nopadding">
		@if(isset($header))
			<div class="bg-olay light black-background">&nbsp;</div>
		@endif
		<div class="container">
			<div class="col-md-12">
				<div class="content-section p-v-80">
					<div class="content-section-heading">
						<div class="content-section-title white-text">
							Gallery
						</div>
						@if(!empty($header->caption))
							<div class="content-section-caption white-text">
								{{ $header->caption }}
							</div>
						@endif
						<div class="content-section-divider white-background">&nbsp;</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End header -->