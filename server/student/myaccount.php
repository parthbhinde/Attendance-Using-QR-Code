<?php
require('connectS.php');

if(!(isset($_SESSION['id']))){
	header('Location: studentlogin.php');
	die();
}
	
		//initalize vars
		$rollno = $_SESSION['id'];
		$name = $_SESSION['student-username'];
		$classid = $_SESSION['classid'];
		
		$eve = 0;
		
		if(isset($_GET['new'])){
			$new = $_GET['new'];
			function validateDate($date, $format = 'Y-m-d')
			{
				$d = DateTime::createFromFormat($format, $date);
				// The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
				return $d && $d->format($format) === $date;
			}
			if(validateDate($new)){
				$sdate = date("Y-m-01", strtotime($new));
				$edate = date("Y-m-t", strtotime($new));
			}
			else{
				$eve = 1;
				$sdate = date('Y-m-01');
				$edate = date('Y-m-d');
			}
			
		}else{
			$eve = 2;
			$sdate = date('Y-m-01');
			$edate = date('Y-m-d');
		}
		
		$query = "SELECT studentinfo.classid, 
					 classinfo.classid, classinfo.courseid, classinfo.year, classinfo.division, 
					 courseinfo.courseid, courseinfo.coursename 
					 FROM studentinfo INNER JOIN classinfo ON studentinfo.classid = classinfo.classid 
					 INNER JOIN courseinfo ON courseinfo.courseid = classinfo.courseid 
					 WHERE studentinfo.classid = ? AND studentinfo.rollno = ? ";
					 
		$studentq =  $conn->prepare($query);
		$studentq->bind_param("ii", $classid, $rollno);
		$studentq->execute();
		$result = $studentq->get_result();
		
		 while($r = $result->fetch_assoc() ) {
			 $year = $r['year'];
			 $coursename = $r['coursename']; 
			 $division = $r['division'];
			 $courseid = $r['courseid'];
		 }
		
		$atttable =  "`".$year.$coursename."-".$division."`";
		
		$output = array();
		$total=0;$p=0;
		
		//get attendance data 
		$attdataquery =  "SELECT DATE(time), TIME(time), subid, r".$rollno." , c".$rollno." FROM ".$atttable." WHERE date BETWEEN ? AND ? ;";
		$attdata = $conn->prepare($attdataquery);
		$attdata->bind_param("ss", $sdate, $edate);
		$attdata->execute();
		$result2 = $attdata->get_result();
		
		while($row = $result2->fetch_assoc() )
		{
			//get subject name
			$lecq = $conn->prepare("SELECT subname FROM subjectinfo WHERE subjectid = ? ;");
			$lecq->bind_param("i", $row['subid']);
			$lecq->execute();
			$result3 = $lecq->get_result();
			$rowlec = $result3->fetch_assoc();
			
			$total++;
			//change value of status
			if($row['r'.$rollno]==1){
				$status = "Present";
				$p++;
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
		
		if ($total == 0) {
			$per = -1;
		} else {
			$per = ($p*100)/$total;
		}
		
		
		
		if($per<25){
			$color = "#E74C3C";
		}elseif($per>=25 && $per<50){
			$color = "#3498DB";
		}else{
			$color = "#13a080";
		}
		
		$atttable = ucwords(str_replace("`","",$atttable));

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Attendance</title>
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
	
  <link rel="stylesheet" href="../css/global.css">
  <link rel="stylesheet" href="../css/snackbar.css">
  
  
  <script> window.parent.document.title = '<?php echo("Welcome ".ucwords($name));?>'; </script>
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
		}
		.card:hover{
			border: 2px solid #343a40;
			box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
		}
		@media only screen and (max-width: 576px) {
			.per{
				font-size: 2rem;
			}
		}
		.datahead h5{
			font-size: 1.1rem;
		}
	</style>
  
	</head>
<body>

	<?php require('../loader.php'); ?>
	<?php require('navbarS.php'); ?>
	<div class="container-fluid" style="margin-top:59px">
		<div class="row">
			<div class="col-12 col-md-8 offset-md-2">
				<div class="card col-md-8 offset-md-2" style="margin-bottom: 2%; margin-top:2%;">
					<div class="card-body">
						<div class="row">
							<div class="col-6 col-md-6">
								<p style="font-size: 1.25rem;"> <?php echo $rollno.". ".ucwords($name);?> <br><?php echo $atttable;?></p>
							</div>
							<div class="col-6 col-md-6">
								<?php 
									if($per == -1){
										echo ('<h5 class="per" style="color: '.$color.'"> No Data </h5>');
									}else{
										echo ('<h5 class="per count" style="color: '.$color.'"> '.round($per).' </h5>');
									}
								?>
							</div>
						</div>
					</div>
				</div>
				<div class="datahead" style="margin-bottom: 0; padding: 2%; color: white; background-color: <?php echo $color;?>;">	
					<?php 
						$prev = date("Y-m-d",strtotime("-1 month", strtotime($sdate)));
						$next = date("Y-m-d",strtotime("+1 month", strtotime($sdate)));
					?>
					<center>
						<div class="row">
							<div class="col-2">
								<h5> 
									<a <?php echo('href="myaccount.php?new='.$prev.'"');?> id="prev" style="text-decoration: none;color: white;"> &#9664; </a> 
								</h5>
							</div>
							<div class="col-8">	
								<h5 id="swipearea">
									<?php echo("FROM ".date("dS M Y", strtotime($sdate))." TO ".date("dS M Y", strtotime($edate)))?>
								</h5>
							</div>
							<div class="col-2">
								<h5>  
									<a <?php echo('href="myaccount.php?new='.$next.'"');?> id="next" style="text-decoration: none;color: white;"> &#9654; </a> 
								</h5>
							</div>
						</div>
					</center>
				</div>
				<!-- detail table of attendace -->
				<table class="table table-striped" style="text-align: center; border-top: 2px solid <?php echo $color;?>;">
					<thead id="swipearea2" style="position: sticky; top:57px; color: white;	 background-color: <?php echo $color;?>;">
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
								echo("<td><span id='infobutton' href='#' onclick='showsnack(\"$comment\")'>".$value['status']."</span></td>");
							}
							echo("</tr>");
						}
					?>
					</tbody>
				</table>
			</div>
		</div>
		
		
	</div>
	<div id="snackbar">
		<p id="error"></p>
	</div>
	<script>
		<?php 
			if($eve==1){
				echo "showsnack('Wrong Date. Using Default Dates');";
			}
			if($eve==2){
				echo "showsnack('Swipe Left/Right On Dates To Switch');";
			}
		?>
	
		//swipe gestures
		function detectswipe(el,func) {
		  swipe_det = new Object();
		  swipe_det.sX = 0; swipe_det.sY = 0; swipe_det.eX = 0; swipe_det.eY = 0;
		  var min_x = 30;  //min x swipe for horizontal swipe
		  var max_x = 30;  //max x difference for vertical swipe
		  var min_y = 50;  //min y swipe for vertical swipe
		  var max_y = 60;  //max y difference for horizontal swipe
		  var direc = "";
		  ele = document.getElementById(el);
		  ele.addEventListener('touchstart',function(e){
			var t = e.touches[0];
			swipe_det.sX = t.screenX; 
			swipe_det.sY = t.screenY;
		  },false);
		  ele.addEventListener('touchmove',function(e){
			e.preventDefault();
			var t = e.touches[0];
			swipe_det.eX = t.screenX; 
			swipe_det.eY = t.screenY;    
		  },false);
		  ele.addEventListener('touchend',function(e){
			//horizontal detection
			if ((((swipe_det.eX - min_x > swipe_det.sX) || (swipe_det.eX + min_x < swipe_det.sX)) && ((swipe_det.eY < swipe_det.sY + max_y) && (swipe_det.sY > swipe_det.eY - max_y) && (swipe_det.eX > 0)))) {
			  if(swipe_det.eX > swipe_det.sX) direc = "r";
			  else direc = "l";
			}
			//vertical detection

			if (direc != "") {
			  if(typeof func == 'function') func(el,direc);
			}
			direc = "";
			swipe_det.sX = 0; swipe_det.sY = 0; swipe_det.eX = 0; swipe_det.eY = 0;
		  },false);  
		}

		function myfunction(el,d) {
		  if(d=="l") {
			  url =  <?php echo('href="myaccount.php?new='.$next.'"');?>;
			  window.location.replace(url);
		  }
		  if(d=="r") {
			  url =  <?php echo('href="myaccount.php?new='.$prev.'"');?>;
			  window.location.replace(url);
		  }
		}
	
		//counter
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
		
		detectswipe('swipearea',myfunction);
		detectswipe('swipearea2',myfunction);
		
	</script>
</body>
</html>