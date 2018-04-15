var currentMapCenter;
function initializeMap(configs, markersCode, stylesCode, boxStyles){
	var myLatlng;

	configs.width = configs.width =='auto' || configs.width =='100%' ? "100%":configs.width + 'px';
	configs.height = configs.height =='auto' || configs.height =='100%' ? "100%":configs.height + 'px';

	document.getElementById(configs.cavas_id).style.width=configs.width;
	document.getElementById(configs.cavas_id).style.height=configs.height;

	if(configs.owm_api == ''){
		configs.weather = false;
	}
	var markersJson = BT.Base64.base64Decode(markersCode);
	var markers 	= JSON.parse(markersJson);
	var max 		= markers.length;
	var stylesJson =  BT.Base64.base64Decode(stylesCode);
	var styles 	= JSON.parse(stylesJson);
	var infowindowData = Array();

	/** create map  */
	createMapCenter(configs, markers ,styles, boxStyles);
	/**
	 * Create Map center
	 * @param configs
	 * @param markers
	 * @returns {Latitude,Longitude}
	 */
	function createMapCenter(configs,markers,styles, boxStyles){
		if(configs.mapCenterType == 'address' && configs.mapCenterAddress == '' || configs.mapCenterType == 'coordinate' && configs.mapCenterCoordinate == '' ){
			if(markers.length == 0){
				var geocoder = new google.maps.Geocoder();
				geocoder.geocode( { 'address': 'A2DN3 Nguyen Khanh Toan, Cau Giay, Ha Noi' }, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK){
						mapCenter = results[0].geometry.location;
						return createMap(configs,styles,markers, mapCenter,boxStyles);
					}else{
						alert("Geocode map center was not successful for the following reason: " + status);
					}
				});
			}else {
				if(markers[0].markerType == 'address'){
					var geocoder = new google.maps.Geocoder();
					geocoder.geocode( { 'address': markers[0].markerValue }, function(results, status) {
						if (status == google.maps.GeocoderStatus.OK){
							mapCenter = results[0].geometry.location;
							return createMap(configs,styles,markers, mapCenter,boxStyles);
						}else{
							alert("Geocode was not successful for the following reason: " + status + '! Map address: ' + markers[0].markerValue);
						}
					})
				}else{
					mapCenterCoordinate = markers[0].markerValue.split(',');
					mapCenter = new google.maps.LatLng(mapCenterCoordinate[0],mapCenterCoordinate[1]);
					return createMap(configs,styles,markers, mapCenter,boxStyles);
				}
			}
		}else{
			if(configs.mapCenterType == 'address'){
				var geocoder = new google.maps.Geocoder();
				geocoder.geocode( { 'address': configs.mapCenterAddress }, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK){
						mapCenter = results[0].geometry.location;
						return createMap(configs,styles,markers, mapCenter,boxStyles);
					}else{
						alert("Geocode was not successful for the following reason: " + status + '! Map address: ' + configs.mapCenterAddress);
					}
				})
			}else{
				mapCenterCoordinate = configs.mapCenterCoordinate.split(',');
				mapCenter = new google.maps.LatLng(mapCenterCoordinate[0],mapCenterCoordinate[1]);
				return createMap(configs,styles,markers, mapCenter,boxStyles);
			}
		}
	}
	/**
	 * Create Map
	 * @param Object configs
	 * @param Array Object styles
	 * @param Array Object markers
	 * @returns {google.maps.Map}
	 */

	function createMap(configs,styles,markers, mapCenter,boxStyles){

		if(configs.enableStyle == 1 ||configs.enableStyle == '1'){
			var stylesArr = [];
			for(var j = 0; j < styles.length; j++)
			{
				var style = {};
				style.stylers = [];
				if(styles[j].featureType!='all'){
					style.featureType = styles[j].featureType;

				}
				if(styles[j].elementType!='all'){
					style.elementType = styles[j].elementType;
				}
				if(styles[j].invertLightness == 'true'){
					style.stylers.push({"invert_lightness":true});
				}
				if(styles[j].visibility){
					style.stylers.push({"visibility":styles[j].visibility});
				}
				if(styles[j].mapColor){
					style.stylers.push({"color":styles[j].mapColor});
				}
				if(styles[j].weight){
					style.stylers.push({"weight":styles[j].weight});
				}
				if(styles[j].hue){
					style.stylers.push({"hue":styles[j].hue});
				}
				if(styles[j].saturation){
					style.stylers.push({"saturation":styles[j].saturation});
				}
				if(styles[j].lightness){
					style.stylers.push({"lightness":styles[j].lightness});
				}
				if(styles[j].gamma){
					style.stylers.push({"gamma":styles[j].gamma});
				}

				stylesArr.push(style);
			}

			if(configs.createNewOrDefault == "applyDefault"){
				if(stylesArr.length != 0){
					var mapOptions = {
						zoom					: configs.zoom,
						zoomControl				: configs.zoomControl,
						scaleControl			: configs.scaleControl,
						mapTypeControl			: configs.mapTypeControl,
						panControl				: configs.panControl,
						streetViewControl		: configs.streetViewControl,
						overviewMapControl		: configs.overviewMapControl,
						draggable				: configs.draggable,
						disableDoubleClickZoom	: configs.disableDoubleClickZoom,
						scrollwheel				: configs.scrollwheel,
						center					: mapCenter,
						mapTypeId				: configs.mapType,
						styles					: stylesArr
					}
				}else{
					var mapOptions = {
						zoom					: configs.zoom,
						zoomControl				: configs.zoomControl,
						scaleControl			: configs.scaleControl,
						mapTypeControl			: configs.mapTypeControl,
						panControl				: configs.panControl,
						streetViewControl		: configs.streetViewControl,
						overviewMapControl		: configs.overviewMapControl,
						draggable				: configs.draggable,
						disableDoubleClickZoom	: configs.disableDoubleClickZoom,
						scrollwheel				: configs.scrollwheel,
						center					: mapCenter,
						mapTypeId				: configs.mapType
					}
				}
			}else{
				var mapOptions = {
					zoom					: configs.zoom,
					zoomControl				: configs.zoomControl,
					scaleControl			: configs.scaleControl,
					mapTypeControl			: configs.mapTypeControl,
					panControl				: configs.panControl,
					streetViewControl		: configs.streetViewControl,
					overviewMapControl		: configs.overviewMapControl,
					draggable				: configs.draggable,
					disableDoubleClickZoom	: configs.disableDoubleClickZoom,
					scrollwheel				: configs.scrollwheel,
					center					: mapCenter,
					mapTypeControlOptions	: {mapTypeIds: [configs.mapType, 'map_style']}
				}
				var styledMap = new google.maps.StyledMapType(stylesArr,{name: configs.styleTitle});
			}
		}else{
			var mapOptions = {
				zoom					: configs.zoom,
				zoomControl				: configs.zoomControl,
				scaleControl			: configs.scaleControl,
				mapTypeControl			: configs.mapTypeControl,
				panControl				: configs.panControl,
				streetViewControl		: configs.streetViewControl,
				overviewMapControl		: configs.overviewMapControl,
				draggable				: configs.draggable,
				disableDoubleClickZoom	: configs.disableDoubleClickZoom,

				scrollwheel				: configs.scrollwheel,
				center					: mapCenter,
				mapTypeId				: configs.mapType
			}
		}
		var map = new google.maps.Map(document.getElementById(configs.cavas_id), mapOptions);

		// set style for map
		if((configs.enableStyle == 1 ||configs.enableStyle == '1') && configs.createNewOrDefault == "createNew" ){
			//Associate the styled map with the MapTypeId and set it to display.
			map.mapTypes.set('map_style', styledMap);
			map.setMapTypeId('map_style');
		}

		/** create marker in map*/
		for (i = 0; i < markers.length; i++) {
			getMarker(configs,markers[i],map ,boxStyles, i);
		}
		currentMapCenter = map.getCenter();
		google.maps.event.addDomListener(map, 'idle', function() {
			currentMapCenter = map.getCenter();
		});
		google.maps.event.addDomListener(window, 'resize', function() {
			map.setCenter(currentMapCenter);
		});
	}

	/**
	 * Get Coordinate of maker and create marker
	 * @param markerSource
	 */
	function getMarker(configs,markerSource, map,boxStyles, i){
		if(markerSource.markerType == 'coordinate'){
			coordinate = markerSource.markerValue.split(',');
			var pos = new google.maps.LatLng(coordinate[0], coordinate[1]);
			if(configs.weather){
				createMakerWithWeather(configs, markerSource, map, pos,boxStyles, i);
			}else{
				createMarker(configs, markerSource, map, pos,boxStyles, i);
			}

		}else{
			var geocoder = new google.maps.Geocoder();
			geocoder.geocode({ 'address': markerSource.markerValue}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					var pos = results[0].geometry.location;
					if(configs.weather){
						createMakerWithWeather(configs, markerSource, map, pos,boxStyles, i);
					}else{
						createMarker(configs, markerSource, map, pos,boxStyles, i);
					}
				}else {
				  // === if we were sending the requests to fast, try this one again and increase the delay
					if (status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT){      
						setTimeout(function(){
							getMarker(configs,markerSource, map,boxStyles, i);
						},3000);
					}
					//alert("Geocode was not successful for the following reason: " + status + '! Map address: ' + markerSource.markerValue);
				}
				
			})
		}
	}

	/**
	 * function create marker for map
	 * @param ObjectJS marker
	 * @param {google.maps.Map}
	 * @param pos: Coordinate of marker
	 */
	function createMarker(configs,markerSource,map, pos,boxStyles, i){
		/** set option of marker */
		var marker, image;
		if(configs.weather && markerSource.weatherInfo && configs.replaceMarkerIcon	){
			image = new google.maps.MarkerImage('http://openweathermap.org/img/w/' + markerSource.weatherInfo.weather[0].icon  + '.png');
		}else {
			if (markerSource.markerIcon == '') {
				image = '';
			} else {
				markerSource.markerIcon = configs.url + markerSource.markerIcon;
				image = {
					//size: new google.maps.Size(21, 32), // size
					//origin: new google.maps.Point(0,0), // origin
					//anchor: new google.maps.Point(10, 32), // anchor 
					url: markerSource.markerIcon // url
				};
			}	 
		}

		if(configs.weather && markerSource.weatherInfo){
			marker = new google.maps.Marker({
				position: pos,
				map: map,
				icon: image,
				title: markerSource.markerTitle	,
				zIndex: i * 10
			});
		}else{
			marker = new google.maps.Marker({
				position: pos,
				map: map,
				icon: image,
				title: markerSource.markerTitle	,
				zIndex: i * 10
			});
		}

		/** create infoWindow */
		if(configs.weather && configs.displayWeatherInfo && markerSource.weatherInfo){
			markerSource.markerInfoWindow += '<p class="weather-info">'
			+ '<img src="' + 'http://openweathermap.org/img/w/' + markerSource.weatherInfo.weather[0].icon  + '.png' + '" alt=""/><br/>'
			+  markerSource.weatherInfo.main.temp + '&deg;' + (configs.temperatureUnit == 'c' ? 'C' : 'F') + '<br/>'
			+  markerSource.weatherInfo.weather[0].main
			+ '</p>';
		}

		if(markerSource.markerInfoWindow){
			if(configs.enableCustomInfoBox == 1 ||configs.enableCustomInfoBox == '1'){
				var pixelOffset = configs.boxPosition.split(',');
				if(configs.closeBoxImage == ''){ configs.closeBoxImage = 'modules/mod_bt_googlemaps/tmpl/images/close.gif';}
				var infoBoxOption = {
					content: markerSource.markerInfoWindow,
					disableAutoPan: false,
					maxWidth: 0,
					pixelOffset: new google.maps.Size(Number(pixelOffset[0]),Number(pixelOffset[1])),
					zIndex: i * 10,
					boxStyle: boxStyles ,
					closeBoxMargin: configs.closeBoxMargin,
					closeBoxURL: configs.url + '/'+ configs.closeBoxImage,
					infoBoxClearance: new google.maps.Size(1, 1),
					isHidden: false,
					pane: "floatPane",
					enableEventPropagation: false
				}

				var infowindow = new InfoBox(infoBoxOption);
			}else{
				var infowindow = new google.maps.InfoWindow({
					content: markerSource.markerInfoWindow
				});
			}
			infowindowData.push(infowindow);
			if(markerSource.markerShowInfoWindow == 1){
				infowindow.open(map,marker);
			}
			google.maps.event.addListener(marker, 'click', function() {
				for(var i = 0; i < infowindowData.length; i++){
					infowindowData[i].close();
				}
				infowindow.open(map,marker);
			});
		}
	}


	function createMakerWithWeather(configs, markerSource, map, pos, boxStyles, i) {
		var requestString = "http://api.openweathermap.org/data/2.5/weather?lat=" + pos.lat() + '&lon=' + pos.lng()
			+ "&cluster=yes&format=json"
			+ (configs.temperatureUnit == 'c' ? '&units=metric' : '')
			+ "&APPID=" + configs.owm_api;

		var request = new XMLHttpRequest();
		request.onload = function() {
			var results = JSON.parse(this.responseText);
			if (results.cod == 200) {
				markerSource.weatherInfo = results;
			}else{
				markerSource.weatherInfo = false;
			}
			createMarker(configs,markerSource,map,pos,boxStyles , i);

		};
		request.open("get", requestString, true);
		request.send();
	}
}