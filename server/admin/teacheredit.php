<?php
require('connect.php');
if(isset($_POST['tid'])){
	//post data
	$tid = $_POST['tid'];
	$teachername = $_POST['tname'];
	$tname = strtolower($teachername);
	$rights = $_POST['rights'];
	$sub1 = $_POST['sub1'];
	$sub2 = $_POST['sub2'];
	$sub3 = $_POST['sub3'];
	$sub4 = $_POST['sub4'];
	$sub5 = $_POST['sub5'];
	$username = strtolower($_POST['username']);
	$pass = $_POST['password'];
	
	if(empty($_POST['password'])){
	$query = $conn->prepare("UPDATE `teacherinfo` SET `name`= ? ,`username`= ?, `rights` = ? , `sub1` = ?,`sub2` = ?,`sub3` = ?,`sub4` = ?,`sub5` = ? WHERE `tid`= ?");
	$query->bind_param("sssiiiiii",$tname,$username,$rights,$sub1,$sub2,$sub3,$sub4,$sub5,$tid);
	$query->execute();
	}
	else{
	$encpass = password_hash($pass, PASSWORD_BCRYPT);
	$query = $conn->prepare("UPDATE `teacherinfo` SET `name`= ?, `username`= ?,`password`= ?, `rights` = ? , `sub1` = ?,`sub2` = ?,`sub3` = ?,`sub4` = ?,`sub5` = ? WHERE `tid`= ?");
	$query->bind_param("ssssiiiiii",$tname,$username,$encpass,$rights,$sub1,$sub2,$sub3,$sub4,$sub5,$tid);
	$query->execute();
	}
	//making 0 values null
	$adnul= $conn->prepare("UPDATE `teacherinfo` set `sub1` = null WHERE `sub1`= 0");
    $adnul2=$conn->prepare("UPDATE `teacherinfo` set `sub2` = null WHERE `sub2`= 0");
    $adnul3=$conn->prepare("UPDATE `teacherinfo` set `sub3` = null WHERE `sub3`= 0");
    $adnul4=$conn->prepare("UPDATE `teacherinfo` set `sub4` = null WHERE `sub4`= 0");
    $adnul5=$conn->prepare("UPDATE `teacherinfo` set `sub5` = null WHERE `sub5`= 0");
	$adnul->execute();
	$adnul2->execute();
	$adnul3->execute();
	$adnul4->execute();
	$adnul5->execute();
	header("location:teacher.php");
	
	
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