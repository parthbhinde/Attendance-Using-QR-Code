
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
#id, #rollno, #name, #status {cursor: pointer;}
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
          <a class="dropdown-item" href="subject.php">Subject</a>
        </div>
      </li>
            
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
            
        </ul>
    </div>
</nav>
<div class="container-fluid">
<p class="text-center font-weight-bold" style="margin-top:10;">Students</p>
<div class="text-center">
<form class="form-inline">
    <label for="cid">Enter Class ID :</label>&nbsp
    <input type="text" name="cid"></input>&nbsp
    <input type="submit" class="btn btn-primary"></input>
</form>
</div>
<?php
if (isset($_GET['cid'])) {
    $cid = $_GET['cid'];
    echo "<div class='text-center'><h4>Class ID: ".$cid."</h4></div>";
?>

<div class="table-responsive">
<?php
    require('connect.php'); 
    $check = $conn->prepare("SELECT * FROM `classinfo` WHERE `classid` = ?");
    $check->bind_param("i", $cid);
	$check->execute();
    $r = $check->get_result();
    if($r->num_rows == 1){
    $query = $conn->prepare("SELECT * FROM `studentinfo` WHERE `classid` = ?");
    $query->bind_param("i", $cid);
    $query->execute();
    $r = $query->get_result();
    echo('<table id="mytable" class="table table-hover"><thead id="tablehead"><tr><th>Select</th><th id="id">ID Card No &#x21D5;</th><th id="rollno">Roll NO &#x21D5;</th><th id="name">Name &#x21D5;</th><th>Username</th><th id="status">Status &#x21D5;</th><th>Edit</th><th>Delete</th></tr></thead><tbody>');
    while ($assoc = $r->fetch_assoc()){
    echo "<tr>";
    echo "<td><input class='get_value' type='checkbox' name='cid[]' value=".$assoc['id']."></td>";
    echo "<td>".$assoc['id']."</td>";
    echo "<td>".$assoc['rollno']."</td>";
    echo "<td>".$assoc['name']."</td>";
    echo "<td>".$assoc['username']."</td>";
    $status = $assoc['status'];
    if ($status == '0') {
        echo "<td></td>";
    }
    else if ($status == '1') {
        echo "<td>Blocked</td>";
    }
    echo '<td><button type="button" class="btn" data-toggle="modal" data-target="#EditModal" data-sid="'.$assoc['id'].'" data-rollno="'.$assoc['rollno'].'" data-name="'.$assoc['name'].'" data-classid="'.$assoc['classid'].'" data-username="'.$assoc['username'].'" data-status="'.$assoc['status'].'">&#x1f58a;</button></td>';
    echo "<td>";
    echo "<form action='studentdel.php' method='post'  style='width=50%;'>";
    echo "<input name='sid' type='text' value= ".$assoc['id']." style= 'display:none;width:200;'/>";
    echo "<input name='classid' type='number' value= ".$assoc['classid']." style='display:none;'>";
    echo "<input name='rno' type='number' value= ".$assoc['rollno']." style='display:none;'>";
    echo "<button class='cnf btn btn-danger' type='submit' name = 'submit' style='width:50;'>X</button>";
    echo "</form>";
    echo"</td>";
    echo "</tr>";
    } 

?>
</tbody>
</table>
<button type="button" name="submit" class="btn btn-danger cnf" id="multi-del">Delete Selected</button> 
<button id="fab" class="btn btn-primary" type="button" data-toggle="modal" data-target="#addclassModal" >+</button> 
</div>
</div>
<div>
<?php
}
else{
    echo "INVALID CLASS ID";
  }
}
?>
<!-- ADD Modal -->
      <div class="modal fade" id="addclassModal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
          
            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title"> Add a student to this class </h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
      <div class="modal-body">
        <form action="studentadd.php" method="post">
        <div class="form-group">
            <label for="sid" class="col-form-label">ID Card No :</label>
            <input name="sid" type="text" id="txt" class="form-control" required>
          </div>
      <div class="form-group">
            <label for="rno" class="col-form-label">Roll No :</label>
            <input name="rno" type="text" id="txt" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="name" class="col-form-label">Name :</label>
            <input name="name" type="text" id='txt' class="form-control" required>
          </div>
          <div class="form-group">
            <label for="username" class="col-form-label">Username :</label>
            <input name="username" type="text" id='txt' class="form-control" required>
          </div>
          <div class="form-group">
            <label for="password" class="col-form-label">Password :</label>
            <input name="password" type="text" class="form-control" required>
          </div>
           <div class="form-group">
            <label for="classid" class="col-form-label">Class ID :</label>
            <input name="classid" type="number" value="<?php echo $cid;?>" class="form-control" readonly>
          </div>
      </div>
            
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
        <h5 class="modal-title" id="exampleModalLabel">Edit Student</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="studentedit.php" method="post">
          <div class="form-group">
            <label for="sid" class="col-form-label">ID Card No :</label>
            <input name="sid" type="number" class="form-control sid" readonly>
          </div>
          <div class="form-group">
            <label for="rno" class="col-form-label">Roll No :</label>
            <input name="rno" type="number" class="form-control rollno" required>
          </div>
          <div class="form-group">
            <label for="name" class="col-form-label">Name :</label>
            <input name="name" type="text" class="form-control name" id="txt" required>
          </div>
          <div class="form-group">
            <label for="username" class="col-form-label">Username :</label>
            <input name="username" type="text" class="form-control Username" id="txt" required>
          </div>
          <div class="form-group">
            <label for="password" class="col-form-label">Password :</label>
            <input name="password" type="text" class="form-control Password">
          </div>
          <div class="form-group">
            <label for="classid" class="col-form-label">Class ID:</label>
            <input name="classid" type="text" class="form-control classid" id="txt" required>
          </div>
          <div>
        <label class="mr-sm-2" for="inlineFormCustomSelect">Status</label>
        <select name="status" class="custom-select mr-sm-2 status" id="inlineFormCustomSelect">
        <option selected>Choose</option>
        <option value="1">Block</option>
        <option value="0">Un-Block</option>
        </select>
          </div>
        
      </div>
      <div class="modal-footer">
        <input type="text" name='cid' value="<?php echo $cid;?>" style='display: none;'></input>
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
  document.getElementById('rollno').click();
  document.getElementById('rollno').click();
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
                url:"studentdelmulti.php",  
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
  var sid = button.data('sid')
  var rollno = button.data('rollno')
  var name = button.data('name')
  var username = button.data('username')
  var classid = button.data('classid')
  var status = button.data('status')
  var modal = $(this)
  modal.find('.modal-body .sid').val(sid)
  modal.find('.modal-body .rollno').val(rollno)
  modal.find('.modal-body .name').val(name)
  modal.find('.modal-body .classid').val(classid)
  modal.find('.modal-body .status').val(status)
  modal.find('.modal-body .username').val(username)
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
var f_id = 1;
var f_rollno = 1;
var f_name = 1;
var f_classid = 1;
var f_status = 1;
$("#id").click(function(){
    f_id *= -1;
    var n = $(this).prevAll().length;
    sortTable(f_id,n);
});
$("#rollno").click(function(){
    f_rollno *= -1;
    var n = $(this).prevAll().length;
    sortTable(f_rollno,n);
});
$("#name").click(function(){
    f_name *= -1;
    var n = $(this).prevAll().length;
    sortTable(f_name,n);
});
$("#classid").click(function(){
    f_classid *= -1;
    var n = $(this).prevAll().length;
    sortTable(f_classid,n);
});
$("#status").click(function(){
    f_status *= -1;
    var n = $(this).prevAll().length;
    sortTable(f_status,n);
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