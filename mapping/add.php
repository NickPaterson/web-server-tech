<?php

require("dbinfo.php");

header('Content-type: application/vnd.api+json');

// Gets data from JSON data in request 
try {
    $json_str = file_get_contents('php://input');
    $requestData = json_decode($json_str);
    $name = $requestData->name; // none of this request data from the client can be trusted!
    $address = $requestData->address; // none of this request datafrom the client can be trusted!
    $type = $requestData->type; // none of this request data from the client can be trusted!
    $lat = floatval($requestData->latlng->lat); // none of this request data from the client can be trusted!
    $lng = floatval($requestData->latlng->lng); // none of this request data from the client can be trusted!
    if (  empty($name) or empty($address) or empty($type) or !is_numeric($lat) or !is_numeric($lng) ) throw new Exception("incomplete and or incorrect details");
} catch (Exception $e) {
    $responseData = ["errors" => [["title"=> "Obtaining JSON Inputs", "details"=>$e->getMessage() ]]];
    echo json_encode( $responseData );
    die();
}    

try {
    $dbh = new PDO("mysql:host=localhost;dbname=mapping", $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    $stmt= $dbh->prepare("INSERT INTO markers (id, name, address, lat, lng, type ) VALUES (NULL, :name, :address, :lat, :lng, :type);");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':lat', $lat);
    $stmt->bindParam(':lng', $lng);
    $stmt->bindParam(':type', $type);
    $stmt->execute(); 
	$insertCount = $stmt->rowCount();  
	if ($insertCount===1) $responseData = ["data" => ["type"=> "success", "insertCount"=>$insertCount, "id"=>$dbh->lastInsertId(), "comment"=>"one record inserted" ]];
    else throw new Exception("insertCount $insertCount is not 1");
    $dbh = null;
    echo json_encode( $responseData );
} catch (PDOException $e) {
    $responseData = ["errors" => [["title"=> "RDBMS Error", "details"=>$e->getMessage() ]]];
    echo json_encode( $responseData );
}
