<?php
require("connect.php");
if(isset ($_POST['classid'])){
	$classid = $_POST['classid'];
	$rno = $_POST['rno'];
	$sid = $_POST['sid'];
	$name = strtolower($_POST['name']);
	$username = strtolower($_POST['username']);
	$password = $_POST['password'];
	$q = $conn->prepare("SELECT * FROM `classinfo` WHERE `classid` = ?");
	$q->bind_param("i", $classid);
	$q->execute();
    $r = $q->get_result();
	if($r->num_rows == 1){
	$encpass = password_hash($password, PASSWORD_BCRYPT);
	$query = $conn->prepare("INSERT INTO `studentinfo` (`id`,`rollno`,`name`,`username`,`password`,`classid`) VALUES (?,?,?,?,?,?)");
	$query->bind_param("iisssi", $sid,$rno,$name,$username,$encpass,$classid);
	$query->execute();
	$classinf = $conn->prepare("SELECT * FROM classinfo , courseinfo WHERE classinfo.courseid = courseinfo.courseid AND classinfo.classid = ?");
	$classinf->bind_param("i",$classid);
	$classinf->execute();
	$r1 = $classinf->get_result();
	$res = $r1->fetch_assoc();
	$tablename = $res['year'].$res['coursename']."-".$res['division'];
	$addcol = $conn->prepare("ALTER TABLE `$tablename`
	ADD `r".$rno."` TINYINT(1) NOT NULL DEFAULT '0' ,
	ADD `c".$rno."` TEXT NULL");
	$addcol->execute();
	header("Location:student.php?cid=".$classid);
}
else {
	echo "Wrong Class ID";
}
	
}
else{
	echo "Something Went Wrong";
}
?>