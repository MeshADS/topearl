var studentJS = new function(){

	function studentSlider(){
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
		studentSlider();		
	};
};