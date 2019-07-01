<?php
//connect to db
$conn = new mysqli("localhost","root","","qratt");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//set date zone for India.
date_default_timezone_set('Asia/Kolkata');


if(!(isset($_SESSION['adminname']))){
	header('Location: login.php');
	die();
}
?>

