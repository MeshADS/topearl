<div class="searchOverlay">
	<!-- Close button -->
	<a href="#" class="close_btn" id="close_btn"><i class="fa fa-times"></i></a>
	<!-- Overlay background -->
	<div class="bg-olay black-background">&nbsp;</div>
	<!-- Container (Centralized view) -->
	<div class="container">
		<!-- Content container -->
		<div class="row">
			<div class="searchOverlay-content">
				<!-- Contains search form -->
				<div class="form-holder">
					<form action="javascript:;", method="get", class="form">
						{{ Form::text("search", "", ["id"=>"search-field", "placeholder"=>"Enter search phrase here..."]) }}
					</form>
				</div>

				<div class="col-md-12">
					<!-- Searching -->
					<div class="searching">
						<span>Searching...</span>
					</div>
					<!-- Contains search results -->
					<div class="search-results">
						<!-- Search results list -->
						<ul class="results-list">
							<!-- Results Go here -->
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Search result meta -->
	<div class="result-meta">
		<div class="bg-olay dark black-background">&nbsp;</div>
		<ul>
			<li class="previous_page">
				<a href="#" class="no-text-decoration changePage" id="btn" data-search-start="" data-search-term="">
					<span class="white-text bold"> <i class="fa fa-chevron-left"></i> Previous Page</span>
				</a>
			</li>
			<li class="next_page">
				<a href="#" class="no-text-decoration changePage" id="btn" data-search-start="" data-search-term="">
					<span class="white-text bold">Next Page <i class="fa fa-chevron-right"></i></span>
				</a>
			</li>
		</ul>
	</div>
</div>