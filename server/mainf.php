<?php 
	//start session
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	require("strings.php");
?>
<html>
	<head>
		  
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<!-- Popper JS -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
		<!-- Latest compiled JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script> 
	 	<link rel="shortcut icon" type="image/png" href="/assets/favicon.ico"/>
		
		<style>
			body{
				margin: 0;
			}
			
			iframe{	
				border: none;
				width: 100%;
				height: 100vh;
			}
			
			#data{
				visibility: hidden;
			}
			#start{
				background-color: #16a085;
				outline: none;
				color: #fff;
				font-size: 2em;
				height: auto;
				font-weight: normal;
				padding: 1% 10%;
				text-transform: uppercase;
				border: 2px solid #16a089;
				border-radius: 2em;
			}
			#start:hover{ 
				background-color: #0f9cd5;
				border: 2px solid #0f9cd9;
				border-radius: 2em;
				box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
			}
			#wrapper {
				margin-top: 5%;
				text-align: center;
			}
		</style>
	</head>
	<body>
	
		<div id="wrapper">
			<center><img src="assets/logo.png" height="200px" width="200px" style="margin-bottom: 2%;"><center>
			<button id = "start">Start</button>
		</div>
		<div id="data">
		<iframe src='<?php echo $serverUrl?>/dash.php'></iframe>
		</div>
		
		<script>
			
			document.getElementById("start").addEventListener("click", demo);
		
			function GoInFullscreen(element) {
				if(element.requestFullscreen)
					element.requestFullscreen();
				else if(element.mozRequestFullScreen)
					element.mozRequestFullScreen();
				else if(element.webkitRequestFullscreen)
					element.webkitRequestFullscreen();
				else if(element.msRequestFullscreen)
					element.msRequestFullscreen();
			}
			
			function demo(){
				btn = document.querySelector("#wrapper");
				
				btn.outerHTML = "";
				delete btn;
				
				x = document.querySelector("#data");
				x.style.visibility='visible';
				GoInFullscreen(x);	
			}
		</script>
	</body>
</html>