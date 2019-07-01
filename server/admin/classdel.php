<?php
require('connect.php');
if(isset($_POST['classid'])){
	$classid = $_POST['classid'];
	//Check if class has any subjects enrolled
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
	delete_files('../verification/'.$tablename);
	delete_files('../assets/qrcode/'.$tablename);
	header("Location:class.php");
	}
	else{
	

?>
<html>
<head>
<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
			
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<!-- Popper JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script> 
	
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <a href="/" class="navbar-brand"><img src="../assets/logogreen.png" height="30" width="30"> QrAtt</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar7">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse collapse justify-content-stretch" id="navbar7">
        <ul class="navbar-nav ml-auto">
		<li class="nav-item">
                <a class="nav-link" href="admin.php">Home</a>
            </li>
			<li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          CRUD
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
		  <a class="dropdown-item" href="course.php">Course</a>
          <a class="dropdown-item" href="class.php">Class</a>
          <a class="dropdown-item" href="subject.php">Subject</a>
          <a class="dropdown-item" href="teacher.php">Teacher</a>
		  <a class="dropdown-item" href="student.php">Student</a>
        </div>
      </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Help</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Logout</a>
            </li>
			
        </ul>
    </div>
</nav>
<div class="container">
<?php
//Get associated subjects
$query1 = $conn->prepare ("SELECT * FROM `studentinfo` WHERE `classid` = ?");
$query1->bind_param("i", $classid);
$query1->execute();
$query1r = $query1->get_result();
echo ("<h3>Following Students are enrolled to Class you are trying to delete</h3>");
echo "Un-Enroll them to delete the class";
echo '(<a href="student.php" class="center">Edit Student Info</a>)';
echo('<table class="table table-hover"><thead id="tablehead"><tr ><th>ID</th><th>Name</th></tr></thead><tbody>');
	while ($assoc = $query1r->fetch_assoc()){
	
	echo "<tr>";
	echo "<td>".$assoc['rollno']."</td>";
	echo "<td>".$assoc['name']."</td>";
	echo "</tr>";
	
	}
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
</div>
</tbody>
</table>


</body>
</html>