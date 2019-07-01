<?php
	header("Content-type:application/json");
	require('connectAPI.php');
	

	if(isset($_POST['username']) and isset($_POST['oldpass']) and isset($_POST['newpass']) and $isset = $_POST['cpass'])
	{
		$username = $_POST['username'];
		$oldpass = $_POST['oldpass'];
		$newpass = $_POST['newpass'];
		$cpass   = $_POST['cpass']; 

		//check password criterias
		$uppercase = preg_match('@[A-Z]@', $newpass);
		$lowercase = preg_match('@[a-z]@', $newpass);
		$number    = preg_match('@[0-9]@', $newpass);

		if(!$uppercase || !$lowercase || !$number || strlen($newpass) < 8) {
			$output = array(
				'response' => false,
				'message' => "Password must contain mixed case letter + number and 8 characters minimum."
			);
			print(json_encode($output)); 
			die();
		}
	
		if($newpass == $cpass){
			//check student id
			$query = $conn->prepare("SELECT password FROM `studentinfo` WHERE username = ? ");
			$query->bind_param("s", $username);
			$query->execute();
			$result = $query->get_result();
			
			//create object of result
			$old = $result->fetch_assoc();
			$query->close();
			
			if (password_verify($oldpass, $old['password'])) {
				
				$encpass = password_hash($newpass, PASSWORD_BCRYPT);
				$update = $conn->prepare("UPDATE `studentinfo` SET `password` = ? WHERE username = ? ");
				$update->bind_param("ss",$encpass, $username);
				$update->execute();
				if ($update->affected_rows === 1){
					$output = array(
						'response' => true,
						'message' => "Password Update Successfull!"
					);
				}
				else{
					$output = array(
						'response' => false,
						'message' =>"Old Password Matched"
					);
				}
			} 
			else {
				$output = array(
					'response' => false,
					'message' => "Incorrect Old Password"
				);
			}
		}
		else{
			$output = array(
				'response' => false,
				'message' => "Passwords Dont Match"
			);
		}
	}
	else{
		$output = array(
			'response' => false,
			'message' => "Inavlid Params"
		);
	} 
	print(json_encode($output)); 
	die();
?>