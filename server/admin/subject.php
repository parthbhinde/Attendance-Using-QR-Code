
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
#subjectid, #subjectname , #classid,#classname {cursor: pointer;}
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
          <a class="dropdown-item" href="class.php">Class</a>
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
<p class="text-center font-weight-bold" style="margin-top:10;">Subjects</p>
<button id="fab" class="btn btn-primary" type="button" data-toggle="modal" data-target="#addcourseModal" >+</button>
<div class="container">
<?php
	require('connect.php');	
	$query = $conn->prepare("SELECT * FROM `subjectinfo`,`classinfo`,`courseinfo` WHERE courseinfo.courseid = classinfo.courseid AND classinfo.classid = subjectinfo.classid");
    $query->execute();
    $r = $query->get_result();
	echo('<table id="mytable" class="table table-hover"><thead id="tablehead"><tr ><th>Select</th><th id="subjectid">Subject ID &#x21D5;</th><th id="subjectname">Subject Name &#x21D5;</th><th id="classid">Class ID</th><th id="classname">Class Name</th><th>Edit</th><th>Delete</th></tr></thead><tbody>');
	while ($assoc = $r->fetch_assoc()){
	echo "<tr>";
	echo "<td><input class='get_value' type='checkbox' name='cid[]' value=".$assoc['subjectid']."></td>";
	echo "<td>".$assoc['subjectid']."</td>";
	echo "<td>".$assoc['subname']."</td>";
	echo "<td>".$assoc['classid']."</td>";
	$classname = $assoc['year']."-".$assoc['coursename']."-".$assoc['division'];
	echo "<td>".$classname."</td>";
	//edit button with data
	echo '<td><button type="button" class="btn" data-toggle="modal" data-target="#EditModal" data-subjectid="'.$assoc['subjectid'].'" data-subjectname="'.$assoc['subname'].'" data-classid="'.$assoc['classid'].'">&#x1f58a;</button></td>';
	echo "<td>";
	//single delete form
	echo "<form action='subjectdel.php' method='post'  style='width=50%;'>";
	echo "<input name='subid' type='text' value= ".$assoc['subjectid']." style= 'display:none;width:200;'/>";
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

	  <div>
<!-- ADD Modal -->
	  <div class="modal fade" id="addcourseModal">
		<div class="modal-dialog modal-lg modal-dialog-centered">
		  <div class="modal-content">
		  
			<!-- Modal Header -->
			<div class="modal-header">
			  <h4 class="modal-title"> Add a Subject </h4>
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			
			<!-- Modal body -->
			<div class="modal-body">
			  <table class="table"><thead id="tablehead"><tr ><th></th><th>Class ID</th><th>Subject Name</th></tr></thead><tbody>
<tbody>
<tr>
<td>
Add:
</td>
<form action="subjectadd.php" method="post" style="width=50%;">
<td>
<input name="classid" type="number" required></input>
</td>
<td>
<input name="subname" id="txt" type="text" required></input>
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

<!--EditModal-->
	  <div class="modal fade" id="EditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Subject</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="subjectedit.php" method="post">
          <div class="form-group">
            <label for="subjectid" class="col-form-label">Subject ID:</label>
            <input name="subjectid" type="number" class="form-control subjectid" readonly>
          </div>
		  <div class="form-group">
            <label for="classid" class="col-form-label">Class ID :</label>
            <input name="classid" type="number" class="form-control classid" required>
          </div>
		  <div class="form-group">
            <label for="subjectname" class="col-form-label">Subject Name:</label>
            <input name="subjectname" type="text" class="form-control subjectname" id="txt" required>
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
  document.getElementById('subjectid').click();
  document.getElementById('subjectid').click();
};
</script>
<script>
$('#EditModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) 
  var subjectid = button.data('subjectid')
  var classid = button.data('classid')
  var subjectname = button.data('subjectname')
  var modal = $(this)
  modal.find('.modal-body .subjectid').val(subjectid)
  modal.find('.modal-body .classid').val(classid)
  modal.find('.modal-body .subjectname').val(subjectname)
})
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
                url:"subjectdelmulti.php",  
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

var f_subjectid = 1;
var f_subjectname = 1;
var f_classid = 1;
var f_classname = 1;

$("#subjectid").click(function(){
    f_subjectid *= -1;
    var n = $(this).prevAll().length;
    sortTable(f_subjectid,n);
});
$("#subjectname").click(function(){
    f_subjectname *= -1;
    var n = $(this).prevAll().length;
    sortTable(f_subjectname,n);
});
$("#classid").click(function(){
    f_classid *= -1;
    var n = $(this).prevAll().length;
    sortTable(f_classid,n);
});
$("#classname").click(function(){
    f_classname *= -1;
    var n = $(this).prevAll().length;
    sortTable(f_classname,n);
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