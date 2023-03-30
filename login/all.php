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


try {
    //$dbh = new PDO("mysql:host=localhost;dbname=$database", $username, $password);
    $dbh = new PDO('mysql:host=' . 
        Config::get('mysql/host') . ';dbname=' . 
        Config::get('mysql/db'), 
        Config::get('mysql/username'), 
        Config::get('mysql/password')
    );
    $stmt = $dbh->prepare("SELECT id,name,address,lat,lng,type from markers"); 
    $stmt->execute();
    $responseData = ["data" => $stmt->fetchAll(PDO::FETCH_ASSOC)];
    // print_r($responseData);
    // print_r($stmt);
    echo json_encode( $responseData );
} catch (PDOException $e) {
    $responseData = ["errors" => [["title"=> "RBDMS Error", "details"=>$e->getMessage() ]]];
    echo json_encode( $responseData );
}
