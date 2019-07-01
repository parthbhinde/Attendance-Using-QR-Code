<?php
header("Content-type:application/json");
require('connectAPI.php');
if(isset($_POST['username']) and isset($_POST['password']))
{
	$output = array();
			
	//Assigning GET values to variables.
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	//SQL query to fetch details
	$stmt = $conn->prepare("SELECT * FROM `studentinfo` WHERE username= ?");
	$stmt->bind_param("s", $username );
	$stmt->execute();
	
	//get result of query
	$result = $stmt->get_result();
	
	//vaid username
	if($result->num_rows == 1)
	{
		$creds=$result->fetch_assoc();
		
		if (password_verify($password, $creds['password'])) {

			//get student data
			$query = "SELECT studentinfo.rollno, studentinfo.name, studentinfo.classid, 
					 classinfo.classid, classinfo.courseid, classinfo.year, classinfo.division, 
					 courseinfo.courseid, courseinfo.coursename 
					 FROM studentinfo INNER JOIN classinfo ON studentinfo.classid = classinfo.classid 
					 INNER JOIN courseinfo ON courseinfo.courseid = classinfo.courseid 
					 WHERE studentinfo.username = ? ";
					 
			$studentq =  $conn->prepare($query);
			$studentq->bind_param("s", $username);
			$studentq->execute();
			$result2 = $studentq->get_result();
			
			// die if no data returned
			if ($result2->num_rows < "1") {
				$output['myheader'] = array(
					'response' => false,
				);
				print(json_encode($output)); 
				die();
			}
			
			while($r = $result2->fetch_assoc() ) {
				$name = $r['name'];
				$rollno =  $r['rollno'];
				$year = $r['year'];
				$coursename = $r['coursename']; 
				$division = $r['division'];
				$courseid = $r['classid'];
			}
			
			$atttable =  "`".$year.$coursename."-".$division."`";
			
			//get attendance data 
			 $attdataquery =  "SELECT DATE(time), TIME(time), subid, r".$rollno."  FROM ".$atttable." ORDER BY attid DESC LIMIT 6 ;";
			 $attdata = $conn->prepare($attdataquery);
			 $attdata->execute();
			 $result3 = $attdata->get_result();
			 
			  while($row = $result3->fetch_assoc() )
			 {
				 //get subject name
				 $lecq = $conn->prepare("SELECT subname FROM subjectinfo WHERE subjectid = ? ;");
				 $lecq->bind_param("i", $row['subid']);
				 $lecq->execute();
				 $result4 = $lecq->get_result();
				 $rowlec = $result4->fetch_assoc();
				 
				 //change value of status
				 if($row['r'.$rollno]==1){
					 $status = "Present";
				 }else{
					 $status = "Absent";
				 }
			
				$output[] = array(
					'date' => $row['DATE(time)'],
					'time' => $row['TIME(time)'],
					'subname' => $rowlec['subname'],
					'status' => $status,
				);
	
				
			 }
			  $output['myheader'] = array(
                                'login'=>"Successfull",
				'version'=>"1.1",
				'url'=>"http://localhost/student/qratt.apk",
				'response' => true,
				'name' => $name,
				'rollno' => $rollno,
				'classname' => $year.$coursename."-".$division,
			);
			 print(json_encode($output)); 
			 die();
		}
		//invalid password 
		else{
			$output['myheader'] = array(
				'response' => false,
                                'login'=>"Failed",
			);
			print(json_encode($output));
			die();
		}
		
	}
	//invalid username
	else{	
		$output['myheader'] = array(
			'response' => false,
                        'login'=>"Failed",
		);
		print(json_encode($output));
		die();
	}
	
}


?>