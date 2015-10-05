var calendarJS = new function(){

	var monthscarousel,
		events =  (Site.data.events) ? (Site.data.events) ? jQuery.parseJSON(Site.data.events) : [] : [],
		nextEvent = (Site.data.nextEvent) ? jQuery.parseJSON(Site.data.nextEvent) : [],
		calendar =  (Site.data.calendar) ? (Site.data.calendar) ? jQuery.parseJSON(Site.data.calendar) : [] : [],
		daysArr = ["Sun", "Mon", "Tue", "Wed", "Thur", "Fri", "Sat"],
		colors = ["green", "green2", "alt", "white", "gray", "black", "gray2"],
		selectedYear, selectedMonth, selectedCategory = null;


	function  prepCalendar () {
		// Varaibales
			var year, $yearTpl;
		// Append year template to calendar
		$('<ul class="years"></ul>').hide().appendTo('.calendar');
		// Loop through years
		for (i = 0; i < calendar.length; i++) {
			// Get the year
			year = calendar[i];
			// Define css class names for year
			var classes = "white-background hoverable";
			// Validate if the year is the current year we are in
			if (year.isCurrent === true) {
				// Set selected year
				selectedYear = year;
				// Change css class names for current year
				var classes = "green-background hoverable active";
				// Add month to calendar
				makeMonths(year);
			};
			// Create year template
			$yearTpl ='<li class="'+classes+' year_item" id="year_item'+year.full+'">'+'<a href="#" class="year_button" data-year="'+year.full+'">'+year.full+'</a></li>'; 
			// Append year template to the year list
			$($yearTpl).appendTo(".calendar ul.years");
		};
		// Init click events for calendar
			clickable_year();
			clickable_month();
		// Make year element visible
		$(".calendar ul.years").fadeIn('fast', function(){
			$(".calendar .loader").fadeOut('fast');
		});
		
	}

	function makeMonths (year) {
		// Variables
		var months = year.months, $monthTpl;
		// Append month template to the calendar
		$('<ul class="months"></ul>').hide().appendTo('.calendar');
		// Loop through months
		for (var i = 0;  i < months.length; i++) {
			month = months[i]
			// Define css class names for month
			var classes = "alt-background hoverable";
			// Validate if the month is the current month we are in
			if (month.isCurrent === true) {
				// Change css class names for current month
				var classes = "gray2-background hoverable active";
				// Set selected month
				selectedMonth = month;
				// Populate the calendar with events
				addEvents(month);
			};
			// Create month template
			$monthTpl ='<li class="'+classes+' month_item" id="month_item'+month.short+'">'+'<a href="#" class="month_button" data-month="'+month.short+'">'+month.short+'</a></li>'; 
			// Append month template to the month list
			$($monthTpl).appendTo(".calendar ul.months");
		};
		// Init month carousel
		monthCarousel();
		// Make month element visible
		$(".calendar ul.months").show();
	}

	function addEvents (month) {
		// Variables
		var firstday = Date.parse(month.full+" "+"01, "+selectedYear.full),
			lastday = Date.parse(month.full+" "+month.maxDays+", "+selectedYear.full+" 23:59:59"),
			eventTime, thisEvent, $eventTpl, selectedEvents = [], $filterBtn ="";
			if (nextEvent != null) {
				var nextEventTime = Date.parse(nextEvent.schedule_starts);
			};
		// 	Remove days container if exists
		if($(".calendar div").hasClass("days")){
			// Remove element
			$(".calendar div.days").remove();
		};
		// Append day template to the calendar
		$('<div class="days p-t-30"></div>').appendTo('.calendar');
		// Add remove button for category filter
		if(selectedCategory != null){
			$filterBtn = '<div class="container-fluid"><div class="col-md-12  m-b-40 text-center"><a href="#" class="green-background hoverable bordered no-text-decoration bold clear_filter_btn p-v-10 p-h-10"><span class="white-text"><i class="fa fa-times"></i> Clear category filter</span></a></div></div>';
			$($filterBtn).appendTo(".calendar .days");
			// Init clear filter method
			clear_filter();
		}
		for (var i = 0; i < events.length; i++) {
			thisEvent = events[i];
			eventTime = Date.parse(thisEvent.schedule_starts);
			if (eventTime >= firstday && eventTime <= lastday) {
				selectedEvents.push(thisEvent);
			};
		};
		selectedEvents = arrayChunk(selectedEvents, 4);
		// Add events to days container
		for (var i = 0; i < selectedEvents.length; i++) {
			var row = selectedEvents[i];
			$('<div class="container-fluid" id="row'+i+'"></div>').hide().appendTo(".calendar div.days");
			for (var j = 0; j < row.length; j++) {
				var col = row[j],
					eventDate = new Date(Date.parse(col.schedule_starts)),
					day = eventDate.getDay(),
					eventDate = eventDate.getDate(),
					evColor = colors[Math.floor( Math.random() * ( colors.length) )],
					fontbg = (evColor == "alt" || evColor === "white") ? "gray-text" :"white-text",
					eventTime = Date.parse(col.schedule_starts),
					isnextMarker = (eventTime === nextEventTime) ? '<span class="isNext '+evColor+'-background alt" title="Next event"><i class="fa fa-check"></i></span>' : '';

				$eventTpl = '<div class="col-md-3"><div class="eventItem '+evColor+'-background hoverable bordered">'
							+isnextMarker
							+'<span class="font-bg xxl-text light '+fontbg+' bold t-0 l-10 rotate-315deg"><i class="fa fa-calendar"></i></span>'
							+'<div class="date_box">'
							+'<span class="day_num">'+eventDate+'</span>'
							+'<span class="day_name">'+daysArr[day]+'</span></div>'
							+'<h4 class="event-title">'+col.title+'</h4>'
							+'<span class="bold s-text">In: </span><a href="#" title="View events in '+col.category.name+'" data-category="'+col.category.id+'" class="ev_category">'+col.category.name+'</a>'
							+'</div></div>';
				$($eventTpl).appendTo(".calendar div.days #row"+i);
			};
		};
		// Init category filter button function
		clickable_category();
		// Validate for no event
		if(events.length < 1)
		{
			$eventTpl = '<h6 class="white-text uppercase s-text text-center m-v-50">... No Event Found ...</h6>';
			$($eventTpl).appendTo(".calendar div.days");
		}
		var delayshow = 200;
		// Make days element visible
		$(".calendar div.days .container-fluid").each(function(el){

			$(this).delay(delayshow).fadeIn(500);

			delayshow = delayshow+100;

		});
	}

	function clickable_year () {
		
		$(".calendar ul.years .year_item .year_button").on("click", function(event){

			$(".calendar ul.years .year_item .year_button").off("click");

			var btn_year = $(this).attr("data-year");

			$(".calendar ul.years .year_item.active").addClass("white-background");

			$(".calendar ul.years .year_item").removeClass("green-background");
			$(".calendar ul.years .year_item").removeClass("active");

			$("#year_item"+btn_year).removeClass("white-background");

			$("#year_item"+btn_year).addClass("green-background");
			$("#year_item"+btn_year).addClass("active");

			setCalendar(btn_year, selectedMonth.short);

			$(".calendar .loader").fadeIn('fast', function(){
				// Re-init clickable
				clickable_year();
				// Fetch events
				if (selectedCategory != null) {
					getEvents(selectedMonth.short, selectedYear.full, selectedCategory);	
				}
				else{
					getEvents(selectedMonth.short, selectedYear.full);					
				}			
			});

			event.preventDefault();
		});
	}

	function clickable_month () {
		
		$(".calendar ul.months .month_item .month_button").on("click", function(event){

			$(".calendar ul.months .month_item .month_button").off("click");

			var btn_month = $(this).attr("data-month");

			$(".calendar ul.months .month_item.active").addClass("alt-background");

			$(".calendar ul.months .month_item").removeClass("gray2-background");
			$(".calendar ul.months .month_item").removeClass("active");

			$("#month_item"+btn_month).removeClass("alt-background");

			$("#month_item"+btn_month).addClass("gray2-background");
			$("#month_item"+btn_month).addClass("active");

			setCalendar(selectedYear.full, btn_month);

			$(".calendar .loader").fadeIn('fast', function(){
				// Re-init clickable
				clickable_month();
				// Fetch events
				if (selectedCategory != null) {
					getEvents(selectedMonth.short, selectedYear.full, selectedCategory);	
				}
				else{
					getEvents(selectedMonth.short, selectedYear.full);					
				}

			});


			event.preventDefault();
		});
	}

	function clickable_category () {
		
		$(".calendar div.days .ev_category").on("click", function(event){

			$(".calendar div.days .ev_category").off("click");

			var btn_cat = $(this).attr("data-category");
			
			selectedCategory = btn_cat;

			$(".calendar .loader").fadeIn('fast', function(){
				// Re-init clickable
				clickable_category();
				// Fetch events
				getEvents(selectedMonth.short, selectedYear.full, selectedCategory);

			});


			event.preventDefault();
		});
	}

	function clear_filter () {

		$(".calendar div.days .clear_filter_btn").off("click");
		
		$(".calendar div.days .clear_filter_btn").on("click", function(event){
			
			selectedCategory = null;

			$(".calendar .loader").fadeIn('fast', function(){
				// Fetch events
				getEvents(selectedMonth.short, selectedYear.full);

			});


			event.preventDefault();
		});
	}

	function monthCarousel () {
		
		monthscarousel = $(".calendar ul.months").owlCarousel({
			loop:false,
			margin:0,
			responsive:{
		        0:{
		            items:1
		        },
		        992:{
		            items:12
		        }
		    }
		});
	}

	function setCalendar (y, m) {
		// Loop through callendar
		for (i = 0; i < calendar.length; i++) {
			// Get the year
			year = calendar[i];
			// Validate if the year is equal to y
			if (year.full === y) {
				// Set selected year
				selectedYear = year;
				// Loop through months in selected year
				for (i = 0; i < year.months.length; i++) {
					// Get month
					month = year.months[i];
					// Validate if month is equal to m
					if (month.short === m) {
						// Set selected month
						selectedMonth = month;
					}
				}
			};

		};
	}

	function getEvents (m, y, c) {
		
		if (!m || !y) return 0;

		var site_url = Site.Config.url,
			token = Site.Config.token;
		
		var req_data = {
			month:m,
			year:y
		};
		

		if (c) { req_data.category = c };


		$.ajax({
			url:site_url+"/api/calendar",
			data:req_data,
			type:"GET",
			headers: {
				token: token
			}
		}).success(function(data){
			switch(data.status){
				case "error":
					$(".calendar div.days").html("<div class='alert allert-"+data.level+" text-center'>"+data.message+"</div>");
				break;
				default:
					events = data.data;
					addEvents(selectedMonth);
				break;
			}

		}).error(function(data){
			console.log(data);
		}).complete(function(){
			// Hide loader
			$(".calendar .loader").fadeOut('fast');
		});
	}

	function arrayChunk (array, size) {
			//declare vars
			var output = [];
			var i = 0; //the loop counter
			var n = 0; //the index of array chunks
			
			for(item in array) {
				
				//if i is > size, iterate n and reset i to 0
				if(i >= size) {
					i = 0;
					n++;
				}
				
				//set a value for the array key if it's not already set
				if(!output[n] || output[n] == 'undefined') {
					output[n] = [];
				}
				
				output[n][i] = array[item];
				
				i++;
				
			}
			
			return output;
			
	};

	/*
		*Init function for calendar JS
	*/
	this.init = function(){

		prepCalendar();
				
	};
};

