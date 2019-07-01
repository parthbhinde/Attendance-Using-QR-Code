<html>
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	
	<link rel="stylesheet" href="../css/global.css">
	<link rel="shortcut icon" type="image/png" href="../assets/favicon.ico"/>
	<script> window.parent.document.title = 'Error'; </script>
</head>

<body>
	<?php require('../loader.php');?>
	<?php
	session_start();
	session_unset();
	session_destroy(); 
	?>
	<div class="container">
		<div class="row">
			<div class="col-10 offset-1" style="text-align: center;margin-top: 5em;">
				<div class="alert alert-danger" role="alert">
					<h1>ERROR</h1>
					<p>Something went wrong &#128577;</p>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
