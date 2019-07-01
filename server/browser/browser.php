<?php
//Detect special conditions devices
$iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
$iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
$iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
$Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
$webOS   = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");

//do something with this information
if($Android || $webOS){
	echo ("<h1 style='margin-top: 25%; text-align: center;'> Please Use App On Your Device </h1>");
	$url= "error.php";
	header("Refresh: 3; URL=$url");
	//die();
}
else if( $iPod || $iPhone || $iPad ){
    //browser reported as an iPhone/iPod touch -- do something here

//start session
session_start();
//Expire the session if user is inactive for 5 minutes or more.
$expireAfter = 5;

//Check to see if our "last action" session
//variable has been set.
if(isset($_SESSION['last_action'])){

    //Figure out how many seconds have passed
    //since the user was last active.
    $secondsInactive = time() - $_SESSION['last_action'];

    //Convert our minutes into seconds.
    $expireAfterSeconds = $expireAfter * 60;

    //Check to see if they have been inactive for too long.
    if($secondsInactive >= $expireAfterSeconds){
        //User has been inactive for too long.
        //Kill their session.
        session_unset();
        session_destroy();
    }

}
//encrypt decrypt
function dec_enc($action, $string) {
    $output = false;
 
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'This is my secret key';
    $secret_iv = 'This is my secret iv';
 
    // hash
    $key = hash('sha256', $secret_key);
    
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
 
    if( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    }
    else if( $action == 'decrypt' ){
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
 
    return $output;
}

//Assign the current timestamp as the user's
//latest activity
$_SESSION['last_action'] = time();




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


//connect to db
$conn = new mysqli("localhost","root","","qratt");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// eve handles alert conditions
$eve = 0;
if(isset($_SESSION['Forwarded'])){
if($_SESSION['Forwarded'] == "yes"){
if(isset($_COOKIE['user']) and !isset($_POST['username'])){
$myString =  dec_enc('decrypt',$_COOKIE['user']);
$myArray = explode(',', $myString);
$_POST['username'] = $myArray[0];
$_POST['password'] = $myArray[1];
$_POST['sr']= $_COOKIE['screen'];
}
if(isset($_POST['username']) and isset($_POST['password']))
{
	//Assigning GET values to variables.
	$username = $_POST['username'];
	$password = $_POST['password'];
	$class = $_SESSION['classid'];
	$table = $_SESSION['tablename'];
	$usera = $_SERVER['HTTP_USER_AGENT'];
	preg_match('#\((.*?)\)#', $usera, $match);
	$usera = $match[1]; 
	$sr = $_POST['sr'];  
	$ua = $usera.$sr;
	

	require('../checklogin.php');
$ipaddr = get_client_ip();

	//check status first
	$cstatq = "SELECT `status`, `attid` FROM `$table` ORDER BY attid DESC LIMIT 1";
	$cstat = $conn->prepare($cstatq);
	$cstat->execute();

	//get result of query
	$qstat = $cstat->get_result();
	$status = $qstat->fetch_assoc();

	if($status['status'] == 0 ){

		//SQL query to fetch details
		$stmt = $conn->prepare("SELECT * FROM `studentinfo` WHERE username = ?");
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

				$sid = $creds['rollno'];
				$sname = $creds['name'];
				$class = $_SESSION['classid'];
				$table = $_SESSION['tablename'];
				if($creds['status']== 0 && $creds['classid']== $class){
					$rolq = "UPDATE `$table` SET r".$sid." = '1' WHERE attid = ?; ";
					$rol = $conn->prepare($rolq);
					$rol->bind_param("i", $status['attid']);
					$rol->execute();

					if ($rol->affected_rows === 1){
            					$cookie_name = "cid";
 						$cookie_value = generateRandomString();
						$ses_value = $cookie_value;
						if(isset($_SESSION['uid']) || isset($_COOKIE[$cookie_name])) {
              				$cookie_exists = $_COOKIE[$cookie_name];
							$ses_exists = $_SESSION['uid'];
							$q = "INSERT INTO `".$table."-multi` (`sid`,`name`,`ip`,`user-agent`,`uid`, `cid`) VALUES (?,?,?,?,?,?)";
							$i = $conn->prepare($q);
							$i->bind_param("isssss", $sid, $sname, $ipaddr, $ua ,$ses_exists,$cookie_exists);
							$i->execute();
						}
						else{
              setcookie($cookie_name, $cookie_value, time() + (60 * 20), "/");
							$_SESSION['uid'] = $ses_value;
							$ses_exval = $_SESSION['uid'];
							$q = "INSERT INTO `".$table."-multi` (`sid`,`name`,`ip`,`user-agent`,`uid`, `cid`) VALUES (?,?,?,?,?,?)";
							$i = $conn->prepare($q);
							$i->bind_param("isssss", $sid, $sname, $ipaddr, $ua ,$ses_exval ,$cookie_value);
							$i->execute();
						}
						$eve = 1;
                                                if(isset($_POST['autologin'])){
                                                   $cookie_name = "user";
                                                   $datatoenc = $username.",".$password;
                                                   
                                                   $cookie_value = dec_enc('encrypt', $datatoenc);
                                                   setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day 
                                                   $cookie_screen = "screen";
                                                   $cookie_svalue = $sr; 
                                                   setcookie($cookie_screen,$cookie_svalue,time() + (86400 * 30), "/");      
                                                }
						header("Location: done.php");
						die();
					}
					else {
						$eve = 4;
					}
				}
				else{
					header("Location: blocked.php");
				}
			}
			else {
				$eve = 2;
			}


		}
		else
		{
			//retry
			$eve = 3;
		}
	}
	else{
		echo ("<script> showsnack('Currently Unavailable.') </script>");
	}
}
$conn->close();

?>
<!DOCTYPE html>
<html>
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
		<link rel="stylesheet" href="../css/snackbar.css">
		<link rel="stylesheet" href="../css/loginpage.css">
		<script> window.parent.document.title = 'Please Login'; </script>
		<link rel="shortcut icon" type="image/png" href="../assets/favicon.ico"/>

	</head>

	<body>
		<?php require('../loader.php');?>
		<div class="container">
		<center><img src="../assets/logo.png" height="200px" width="200px"><center>
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
									<div class="form-group" style="display:none;">
										<input type="text" name="sr" id="sr" value="">
									</div>

									<div class="form-group">
										<input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Username" required>
									</div>
									<div class="form-group">
										<input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password" required>
									</div>
<div class="form-check form-group">
    <input type="checkbox" name="autologin" class="form-check-input" id="exampleCheck1">
    <label class="form-check-label" for="exampleCheck1">Remember Me</label>
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
		<script type="text/javascript" src="../js/jquery.min.js"></script>
		<script src="../js/bootstrap.js"></script>

		<script>
		var w = window.screen.width;
		var h = window.screen.height;
		var x = w+'X'+h;
		document.getElementById("sr").value = x;
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

<?php
}
else{
header("Location: error.php");
}
}
else{
header("Location: error.php");
}
}
?>
