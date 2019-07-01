<?php
require('connect.php');

// store all teacher data in respective arrays
	$maxsub=5;
	//empty array
	$subids = array();
	$subnames = array();
	$classids = array();
	$courseids = array();
	$coursenames = array();
	$divisions = array();
	$years = array();
	
	//generate no of subs dynamically
	$subidsquery = "SELECT ";
	for($i=1;$i<=$maxsub;$i++){
		if(!($i==$maxsub)){
			$subidsquery = $subidsquery .("sub".(string)$i." , ");
		}
		else{
			$subidsquery = $subidsquery .("sub".(string)$i." ");
		}
	}
	
	$subid = $conn->prepare($subidsquery. "FROM `teacherinfo` WHERE username = ?");
	$subid->bind_param("s", $_SESSION['teacher-username']);
	$subid->execute();
	$result1 = $subid->get_result();
	
	//create object of result
	$creds1 = $result1->fetch_assoc();
	$subid->close();
	
	for($j=1;$j<=$maxsub;$j++)
	{
		$pos= 'sub'.$j;
		//push id in subids if its not null
		if($creds1[$pos]!=NULL)
		{
			array_push($subids,$creds1[$pos]);
		}
	}
	
	foreach ($subids as &$id) {
		
		//query to get subname,  classid 
		$subname = $conn->prepare("SELECT subname, classid FROM `subjectinfo` WHERE subjectid = ?");
		$subname->bind_param("i",$id);
		$subname->execute();
		$result2 = $subname->get_result();
		
		//create object of result
		$creds2 = $result2->fetch_assoc();
		$subname->close();
		
		//query to get all data from classinfo
		$classdata = $conn->prepare("SELECT courseid, year, division FROM `classinfo` WHERE classid = ?");
		$classdata->bind_param("i",$creds2['classid']);
		$classdata->execute();
		$result3 = $classdata->get_result();
		
		//create object of result
		$creds3 = $result3->fetch_assoc();
		$classdata->close();
		
		//query to get coursename
		$coursename = $conn->prepare("SELECT coursename FROM `courseinfo` WHERE courseid = ?");
		$coursename->bind_param("i",$creds3['courseid']);
		$coursename->execute();
		$result4 = $coursename->get_result();
		
		//create object of result
		$creds4 = $result4->fetch_assoc();
		$coursename->close();
		
		//push all to array
		array_push($subnames,$creds2['subname']);
		array_push($classids,$creds2['classid']);
		
		
		array_push($courseids,$creds3['courseid']);
		array_push($years,$creds3['year']);
		array_push($divisions,$creds3['division']);
		
		array_push($coursenames,$creds4['coursename']);	
	}
	
?>

