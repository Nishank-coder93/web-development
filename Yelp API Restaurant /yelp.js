

function initialize () {
}


/* Send request function to retrieve the data from Yelp API */
function sendRequest () {

   var query = encodeURI(document.getElementById("search").value);
   console.log(" The Query is : " + query);
   var xhr = new XMLHttpRequest();

   // Uses Encoded Location from gmap.js and sends the API according to location
   xhr.open("GET", "proxy.php?term=" +  query + "&location= " + encodedLoc + "&limit=10");
   xhr.setRequestHeader("Accept","application/json");

   console.log(xhr);
   // When the data is ready 
   xhr.onreadystatechange = function () {
       if (this.readyState == 4) {
       	  var locArr = [];
          var json = JSON.parse(this.responseText);

          var places = json.businesses; // Stores the array of 10 info in Placess

          // Loops through the array of info to retrieve the Longitute and Latitude 
          // and pushes the coordinates into array locArr 
          for(var i = 0; i < places.length; i++) {
            
            locObj = {
              lat: places[i].location.coordinate.latitude,
              lng: places[i].location.coordinate.longitude
            }

            locArr.push (locObj);
          }

          // sends the array of coordinates into setMarkers() function
          setMarker(locArr,places);  // Sets the Markers on the Map
          setData(places) // Sets the List of Data 
       }
   };
   xhr.send(null);
}


// Set Data funtion puts the data retrieved from Yelp API to it's respective HTML division
function setData(places) {

	var str = " <tr style='color: black;'> <th> Num </th> <th> Image </th> <th> Name </th> <th> Rating Image</th> <th> Rating </th> <th> Snippet </th></tr>";
          // Loops through the list of Movie titles and presents it in the form of table
          for (var i = 0; i < places.length; i++) {

          	var name_of_place = places[i].name;  // Name of th place
			var img_of_url = places[i].image_url;  // URL of the image 
			var rating_of_place = places[i].rating; // Rating of the place
			var rating_url = places[i].rating_img_url_large; // URL of the rating image
			var rating_num = places[i].rating; // The rating number 
			var rating_float = rating_num.toFixed(2); // Converts the rating number into a float
			var snippet = places[i].snippet_text; // Small snippet of the place
			var url_website = places[i].url; // URL to the original Yelp Website 
            // List of Places in a tabular format that is also clickable name to original yelp website
            str = str + "<tr class='table_row'>" + "<td>" + (i+1) + "</td>" 
            + "<td><img src='" + img_of_url + "' ></td>" 
            + "<td> <a href='" + url_website + "' target='_blank'>" + name_of_place + "</a></td>" 
            + "<td><img src='" + rating_url + "' ></td>"
            + "<td>" + rating_float + "</td>" 
            + "<td>" + snippet + "</td>" + "</tr>";
          }

          document.getElementById("yelp-list").innerHTML = str;

}