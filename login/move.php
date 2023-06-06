<?php

//require("dbinfo.php");
require_once 'core/init.php';
$user = new User(); 
header('Content-type: application/vnd.api+json'); 
if(!$user->isLoggedIn()) { 
    $responseData = ["errors" => [["title"=> "User not logged in"]]]; 
    echo json_encode( $responseData ); 
    // not logged in so die and // do not execute SQL and do not output more JSON to HTTP response die(); 
}

header('Content-type: application/vnd.api+json');

// Gets data from JSON data in request 
try {
    $json_str = file_get_contents('php://input');
    $requestData = json_decode($json_str);
    // request data from client cannot be trusted! 
    $token = $requestData->token; 
    //request data from client cannot be trusted! 
    if(!Token::check($token)) { 
        $responseData = ["errors" => [["title"=> "Possible CSRF detected"]]]; 
        // during development distinguish between user/password & CSRF failure 
        // when deployed use same error message for login & CSRF failure 
        echo json_encode( $responseData ); 
        die(); 
        // failed so do not execute SQL or add to HTTP response 
    }
    $id = intval($requestData->id); // none of this request data from the client can be trusted!
    $lat = floatval($requestData->latlng->lat); // none of this request data from the client can be trusted!
    $lng = floatval($requestData->latlng->lng); // none of this request data from the client can be trusted!

    if ( !is_int($id) or !is_numeric($lat) or !is_numeric($lng) ) throw new Exception("incomplete and or incorrect details"); 
    if ( $lat < -90 or $lat > 90 or $lng < -180 or $lng > 180 ) throw new Exception("lat or lng out of range");
    if ( $lat == 0 or $lng == 0 ) throw new Exception("lat or lng cannot be zero");
    if ( is_string($lat) or is_string($lng) ) throw new Exception("lat or lng cannot be string");

} catch (Exception $e) {
    $responseData = ["errors" => [["title"=> "Obtaining JSON Inputs", "details"=>$e->getMessage() ]]];
    echo json_encode( $responseData );
    die();
}  
   

try {
    //$dbh = new PDO("mysql:host=localhost;dbname=$database", $username, $password);
    $dbh = new PDO('mysql:host=' . 
        Config::get('mysql/host') . ';dbname=' . 
        Config::get('mysql/db'), 
        Config::get('mysql/username'), 
        Config::get('mysql/password')
    );
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

