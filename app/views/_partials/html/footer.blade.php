<footer>
	<div class="colored-line thicker">&nbsp;</div>
	<div class="pre-footer">
		<div class="container">
			<div class="container-fluid">
				<!-- <div class="col-md-3 m-v-50 footer-image">
				<h4 class="title uppercase xs-text white-text p-b-10 bold m-t-0">Partners</h4>
					<div class="row">
						
					</div>
				</div> -->
				<div class="col-md-4 m-v-50 useful-links">
					<h4 class="uppercase xs-text white-text p-b-10 bold m-v-0">Useful Links</h4>
					<ul class="m-b-50">
						@if(isset($sitedata["footer"]["menu"]))
							<?php $footer_menu = explode(";", $sitedata["footer"]["menu"]["body_notags"]);?>
							@foreach($footer_menu as $fmn)
								<?php $fmn_items = explode(',', $fmn) ?>
								@if(count($fmn_items) == 2)
									<li><a href="{{ trim($fmn_items[0]) }}"><i class="fa fa-angle-right"></i>&nbsp;{{ (isset($fmn_items[1]) ? trim($fmn_items[1]) : 'N/A') }}</a></li>
								@endif
							@endforeach
						@endif
					</ul>
				</div>
				<div class="col-md-4 m-v-50 tweet-feed">
					<h4 class="title uppercase xs-text white-text p-b-10 bold m-v-0">Tweet feeds</h4>
					<ul class="m-b-30">
						<!-- Tweets go here -->
					</ul>
				</div>
				<div class="col-md-4 m-v-50 gallery">
					<h4 class="title uppercase xs-text white-text p-b-10 bold m-t-0 m-b-10">Gallery</h4>
					<div class="container-fluid nopadding">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="copynsocial">
		<div class="row">
			<div class="col-md-6 copy p-v-20">
				<span>&copy; {{ date("Y") }}</span> <strong>{{ $basicdata->fullname }}.</strong> <span>All rights reserved.</span>
			</div>
			@if(isset($sitedata["Footer"]["socials"]))
			<?php $footer_socials = explode(",", $sitedata["Footer"]["socials"]["body_notags"]);?>
				<div class="col-md-6 social">
					<ul class="social-list text-right p-v-20">
						@foreach($footer_socials as $fs)
							<?php $fs = trim($fs);  ?>
							<?php $fs = explode(" ", $fs); ?>
							<li><a href="{{ strtolower(trim($fs[1])) }}" target="_blank" class="{{ strtolower(trim($fs[0])) }}-background hoverable radius"><i class="fa fa-{{ strtolower($fs[0]) }} fa-2"></i></a></li>
						@endforeach
					</ul>
				</div>
			@endif
		</div>
	</div>
	<div class="credit">
		<span>By:</span> <a href="javscript:;">Mesh Ads</a>
	</div>
</footer>