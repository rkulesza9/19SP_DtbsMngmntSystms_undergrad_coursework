<?php
  include "dbconfig.php";

  //id, name, address, city, state, zipcode, longitude, lattitude,
  // from CPS3740.Stores (do not display if loc = ?)
  $sql = "select sid, Name, Zipcode, State, city, address, latitude, longitude from Stores where latitude is not NULL and longitude is not NULL";
  $stmt = $conn->prepare($sql);
  $stmt->bind_result($sid,$Name,$Zipcode,$State,$city,$address,$latitude,$longitude);
  $stmt->execute();
  $table_entries = "";

  $stmt->fetch();
  $gmap_markers = "['$sid','$Name','$latitude','$longitude','$address','$city','$State','$Zipcode'] ";
  $table_entries .= <<<HTML
    <tr><td>$sid</td><td>$Name</td><td>$address</td><td>$city</td><td>$State</td><td>$Zipcode</td><td>($latitude,$longitude)</td></tr>
HTML;
  while($stmt->fetch()){
    $table_entries .= <<<HTML
      <tr><td>$sid</td><td>$Name</td><td>$address</td><td>$city</td><td>$State</td><td>$Zipcode</td><td>($latitude,$longitude)</td></tr>
HTML;
    $gmap_markers .= ", ['$sid','$Name','$latitude','$longitude','$address','$city','$State','$Zipcode']";
  }

  $stmt->close();

  $sql = "select avg(latitude), avg(longitude) from Stores where latitude is not NULL and longitude is not NULL";
  $stmt = $conn->prepare($sql);
  $stmt->bind_result($latitude,$longitude);
  $stmt->execute();
  $stmt->fetch();

  $center = $latitude." , ".$longitude;

  $stmt->close();

?>

<html>
  <head>
    <title>Bonus</title>
    <style>
      body {
        text-align:center;
      }
      #center {
        display:inline-block;
      }
      table, tr, td, th {
        border-collapse: collapse;
        border: solid black 1px;
      }
    </style>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>




   <script>

   var i = 0;

   function initialize() {
       var mapOptions = {
               zoom: 4,

               //center: new google.maps.LatLng(39.521741, -96.848224),
               center: new google.maps.LatLng(<?php echo $center; ?>),
               mapTypeId: google.maps.MapTypeId.ROADMAP
      };

      var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

      var infowindow = new google.maps.InfoWindow();

 var markerIcon = {
     scaledSize: new google.maps.Size(80, 80),
     origin: new google.maps.Point(0, 0),
     anchor: new google.maps.Point(32,65),
     labelOrigin: new google.maps.Point(40,33)
 };
       var location;
       var mySymbol;
       var marker, m;
       var MarkerLocations= [
         <?php echo $gmap_markers; ?>
       ];

for (m = 0; m < MarkerLocations.length; m++) {

       location = new google.maps.LatLng(MarkerLocations[m][2], MarkerLocations[m][3]),
       marker = new google.maps.Marker({
     map: map,
     position: location,
     icon: markerIcon,
     label: {
     text: MarkerLocations[m][0] ,
   color: "black",
       fontSize: "16px",
       fontWeight: "bold"
     }
 });

     google.maps.event.addListener(marker, 'click', (function(marker, m) {
       return function() {
         infowindow.setContent("Store Name: " + MarkerLocations[m][1] + "<br>" + MarkerLocations[m][4] + ", " + MarkerLocations[m][5] + ", " + MarkerLocations[m][6] + " " + MarkerLocations[m][7]);
         infowindow.open(map, marker);
       }
     })(marker, m));
}
}
 google.maps.event.addDomListener(window, 'load', initialize);;

 </script>
  </head>
  <body>
    <div id='center'>
      <table>
        <b>The Following Stores are in the database.</b>
        <tr><th>ID</th><th>Name</th><th>Address</th><th>City</th><th>State</th><th>Zipcode</th><th>Location (Latitude,Longitude)</th></tr>
        <?php echo $table_entries; ?>
      </table><br>
      <div id="map-canvas" style="height: 400px; width: 720px;"></div>
      </div>
    </div>
</html>
