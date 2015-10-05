var teamJS = new function(){

	function loadTeamGallery(){
	    // Init inline Fancybox
	  $(".teamBioBtn").fancybox({ 
	  	overlay : { locked : false },
	  	autoDimensions:false,
	  	width:560
	  });
	}
	/*
		Init function for page JS
	*/
	this.init = function(){
		loadTeamGallery();		
	};
};