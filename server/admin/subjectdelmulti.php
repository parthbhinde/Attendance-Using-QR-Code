<?php  
 require ('connect.php');
 if(isset($_POST["languages"]))  
 {	
 	$e = 0;
	foreach($_POST['languages'] as $val){
		$query = $conn->prepare("DELETE FROM `subjectinfo` WHERE `subjectid` = ? ");
	$query->bind_param("i", $val);
	$query->execute();
	//Get max ai value
	$gsubid = $conn->prepare ("SELECT MAX(subjectid) FROM `subjectinfo`");	
	$gsubid->execute();
	$r = $gsubid->get_result();			
	while($row = $r->fetch_assoc())
	{
		$subbid = $row['MAX(subjectid)'];
	}
	//Reset auto-increment to max id
	$resetai = $conn->prepare("ALTER TABLE `subjectinfo` AUTO_INCREMENT = $subbid");
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