var admissionJS = new function(){

	function loadTeamGallery(){
	    // Init inline Fancybox
	  $(".fancyboxBtn").fancybox({ 
	  	overlay : { locked : false },
	  	autoDimensions:false,
	  	width:560
	  });
	}

	function validateForm(){
	    // Init inline Fancybox
	    if ($("form").hasClass("application-form")) {

	    	$("#application-form").validate({
	    		rules:{
	    			childs_name:{
	    				required:true
	    			},
	    			childs_surname:{
	    				required:true
	    			},
	    			childs_age:{
	    				required:true,
	    				number:true,
	    				min:0
	    			},
	    			childs_sex:{
	    				required:true
	    			},
	    			childs_birthday:{
	    				required:true,
	    				date:true
	    			},
	    			address:{
	    				required:true
	    			},
	    			starting_on:{
	    				required:true,
	    				date:true
	    			},
	    			mothers_name:{
	    				required:true
	    			},
	    			mothers_workphone:{
	    				required:true
	    			},
	    			mothers_homephone:{
	    				required:true
	    			},
	    			mothers_mobilephone:{
	    				required:true
	    			},
	    			mothers_email:{
	    				required:true,
	    				email:true
	    			},
	    			fathers_name:{
	    				required:function(element) {
							        if ($(".application-form #mothers_name").val().length < 1) {
							        	return true;
							        }
							        else{
							        	return false;
							        }
							      }
	    			},
	    			fathers_workphone:{
	    				required:function(element) {
							        if ($(".application-form #mothers_workphone").val().length < 1) {
							        	return true;
							        }
							        else{
							        	return false;
							        }
							      }
	    			},
	    			fathers_homephone:{
	    				required:function(element) {
							        if ($(".application-form #mothers_homephone").val().length < 1) {
							        	return true;
							        }
							        else{
							        	return false;
							        }
							      }
	    			},
	    			fathers_mobilephone:{
	    				required:function(element) {
							        if ($(".application-form #mothers_mobilephone").val().length < 1) {
							        	return true;
							        }
							        else{
							        	return false;
							        }
							      }
	    			},
	    			fathers_email:{
	    				required:function(element) {
							        if ($(".application-form #mothers_email").val().length < 1) {
							        	return true;
							        }
							        else{
							        	return false;
							        }
							      },
	    				email:true
	    			},
	    		},
	    		messages:{
	    			childs_age:{
	    				required:"Age must be a number.",
	    				number:"Age must be a number.",
	    				min:"Child's age must 0 or above."
	    			},
	    			childs_birthday:{
	    				date:"Birthday must be in the following format mm/dd/yyyy."
	    			},
	    			starting_on:{
	    				date:"Starting date must be in the following format mm/dd/yyyy."
	    			},
	    		}
	    	});

	    };
	    // Init inline Fancybox
	    if ($("form").hasClass("afterschool-application-form")) {

	    	$("#afterschool-application-form").validate({
	    		rules:{
	    			childs_name:{
	    				required:true
	    			},
	    			childs_surname:{
	    				required:true
	    			},
	    			childs_sex:{
	    				required:true
	    			},
	    			dob:{
	    				required:true,
	    				date:true
	    			},
	    			address:{
	    				required:true
	    			},
	    			starting_on:{
	    				required:true,
	    				date:true
	    			},
	    			parents_name:{
	    				required:true
	    			},
	    			parents_phone:{
	    				required:true
	    			},
	    			parents_email:{
	    				required:true,
	    				email:true
	    			},
	    			work_address:{
	    				required:true
	    			},
	    			checkboxes:{
	    				required:true,
	    				min:1
	    			}
	    		},
	    		messages:{
	    			dob:{
	    				date:"Birthday must be in the following format mm/dd/yyyy."
	    			},
	    			starting_on:{
	    				date:"Starting date must be in the following format mm/dd/yyyy."
	    			},
	    			checkboxes:{
	    				required:"Please select at least one club to continue.",
	    				min:"Please select at least one club to continue."
	    			},
	    		}
	    	});

	    	var checkedClubs = 0;
	    		checkcheckboxes(checkedClubs);
	    	// Clubs check box
	    	$(".club_checks").click(function(){
				checkedClubs = 0;
	    		checkcheckboxes(checkedClubs);
	    	});

	    };
	}
	function checkcheckboxes(checkedClubs){
		// Update count of checkboxes
		$(".club_checks").each(function(){
			elm = $(this);
			if (elm.is(":checked")) {
				checkedClubs++;
			};
		});
		// Update checkedbox count
		$("#afterschool-application-form .checkboxes").val(checkedClubs);
		// Disable/Enable checkboxes
		$(".club_checks").each(function(){
			elm = $(this);
			if (checkedClubs == 2 && !elm.is(":checked")) {
				elm.prop("disabled", true);
			}
			else{
				elm.prop("disabled", false);
			}
		});
	}
	/*
		Init function for page JS
	*/
	this.init = function(){
			
		loadTeamGallery();
		validateForm();
	};
};
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
var formJS = new function(){
	var form, elements,
		cc = 0,
		validationRules = [];
		validateOptions = {},
		colors = ["orange", "green", "green2", "purple", "blue"],
		$fieldsetTpl = '<fieldset class="m-b-30">'+
							'|LEGEND|'+
							'<div class="row">'+
								'|ELEMENTS|'+
							'</div>'+
						'</fieldset>',
		$elementTpl = 	'<div class="|SIZE|">'+
							'<div class="form-group">'+
								'|ELEMENT|'+
							'</div>'+										
						'</div>',
		$buttonTpl= '<button type="submit" class="btn btn-lg |COLOR|-background hoverable m-v-40">'+
							'<span class="white-text">Submit</span>'+
					'</button>',
		$elements = [],
		$listValues = [],
		$elements["checkbox"] = '<h4 class="black-text">|ELEMENTNAME|</h4>|OPTIONS|',
		$elements["radio-button"] = '<h4 class="black-text">|ELEMENTNAME|</h4>|OPTIONS|',
		$elements["select"] = 	'<label for="|ELEMENTID|">|ELEMENTNAME|</label>'+
								'<select name="|ELEMENTSLUG|" id="|ELEMENTID|" class="form-control">'+
								'|OPTIONS|'+
								'</select>',
		$elements["text-input"] = 	'<label for="|ELEMENTID|">|ELEMENTNAME|</label>'+
									'<input type="text" name="|ELEMENTSLUG|" id="|ELEMENTID|" class="form-control |IFDATE|">',
		$elements["textarea"] = 	'<label for="|ELEMENTID|">|ELEMENTNAME|</label>'+
									'<textarea name="|ELEMENTSLUG|" id="|ELEMENTID|" rows="3" class="form-control"></textarea>',
		$listValues["select"] = 	'<option value="|OPTIONVALUE|">'+'|OPTIONNAME|'+'</option>',
		$listValues["checkbox"] = 	'<label for="|ELEMENTID|">'+
										'<input type="checkbox" name="|ELEMENTSLUG|[]" id="|ELEMENTID|" value="|OPTIONVALUE|">&nbsp;|ELEMENTNAME|'+
									'</label><br>',
		$listValues["radio-button"] = '<label for="|ELEMENTID|">'+
										'<input type="radio" name="|ELEMENTSLUG|" id="|ELEMENTID|" class="">&nbsp;|ELEMENTNAME|'+
									'</label><br>';

	var bootData = function(){
		form = Site.data.form;
		elements = form.elements;
		formHtml = "";
		for (var i in elements) {
			var group = elements[i];
			var resp = addElementGroups(i, group, $fieldsetTpl);
			formHtml += resp;
		};
		// Append hidden form elements to form
		$(formHtml).appendTo("#"+form.slug+" #form_fields");
		// Create submit button for forms
		var button = $buttonTpl;
		checkCC();
		button = button.replace("|COLOR|", colors[cc]);
		cc++;
		$(button).appendTo("#"+form.slug+" #form_button");
		// Hide form loader
		$(".form_holder .loader").fadeOut('fast', function(){
			// Make form visisible
			$(".form_holder form").fadeIn('fast');
		});
		// Init form plugins
		// Validate form
		validateForm("#"+form.slug);
		// Date picker
		$('.datepicker').datepicker({
		    format: 'mm/dd/yyyy'
		});
	};

	var addElementGroups = function (groupName, group, tpl) {
		checkCC();
		var elements = "";
		if (group.length > 0) {
			tpl = tpl.replace('|LEGEND|', '<legend class="'+colors[cc]+'-text">'+groupName+'</legend>')
			cc++;
		}
		else{
			tpl = tpl.replace('|LEGEND|', '');
		}
		for(var i in group){
			var element = group[i];
		  	elements += creatElement(element);
		};
		tpl = tpl.replace("|ELEMENTS|", elements);
	  	return tpl;
	};

	var creatElement= function(element){
		// Create new checkbox
		var mainTpl = $elementTpl,
		    groupElement = $elements[element.type],
			thisListValues = "",
			listvalues = element.list_values,
			elementRules = element.rules,
			rules = [],
			isDate = false;
		// Loop through rules
		for(var er in elementRules){
			elRule = elementRules[er];
			rules[elRule] = true;
			if (elRule == "date") {
				isDate = true;
			};
		}
		// Loop through list value
		for(var lv in listvalues){
			var listvalue = listvalues[lv];
			thisListValues += $listValues[element.type];
			// Convert elemenet data
			thisListValues = thisListValues.replace("|ELEMENTID|", element.id+listvalue.id);
			thisListValues = thisListValues.replace("|ELEMENTNAME|", listvalue.name);
			thisListValues = thisListValues.replace("|ELEMENTSLUG|", element.slug);
			thisListValues = thisListValues.replace("|OPTIONNAME|", listvalue.name);
			thisListValues = thisListValues.replace("|OPTIONVALUE|", listvalue.value);
			thisListValues = thisListValues.replace("|ELEMENTID|", element.id+listvalue.id);			
		};
		// Set element rules
		validationRules[element.slug] = rules;
		// Format element
		groupElement = groupElement.replace("|OPTIONS|", thisListValues)
		groupElement = groupElement.replace("|ELEMENTNAME|", element.name)
		groupElement = groupElement.replace("|ELEMENTID|", element.id);
		groupElement = groupElement.replace("|ELEMENTID|", element.id);
		groupElement = groupElement.replace("|ELEMENTSLUG|", element.slug);
		if (isDate) {
			groupElement = groupElement.replace("|IFDATE|", 'datepicker');
		};
		// Format element
		mainTpl = mainTpl.replace("|SIZE|", element.size);
		mainTpl = mainTpl.replace("|ELEMENT|", groupElement);
		// Return response
		return mainTpl;
	};

	var validateForm = function(id){

		$(id).validate({
			rules: validationRules
		});
	};

	var checkCC = function(){
		if (cc == colors.length < 1) { cc = 0; };		
	};

	/*
		Init function for page JS
	*/
	this.init = function(){
		bootData();		
	};
};
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
var homeJS = new function(){

	var slider;
	
	/**
	 * Starts home slider
	*/
	function slider_init(){
		// Init slider
		slider = $(".heroslider").owlCarousel({
					"loop":true,
				  	"margin":0,
				  	"autoplay":true,
				  	"autoplayHoverPause":true,
				  	"autoplayTimeout":7000,
				  	responsive:{
						        0:{
						            items:1
						        },
						        992:{
						            items:1
						        }
						    }
				});
				// Call nav function
				slider_navigation();
	}
	
	/**
	 * Centers the slider navigation vertically
	*/
	function reposition_slider_nav(){
		// Variables
		var navheight = $(".heroslider-nav .nav-item").outerHeight(), // Grab slider nav height
			sliderHeight = $(".heroslider").outerHeight(), // Grab slider height
			newPos = ( sliderHeight - navheight ) / 2; // Calculate new top position
			// Set nav item new top position ( CSS Injection )
			$(".heroslider-nav .nav-item").css({top:newPos+"px"});
	}

	/**
	 * Slider navigation function
	*/
	function slider_navigation(){
		// Variables
		navItem = $(".heroslider-nav .nav-item"); // Grab nav items
		// Add click event listener to nav items
		navItem.click(function(event){	
			// Determine event type
			if($(this).hasClass("next"))
			{
				// Go to next slide
				slider.trigger('next.owl.carousel');
			}
			else if($(this).hasClass("prev")){
				// Gor to previuos slide
				slider.trigger('prev.owl.carousel');
			}
			else{
				// Do nothing
			}
			// Element default action
			event.preventDefault();
		});
		// Reposition slider nav
		waitForFinalEvent(function(){
			reposition_slider_nav();
		}, 500, "yadiya");

	}

	/**
	 * Window events listener for home page
	*/
	function nexteventcountdown(){
		// Get next event
		var nextevent = JSON.parse(Site.data.nextEvent);
		if(nextevent){
			$("#next-event-title").text(nextevent.title);
			// Init next event count down
			$('.nexteventcountdown')
				.countdown({
			        date: nextevent.schedule_starts,
			        render: function(data){
			        	$(this.el).html(
			        		"<li><span class='green-text'>"+this.leadingZeros(data.days, 2)+"</span><br> <span class='time-title'>Days</span></li>"
			        		+"<li><span class='yellow-text'>"+this.leadingZeros(data.hours, 2)+"</span><br> <span class='time-title'>Hours</span></li>"
			        		+"<li><span class='white-text'>"+this.leadingZeros(data.min, 2)+"</span><br> <span class='time-title'>Minutes</span></li>"
			        		+"<li><span class='yellow-text'>"+this.leadingZeros(data.sec, 2)+"</span><br> <span class='time-title'>Seconds</span></li>"
			        		);
			        }
			    });
		};
	}

	/**
	 * Window events listener for home page
	*/
	function homeWindowEventsListener(){

		// Resize listener
		$(window).resize(function(){
			// Delay event
			waitForFinalEvent(function(){
				reposition_slider_nav();
			}, 500, "yadiya");
		});
	}

	/*
		*Init function for home JS
	*/
	this.init = function(){
		slider_init();
		nexteventcountdown();
		homeWindowEventsListener();
	};
};
var myaccount = new function(){

	/**
	* Upload and crop user avatar with cropic js
	*/
	function imageUpload(){
		var url = Site.Config.url;
		var user = Site.data.userdata;
		var cropperOptions = {
			modal:true,
			zoomFactor:20,
			rotateControls:false,
			customUploadButtonId:'newAvatarTriger',
			uploadUrl:url+'/api/user/uploadAvatar',
			cropUrl:url+'/api/user/cropAvatar',
			uploadData:{
				"user": user.id,
				"_token": Site.Config.token
			},
			cropData:{
				"user": user.id,
				"_token": Site.Config.token
			},
			onAfterImgCrop: function(data){
				$("#account_avatar").prop("src", url+"/"+data.url);
				resetCroper();
			},
			onError: function(errormsg){
			}
		};
		var cropper = new Croppic('newAvatarCrop', cropperOptions);

		function resetCroper(){
			cropper.reset();
		}
	}
	/**
	* Messages table checkbox actions
	*/
	function checkbox_action () {
		$("#master_checkbox").click(function(event){

			var checked = 0;

			if ($(this).is(":checked")) {

				$(".table_checkbox").prop("checked", true);
				$(".bulk_checked_list").html("");
				$(".table_checkbox").each(function(){
					var item_id = $(this).val();
					$("<input type='hidden' name='list[]' value='"+item_id+"' id='item"+item_id+"'>").prependTo(".bulk_checked_list");
				});
			}
			else{

				$(".table_checkbox").prop("checked", false);
				$(".bulk_checked_list").html("");
			}

			$(".table_checkbox").each(function(){

				if ($(this).is(":checked")) {
					checked ++;
				};

			});

			if (checked > 0) {
				// There are checked
				$(".bulkActionElement").removeClass("hide");
			}
			else{
				// No checked
				if (!$(".bulkActionElement").hasClass("hide")) {
					$(".bulkActionElement").addClass("hide");
				};
			}
			
		});

		$(".table_checkbox").click(function(){

			var item_id = $(this).val(),
				checked = 0;

			if (!$(this).is(":checked")) {

				$("#master_checkbox").prop("checked", false);

				$(".bulk_checked_list #item"+item_id+"").remove();
			}
			else{

				$("<input type='hidden' name='list[]' value='"+item_id+"' id='item"+item_id+"'>").prependTo(".bulk_checked_list");
			}

			$(".table_checkbox").each(function(){

				if ($(this).is(":checked")) {
					checked ++;
				};

			});

			if (checked > 0) {
				// There are checked
				$(".bulkActionElement").removeClass("hide");
			}
			else{
				// No checked
				if (!$(".bulkActionElement").hasClass("hide")) {
					$(".bulkActionElement").addClass("hide");
				};
			}
		});
	}

	/*
	* Init function for page JS
	*/
	this.init = function(){
		imageUpload();
		checkbox_action();		
	};
};
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