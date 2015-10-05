<div class="container-fluid">
	<div class="row">
		<div class="col-md-12 nopadding">
			<div class="menu visible-lg visible-md">
				<ul class="menu-list full">
					<?php $submenus = [] ?>
					@foreach($menus as $mn)
						<li class="menu-item {{ (count($mn->submenus) > 0) ? 'has-sub' : ''}} {{ ( trim($page->slug) == $mn->slug )? 'alt active' : '' }}  {{ $mn->color }}-background hoverable p-v-5">
							<a href="{{ (empty($mn->url)) ? '#'.$mn->slug : $mn->url }}" target="{{ ($mn->ext == 1) ? '_blank' : '_self' }}" >
								<span class="white-text">
									{{ $mn->title }}{{ (count($mn->submenus) > 0) ? '&nbsp;<i class="fa fa-caret-right"></i>' : '' }}
								</span>
							</a>
						</li>
						<?php 
							if(count($mn->submenus) > 0){
								$submenus[] = $mn;
							}
						?>
					@endforeach
				</ul>
				<!-- Put all sub menu here -->
				@foreach(@$submenus as $mn)
					<div class="sub-menu" id="{{$mn->slug}}">
						<div class="container-fluid">
							<div class="col-md-8">
								<div class="row">
									<div class="col-md-6">
										<img src="{{ (isset($menuImages[$mn->title])) ? URL::to($menuImages[$mn->title]->image) : 'http://placehold.it/480x360' }}" class="submenu-image">
									</div>
									<div class="col-md-6 xs-text">
										<p class="submenu-caption">
											{{ (isset($menuImages[$mn->title]->caption)) ? $menuImages[$mn->title]->caption : '' }}
										</p>
										@if(isset($menuImages[$mn->title]->link_url))
											<a href="{{ $menuImages[$mn->title]->link_url }}" class="link p-v-10 p-h-10 {{ ($menuImages[$mn->title]->link_type != 1) ? 'green2-background hoverable bordered' : ''}} pull-left no-text-decoration">
												<span class="{{ ($menuImages[$mn->title]->link_type != 1) ? 'white-text' : $menuImages[$mn->title]->link_color.'-text' }} bold">
													{{ (isset($menuImages[$mn->title]->link_title)) ? $menuImages[$mn->title]->link_title : '' }} &nbsp;<i class="fa fa-chevron-right"></i>
												</span>
											</a>
										@endif
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<h4 class="menu-title">{{ $mn->title }}</h4>
								<ul class="menu-list">
									@foreach($mn->submenus as $smn)
										<li class="menu-item"><a href="{{ $smn->url }}"><i class="fa fa-caret-right"></i>&nbsp;{{ $smn->title }}</a></li>
									@endforeach
								</ul>
							</div>
						</div>
						<div class="menu-background  {{  $mn->color }}-background">&nbsp;</div>
						<div class="artificial-shadow">&nbsp;</div>
					</div>
				@endforeach
			</div>
		</div>
	</div>
</div>