<?php 
	//start session
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	if(!(isset($_SESSION['teacher-name']))){
		header('Location: ../teacherlogin.php');
		die();
	};
?>
<link rel="stylesheet" href="../css/global.css">
<!--Navbar for files inside takeattendance folder -->
<style>
	#teacherinfo{
		margin-right: 2em;
		display: inline-flex;
	}
	#backbutton{
		background-color: #2C3E50 !important;
		color: #ECF0F1;
		cursor: pointer;
		outline: none;
		border: none;
	}
	#backbutton:focus{
		outline: none;
		border: none;
	}
	@media only screen and (max-width: 576px) {
		#teacherinfo {
			display: none;
		}
	}
	nav{
		background-color: #2C3E50;
	}
	
</style>
<nav class="navbar navbar-expand-sm navbar-dark fixed-top" style="margin-bottom:80px">
  <button class="btn btn-dark" id="backbutton" onclick='goBack()' style="color: #ECF0F1;"> &#9664; </button>
  <a class="navbar-brand" href="../dash.php" style="color: #ECF0F1;"><img src="../assets/logogreen.png" alt="Logo" style="width:1.5em; margin: 0% 10% 0% 2%;">&nbsp;QrAtt</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
   <div class="collapse navbar-collapse" id="collapsibleNavbar">
	  <ul class="navbar-nav ml-auto">
		<div id="teacherinfo">
			<li class="nav-item">
				<img src="../assets/teachericon.png" alt="" style="width:3.5em; margin: 7% 0%;">
			</li>
			<li class="nav-item">
			   <span class="navbar-text" style="color: #ECF0F1;"><?php echo (ucwords($_SESSION['teacher-name']));?> </span>
			</li>
		</div>
		<li class="nav-item">
		  <a class="nav-link" href="../teacherlogout.php" style="color: #ECF0F1;"> Logout</a>
		</li>
	  </ul>
  </div>
</nav>

<script>
	function goBack() {
		window.history.back();
	}
</script>