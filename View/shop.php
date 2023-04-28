<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['add_to_wishlist'])){

    $instruments_id = $_POST['instruments_id'];
    $instruments_name = $_POST['instruments_name'];
    $instruments_price = $_POST['instruments_price'];
    $instruments_image = $_POST['instruments_image'];

    $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$instruments_name' AND user_id = '$user_id'") or die('query failed');

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$instruments_name' AND user_id = '$user_id'") or die('query failed');

    if(mysqli_num_rows($check_wishlist_numbers) > 0){
        $message[] = 'already added to wishlist';
    }elseif(mysqli_num_rows($check_cart_numbers) > 0){
        $message[] = 'already added to cart';
    }else{
        mysqli_query($conn, "INSERT INTO `wishlist`(user_id, in_id, name, price, image) VALUES('$user_id', '$instruments_id', '$instruments_name', '$instruments_price', '$instruments_image')") or die('query failed');
        $message[] = 'instruments added to wishlist';
    }

}

if(isset($_POST['add_to_cart'])){

    $instruments_id = $_POST['instruments_id'];
    $instruments_name = $_POST['instruments_name'];
    $instruments_price = $_POST['instruments_price'];
    $instruments_image = $_POST['instruments_image'];
    $instruments_quantity = $_POST['instruments_quantity'];

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$instruments_name' AND user_id = '$user_id'") or die('query failed');

    if(mysqli_num_rows($check_cart_numbers) > 0){
        $message[] = 'already added to cart';
    }else{

        $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$instruments_name' AND user_id = '$user_id'") or die('query failed');

        if(mysqli_num_rows($check_wishlist_numbers) > 0){
            mysqli_query($conn, "DELETE FROM `wishlist` WHERE name = '$instruments_name' AND user_id = '$user_id'") or die('query failed');
        }

        mysqli_query($conn, "INSERT INTO `cart`(user_id, in_id, name, price, quantity, image) VALUES('$user_id', '$instruments_id', '$instruments_name', '$instruments_price', '$instruments_quantity', '$instruments_image')") or die('query failed');
        $message[] = 'instruments added to cart';
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shop</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>our shop</h3>
    <p> <a href="home.php">home</a> / shop </p>
</section>

<section class="instruments">

   <h1 class="title">latest products</h1>

   <div class="box-container">

      <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `instruments`") or die('query failed');
         if(mysqli_num_rows($select_instruments) > 0){
            while($fetch_instruments = mysqli_fetch_assoc($select_instruments)){
      ?>
      <form action="" method="POST" class="box">
         <a href="view_page.php?in_id=<?php echo $fetch_instruments['id']; ?>" class="fas fa-eye"></a>
         <div class="price">$<?php echo $fetch_products['price']; ?>/-</div>
         <img src="uploaded_img/<?php echo $fetch_instruments['image']; ?>" alt="" class="image">
         <div class="name"><?php echo $fetch_instruments['name']; ?></div>
         <input type="number" name="instruments_quantity" value="1" min="0" class="qty">
         <input type="hidden" name="instruments_id" value="<?php echo $fetch_instruments['id']; ?>">
         <input type="hidden" name="instruments_name" value="<?php echo $fetch_instruments['name']; ?>">
         <input type="hidden" name="instruments_price" value="<?php echo $fetch_instruments['price']; ?>">
         <input type="hidden" name="instruments_image" value="<?php echo $fetch_instruments['image']; ?>">
         <input type="submit" value="add to wishlist" name="add_to_wishlist" class="option-btn">
         <input type="submit" value="add to cart" name="add_to_cart" class="btn">
      </form>
      <?php
         }
      }else{
         echo '<p class="empty">no instruments added yet!</p>';
      }
      ?>

   </div>

</section>






<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>