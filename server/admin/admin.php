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
	
	<style type="text/css">
  .navbar-brand.abs
    {
        position: absolute;
        left: 50%;
        text-align: center;
    }
}
</style>
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <a href="admin.php" class="navbar-brand abs"><img src="../assets/logogreen.png" height="30" width="30"> QrAtt</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar7">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse collapse justify-content-stretch" id="navbar7">
        <ul class="navbar-nav ml-auto">
            
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<p class="text-center font-weight-bold" style="margin-top:10;">Admin Panel</p>
<div class="container">
<div class="row" style="margin-top:90;"]>
    <ul><h3>CRUD Operations</h3>
	<div class="col">
      <li><a href="course.php" class="font-weight-bold">Course</a></li>
      <li><a href="class.php" class="font-weight-bold">Class</a></li>
      <li><a href="subject.php" class="font-weight-bold">Subject</a></li>
      <li><a href="teacher.php" class="font-weight-bold">Teacher</a></li>
      <li><a href="student.php" class="font-weight-bold">Student</a></li>
    </div>
	</ul>

<div class="col">
   <form action="" method="post">
    <ul><h3>Class Change :</h3>
        <div class="form-group">
        <div class="col">
            <label for="from" class="col-form-label">From :</label>
            <input name="from" type="number" id="txt" placeholder="class id" class="form-control form-control-sm" required >
        </div>
        <div class="col">
            <label for="to" class="col-form-label">To :</label>
            <input name="to" type="number" id="txt" placeholder="class id" class="form-control form-control-sm" required>
        </div>&nbsp
        <div class="col">
            <input class="btn btn-primary"type="submit" name="submit" value="Change" >
        </form>
        </div>  
    </div>
 
  <?php
//connect to db
$conn = new mysqli("localhost","root","","qratt");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if(!(isset($_SESSION['adminname']))){
	header('Location: login.php');
	die();
}
if (isset($_POST['from']) && isset($_POST['to'])){
  $to = $_POST['to'];
  $from = $_POST['from'];
  // update student info
  $query = $conn->prepare("SELECT * FROM `studentinfo` WHERE `classid` = ?");
  $query->bind_param("i", $to);
  $query->execute();
  $r = $query->get_result();
  if($r->num_rows == 0){
     //old table name
    $otablename = $conn->prepare("SELECT * FROM `courseinfo` LEFT JOIN `classinfo` on courseinfo.courseid = classinfo.courseid WHERE classinfo.classid = ?");
    $otablename->bind_param("i", $from);
    $otablename->execute();
    $r1 = $otablename->get_result();
    $gotablename = $r1->fetch_assoc();
    $oldtablename = $gotablename['year'].$gotablename['coursename']."-".$gotablename['division'];
    //new table name
    $ntablename = $conn->prepare("SELECT * FROM `courseinfo` LEFT JOIN `classinfo` on courseinfo.courseid = classinfo.courseid WHERE classinfo.classid = ?");
    $ntablename->bind_param("i", $to);
    $ntablename->execute();
    $r2 = $ntablename->get_result();
    $gntablename = $r2->fetch_assoc();
    $newtablename = $gntablename['year'].$gntablename['coursename']."-".$gntablename['division'];
    //drop new table if exists
    $deltable = $conn->prepare("DROP TABLE IF EXISTS `$newtablename`");
    $deltable->execute();
    //create table
    $ctable = $conn->prepare("CREATE TABLE `$newtablename` LIKE `$oldtablename`");
    $ctable->execute();
    //duplicate table
    $duplicate = $conn->prepare("INSERT `$newtablename` SELECT * FROM `$oldtablename`");
    $duplicate->execute();
    //update studentinfo
    $q = $conn->prepare("UPDATE `studentinfo` SET `classid` = '$to' WHERE `classid` = ?");
    $q->bind_param("i", $from);
    $q->execute();
    ?>

    <div class="alert alert-primary col" role="alert">
      Success
    </div>
    <?php
  }
  else{
    ?>
     <div class="alert alert-danger col" role="alert">
      The Class Contains Students(Please update hierarchically).
    </div>
  <?php
  }
}

?>
</ul>
</div>
<div class="col">
   <form action="" method="post">
    <ul><h3>Unassign Class:</h3>
        <div class="form-group">
        <div class="col">
            <label for="del" class="col-form-label">Class ID :</label>
            <input name="del" type="number" id="txt" placeholder="class id" class="form-control form-control-sm" required>
        </div>&nbsp
        <div class="col">
            <input class="btn btn-primary"type="submit" name="submit" value="Unassign">
        </form>
        </div>  
    </div>
 
  
 
<?php
if (isset($_POST['del'])){
  $del = $_POST['del'];
  $query = $conn->prepare("SELECT * FROM `studentinfo` WHERE `classid` = ?");
  $query->bind_param("i", $del);
  $query->execute();
  $r3 = $query->get_result();
  if($r3->num_rows >= 1){
    $tablename = $conn->prepare("SELECT * FROM `courseinfo` LEFT JOIN `classinfo` on courseinfo.courseid = classinfo.courseid WHERE classinfo.classid = ?");
    $tablename->bind_param("i", $del);
    $tablename->execute();
    $r4 = $tablename->get_result();
    $gtablename = $r4->fetch_assoc();
    $tablenam = $gtablename['year'].$gtablename['coursename']."-".$gtablename['division'];
    $deltable = $conn->prepare("DROP TABLE `$tablenam`");
    $deltable->execute();
    $q = $conn->prepare("UPDATE `studentinfo` SET `classid` = null WHERE `classid` = '$del'");
    $q->execute();
    ?>
     <div class="alert alert-primary col" role="alert">
      Success.
    </div>
  <?php
  }
  else{
    ?>
     <div class="alert alert-danger col" role="alert">
      The Class ID entered has no students assigned
    </div>
  <?php
  }
}

?>
</ul>
 

</div>
</div>


</body>
</html>
