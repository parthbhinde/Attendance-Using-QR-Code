<?php

	require('../connect.php');
	
	if(isset($_GET['studentvalue'])){
		
		foreach($_GET as $loc=>$item)
		$_GET[$loc] = base64_decode(urldecode($item));
		
		$studentvalue = explode("/",$_GET['studentvalue']);
		//insert values of post
		$rollno = $studentvalue[0];
		$name = $studentvalue[1];
		$sdate = $studentvalue[2];
		$edate = $studentvalue[3];
		$per = $studentvalue[4];
		$div = $studentvalue[5];
		
		if($per<25){
			$color = "#E74C3C";
		}elseif($per>=25 && $per<75){
			$color = "#3498DB";
		}else{
			$color = "#13a080";
		}
		
		$output = array();
		
		//get attendance data 
		$attdata = mysqli_query($conn,  "SELECT DATE(time), TIME(time), subid, r".$rollno." , c".$rollno." FROM ".$div." WHERE date BETWEEN '".$sdate."' AND '".$edate."';") or die(mysqli_error($conn));
		while($row=mysqli_fetch_assoc($attdata))
		{
			//get subject name
			$lec = mysqli_query($conn,"SELECT subname FROM subjectinfo WHERE subjectid=".$row['subid']) or die(mysqli_error($conn));
			$rowlec = mysqli_fetch_assoc($lec);
			
			//change value of status
			if($row['r'.$rollno]==1){
				$status = "Present";
			}else{
				$status = "Absent";
			}
			
			//push final data
			$output[] = array(
				'date' => $row['DATE(time)'],
				'time' => $row['TIME(time)'],
				'subname' => $rowlec['subname'],
				'status' => $status,
				'info' => $row['c'.$rollno],
			);
		}
		
		$div = ucwords(str_replace("`","",$div));
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
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link rel="stylesheet" href="../css/global.css">
  <link rel="stylesheet" href="../css/snackbar.css">
  
  		
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<!-- Popper JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script> 
	
  
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
		
		function sort() {
			var tbody = document.getElementById('table-body'),
			  childNodes = tbody.childNodes,
				i = childNodes.length;
		  
		  while (i--)
				tbody.appendChild(childNodes[i]);  
		  
		}
		
  </script>
  
	<style>
		.per{
			font-size: 3.25rem; 
			text-align: end;
		}
		#infobutton{
			color: #007bff;
			text-decoration: none;
			background-color: transparent;
			cursor: pointer;
		}
		#infobutton:focus , #infobutton:hover {
			 text-decoration: underline;
		}
		#d{
			cursor: pointer;
		}
		.card{
			border: 2px solid gray;
			border-radius: 10px;
			transition:all 0.3s ease;
			margin-bottom: 2%;
			margin-top: 2%;
		}
		.card:hover{
			border: 2px solid #343a40;
			box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
		}
		
	</style>
  
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

	</head>
<body>

	<?php require('../loader.php'); ?>
	<?php require('navbarR.php'); ?>
	<div class="container-fluid" style="margin-top:59px">
		<div class="row">
			<div class="col-12 col-md-8 offset-md-2">
				<div class="card col-md-6 offset-md-3">
					<div class="card-body">
						<div class="row">
							<div class="col-6 col-md-6">
								<p style="font-size: 1.25rem;">Name: <?php echo ucwords($name);?> <br>Roll No: <?php echo $rollno;?> <br>Class: <?php echo $div;?></p>
							</div>
							<div class="col-6 col-md-6">
								<?php echo ('<h3 class="per count" style="color: '.$color.'"> '.round($per).' </h3>');?>
							</div>
						</div>
					</div>
				</div>
				<center><h5 style="margin-bottom: 0; padding: 2% 0%; color: white; background-color: <?php echo $color;?>;"><?php echo("FROM ".date("dS M Y", strtotime($sdate))." TO ".date("dS M Y", strtotime($edate)))?></h5></center>
				<!-- detail table of attendace -->
				<table class="table table-striped" style="text-align: center; border-top: 2px solid <?php echo $color;?>;">
					<thead style="position: sticky; top:57px; color: white;	 background-color: <?php echo $color;?>;">
					  <tr>
						<th style="width: 20%;" onclick="sort()" id="d"> TIME  &#8597;</th>
						<th style="width: 60%;">LECTURE</th>
						<th style="width: 20%;">STATUS</th>
					  </tr>
					</thead>
					<tbody id="table-body">
					<?php
						foreach ($output as &$value) {
							echo("<tr>");
							echo("<td>".$value['date']."<br>".$value['time']."</td>");
							echo("<td>".ucwords($value['subname'])."</td>");
							$comment = $value['info'];
							if($comment==""){
								echo("<td>".$value['status']."</td>");
							}else{
								echo("<td><span id='infobutton' onclick='showsnack(\"$comment\")'>".$value['status']."</span></td>");
							}
							echo("</tr>");
						}
					?>
					</tbody>
				</table>
			</div>
		</div>
		
		<div id="snackbar">
			<p id="error"></p>
		</div>
	</div>
	
	<script>
		$('.count').each(function () {
			$(this).prop('Counter',0).animate({
				Counter: $(this).text()
			}, {
				duration: 2000,
				easing: 'swing',
				step: function (now) {
					$(this).text(Math.ceil(now)+"%");
				}
			});
		});
	</script>
</body>
</html>