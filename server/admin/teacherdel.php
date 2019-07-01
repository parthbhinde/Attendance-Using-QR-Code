<?php
require("connect.php");
if(isset ($_POST['tid'])){
	$tid = $_POST['tid'];
	$query = $conn->prepare("DELETE FROM `teacherinfo` WHERE `tid` = ? ");
	$query->bind_param("i", $tid);
	$query->execute();
	//Get max ai value
	$tsubid = $conn->prepare ("SELECT MAX(tid) FROM `teacherinfo`");	
	$tsubid->execute();
	$r = $tsubid->get_result();			
	while($row = $r->fetch_assoc())
	{
		$ttid = $row['MAX(tid)'];
	}
	//Reset auto-increment to max id
	$resetai =$conn->prepare("ALTER TABLE `teacherinfo` AUTO_INCREMENT = $ttid");
	$resetai->execute();
	header("Location:teacher.php");
	}
	else{}
?>