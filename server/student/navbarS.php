<?php 
	//start session
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	if(!(isset($_SESSION['id']))){
		header('Location: studentlogin.php');
		die();
	};
?>
<link rel="stylesheet" href="../css/global.css">
<!--Navbar for files inside takeattendance folder -->
<style>
	
	nav{
		background-color: #2C3E50;
	}
	
	
</style>
<nav class="navbar navbar-expand-sm navbar-dark fixed-top" style="margin-bottom:80px">
  <a class="navbar-brand" href="myaccount.php" style="color: #ECF0F1;"><img src="../assets/logogreen.png" alt="Logo" style="width:1.5em; margin: 0% 10% 0% 2%;">&nbsp;QrAtt-Student</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
   <div class="collapse navbar-collapse" id="collapsibleNavbar">
	  <ul class="navbar-nav ml-auto">
		<li class="nav-item">
		  <a class="nav-link" href="changepass.php" style="color: #ECF0F1;"> Change Password</a>
		</li>
		<li class="nav-item">
		  <a class="nav-link" href="qratt.apk" style="color: #ECF0F1;" download> Download App &#x21E3;</a>
		</li>
		<li class="nav-item">
		  <a class="nav-link" href="studentlogout.php" style="color: #ECF0F1;"> Logout</a>
		</li>
	  </ul>
  </div>
</nav>
