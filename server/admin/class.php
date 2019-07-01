
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
	#classid, #courseid, #coursename, #year, #division {cursor: pointer;}
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
		  <a class="dropdown-item" href="course.php">Course</a>
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
<p class="text-center font-weight-bold" style="margin-top:10;">Classes</p>
<button id="fab" class="btn btn-primary" type="button" data-toggle="modal" data-target="#addclassModal" >+</button>
<div class="container">
<div class="table-responsive">
<?php
	require('connect.php');	
	$query = $conn->prepare("SELECT * FROM `courseinfo` LEFT JOIN `classinfo` on courseinfo.courseid = classinfo.courseid");
  $query->execute();
  $r = $query->get_result();
	echo('<table id="mytable" class="table table-hover"><thead id="tablehead"><tr ><th>Select</th><th id="classid">Class ID &#x21D5;</th><th id="courseid">Course ID &#x21D5;</th><th id="coursename">Course Name &#x21D5;</th><th id="year">Year &#x21D5;</th><th id="division">Division &#x21D5;</th><th>Edit</th><th>Delete</th></tr></thead><tbody>');
	while ($assoc = $r->fetch_assoc()){
	if(is_null($assoc['classid'])){}
	else{
	
	echo "<tr>";
	echo "<td><input class='get_value' type='checkbox' name='cid[]' value=".$assoc['classid']."></td>";
	echo "<td>".$assoc['classid']."</td>";
	echo "<td>".$assoc['courseid']."</td>";
	echo "<td>".$assoc['coursename']."</td>";
	echo "<td>".$assoc['year']."</td>";
	echo "<td>".$assoc['division']."</td>";
	echo '<td><button type="button" class="btn" data-toggle="modal" data-target="#EditModal" data-classid="'.$assoc['classid'].'" data-courseid="'.$assoc['courseid'].'" data-year="'.$assoc['year'].'" data-division="'.$assoc['division'].'">&#x1f58a;</button></td>';
	echo "<td>";
	echo "<form action='classdel.php' method='post'  style='width=50%;'>";
	echo "<input name='classid' type='text' value= ".$assoc['classid']." style= 'display:none;width:200;'/>";
	echo "<button class='cnf btn btn-danger' type='submit' name = 'submit' style='width:50;'>X</button>";
	echo "</form>";
	echo"</td>";
	echo "</tr>";
	}
	}
?>
</tbody>
</table>
<button type="button" name="submit" class="btn btn-danger cnf" id="multi-del">Delete Selected</button>  
</div>
</div>
<div>
<!-- ADD Modal -->
	  <div class="modal fade" id="addclassModal">
		<div class="modal-dialog modal-lg modal-dialog-centered">
		  <div class="modal-content">
		  
			<!-- Modal Header -->
			<div class="modal-header">
			  <h4 class="modal-title"> Add a class </h4>
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			
			<!-- Modal body -->
<div class="modal-body">
    <form action="classadd.php" method="post">
    <div class="form-group">
    <label for="courseid" class="col-form-label">Course ID :</label>
    <input type="number" class="form-control" name="courseid" required>
  </div>
  <div>
    <label class="mr-sm-2" for="inlineFormCustomSelect">Year :</label>
    <select name="year" class="custom-select mr-sm-2 year" id="inlineFormCustomSelect">
      <option value="fy" selected>FY</option>
      <option value="sy">SY</option>
      <option value="ty">TY</option>
    </select>
  </div>
  <div class="form-group">
    <label for="division" class="col-form-label">Division :</label>
    <input name="division" class="form-control" value="a" id="txt" type="text" required>
  </div>
</div>
			
			<!-- Modal footer -->
			<div class="modal-footer">
			   <input class="btn btn-primary"type="submit" name="submit" value="ADD" >
			   </form>
			</div>
			
		  </div>
		</div>
	  </div> 
	  <!--EditModal-->
	  <div class="modal fade" id="EditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Class</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="classedit.php" method="post">
          <div class="form-group">
            <label for="classid" class="col-form-label">Class ID:</label>
            <input name="classid" type="number" class="form-control classid" readonly>
          </div>
		  <div class="form-group">
            <label for="courseid" class="col-form-label">Course ID:</label>
            <input name="courseid" type="number" class="form-control courseid" required>
          </div>
          <div>
        <label class="mr-sm-2" for="inlineFormCustomSelect">Year:</label>
        <select name="year" class="custom-select mr-sm-2 year" id="inlineFormCustomSelect">
        <option selected>Choose</option>
        <option value="fy">FY</option>
        <option value="sy">SY</option>
        <option value="ty">TY</option>
        </select>
          </div>
		  <div class="form-group">
            <label for="division" class="col-form-label">Division:</label>
            <input name="division" value="a" type="text" class="form-control division" id="txt" required>
          </div>
          
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Edit</button>
		</form>
      </div>
    </div>
  </div>
</div>
</div>

<script>
window.onload = function() {
  document.getElementById('classid').click();
  document.getElementById('classid').click();
};
</script>
<script>
$(document).ready(function(){  
      $('#multi-del').click(function(){  
           var languages = [];  
           $('.get_value').each(function(){  
                if($(this).is(":checked"))  
                {  
                     languages.push($(this).val());  
                }  
           });  
            
           $.ajax({  
                url:"classdelmulti.php",  
                method:"POST",  
                data:{languages:languages}, 
                success:function(text){
					alert(text);
				}  
           });  
      });  
 });  
 </script> 
 <!--Edit Modal-->
<script>
$('#EditModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) 
  var classid = button.data('classid')
  var courseid = button.data('courseid')
  var year = button.data('year')
  var division = button.data('division')
  var modal = $(this)
  modal.find('.modal-body .classid').val(classid)
  modal.find('.modal-body .courseid').val(courseid)
  modal.find('.modal-body .year').val(year)
  modal.find('.modal-body .division').val(division)
})
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
var f_classid = 1;
var f_courseid = 1;
var f_coursename = 1;
var f_division = 1;
var f_year = 1;
$("#classid").click(function(){
    f_classid *= -1;
    var n = $(this).prevAll().length;
    sortTable(f_classid,n);
});
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
$("#division").click(function(){
    f_division *= -1;
    var n = $(this).prevAll().length;
    sortTable(f_division,n);
});
$("#year").click(function(){
    f_year *= -1;
    var n = $(this).prevAll().length;
    sortTable(f_year,n);
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