(function(){
	var defaultsJs = {	

		googleSearchReq: null,	
		/**
		 * Sticks header to top of window
		*/
		stickynav: function(){
			var scrollsize = $(window).scrollTop(),
				headerheight = $("header").outerHeight();
				// Validate scroll size
				if (scrollsize > headerheight) {
					// Check for "sticky" class on header
					if ( ! $("header").hasClass("sticky") ) {
						// Add sticky class to header
						$("header").addClass("sticky");
					};
				}
				else{
					// Check for "sticky" class on header
					if ( $("header").hasClass("sticky") ) {
						// Remove sticky class
						$("header").removeClass("sticky");
					};
				}
		},

		/**
		 * Functions associated with the mobile menu
		*/
		mobilemenu: function(){
			// Add click event listener to mobile menu toggler
			$("#mobile-menu-toggler").click(function(event){
				// Variables
				var mobileMenu = $("#mobile-menu-list"),
					submenus = $(".mobile-menu .has-sub .sub-menu");
					submenusToggler = $(".mobile-menu .has-sub a");
				// Validate mobile menu visibility
				if( $("#mobile-menu-list").is(":visible") ){
					// Hide mobile menu
					mobileMenu.slideUp(250);
					// Hide submenus
					submenus.slideUp(250);
					// Remove "open" class from sub-menu toggler
					submenusToggler.removeClass("open");
				}
				else{
					// Show mobile menu
					mobileMenu.slideDown(250);
				}
				// Cancel default event 
				event.preventDefault();
			});
			// Toggle submenu
			var mobileMenuItems = $(".mobile-menu .menu-list .has-sub a");
			// Add click event to mobile menu items
			mobileMenuItems.click(function(event){
				// Validate for open class
				if( $(this).hasClass("open") ){
					// Remove open class
					$(this).removeClass("open");
				}
				else{
					// Add open class
					$(this).addClass("open");
				}
				// Variables
				var selfSub = $(this).parent(".has-sub"),
					submenu = selfSub.children(".sub-menu");
				// Validate if the mobile menu has sub-menu
				if (submenu.hasClass("sub-menu")) {
					// Validate sub-menu visiblity
					if(submenu.is(":visible")){
						// Hide sub-menu
						submenu.slideUp(250);
					}
					else{
						// Show sub-menu
						submenu.slideDown(250);
					}
					// Prevent events default action
					event.preventDefault();
				}
				else{
					// Do nothing
				}
			});
		},

		/**
		 * Functions associated with the large menu
		*/
		largmenu: function(){
			// Toggle submenu
			var mobileMenuItems = $(".menu .menu-list .has-sub a");
			// Add click event to large menu items
			mobileMenuItems.click(function(event){
				var self = $(this);
				// Validate for open class
				if( self.hasClass("open") ){
					// Remove open class
					self.removeClass("open");
				}
				else{
					// Add open class
					self.addClass("open");
				}
				// Variables
				var LselfSub = self.parent(".has-sub"),
					Lsubmenu = $(this).attr("href"),
					Lsubmenu = $("header .menu "+Lsubmenu);
				// Validate if the large menu has sub-menu
				if (Lsubmenu.hasClass("sub-menu")) {
					// Validate sub-menu visiblity
					if(Lsubmenu.is(":visible")){
						// Hide sub-menu
						Lsubmenu.slideUp(250);
					}
					else{
						// Show sub-menu
						Lsubmenu.slideDown(250, function(){
							// Listen for windwo click once
							$(window).one("click", function(){
								// Hide sub menu
								Lsubmenu.slideUp(250);
								// Remove open class
								self.removeClass("open");
							});
						});
					}
					// Prevent events default action
					event.preventDefault();
				}
				else{
					// Do nothing
				}
			});
		},

		/**
		 * Inits search overlay
		*/
		searchOverlayTrigger: function(){

			$("#searchOverlayTrigger").click(function(event){

				var searchOverlay = $("header .searchOverlay");
				var searchOverlayOlay = $("header .searchOverlay .bg-olay");

				if (searchOverlay.is(":visible")) {
					$("body").removeClass("hide-overflow");
					searchOverlay.fadeOut("fast");
					searchOverlayOlay.off("click");
				}
				else{
					$("body").addClass("hide-overflow");
					searchOverlay.fadeIn("fast", function(){

						$("header .searchOverlay .form #search-field").focus();
					});
				}

				searchOverlayOlay.one("click", function(event){

					$("body").removeClass("hide-overflow");
					searchOverlay.fadeOut("fast");
					searchOverlayOlay.off("click");

					event.preventDefault();

				});

				$("header .searchOverlay #close_btn").one("click", function(event){

					$("body").removeClass("hide-overflow");
					searchOverlay.fadeOut("fast");
					searchOverlayOlay.off("click");
					$("header .searchOverlay #close_btn").off("click");

					event.preventDefault();
				});

				event.preventDefault();

			});


			$("header .searchOverlay .form #search-field").keyup(function(){

				var q = $(this).val();

				waitForFinalEvent(function(){
					if (q.length > 0) {
						defaultsJs.googleSearch(q);
					};
				}, 1000, "googlesearchevent");

			});

			defaultsJs.pageChange();

		},

		pageChange: function(){

				$("header .searchOverlay .result-meta .changePage").on("click", function(event){

					var start = $(this).attr("data-search-start"),
						q = $(this).attr("data-search-term");

					if (typeof start != undefined) {

						$("header .searchOverlay .form #search-field").attr("disabled", true);

						defaultsJs.googleSearch(q, start);
						
					};


					event.preventDefault();
					
				});
		},

		googleSearch: function(q, start){

			if (!start) start = 1;

			// Abort on going request
			if (defaultsJs.googleSearchReq != null) {
				if (defaultsJs.googleSearchReq.readyState != 4) { defaultsJs.googleSearchReq.stop(); };
			};

			var key = "AIzaSyCSZefcJrauV3qhV_KL0rAhP20ZhkJsbxQ",
				cx ="016453095510499713017:ixtncl58cgy", 
				$srTpl, $list, $item;

			$("header .searchOverlay .searching").fadeIn(1, function(){

				// Clear result list content
				$("header .searchOverlay .search-results .results-list").html("");

				defaultsJs.googleSearchReq = $.ajax({
										type:"GET",
										url:"https://www.googleapis.com/customsearch/v1?key="+key+"&cx="+cx+"&q="+q+"&start="+start+""
										}).success(function(data){
											// Hide loader
											$("header .searchOverlay .searching").fadeOut(1, function(){
												// Validate result items
												if (data.items) {
													// Get returned item
													$list = data.items;
													// Loop through the list of items
													for (var i = $list.length - 1; i >= 0; i--) {
														// Get item
														var $item = $list[i];
														// Create item template
														$srTpl = 	'<li class="result-item">'+
																	'<a href="'+$item.link+'"><h4 class="title">'+$item.htmlTitle+'</h4></a>'+
																	'<div class="snippet">'+$item.htmlSnippet+'</div>'+
																 	'</li>';
														// Prepend to ul container
														$($srTpl).prependTo("header .searchOverlay .search-results .results-list");
													};
												}
												else{
													// Create not found template
													$srTpl = '<li class="notfound"> --No Result Found-- </li>';
													// Render as html on view in list container
													$("header .searchOverlay .search-results .results-list").html($srTpl)
												}
												// Set next page
												if (data.queries.nextPage) {
													var nextPage = data.queries.nextPage[0];
														$('header .searchOverlay .result-meta .next_page #btn').attr("data-search-start", nextPage.startIndex);
														$('header .searchOverlay .result-meta .next_page #btn').attr("data-search-term", nextPage.searchTerms);
														$('header .searchOverlay .result-meta .next_page').show();
												}
												else{
													$('header .searchOverlay .result-meta .next_page').hide();
												}
												// Set next page
												if (data.queries.previousPage) {
													var previousPage = data.queries.previousPage[0];
														$('header .searchOverlay .result-meta .previous_page #btn').attr("data-search-start", previousPage.startIndex);
														$('header .searchOverlay .result-meta .previous_page #btn').attr("data-search-term", previousPage.searchTerms);
														$('header .searchOverlay .result-meta .previous_page').show();
												}
												else{
													$('header .searchOverlay .result-meta .previous_page').hide();
												}

											});
										}).error(function(jqXHR, textStatus, errorThrown){
											$("header .searchOverlay .searching").fadeOut(1, function(){
												// Create not found template
												$srTpl = '<li class="notfound"> --Search is currently unavailable-- </li>';
												// Render as html on view in list container
												$("header .searchOverlay .search-results .results-list").html($srTpl)
											});
										}).complete(function(){

											$("header .searchOverlay .form :input").attr("disabled", false);
										});
			});
		},
	
		/**
		 * Starts basic owl carousel
		*/
		basicowlcarousel: function(){
			// Find slider
			$(".basicowlcarousel").each(function(){
				// Init slider
				$(this).owlCarousel({
							"loop":true,
						  	"margin":10,
						  	"autoplay":true,
						  	"autoplayTimeout":7000,
						  	responsive:{
								        0:{
								            items:1
								        },
								        768:{
								            items:1
								        },
								        991:{
								            items:2
								        },
								        992:{
								            items:4
								        }
							}
				});				
			});
		},

		/**
		 * Fetches flickr
		*/
		footerFlickr: function(){

			var token = Site.Config.token,
				url = Site.Config.url, $fl_tpl, $image;

			$.ajax({
				url: url+"/api/flickr",
				method: "GET",
				headers:{ token:token }

			}).success(function(data){

					switch(data.status){

						case "error":

						break;

						default:

							for (var i = 0; i < data.data.length; i++) {
								$image = data.data[i];
								$fl_tpl = 	'<div class="col-md-3 col-sm-3 col-xs-4 m-b-10 fgi p-h-5">'+
												'<a href="'+url+'/gallery/'+$image.set+'">'+
													'<img src="https://farm'+$image.farm+'.staticflickr.com/'+$image.server+'/'+$image.id+'_'+$image.secret+'_q.jpg" class="fullwidth radius">'+
												'</a>'+
											'</div>';
								$($fl_tpl).hide().appendTo("footer .pre-footer .gallery .container-fluid");
							};
							var delay = 200;
							// Show tweets
							$("footer .pre-footer .gallery .fgi").each(function(){
								$(this).delay(delay).fadeIn("fast");
								delay = delay + 50;
							});
						break;

					};

			}).error(function(){

			}).complete(function(){

			});
		},

		/**
		 * Fetches tweet feeds
		*/
		footerTweets: function(){

			var token = Site.Config.token,
				url = Site.Config.url, $tw_tpl, $tweet;

			$.ajax({
				url: url+"/api/twitter",
				method: "GET",
				headers:{ token:token }

			}).success(function(data){

				switch(data.status){
					case "error":

					break;
					default:
						for (var i = 0;  i <= data.data.tweets.length-1; i++) {
							$tweet = data.data.tweets[i];
							$tw_tpl = '<li>'+
										'<h4 class="handle xs-text p-b-10 m-v-0">'+
										'<a href="https://twitter.com/'+$tweet.username+'" class="white-text">'+$tweet.name+'</a>'+
										'</h4>'+
										'<p class="tweet-text">'+$tweet.text+'</p>'+
										'<span class="tweet-time xs-text white-text p-b-10 m-v-0" data-livestamp="'+$tweet.time+'"></span>'+
										'</li>';
							$($tw_tpl).hide().appendTo("footer .pre-footer .tweet-feed ul");
						};
						// Init carousel
						$("footer .pre-footer .tweet-feed ul").owlCarousel({
							"loop":true,
						  	"margin":10,
						  	"autoplay":true,
						  	"autoplayTimeout":5000,
						  	responsive:{
						        0:{
						            items:1
						        },
						        992:{
						            items:1
						        }
						    }
						});
						// Show tweets
						$("footer .pre-footer .tweet-feed ul li").fadeIn("fast");
					break;
				};

			}).error(function(){

			}).complete(function(){

			});
		},

		/**
		 * Display/Dismiss my custom modal
		*/
		myModal: function(){
			// Listen for click events on modal toggle buttons
			$('[data-toggle="myModal"]').on("click", function(event){
				// Remove click event litener from toggler, dimisser and overlay
				$('[data-toggle="myModal"]').off("click");
				$('[data-dismiss="myModal"]').off("click");
				$('.myModal-olay').off();
				// Get target
				var target = $(this).attr("data-target");
				defaultsJs.showMyModal(target);
				// Re initiate modal function
				defaultsJs.myModal();
				// Prevent default event
				event.preventDefault();
			});
			// Listen for click event on modal dimisser
			$('[data-dismiss="myModal"]').on("click", function(event){
				// Remove click event litener from toggler, dimisser and overlay
				$('[data-toggle="myModal"]').off("click");
				$('[data-dismiss="myModal"]').off("click");
				$('.myModal-olay').off();
				// Get target
				var target = $(this).parents(".myModal"),
					toggle = $(this).attr("data-toggle"),
					next = $(this).attr("data-target");
				// Validate target
				if (target.hasClass("myModal")) {
					// Remove effect class from modal
					target.removeClass("showMyModal");
					// Hide target
					target.delay(200).fadeOut(200, function(){
						// Check if next modal is set
						if (toggle != undefined && toggle == "nextMyModal") {
							// Make modal visible
							defaultsJs.showMyModal(next);
						}
						else{
							// Make scroll bar visible on body tag
							$("body").removeClass("hide-overflow");
						}
					});
				};
				// Re initiate modal function
				defaultsJs.myModal();
				// Prevent default action
				event.preventDefault();
			});
			// Listen for click event on modal overlay
			$('.myModal-olay').on("click", function(event){
				// Remove click event litener from toggler, dimisser and overlay
				$('[data-toggle="myModal"]').off("click");
				$('[data-dismiss="myModal"]').off("click");
				$('.myModal-olay').off();
				// Get target
				var target = $(this).parents(".myModal");
				// Validate target
				if (target.hasClass("myModal")) {
					// Remove effect class from modal
					target.removeClass("showMyModal");
					// Hide target
					target.delay(200).fadeOut(200, function(){
						// Make scroll bar visible on body tag
						$("body").removeClass("hide-overflow");
					});
				};
				// Re initiate modal function
				defaultsJs.myModal();
				// Prevent default action
				event.preventDefault();
			});
		},

		showMyModal: function(target){
			// Validate target
			if (target != undefined) {
				// Validate target element
				if ($(target).hasClass("myModal")) {
					// Remove scroll bar from body tag
					$("body").addClass("hide-overflow");
					// Show target
					$(target).fadeIn(200, function(){
						// Add class for effect
						$(target).addClass("showMyModal");
					});
				};
			};
		},

		/**
		 * functions associated with the footer
		*/
		footer: function(){
			// Init footer tweets function
			defaultsJs.footerTweets();
			// Init footer flickr
			defaultsJs.footerFlickr();
		},

		login: function(){

			$("#login-form").validate({

				rules:{
					email:{
						required:true,
						email:true,
					},
					password:{
						required:true,
					}
				},
				messages:{
					email:{
						required:"Please enter your email.",
						required:"Please enter your email.",
					},
					password:{
						required:"Please enter your password.",
					}
				}

			});

			$("#login-form").submit(function(event){

				var $alertTpl = '<p class="alert alert-|LEVEL| alert-dismissable flat-it" id="loginFormAlert">'+
									'<a href="#" data-dismiss="alert" class="close"><i class="fa fa-times"></i></a>'+
									'|MESSAGE|'+
								'</p>',
					displayAlert;

				if ($("#login-form").valid()) {
					// Get form data
					var email = $("#login-form #email").val(),
						password = $("#login-form #password").val(),
						returnTo = $("#login-form #return").val(),
						remember = ($("#login-form #remember").is(":checked")) ? true : false,
						token = Site.Config.token,
						url = Site.Config.url+"/api/auth/login";
					// Disable form
					$("#login-form #email").prop("disabled", true);
					$("#login-form #password").prop("disabled", true);
					$("#login-form #remember").prop("disabled", true);
					$("#login-form [type=submit]").prop("disabled", true);
					// Begin Ajax request
					$.ajax({
						url: url,
						type: 'POST',
						data: {
							email: email,
							password: password,
							remember: remember,
						},
						headers: {
							token:token
						}
					}).success(function(response){
						console.log(response);
						$("#login-form  .alert-container #loginFormAlert").remove();
						switch(response.status){
							case"error":
								displayAlert = $alertTpl;
								displayAlert = displayAlert.replace("|LEVEL|", response.level);
								displayAlert = displayAlert.replace("|MESSAGE|", response.message);
								$(displayAlert).prependTo("#login-form  .alert-container");
							break;
							case"success":
								displayAlert = $alertTpl;
								displayAlert = displayAlert.replace("|LEVEL|", response.level);
								displayAlert = displayAlert.replace("|MESSAGE|", response.message);
								$(displayAlert).prependTo("#login-form  .alert-container");
								window.location.href = returnTo;
							break;
							case"warning":
								displayAlert = $alertTpl;
								displayAlert = displayAlert.replace("|LEVEL|", response.level);
								displayAlert = displayAlert.replace("|MESSAGE|", response.message);
								$(displayAlert).prependTo("#login-form  .alert-container");
							break;
							case"info":
								displayAlert = $alertTpl;
								displayAlert = displayAlert.replace("|LEVEL|", response.level);
								displayAlert = displayAlert.replace("|MESSAGE|", response.message);
								$(displayAlert).prependTo("#login-form  .alert-container");
								window.location.href = returnTo;
							break;
							default:
								// Un recognized response
							break;
						};

					}).error(function(){
						// Remove current form
						$("#login-form  .alert-container #loginFormAlert").remove();
						displayAlert = $alertTpl;
						displayAlert = displayAlert.replace("|LEVEL|", "danger");
						displayAlert = displayAlert.replace("|MESSAGE|", "An error occured, please try again.");
						$(displayAlert).prependTo("#login-form  .alert-container");

					}).complete(function(response){
						// Enable form
						$("#login-form #email").prop("disabled", false);
						$("#login-form #password").prop("disabled", false);
						$("#login-form #remember").prop("disabled", false);
						$("#login-form [type=submit]").prop("disabled", false);
					});

				};

				event.preventDefault();

			});

		},

		forgotPassword: function(){

			$("#forgot-form").validate({

				rules:{
					email:{
						required:true,
						email:true,
					}
				},
				messages:{
					email:{
						required:"Please enter your email.",
						required:"Please enter your email.",
					}
				}

			});

			$("#forgot-form").submit(function(event){

				var $alertTpl = '<p class="alert alert-|LEVEL| alert-dismissable flat-it" id="forgotFormAlert">'+
									'<a href="#" data-dismiss="alert" class="close"><i class="fa fa-times"></i></a>'+
									'|MESSAGE|'+
								'</p>',
					displayAlert;

				if ($("#forgot-form").valid()) {
					// Get form data
					var email = $("#forgot-form #email").val(),
						token = Site.Config.token,
						url = Site.Config.url+"/api/auth/forgot";
					// Disable form
					$("#forgot-form #email").prop("disabled", true);
					$("#forgot-form [type=submit]").prop("disabled", true);
					// Begin Ajax request
					$.ajax({
						url: url,
						type: 'POST',
						data: {
							email: email,
						},
						headers: {
							token:token
						}
					}).success(function(response){
						console.log(response);
						$("#forgot-form .alert-container #forgotFormAlert").remove();
						switch(response.status){
							case"error":
								displayAlert = $alertTpl;
								displayAlert = displayAlert.replace("|LEVEL|", response.level);
								displayAlert = displayAlert.replace("|MESSAGE|", response.message);
								$(displayAlert).prependTo("#forgot-form .alert-container");
							break;
							case"success":
								displayAlert = $alertTpl;
								displayAlert = displayAlert.replace("|LEVEL|", response.level);
								displayAlert = displayAlert.replace("|MESSAGE|", response.message);
								$(displayAlert).prependTo("#forgot-form .alert-container");
							break;
							case"warning":
								displayAlert = $alertTpl;
								displayAlert = displayAlert.replace("|LEVEL|", response.level);
								displayAlert = displayAlert.replace("|MESSAGE|", response.message);
								$(displayAlert).prependTo("#forgot-form .alert-container");
							break;
							case"info":
								displayAlert = $alertTpl;
								displayAlert = displayAlert.replace("|LEVEL|", response.level);
								displayAlert = displayAlert.replace("|MESSAGE|", response.message);
								$(displayAlert).prependTo("#forgot-form .alert-container");
							break;
							default:
								// Un recognized response
							break;
						};

					}).error(function(){
						// Remove current form
						$("#forgot-form .alert-container #forgotFormAlert").remove();
						displayAlert = $alertTpl;
						displayAlert = displayAlert.replace("|LEVEL|", "danger");
						displayAlert = displayAlert.replace("|MESSAGE|", "An error occured, please try again.");
						$(displayAlert).prependTo("#forgot-form .alert-container");

					}).complete(function(response){
						// Enable form
						$("#forgot-form #email").prop("disabled", false);
						$("#forgot-form [type=submit]").prop("disabled", false);
					});

				};

				event.preventDefault();

			});

		},

		resetPassword: function(){

			$("#reset-form").validate({

				rules:{
					password:{
						required:true,
						minlength:8
					},
					password_confirmation:{
						required:true,
						equalTo:"#reset-form #password"
					}
				},
				messages:{
					password:{
						required:"This field is required.",
						minlength:"Password must be atleast 8 characters in length.",
					},
					password_confirmation:{
						required:"This field is required.",
						equalTo:"Passwords dont match."
					}
				}

			});

			$("#reset-form").submit(function(event){

				var $alertTpl = '<p class="alert alert-|LEVEL| alert-dismissable flat-it" id="resetFormAlert">'+
									'<a href="#" data-dismiss="alert" class="close"><i class="fa fa-times"></i></a>'+
									'|MESSAGE|'+
								'</p>',
					displayAlert;

				if ($("#reset-form").valid()) {
					// Get form data
					var password = $("#reset-form #password").val(),
						password_confirmation = $("#reset-form #password_confirmation").val(),
						token = Site.Config.token,
						url = $("#reset-form").prop("action");
					// Disable form
					$("#reset-form #password").prop("disabled", true);
					$("#reset-form #password_confirmation").prop("disabled", true);
					$("#reset-form [type=submit]").prop("disabled", true);
					// Begin Ajax request
					$.ajax({
						url: url,
						type: 'POST',
						data: {
							password: password,
							password_confirmation: password_confirmation,
						},
						headers: {
							token:token
						}
					}).success(function(response){
						console.log(response);
						$("#reset-form .alert-container #resetFormAlert").remove();
						switch(response.status){
							case"error":
								displayAlert = $alertTpl;
								displayAlert = displayAlert.replace("|LEVEL|", response.level);
								displayAlert = displayAlert.replace("|MESSAGE|", response.message);
								$(displayAlert).prependTo("#reset-form .alert-container");
							break;
							case"success":
								displayAlert = $alertTpl;
								displayAlert = displayAlert.replace("|LEVEL|", response.level);
								displayAlert = displayAlert.replace("|MESSAGE|", response.message);
								$(displayAlert).prependTo("#reset-form .alert-container");
							break;
							case"warning":
								displayAlert = $alertTpl;
								displayAlert = displayAlert.replace("|LEVEL|", response.level);
								displayAlert = displayAlert.replace("|MESSAGE|", response.message);
								$(displayAlert).prependTo("#reset-form .alert-container");
							break;
							case"info":
								displayAlert = $alertTpl;
								displayAlert = displayAlert.replace("|LEVEL|", response.level);
								displayAlert = displayAlert.replace("|MESSAGE|", response.message);
								$(displayAlert).prependTo("#reset-form .alert-container");
							break;
							default:
								// Un recognized response
							break;
						};

					}).error(function(){
						// Remove current form
						$("#reset-form .alert-container #resetFormAlert").remove();
						displayAlert = $alertTpl;
						displayAlert = displayAlert.replace("|LEVEL|", "danger");
						displayAlert = displayAlert.replace("|MESSAGE|", "An error occured, please try again.");
						$(displayAlert).prependTo("#reset-form .alert-container");

					}).complete(function(response){
						// Enable form
						$("#reset-form #password").prop("disabled", false);
						$("#reset-form #password_confirmation").prop("disabled", false);
						$("#reset-form [type=submit]").prop("disabled", false);
					});

				};

				event.preventDefault();

			});

		},
		
		/**
		 * Listens for window level events 
		 * and calls the required function
		*/
		windowEventsListener: function(){
			// Window scroll event listener
			$(window).scroll(function(){
				// Call sticky nav function
				// defaultsJs.stickynav();
			});
		},

		init: function(){
			// Init window events listener
			defaultsJs.windowEventsListener();
			// Load javascript scroll reveal 
			window.sr = new scrollReveal();
			// Init mobile menu function
			defaultsJs.mobilemenu();
			// Init large menu function
			defaultsJs.largmenu();
			// Init footer events
			defaultsJs.footer();
			// Init events slider
			defaultsJs.basicowlcarousel();
			// Init search overlay trigger
			defaultsJs.searchOverlayTrigger();
			// Init Bootstrap datepicker plugin
			$('.datepicker').datepicker({
			    format: 'mm/dd/yyyy'
			});
			defaultsJs.myModal();
			defaultsJs.login();
			defaultsJs.forgotPassword();
			defaultsJs.resetPassword();
		}
	};
	// Load defaults
	defaultsJs.init();
})();

var waitForFinalEvent = (function () {
  var timers = {};
  return function (callback, ms, uniqueId) {
    if (!uniqueId) {
      uniqueId = "Don't call this twice without a uniqueId";
    }
    if (timers[uniqueId]) {
      clearTimeout (timers[uniqueId]);
    }
    timers[uniqueId] = setTimeout(callback, ms);
  };
})();
// Read a page's GET URL variables and return them as an associative array.
var getUrlVars = (function(){
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
});