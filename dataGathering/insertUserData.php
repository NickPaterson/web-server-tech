<!DOCTYPE HTML>
<html>
<head>
<title>PHP Script Using MySQL and PDO</title>
</head>
<body>
<h1>PHP Script Using MySQL and PDO</h1>
<p>This version uses a MySQL back end but that's invisible to web site visitors.</p>

<?php 
// RDBMS coonection paramters
$database = "data_gathering";
$username = "nick";
$password  = "21)A.ocM(8tGJFqx";


try {
	$dbh = new PDO("mysql:host=localhost;dbname=$database", $username, $password); // Connecting, selecting database
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // SQL errors will not be silent

    $stmt = $dbh->prepare("INSERT INTO userData (fullname, bannerID, travelMode) VALUES (:fullname, :bannerID, :travelMode); ");
    $stmt->bindParam(':fullname', $fullname);
    $stmt->bindParam(':bannerID', $bannerID);
    $stmt->bindParam(':travelMode', $travelMode);

    // Retrieve form data 
    $fullname = $_POST['fullname'];
    $bannerID = $_POST['bannerID'];
    $travelMode = $_POST['travelMode'];
    
    $stmt->execute();

	$stmt= $dbh->prepare( "SELECT fullname, bannerID, travelMode FROM userData;"); // prepare for execution & return a statement object
	$stmt->bindColumn( 'fullname', $fullnameSel ); // Bind named column to a named PHP variable
	$stmt->bindColumn( 'bannerID', $bannerIDSel ); 
    $stmt->bindColumn( 'travelMode', $travelModeSel );
	$stmt->execute();
	// echo "<br>rowCount: ",  $stmt->rowCount(), "<br>"; // USEFUL FOR DIAGNOSTICS AFTER EACH EXECUTE, REMOVE OR COMMENT OUT WHEN TESTING COMPLETE
	// echo "<br>debugDumpParams:<br><pre>" , $stmt->debugDumpParams(), "</pre>"; // USEFUL FOR DIAGNOSTICS, REMOVE OR COMMENT OUT WHEN TESTING COMPLETE
	while ( $stmt->fetch(PDO::FETCH_BOUND) ){ // fetch one row of results binding coulmn values to PHP variables
		echo "<p>$bannerIDSel<br/>$fullnameSel<br/>$travelModeSel</p>";
	}
	$dbh = null; // Closing connection after success
} catch (PDOException $e) {
	$dbh = null; // Closing connection if some error has occurred
	print "Error!: " . $e->getMessage() . "<br/>"; // WARNING - error messages are potential security weakness on production sites
	print "PHP Line Number: " . $e->getLine() . "<br/>"; 
	print "PHP File: " . $e->getFile() . "<br/>";
	die();
}

	header("Location: /dataGathering/singleData.php");
	die();
?>
</body>
</html>