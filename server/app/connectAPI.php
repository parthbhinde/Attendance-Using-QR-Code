<?php
//connect to db 
$conn = new mysqli("localhost","root","","qratt");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

