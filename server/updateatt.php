<?php 	
	require('connect.php');
	if(isset($_POST['classvalue']) && isset($_POST['rollno']) && isset($_POST['reason']) && isset($_POST['sdate']) && isset($_POST['edate'])){
		$values = explode("-",$_POST['classvalue']);
		//insert values of post
		$year = strtolower($values[0]);
		$coursename = strtolower($values[1]);
		$division = strtolower($values[2]);
		$classid = $values[3];

		$atttable = "`".$year.$coursename."-".$division."`";
		$rollno = $_POST['rollno'];
		$sdate = $_POST['sdate'];
		$edate = $_POST['edate'];
		$reason = $_POST['reason'];
		
		$blockq = $conn->prepare("SELECT status FROM `studentinfo` WHERE rollno = ?");
		$blockq->bind_param("i",$rollno);
		$blockq->execute();
		$result = $blockq->get_result();
		$creds = $result->fetch_assoc();
		
		//check if blocked
		if($creds['status']==1){
			header("Location: updateatt.php?u=".($rollno)."&s=2");
		}else{
			$query = "UPDATE ".$atttable." SET r".$rollno." = 1, c".$rollno." = ? WHERE date BETWEEN ? AND ? ";
			if(!($updateq = $conn->prepare($query))){
				header("Location: updateatt.php?u=".($rollno)."&s=0");	
			}
			else{
				$updateq = $conn->prepare($query);
				$updateq->bind_param("sss",$reason,$sdate,$edate);
				$updateq->execute();
				header("Location: updateatt.php?u=".($rollno)."&s=1");
			}
		}
		
	}
?>
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
		
	<link rel="stylesheet" href="css/global.css">
	<link rel="stylesheet" href="css/snackbar.css">
	
	<script> window.parent.document.title = 'Update Attendance'; </script>
	<style>
		#backbutton{
			visibility: collapse;
		}
		
		#datadiv > *{
			display: inline;
		}
		
		#txt{
			width: 100%; 
			margin-top: 1em; 
			font-size: 1.5em; 
			color:#2c3e50;
		}
		
		.sbutton{
			padding: 2.5% 20%;
			margin: 10% 0%;
			background-color: #13A080;
			color: #fff;
			border-radius: 50px;
			border: none;
			cursor: pointer;
			font-weight: bold;
			transition:all 0.3s ease;
			opacity:0.9;
		}
		.sbutton:hover{
			box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
			opacity:1;
			border-radius:50px;
		}
		input,select{
			transition:all 0.3s ease;
			border: none;
			border-bottom: 2px solid #2c3e50;
		}
		input:focus,select:focus{
			border-bottom: 2px solid #13a080;
		}
	</style>
</head>

<body>
	<?php 	
		require('navbar.php');
		if ($_SESSION['rights']!="a"){
			echo ("<script> alert('Admin Rights Required'); </script>");
			exit();
		}			
	?>
	<div class="container" style="margin-top:60px">
		<div class="row">
			<div class="col-md-8 offset-md-2">
				<?php require('teacherdata.php');?>
				
				<form action="updateatt.php" method="post" name="datepicker" onsubmit="return validateForm()" style="margin: 5% 0%;">
					
					<div class="row">
						<div class="col-sm-12 col-md-10 offset-md-1" id="datadiv">
							<select name="classvalue" id="classvalue" style="width: 100%; font-size: 2em; color:#2c3e50;" required>
								<option value="" disabled selected>Select Class</option>
								<?php
									for($i=0;$i<sizeof($subids);$i++)
									{
										$classvalue= $years[$i]."-".$coursenames[$i]."-".$divisions[$i]."-".$classids[$i];
										echo(" <option value = '".$classvalue."'> ".ucwords($years[$i])." ".ucwords($coursenames[$i])." (Div ".ucwords($divisions[$i]).") </option> ");
									}
								?>
							</select>
							<input id="txt" name="rollno" type="number" autocomplete="off" placeholder="Roll No" min="0" required></input>
							<input id="txt" name="reason" type="text" autocomplete="off" placeholder="Reason" pattern="[^()/><\][\\\x22,;|]+""" title="Special Characters are not allowed" ></input>
							<div style="display: block; margin-top: 1em; text-align: center;">
								<h5>Start Date: <input type="date" name="sdate" id="sdate" value="<?php echo date('Y-m-01'); ?>" required></input></h5>
								<br>
								<h5>End Date: <input type="date" name="edate" id="edate"  value="<?php echo date('Y-m-d'); ?>" required></input></h5>
							</div>
						</div>
						
						<div class="col-sm-12 col-md-6 offset-md-3">
							<center><button type="submit" value="start" class="sbutton">UPDATE</button></center>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div id="snackbar">
			<p id="error"></p>
		</div>
	</div>
</body>
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
	
	function validateForm() {
		var x = document.forms["datepicker"]["sdate"].value;
		var y = document.forms["datepicker"]["edate"].value;
		
		//get current date
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1; //January is 0!
		var yyyy = today.getFullYear();
		if(dd<10) {
			dd = '0'+dd
		} 
		if(mm<10) {
			mm = '0'+mm
		} 

		//today = mm + '/' + dd + '/' + yyyy;
		today = yyyy + '-' + mm + '-' + dd;
		
		if (x > y) {
			document.getElementById("sdate").style.border = "2px solid #E74C3C";
			showsnack("Start date cannot be <br> greater than End Date");
			return false;
		}
		else if(y>today){
			document.getElementById("edate").style.border = "2px solid #E74C3C";
			showsnack("End date cannot be <br> greater than Current Date");
			return false;
		} else{
			var choice =  confirm("All Attendance Between "+x+" AND "+y+" Would be Marked Present. Do You Want To Continue?");

			if (choice == true) {
				return true;
			} else {
				return false;
			} 
			
		}
	}
	<?php
		if(isset($_GET['u'])&&isset($_GET['s']))
		{
			if(($_GET['u']!=-1)&&$_GET['s']==0)
			{
				echo(" showsnack('Cannot Update Attendance Of Roll No: ".$_GET['u']."') ");
			}
			if(($_GET['u']!=-1)&&$_GET['s']==1)
			{
				echo(" showsnack('Succesfully Updated Attendance Of Roll No: ".$_GET['u']."') ");
			}
			if(($_GET['u']!=-1)&&$_GET['s']==2)
			{
				echo(" showsnack('Roll No: ".$_GET['u']." Blocked. Please Unblock And Try Again') ");
			}
		}
	?>
</script>