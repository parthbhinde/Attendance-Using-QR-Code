<?php
//connect to db 
$conn = new mysqli("localhost","root","","qratt");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST['username'])){
	
	//set date zone for India.
	date_default_timezone_set('Asia/Kolkata');
	
	//SQL query to fetch details
	$idq= $conn->prepare("SELECT id, email FROM `studentinfo` WHERE username=?");
	$idq->bind_param("s", $_POST['username']);
	$idq->execute();
	$result = $idq->get_result();
	
	if($result->num_rows == 1)
	{
		$id= $result->fetch_assoc();
		
		//custom md5 encryption
		$today = date("Ymd");
		$custom = strtotime($today)+(90*1024);
		$encrypt = md5($custom+$id['id']);
		
		//email block
		{
			$to = $id['email'];
			$subject = "Reset Passsword";
			 $body = "<div> Hello ".$_POST['username'].", Click on button below to reset your password <br>
			 	<a href='localhost/reset/updatepassword.php?update=".$encrypt."&action=reset'><button>Reset</button></a>
			 	<p>Link is valid for 1 day only</p>
			 	</div>";
			
			    $headers = 'From: attendance@team.com' . "\r\n" ;
			    $headers .='Reply-To: '. $to . "\r\n" ;
			    $headers .='X-Mailer: PHP/' . phpversion();
			    $headers .= "MIME-Version: 1.0\r\n";
			    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";   
			if(mail($to, $subject, $body,$headers)) {
			  	echo("<center><p style='color:#2C3E50; margin: 10% auto; font-size: 2em;'> Reset link has been sent to your registered email id </p></center>");
			  } 
			  else 
			  {
			  	echo("<center><p style='color:#2C3E50; margin: 10% auto; font-size: 2em;'>Email Message delivery failed...</p></center>");
			  }
		}
		
	}
	else{
		echo("<center><p style='color:#2C3E50; margin: 10% auto; font-size: 2em;'>Invalid Username</p></center>");
	}
	

}
else{
	die();
}
  

?> 