<?php
require('db.php');
 $sql = "SELECT * FROM `form`";
 $res = mysqli_query($con, $sql);


if(isset($_GET['id']) && $_GET['id'] > 0){
    $id = $_GET['id'];
    $sql = "DELETE  FROM form  WHERE id = $id ";
    
    if(mysqli_query($con, $sql)){
    	$_SESSION['msg'] = "Record Delete Successfully";
        ?>
      <script>
			  setInterval(function(){
			  	window.location.href= "index.php";
			   }, 2000);

		
      </script>
      
        <?php

    }else{
    	$_SESSION['msg'] = "Something Went Wrong";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="file/css/bootstrap.min.css">
	<script src="file/js/jquery.min.js"></script>
    <script src="file/js/bootstrap.min.js"></script>
		
</head>
<body>

<div class="container p-5">
   <div class="row">
	 <div class="col-md-12">
	 	<h3 class="text-center mb-5" style="color: red;">Table Data</h3>
	 	<?php if(isset($_SESSION['msg'])){?>	
	               <div class="alert alert-success alert-dismissible fade show" role="alert">
				   <?php echo $_SESSION['msg']; ?>
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				    <span aria-hidden="true">&times;</span>
				  </button>
				</div>   
	         <?php }	?>
	 
		<a href="create.php" class="btn btn-success mb-3">Add Data</a>   

		  <table class="table table-bordered table-striped">
		    <thead>
		      <tr>
		        <th>Firstname</th>
		        <th>Email</th>
		        <th>Phone</th>
		        <th>Password</th>
		        <th>Image</th>
		        <th>Action</th>
		      </tr>
		    </thead>
		    <tbody>
		      
		      	<?php 
                if(mysqli_num_rows($res) > 0){
                 while($row  = mysqli_fetch_assoc($res)){

                 ?>
                 <tr>
                    <td><?php echo $row['name']; ?></td>
			        <td><?php echo $row['email']; ?></td>
			        <td><?php echo $row['phone']; ?></td>
			        <td><?php echo $row['password']; ?></td>
			        <td><img src="upload/<?php echo $row['image']; ?>" width="100px"></td>
			        <td>
			        	<a href="create.php?id=<?php echo $row['id'] ?>" class="btn btn-warning">Edit</a>
			        	<a href="index.php?id=<?php echo $row['id'] ?>" class="btn btn-danger">Delete</a>
			      </tr>
                 <?php 
                 }  } 
		      	?>
		       
		    </tbody>
		  </table>

	 	
	 </div>	
   </div>
  
</div>
</body>
<?php unset($_SESSION['msg']); ?>
</html>