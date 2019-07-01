<?php  
 require ('connect.php');
 if(isset($_POST["languages"]))  
 {	
 	$e = 0;
	foreach($_POST['languages'] as $val){
		$query = $conn->prepare("DELETE FROM `teacherinfo` WHERE `tid` = ? ");
		$query->bind_param("i", $val);
		$query->execute();
		//Get max ai value
		$tsubid = $conn->prepare ("SELECT MAX(tid) FROM `teacherinfo`");	
		$tsubid->execute();
		$r = $tsubid->get_result();			
		while($row = $r->fetch_assoc())
		{
			$ttid = $row['MAX(tid)'];
		}
		//Reset auto-increment to max id
		$resetai =$conn->prepare("ALTER TABLE `teacherinfo` AUTO_INCREMENT = $ttid");
		$resetai->execute();
		$e = 1;
	}
	if ($e = 1) {
		echo "Success \nRefresh Page to see changes";
	}
		
}
else{
	echo "Something Went Wrong";
}
		
	
   
 ?>  