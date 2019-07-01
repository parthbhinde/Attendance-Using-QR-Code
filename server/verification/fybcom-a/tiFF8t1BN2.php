<?php 
require("../../strings.php");

if(isset($_POST['username']) and isset($_POST['password']))
{
//connect to db 
$conn = new mysqli("localhost","root","","qratt");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//rest
foreach($_GET as $loc=>$item)
$_GET[$loc] = base64_decode(urldecode($item));
$parameters = explode("-",$_GET["vpb"]);
$class = $parameters[0];
$tablename = $parameters[1];
$division = $parameters[2];
$table= $tablename."-".$division;
	//Assigning GET values to variables.
	$username = $_POST['username'];
    $password = $_POST['password'];
    $uid = $_POST['uid'];
	$ipaddr = $_POST['ipad'];
	


	//check status first
	$cstatq = "SELECT `status`, `attid` FROM `$table` ORDER BY attid DESC LIMIT 1";
	$cstat = $conn->prepare($cstatq);
	$cstat->execute();

	//get result of query
	$qstat = $cstat->get_result();
	$status = $qstat->fetch_assoc();

	if($status['status'] == 0 ){

		//SQL query to fetch details
		$stmt = $conn->prepare("SELECT * FROM `studentinfo` WHERE username = ?");
		$stmt->bind_param("s", $username);
		$stmt->execute();

		//get result of query
		$result = $stmt->get_result();

		if($result->num_rows == 1)
		{
			$creds=$result->fetch_assoc();

			if (password_verify($password, $creds['password'])) {

				$sid = $creds['rollno'];
				$sname = $creds['name'];
				if($creds['status']== 0 && $creds['classid']== $class){
					$rolq = "UPDATE `$table` SET r".$sid." = '1' WHERE attid = ?; ";
					$rol = $conn->prepare($rolq);
					$rol->bind_param("i", $status['attid']);
					$rol->execute();

					if ($rol->affected_rows === 1){						
						$q = "INSERT INTO `".$table."-multi` (`sid`,`name`,`ip`,`imei`) VALUES (?,?,?,?)";
						$i = $conn->prepare($q);
						$i->bind_param("isss", $sid, $sname, $ipaddr,$uid);
                        $i->execute();
                        $arr = array('status'=>"success");
                        echo json_encode($arr);
						die();
                    }
                    else{
                        $arr = array('status'=>"error");
                        echo json_encode($arr);
						die();
                    }
						
					}
					else {
						$arr = array('status'=>"blocked");
                        echo json_encode($arr);
						die();
					}
				}
				else{
					$arr = array('status'=>"creds");
                        echo json_encode($arr);
						die();
				}
			}
			else {
				$arr = array('status'=>"creds");
                        echo json_encode($arr);
						die();
			}


		}
		else
		{
			$arr = array('status'=>"over");
                        echo json_encode($arr);
						die();
		}
	}
	else{

session_start();
foreach($_GET as $loc=>$item)
$_GET[$loc] = base64_decode(urldecode($item));
$parameters = explode("-",$_GET["vpb"]);
$classid = $parameters[0];
$tablename = $parameters[1];
$division = $parameters[2];
$_SESSION["Forwarded"] = "yes";
$_SESSION["classid"]= $classid;
$_SESSION["tablename"]= $tablename."-".$division;
$targetLoc = $serverUrl."/browser/browser.php";
header("Location: $targetLoc");
die();

	}


?>