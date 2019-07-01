

<?php 	
	if(!(isset($_SESSION['teacher-name']))){
		header('Location: ../teacherlogin.php');
		die();
	}
?>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style>

	#datadiv > *{
		display: inline;
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
		border: none;
		border-bottom: 2px solid #2c3e50;
		background-color: white;
	}
	input:focus,select:focus{
		border-bottom: 2px solid #13a080;
	}
	#sdate,#edate, #classvalue{
		color: #2c3e50;
		width: 100%;
	}
	</style>
</head>


<div id="reviewattpage">
	<?php require('teacherdata.php');?>
	
	<form action="reviewattendance/getatt.php" name="datepicker" method="get" onsubmit="return validateForm()" style="margin: 5% 0%;">
		
		<div class="row">
			<div class="col-sm-12 col-md-10 offset-md-1" id="datadiv">
				<h4>
					<select name="classvalue" id="classvalue" required>
						<option value="" disabled selected>Select Class</option>
						<?php
							for($i=0;$i<sizeof($subids);$i++)
							{
								$classvalue= $years[$i]."-".$coursenames[$i]."-".$divisions[$i]."-".$subids[$i]."-".$classids[$i];
								echo(" <option value = '".$classvalue."'> ".ucwords($years[$i])." ".ucwords($coursenames[$i])." (Div ".ucwords($divisions[$i]).") </option> ");
							}
						?>
					</select>
				</h4>
				<div style="display: block; margin: 5% 0% 0% 0%;"><h5>Start Date: <input type="date" name="sdate" id="sdate" value="<?php echo date('Y-m-01'); ?>" required></input></h5></div><br>
				<div style="display: block; margin: 0% 0% 5% 0%;"><h5>End Date: <input type="date" name="edate" id="edate"  value="<?php echo date('Y-m-d'); ?>" required></input></h5></div>
			</div>
			
			<div class="col-sm-12 col-md-6 offset-md-3">
			<center><button type="submit" value="start" class="sbutton" id="sbutton">SUBMIT</button></center>
			</div>
		</div>
	</form>
	<script>
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
				if(y>today){
					document.getElementById("edate").style.border = "2px solid #E74C3C";
					showsnack("End date cannot be <br> greater than Current Date");
					return false;
				}
			}
	</script>
</div>