<?php
	require('../connect.php');
	
	foreach($_GET as $loc=>$item)
		$_GET[$loc] = base64_decode(urldecode($item));
			
	$values = explode("-",$_GET['classvalue']);
	
	//insert values of post
	$year = strtolower($values[0]);
	$coursename = strtolower($values[1]);
	$division = strtolower($values[2]);
	$subid = $values[3];
	$classid = $values[4];
?>

<!DOCTYPE html>
<html lang="en" style="overflow-x: hidden;">
<head>
  <title>Current Attendance</title>
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
  <link rel="stylesheet" type="text/css" href="../css/attdata.css"/> 
  <link rel="stylesheet" href="../css/snackbar.css">
  
  <script> window.parent.document.title = 'Current Attendance'; </script>
</head>

<body>
	<?php require('../loader.php'); ?>

	<nav class="navbar navbar-expand-sm navbar-dark fixed-top" style="height: 65px">
		<h2 style="width:45%;" id="top">Total Present : </h2>
		<h2 style="width:45%;" id="top2"><?php echo(ucwords($year)." ".ucwords($coursename)."-".ucwords($division));?></h2>
		
		<button id="dn-btn" type="button" data-toggle="modal" data-target="#ProxyModal" > &#x2753;</button>
		<button id="dn-btn" type="button" data-toggle="modal" data-target="#LecModal"> DONE</button>
	</nav>
	
	<div id="allModals">
	
	  <!-- ADD Student Modal -->
	  <div class="modal fade" id="AddModal">
		<div class="modal-dialog modal-lg modal-dialog-centered">
		  <div class="modal-content">
		  
			<!-- Modal Header -->
			<div class="modal-header">
			  <h4 class="modal-title"> Add a Student </h4>
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			
			<!-- Modal body -->
			<div class="modal-body">
			  <form id="addstud" action="addstudent.php?classvalue=<?php echo (urlencode(base64_encode($_GET['classvalue'])));?> " method="post">
				 <input id="rollno" name="rollno" type="number" placeholder="Roll Number" class="validate" autocomplete="off" min="0" required> </input><br>
				 <input id="reason" name="reason" type="text" autocomplete="off" pattern="[^()/><\][\\\x22,;|]+""" class="validate" title="Special Characters are not allowed" placeholder="Reason"></input>
			  
			</div>
			
			<!-- Modal footer -->
			<div class="modal-footer">
			   <input type="submit" name="submit" value="ADD" >
			   </form>
			</div>
			
		  </div>
		</div>
	  </div> 

	  <!-- Number Of Lec Modal -->
	  <div class="modal fade" id="LecModal">
		<div class="modal-dialog modal-lg modal-dialog-centered">
		  <div class="modal-content">
		  
			<!-- Modal Header -->
			<div class="modal-header">
			  <h4 class="modal-title"> Number Of Lectures </h4>
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			
			<!-- Modal body -->
			<div class="modal-body">
			  <form id="addstud" action="lecno.php?classvalue=<?php echo (urlencode(base64_encode($_GET['classvalue'])));?>  " method="post">
				  <input id="lecno" name="nolec" value="1" min="1" max="3" onfocus="this.value=''" type="number" class="validate" autocomplete="off" placeholder="Number of lecs">
			</div>
			
			<!-- Modal footer -->
			<div class="modal-footer">
			   <input type="submit" name="submit" value="FINISH" >
			   </form>
			</div>
			
		  </div>
		</div>
	  </div> 
	  
	  <!-- Proxy Modal -->
	  <div class="modal fade" id="ProxyModal">
		<div class="modal-dialog modal-lg" style="height: 90%;">
		  <div class="modal-content" style="height: 100%; width:100%;">
		  
			<!-- Modal Header -->
			<div class="modal-header" style="margin:10;">
			  <h4 class="modal-title"> Attendance From Same Device </h4>
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			
			<!-- Modal body -->
			<div class="modal-body" style="height: 90%; width:100%;">
				<object type="text/html" data="multiatt.php?classvalue=<?php echo (urlencode(base64_encode($_GET['classvalue'])));?> " style=" width:90%; height: 100%;"></object> 
			</div>
		  </div>
		</div>
	  </div>
	</div>
	
	<!-- table of students -->
    <div style="margin-top: 64px; padding: 0px 15px;">
	<?php

		
		$atttable = "`".$year.$coursename."-".$division."`";
		
		//get attendance id
		$query= "SELECT MAX(attid) FROM ".$atttable;
		$attidq = $conn->prepare($query);
		$attidq->execute();
		$result = $attidq->get_result();

		//create assoc array of result
		$row = $result->fetch_assoc();
		$attidq->close();

		$attid = $row['MAX(attid)'];
		
		
		//Set status to 1
		$q = "UPDATE ".$atttable." SET `status` = 1 ORDER BY attid DESC LIMIT 1 ";
		$status = $conn->prepare($q);
		$status->execute();	
		$status->close();
		
		$total = 0;
		
		//Get student info
		$sinfoq = $conn->prepare("SELECT rollno FROM studentinfo WHERE classid = ? ORDER By rollno");
		$sinfoq->bind_param("i",$classid);
		$sinfoq->execute();
		$sinfo = $sinfoq->get_result();
		

		$attdatamain = array();		
		while($row = $sinfo->fetch_assoc())
		{
			//Get attendace data of each roll no dynamically
			$sql = "SELECT r".$row['rollno']." FROM ".$atttable." WHERE attid = ? and r".$row['rollno']."= '1'";
			
			$attdataq = $conn->prepare($sql);
			$attdataq->bind_param("i",$attid);
			$attdataq->execute();
			$attdata = $attdataq->get_result();
			
			
			if ($attdata->num_rows >0){
				while($property = $attdata-> fetch_field())
				{
					$total++;
					$did = $property -> name;
					$did=str_replace("r","",$did);
					
					$displayq = $conn->prepare("SELECT rollno,name FROM studentinfo WHERE rollno = ? AND classid = ? ");
					$displayq->bind_param("ii",$did,$classid);
					$displayq->execute();
					$display = $displayq->get_result();
					
					$inner = array();
					
					while($dispdata = $display->fetch_assoc() )
					{	
						//generate data
						array_push($inner,$did,$dispdata['name'],$dispdata['rollno']);
						 
					}
					$displayq->close();
				
					array_push($attdatamain,$inner);
					
				}
				$attdataq->close();
			}
		}
		$sinfoq->close();

		$size = (count($attdatamain));

		function getParts($number, $parts)
		{
			return array_map('round', array_slice(range(0, $number, $number / $parts), 1));
		}

		if($size>=1){
		$parts=(getParts($size, 4));

		echo('<div class="row">');
		//table 1
		{
			echo('<div class="col-12 col-md-6 col-lg-3" id="mytable">');
			echo('<table class="table ">
					<thead id="tablehead" style="visibility: visible;">
						<tr >
							<th style="width: 25%;">ROLL NO</th>
							<th style="width: 50%;">NAME</th>
							<th style="width: 25%;">ACTION</th>
						</tr>
					</thead>
					<tbody>');
			for($i=0;$i<$parts[0];$i++)
			{
				  echo     "<tr>";
					 echo       	"<td id='td-td'>".$attdatamain[$i][0]."</td>";
					 echo       	"<td id='td-td'>".$attdatamain[$i][1]."</td>";
					 echo       	"<td >";
					 //block
					 echo     	 		"<form action='blockstudent.php?classvalue=".urlencode(base64_encode($_GET['classvalue']))."' id='myaction' method='post' style='float: left; width=50%;'>";
					 echo      				"<input name='rollno' type='text' value= ".$attdatamain[$i][2]." style= 'display:none;'/>";
					 echo					"<input name='classid' type='text' value= ".$classid." style= 'display:none;'/>";
					 echo      				"<button id='btndel' type='submit'  name = 'submit'>&#8709;</button>";
					 echo       	    "</form>";
					 //remove
					 echo      		"<form action='deletestudent.php?classvalue=".urlencode(base64_encode($_GET['classvalue']))."'  id='myaction' method='post'  style='float: right; width=50%;'>";
					 echo      			"<input name='rollno' type='text' value= ".$attdatamain[$i][2]." style= 'display:none;'/>";
					 echo				"<input name='classid' type='text' value= ".$classid." style= 'display:none;'/>";
					 echo      			"<button id='btndel' type='submit'  name = 'submit'>&#x2716</button>";
					 echo           "</form>";
					 echo       	"</td>";
					 echo    	" </tr>";  
			}
			echo('</tbody>
					</table>
					</div>');
		}

		//table 2
		{
			echo('<div class="col-12 col-md-6 col-lg-3" id="mytable">');
			echo('<table class="table ">
					<thead id="tablehead">
						<tr >
							<th style="width: 25%;">ROLL NO</th>
							<th style="width: 50%;">NAME</th>
							<th style="width: 25%;">ACTION</th>
						</tr>
					</thead>
					<tbody>');
			for($i=$parts[0];$i<$parts[1];$i++)
			{
					 echo     "<tr>";
					 echo       	"<td id='td-td'>".$attdatamain[$i][0]."</td>";
					 echo       	"<td id='td-td'>".$attdatamain[$i][1]."</td>";
					 echo       	"<td >";
					 //block
					 echo     	 		"<form action='blockstudent.php?classvalue=".urlencode(base64_encode($_GET['classvalue']))."' id='myaction' method='post' style='float: left; width=50%;'>";
					 echo      				"<input name='rollno' type='text' value= ".$attdatamain[$i][2]." style= 'display:none;'/>";
					 echo					"<input name='classid' type='text' value= ".$classid." style= 'display:none;'/>";
					 echo      				"<button id='btndel' type='submit'  name = 'submit'>&#8709;</button>";
					 echo       	    "</form>";
					 //remove
					 echo      		"<form action='deletestudent.php?classvalue=".urlencode(base64_encode($_GET['classvalue']))."'  id='myaction' method='post'  style='float: right; width=50%;'>";
					 echo      			"<input name='rollno' type='text' value= ".$attdatamain[$i][2]." style= 'display:none;'/>";
					 echo				"<input name='classid' type='text' value= ".$classid." style= 'display:none;'/>";
					 echo      			"<button id='btndel' type='submit'  name = 'submit'>&#x2716</button>";
					 echo           "</form>";
					 echo       	"</td>";
					 echo    	" </tr>"; 
			}
			echo('</tbody>
				</table>
				</div>');
		}

		//table 3
		{
			echo('<div class="col-12 col-md-6 col-lg-3" id="mytable">');
			echo('<table class="table " ">
					<thead id="tablehead">
						<tr >
							<th style="width: 25%;">ROLL NO</th>
							<th style="width: 50%;">NAME</th>
							<th style="width: 25%;">ACTION</th>
						</tr>
					</thead>
					<tbody>');
			for($i=$parts[1];$i<$parts[2];$i++)
			{
					 echo     "<tr>";
					 echo       	"<td id='td-td'>".$attdatamain[$i][0]."</td>";
					 echo       	"<td id='td-td'>".$attdatamain[$i][1]."</td>";
					 echo       	"<td >";
					 //block
					 echo     	 		"<form action='blockstudent.php?classvalue=".urlencode(base64_encode($_GET['classvalue']))."' id='myaction' method='post' style='float: left; width=50%;'>";
					 echo      				"<input name='rollno' type='text' value= ".$attdatamain[$i][2]." style= 'display:none;'/>";
					 echo					"<input name='classid' type='text' value= ".$classid." style= 'display:none;'/>";
					 echo      				"<button id='btndel' type='submit'  name = 'submit'>&#8709;</button>";
					 echo       	    "</form>";
					 //remove
					 echo      		"<form action='deletestudent.php?classvalue=".urlencode(base64_encode($_GET['classvalue']))."'  id='myaction' method='post'  style='float: right; width=50%;'>";
					 echo      			"<input name='rollno' type='text' value= ".$attdatamain[$i][2]." style= 'display:none;'/>";
					 echo				"<input name='classid' type='text' value= ".$classid." style= 'display:none;'/>";
					 echo      			"<button id='btndel' type='submit'  name = 'submit'>&#x2716</button>";
					 echo           "</form>";
					 echo       	"</td>";
					 echo    	" </tr>"; 
			}
			echo('</tbody>
				</table>
				</div>');
		}

		//table 4
		{
			echo('<div class="col-12 col-md-6 col-lg-3" id="mytable">');
			echo('<table class="table ">
					<thead id="tablehead">
						<tr >
							<th style="width: 25%;">ROLL NO</th>
							<th style="width: 50%;">NAME</th>
							<th style="width: 25%;">ACTION</th>
						</tr>
					</thead>
					<tbody>');
			for($i=$parts[2];$i<$parts[3];$i++)
			{
					 echo     "<tr>";
					 echo       	"<td id='td-td'>".$attdatamain[$i][0]."</td>";
					 echo       	"<td id='td-td'>".$attdatamain[$i][1]."</td>";
					 echo       	"<td >";
					 //block
					 echo     	 		"<form action='blockstudent.php?classvalue=".urlencode(base64_encode($_GET['classvalue']))."' id='myaction' method='post' style='float: left; width=50%;'>";
					 echo      				"<input name='rollno' type='text' value= ".$attdatamain[$i][2]." style= 'display:none;'/>";
					 echo					"<input name='classid' type='text' value= ".$classid." style= 'display:none;'/>";
					 echo      				"<button id='btndel' type='submit'  name = 'submit'>&#8709;</button>";
					 echo       	    "</form>";
					 //remove
					 echo      		"<form action='deletestudent.php?classvalue=".urlencode(base64_encode($_GET['classvalue']))."'  id='myaction' method='post'  style='float: right; width=50%;'>";
					 echo      			"<input name='rollno' type='text' value= ".$attdatamain[$i][2]." style= 'display:none;'/>";
					 echo				"<input name='classid' type='text' value= ".$classid." style= 'display:none;'/>";
					 echo      			"<button id='btndel' type='submit'  name = 'submit'>&#x2716</button>";
					 echo           "</form>";
					 echo       	"</td>";
					 echo    	" </tr>"; 
			}
			echo('</tbody>
				</table>
				</div>');
		}

		}

		echo('</div>');

		echo("<script> document.getElementById('top').innerHTML =' Total Present : ".$total." '</script>");
	?>
	</div>
	
	<div style="margin-top: 80px">
        <button id="addbtn" type="button" data-toggle="modal" data-target="#AddModal" data-target="modal1">
            <span>&#10133;</span>
        </button>
    </div>
	
	<div id="snackbar">Response</div>
	  
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
		<?php 
			if(isset($_GET['b'])&&isset($_GET['s']))
			{	
				if(($_GET['b']!=-1)&&($_GET['s']==1))
				{
					echo("showsnack('Roll No ".$_GET['b']." Blocked')");
				}
				if(($_GET['b']!=-1)&&($_GET['s']==0))
				{
					echo("showsnack('Already Blocked Roll No ".$_GET['b']."')");
				}
			}
			if(isset($_GET['r'])&&isset($_GET['s']))
			{
				if(($_GET['r']!=-1)&&$_GET['s']==1)
				{
					echo("showsnack('Roll No ".$_GET['r']." Removed')");
				}
				if(($_GET['r']!=-1)&&$_GET['s']==0)
				{
					echo("showsnack('Cannot Remove Roll No ".$_GET['r']."')");
				}
			}		
			if(isset($_GET['a'])&&isset($_GET['s']))
			{
				if(($_GET['a']!=-1)&&$_GET['s']==1)
				{
					echo("showsnack('Roll No ".$_GET['a']." Added')");
				}
				if(($_GET['a']!=-1)&&$_GET['s']==0)
				{
					echo("showsnack('Cannot Add Roll No ".$_GET['a']."')");
				}
				if(($_GET['a']!=-1)&&$_GET['s']==2)
				{
					echo("showsnack('Roll No ".$_GET['a']." Not Found')");
				}
				if(($_GET['a']!=-1)&&$_GET['s']==3)
				{
					echo("showsnack('Roll No ".$_GET['a']." Is Currently Blocked')");
				}
			}
			else 
			{	
			}
		?>	
	</script>
</body>
</html>
