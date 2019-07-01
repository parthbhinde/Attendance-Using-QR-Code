<?php
require('../connect.php');
require('../loader.php');

//get data
foreach($_GET as $loc=>$item)
	$_GET[$loc] = base64_decode(urldecode($item));
		
$values = explode("-",$_GET['classvalue']);

//insert values of post
$year = strtolower($values[0]);
$coursename = strtolower($values[1]);
$division = strtolower($values[2]);
$subid = $values[3];
$classid = $values[4];
$tablename = $year.$coursename."-".$division."-multi";
?>
<html>
<head>
<link rel="stylesheet" href="../css/global.css">
<style>

	form>input[type=number]{
		width: 90%;
		margin: 2% 5%;
		padding: 1% 2%;
		border: none;
		border-bottom: 2px solid #13a080;
	}
	input[type=submit]{
		background-color: #13a080 !important; 
		color:#FFF;
		border-radius:50px;
		text-align:center;
		border: none;
		padding: 10px 35px;
		transition:all 0.3s ease;
		cursor: pointer;
		float: right;
	}
	table{
		clear: both;
		margin: 0 auto;
		width: 60%;
		font-size: 1.1em;
	}
	tbody{
		text-align: center;
	}
	table, th, td {
		border: 1px solid #2C3E50;
		border-collapse: collapse;
	}
	th{
		background-color:#2C3E50;
		color:#fff;
	}
</style>
</head>
<body>
<div style="width:100%;">
	<form id="login-form" action="multiatt.php?classvalue=<?php echo (urlencode(base64_encode($_GET['classvalue'])));?> " method="post" role="form" style="display: block;">
		<input type="number" name="sid" id="sid" placeholder=" Enter Roll No:" autocomplete="off" value="" required>
		<input type="submit" id="click" value="SUBMIT">
	</form>
</div>
<div style="padding-top: 40px;">
	<?php
		if(isset($_POST['sid'])){
			$sid = $_POST['sid'];
			$q = "SELECT * FROM `".$tablename."` WHERE `sid`= ? ";
			$sidq = $conn->prepare($q);
			$sidq->bind_param("i",$sid);
			$sidq->execute();
			$sid = $sidq->get_result();
			
			$creds = $sid->fetch_assoc();
			
			$query = "SELECT * FROM `".$tablename."` WHERE `ip`= ? OR `user-agent`= ? OR `uid`= ? OR `cid`= ? OR `imei`=?";
			$data = $conn->prepare($query);
			$data->bind_param("sssss",$creds['ip'],$creds['user-agent'],$creds['uid'],$creds['cid'],$creds['imei']);
			$data->execute();
			$r = $data->get_result();
			
			//echo('Attendance from same device:');
			echo('<table>
					<thead id="tablehead">
						<tr>
							<th>Roll-no</th>
							<th>Name</th>
							<th>Possibility</th>
						</tr>
					</thead>
					<tbody>');
			while ($assoc = $r->fetch_assoc()){
				$e=0;
				if($creds['ip']==$assoc['ip']){
					$e+=30;
				}
				if($creds['user-agent']==$assoc['user-agent']){
				if($creds['user-agent']=== NULL) {
					}
					else{
					$e+=10;
					}
				}
				if($creds['uid']==$assoc['uid']){
				if($creds['uid']=== NULL) {
					}
					else{
					$e+=30;
					}
				}
				if($creds['cid']==$assoc['cid']){
					if($creds['cid']=== NULL) {
					}
					else{
					$e+=30;
					}
				}

				if($creds['imei']==$assoc['imei']){
				if($creds['imei']=== NULL) {}
					else{
					$e+=70;
					}
				}
				if($creds['sid']==$assoc['sid']){
					echo "<tr>";
					echo "<td id='td-td'>".$assoc['sid']."</td>";
					echo "<td id='td-td'>".$assoc['name']."</td>";
					echo "<td id='td-td'>*</td>";
					echo "</tr>";	
				}
				else{
					echo "<tr>";
					echo "<td id='td-td'>".$assoc['sid']."</td>";
					echo "<td id='td-td'>".$assoc['name']."</td>";
					echo "<td id='td-td'>".$e."%</td>";
					echo "</tr>";
				}
			}
			echo('</tbody></table>');
		}
	?>
</div>
<script>
	$('#click').click(function(){
        $('#ProxyModal').modal('handleUpdate'); 
  });
	</script>
</body>
</html>