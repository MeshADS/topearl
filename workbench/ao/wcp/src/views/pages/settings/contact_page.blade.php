@extends('wcp::layout.master')
@section('jumbotron_breadcrumb')
	<ul class="breadcrumb">
	    <li>
	      <a href="{{ URL::to('admin') }}"><i class="fa fa-home"></i></a>
	    </li>
	    <li><a href="{{ URL::to('admin/contact_page') }}" class="active">Contact Data</a>
	    </li>
	</ul>
@stop
@section('jumbotron')
	@include('wcp::_partials.html.jumbotron')
@stop
@section("stylesheet")
{{ HTML::style('assets/wcp/plugins/fancyBox/source/jquery.fancybox.css') }}
{{ HTML::style('assets/wcp/plugins/fancyBox/source/helpers/jquery.fancybox-buttons.css') }}
<style type="text/css">
	.nopadding{
		padding: 0px 0px !important;
	}
	#map-canvas{
		position: relative;
		float: left;
		width: 100%;
		height:400px;
		background-color: #ddd;
	}
</style>
@stop
@section('content')

	<div class="content-fluid">

			{{-- Begin Modals --}}

				{{-- Begin Edit Contact Data Modal --}}
					<div class="modal fade slide-down disable-scroll" id="basicinfoModal" tabindex="-1" role="dialog" aria-labelledby="basicinfoModalLabel" aria-hidden="true">

						<div class="modal-dialog">

							<div class="modal-content">
								{{ Form::open(["url"=>"admin/contact_page/".$contact_page->id, "role"=>"form", "method"=>"put"]) }}

									<div class="modal-header">
										<button class="close" data-dismiss="modal" aria-label="close">
											<span aria-hidden="true"><i class="fa fa-times"></i></span>
										</button>
										<h4 class="modal-title">
											Edit Contact Data
										</h4>
									</div>

									<div class="modal-body">

									</div>

									<div class="modal-footer">
										<button type="submit" class="btn btn-warning pull-right">
											Save
										</button>
										<a href="#" class="btn btn-default pull-right m-r-10" data-dismiss="modal">
											Cancel
										</a>
									</div>
								{{ Form::close() }}

							</div>

						</div>

					</div>
				{{-- End Edit Contact Data Modal--}}

			{{-- End Modals --}}
		</div>

		<div class="col-md-12">

			<div class="panel panel-transparent">

				<div class="panel-heading">
					<h4 class="panel-title">Contact Data</h4>
				</div>

				<div class="panel-body">

					<div id="map-canvas">
					</div>

				</div>

			</div>

		</div>

	</div>
	
@stop

@section("javascript")
	{{ HTML::script("assets/wcp/plugins/fancyBox/lib/jquery.mousewheel-3.0.6.pack.js") }}
	{{ HTML::script("assets/wcp/plugins/fancyBox/source/jquery.fancybox.pack.js") }}
	{{ HTML::script("assets/wcp/plugins/fancyBox/source/helpers/jquery.fancybox-buttons.js") }}
	<script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDrhaMKcj3239_zB6LvzwpxZotoH47TUBU">
    </script>
    <script type="text/javascript">
	    function initialize() {

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
							      '<p><b>Acorns And Oaks</b>, also referred to as <b>Acorns \'n Oaks</b>, is a large ' +
							      'sandstone rock formation in the southern part of the '+
							      'Northern Territory, central Australia. It lies 335&#160;km (208&#160;mi) '+
							      'south west of the nearest large town, Alice Springs; 450&#160;km '+
							      '(280&#160;mi) by road. Kata Tjuta and Uluru are the two major '+
							      'features of the Uluru - Kata Tjuta National Park. Uluru is '+
							      'sacred to the Pitjantjatjara and Yankunytjatjara, the '+
							      'Aboriginal people of the area. It has many springs, waterholes, '+
							      'rock caves and ancient paintings. Uluru is listed as a World '+
							      'Heritage Site.</p>'+
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
	    // Init map on window load
	    google.maps.event.addDomListener(window, 'load', initialize);

    </script>
	<script type="text/javascript">
		$(function(){
			$("a.fancyboxGroup").fancybox({
				'transitionIn'	:	'elastic',
				'transitionOut'	:	'elastic',
				'speedIn'		:	600, 
				'speedOut'		:	200, 
				'overlayShow'	:	false
			});

			$(".editLogoModal").on("click", function(event){
				var logoType = $(this).attr("data-logo-type"),
						   w = $(this).attr("data-size-w"),
						   h = $(this).attr("data-size-h");
				$("#logoEditType").val(logoType);
				$("#logoEditDimensions").text("Dimension: "+w+"px by"+h+"px");
	            $("#editLogoModal").modal("show");
	            event.preventDefault();
	        });
		});
	</script>
@stop