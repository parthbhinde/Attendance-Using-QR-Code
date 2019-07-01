<?php
require("connect.php");
if(isset ($_POST['classid'])){
	$classid = $_POST['classid'];
	$subjectname = $_POST['subname'];
	$subname = strtolower($subjectname);
	$q = $conn->prepare("SELECT * FROM `classinfo` WHERE `classid` = '$classid'");
	$q->bind_param("i", $classid);
	$q->execute();
	$r = $q->get_result();
	if($r->num_rows == 1){
	$query = $conn->prepare("INSERT INTO `subjectinfo` (`subname`,`classid`) VALUES (?,?)");
	$query->bind_param("si", $subname ,$classid);
	$query->execute();
	header("Location:subject.php");
}
else {
	echo "Wrong Class ID";
}
	
}
else{
	echo "Something Went Wrong";
}
?>
