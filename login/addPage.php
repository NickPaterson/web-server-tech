<?php require_once 'core/init.php'; 
$user = new User(); 
if(!$user->isLoggedIn()) { 
	Redirect::to('index.php'); 
	die(); 
	// do not output HTML into HTTP response if user not logged in 
} 
?>
<!DOCTYPE html>
<html>
<head>
	
	<title>Add Marker - Leaflet</title>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" />

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>

	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
	
</head>
<body>



<div id="mapid" style="width: 600px; height: 400px;"></div>
<script>
	// Generates a JSON token for CSRF (Cross-Site Request Forgery)
	var token='<?php echo Token::generate(); ?>';
	// declare global just so it is conveniently out of the way in the code listing!
	var html = `<table>
             <tr><td>Name:</td> <td><input type='text' id='name'></td></tr> 
             <tr><td>Address:</td> <td><input type='text' id='address'></td></tr>
             <tr><td>Type:</td> <td><select id='type'> 
             <option value='bar' SELECTED>bar</option> 
             <option value='restaurant'>restaurant</option>
             </select></td></tr> 
             <tr><td></td><td><input type='button' value='Save' onclick='saveData()'></td></tr>
			 </table>`;

	var mymap = L.map('mapid').setView([52.237049, 21.01753], 13);

	L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
		attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
		maxZoom: 18,
		id: 'mapbox/streets-v11',
		tileSize: 512,
		zoomOffset: -1,
		accessToken: 'pk.eyJ1IjoibnBnYiIsImEiOiJjbGZsOGwxZHcwMm9zM3drYXR4bmJ6bmNsIn0.TgKMy-KuBT64X8zlicl23Q'
	}).addTo(mymap);
	
	var marker; // global so we can refer to this later for removal
	
	function onMapClick(e) {
		//alert("You clicked the map at " + e.latlng);
		if(marker) mymap.removeLayer(marker);
		marker = L.marker(e.latlng, {draggable:true}).addTo(mymap);
		marker.bindPopup(html).openPopup();
		//console.table(e.latlng);
	}

	mymap.on('click', onMapClick);
	
	function saveData() {
		var createURL = "add.php";
		var requestData = {};
		requestData.name = $("#name").val();
		requestData.address = $("#address").val();
		requestData.type = $("#type").val();
		requestData.latlng = marker.getLatLng();
		requestData.token = token;
		console.table(requestData);	
		if (requestData.name==="" || requestData.address==="") {
			alert("Name and Address are required");
			return;
		}
		if (requestData.latlng.lat===undefined || requestData.latlng.lng===undefined || 
			requestData.latlng.lat===null || requestData.latlng.lng===null ||
			requestData.latlng.lat===0 || requestData.latlng.lng===0 ||
			isNaN(requestData.latlng.lat) || isNaN(requestData.latlng.lng)) {
			alert("Please click on the map to set the marker");
			return;
		}
		if (requestData.type!=="bar" && requestData.type!=="restaurant") {
			alert("Type must be bar or restaurant");
			return;
		}
		$.ajax({url: createURL, 
			contentType : 'application/vnd.api+json',
			type : 'POST',	
			data: JSON.stringify(requestData)
		})
		.done(function( responseData, textStatus, jqxhr ) {
			if (responseData.data.type==="success") {
				$("input").prop("disabled",true);
				$("select").prop("disabled",true);
				$("input[type=button]").prop("value", "Successfully Saved");
			};
			console.log( createURL + " response " + textStatus + " " +  jqxhr.status + " " + jqxhr.statusText);
		})
		.fail(function( jqxhr, textStatus, error ) {
			console.table(jqxhr); // verbose if you expand object but very useful 
			// console.table(error);
			console.log( createURL +" Request Failed " +  jqxhr.status + " " + jqxhr.statusText);
		})
		.always(function() {
			console.log( createURL +" Whatever" );
		});
	}

</script>
<!-- derived from https://leafletjs.com/examples/quick-start/ -->

<h1>Add Marker - Leaflet</h1>
<table>
	<tr><th>User Interface - HTML/Leaflet/jQuery </th><th>PHP - mostly API endpoint(s)</th></tr>
	<tr><td><a href="mapPage.php">mapPage.php</a> (Read Only)</td><td><a href="all.php">all.php</a> (all map markers)</td></tr>
	<tr><td><a href="addPage.php">addPage.php</a></td><td><a href="add.php">add.php</a> (add map marker)</td></tr>
	<tr><td><a href="deletePage.php">deletePage.php</a></td><td><a href="delete.php">delete.php</a> (delete map marker), <a href="all.php">all.php</a> (all map markers)</td></tr>
	<tr><td><a href="updatePage.php">updatePage.php</a></td><td><a href="move.php">move.php</a> (drag & drop), <a href="update.php">update.php</a> (edit text), <a href="all.php">all.php</a> (all map markers) </td></tr>
	<tr><td>Database Connection Details</td><td><a href="dbinfo.php">dbinfo.php</a> (no output unless error message)</td></tr> 
</table>
</body>
</html>
