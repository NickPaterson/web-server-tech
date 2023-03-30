<?php

require("dbinfo.php");

header('Content-type: application/vnd.api+json');

// Gets data from JSON data in request 
try {
    $json_str = file_get_contents('php://input');
    $requestData = json_decode($json_str);
    $id = intval($requestData->id); // none of this request data from the client can be trusted!
    $lat = floatval($requestData->latlng->lat); // none of this request data from the client can be trusted!
    $lng = floatval($requestData->latlng->lng); // none of this request data from the client can be trusted!
    if ( !is_int($id) or !is_numeric($lat) or !is_numeric($lng) ) throw new Exception("incomplete and or incorrect details");
} catch (Exception $e) {
    $responseData = ["errors" => [["title"=> "Obtaining JSON Inputs", "details"=>$e->getMessage() ]]];
    echo json_encode( $responseData );
    die();
}   
   

try {
    $dbh = new PDO("mysql:host=localhost;dbname=$database", $username, $password);
    $stmt= $dbh->prepare("UPDATE markers SET lat = :lat, lng = :lng WHERE id = :id;");
    //print_r($stmt->debugDumpParams());
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':lat', $lat);
    $stmt->bindParam(':lng', $lng);
    $stmt->execute(); 
    $moveCount = $stmt->rowCount();
	// rowCount zero if record unchanged because user decides not to change anything 
	// or no record with the id exists
    $moveCount = $stmt->rowCount();  
	if ($moveCount===0) $responseData = ["data" => ["type"=> "success", "moveCount"=>$moveCount, "id"=>$id, "comment"=>"no record changed" ]];
	else if ($moveCount===1) $responseData = ["data" => ["type"=> "success", "moveCount"=>$moveCount, "id"=>$id, "comment"=>"one record changed" ]];
    else throw new Exception("moveCount $moveCount for id $id is not 0 or 1");
    $dbh = null;
    echo json_encode( $responseData );
} catch (Exception $e) {
    $responseData = ["errors" => [["title"=> "RDBMS Error", "details"=>$e->getMessage() ]]];
    echo json_encode( $responseData );
}

