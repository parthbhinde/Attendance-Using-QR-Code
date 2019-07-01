<?php
require('../connect.php');

if ( isset( $_POST['submit'] ) ) {
   
	$rollno = $_REQUEST['rollno']; 
	$classid = $_REQUEST['classid'];
	
	$blockq = $conn->prepare("UPDATE studentinfo SET status = 1 WHERE rollno = ? AND classid = ? ");
	$blockq->bind_param("ii",$rollno,$classid);
	$blockq->execute();
	
   if ($blockq->affected_rows === 1){
       echo "Success";
	   $blockq->close();
	   header("Location: attdata.php?classvalue=".$_GET['classvalue']."&b=".urlencode(base64_encode($rollno))."&s=".urlencode(base64_encode('1')));
   }
   else {
	    header("Location: attdata.php?classvalue=".$_GET['classvalue']."&b=".urlencode(base64_encode($rollno))."&s=".urlencode(base64_encode('0')));  
   }

}
else {
	die();
}
?>