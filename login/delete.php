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
    $id = $requestData->id; // none of this request data from the client can be trusted!
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
