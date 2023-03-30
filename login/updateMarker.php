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
    $token = $requestData->token; 
    // request data from client cannot be trusted! 
    if(!Token::check($token)) { 
        $responseData = ["errors" => [["title"=> "Possible CSRF detected"]]]; 
        // during development distinguish between user/password & CSRF failure 
        // when deployed use same error message for login & CSRF failure 
        echo json_encode( $responseData ); 
        die(); 
        // failed so do not execute SQL or add to HTTP response 
    }
    $id = intval($requestData->id); // none of this request data from the client can be trusted!
    $name = $requestData->name; // none of this request data from the client can be trusted!
    $address = $requestData->address; // none of this request datafrom the client can be trusted!
    $type = $requestData->type; // none of this request data from the client can be trusted!
    $lat = floatval($requestData->latlng->lat); // none of this request data from the client can be trusted!
    $lng = floatval($requestData->latlng->lng); // none of this request data from the client can be trusted!
    if ( !is_int($id) or empty($name) or empty($address) or empty($type) or !is_numeric($lat) or !is_numeric($lng) ) throw new Exception("incomplete and or incorrect details");
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
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    $stmt= $dbh->prepare("UPDATE markers SET name = :name, address = :address, type = :type, lat = :lat, lng = :lng WHERE id = :id;");
    print_r($dbh);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':lat', $lat);
    $stmt->bindParam(':lng', $lng);
    $stmt->bindParam(':type', $type);
    $stmt->execute(); 
    $updateCount = $stmt->rowCount();
	// rowCount zero if record unchanged because user decides not to change anything 
	// or no record with the id exists
    $updateCount = $stmt->rowCount();  
	if ($updateCount===0) $responseData = ["data" => ["type"=> "success", "updateCount"=>$updateCount, "id"=>$id, "comment"=>"no record changed" ]];
	else if ($updateCount===1) $responseData = ["data" => ["type"=> "success", "updateCount"=>$updateCount, "id"=>$id, "comment"=>"one record changed" ]];
    else throw new Exception("updateCount $updateCount for id $id is not 0 or 1");
    $dbh = null;
    echo json_encode( $responseData );
} catch (Exception $e) {
    $responseData = ["errors" => [["title"=> "RDBMS Error", "details"=>$e->getMessage() ]]];
    echo json_encode( $responseData );
}
