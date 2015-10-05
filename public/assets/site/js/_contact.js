var contactJS = new function(){

	function initializeMap() {

	      	// Define style options
	      	var stylesArray = [
	      		{
	      			featureType:'water',
	      			elementType:'geometry',
	      			 stylers: [
	      			 	{ color:'#fff212' },
	      			 	{ saturation:50 },
				        { lightness:70 },
				        { visibility:"simplified" }
				      ]
	      		},
	      		{
	      			featureType:'road.local',
	      			elementType:'geometry',
	      			 stylers: [
	      			 	{color:'#d28a3e'},
	      			 	{saturation:0},
				        { lightness: 0 }
				      ]
	      		},
	      		{
	      			featureType:'road.arterial',
	      			elementType:'geometry',
	      			 stylers: [
	      			 	{ color:'#d28a3e' },
	      			 	{ saturation:0 },
				        { lightness: 10 }
				      ]
	      		},
	      		{
	      			featureType:'landscape',
	      			elementType:'all',
	      			 stylers: [
	      			 	{color:'#626f3e'},
	      			 	{lightness:0},
				        { visibility: "on" }
				      ]
	      		},
	      		{
	      			featureType:'transit',
	      			elementType:'all',
	      			 stylers: [
				        { visibility: "on" }
				      ]
	      		},
	      		{
	      			featureType:'poi',
	      			elementType:'geometry',
	      			 stylers: [
				        { color: "#626f3e" },
				        { lightness: 0 },
				        { visibility: "on" }
				      ]
	      		},
	      		{
	      			featureType:'administrative',
	      			elementType:'geometry',
	      			 stylers: [
				        { color: "#626f3e" },
				        { lightness: 0 },
				        { visibility: "on" }
				      ]
	      		},
	      		{
	      			featureType:'all',
	      			elementType:'labels.text.fill',
	      			 stylers: [
				        { color: "#ffffff" },
				        { visibility: "on" }
				      ]
	      		},
	      		{
	      			featureType:'all',
	      			elementType:'labels.text.stroke',
	      			 stylers: [
				        { color: "#626f3e" },
				        { visibility: "on" }
				      ]
	      		}
	      	];

	      	// Set info window content
	      	 var contentString = '<div id="content">'+
							      '<div id="siteNotice">'+
							      '</div>'+
							      '<h1 id="firstHeading" class="firstHeading">Acorns And Oaks</h1>'+
							      '<div id="bodyContent">'+
							      '<p>Attribution: Acorns And Oaks, <a href="http://www.acornsandoaks.org" target="_blank">'+
							      'Website</a> </p>'+
							      '</div>'+
							      '</div>';
			// Init info window
			var infowindow = new google.maps.InfoWindow({
				      content: contentString
				});


	      	// Set Lattitude and longitude
	      	var myLatlng = new google.maps.LatLng(4.8007939,7.0360621);

	      	// Set map options
	        var mapOptions = {
	          center: myLatlng,
	          zoom: 16,
	          scrollwheel: false,
	          mapTypeId: google.maps.MapTypeId.ROADMAP
	        };

	        infowindow.setPosition(myLatlng);

	        // Create map with google maps map object
	        var map = new google.maps.Map(document.getElementById('map-canvas'),
	            mapOptions);

	        // Set marker
	        var marker_image = "assets/wcp/img/test/map_marker.png";
	        var marker = new google.maps.Marker({
			      position: myLatlng,
			      icon: marker_image,
			      draggable: false,
			      map: map,
			      title: 'Acorns And Oaks'
			  });

	        // Set map style option
	        map.setOptions({styles: stylesArray});

	        // Set events listener for marker
	        google.maps.event.addListener(marker, "click", function(){

	        	infowindow.open(map,marker);
	        });

	        toggleBounce();

		     // Marker toggle bounce effect
	      	function toggleBounce() {
			  if (marker.getAnimation() != null) {
			    marker.setAnimation(null);
			  } else {
			    marker.setAnimation(google.maps.Animation.BOUNCE);
			  }
			}
	}

	function validateForm () {
		
		$("#contactForm").validate({
			rules:{
				name:{
					required:true
				},
				email:{
					required:true,
					email:true
				},
				comment:{
					required:true
				}
			},
			messages:{
				name:{
					required:"This field is required."
				},
				email:{
					required:"This field is required.",
					email:"Please enter a valid email address."
				},
				comment:{
					required:"This field is required."
				}
			}
		});
	}

	/*
		Init function for page JS
	*/
	this.init = function(){
			validateForm();
		// Init map on window load
    	// google.maps.event.addDomListener(window, 'load', initializeMap);
	};
};