<?php
require('connect.php');

// eve handles alert conditions
$eve = 0;

if (isset($_SESSION['teacher-username'])){
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
	echo ("Password must contain mixed case letter and number.");
	die();
}
	
	
		
	if($newpass == $cpass){
		//SQL query to fetch details
		$oldq = $conn->prepare("SELECT * FROM `teacherinfo` WHERE username=?");
		$oldq->bind_param("s", $_SESSION['teacher-username']);
		$oldq->execute();
		$result = $oldq->get_result();
		
		//create object of result
		$old = $result->fetch_assoc();
		$oldq->close();
		
		if (password_verify($oldpass, $old['password'])) {
			
			$encpass = password_hash($newpass, PASSWORD_BCRYPT);
			
			$updq = $conn->prepare("UPDATE `teacherinfo` SET `password` = ? WHERE username = ?");
			$updq->bind_param("ss",$encpass, $_SESSION['teacher-username']);
			$updq->execute();
			
			//if succesfull
			if($updq->affected_rows === 1){
				echo ("Password Update Successfull!");
				$updq->close();
			}
			else{
				echo ("Somethin Went Wrong");
			}
		}
		else {
			echo ("Incorrect Old Password");
		}
	}
	else{
		echo "Passwords dont match";
	}
}
else{
	echo ("Somethin Went Wrong");
	die();
} 

}
else{
	echo "Somethin Went Wrong";
}

?>
