<?php  
 require ('connect.php');
 if(isset($_POST["languages"]))  
 {	
	foreach($_POST['languages'] as $val){
		//check if class is enrolled
		$q = $conn->prepare("SELECT * FROM `classinfo` WHERE `courseid` = ?");
		$q->bind_param("i", $val);
		$q->execute();
		$rowcount = $q->get_result();
		if($rowcount->num_rows  == 0){
			$query = $conn->prepare("DELETE FROM `courseinfo` WHERE `courseid` = ? ");
			$query->bind_param("i", $val);
			$query->execute();
			//Get max ai value
			$gcourseid = $conn->prepare ("SELECT MAX(courseid) FROM `courseinfo`");	
			$gcourseid->execute();
			$r = $gcourseid->get_result();		
			while($row =  $r->fetch_assoc())
			{
				$coid = $row['MAX(courseid)'];
			}
			//Reset auto-increment to max id
			$resetai = $conn->prepare("ALTER TABLE `courseinfo` AUTO_INCREMENT = $coid ");
			$resetai->execute();
			echo "Refresh to see changes";
		}
		else{
			$coursename = $conn->prepare("SELECT `coursename` FROM `courseinfo` WHERE `courseid` = ?");
			$coursename->bind_param("i", $val);
			$coursename->execute();
			$r = $coursename->get_result();
			$cname =$r->fetch_assoc();
			echo "  Classes are enrolled to ".$cname['coursename'];
			echo " Please unenroll classes or delete them";
		}
		
	}
 }  
 ?>  