//Uses Google Maps API v3
function gMAP(element)
{
	this.map;
	this.geocoder = new google.maps.Geocoder();
	this.goptions;
	this.markers = new Array();
	var i = 0;
	
	this.element = element;
	
	this.createMap = function() {
		this.map = new google.maps.Map(document.getElementById(this.element), {});
	}
	
	this.setOption = function(option, value) {
		switch(option) {
			case "center":
				this.map.set_center(new google.maps.LatLng(value.lat, value.lng));
				break;
			case "zoom":
				this.map.set_zoom(value);
				break;
			case "maptype":
				this.map.set_mapTypeId(value); //ROADMAP, SATELLITE, HYBRID, TERRAIN
				break;
			case "controls":
				this.map.navigationControlOptions(value); //DEFAULT, SMALL, ANDROID, ZOOM_PAN
				break;
		}
	}
	
	this.addMarkerByPosition = function(lat, lng, title, info, icon) {
		position = new google.maps.LatLng(lat, lng);
		
		var marker = new google.maps.Marker({
			position: position, 
			map: this.map
		});
		
		return this.addMarker(marker, title, info, icon);
	}
	
	this.addMarkerByAddress = function(address, title, info, icon) {
		var marker = new google.maps.Marker({
			map: this.map
		});
		
		this.geocoder.geocode( { address: address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK && results.length) {
				if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
					marker.set_position(results[0].geometry.location);
		        }
			} else {
				alert("Geocode was unsuccessful due to: " + status);
			}
		});
		
		return this.addMarker(marker, title, info, icon);
	}
	
	this.addMarker = function(marker, title, info, icon) {
		if(typeof(title) != "undefined")
			marker.set_title(title);
		
		if(typeof(info) != "undefined") {
			infowindow = new google.maps.InfoWindow({
				content: info
			});
		
			//google.maps.event.addListener(marker, 'click', function() {
				infowindow.open(this.map, marker)
			//});
		}
		
		if(typeof(icon) != "undefined") {
			if(typeof(icon.icon) != "undefined")
				marker.set_icon(new google.maps.MarkerImage(icon.icon.url, icon.icon.size, icon.icon.origin, icon.icon.anchor));
			if(typeof(icon.shadow) != "undefined")
				marker.set_shadow(new google.maps.MarkerImage(icon.shadow.url, icon.shadow.size, icon.shadow.origin, icon.shadow.anchor));
		}
		
		this.markers[i] = marker;
		i++;
		
		return marker;
	}
	
	this.setCenterBounds = function() {
		bounds = new google.maps.LatLngBounds(0, 0);
		
		for(x=0;x<this.markers.length;x++) {
			bounds.extend(this.markers[x].get_position());
		}
		
		this.map.set_center(bounds.getCenter());
	}
}