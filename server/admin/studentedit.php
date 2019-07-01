<?php
require('connect.php');
if(isset($_POST['sid'])){
	//post data
	$cid = $_POST['cid']; //previous cid
	$sid = $_POST['sid'];
	$classid = $_POST['classid'];
	$rno= $_POST['rno'];
	$name = strtolower($_POST['name']);
	$username = strtolower($_POST['username']);
	$pass = $_POST['password'];
	$status = $_POST['status'];
	$q = $conn->prepare("SELECT * FROM `classinfo` WHERE `classid` = ?");
	$q->bind_param("i", $classid);
	$q->execute();
	$r = $q->get_result();
	if($r->num_rows == 1){
		$sinfo = $conn->prepare("SELECT * FROM `studentinfo` WHERE `id` = ?");
		$sinfo->bind_param("i", $sid);
		$sinfo->execute();
		$r1 = $sinfo->get_result();
		$gsinfo = $r1->fetch_assoc();
		$oldclassid = $gsinfo['classid'];
		$oldrno = $gsinfo['rollno'];
		$classinf = $conn->prepare("SELECT * FROM classinfo , courseinfo WHERE classinfo.courseid = courseinfo.courseid AND classinfo.classid = '$oldclassid'");
		$classinf->execute();
		$r2 = $classinf->get_result();
		$res = $r2->fetch_assoc();
		$oldtablename = $res['year'].$res['coursename']."-".$res['division'];
		if ($oldclassid == $classid) {
		$editcol = $conn->prepare("ALTER TABLE `$oldtablename` 
		CHANGE `r".$oldrno."` `r".$rno."` TINYINT(1) NOT NULL DEFAULT '0', 
		CHANGE `c".$oldrno."` `c".$rno."` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL");
		$editcol->execute();
		}
		else{
		$classinf = $conn->prepare("SELECT * FROM classinfo , courseinfo WHERE classinfo.courseid = courseinfo.courseid AND classinfo.classid = '$classid'");
		$classinf->execute();
		$r3 = $classinf->get_result();
		$res = $r3->fetch_assoc();
		$tablename = $res['year'].$res['coursename']."-".$res['division'];
		$addcol = $conn->prepare("ALTER TABLE `$tablename`
		ADD `r".$rno."` TINYINT(1) NOT NULL DEFAULT '0' ,
		ADD `c".$rno."` TEXT NULL");
		$addcol->execute();
		$delcol = $conn->prepare("ALTER TABLE `$oldtablename` 
		DROP `r".$rno."` , 
		DROP `c".$rno."`");
		$delcol->execute();
	
		}
		if(empty($_POST['password'])){
		$query = $conn->prepare("UPDATE `studentinfo` SET `name`= ? , `username`= ? , `classid` = ? , `rollno` = ? , `status` = ? WHERE `id`=?");
		$query->bind_param("ssiiii", $name,$username,$classid,$rno,$status,$sid);
		$query->execute();
		header("Location:student.php?cid=".$classid);
		}
		else{
			$encpass = password_hash($pass, PASSWORD_BCRYPT);
			$query = $conn->prepare("UPDATE `studentinfo` SET `name`= ? , `username`= ? ,`password`= ? , `classid` = ? , `rollno` = ? , `status` = ? WHERE `id`=?");
			$query->bind_param("sssiiii", $name,$username,$encpass,$classid,$rno,$status,$sid);
			$query->execute();
			header("Location:student.php?cid=".$classid);
		}
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
<a href="student.php<?php echo '?cid='.$cid;?>">Go Back</a>
</body>
</html>