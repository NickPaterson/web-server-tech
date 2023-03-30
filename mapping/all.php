<?php

require("dbinfo.php");

header('Content-type: application/vnd.api+json');


try {
    $dbh = new PDO("mysql:host=localhost;dbname=$database", $username, $password);
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
