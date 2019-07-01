<?php
require("connect.php");
if(isset ($_POST['subid'])){
	$subid = $_POST['subid'];
	$query = $conn->prepare("DELETE FROM `subjectinfo` WHERE `subjectid` = ? ");
	$query->bind_param("i", $subid);
	$query->execute();
	//Get max ai value
	$gsubid = $conn->prepare ("SELECT MAX(subjectid) FROM `subjectinfo`");	
	$gsubid->execute();
	$r = $gsubid->get_result();			
	while($row = $r->fetch_assoc())
	{
		$subbid = $row['MAX(subjectid)'];
	}
	//Reset auto-increment to max id
	$resetai = $conn->prepare("ALTER TABLE `subjectinfo` AUTO_INCREMENT = $subbid");
	$resetai->execute();
	header("Location:subject.php");
	}
	else{}
?>