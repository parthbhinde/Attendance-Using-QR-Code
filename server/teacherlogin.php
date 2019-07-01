<?php

//connect to db 
$conn = new mysqli("localhost","root","","qratt");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//start session
session_start();
	
// eve handles alert conditions
$eve = 0;

if(isset($_POST['username']) and isset($_POST['password']))
{
	//Assigning GET values to variables.
	$username = $_POST['username'];
	$password = $_POST['password'];
	 
	require('checklogin.php');
	
	
	//SQL query to fetch details
	$stmt = $conn->prepare("SELECT * FROM `teacherinfo` WHERE username=?");
	$stmt->bind_param("s", $username);
	$stmt->execute();
	
	//get result of query
	$result = $stmt->get_result();
	
	if($result->num_rows == 1)
	{
		$creds=$result->fetch_assoc();
		
		if (password_verify($password, $creds['password'])) {
			$_SESSION['teacher-name']= $creds['name'];
			$_SESSION['teacher-username'] = $creds['username'];
			$_SESSION['rights'] = $creds['rights'];
			$eve = 1;
			$stmt->close();
			
			/* ______________DELETE From Logincontrol as it's successfull______________ */

			$delcheck = $conn->prepare("DELETE FROM `logincontrol` WHERE  username = ?");
			$delcheck->bind_param("s", $username);
			$delcheck->execute();

			/* ________________________________________________________________________ */
			
			//Redirect to homeoage
			header('Location: dash.php');
			die();
		} 
		else {
			$eve = 2;
		}
		//Set Variable in Session
		
	}else
	{	
		//retry
		$eve = 3;
	}
}  
?>
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
		
		<link rel="stylesheet" href="css/mystyle.css">
		<link rel="stylesheet" href="css/snackbar.css">
		<link rel="stylesheet" href="css/loginpage.css">
		<script> window.parent.document.title = 'Login To QrAtt'; </script>
	</head>

	<body>
		<div class="container">
		<center><img src="assets/logo.png" height="200px" width="200px"><center>
    	<div class="row">
			<div class="col-md-12 col-md-offset-3">
				<div class="panel panel-login" style="padding: 6%">

					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
								<form id="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" role="form" style="display: block;">
									<div class="form-group">
										<input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Username"  required>
									</div>
									<div class="form-group">
										<input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password" required>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<input type="submit" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-login" value="Log In">
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-lg-12">
												<!--<div class="text-center">
													<a href="resetpassteacher.php" tabindex="5" class="forgot-password">Forgot Password?</a><br>
												</div>-->
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="snackbar">Alert message..</div>
	</div>
		
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
		
		
		$(function() {
  $( "#button" ).click(function() {
    $( "#button" ).addClass( "onclic", 250, validate);
  });

  function validate() {
    setTimeout(function() {
      $( "#button" ).removeClass( "onclic" );
      $( "#button" ).addClass( "validate", 450, callback );
    }, 2250 );
  }
    function callback() {
      setTimeout(function() {
        $( "#button" ).removeClass( "validate" );
      }, 1250 );
    }
  });
		</script>
	<?php
		if($eve==1){
			echo ("<script> showsnack('Login Successfull!') </script>");
		}elseif($eve==2){
			echo ("<script> showsnack('Invalid password.') </script>");
		}elseif($eve==3){
			echo ("<script> showsnack('Invalid Username Please Try Again.') </script>");
		}
	?>
	</body>
<?php mysqli_close($conn);?>
</html>


