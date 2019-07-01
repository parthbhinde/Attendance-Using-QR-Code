<?php
require('connect.php');
if(isset($_POST['classid'])){
	//post data
	$subid = $_POST['subjectid'];
	$classid = $_POST['classid'];
	$subjectname= $_POST['subjectname'];
	$subname = strtolower($subjectname);
	$q = $conn->prepare("SELECT * FROM `classinfo` WHERE `classid` = '$classid'");
	$q->bind_param("i", $classid);
	$q->execute();
	$r = $q->get_result();
	if($r->num_rows == 1){
		$query = $conn->prepare("UPDATE `subjectinfo` SET `subname`= '$subname' , `classid` = '$classid' WHERE `subjectid`=$subid");
		$query->execute();
		header("location:subject.php");
	}
	else{
		echo 'Invalid Class ID ';
	}
}
else{
	echo "Something Went Wrong ";
}
?>
<html>
<body>
<a href="subject.php">Go Back</a>
</body>
</html>