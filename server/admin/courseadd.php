<?php
require("connect.php");
if(isset ($_POST['cname'])){
	$coursename = $_POST['cname'];
	$cname = strtolower($coursename);
	$query = $conn->prepare("INSERT INTO `courseinfo` (`coursename`) VALUES (?)");
	$query->bind_param("s", $cname);
	$query->execute();
	header("Location:course.php");
	
}
?>