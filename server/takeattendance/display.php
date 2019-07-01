<?php

	//get current url
	$url=$_SERVER['REQUEST_URI'];
	header("Refresh: 10; URL=$url");
	
	require('../connect.php');
	
	//random string function
	function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
		
	foreach($_GET as $loc=>$item)
		$_GET[$loc] = base64_decode(urldecode($item));
		
	$values = explode("-",$_GET['classvalue']);
	
	//insert values of post
	$year = strtolower($values[0]);
	$coursename = strtolower($values[1]);
	$division = strtolower($values[2]);
	$subid = $values[3];
	$classid = $values[4];
	$subname = $values[5];
	
	$tablename = $year.$coursename."-".$division;
	
	require("../loader.php");
?>
<!DOCTYPE html>
<html>
	<head>
		
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
				
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<!-- Popper JS -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
		<!-- Latest compiled JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script> 
	
		<link rel="stylesheet" href="../css/global.css">
		<script> window.parent.document.title = 'Scan Code'; </script>
		<title>Take Attendance</title>
	
		<style>		
			#stpbtn{
				float: right;
				border: none;
				box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
				outline: none;
				color: #ECF0F1;
				background-color: #E74C3C;
				border-radius: 50px;
				padding: 0.5% 2%;
				margin: -0.5%;
				cursor: pointer;
			}	
			#qrimg{
				max-width: 100%;
				max-height: 100vh;
				height: auto;
				width: 100vh; 
			}		
			
			.col-sm-12{
				border: 0.3em solid #2C3E50;
			}
			.datahead{
				text-align: center;
				color: white;
				background-color: #2C3E50;
				margin: 0px -15px;
				padding: 15px 15px;
			}
			.datahead>*{
				font-weight: bold;
				font-size: 1.2em;
			}
			.count{
				position: sticky;
				left: 10;
				bottom: 10;
				font-size: 3rem;
				color: black;
				text-shadow: 2px 2px 4px #ffffff;
			}
		</style>
	</head>
	
	<body style="background: #FFFFFF;">
		<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<?php
					//assign random name
					$newname = generateRandomString();
					$newname = $newname.".php";
					
					$oldname = "";
					
					//get name of old php file in directory and assign it to a variable
					$directory = '../verification/'.$year.$coursename.'-'.$division.'/';
					foreach (glob($directory."*.php") as $filename) {
						$file = realpath($filename);
						$oldname = $file;
					}
					
					//rename verification file
					rename($oldname,$directory.$newname);

					//QR generate and save it in assets
					require("../generateQR.php");
					
					echo("<center><img id='qrimg' src='../assets/qrcode/".$year.$coursename."-".$division."/qrcode.png' alt='QR_Code'></center>");
				?>
				<div class="datahead">
					<?php 
						//current year,course,sub
						echo ("<span>".ucwords($subname).": ".ucwords($year)." ".ucwords($coursename)."-".ucwords($division)."</span>");
						//stop button
						$delurl = "deletever.php?classvalue=".urlencode(base64_encode($_GET['classvalue']));
						echo ("<a href='".$delurl."'><button id='stpbtn'>Stop</button></a>"); 
					?>		
				</div>
				
			</div>
		</div>
		<h1 class="count">0</h1>
		</div>
<script>document.getElementById('qrimg').src = document.getElementById('qrimg').src + '?' + (new Date()).getTime();</script>
		<script>
			//counter
			$('.count').each(function () {
				$(this).prop('Counter',10).animate({
					Counter: $(this).text()
				}, {
					duration: 10000,
					easing: 'linear',
					step: function (now) {
						$(this).text(Math.ceil(now));
					}
				});
			});
		</script>
	</body>
</html>