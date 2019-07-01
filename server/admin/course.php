
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
	
	<style>
	
	#txt {
    text-transform:lowercase;
}
#fab {
	border-radius: 50px;
	padding:0;
	padding-left:10;
	padding-right:10;
    position: fixed;
    bottom: 20px;
    right: 20px;
	font-size:30;
            }
#courseid, #coursename {cursor: pointer;}
.navbar-brand.abs
    {
        position: absolute;
        left: 50%;
        text-align: center;
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
                <a class="nav-link" href="admin.php">Home</a>
            </li>
			<li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          CRUD
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="class.php">Class</a>
          <a class="dropdown-item" href="subject.php">Subject</a>
          <a class="dropdown-item" href="teacher.php">Teacher</a>
		  <a class="dropdown-item" href="student.php">Student</a>
        </div>
      </li>
            
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
			
        </ul>
    </div>
</nav>
<p class="text-center font-weight-bold" style="margin-top:10;">Courses</p>
<button id="fab" class="btn btn-primary" type="button" data-toggle="modal" data-target="#addcourseModal" >+</button>
<div class="container">
<?php
	require('connect.php');	
	$query = $conn->prepare("SELECT * FROM `courseinfo`");
	$query->execute();
	$r = $query->get_result();
	echo('<table id="mytable" class="table table-hover"><thead id="tablehead"><tr ><th>Select</th><th id="courseid">Course ID &#x21D5;</th><th id="coursename">Name &#x21D5;</th><th>Delete</th></tr></thead><tbody>');
	while ($assoc = $r->fetch_assoc()){
	
	echo "<tr>";
	echo "<td><input class='get_value' type='checkbox' name='cid[]' value=".$assoc['courseid']."></td>";
	echo "<td>".$assoc['courseid']."</td>";
	echo "<td>".$assoc['coursename']."</td>";
	echo "<td>";
	//single delete form
	echo "<form action='coursedel.php' method='post'  style='width=50%;'>";
	echo "<input name='cid' type='text' value= ".$assoc['courseid']." style= 'display:none;width:200;'/>";
	echo "<button class='cnf btn btn-danger' type='submit' name = 'submit' style='width:50;' id='single'>X</button>";
	echo "</form>";
	echo"</td>";
	echo "</tr>";
	
	}
?>

</tbody>
</table>
<button type="button" name="submit" class="btn btn-danger cnf" id="multi">Delete Selected</button>  
</div>
<div id="result"></div> 
	  <div>
<!-- ADD Student Modal -->
	  <div class="modal fade" id="addcourseModal">
		<div class="modal-dialog modal-lg modal-dialog-centered">
		  <div class="modal-content">
		  
			<!-- Modal Header -->
			<div class="modal-header">
			  <h4 class="modal-title"> Add a Course </h4>
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			
			<!-- Modal body -->
			<div class="modal-body">
			  <table class="table"><thead id="tablehead"><tr ><th>Option</th><th>Course Name</th><th></th></tr></thead><tbody>
<tbody>
<tr>
<td>
Add:
</td>
<form action="courseadd.php" method="post" style="width=50%;">
<td>
<input name="cname" id="txt" type="text" required>
</td>
<td>
</td>
</tr>
</tbody>
</table>
			</div>
			
			<!-- Modal footer -->
			<div class="modal-footer">
			   <input type="submit" class="btn btn-primary" name="submit" value="ADD" >
			   </form>
			</div>
			
		  </div>
		</div>
	  </div> 
</div>
	  


<script>
window.onload = function() {
  document.getElementById('courseid').click();
  document.getElementById('courseid').click();
};
</script>
<script>

 $(document).ready(function(){  
      $('#multi').click(function(){  
           var languages = [];  
           $('.get_value').each(function(){  
                if($(this).is(":checked"))  
                {  
                     languages.push($(this).val());  
                }  
           });  
            
           $.ajax({  
                url:"coursedelmulti.php",  
                method:"POST",  
                data:{languages:languages}, 
                success:function(text){
					alert(text);
				}  
           });  
      });  
 });  
 </script>  

<script>
function sortTable(f,n){
	var rows = $('#mytable tbody  tr').get();

	rows.sort(function(a, b) {

		var A = getVal(a);
		var B = getVal(b);

		if(A < B) {
			return -1*f;
		}
		if(A > B) {
			return 1*f;
		}
		return 0;
	});

	function getVal(elm){
		var v = $(elm).children('td').eq(n).text().toUpperCase();
		if($.isNumeric(v)){
			v = parseInt(v,10);
		}
		return v;
	}

	$.each(rows, function(index, row) {
		$('#mytable').children('tbody').append(row);
	});
}

var f_courseid = 1;
var f_coursename = 1;

$("#courseid").click(function(){
    f_courseid *= -1;
    var n = $(this).prevAll().length;
    sortTable(f_courseid,n);
});
$("#coursename").click(function(){
    f_coursename *= -1;
    var n = $(this).prevAll().length;
    sortTable(f_coursename,n);
});

</script>
<script>
$(function() {
    $('.cnf').click(function() {
        return window.confirm("Are you sure?");
    });
});
</script>

</body>
</html>