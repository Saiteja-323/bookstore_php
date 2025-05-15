<?php
include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_completed_orders.php');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>completed orders</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="css/admin_style.css">
</head>

<body>
  <?php include 'admin_header.php'; ?>

  <section class="orders">
    <h1 class="title">completed orders</h1>
    <div class="box-container">
      <?php
         $select_orders = mysqli_query($conn, "SELECT * FROM `orders` WHERE payment_status = 'completed'") or die('query failed');
         if(mysqli_num_rows($select_orders) > 0){
            while($fetch_orders = mysqli_fetch_assoc($select_orders)){
      ?>
      <div class="box">
        <p> user id : <span><?php echo $fetch_orders['user_id']; ?></span> </p>
        <p> placed on : <span><?php echo $fetch_orders['placed_on']; ?></span> </p>
        <p> name : <span><?php echo $fetch_orders['name']; ?></span> </p>
        <p> total price : <span>₹<?php echo $fetch_orders['total_price']; ?>/-</span> </p>
        <p> payment method : <span><?php echo $fetch_orders['method']; ?></span> </p>
        <div class="order-products">
          <h3>ordered products:</h3>
          <?php
               $products = explode(', ', $fetch_orders['total_products']);
               foreach($products as $product){
                  if(empty($product)) continue;
                  $product_name = trim(explode('(', $product)[0]);
                  $product_query = mysqli_query($conn, "SELECT * FROM `products` WHERE name = '$product_name'");
                  if(mysqli_num_rows($product_query) > 0){
                     $product_data = mysqli_fetch_assoc($product_query);
                     echo '<p>'.$product_data['name'].' ('.ucfirst($product_data['category']).')</p>';
                  }
               }
            ?>
        </div>
        <a href="admin_completed_orders.php?delete=<?php echo $fetch_orders['id']; ?>"
          onclick="return confirm('delete this order?');" class="delete-btn">delete</a>
      </div>
      <?php
            }
         }else{
            echo '<p class="empty">no completed orders!</p>';
         }
      ?>
    </div>
  </section>
  <script src="js/admin_script.js"></script>
</body>

</html>