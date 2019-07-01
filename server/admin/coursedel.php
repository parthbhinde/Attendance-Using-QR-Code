<?php
require("connect.php");
if(isset ($_POST['cid'])){
	$cid = $_POST['cid'];
	$q = $conn->prepare("SELECT * FROM `classinfo` WHERE `courseid` = ?");
	$q->bind_param("i", $cid);
	$q->execute();
	$result = $q->get_result();
	if($result->num_rows == 0){
	$query = $conn->prepare("DELETE FROM `courseinfo` WHERE `courseid` = ? ");
	$query->bind_param("i", $cid);
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

	header("Location:course.php");
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

<?php
$query1 = $conn->prepare("SELECT * FROM `classinfo` WHERE `courseid` = ?");
$query1->bind_param("i", $cid);
$query1->execute();
$query1r = $query1->get_result();
$coursename = $conn->prepare("SELECT `coursename` FROM `courseinfo` WHERE `courseid` = ?");
$coursename->bind_param("i", $cid);
$coursename->execute();
$coursenamer = $coursename->get_result();
$cname= $coursenamer->fetch_assoc();
echo ("<h1>Following Classes are enrolled to ".$cname['coursename']."</h1>");
echo ("<h4>Delete following classes to delete course");
echo('<table class="table table-hover"><thead id="tablehead"><tr ><th>Class ID</th><th>Year</th><th>Division</th></tr></thead><tbody>');
	while ($assoc = $query1r->fetch_assoc()){
	
	echo "<tr>";
	echo "<td>".$assoc['classid']."</td>";
	echo "<td>".$assoc['year']."</td>";
	echo "<td>".$assoc['division']."</td>";
	echo "</tr>";
	}
	echo '(<a href="class.php" class="center">Edit Classes</a>)';
?>



</body>
</html>
<?php
		
	}
}

?>