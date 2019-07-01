<?php
session_start();

require('../connect.php');

if(isset($_POST['classvalue'])){

	$values = explode("-",$_POST['classvalue']);

	//insert values of post
	$year = strtolower($values[0]);
	$coursename = strtolower($values[1]);
	$division = strtolower($values[2]);
	$subid = $values[3];
	$classid = $values[4];

	$tablename = $year.$coursename."-".$division;
	//echo $tablename.$subid;
	$date = date('Y-m-d');
	$timestamp = date('Y-m-d h:i:s', time());

	//del previous entry if status = 0
	$query = "DELETE FROM `".$tablename."` WHERE `status` = 0  AND `subid` = ? ";
	$delq = $conn->prepare($query);
	$delq->bind_param("i",$subid);
	$delq->execute();

	//empty entry
	$query = "INSERT INTO `".$tablename."`(`date`,`time`, `subid`, `status` ) VALUES (?,?,?,0)";
	$insq = $conn->prepare($query);
	$insq->bind_param("ssi",$date,$timestamp,$subid);
	$insq->execute();

	//create empty tablen
	$a = "DROP TABLE IF EXISTS `".$tablename."-multi`";
	$dropq = $conn->prepare($a);
	$dropq->execute();

	$q = "CREATE TABLE `".$tablename."-multi` (id INT(3) UNSIGNED AUTO_INCREMENT PRIMARY KEY,sid INT(3) NOT NULL,`name` VARCHAR(50),ip VARCHAR(25) ,`user-agent` VARCHAR(100),`uid` VARCHAR(10), `cid` VARCHAR(10) , `imei` VARCHAR(25) ,UNIQUE (sid))";
	$create = $conn->prepare($q);
	$create->execute();

	//redirect
	$url = "display.php?classvalue=".urlencode(base64_encode($_POST['classvalue']));
	header('Location: '.$url);


}
else{
	die();
}

?>
