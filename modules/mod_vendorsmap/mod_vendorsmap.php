<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_vendorsmap
 * @copyright   Copyright (C) 2005 - 2016 Nordmograph.com. All rights reserved.
 * @license     GNU General Public License version 3; see LICENSE.txt
 */
defined('_JEXEC') or die;
require_once dirname(__FILE__).'/helper.php' ;
$juri	= JURI::Base();
$jinput 	= JFactory::getApplication()->input;
$input_lat	= $jinput->get('lat','');
$input_lng	= $jinput->get('lng','');
$doc	= JFactory::getDocument();
$db		= JFactory::getDBO();
$user	= JFactory::getUser();
$lang 	= JFactory::getLanguage();
$lang->load( 'com_vmvendor', JPATH_SITE , '', false);


// component params
$cparams 			= JComponentHelper::getParams('com_vmvendor');
$unitsystem			= $cparams->get('unitsystem','METRIC');
$markers_folder		= $cparams->get('markers_folder','components/com_vmvendor/assets/img');
$js_apikey			= $cparams->get('js_key');  

$tipclass		= $cparams->get('tipclass','');
$doc->addStylesheet($juri.'components/com_vmvendor/assets/css/fontello.css');

// module params
$width 				= $params->get('width','100%');
$height				= $params->get('height','400px');
$html5				= $params->get('html5',1);
$zoom				= $params->get('zoom','10');
$min_zoom			= $params->get('min_zoom','10');
$max_zoom			= $params->get('max_zoom','20');
$map_type			= $params->get('map_type','ROADMAP');
$scrollwheel 		= $params->get('scrollwheel','false');
$streetview 		= $params->get('show_streetview',1);
$placeapifield 		= $params->get('address_search',1);
$marker_search 		= $params->get('marker_search',1);
$stylez				= $params->get('stylez');

//$custom_filter		= $params->get('custom_filter' );
$addmodule			= $params->get('addmodule' );
$addmodule_position	= $params->get('addmodule_position','RIGHT_CENTER' );
$closemodule		= $params->get('closemodule',1 );
$fullscreen_button	= $params->get('fullscreen_button',1 );
$clustering			= $params->get('clustering',1 );
$cluster_lib		= $params->get('cluster_lib',2 );
$drop_markers		= $params->get('drop_markers',1 );

$def_lat				= $params->get('def_lat','0');
$def_lng				= $params->get('def_lng','0');


$integrations_position		= $params->get('integrations_position', 'BOTTOM_LEFT' );
$teleportation_position		= $params->get('teleportation_position' , 'TOP_LEFT');
$markersearch_position		= $params->get('markersearch_position' , 'TOP_CENTER');
$ajaxloader_position		= $params->get('ajaxloader_position' , 'BOTTOM_CENTER');

$include_jquery		= $params->get('include_jquery',0 );
$jquery_url			= $params->get('jquery_url','//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js' );
$jqueryui_url		= $params->get('jqueryui_url','//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js' );
$jqueryui_css		= $params->get('jqueryui_css','//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css' );
if($include_jquery)
	$doc->addScript($jquery_url);


$integrations 			= 0;
$show_users				= 1;
$usermarker 			= '1';

if($show_users)
	$integrations++;

//if($clustering==2)
	//$integrations++;	
$api_url= '//maps.googleapis.com/maps/api/js?key='.$js_apikey;
if($placeapifield)
	$api_url .= '&libraries=places';	
$doc->addScript( $api_url );	

if($clustering>0)
{
	if($cluster_lib==1 )
		$doc->addScript('//cdn.rawgit.com/googlemaps/js-marker-clusterer/gh-pages/src/markerclusterer_compiled.js');
	elseif($cluster_lib==2)
		$doc->addScript( $juri.'modules/mod_vendorsmap/js/markerclusterer.js' );
}

$doc->addStylesheet($juri.'modules/mod_vendorsmap/css/style.css');

