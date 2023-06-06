<?php
/**
 * Created by Chris on 9/29/2014 3:42 PM.
 */

require_once 'core/init.php';

if(Session::exists('home')) {
    echo '<p>' . Session::flash('home'). '</p>';
}

$user = new User(); //Current

if($user->isLoggedIn()) {
?>

    <p>Hello, <a href="profile.php?user=<?php echo escape($user->data()->username);?>"><?php echo escape($user->data()->username); ?></p>

    <ul>
        <li><a href="update.php">Update Profile</a></li>
        <li><a href="changepassword.php">Change Password</a></li>
        <li><a href="logout.php">Log out</a></li>
    </ul>
<?php

    if($user->hasPermission('admin')) {
        echo '<p>You are a Administrator!</p>';
    }

} else {
    echo '<p>You need to <a href="login.php">login</a> or <a href="register.php">register.</a></p>';
}

?>

<table>
	<tr><th>User Interface - HTML/Leaflet/jQuery </th><th>PHP - mostly API endpoint(s)</th></tr>
	<tr><td><a href="mapPage.php">mapPage.php</a> (Read Only)</td><td><a href="all.php">all.php</a> (all map markers)</td></tr>
	<tr><td><a href="addPage.php">addPage.php</a></td><td><a href="add.php">add.php</a> (add map marker)</td></tr>
	<tr><td><a href="deletePage.php">deletePage.php</a></td><td><a href="delete.php">delete.php</a> (delete map marker), <a href="all.php">all.php</a> (all map markers)</td></tr>
	<tr><td><a href="updatePage.php">updatePage.php</a></td><td><a href="move.php">move.php</a> (drag & drop), <a href="update.php">update.php</a> (edit text), <a href="all.php">all.php</a> (all map markers) </td></tr>
	<tr><td>Database Connection Details</td><td><a href="dbinfo.php">dbinfo.php</a> (no output unless error message)</td></tr> 
</table>