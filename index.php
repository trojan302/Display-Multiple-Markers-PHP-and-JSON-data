<?php

/*   
Display Multiple Markers PHP and JSON data

Created     : Imam Ali Mustofa
Date        : Sunday, January 2015 18:09:40 WIB 2017
Github      : https://github.com/trojan302
Email       : bettadevindonesia@gmail.com
Youtube     : Betta Dev
License     : Read txt file [LICENSE]

Please Subscribe my youtube channel and fork me on Github ^_^ thanks!

*/

// $data = 'file.json' required json data OR you can get from database
$data = 'maps2.json';

// This function to create new Array Assosiative | required $parameter
function getMaps($param){

    // create json data to strings
    $json = file_get_contents($param);
    // decode json to array
    $decode = json_decode($json, true);
    // create new Array Assosiative | $marker = array('title','lat','lng','description','images');
    $marker = array();
    // each data to create new Array Assosiative
    foreach ($decode as $key => $value) {
        $marker[] = array(
            "title" => $value["title"],
            "lat" => $value["lat"],
            "lng" => $value["lng"],
            "description" => $value["description"],
            "images" => $value["images"]
            );
    }
    // return new Array Assosiative
    return $marker;

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Gmaps Multiple Markers</title>
</head>
<body>
    <!-- get Gmaps API -->
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>    
    <script type="text/javascript">
        <?php
        // encode JSON file from getMaps function
        $objJSON = json_encode(getMaps($data));
        // create varible markers
        echo "var markers  = ". $objJSON . ";\n";
        ?>
        // create maps
        window.onload = function () {
            // Maps Options
            var mapOptions = {
                center: new google.maps.LatLng(markers[0].lat, markers[0].lng),
                zoom: 10,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            // Created maps to dvMaps
            var map = new google.maps.Map(document.getElementById("dvMap"), mapOptions);
            // Created infowindow
            var infoWindow = new google.maps.InfoWindow();
            // Created new array to get data from JSON file
            var lat_lng = new Array();
            var latlngbounds = new google.maps.LatLngBounds();

            for (i = 0; i < markers.length; i++) {

                var data = markers[i]
                var myLatlng = new google.maps.LatLng(data.lat, data.lng);
                lat_lng.push(myLatlng);
                var marker = new google.maps.Marker({
                    position: myLatlng,
                    map: map,
                    title: data.title
                });

                latlngbounds.extend(marker.position);
                // Created Content Info | modif to your style
                var contentInfo = "<div style = 'width:400px;min-height:50px'> <h2>" + data.title + "</h2> <img src='"+ data.images +"' style = 'float:left;width:100px;margin:10px;'  /> <br> <small> Latitude : " + data.lat + " <br> Longtitude : " + data.lng + "</small> <p>" + data.description + "</p></div>";
                // Click Function to info window
                (function (marker, data) {
                    google.maps.event.addListener(marker, "click", function (e) {
                        infoWindow.setContent(contentInfo);
                        infoWindow.open(map, marker);
                    });
                })(marker, data);

            }
            // Setting Maps Views
            map.setCenter(latlngbounds.getCenter());
            map.fitBounds(latlngbounds);

        }
    </script>
    <div id="dvMap" style="width: 100%; height: 500px">
    </div>
</body>
</html>