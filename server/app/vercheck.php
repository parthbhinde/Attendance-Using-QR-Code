<?php 
if($_POST['username']=="Jagga"){
$arr = array('version'=>"1.1",'url'=>"http://localhost/student/qratt.apk");
            echo json_encode($arr);
            die();
}
?>