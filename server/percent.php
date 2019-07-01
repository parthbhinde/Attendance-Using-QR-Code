<?php

	require('connect.php');
	
	require('teacherdata.php');
	
	$data = array();
	for($i=0;$i<sizeof(array_unique($classids));$i++)
	{	
		$classname = "`".$years[$i].$coursenames[$i]."-".$divisions[$i]."`";
		$classid = $classids[$i];
		
		$sdate = date('Y-m-01');
		$edate = date('Y-m-d');
		
		$atttable = $classname;
		
		/* //Number Of Lecs
		$totallecq=mysqli_fetch_assoc(mysqli_query($con,$totalquery)) or die(mysqli_error($con)); 
		$totallec = $totallecq['COUNT(attid)']; */
		
		$totalquery = "SELECT COUNT(attid) FROM ".$atttable." WHERE date BETWEEN ? AND ? ;";
		
		$totallecq = $conn->prepare($totalquery);
		$totallecq->bind_param("ss", $sdate, $edate);
		$totallecq->execute();
		$result1 = $totallecq->get_result();
		$creds1 = $result1->fetch_assoc();
		$totallecq->close();
		$totallec = $creds1['COUNT(attid)'];
		
		$totalpercent = 0;
		
		if($totallec>0){
			
			$sinfoq = $conn->prepare("SELECT * FROM studentinfo WHERE classid = ?");
			$sinfoq->bind_param("i", $classid);
			$sinfoq->execute();
			$result2 = $sinfoq->get_result();
			
			$totalstudents = $result2->num_rows;
			
			while($row = $result2->fetch_assoc())
			{
				
				//Get attendace data of each roll no dynamically
				$attquery = "SELECT r".$row['rollno']." FROM ".$atttable." WHERE date BETWEEN ? AND ? ;";
				$attq = $conn->prepare($attquery);
				$attq->bind_param("ss", $sdate, $edate);
				$attq->execute();
				$result3 = $attq->get_result();
				
				$present=0;
				
				while($rowatt = $result3->fetch_assoc())
					{
						foreach ($rowatt as $value) {
							//iterate each value
							if($value == "1"){
								$present++;
							}
						}
					}
				
				//update data
				$percent = round(($present/$totallec*100),2);
				//add final data
				$totalpercent += $percent;
			}
		 $totalpercent=$totalpercent/$totalstudents;
		}
		$classname = str_replace("`","",$classname);
		//push final data
		$inner = array('class'=>$classname,'percent'=>$totalpercent);
		array_push($data,$inner);
		
	}
?>
<style>
p{
	text-align: center;
	margin-top: 1em;
	font-size: 1.2rem;
	color: #2c3e50;
}
.per{
	transition: all .1s ease-in-out;
}
.per:hover{
	transform: scale(1.1);
}

</style>
<script src="js/jquery.circlechart.js"></script>

<div class="row" style="margin-top: 2em;">
	
	<?php
		foreach ($data as &$value) {
			if($value['percent']<25){
				$mycolor = "red";
			}
			elseif($value['percent']>=25 && $value['percent']<50){
				$mycolor = "blue";
			}
			elseif($value['percent']>=50){
				$mycolor="green";
			}
			
			echo ('<div class="col-12 col-md-4 col-lg-3 per">');
			echo ('<div class="demo-'.$mycolor.'" data-percent="'.$value['percent'].'"></div>');
			echo ('<p>'.strtoupper($value['class']).'</p>');
			echo ('</div>');
		}
	?>
	
</div>


<script>
	$('.demo-red').percentcircle({
		animate : true,
		diameter : 100,
		guage: 3,
		coverBg: '#fff',
		bgColor: '#efefef',
		fillColor: '#E74C3C',
		percentSize: '15px',
		percentWeight: 'normal'
	});
	
	$('.demo-blue').percentcircle({
		animate : true,
		diameter : 100,
		guage: 3,
		coverBg: '#fff',
		bgColor: '#efefef',
		fillColor: '#3498DB',
		percentSize: '15px',
		percentWeight: 'normal'
	});
	
	$('.demo-green').percentcircle({
		animate : true,
		diameter : 100,
		guage: 3,
		coverBg: '#fff',
		bgColor: '#efefef',
		fillColor: '#13a080',
		percentSize: '15px',
		percentWeight: 'normal'
	});

</script>

