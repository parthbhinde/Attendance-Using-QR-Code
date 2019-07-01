<?php
require("connect.php");
if(isset ($_POST['tname'])){
	$sub1 = $_POST['sub1'];
	$sub2 = $_POST['sub2'];
	$sub3 = $_POST['sub3'];
	$sub4 = $_POST['sub4'];
	$sub5 = $_POST['sub5'];
	$teachername = $_POST['tname'];
	$tname = strtolower($teachername);
	$username = strtolower($_POST['username']);
	$pass = $_POST['password'];
	$tright = $_POST['rights'];
	$encpass = password_hash($pass, PASSWORD_BCRYPT);
	$query =$conn->prepare("INSERT INTO `teacherinfo` (`name`,`username`,`password`,`rights`,`sub1`,`sub2`,`sub3`,`sub4`,`sub5`) VALUES (?,?,?,?,?,?,?,?,?)");
	$query->bind_param("ssssiiiii", $tname,$username,$encpass,$tright,$sub1,$sub2,$sub3,$sub4,$sub5);
	$query->execute();
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
	header('Location:teacher.php');
}
?>