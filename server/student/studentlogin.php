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

if(isset($_POST['susername']) and isset($_POST['password']))
{
	//Assigning GET values to variables.
	$username = $_POST['susername'];
	$password = $_POST['password'];
	
	require('../checklogin.php');
	
	//SQL query to fetch details
	$stmt = $conn->prepare("SELECT * FROM `studentinfo` WHERE username= ?");
	$stmt->bind_param("s", $username);
	$stmt->execute();
	
	//get result of query
	$result = $stmt->get_result();
 
	if($result->num_rows == 1)
	{
		$creds=$result->fetch_assoc();
		
		if (password_verify($password, $creds['password'])) {
		
			/* ______________DELETE From Logincontrol as it's successfull______________ */

			$delcheck = $conn->prepare("DELETE FROM `logincontrol` WHERE  username = ?");
			$delcheck->bind_param("s", $username);
			$delcheck->execute();

			/* ________________________________________________________________________ */
		
			$_SESSION['id'] = $creds['rollno'];
			$_SESSION['student-username'] = $creds['name'];
			$_SESSION['classid'] = $creds['classid'];
			header("Location: myaccount.php");
		} 
		else {
			$eve = 2;
		}
		
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
		
		<link rel="stylesheet" href="../css/snackbar.css">
		<link rel="stylesheet" href="../css/loginpage.css">
		
		<style>
			h2{
				color: white;
				text-align: center;
				margin-top: -90px;
				margin-bottom: 85px;
				background-color: #2C3E50;
				padding: 0.3em;
			}
		</style>
		
		<script> window.parent.document.title = 'Please Login'; </script>
	</head>

	<body>
		<?php require('../loader.php');?>
	
	<div>
		<h2>QrAtt-Student</h2>
	</div>
	
	<div class="container">
		<center>
			<img src="../assets/teachericon.png" height="auto" width="200px">
		<center>
    	<div class="row">
			<div class="col-md-12 col-md-offset-3">
				<div class="panel panel-login" style="padding: 6%">

					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
								<form id="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" role="form" style="display: block;">
									<div class="form-group" style="display:none;">
										<input type="text" name="user-agent" id="user-agent" value="">
									</div>
									<div class="form-group">
										<input type="text" name="susername" id="susername" tabindex="1" class="form-control" placeholder="Username"  required>
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
												<div class="text-center">
													<a href="../reset/forgotpassword.php" tabindex="5" class="forgot-password">Forgot Password?</a><br>
												</div>
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
		var regExp = /\(([^)]+)\)/;
		var matches = regExp.exec(navigator.userAgent);
		document.getElementById("user-agent").value = matches[1];
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
			echo ("<script> showsnack('Successfull!') </script>");
		}elseif($eve==2){
			echo ("<script> showsnack('Invalid password.') </script>");
		}elseif($eve==3){
			echo ("<script> showsnack('Invalid Username Please Try Again.') </script>");
		}elseif($eve==4){
			echo ("<script> showsnack('Error try again!') </script>");
		}
	?>
	</body>

</html>

