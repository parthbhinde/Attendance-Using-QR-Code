<?php
header("Content-type:application/json");
require('connectAPI.php');
if(isset($_POST['username']) and isset($_POST['password']))
{
	//Assigning GET values to variables.
	$susername = $_POST['username'];
    $password = $_POST['password'];
    //SQL query to fetch details
	$stmt = $conn->prepare("SELECT * FROM `studentinfo` WHERE username= ?");
	$stmt->bind_param("s", $susername);
	$stmt->execute();
	
	//get result of query
	$result = $stmt->get_result();
 
	if($result->num_rows == 1)
	{
		$creds=$result->fetch_assoc();
		
		if (password_verify($password, $creds['password'])) {
            $arr = array('status'=>"Successfull");
            echo json_encode($arr);
            die();
		} 
		else {
			$arr = array('status'=>"error");
            echo json_encode($arr);
            die();
		}
		
	}else
	{	
        //retry
        $arr = array('status'=>"error");
        echo json_encode($arr);
        die();
	
	}
	
}


?>