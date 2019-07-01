<!DOCTYPE html>
<html>
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<!-- Popper JS -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
		<!-- Latest compiled JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script> 
				
		<link rel="stylesheet" href="../css/global.css">
		<link rel="stylesheet" href="../css/snackbar.css">
		<link rel="stylesheet" href="../css/register.css">
		<script>
		
			function showsnack(txt) {
				// Get the snackbar DIV
				var x = document.getElementById("snackbar")
				x.innerHTML = txt;
				// Add the "show" class to DIV
				x.className = "show";
	
				// After 3 seconds, remove the show class from DIV
				setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
				}
		</script>
		
</head>

<body style="background: #FFFFFF;">
	<div id="snackbar">Some text some message..</div>
	<?php
	//connect to db 
	$conn = new mysqli("localhost","root","","qratt");
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	
	if(isset($_GET['action']))
	{          
	    if($_GET['action']=="reset")
	    {
	   	 //set date zone for India.
		date_default_timezone_set('Asia/Kolkata');
		
	    	//get encrypted password
	        $encrypt = mysqli_real_escape_string($conn,$_GET['update']); 
	        
	        //custom md5 encryption
		$today = date("Ymd");
		$custom = strtotime($today)+(90*1024);
	        
	        //SQL query to decrypt md5 and get data
	        $q = "SELECT id, username FROM `studentinfo` where md5(".$custom."+id)= ? ";
		$idq= $conn->prepare($q);
		$idq->bind_param("s", $encrypt);
		$idq->execute();
		$result = $idq->get_result();
		
		if($result->num_rows == 1)
		{	$id= $result->fetch_assoc();
			echo "<center><h2>Update Password</h2></center>";
			
			echo ('
				<div class="row">
					<div class="col-sm-8 offset-sm-2">
						<form action="'.basename($_SERVER['REQUEST_URI']).'" method="post">
							<input type="password" name="newpass" placeholder="New Password" id = "np"class="txt" autocomplete="off" required><br>
							<input type="password" name="cpass" placeholder="Confirm New Password" id = "cp" class="txt"  autocomplete="off"  required><br>
							<button type="submit" value="Submit" class="sbutton" id="sbutton" >UPDATE</button>
						</form>
					</div>
				</div>');
			
			if(isset($_POST['newpass']) and isset($_POST['cpass']))
			{
				
				$newpass = $_POST['newpass'];
				$cpass   = $_POST['cpass']; 
					
				//check password criterias
				$uppercase = preg_match('@[A-Z]@', $newpass);
				$lowercase = preg_match('@[a-z]@', $newpass);
				$number    = preg_match('@[0-9]@', $newpass);
			
				if(!$uppercase || !$lowercase || !$number || strlen($newpass) < 8) {
					echo ("<script> showsnack(' Password must contain mixed case letter and number ')</script>");
					die();
				}
				
	
				if($newpass == $cpass){
						
					$encpass = password_hash($newpass, PASSWORD_BCRYPT);
					
					$updq = $conn->prepare("UPDATE `studentinfo` SET `password` = ? WHERE username = ?");
					$updq->bind_param("ss",$encpass, $id['username']);
					$updq->execute();
					
					//if succesfull
					if($updq->affected_rows === 1){
						echo ("<script> showsnack('Password Update Successfull!')</script>");
						$updq->close();
					}
					else{
						echo ("<script> showsnack('Somethin Went Wrong')</script>");
					}
				}
				else{
					echo ("<script> showsnack('Passwords dont match')</script>");
				}
			}
			
		}
		else
	        {
	            echo ('<center><p style="color:#2C3E50; font-size: 2em;">Something went wrong, please try again. <a href="forgotpassword.php">Forgot Password?</a></p></center>');
	            die();
	        }
	        
	    }
	}
	
	
	
	?>
</body>
</html>
