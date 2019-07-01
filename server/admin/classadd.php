<?php
require("connect.php");
if(isset ($_POST['courseid'])){
	$courseid = $_POST['courseid'];
	$y = $_POST['year'];
	$year = strtolower($y);
	$d = $_POST['division'];
	$division = strtolower($d);
	$q = $conn->prepare("SELECT * FROM `courseinfo` WHERE `courseid` = ?");
	$q->bind_param("i", $courseid);
	$q->execute();
	$r = $q->get_result();
	$cname = $r->fetch_assoc();
	$tablename = $year.$cname['coursename']."-".$division;
	if($r->num_rows == 1){
	$ctable =$conn->prepare("CREATE TABLE `$tablename` (
 `attid` int(5) NOT NULL AUTO_INCREMENT,
 `date` date DEFAULT NULL,
 `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `subid` int(3) DEFAULT NULL,
 `status` tinyint(2) NOT NULL DEFAULT '0',
 PRIMARY KEY (`attid`)
) ");
$ctable->execute();
	$query = $conn->prepare("INSERT INTO `classinfo` (`courseid`,`year`,`division`) VALUES (?,?,?)");
	$query->bind_param("iss", $courseid,$year,$division);
	$query->execute();
	// make directories add files
	if (!file_exists('../verification/'.$tablename)) {
    mkdir('../verification/'.$tablename, 0777, true);
    mkdir('../assets/qrcode/'.$tablename, 0777, true);
    
    //copy abc to verification folder
    $newdir = '../verification/'.$tablename.'/asaWsdQssd.php';
    copy('abc.php', $newdir);
   
// redirect

    header("Location:class.php");
}
else{
	echo "verification not successful";
}
	
	}
	else{
		echo 'Invalid Course ID';
	}
}
?>
