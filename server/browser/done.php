<html>
<?php require('../loader.php');?>
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
	<link rel="shortcut icon" type="image/png" href="../assets/favicon.ico"/>
	
	<link rel="stylesheet" href="../css/global.css">
	<link rel="stylesheet" href="../css/tick.css">
	<script> window.parent.document.title = 'Successfull'; </script>
	<style>
		.suc{
			background-color: #13a080;
			color: white;
			padding: 1em;
			border-radius: 10px;
			animation: fadeIn 3s infinite alternate ;
		}
		@keyframes fadeIn { 
		  from { opacity: 0.5; } 
		}
		p{
			color: rgba(0, 0, 0, 0.75);
			font-size: 0.80rem;
		}
		
		.check_mark {
		  width: 80px;
		  height: 130px;
		  margin: 0 auto;
		}

		.hide{
		  display:none;
		}
	</style>
</head>

<body>

	<div class="container">
		<div class="row">
			<div class="col-10 offset-1" style="margin-top: 4em;">
				<center>
					<div class="check_mark">
						<div class="sa-icon sa-success animate">
							<span class="sa-line sa-tip animateSuccessTip"></span>
							<span class="sa-line sa-long animateSuccessLong"></span>
							<div class="sa-placeholder"></div>
							<div class="sa-fix"></div>
						</div>
					</div>
					<div class="suc">
						<h4>Attendance Successfull</h4>
					</div>
					<div style="margin-top: 0.3em;"><p> <a href="../student.php">view attendance</a> to confirm your attendance.</p></div>
					<div style="margin-top: 2em;"><a class="btn btn-primary" href="help.php">Help a friend ➔</a></div>
				</center>
			</div>
		</div>
	</div>
</body>

</html>
