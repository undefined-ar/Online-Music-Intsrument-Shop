<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['add_instruments'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $price = mysqli_real_escape_string($conn, $_POST['price']);
   $details = mysqli_real_escape_string($conn, $_POST['details']);
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folter = 'uploaded_img/'.$image;

   $select_instruments_name = mysqli_query($conn, "SELECT name FROM `instruments` WHERE name = '$name'") or die('query failed');

   if(mysqli_num_rows($select_instruments_name) > 0){
      $message[] = 'instruments name already exist!';
   }else{
      $insert_instruments = mysqli_query($conn, "INSERT INTO `instruments`(name, details, price, image) VALUES('$name', '$details', '$price', '$image')") or die('query failed');

      if($insert_product){
         if($image_size > 2000000){
            $message[] = 'image size is too large!';
         }else{
            move_uploaded_file($image_tmp_name, $image_folter);
            $message[] = 'instruments added successfully!';
         }
      }
   }

}

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $select_delete_image = mysqli_query($conn, "SELECT image FROM `instruments` WHERE id = '$delete_id'") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($select_delete_image);
   unlink('uploaded_img/'.$fetch_delete_image['image']);
   mysqli_query($conn, "DELETE FROM `instruments` WHERE id = '$delete_id'") or die('query failed');
   mysqli_query($conn, "DELETE FROM `wishlist` WHERE pid = '$delete_id'") or die('query failed');
   mysqli_query($conn, "DELETE FROM `cart` WHERE pid = '$delete_id'") or die('query failed');
   header('location:admin_instruments.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php @include 'admin_header.php'; ?>

<section class="add-instruments">

   <form action="" method="POST" enctype="multipart/form-data">
      <h3>add new product</h3>
      <input type="text" class="box" required placeholder="enter instruments name" name="name">
      <input type="number" min="0" class="box" required placeholder="enter instruments price" name="price">
      <textarea name="details" class="box" required placeholder="enter instruments details" cols="30" rows="10"></textarea>
      <input type="file" accept="image/jpg, image/jpeg, image/png" required class="box" name="image">
      <input type="submit" value="add instruments" name="add_instruments" class="btn">
   </form>

</section>

<section class="show-instruments">

   <div class="box-container">

      <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `instruments`") or die('query failed');
         if(mysqli_num_rows($select_instruments) > 0){
            while($fetch_instruments = mysqli_fetch_assoc($select_instruments)){
      ?>
      <div class="box">
         <div class="price">$<?php echo $fetch_instruments['price']; ?>/-</div>
         <img class="image" src="uploaded_img/<?php echo $fetch_instruments['image']; ?>" alt="">
         <div class="name"><?php echo $fetch_instruments['name']; ?></div>
         <div class="details"><?php echo $fetch_instruments['details']; ?></div>
         <a href="admin_update_instruments.php?update=<?php echo $fetch_instruments['id']; ?>" class="option-btn">update</a>
         <a href="admin_instruments.php?delete=<?php echo $fetch_instruments['id']; ?>" class="delete-btn" onclick="return confirm('delete this instruments?');">delete</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no instruments added yet!</p>';
      }
      ?>
   </div>
   

</section>












<script src="js/admin_script.js"></script>

</body>
</html>