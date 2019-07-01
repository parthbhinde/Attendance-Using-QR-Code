<?php
//connect to db
require('connect.php');

$eve = 0;



if ($_SESSION['rights']=="a"){
	if(isset($_POST['uid']))
	{
		//Assigning GET values to variables.
		$uid = $_POST['uid'];
		
		$details = $conn->prepare("SELECT * FROM studentinfo WHERE id = ?");
		$details->bind_param("s", $uid);
		$details->execute();
		//get result of query
		$result = $details->get_result();
		$name = $result->fetch_assoc();
		
		//update status
		$updq = $conn->prepare("UPDATE studentinfo SET status = 0 WHERE id =? ");
		$updq->bind_param("s",$uid);
		$updq->execute();
		
		//if succesfull
		if($updq->affected_rows === 1){
			$eve = 1;
			$updq->close();
		}else{
			$eve = 2;
		}
		$details->close();
	}

}
else{
	$eve = 3;
}  


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
			
		<link rel="stylesheet" href="css/register.css">
		<link rel="stylesheet" href="css/snackbar.css">
		<script> window.parent.document.title = 'Unblock'; </script>
		<style>
			#backbutton{
				visibility: collapse;
			}
		</style>
	</head>

	<body style="background: #FFFFFF;">
	<?php require('loader.php'); ?>
	<?php require('navbar.php'); ?>
		<div class="container" style="margin-top:80px">
		
			<h2> Unblock Student</h2>
			<div class="row">
				<div class="col-sm-8">
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
						<input type="text" name="uid" placeholder="ID Number" class="txt" autocomplete="off" <?php if(isset($_POST['uid'])) echo ("value=".$_POST['uid']); ?> required><br>
						<button type="submit" value="reset" class="sbutton" id="button">UNBLOCK</button>
					</form>
				</div>
			</div>
		
		<div id="snackbar">Some text some message..</div>
		
		</div>
		
		<script>
		
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
		
		
		

		<?php
		if($eve==1){
			echo ("<script> showsnack('Unblocked ".ucwords($name['name'])." [Roll No: ".$name['rollno']."]') </script>");
		}elseif($eve==2){
			echo ("<script> showsnack('Wrong ID Number Or Already Unblocked') </script>");
		}elseif($eve==3){
			echo ("<script> showsnack('Admin Rights Required'); </script>");
		}
		?>
	</body>

</html>


