<?php
require('../connect.php');

foreach($_GET as $loc=>$item)
	$_GET[$loc] = base64_decode(urldecode($item));
		
$values = explode("-",$_GET['classvalue']);

//insert values of post
$year = strtolower($values[0]);
$coursename = strtolower($values[1]);
$division = strtolower($values[2]);
$subid = $values[3];
$classid = $values[4];

$atttable = "`".$year.$coursename."-".$division."`";

//get attendance id
$query= "SELECT MAX(attid) FROM ".$atttable;
$attidq = $conn->prepare($query);
$attidq->execute();
$result = $attidq->get_result();

//create assoc array of result
$row = $result->fetch_assoc();
$attidq->close();

$attid = $row['MAX(attid)'];

if ( isset( $_POST['submit'] ) ) {

    $rollno = $_REQUEST['rollno']; 
	
	//set 0 of given roll no
    $sql = "UPDATE ".$atttable." set r".$rollno." ='0' WHERE attid = ? ";	
	$delq = $conn->prepare($sql);
	$delq->bind_param("i",$attid);
	$delq->execute();

	//if succesfull
	if($delq->affected_rows === 1){
		$delq->close();
		echo "Success";
		header("Location: attdata.php?classvalue=".urlencode(base64_encode($_GET['classvalue']))."&r=".urlencode(base64_encode($rollno))."&s=".urlencode(base64_encode('1')));
	}
	else{
		echo "Error. Something went wrong:".htmlspecialchars($delq->error);
	}	
}
else{
	die();
}
?>