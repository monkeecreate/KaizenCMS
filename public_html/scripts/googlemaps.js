//Uses Google Maps API v3
function gMAP(element)
{
	this.map;
	this.geocoder = new google.maps.Geocoder();
	this.goptions;
	this.bounds = new google.maps.LatLngBounds(0, 0);
	var i = 0;
	
	this.element = element;
	
	this.createMap = function() {
		this.map = new google.maps.Map(document.getElementById(this.element), {});
	}
	
	this.setOption = function(option, value) {
		switch(option) {
			case "center":
				this.map.setCenter(new google.maps.LatLng(value.lat, value.lng));
				break;
			case "zoom":
				this.map.setZoom(value);
				break;
			case "maptype":
				this.map.setMapTypeId(value); //ROADMAP, SATELLITE, HYBRID, TERRAIN
				break;
			case "controls":
				this.map.navigationControlOptions(value); //DEFAULT, SMALL, ANDROID, ZOOM_PAN
				break;
		}
	}
	
	this.addPoint = function(point) {
		this.bounds.extend(point);
	}
	
	this.setCenterBounds = function() {
		this.map.setCenter(this.bounds.getCenter());
	}
}
function mapMarker(map)
{
	this.gmap = map;
	this.map = map.map;
	this.marker = new google.maps.Marker({
		map: this.map
	});
	
	this.setByPosition = function(lat, lng) {
		point = new google.maps.LatLng(
			lat,
			lng
		);
		
		this.marker.setPosition(point);
		this.gmap.addPoint(point);
	}
	this.setByAddress = function(address) {
		new google.maps.Geocoder().geocode({
				address: address
			},
			function(results, status) {
				if (status == google.maps.GeocoderStatus.OK && results.length) {
					if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
						this.marker.marker.setPosition(results[0].geometry.location);
		        	}
				} else {
					alert("Geocode was unsuccessful due to: " + status);
				}
			}
		);
	}
	this.setTitle = function(title) {
		this.marker.setTitle(title);
	}
	this.setInfoWindow = function(info) {
		var infowindow = new google.maps.InfoWindow({
			content: info
		});
		
		google.maps.event.addListener(this.marker, 'click', function() {
			infowindow.open(this.map, this);
		});
	}
	this.setIcon = function(icon) {
		this.marker.setIcon(
			new google.maps.MarkerImage(
				icon.url,
				icon.size,
				icon.origin,
				icon.anchor
			)
		);
	}
	this.setShadow = function(shadow) {
		this.marker.setShadow(
			new google.maps.MarkerImage(
				shadow.url,
				shadow.size,
				shadow.origin,
				shadow.anchor
			)
		);
	}
	this.draggable = function(ondrag, enddrag) {
		this.marker.setDraggable(true);
		this.marker.setCursor('move');
		
		if(ondrag != '')
			google.maps.event.addListener(this.marker, 'dragstart', ondrag);
		
		if(enddrag != '')
			google.maps.event.addListener(this.marker, 'dragend', enddrag);
	}
}
function mapPolyline(map)
{
	this.gmap = map;
	this.map = map.map;
	this.polyline = {
		points: new Array,
		stroke: {
			color: "#FF0000",
			opacity: 1.0,
			weight: 1
		},
		polyline: null
	};
	
	this.addPoint = function(lat,lng) {
		point = new google.maps.LatLng(
			lat,
			lng
		);
		
		this.polyline.points.push(point);
		this.gmap.addPoint(point);
	}
	this.setColor = function(color) {
		this.polyline.stroke.color = color;
	}
	this.setOpacity = function(opacity) {
		this.polyline.stroke.opacity = opacity;
	}
	this.setWeight = function(weight) {
		this.polyline.stroke.weight = weight;
	}
	this.draw = function() {
		this.polyline.polyline = new google.maps.Polyline({
			path: this.polyline.points,
			strokeColor: this.polyline.stroke.color,
			strokeOpacity: this.polyline.stroke.opacity,
			strokeWeight: this.polyline.stroke.weight
		});
		this.polyline.polyline.setMap(this.map);
	}
}
function mapPolygon(map)
{
	this.gmap = map;
	this.map = map.map;
	this.polygon = {
		points: new Array(),
		stroke: {
			color: "#FF0000",
			opacity: 1.0,
			weight: 1
		},
		fill: {
			color: "#FF0000",
			opacity: 0.1
		},
		polygon: null
	};
	
	this.addPoint = function(lat,lng) {
		point = new google.maps.LatLng(
			lat,
			lng
		);
		
		this.polygon.points.push(point);
		this.gmap.addPoint(point);
	}
	this.setStrokeColor = function(color) {
		this.polygon.stroke.color = color;
	}
	this.setStrokeOpacity = function(opacity) {
		this.polygon.stroke.opacity = opacity;
	}
	this.setStrokeWeight = function(weight) {
		this.polygon.stroke.weight = weight;
	}
	this.setFillColor = function(color) {
		this.polygon.fill.color = color;
	}
	this.setFillOpacity = function(opacity) {
		this.polygon.fill.opacity = opacity;
	}
	this.draw = function() {
		this.polygon.points.push(this.polygon.points[0]);
		this.polygon.polygon = new google.maps.Polygon({
			path: this.polygon.points,
			strokeColor: this.polygon.stroke.color,
			strokeOpacity: this.polygon.stroke.opacity,
			strokeWeight: this.polygon.stroke.weight,
			fillColor: this.polygon.fill.color,
			fillOpacity: this.polygon.fill.opacity
		});
		this.polygon.polygon.setMap(this.map);
	}
}