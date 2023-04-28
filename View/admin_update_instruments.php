<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['update_instruments'])){

   $update_in_id = $_POST['update_in_id'];
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $price = mysqli_real_escape_string($conn, $_POST['price']);
   $details = mysqli_real_escape_string($conn, $_POST['details']);

   mysqli_query($conn, "UPDATE `instruments` SET name = '$name', details = '$details', price = '$price' WHERE id = '$update_in_id'") or die('query failed');

   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folter = 'uploaded_img/'.$image;
   $old_image = $_POST['update_in_image'];
   
   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'image file size is too large!';
      }else{
         mysqli_query($conn, "UPDATE `instruments` SET image = '$image' WHERE id = '$update_in_id'") or die('query failed');
         move_uploaded_file($image_tmp_name, $image_folter);
         unlink('uploaded_img/'.$old_image);
         $message[] = 'image updated successfully!';
      }
   }

   $message[] = 'instruments updated successfully!';

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update product</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php @include 'admin_header.php'; ?>

<section class="update-instruments">

<?php

   $update_id = $_GET['update'];
   $select_instruments = mysqli_query($conn, "SELECT * FROM `instruments` WHERE id = '$update_id'") or die('query failed');
   if(mysqli_num_rows($select_instruments) > 0){
      while($fetch_instruments = mysqli_fetch_assoc($select_instruments)){
?>

<form action="" method="post" enctype="multipart/form-data">
   <img src="uploaded_img/<?php echo $fetch_instruments['image']; ?>" class="image"  alt="">
   <input type="hidden" value="<?php echo $fetch_instruments['id']; ?>" name="update_in_id">
   <input type="hidden" value="<?php echo $fetch_instruments['image']; ?>" name="update_in_image">
   <input type="text" class="box" value="<?php echo $fetch_instruments['name']; ?>" required placeholder="update instruments name" name="name">
   <input type="number" min="0" class="box" value="<?php echo $fetch_instruments['price']; ?>" required placeholder="update instruments price" name="price">
   <textarea name="details" class="box" required placeholder="update instruments details" cols="30" rows="10"><?php echo $fetch_instruments['details']; ?></textarea>
   <input type="file" accept="image/jpg, image/jpeg, image/png" class="box" name="image">
   <input type="submit" value="update instruments" name="update_instruments" class="btn">
   <a href="admin_instruments.php" class="option-btn">go back</a>
</form>

<?php
      }
   }else{
      echo '<p class="empty">no update instruments select</p>';
   }
?>

</section>













<script src="js/admin_script.js"></script>

</body>
</html>