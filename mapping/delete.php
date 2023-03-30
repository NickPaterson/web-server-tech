<?php

require("dbinfo.php");

header('Content-type: application/vnd.api+json');

// Gets data from JSON data in request 
try {
    $json_str = file_get_contents('php://input');
    $requestData = json_decode($json_str);
    $id = $requestData->id; // none of this request data from the client can be trusted!
} catch (Exception $e) {
    $responseData = ["errors" => [["title"=> "Obtaining JSON Inputs", "details"=>$e->getMessage() ]]];
    echo json_encode( $responseData );
    die();
}    

try {
    $dbh = new PDO("mysql:host=localhost;dbname=$database", $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // SQL errors will not be silent! 
    $stmt= $dbh->prepare("DELETE FROM markers WHERE id=:id;");
    $stmt->bindParam(':id', $id);
    $stmt->execute(); 
    $deleteCount = $stmt->rowCount();
    if ($deleteCount===0) $responseData = ["data" => ["type"=> "success", "deleteCount"=>$deleteCount, "id"=>$id, "comment" => "no record deleted"  ]];
    else if ($deleteCount===1) $responseData = ["data" => ["type"=> "success", "deleteCount"=>$deleteCount, "id"=>$id, "comment" => "one record deleted" ]];
    else throw new Exception("deleteCount $deleteCount for id $id is not 0 or 1");
    $dbh = null;
    echo json_encode( $responseData );
} catch (Exception $e) {
    $responseData = ["errors" => [["title"=> "RDBMS Error", "details"=>$e->getMessage() ]]];
    echo json_encode( $responseData );
}
