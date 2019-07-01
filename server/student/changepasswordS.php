<?php
require('connectS.php');
// eve handles alert conditions
$eve = 0;


if(isset($_POST['oldpass']) and isset($_POST['newpass']))
{
	//Assigning GET values to variables.
	$oldpass = $_POST['oldpass'];
	$newpass = $_POST['newpass'];
	$cpass   = $_POST['cpass']; 

	//check password criterias
	$uppercase = preg_match('@[A-Z]@', $newpass);
	$lowercase = preg_match('@[a-z]@', $newpass);
	$number    = preg_match('@[0-9]@', $newpass);

if(!$uppercase || !$lowercase || !$number || strlen($newpass) < 8) {
	echo ("Password must contain mixed case letter + number and 8 characters minimum.");
	die();
}
	

	
	if($newpass == $cpass){
	//check student id
	$query = $conn->prepare("SELECT password FROM `studentinfo` WHERE rollno = ? AND classid = ?");
	$query->bind_param("ii", $_SESSION['id'], $_SESSION['classid']);
	$query->execute();
	$result = $query->get_result();
	
	//create object of result
	$old = $result->fetch_assoc();
	$query->close();
	
	if (password_verify($oldpass, $old['password'])) {
		
		$encpass = password_hash($newpass, PASSWORD_BCRYPT);
		$update = $conn->prepare("UPDATE `studentinfo` SET `password` = ? WHERE rollno = ? AND classid = ? ");
		$update->bind_param("sii",$encpass, $_SESSION['id'], $_SESSION['classid']);
		$update->execute();
		if ($update->affected_rows === 1){
			echo ("Password Update Successfull!");
		}
		else{
			echo ("Old Password Matched");
		}
	} else {
		echo ("Incorrect Old Password");
	}
}
else{
	echo ("Passwords Dont Match");
}
} 
?>