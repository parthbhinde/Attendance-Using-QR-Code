<?php 
	
	require('../connect.php');
	
	//random string function
	function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	if(isset($_GET['classvalue'])){
		
		foreach($_GET as $loc=>$item)
			$_GET[$loc] = base64_decode(urldecode($item));
		
		$values = explode("-",$_GET['classvalue']);
		
		//insert values of post
		$year = strtolower($values[0]);
		$coursename = strtolower($values[1]);
		$division = strtolower($values[2]);
		$subid = $values[3];
		$classid = $values[4];
		
		//assign random name
		$newname = generateRandomString();
		$newname = $newname.".php";
		
		$oldname = "";
		
		//get name of old php file in directory and assign it to a variable
		$directory = '../verification/'.$year.$coursename.'-'.$division.'/';
		foreach (glob($directory."*.php") as $filename) {
			$file = realpath($filename);
			$oldname = $file;
		}
		
		//rename verification file
		rename($oldname,$directory.$newname);
		
		//get name of old php file in directory and assign it to a variable
		$directory = '../assets/qrcode/'.$year.$coursename.'-'.$division.'/';

		//delete qrcode.png
		array_map('unlink', glob($directory."*.png")); 
		
		
		//redirect to attdata
		$url = "attdata.php?classvalue=".urlencode(base64_encode($_GET['classvalue']));
		header("Refresh: 0; URL=".$url);
	}
	else{
		mysqli_close($con);
	}

?>

