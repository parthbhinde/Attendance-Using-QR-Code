
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
#tid, #teachername{cursor: pointer;}
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
          <a class="dropdown-item" href="subject.php">Subject</a>
		  <a class="dropdown-item" href="student.php">Student</a>
        </div>
      </li>
          
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
			
        </ul>
    </div>
</nav>
<p class="text-center font-weight-bold" style="margin-top:10;">Teachers</p>
<button id="fab" class="btn btn-primary" type="button" data-toggle="modal" data-target="#addcourseModal" >+</button>
<div class="container-fluid">
<?php
	require('connect.php');	
	$query = $conn->prepare("SELECT * FROM `teacherinfo`");
  $query->execute();
  $r = $query->get_result();
	echo('<table id="mytable" class="table table-hover"><thead id="tablehead"><tr ><th>Select</th><th id="tid">Teacher ID &#x21D5;</th><th id="teachername">Teacher Name &#x21D5;</th><th>Username</th><th id="rights">Rights</th><th id="sub1">Subject 1</th><th id="sub2">Subject 2</th><th id="sub3">Subject 3</th><th id="sub4">Subject 4</th><th id="sub5">Subject 5</th><th>Edit</th><th>Delete</th></tr></thead><tbody>');
	while ($assoc =$r->fetch_assoc()){
	echo "<tr>";
	echo "<td><input class='get_value' type='checkbox' name='cid[]' value=".$assoc['tid']."></td>";
	echo "<td>".$assoc['tid']."</td>";
  echo "<td>".$assoc['name']."</td>";
  echo "<td>".$assoc['username']."</td>";
  $right = $assoc['rights'];
  if ($right == 't') {
    echo "<td>Teacher</td>";
  }
  else if($right == 'a') {
    echo "<td>Admin</td>";
  }
  else{
    echo "<td>!</td>";
  }
	echo "<td>".$assoc['sub1']."</td>";
  echo "<td>".$assoc['sub2']."</td>";
  echo "<td>".$assoc['sub3']."</td>";
  echo "<td>".$assoc['sub4']."</td>";
  echo "<td>".$assoc['sub5']."</td>";
	//edit button with data
	echo '<td><button type="button" class="btn" data-toggle="modal" data-target="#EditModal" data-tid="'.$assoc['tid'].'" data-tname="'.$assoc['name'].'" data-uname="'.$assoc['username'].'" data-tright="'.$assoc['rights'].'" data-sub1="'.$assoc['sub1'].'"data-sub2="'.$assoc['sub2'].'"data-sub3="'.$assoc['sub3'].'"data-sub4="'.$assoc['sub4'].'"data-sub5="'.$assoc['sub5'].'">&#x1f58a;</button></td>';
	echo "<td>";
	//single delete form
	echo "<form action='teacherdel.php' method='post'  style='width=50%;'>";
	echo "<input name='tid' type='text' value= ".$assoc['tid']." style= 'display:none;width:200;'/>";
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
			  <h4 class="modal-title"> Add a Teacher </h4>
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			
			<!-- Modal body -->
      <div class="modal-body">
        <form action="teacheradd.php" method="post">
      <div class="form-group">
            <label for="tname" class="col-form-label">Teacher Name :</label>
            <input name="tname" type="text" id="txt" class="form-control tname" required>
          </div>
          <div class="form-group">
            <label for="username" class="col-form-label">Username :</label>
            <input name="username" type="text" id="txt" class="form-control username" required>
          </div>
          <div class="form-group">
            <label for="password" class="col-form-label">Password :</label>
            <input name="password" type="text" class="form-control password" required>
          </div>
          <div>
        <label class="mr-sm-2" for="inlineFormCustomSelect">Rights</label>
        <select name="rights" class="custom-select mr-sm-2" id="inlineFormCustomSelect">
        <option value="t" selected>Teacher</option>
        <option value="a">Admin</option>
        </select>
          </div>
           <div class="form-group">
            <label for="sub1" class="col-form-label">Subject ID 1:</label>
            <input name="sub1" type="number" class="form-control sub1">
          </div>
           <div class="form-group">
            <label for="sub2" class="col-form-label">Subject ID 2:</label>
            <input name="sub2" type="number" class="form-control sub2">
          </div>
           <div class="form-group">
            <label for="sub3" class="col-form-label">Subject ID 3:</label>
            <input name="sub3" type="number" class="form-control sub3">
          </div>
           <div class="form-group">
            <label for="sub4" class="col-form-label">Subject ID 4:</label>
            <input name="sub4" type="number" class="form-control sub4">
          </div>
           <div class="form-group">
            <label for="sub5" class="col-form-label">Subject ID 5:</label>
            <input name="sub5" type="number" class="form-control sub5">
          </div>
          
        
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
        <h5 class="modal-title" id="exampleModalLabel">Edit Teacher</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="teacheredit.php" method="post">
          <div class="form-group">
            <label for="tid" class="col-form-label">Teacher ID:</label>
            <input name="tid" type="number" class="form-control tid" readonly>
          </div>
		  <div class="form-group">
            <label for="tname" class="col-form-label">Teacher Name :</label>
            <input name="tname" type="text" id="txt" class="form-control tname" required>
          </div>
          <div class="form-group">
            <label for="username" class="col-form-label">Username :</label>
            <input name="username" type="text" id="txt" class="form-control username" required>
          </div>
          <div class="form-group">
            <label for="password" class="col-form-label">Password :</label>
            <input name="password" type="text" class="form-control password">
          </div>
             <div>
        <label class="mr-sm-2" for="inlineFormCustomSelect">Rights</label>
        <select name='rights' class="custom-select mr-sm-2 tright" id="inlineFormCustomSelect">
        <option value="t" selected>Teacher</option>
        <option value="a">Admin</option>
        </select>
          </div>
           <div class="form-group">
            <label for="sub1" class="col-form-label">Subject 1:</label>
            <input name="sub1" type="number" class="form-control sub1">
          </div>
           <div class="form-group">
            <label for="sub2" class="col-form-label">Subject 2:</label>
            <input name="sub2" type="number" class="form-control sub2">
          </div>
           <div class="form-group">
            <label for="sub3" class="col-form-label">Subject 3:</label>
            <input name="sub3" type="number" class="form-control sub3">
          </div>
           <div class="form-group">
            <label for="sub4" class="col-form-label">Subject 4:</label>
            <input name="sub4" type="number" class="form-control sub4">
          </div>
           <div class="form-group">
            <label for="sub5" class="col-form-label">Subject 5:</label>
            <input name="sub5" type="number" class="form-control sub5">
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
  document.getElementById('tid').click();
  document.getElementById('tid').click();
};
</script>
<script>
$('#EditModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) 
  var tid = button.data('tid')
  var tname = button.data('tname')
  var uname = button.data('uname')
  var tright = button.data('tright')
  var sub1 = button.data('sub1')
  var sub2 = button.data('sub2')
  var sub3 = button.data('sub3')
  var sub4 = button.data('sub4')
  var sub5 = button.data('sub5')
  var modal = $(this)
  modal.find('.modal-body .tid').val(tid)
  modal.find('.modal-body .tright').val(tright)
  modal.find('.modal-body .tname').val(tname)
  modal.find('.modal-body .sub1').val(sub1)
  modal.find('.modal-body .sub2').val(sub2)
  modal.find('.modal-body .sub3').val(sub3)
  modal.find('.modal-body .sub4').val(sub4)
  modal.find('.modal-body .sub5').val(sub5)
  modal.find('.modal-body .username').val(uname)

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
                url:"teacherdelmulti.php",  
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

var f_tid = 1;
var f_teachername = 1;


$("#tid").click(function(){
    f_tid *= -1;
    var n = $(this).prevAll().length;
    sortTable(f_tid,n);
});
$("#teachername").click(function(){
    f_teachername *= -1;
    var n = $(this).prevAll().length;
    sortTable(f_teachername,n);
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