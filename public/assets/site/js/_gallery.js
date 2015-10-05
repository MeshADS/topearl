var galleryJS = new function(){

	function initGallery(){
	    // Init inline Fancybox
	  $(".fancyboxBtn").fancybox({ 
	  	overlay : { locked : false },
	  	autoDimensions:false,
	  	width:560
	  });
	}
	/*
		Init function for page JS
	*/
	this.init = function(){
		initGallery();		
	};
};