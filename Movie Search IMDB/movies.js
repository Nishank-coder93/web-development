function initialize () {
}

// This is the main send request which retrives the list of movies based on the search result
function sendRequest () {

   var xhr = new XMLHttpRequest();
   var query = encodeURI(document.getElementById("search").value);
   xhr.open("GET", "proxy.php?method=/3/search/movie&query=" + query);
   xhr.setRequestHeader("Accept","application/json");


   xhr.onreadystatechange = function () {
       if (this.readyState == 4) {
          var json = JSON.parse(this.responseText);

          var str = " <tr style='color: white;'> <th> Movie Title </th> <th> Release year </tth> </tr>";
          // Loops through the list of Movie titles and presents it in the form of table
          for (var i = 0; i < json.results.length; i++) {
            // List of Movies in a tabular format that is also clickable and executes the getMovieinfo(id) function that sends ID
            str = str + "<tr class='table_row'>" + "<td> <a onclick='getMovieInfo(" + json.results[i].id+ ");'>" + json.results[i].title + "</a> </td> <td> " + json.results[i].release_date.split("-")[0] + "</td> </tr>";
          }

          document.getElementById("movie-list").innerHTML = str;
       }
   };
   xhr.send(null);
}

// Function that retrieves the Movie information and presents it.
function getMovieInfo(id) {

  var xhr = new XMLHttpRequest();
  xhr.open("GET", "proxy.php?method=/3/movie/" + id);
  xhr.setRequestHeader("Accept","application/json");

  xhr.onreadystatechange = function () {
    if (this.readyState == 4){
      var json = JSON.parse(this.responseText);
          var genres_array = json.genres;
          var genres_string = ""; 

          // Loops through the genres to store in a string variable
          for (var i = 0; i < genres_array.length; i++) {
            genres_string = genres_string + genres_array[i].name + ", ";
          }


          // Places the indvidual elements in specific places respective to thier positions
          document.getElementById("movie_poster_image").src = "https://image.tmdb.org/t/p/w500" + json.poster_path;
          document.getElementById("movie_title").innerText = json.title;
          document.getElementById("movie_summary").innerText = json.overview;
          document.getElementById("movie_genres").innerText = genres_string;
          movieCast(id); // This function retrieves the cast and crew information 
    }
  };
  xhr.send(null);
}

// Reteives the cast and crew information
function movieCast(id) {
  
  var movie_cast = "";
  var xhrm = new XMLHttpRequest();
  xhrm.open("GET", "proxy.php?method=/3/movie/" + id + "/credits");
  xhrm.setRequestHeader("Accept","application/json");
  
  xhrm.onreadystatechange = function () {
    if (this.readyState == 4){
      var jsonc = JSON.parse(this.responseText);
      
      var cast_array = jsonc.cast;

      // Some movies have cast numbers less than 5 this if condition checks for that 
      if( cast_array.length >= 5){
        for (var i = 0; i < 5; i++) {
        movie_cast = movie_cast + cast_array[i].name + ", ";
        }
      }
      else {
        for (var i = 0; i < cast_array.length; i++) {
        movie_cast = movie_cast + cast_array[i].name + ", ";
        }
      }
      document.getElementById("movie_cast").innerText = movie_cast;
    }

  };
  xhrm.send(null);
}