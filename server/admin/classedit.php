<?php
require('connect.php');
if(isset($_POST['classid'])){
	//post data
	$classid = $_POST['classid'];
	$courseid = $_POST['courseid'];
	$y= $_POST['year'];
	$year = strtolower($y);
	$d = $_POST['division'];
	$division = strtolower($d);
	//old info
	$old = $conn->prepare("SELECT * FROM `classinfo` WHERE `classid`= ?");
	$old->bind_param("i", $classid);
	$old->execute();
	$r = $old->get_result();
	$gold = $r->fetch_assoc();
	$oldcourseid = $gold['courseid'];
	$oldyear = $gold['year'];
	$olddivision = $gold['division'];
	//old coursename & tablename
	$q = $conn->prepare("SELECT `coursename` FROM `courseinfo` WHERE `courseid` = ?");
	$q->bind_param("i", $oldcourseid);
	$q->execute();
	$r1 = $q->get_result();
	$gq =  $r1->fetch_assoc();
	$oldcoursename = $gq['coursename'];
	$oldtablename = $oldyear.$oldcoursename."-".$olddivision;
	//new coursename & tablename
	$newcname = $conn->prepare("SELECT `coursename` FROM `courseinfo` WHERE `courseid` = ?");
	$newcname->bind_param("i", $courseid);
	$newcname->execute();
	$r2 = $newcname->get_result();
	if($r2->num_rows == 1){
	$gcname = $r2->fetch_assoc();
	$newcoursename = $gcname['coursename'];
	$newtablename = $year.$newcoursename."-".$division;
	$editable = $conn->prepare("RENAME TABLE `$oldtablename` TO `$newtablename`");
	$editable->execute();
	//directory edit
	delete_files('../verification/'.$oldtablename);
	delete_files('../assets/qrcode/'.$oldtablename);
	mkdir('../verification/'.$newtablename, 0777, true);
	mkdir('../assets/qrcode/'.$newtablename, 0777, true);
	
	 //copy abc to verification folder
	$newdir2 = '../verification/'.$newtablename.'/asaWsdQssd.php';
	copy('abc.php', $newdir2);
  
   

	//new info
	$query = $conn->prepare("UPDATE `classinfo` SET `courseid`= ? , `year`= ? , `division` = ? WHERE classid=?");
	$query->bind_param("issi", $courseid,$year,$division,$classid);
	$query->execute();
	header("location:class.php");
	}
	else{
		echo 'Invalid Course ID';
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
<html>
<body>
<a href="class.php">Go Back</a>
</body>
</html>