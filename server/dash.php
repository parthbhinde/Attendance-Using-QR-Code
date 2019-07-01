<?php 
	
	require('connect.php');

?>

<html>
	<?php require('loader.php'); ?>
	<head>
	  <meta charset="utf-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <title>Dash</title>
	  	
	  <!-- Latest compiled and minified CSS -->
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	  <!-- jQuery library -->
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	  <!-- Popper JS -->
	  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
	  <!-- Latest compiled JavaScript -->
	  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script> 
		
	  <link rel="stylesheet" href="css/global.css">
	  <link rel="stylesheet" href="css/snackbar.css">
	  
	  <script> window.parent.document.title = 'Dashboard'; </script>
	  <style>
			#backbutton{
				visibility: collapse;
			}
			.pannel > a > button{
				background-color: transparent;
				border: none;
				border-bottom: 2px solid gray;
				width: 100%;
				padding: 5% 0%;
				transition:all 0.3s ease;
			}
			.pannel > a > button:hover{
				background-color: rgba(19, 160, 128, 0.15);
				cursor: pointer;
			}
			.interraction{
				padding-top: 4%;
			}
			.interractionbtn{
				padding: 2.5% 6%;
				margin-bottom: 3%;
				background-color: #2C3E50;
				color: #ECF0F1;
				border-radius: 10px;
				border: none;
				cursor: pointer;
				font-weight: bold;
				transition:all 0.3s ease;
				opacity:0.9;
			}
			.interractionbtn:hover{
				box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
				opacity:1;
				border-radius:50px;
			}
			#takeattendance{
				border: 2px solid gray;
				border-radius: 10px;
				transition:border,box-shadow 0.3s ease;
				margin-bottom: 20px;
			}
			#takeattendance:hover{
				border: 2px solid #2C3E50;
				box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
			}
			#reviewattendance{
				border: 2px solid gray;
				border-radius: 10px;
				transition:border,box-shadow 0.3s ease;
				margin-bottom: 20px;
			}
			#reviewattendance:hover{
				border: 2px solid #2C3E50;
				box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
			}
			
			@media only screen and (max-width: 768px) {
				.pannel{
					display: inline-flex;
					margin-top: 1em;
				}
				.pannel > a > button{
					border: 2px solid gray;
					padding: 5%;
				}
				.pannel > a {
					margin: 0 auto;
				}
				.interractionbtn{
					margin-top: 10%;
				}
				#curr
			}
			@media only screen and (min-width: 768px) {
				.pannel {
					border-right: 2px solid grey;
					height:100vh;
					padding:0;
				}
			}
	  </style>
	</head>
	
	<body>
	<script>
		  
		$(document).ready(function() {
			$('#toggletakeatt').click(function() {
					$('#takeattendance').slideToggle(300);
					$('#reviewattendance').hide(300);
			});
		});	

		$(document).ready(function() {
			$('#togglereviewatt').click(function() {
					$('#reviewattendance').slideToggle(300);
					$('#takeattendance').hide(300);
			});
		});
		
		function showsnack(txt) {
			// Get the snackbar DIV
			var x = document.getElementById("snackbar")
			x.innerHTML = txt;
			// Add the "show" class to DIV
			x.className = "show";

			// After 3 seconds, remove the show class from DIV
			setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
		}
	</script>
		<?php require('navbar.php'); ?>
		<div class="container-fluid" style="margin-top:60px">
			<div class="row">
				<div class="col-sm-12 col-md-2 pannel">
					<a href="changepasst.php"><button>Change Password</button></a>
					<?php 	
						if ($_SESSION['rights'] == "a"){
							echo('<a href="unblockstd.php"><button>Unblock Student</button></a>');
							echo('<a href="updateatt.php"><button>Update Attendance</button></a>');
						}			
					?>
				</div>
				<div class="col-sm-12 col-md-10">
					<div class="row interraction">
						<div class="col-12 col-sm-6">	
							<center><button class="interractionbtn" id="toggletakeatt">Take Attendance</button></center><br>
							<div class="col-10 offset-1" id="takeattendance" style="display:none;">
								<?php require('takeattendance/takeatt.php'); ?>
							</div>
						</div>
				
						<div class="col-12 col-sm-6">
							<center><button class="interractionbtn" id="togglereviewatt">Review Attendance</button></center><br>
							<div class="col-10 offset-1" id="reviewattendance" style="display:none;">
								<?php require('reviewattendance/reviewatt.php'); ?>
							</div>
						</div>
					</div>
					<div class="row" style="margin-top:5em;">
						<div class="col-10 offset-1">
						
							<?php
								echo("<hr>");
								echo("<h4 id='currmonth' style='clear: both;'>".date("F, Y")."</h4>");
								require('percent.php');
							?>
						</div>
					</div>
				</div>
			</div>
			<div id="snackbar">
				<p id="error"></p>
			</div>
		</div>
		
		<script>	
				<?php if(isset($_GET['success']))
					echo ('showsnack("'.$_GET["success"].'")');
				?>
		</script>
		

	</body>
</html>