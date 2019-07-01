<?php

	require('../connect.php');
	
	if(isset($_GET['sdate']) && isset($_GET['edate']) && isset($_GET['classvalue'])){
		
		$values = explode("-",$_GET['classvalue']);
		//insert values of post
		$year = strtolower($values[0]);
		$coursename = strtolower($values[1]);
		$division = strtolower($values[2]);
		$subid = $values[3];
		$classid = $values[4];
		
		$sdate = $_GET['sdate'];
		$edate = $_GET['edate'];
		
		$atttable = "`".$year.$coursename."-".$division."`";
		
		//Number Of Lecs
		$totalquery = "SELECT COUNT(attid) FROM ".$atttable." WHERE date BETWEEN '".$sdate."' AND '".$edate."';";
		$totallecq=mysqli_fetch_assoc(mysqli_query($conn,$totalquery)) or die(mysqli_error($conn)); 
		$totallec = $totallecq['COUNT(attid)'];
		
		$output = array();
		
		if($totallec>0){
			
			//Get student info
			$sinfo = mysqli_query($conn, "SELECT rollno,name FROM studentinfo WHERE classid = ".$classid." ORDER By rollno");			
			while($row = mysqli_fetch_assoc($sinfo))
			{
				
				//Get attendace data of each roll no dynamically
				$attquery = "SELECT r".$row['rollno']." FROM ".$atttable." WHERE date BETWEEN '".$sdate."' AND '".$edate."';";
		echo $attquery;

				$attdata = mysqli_query($conn, $attquery) or die(mysqli_error($conn));
				
				$present=0;
				
				while($rowatt = mysqli_fetch_assoc(($attdata)))
					{
						foreach ($rowatt as $value) {
							//iterate each value
							if($value == "1"){
								$present++;
							}
						}
					}
				
				//update data
				$rollno = str_replace("r","",$row['rollno']);
				$name = $row['name'];
				$percent = round(($present/$totallec*100),2);
				
				//push final data
				$output[] = array(
				  'rollno' => $rollno,
				  'name' => $name,
				  'percent' => $percent,
				);
			}
		}
	}
	else{
		echo("Invalid Parameters");
		die();
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Attendance</title>
  <meta charset="utf-8">
  <meta name="viewport" conntent="width=device-width, initial-scale=1">
  		
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<!-- Popper JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script> 
	
  <link rel="stylesheet" href="../css/global.css">
  <script> window.parent.document.title = '<?php echo(ucwords($year)." ".ucwords($coursename)."-".ucwords($division)); ?>'; </script>
   
  <style>
	.headdata{
		color: #ECF0F1;
		background-color: #2C3E50;
		padding: 0.4em;
		margin-left: -15px;
		margin-right: -15px;
		box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.2), 0 2px 10px 0 rgba(0, 0, 0, 0.19);
	}
	.table{
		padding: 0;
		text-align: center;
		margin-top: 0;
	}
	a:hover{
		text-decoration: none;
	}
	@media only screen and (max-width: 768px) {
			.headdata *{
				font-size: 1.25rem;
			}
	}
	
  </style>
</head>
<body>
	<?php require('../loader.php'); ?>
	<?php require('navbarR.php'); ?>
	<div class="conntainer-fluid" style="margin-top:57px">
		<div class="headdata">
			<center><h4> <?php echo("Attendance of ".ucwords($year)." ".ucwords($coursename)."-".ucwords($division)." From ".date("dS M Y", strtotime($sdate))." To ".date("dS M Y", strtotime($edate)));?> </h4></center>
		</div>
		<div class="row">
		
			<!-- table of attendace -->
			<div class="col-md-12 col-lg-4">
				<center><h5 style="margin-bottom: 0; padding: 2% 0%; color: white; background-color: #E74C3C;">Less Than 25%</h5></center>
				<table class="table table-striped" style="border-top: 2px solid #E74C3C;">
					<thead style="color: white;	 background-color: #E74C3C;">
					  <tr>
						<th style="width: 20%;">NO</th>
						<th style="width: 50%;">NAME</th>
						<th style="width: 15%;">PERCENTAGE</th>
						<th style="width: 15%;">DETAIL</th>
					  </tr>
					</thead>
					<tbody>
					  <?php
						foreach ($output as &$value) {
							if($value['percent']<25)
							{
								echo("<tr>");
								echo("<td>".$value['rollno']."</td>");
								echo("<td>".$value['name']."</td>");
								echo("<td>".$value['percent']."%</td>");
								$studentvalue = urlencode(base64_encode($value['rollno']."/".$value['name']."/".$sdate."/".$edate."/".$value['percent']."/".$atttable));
								echo("<td> <a href='detailatt.php?studentvalue=".$studentvalue."' >&#10149;</a></td>");
								echo("</tr>");
							}
						}
					  ?>
					</tbody>
				</table>
			</div>
			
			<div class="col-md-12 col-lg-4">
				<center><h5 style="margin-bottom: 0; padding: 2% 0%; color: white; background-color: #3498DB;">Between 25% To 50%</h5></center>
				<table class="table table-striped" style="border-top: 2px solid #3498DB;">
					<thead style="color: white; background-color: #3498DB;">
					  <tr>
						<th style="width: 20%;">NO</th>
						<th style="width: 50%;">NAME</th>
						<th style="width: 15%;">PERCENTAGE</th>
						<th style="width: 15%;">DETAIL</th>
					  </tr>
					</thead>
					<tbody>
					  <?php
						foreach ($output as &$value) {
							if($value['percent']>=25 && $value['percent']<50)
							{
								echo("<tr>");
								echo("<td>".$value['rollno']."</td>");
								echo("<td>".$value['name']."</td>");
								echo("<td>".$value['percent']."%</td>");
								$studentvalue = urlencode(base64_encode($value['rollno']."/".$value['name']."/".$sdate."/".$edate."/".$value['percent']."/".$atttable));
								echo("<td> <a href='detailatt.php?studentvalue=".$studentvalue."' >&#10149;</a></td>");
								echo("</tr>");
							}
						}
					  ?>
					</tbody>
				</table>
			</div>
			
			<div class="col-md-12 col-lg-4">
				<center><h5 style="margin-bottom: 0; padding: 2% 0%; color: white; background-color: #13a080;">Greater Than 50%</h5></center>
				<table class="table table-striped" style="border-top: 2px solid #13a080;">
					<thead style="color: white; background-color: #13a080;">
					  <tr>
						<th style="width: 20%;">NO</th>
						<th style="width: 50%;">NAME</th>
						<th style="width: 15%;">PERCENTAGE</th>
						<th style="width: 15%;">DETAIL</th>
					  </tr>
					</thead>
					<tbody>
					  <?php
						foreach ($output as &$value) {
							if($value['percent']>=50)
							{
								echo("<tr>");
								echo("<td>".$value['rollno']."</td>");
								echo("<td>".$value['name']."</td>");
								echo("<td>".$value['percent']."%</td>");
								$studentvalue = urlencode(base64_encode($value['rollno']."/".$value['name']."/".$sdate."/".$edate."/".$value['percent']."/".$atttable));
								echo("<td> <a href='detailatt.php?studentvalue=".$studentvalue."' >&#10149;</a></td>");
								echo("</tr>");
							}
						}
					  ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</body>
</html>