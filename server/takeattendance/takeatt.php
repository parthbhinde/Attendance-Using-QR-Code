<?php
	require('connect.php'); 	
?>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style>

	#datadiv > *{
		display: inline;
	}
	.sbutton{
		padding: 2.5% 20%;
		margin: 10% 0%;
		background-color: #13A080;
		color: #fff;
		border-radius: 50px;
		border: none;
		cursor: pointer;
		font-weight: bold;
		transition:all 0.3s ease;
		opacity:0.9;
	}
	.sbutton:hover{
		box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
		opacity:1;
		border-radius:50px;
	}
	input,select{
		border: none;
		border-bottom: 2px solid #2c3e50;
	}
	input:focus,select:focus{
		border-bottom: 2px solid #13a080;
	}
	</style>
</head>


<div id="takeattpage">
	<?php 
		require('teacherdata.php');
		
	?>
	
	<form action="takeattendance/blankinsert.php" method="post"  style="margin: 5% 0%;">
		
		<div class="row">
			<div class="col-sm-12 col-md-10 offset-md-1" id="datadiv">
				<select name="classvalue" id="classvalue" style="width: 100%; font-size: 2em; color:#2c3e50;" required>
					<option value="" disabled selected>Select Class</option>
					<?php
						for($i=0;$i<sizeof($subids);$i++)
						{
							$classvalue= $years[$i]."-".$coursenames[$i]."-".$divisions[$i]."-".$subids[$i]."-".$classids[$i]."-".$subnames[$i];
							
							//check for status of prev att of same class
							$query = "SELECT status FROM `".$years[$i].$coursenames[$i]."-".$divisions[$i]."` ORDER BY attid DESC LIMIT 1 ";
							$prevattq = $conn->prepare($query);
							$prevattq->execute();
							$result5 = $prevattq->get_result();
							
							$creds5 = $result5->fetch_assoc();
							$prevattq->close();
							
							//check for status of prev att of same class
							if($creds5['status']==0)
							{
								//echo("<script>alert('".$years[$i].$coursenames[$i]."-".$divisions[$i].": ".$creds5['status']."')</script>");
								echo(" <option id='incomplete' value = '".$classvalue."'> ".ucwords($subnames[$i]).": ".ucwords($years[$i])." ".ucwords($coursenames[$i])." (Div ".ucwords($divisions[$i]).") </option> ");
							}else{
								echo(" <option id='complete' value = '".$classvalue."'> ".ucwords($subnames[$i]).": ".ucwords($years[$i])." ".ucwords($coursenames[$i])." (Div ".ucwords($divisions[$i]).") </option> ");
							}
							
						}
					?>
				</select>
			</div>
			
			<div class="col-sm-12 col-md-6 offset-md-3">
			<center><button type="submit" value="start" class="sbutton">START</button></center>
			</div>
		</div>
	</form>
</div>

<script>
	$("#classvalue").change(function(){
  			{
				if ($(this).children(":selected").attr("id")== "incomplete") {
				   alert("WARNING: Previous Attendance Of This Class Is Not Completed Yet. Starting A New One Will Delete Previous Data.");
				}	
  			}
		});

</script>