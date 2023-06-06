<?php 
	require_once 'core/init.php'; 
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
	
	<title>Update and Move Markers - Leaflet</title>

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
	var token ='<?php echo Token::generate();?>';
    //token += "Nick";
	var markers = [];
	function renderMarkers() {
		var allURL = "all.php"
		$.ajax({url: allURL, 
			contentType : 'application/vnd.api+json',
			type : 'GET'
		})
		.done(function( responseData, textStatus, jqxhr ) {
			console.table(responseData);
			$.each(responseData.data,function(index,value){
				//console.log(index,value);
				markers[value.id] = L.marker([value.lat,value.lng], {draggable:true}).addTo(mymap);
				var html = `<table id='marker${value.id}'>
				<tr><td>Name:</td> <td><input class='name' value='${value.name}'></td> </tr> 
				<tr><td>Address:</td> <td><input class='address' value='${value.address}'></td> </tr>
                <tr><td>Type:</td> <td><select class='type'> 
                <option value='bar' ${value.type==="bar"?"SELECTED":""} >bar</option> 
                <option value='restaurant'  ${value.type==="restaurant"?"SELECTED":""} >restaurant</option>
                </select></td></tr> 
                <tr><td></td> <td><input type='button' onclick='updateMarker(${value.id})' value='Update'></td></tr> 
				</table>`;
                markers[value.id].on('dragend', function(e) {
                    console.log(`marker dragend event ${value.id}`);
                    moveMarker(value.id);
                });
				markers[value.id].bindPopup(html);
			});
			console.log( allURL + " response " + textStatus + " " +  jqxhr.status + " " + jqxhr.statusText);
		})
		.fail(function( jqxhr, textStatus, error ) {
			console.table(jqxhr); // verbose if you expand object but very useful 
			// console.table(error);
			console.log( allURL +" Request Failed " +  jqxhr.status + " " + jqxhr.statusText);
		})
		.always(function() {
			console.log( allURL +" Whatever" );
		});
	}

	var mymap = L.map('mapid').setView([52.237049, 21.01753], 13);

	L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
		attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
		maxZoom: 18,
		id: 'mapbox/streets-v11',
		tileSize: 512,
		zoomOffset: -1,
		accessToken: 'pk.eyJ1IjoibnBnYiIsImEiOiJjbGZsOGwxZHcwMm9zM3drYXR4bmJ6bmNsIn0.TgKMy-KuBT64X8zlicl23Q'
	}).addTo(mymap);

	mymap.whenReady(renderMarkers);

    function updateMarker(id) {
		var updateURL = "updateMarker.php"
		var requestData = {};
		requestData.id = id;
        requestData.name = $(`#marker${id} .name`).val();
		requestData.address = $(`#marker${id} .address`).val();
		//requestData.address = "padding".padEnd(1000, "Nick");
		requestData.type = $(`#marker${id} .type`).val();
		requestData.latlng = markers[id].getLatLng();
		requestData.token = token;
		console.table(requestData);	
		if (requestData.name.length > 80) {
			alert("Name too long");
			return;
		}
		if (requestData.address.length > 80) {
			alert("Address too long");
			return;
		}
		$.ajax({url: updateURL, 
			contentType : 'application/vnd.api+json',
			type : 'POST',	
			data: JSON.stringify(requestData)
		})
		.done(function( responseData, textStatus, jqxhr ) {
            console.log(responseData);
			if (responseData.data) {
                console.log(`Marker ${responseData.data.id} updated with ${responseData.data.type}`)
                var html = `<table id='marker${requestData.id}'>
				<tr><td>Name:</td> <td><input class='name' value='${requestData.name}'></td> </tr> 
				<tr><td>Address:</td> <td><input class='address' value='${requestData.address}'></td> </tr>
                <tr><td>Type:</td> <td><select class='type'> 
                <option value='bar' ${requestData.type==="bar"?"SELECTED":""} >bar</option> 
                <option value='restaurant'  ${requestData.type==="restaurant"?"SELECTED":""} >restaurant</option>
                </select></td></tr> 
                <tr><td></td> <td><input type='button' onclick='updateMarker(${requestData.id})' value='Update'></td></tr> 
				</table>`;
				markers[requestData.id].bindPopup(html);
            } else if(responseData.errors) console.log(responseData.errors);
            else console.log("Response not recognised as success and not recognised as errors, this should never happen!");
            console.log( updateURL + " response " + textStatus + " " +  jqxhr.status + " " + jqxhr.statusText);
		})
		.fail(function( jqxhr, textStatus, error ) {
			console.table(jqxhr); // verbose if you expand object but very useful 
			// console.table(error);
			console.log( updateURL +" Request Failed " +  jqxhr.status + " " + jqxhr.statusText);
		})
		.always(function() {
			console.log( updateURL +" Whatever" );
		});
	}
	
    function moveMarker(id) {
		var moveURL = "move.php"
		var requestData = {};
		requestData.id = id;
		requestData.latlng = markers[id].getLatLng();
		requestData.token = token;
		console.table(requestData);	
		if (requestData.latlng.lat === "" || requestData.latlng.lng === "") return;
		
		$.ajax({url: moveURL, 
			contentType : 'application/vnd.api+json',
			type : 'POST',	
			data: JSON.stringify(requestData)
		})
		.done(function( responseData, textStatus, jqxhr ) {
            console.log(responseData);
			if (responseData.data) console.log(`Marker ${responseData.data.id} moved with ${responseData.data.type}`)
            else if(responseData.errors) console.log(responseData.errors);
            else console.log("Response not recognised as success and not recognised as errors, this should never happen!");
            console.log( moveURL + " response " + textStatus + " " +  jqxhr.status + " " + jqxhr.statusText);
		})
		.fail(function( jqxhr, textStatus, error ) {
			console.table(jqxhr); // verbose if you expand object but very useful 
			// console.table(error);
			console.log( moveURL +" Request Failed " +  jqxhr.status + " " + jqxhr.statusText);
		})
		.always(function() {
			console.log( moveURL +" Whatever" );
		});
	}
	

</script>
<!-- derived from https://leafletjs.com/examples/quick-start/ -->
<h1>Update and Move Markers - Leaflet</h1>
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
