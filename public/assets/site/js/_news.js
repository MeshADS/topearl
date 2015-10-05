var newsJs = new function(){

	function highlightText(){
	 	var searchQuery = getUrlVars()['search'];
	 	$('.news-list').highlight(searchQuery);
	}

	function reHighlight () {
		$("#news-search").keyup(function(){
			var string = $(this).val();
			$('.news-list').removeHighlight();
			$('.news-list').highlight(string);
		});
	}

	/*
		Init function for page JS
	*/
	this.init = function(){
		highlightText();
		reHighlight();
	};
};