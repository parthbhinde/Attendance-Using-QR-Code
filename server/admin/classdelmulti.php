<?php  
 require ('connect.php');
 $e = 0;
 if(isset($_POST["languages"]))  
 {	
	foreach($_POST['languages'] as $classid){
	//check if class is enrolled
	$q = $conn->prepare("SELECT * FROM `studentinfo` WHERE `classid` = ?");
	$q->bind_param("i", $classid);
	$q->execute();
	$r = $q->get_result();
	if($r->num_rows ==  0){
	//delete class table
	$qgetclassinfo =$conn->prepare("SELECT * FROM `classinfo` WHERE `classid` = ?");
	$qgetclassinfo->bind_param("i", $classid);
	$qgetclassinfo->execute();
	$rgetclassinfo = $qgetclassinfo->get_result();
	$fclassinfo = $rgetclassinfo->fetch_assoc();
	$courseid = $fclassinfo['courseid'];
	$coursename = $conn->prepare("SELECT `coursename` FROM `courseinfo` WHERE `courseid` = ?");
	$coursename->bind_param("i", $courseid);
	$coursename->execute();
	$getcoursename = $coursename->get_result();
	$cname = $getcoursename->fetch_assoc();
	$tablename = $fclassinfo['year'].$cname['coursename']."-".$fclassinfo['division'];
	$deletetable = $conn->prepare("DROP TABLE IF EXISTS `$tablename`");
	$deletetable->execute();
	//delete entry from classinfo
	$query = $conn->prepare("DELETE FROM `classinfo` WHERE `classid` = ? ");
	$query->bind_param("i", $classid);
	$query->execute();
	//Get max ai value
	$clasid = $conn->prepare ("SELECT MAX(classid) FROM `classinfo`");
	$clasid->execute();
	$r = $clasid->get_result();		
	while($row =  $r->fetch_assoc())
	{
		$cid = $row['MAX(classid)'];
	}
	//Reset auto-increment to max id
	$resetai = $conn->prepare("ALTER TABLE `classinfo` AUTO_INCREMENT = $cid ");
	$resetai->execute();
	//delete directory
	@delete_files('../verification/'.$tablename);
	@delete_files('../assets/qrcode/'.$tablename);
	echo $classid."-Success\n";
	$e=1;
		}
		else{	
			echo $classid."-Fail (Students are enrolled)\n";
			$e=1;
		}
		
	}
	if($e == 1){
		
		echo "Refresh the page to see changes.";
	}
	else{
		echo "Something went wrong";
	}
 }


 function delete_files($target) {
    if(is_dir($target)){
        $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned

        foreach( $files as $file )
        {
            delete_files( $file );      
        }

        rmdir( $target );
    } elseif(is_file($target)) {
        unlink( $target );  
    }
}  
 ?>  