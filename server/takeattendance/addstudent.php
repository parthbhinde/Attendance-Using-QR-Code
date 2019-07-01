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

    
    $reason=$_REQUEST['reason'];
    
    $rollno = $_REQUEST['rollno']; 
	
	$blockq = $conn->prepare("SELECT status FROM `studentinfo` WHERE rollno = ?");
	$blockq->bind_param("i",$rollno);
	$blockq->execute();
	$result = $blockq->get_result();
	$creds = $result->fetch_assoc();
	
	//check if blocked
	if($creds['status']==1){
		header("Location: attdata.php?classvalue=".urlencode(base64_encode($_GET['classvalue']))."&a=".urlencode(base64_encode($rollno))."&s=".urlencode(base64_encode('3')));
	}else{

		$rol = "UPDATE ".$atttable." set r".$rollno." ='1' WHERE attid = ?";
		$rea = "UPDATE ".$atttable." set c".$rollno." = ? WHERE attid = ?";
		
		
		//set 1 of given roll no

		if(!($rolq = $conn->prepare($rol))){
			header("Location: attdata.php?classvalue=".urlencode(base64_encode($_GET['classvalue']))."&a=".urlencode(base64_encode($rollno))."&s=".urlencode(base64_encode('2')));
		}
		else{
			$rolq->bind_param("i",$attid);
		}
		
		//set comments
		
		if(!($reaq = $conn->prepare($rea))){
			header("Location: attdata.php?classvalue=".urlencode(base64_encode($_GET['classvalue']))."&a=".urlencode(base64_encode($rollno))."&s=".urlencode(base64_encode('2')));
		}
		else{
			$reaq->bind_param("si",$reason,$attid);
		}
		
		if ($rolq->execute()){
			echo "Success";
			$rolq->close();
			header("Location: attdata.php?classvalue=".urlencode(base64_encode($_GET['classvalue']))."&a=".urlencode(base64_encode($rollno))."&s=".urlencode(base64_encode('1')));
		}
		else {
			echo "Error. Something went wrong:".htmlspecialchars($rolq->error);
			header("Location: attdata.php?classvalue=".urlencode(base64_encode($_GET['classvalue']))."&a=".urlencode(base64_encode($rollno))."&s=".urlencode(base64_encode('0')));
		}	
		if ($reaq->execute()){
			echo "Success";
			$reaq->close();
			header("Location: attdata.php?classvalue=".urlencode(base64_encode($_GET['classvalue']))."&a=".urlencode(base64_encode($rollno))."&s=".urlencode(base64_encode('1')));
		}
		else {
			echo "Error. Something went wrong:".htmlspecialchars($reaq->error);
			header("Location: attdata.php?classvalue=".urlencode(base64_encode($_GET['classvalue']))."&a=".urlencode(base64_encode($rollno))."&s=".urlencode(base64_encode('0')));
		}	
	}		
}
?>
