<?php
require('connect.php');
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
				
		<link rel="stylesheet" href="css/global.css">
		<link rel="stylesheet" href="css/snackbar.css">
		<link rel="stylesheet" href="css/register.css">

		<style>
			button:disabled {
				background: rgba(19, 160, 128, 0.25);
			}
			#backbutton{
				visibility: collapse;
			}
		</style>
	</head>

	<body style="background: #FFFFFF;">
	 
	<?php require('navbar.php'); ?>
		<div class="container" style="margin-top:80px">
			<h2>Update Password</h2>
			<div class="row">
				<div class="col-sm-8 offset-sm-2">
				<form action="" method="post" onsubmit="return false;">
						<input type="password" name="oldpass" placeholder="Old Password" id = "op" class="txt" autocomplete="off"  required><br>
						<input type="password" name="newpass" placeholder="New Password" id = "np"class="txt" autocomplete="off" required><br>
						<input type="password" name="pass" placeholder="Confirm New Password" id = "cp" class="txt"  autocomplete="off"  required><br>
						<button type="submit" value="Login" class="sbutton" id="sbutton" >UPDATE</button>
					</form>
				</div>
			</div>

			<div id="snackbar">Some text some message..</div>
		</div>
		
		

		<script>
		$("#sbutton").click(function(){
				var oldpass =$("#op").val();
				var newpass=$("#np").val();
				var cpass =$("#cp").val();
				if(oldpass!="" && newpass!=""){
					jQuery.post("http://localhost/changepasswordt.php" ,{oldpass: oldpass , newpass:newpass , cpass:cpass},function(text){
                        showsnack(text);
                    });
				}
				else{
					alert("Please fill all the fields");
					return false;
				}
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

	</body>
</html>
