<?php 
	//start session
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	if(!(isset($_SESSION['teacher-name']))){
		header('Location: teacherlogin.php');
		die();
	}
?>
<style>
	#teacherinfo{
		margin-right: 2em;
		display: inline-flex;
	}
	#backbutton:hover{
		background-color: #343a40 ;
	}
	.navbar{
		box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.2), 0 2px 10px 0 rgba(0, 0, 0, 0.19);
	}
	@media only screen and (max-width: 576px) {
		#teacherinfo {
			display: none;
		}
	}
	nav{
		background-color: #2C3E50;
	}
	.navbar-brand{
		color: #ECF0F1;
	}
	
</style>
<nav class="navbar navbar-expand-sm navbar-dark fixed-top" style="margin-bottom:80px">
  <button class="btn btn-dark text-white" id="backbutton" onclick='goBack()'> &#9664; </button>
  <a class="navbar-brand" href="dash.php" style="color: #ECF0F1;"><img src="assets/logogreen.png" alt="Logo" style="width:1.5em; margin: 0% 2%;">&nbsp;QrAtt</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
   <div class="collapse navbar-collapse" id="collapsibleNavbar">
	  <ul class="navbar-nav ml-auto">
		<div id="teacherinfo">
			<li class="nav-item">
				<img src="assets/teachericon.png" alt="" style="width:3.5em; margin: 7% 0%;">
			</li>
			<li class="nav-item">
			   <span class="navbar-text" style="color: #ECF0F1;"><?php echo (ucwords($_SESSION['teacher-name']));?> </span>
			</li>
		</div>
		<li class="nav-item">
		  <a class="nav-link" href="teacherlogout.php" style="color: #ECF0F1;"> Logout</a>
		</li>
	  </ul>
  </div>
</nav>

<script>
	function goBack() {
		window.history.back();
	}
</script>