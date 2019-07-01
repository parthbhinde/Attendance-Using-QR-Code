<?php

	header("Content-type:application/json");
	require('connectAPI.php');
	
	if(isset($_POST['fusername']) and isset($_POST['fpass']) and isset($_POST['imei']) and isset($_POST['ipaddr']) ){

		$username = $_POST['fusername'];
		$password = $_POST['fpass'];
		$imei = $_POST['imei'];
		$ipaddr =  $_POST['ipaddr'];

		$query = "SELECT studentinfo.rollno,  studentinfo.classid, studentinfo.password, studentinfo.name, studentinfo.status,
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
			$output = array(
				'message' => "Invalid Username"
			);
			print(json_encode($output)); 
			die();
		}  

		else if($result->num_rows == 1)
		{
			$r = $result->fetch_assoc();
			
			if($r['status']==1){
				$output = array(
					'message' => "User Blocked"
				);
				print(json_encode($output)); 
				die();
				
			}

			if (password_verify($password, $r['password'])) {

				$sid = $r['rollno'];
				$rollno =  $r['rollno'];
				$sname =  $r['name'];
				$year = $r['year'];
				$coursename = $r['coursename']; 
				$division = $r['division'];
				$courseid = $r['classid'];

				$atttable = $year.$coursename."-".$division;
		
				//check status first
				$cstatq = "SELECT `status`, `attid` FROM `$atttable` ORDER BY attid DESC LIMIT 1";
				$cstat = $conn->prepare($cstatq);
				$cstat->execute();
				$qstat = $cstat->get_result();
				$status = $qstat->fetch_assoc();

				if($status['status'] == 0 ){
					
					$imeiquery = "SELECT `imei` FROM `$atttable-multi` WHERE `imei` = ? AND ip= ? ";
					$imeeiq = $conn->prepare($imeiquery);
					$imeeiq->bind_param("is", $imei, $ipaddr);
					$imeeiq->execute();
					$imeistat = $imeeiq->get_result();

					if ($imeistat->num_rows >= "1") {
					

						$rolq = "UPDATE `$atttable` SET r".$sid." = '1' WHERE attid = ?; ";
						$rol = $conn->prepare($rolq);
						$rol->bind_param("i", $status['attid']);
						$rol->execute();
						
						$q = "INSERT INTO `$atttable-multi` (`sid`,`name`,`ip`,`imei`) VALUES (?,?,?,?)";
						$i = $conn->prepare($q);
						$i->bind_param("isss", $sid, $sname, $ipaddr,$imei);
                        			$i->execute();
						
						if($rol ->affected_rows === 0)
						{
							$output = array(
								'message' => "Already Marked"
							);
							print(json_encode($output)); 
							die();
						}
						
						$output = array(
							'message' => "Successfull"
						);
						print(json_encode($output)); 
						die();
						
					}
					else{
						$output = array(
							'message' => "Cannot Verify Main Account"
						);
						print(json_encode($output)); 
						die();
					}
				}
				else{
					$output = array(
						'message' => "Over"
					);
					print(json_encode($output)); 
					die();
				}
				
			}
			else{
				$output= array(
					'message' => "Wrong Password"
				);
				print(json_encode($output)); 
				die();
			}
		}
	}
	
	else{
		$output = array(
			'message' => "Something Went Wrong"
		);
		print(json_encode($output)); 
		die();
	}
?>