$script =  "var map; 
			var seenCoordinates 	= [];
			var markers 			= [];
			var marker 				;
			var html				= [];
			var directionsDisplay	= null;
      		var directionsService 	= new google.maps.DirectionsService();
			var mc 					=[];
			var clustate 			= 1;
			var input_lat 			= '".$input_lat."' ;
			var input_lng 			= '".$input_lng."';
			function initialize()   
			{  
  				directionsDisplay = new google.maps.DirectionsRenderer();
				function loadLoc(){
					if($html5 && navigator.geolocation && input_lat=='' ){
						navigator.geolocation.getCurrentPosition(
							function(position) {							   
								var loc = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
								loadmap(loc, true);
							},
							function (error) { 
						  		loadmap(new google.maps.LatLng('".$def_lat."', '".$def_lng."') , false );
							}
						);	
					}
					else
						loadmap(new google.maps.LatLng('".$def_lat."', '".$def_lng."') , false );
				}
				loadLoc();
		 		function loadmap(loc , guessed ){
					var myOptions = {  
						zoom: ".$zoom.",  
						minZoom: ".$min_zoom.",
						maxZoom: ".$max_zoom.",
						scrollwheel: ".$scrollwheel." ,
						center: loc,  
						mapTypeId: google.maps.MapTypeId.".$map_type.",
						scroll:{x:jQuery(window).scrollLeft(),y:jQuery(window).scrollTop()},
						mapTypeControl: true,
						streetViewControl: ".$streetview."
					}  
					var map = new google.maps.Map(document.getElementById('map-canvas'), myOptions); 
					directionsDisplay.setMap(map);
					directionsDisplay.setPanel(document.getElementById('directionspanel'));";
					if($clustering>0)
					{
						if($cluster_lib==1)
							$script .="mc = new MarkerClusterer(map, markers, {averageCenter: true, gridSize: 40});";
						elseif($cluster_lib==2)
						{
							$script .= "var mcOptions = {gridSize: 40, maxZoom: 20};
								mc = new MarkerClusterer(map, markers ,mcOptions);";
						}
					}

		
			if($stylez!=''){
				$script .="var stylez = ".$stylez.";
					var styledMapOptions = {
							name: '1'
					}
					var MapType = new google.maps.StyledMapType( stylez, styledMapOptions);
					map.mapTypes.set('1', MapType);
					map.setMapTypeId('1');";	
			}
			
		
			
			$script .= "var mapajaxloader = /** @type {HTMLInputElement} */( document.getElementById('mapajaxloader'));
				map.controls[google.maps.ControlPosition.".$ajaxloader_position."].push(mapajaxloader);				
				jQuery('#mapajaxloader').css('margin-top', '5px');
				//jQuery('#mapajaxloader').show();
				jQuery('#mapajaxloader').bind('ajaxStart', function(){
					jQuery(this).show();
				}).bind('ajaxStop', function(){
					jQuery(this).hide();
				});
				";
			if($fullscreen_button)
			{	
				$script .= "var fullscreen_tog = /** @type {HTMLInputElement} */( document.getElementById('fs_toggler'));
				map.controls[google.maps.ControlPosition.TOP_RIGHT].push(fullscreen_tog);
				jQuery('#fs_toggler').css('padding-top', '5px');
				var fs = 0;
				
				function setfullscreen(){
					jQuery('#map-canvas').toggleClass('setfullscreen');
					jQuery('body').css('overflow','hidden');
			 		jQuery('#map-canvas').css('position','fixed');
					fs = 1;
					google.maps.event.trigger(map, 'resize');
				}
				function unsetfullscreen(){				
					jQuery('#map-canvas').toggleClass('setfullscreen');
					jQuery('body').css('overflow','');
					jQuery('#map-canvas').css('position','relative');
					fs = 0;
					google.maps.event.trigger(map, 'resize');
				}
				jQuery('#fullscreen_toggler').click(function() {
			  		if(fs==0){
						setfullscreen();
					}
					else if(fs==1){
						unsetfullscreen();
					}
				});
				jQuery(document).keyup(function(e) {
					if (e.keyCode == 27 && fs==1){					
							unsetfullscreen();
					}
				});";
			}
			
			
		
			if($addmodule)
			{
				$script .= "var embed_modules = /** @type {HTMLInputElement} */( document.getElementById('embed_modules'));
				map.controls[google.maps.ControlPosition.".$addmodule_position."].push(embed_modules);
				jQuery('#embed_modules').show();";
			}
			if($marker_search)
			{
				//$doc->addScript($jqueryui_url);
				$doc->addStyleSheet($jqueryui_css);
				echo '<script src="'.$jqueryui_url.'"></script>';
				$script .= "var marker_search = /** @type {HTMLInputElement} */(
				document.getElementById('marker-search'));
				map.controls[google.maps.ControlPosition.".$markersearch_position."].push(marker_search);
				document.getElementById('marker-search').style.display ='';
				jQuery(function() {
					jQuery('.auto').autocomplete({
						source: function( request, response )
								{                      
									var integrations = jQuery('input:checkbox:checked.integrations_box').map(function () {
									  return this.value;
									}).get();
							jQuery.ajax(
									{ 
										url: 'index.php?option=com_vmvendor&view=markersearch&format=json',
										data: {
												term: request.term, 
											  },        
										type: 'POST',  
										dataType: 'json',                                                                                        
										success: function( data ) 
										{
											response( jQuery.map( data, function( item ) 
											{
												return{
														label: item.label+' '+item.title ,
														value: item.title ,
														coords: item.coords    ,
														integration: item.integration,
														contentid:item.contentid       
													   }
											}));
										}
									});                
								},
						minLength: 3,
						select: function(event, ui) { 
									var coordz = ui.item.coords.split(',');
									var center = new google.maps.LatLng( coordz[0],coordz[1] );
									map.setCenter( center );
									map.setZoom(20);
									var wait = setTimeout( bounceSearchResultMarker( ui.item.integration , ui.item.contentid) ,1000);
									clearTimeout(wait);
								}
					});				
				});";
			}
			if( $placeapifield)	{				
				$script .= "// Create the search box and link it to the UI element.
			  		var input = /** @type {HTMLInputElement} */(
				  	document.getElementById('pac-input'));					
			  		map.controls[google.maps.ControlPosition.".$teleportation_position."].push(input);
					document.getElementById('pac-input').style.display ='';
			  		var searchBox = new google.maps.places.SearchBox(
					/** @type {HTMLInputElement} */(input));
				  	google.maps.event.addListener(searchBox, 'places_changed', function() {
						var places = searchBox.getPlaces();
						var placemarkers = [];
						for (var i = 0, placemarker; placemarker = placemarkers[i]; i++) {
						  placemarker.setMap(null);
						}
						var bounds = new google.maps.LatLngBounds();
						for (var i = 0, place; place = places[i]; i++) {
					  		bounds.extend(place.geometry.location);
						}
			    		map.fitBounds(bounds);
						map.setZoom(12);
  					});";
			}
			$script .= "google.maps.event.addListener(map, 'idle', mapSettleTime);
						google.maps.event.addListener(map,'dragend',function(event) {
							jQuery('#marker-search').val('');
							jQuery('#pac-input').val('');
						});";
			
			
					
			$script .= "jQuery('input:checkbox.integrations_box').on('change', function(){
					 var integrations = jQuery('input:checkbox:checked.integrations_box').map(function () {
					  return this.value;
					}).get();
					dropOutofIntegrationsMarkers(map);
					if (jQuery.inArray('user' , integrations)>=0)
					{
					 	loadUserMarkersFromCurrentBounds(map);
					 	//if($clustering>0)
					 		buildMultiInfowindowCluster(map);

					}
					if (jQuery.inArray('user' , integrations)=='-1')
					{
						jQuery('#custom_filter').hide('fast');
					}
					else
					{
						jQuery('#custom_filter').show('slow');
					}
				});
				function mapSettleTime()
				{
					var usersUpdater;
					var integrations = jQuery('input:checkbox:checked.integrations_box').map(function () 
					{
					  return this.value;
					}).get();
						 if($show_users && jQuery.inArray('user' , integrations)>=0){
						 	usersUpdater =setTimeout(loadUserMarkersFromCurrentBounds(map) ,200);
						 }
						if($show_users && jQuery.inArray('user' , integrations)>=0){
							clearTimeout(usersUpdater);
						}
						
					}
				}
			} 
			// remove markers that aren't currently visible
			var multi_infowindow = new google.maps.InfoWindow();
			function dropOutofBoundsMarkers(map) {	
				var mapBounds = map.getBounds();
				var delmarkers = [];
				for (var i = 0 ; i < markers.length; i++)  {
					if (!markers[i]) {continue};
					if (!mapBounds.contains(markers[i].getPosition())) {
					  	// remove from the map
					  	markers[i].setMap(null);
					  	// remove from the record of seen markers
					  	coordHash = markers[i].integration+markers[i].contentid;
					  	if(seenCoordinates[coordHash]) {
							seenCoordinates[coordHash] = null;
					  	}
					  	// remove from the markers array
					  	markers.splice(i, 1);
						if($clustering>0 && clustate==1 ){
							if(markers.length==0)
								mc.clearMarkers();
							if(i==markers.length-1){
								buildMultiInfowindowCluster(map);
							}
						}
					}
				}
			}
			function buildMultiInfowindowCluster(map){
				multi_infowindow.close();
				mc.clearMarkers();
				mc =  new MarkerClusterer(map, markers);
				google.maps.event.addListener(mc, 'click', 			
					function(cluster) {
						var clickedMarkers = cluster.getMarkers();			
						// check if all cluster markers share exact same location
						for(var j=1; j<clickedMarkers.length; j++){  
							if(!clickedMarkers[j].position.equals( clickedMarkers[0].position ) )
								return false;		
						}
						var compileHtml = '';
						compileHtml += '<div class=\'fullmulticontent\' >';
						compileHtml += '<strong>'+clickedMarkers.length+' ".JText::_('COM_VMVENDOR_MAP_MARKERSHERE') ."</strong>';
						compileHtml += '<div class=\'multicontent\'>';		
						compileHtml += '</div>';
						compileHtml += '</div>';	
						multi_infowindow.setContent(compileHtml);
						 multi_infowindow.setPosition(clickedMarkers[0].position);
						 multi_infowindow.open(map );
						 for (var j=0; j < clickedMarkers.length;j++){
						 //	compiledMarkers.push( clickedMarkers[j].integration+clickedMarkers[j].contentid );
							jQuery.ajax({
								url: 'index.php?option=com_vmvendor&view=vendorinfowindow&format=raw' ,
								data: {contentid: clickedMarkers[j].contentid ,
								latitude:clickedMarkers[j].position.lat(),
								longitude:clickedMarkers[j].position.lng()
							},					
							success: function(Html){			
								jQuery( 'div.multicontent' ).append( '<hr />'+Html+'<div style=\'clear:both\'></div>' );
							}
						});	
					}
				}); 
			}";
		
	
	
			$script .="function loadUserMarkersFromCurrentBounds(map) {
				dropOutofBoundsMarkers(map);
  				var bounds = map.getBounds();
				var swPoint = bounds.getSouthWest();
				var nePoint = bounds.getNorthEast();
				var swLat = swPoint.lat();
				var swLng = swPoint.lng();
				var neLat = nePoint.lat();
				var neLng = nePoint.lng();
				
				jQuery.ajax({
					url: 'index.php?option=com_vmvendor&view=vendormarkers&format=json' ,
					data: {
						swLat: swLat,
						swLng: swLng,
						neLat: neLat,
						neLng: neLng
					},
					type: 'POST',
					dataType: 'json',
					success: function (data) {
						populateUserMarkers(data, map);
    				}
				});	
			}
			
			function populateUserMarkers(pointData ,map, status, xhr) {		
				var userinfowindow = new google.maps.InfoWindow({
				  	content: '<img src=\'".$juri.$markers_folder."/maploader.gif\' />'
				});				
				for (var i = 0 ;  i <  pointData.length ; i++)  {
					var lat = pointData[i].latitude;
					var lng = pointData[i].longitude;
					
					var title = pointData[i].name;
					var contentid = pointData[i].userid;
					var raw	= pointData[i].raw; 
					coordHash = 'user'+pointData[i].userid;";
					//if($usermarker=='1')
						$script .= "var icon = '".$juri.$markers_folder."/marker.png';";
					/*elseif($usermarker=='2')
					{
						$script .= "
						if(pointData[i].thumb !=null && pointData[i].avatarapproved=='1')
						{
							//alert(pointData[i].thumb);
							if( pointData[i].thumb.substr(0,7)=='gallery' )
								var icon = '".$juri."images/comprofiler/gallery/'+ pointData[i].thumb.substr(8) ;
							else
								var icon = '".$juri."images/comprofiler/tn'+pointData[i].thumb ;
						}
						else if(pointData[i].thumb !=null && pointData[i].avatarapproved=='0')
						{
							var icon = '".$juri."components/com_comprofiler/plugin/templates/default/images/avatar/tnpending_n.png';
						}
						else
							var icon = '".$juri."components/com_comprofiler/plugin/templates/default/images/avatar/tnnophoto_n.png';";
					}*/
				$script .= "marker = new google.maps.Marker({		
						position: new google.maps.LatLng(lat, lng),";
				if($drop_markers)
					$script .= "animation: google.maps.Animation.DROP, ";
				$script .="icon: icon,
						title: title,
						integration: 'user',
						contentid: contentid,";
				$script .= "});
					// hash the marker position	
					if(seenCoordinates[coordHash] == null) {
						seenCoordinates[coordHash] = 1;
						markers.push(marker);
						if($clustering>0 && clustate==1){
							mc.addMarker(marker);
							buildMultiInfowindowCluster(map); ////////// yeah !!!!
						}
						else
							marker.setMap(map);
							if(marker.position.equals(map.getCenter()) ){
							marker.setAnimation(google.maps.Animation.BOUNCE);
						}";
			
			
					$script .="google.maps.event.addListener(marker, 'click', (function( marker , i ) {
						return function() {
							userinfowindow.setContent( '<img src=\'".$juri."components/com_vmvendor/assets/img/maploader.gif\' />' );
						  	userinfowindow.open(map, marker);
						  	jQuery.ajax({
									url: 'index.php?option=com_vmvendor&view=vendorinfowindow&format=raw' ,
									data: {contentid: marker.contentid ,
											latitude:marker.position.lat(),
											longitude:marker.position.lng()
											},
									success: function(html){
										userinfowindow.setContent( html );
									}
								});
							}
					  	})(marker, i));
					}		
				}
				
			}





			// remove markers that are not in the  customfilter selection anymore
			function dropOutofCustomProfilesMarkers(map) { ";
		
			
				$script .= "var delmarkers = [];
				for (var i = 0 ; i < markers.length; i++)  {
					if (!markers[i]) {continue};";
				

		$script .= "}
				for (var i = 0 ; i < delmarkers.length; i++)  {
					for (var j = 0 ; j < markers.length; j++)  {
						if(delmarkers[i] == markers[j].integration+markers[j].contentid)	
						{
							markers.splice(j, 1);
							if($clustering>0 && clustate==1 ){
								//if(i==markers.length-1){
									mc.clearMarkers();
								
									buildMultiInfowindowCluster(map);
								//}
							}
							
						}
					}			
				}
			}";

		
		$script .= "// remove markers that are not in the integrations selection anymore
			function dropOutofIntegrationsMarkers(map) {  
				var integrations = jQuery('input:checkbox:checked.integrations_box').map(function () {
				  return this.value;
				}).get();
				var delmarkers = [];
				for (var i = 0 ; i < markers.length; i++)  {
					if (!markers[i]) {continue};
					if (jQuery.inArray(markers[i].integration , integrations)=='-1') {
							// remove from the map
							markers[i].setMap(null);
						
							// remove from the record of seen markers
							coordHash = markers[i].integration+markers[i].contentid;
							if(seenCoordinates[coordHash]) {
								seenCoordinates[coordHash] = null;
							}
							// remove from the markers array
							delmarkers.push( coordHash );
						}
					}
				for (var i = 0 ; i < delmarkers.length; i++)  {
					for (var j = 0 ; j < markers.length; j++)  {
						if(delmarkers[i] == markers[j].integration+markers[j].contentid)	
						{
							markers.splice(j, 1);
							if($clustering>0 && clustate==1){
								mc.clearMarkers();
								buildMultiInfowindowCluster(map);

							}
						}
					}			
				}
			}";	

		if( $marker_search)
		{	// bounce an allready loaded marker
			$script .= "function bounceSearchResultMarker(integration,contentid) {
				if (markers){
					for (var i in markers) {
						if(markers[i].integration == integration && markers[i].contentid == contentid){ 
								markers[i].setAnimation(google.maps.Animation.BOUNCE);
						}
					}
				}
			}";
		}
		
$script .= "function calcRoute(endlat,endlng,modeoftravel) {
		document.getElementById('routelat').value = endlat;
		document.getElementById('routelng').value = endlng;
		var start;
		if(!modeoftravel )
			modeoftravel = document.getElementById('mode').value;		
		if(navigator.geolocation  ) {
			navigator.geolocation.getCurrentPosition(function(position) {
				start = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);			
				var end		=new google.maps.LatLng(endlat,endlng);
				var request = {
						  origin: start,
						  destination: end,
						  travelMode: google.maps.DirectionsTravelMode[modeoftravel],
						  unitSystem: google.maps.DirectionsUnitSystem.".$unitsystem."
				};		
				directionsService.route(request, function(response, status) {
					if (status == google.maps.DirectionsStatus.OK) {
								document.getElementById('directionspanel').style.display='block';
								directionsDisplay.setDirections(response);
					}
					else
						alert('".JText::_('COM_VMVENDOR_MAP_NOROUTEDATA')."');
				});	
			});
		}
     }
	 google.maps.event.addDomListener(window, 'load', initialize);";
$doc->addScriptDeclaration( $script );
require JModuleHelper::getLayoutPath('mod_vendorsmap', $params->get('layout', 'default'));