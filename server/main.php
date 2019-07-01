<?php 
	//start session
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	require("strings.php");
?>
<html>
	<head>
	  <meta charset="utf-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  
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
	  </style>
	</head>
	<body>
		<iframe src= '<?php echo $serverUrl?>/dash.php'></iframe>
	</body>
	<script>

	</script>
</html>