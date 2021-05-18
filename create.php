<?php
require('db.php');
$error = "";
$id = "";
$name = "";
$email = "";
$phone = "";
$password = "";

     
if(isset($_GET['id']) && $_GET['id'] > 0){
    $id = $_GET['id'];
    $sql = "SELECT * FROM `form`  WHERE id = $id ";
    $res = mysqli_query($con, $sql);
    if(mysqli_num_rows($res) > 0){
        $data = mysqli_fetch_assoc($res);
        $id = $data['id'];
        $name = $data['name'];
        $email = $data['email'];
        $phone = $data['phone'];
        $password = $data['password'];
        $image = $data['image'];
    }
}

if(isset($_POST['submit'])){

    if(empty($_POST['name'])){
        $nameerror = "Name field is required";
        $error = "yes";
    }else{
        $name = mysqli_real_escape_string($con, $_POST['name']);
    }

    if(empty($_POST['email'])){
         $emailerror = "Email field is required";
         $error = "yes";
    }else{
       $email = mysqli_real_escape_string($con, $_POST['email']);
    }

    if(empty($_POST['phone'])){
         $phoneerror = "Phone field is required";
         $error = "yes";
    }else{
      $phone = mysqli_real_escape_string($con, $_POST['phone']);
    }

    if(empty($_POST['password'])){
         $passworderror = "Password field is required";
         $error = "yes";
    }else{
         $password = mysqli_real_escape_string($con, $_POST['password']);
    }
    
    if($_FILES['image']['type'] !== "image/png" && $_FILES['image']['type'] !== "image/jpg" && $_FILES['image']['type'] !== "image/jpeg"){
         $imageerror = "Choose Valid image type";
         $error = "yes";
    }else{
      $error = "";
    }


    if(empty($_FILES['image']['name'])){
        if($_POST['id']){
            $error = "";
        }else{
             $imageerror = "image field is required";
              $error = "yes";
        }
    }else{
        $image = $_FILES['image']['name'];
    }
    
    if($_POST['id'] > 0){
        $id = $_POST['id'];
        $sql = "select name from form where name= '$name' and id != $id";
    }else{
        $sql = "select name from form where name= '$name'";
    }
    
    if(mysqli_num_rows(mysqli_query($con,$sql)) > 0){
         $nameerror = "Name is already taken";
         $error = "yes";
    }

if($error == ""){
    if($_POST['id'] > 0){
        $id = $_POST['id'];
        $image_condition="";
        if($_FILES['image']['name'] !== ""){
            $image = mysqli_fetch_assoc(mysqli_query($con, "select image from form where id = '$id'"));
            unlink("upload/".$image['image']);
            $image = time().'_'.$_FILES['image']['name'];
            $image_condition =", image='$image'";
            move_uploaded_file($_FILES['image']['tmp_name'], "upload/".$image);
        }
        $sql = "UPDATE form SET name= '$name', email='$email', phone='$phone', password='$password' $image_condition WHERE id = $id";
        $result = mysqli_query($con, $sql);
        if ($result) { 
          $_SESSION['msg'] = "Record Updated Successfully";
          header("Location:index.php");
        } else {
          echo "Error: " . $sql . "<br>" . mysqli_error($con);
        }
      }else{
      $image = time().'_'.$_FILES['image']['name'];
      move_uploaded_file($_FILES['image']['tmp_name'], "upload/".$image);
       $sql = "INSERT INTO `form`( `name`, `email`, `phone`, `password`, `image`) VALUES ('$name','$email','$phone','$password','$image')";
            if (mysqli_query($con, $sql)) {
                $_SESSION['msg'] = "Record Added Successfully";
                header("Location:index.php");
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($con);
            }
    }
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
    <div class="col-md-2"></div>
    <div class="col-md-8">
      <h3 class="text-center mb-5" style="color: red;">Form</h3>
      <a href="index.php" class="btn btn-success mb-3">Back</a>   
      <form method="post" id="frmData" enctype="multipart/form-data">
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>">
          <br>
          <?php if(isset($nameerror)){
          ?>
          <div class="alert alert-warning" role="alert">
              <?php echo $nameerror; ?>
          </div>
          <?php
          } 
          ?>
             
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" class="form-control" id="email" name="email" value="<?php echo $email ;?>"><br>
          <?php if(isset($emailerror)){
          ?>
          <div class="alert alert-warning" role="alert">
              <?php echo $emailerror; ?>
          </div>
          <?php
          } 
          ?>
        </div>
        <div class="form-group">
          <label for="phone">Phone</label>
          <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $phone; ?>"><br>
          <?php if(isset($phoneerror)){
          ?>
          <div class="alert alert-warning" role="alert">
              <?php echo $phoneerror; ?>
          </div>
          <?php
          } 
          ?>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="text" class="form-control" id="password" name="password"  value="<?php echo $password; ?>"><br>
          <?php if(isset($passworderror)){
          ?>
          <div class="alert alert-warning" role="alert">
              <?php echo $passworderror; ?>
          </div>
          <?php
          } 
          ?>
        </div>
        <div class="form-group">
          <label for="image">Image</label>
          <input type="file" class="form-control" id="image" name="image"><br>
          <?php if(isset($image)){
          ?>
              <img src="upload/<?php echo $image; ?>" width="100px">
     
          <?php
          } 
          ?>
          
          <?php if(isset($imageerror)){
          ?>
          <div class="alert alert-warning" role="alert">
              <?php echo $imageerror; ?>
          </div>
          <?php
          } 
          ?>
        </div>
        <input type="hidden" name="id"  value="<?php echo $id; ?>">
        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
      </form>

    </div> 
   </div>
  
</div>
</body>
</html>