<?php  
 require ('connect.php');
 if(isset($_POST["languages"]))  
 {	
 	$e = 0;
	foreach($_POST['languages'] as $val){
		$sinfo = $conn->prepare("SELECT * FROM `studentinfo` WHERE `id` = ?");
		$sinfo->bind_param("i", $val);
		$sinfo->execute();
		$r = $sinfo->get_result();
		$gsinfo = $r->fetch_assoc();
		$classid = $gsinfo['classid'];
		$rno = $gsinfo['rollno'];
		$classinf = $conn->prepare("SELECT * FROM classinfo , courseinfo WHERE classinfo.courseid = courseinfo.courseid AND classinfo.classid = '$classid'");
		$classinf->execute();
		$r = $classinf->get_result();
		$res = $r->fetch_assoc();
		$tablename = $res['year'].$res['coursename']."-".$res['division'];
		$delcol = $conn->prepare("ALTER TABLE `$tablename` 
		DROP `r".$rno."` , 
		DROP `c".$rno."`");
		$delcol->execute();
		$query = $conn->prepare("DELETE FROM `studentinfo` WHERE `id` = ? ");
		$query->bind_param("i", $val);
		$query->execute();

		$e = 1;
	}
	if ($e = 1) {
		echo "Success \nRefresh Page to see changes";
	}
	else{
		echo "Something Went Wrong";
	}
		
}
else{
	echo "Something Went Wrong";
}
		
	
   
 ?>  