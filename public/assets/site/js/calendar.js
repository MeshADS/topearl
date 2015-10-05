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