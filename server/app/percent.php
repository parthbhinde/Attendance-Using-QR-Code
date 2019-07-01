<?php

	header("Content-type:application/json");
	require('connectAPI.php');
	
	if(isset($_POST['username'])){

		$username = $_POST['username'];
		$sdate = date('Y-m-01');
		$edate = date('Y-m-d');
		$counter = 0;

		$query = "SELECT studentinfo.rollno, studentinfo.name, studentinfo.classid, 
					 classinfo.classid, classinfo.courseid, classinfo.year, classinfo.division, 
					 courseinfo.courseid, courseinfo.coursename 
					 FROM studentinfo INNER JOIN classinfo ON studentinfo.classid = classinfo.classid 
					 INNER JOIN courseinfo ON courseinfo.courseid = classinfo.courseid 
					 WHERE studentinfo.username = ? ";
					 
		$studentq =  $conn->prepare($query);
		$studentq->bind_param("s", $username);
		$studentq->execute();
		$result = $studentq->get_result();

		// die if no data returned
		if ($result->num_rows < "1") {
			$output[] = array(
				'response' => false,
			);
			print(json_encode($output)); 
			die();
		}
		
		 while($r = $result->fetch_assoc() ) {
			 $name = $r['name'];
			 $rollno =  $r['rollno'];
			 $year = $r['year'];
			 $coursename = $r['coursename']; 
			 $division = $r['division'];
			 $courseid = $r['classid'];
		 }
		 
		 $atttable =  "`".$year.$coursename."-".$division."`";
		
		 $output = array();
		 $total=0;$p=0;
		 
		 //get attendance data 
		 $attdataquery =  "SELECT DATE(time), TIME(time), subid, r".$rollno."  FROM ".$atttable." WHERE date BETWEEN ? AND ? ORDER BY attid DESC;";
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
			 
			 //push final data limit 5
			 if($counter<5)
			 {
				$output[] = array(
					'date' => $row['DATE(time)'],
					'time' => $row['TIME(time)'],
					'subname' => $rowlec['subname'],
					'status' => $status,
				);

			}
			$counter++;
		 }

		 $per = ($p*100)/$total;
		 $per = round($per);
		 
		 $output['myheader'] = array(
			'response' => true,
			'percent' => $per,
			'name' => $name,
			'rollno' => $rollno,
			'classname' => $year.$coursename."-".$division,
		);
		 print(json_encode($output)); 
		 die();
	}
	
	else{
		$output[] = array(
			'response' => false,
		);
		print(json_encode($output)); 
		die();
	}
?>
