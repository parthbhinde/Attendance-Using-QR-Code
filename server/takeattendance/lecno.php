<?php
session_start();

require('../connect.php');

foreach($_GET as $loc=>$item)
	$_GET[$loc] = base64_decode(urldecode($item));
		
$values = explode("-",$_GET['classvalue']);

//insert values of post
$year = strtolower($values[0]);
$coursename = strtolower($values[1]);
$division = strtolower($values[2]);
$subid = $values[3];
$classid = $values[4];

$atttable = "`".$year.$coursename."-".$division."`";
$tablename = $year.$coursename."-".$division;

if ( isset( $_POST['submit'] ) ) {
	
	//Drop proxy table
	$d = "DROP TABLE `".$tablename."-multi`";
	$drop =  $conn->prepare($d);
	$drop->execute();
	$drop->close();
    
    $nolec = $_REQUEST['nolec']; 
	
    if ($nolec ==1){
        echo "<br>1";
		header("Location: ../dash.php?success=Attendance Succesfull Of ".ucwords($year)." ".ucwords($coursename)."-".ucwords($division)." (1 Lec)");
    }
    else if ($nolec ==2){
		echo "<br>2";
        DuplicateMySQLRecord(2,$atttable);
        header("Location: ../dash.php?success=Attendance Succesfull Of ".ucwords($year)." ".ucwords($coursename)."-".ucwords($division)." (2 Lecs)");
    }
    else if ($nolec ==3){
		echo "<br>3";
		DuplicateMySQLRecord(3,$atttable);
        header("Location: ../dash.php?success=Attendance Succesfull Of ".ucwords($year)." ".ucwords($coursename)."-".ucwords($division)." (3 Lecs)");
    }
    else{
		$delcurrquery = "DELETE FROM ".$atttable." ORDER BY attid DESC LIMIT 1";
		$delcurr = $conn->prepare($delcurrquery);
		$delcurr->execute();	
		$delcurr->close();
		echo "Error. Maximum Lecs can be only 3. Only Attendance for 1 lecture has been marked. Current Lecs: ".$nolec." <a href='../dash.php'>Retry From Start</a>";
    }
}
else{
    //header("Location : dash.php");
}

function DuplicateMySQLRecord ($times,$atttable_P) {
	
	//for connection
	require('../connect.php');
	
    //latest row
	$latestq = "SELECT * FROM ".$atttable_P." ORDER BY attid DESC LIMIT 1 ";
	$latest = $conn->prepare($latestq);
	$latest->execute();
	$result = $latest->get_result();
	$latestdata = $result->fetch_assoc();
	//print_r($latestdata);

	$counter = 0;

	//dynamic genrate query
	$copyq = "INSERT INTO ".$atttable_P."("; 
	foreach ($latestdata as $key => $value) {
			$counter++;
			
			//dont add attid
			if($key=="attid")
				continue;
			$copyq = $copyq.$key;
			
			//dont append ',' if last condition
			if($counter == (count($latestdata)))
			{
				$copyq = $copyq." ) VALUES (";
			}
			else{
				$copyq = $copyq.", ";
			}
	} 
	$counter = 0;
	foreach ($latestdata as $key => $value) {
			$counter++;
			
			//dont add attid
			if($key=="attid")
				continue;
			
			$copyq = $copyq."'".$value."'";
			
			//dont append ',' if last condition
			if($counter == (count($latestdata)))
			{
				$copyq = $copyq." ); ";
			}
			else{
				$copyq = $copyq.", ";
			}
	}
	$copy = $conn->prepare($copyq);
	
	//multiple times
	for($i=1;$i<$times;$i++){
		$copy->execute();
	}
	$latest->close();
	$copy->close();
} 


?>