var map;
var markers = [];
var cenloc = {lat: 32.75, lng: -97.13};
var encodedLoc;

// This functions initializes at first load of web page
function initMap() {
	// The initial location the Map is set in
        
        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 16,  // Zoom to level 16
          center: cenloc
        });

        var lati = cenloc.lat;
        var longi = cenloc.lng;
        //getLocation(lati,longi);

        map.addListener('bounds_changed', function() {
   		// calculate the new bounds
   		lati = map.getBounds().f.b;
   		longi = map.getBounds().b.b;

   		// Send the new bounds to get the location 
   		getLocation(lati,longi);
  		});
        
}

// This gets the location from the Longitute and Latitude 
function getLocation(lat,lng) {
	var xhr = new XMLHttpRequest();
   xhr.open("GET", "https://maps.googleapis.com/maps/api/geocode/json?latlng=" + lat +"," + lng +"&key=AIzaSyB9CRKX6cyb-pOFAVkQ-vZ52sjYKoYEHj4"); //location=Arlington+Texas
   xhr.setRequestHeader("Accept","application/json");

   // When the data is ready gets the array with information about location 
   xhr.onreadystatechange = function () {
       if (this.readyState == 4) {

          var json = JSON.parse(this.responseText);

          // Retrieves the City and State
          var City = json.results[2].address_components[0].long_name;
          var State = json.results[6].address_components[0].long_name;

          // Encodes the City and State and updates the location
          encodedLoc = encodeURI(City + " " + State);
          encodedLoc = encodedLoc.replace(/%20/g,"+");

       }
   };
   xhr.send(null);
}

// Set Markers function to place the Markers on the Google Map respective to places location
// Locations parameter contains the array of Location
function setMarker(locations,places){
	var labels = ['1','2','3','4','5','6','7','8','9','10'];
	// If the markers are already present on the map this clears it
	if(markers.length > 0){
		for (let i = 0; i < markers.length; i++) {
		 markers[i].setMap(null);
		};

		markers=[];

	}

	// This for loop pushes the Marker into an array of marker 
		for (let i = 0; i < locations.length; i++) {
		markers.push(new google.maps.Marker({
		position: locations[i],
		map: map,
		label: labels[i],
		title: places[i].name,
		animation: google.maps.Animation.DROP
		}))

		// Set's Marker on the Map
		markers[i].setMap(map);	

		// Click event listener on the individual Marker 
		// On click it set's the zoom to 16 and makes the center of the map to particular marker
		markers[i].addListener('click', function() {
	    map.setZoom(16);
	    map.setCenter(markers[i].getPosition());
	  		});
		};


}