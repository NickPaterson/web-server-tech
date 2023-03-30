<?php
session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Index Page - creates tracking cookie</title>
</head>
<body>
<h1>Index Page on <?php echo $_SERVER['HTTP_HOST'];?></h1>
This page creates a tracking cookie using PHP.
<ul>
<li><a href="red.php">red</a></li>
<li><a href="orange.php">orange</a></li>
<li><a href="yellow.html">yellow</a></li>
<li><a href="green.php">green</a></li>
<li><a href="blue.php">blue</a></li>
<li><a href="indigo.php">indigo</a></li>
<li><a href="violet.php">violet</a></li>
</ul>
</body>
</html>
