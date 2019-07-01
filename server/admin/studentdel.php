<?php
require("connect.php");
if(isset ($_POST['sid'])){
	$sid = $_POST['sid'];
	$rno = $_POST['rno'];
	$classid = $_POST['classid'];
	$query = $conn->prepare("DELETE FROM `studentinfo` WHERE `id` = ?");
	$query->bind_param("i", $sid);
	$query->execute();
	//remove cols
	$classinf =$conn->prepare("SELECT * FROM classinfo , courseinfo WHERE classinfo.courseid = courseinfo.courseid AND classinfo.classid = ?");
	$classinf->bind_param("i", $classid);
	$classinf->execute();
	$r = $classinf->get_result();
	$res = $r->fetch_assoc();
	$tablename = $res['year'].$res['coursename']."-".$res['division'];
	$delcol = $conn->prepare("ALTER TABLE `$tablename` 
	DROP `r".$rno."` , 
	DROP `c".$rno."`");
	$delcol->execute();
	header("Location:student.php?cid=".$classid);
	}
	else{}
?>