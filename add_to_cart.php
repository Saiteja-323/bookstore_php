<?php
include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_GET['id'])){
   $product_id = $_GET['id'];
   $select_product = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$product_id'") or die('query failed');

   if(mysqli_num_rows($select_product) > 0){
      $fetch_product = mysqli_fetch_assoc($select_product);
      
      $check_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id' AND name = '{$fetch_product['name']}'") or die('query failed');
      
      if(mysqli_num_rows($check_cart) > 0){
         $message[] = 'Product already in cart!';
      }else{
         mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '{$fetch_product['name']}', '{$fetch_product['price']}', 1, '{$fetch_product['image']}')") or die('query failed');
         $message[] = 'Product added to cart!';
      }
   }else{
      $message[] = 'Product not found!';
   }
}

header('location:shop.php');
?>