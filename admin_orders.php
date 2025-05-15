<?php
include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

if(isset($_POST['update_order'])){
   $order_update_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   mysqli_query($conn, "UPDATE `orders` SET payment_status = '$update_payment' WHERE id = '$order_update_id'") or die('query failed');
   $message[] = '<span style="color:green">Payment status has been updated!</span>';
}

if(isset($_POST['update_product'])){
   $product_id = $_POST['product_id'];
   $update_name = $_POST['update_name'];
   $update_price = $_POST['update_price'];
   $update_category = $_POST['update_category'];
   
   mysqli_query($conn, "UPDATE `products` SET name = '$update_name', price = '$update_price', category = '$update_category' WHERE id = '$product_id'") or die('query failed');
   $message[] = '<span style="color:green">Product details updated successfully!</span>';
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_orders.php');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>orders</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="css/admin_style.css">
  <style>
  .product-details {
    margin-top: 1rem;
    padding: 1rem;
    background: var(--light-bg);
    border-radius: .5rem;
  }

  .product-item {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: var(--border);
  }

  .product-item img {
    width: 80px;
    height: auto;
    margin-right: 1rem;
  }

  .edit-form {
    display: none;
    margin-top: 1rem;
    padding: 1rem;
    background: var(--light-bg);
    border-radius: .5rem;
  }

  .category-label {
    display: inline-block;
    padding: 0.3rem 0.8rem;
    border-radius: 0.5rem;
    font-size: 1.4rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
  }

  .romance {
    background: #ffb6c1;
    color: #8b0000;
  }

  .action {
    background: #ffa07a;
    color: #8b0000;
  }

  .drama {
    background: #add8e6;
    color: #00008b;
  }

  .sci-fi {
    background: #9370db;
    color: #ffffff;
  }

  .comedy {
    background: #ffff00;
    color: #000000;
  }

  .horror {
    background: #8b0000;
    color: #ffffff;
  }

  .thriller {
    background: #a9a9a9;
    color: #000000;
  }

  .education {
    background: #90ee90;
    color: #006400;
  }
  </style>
</head>

<body>
  <?php include 'admin_header.php'; ?>

  <section class="orders">
    <h1 class="title">placed orders</h1>
    <div class="box-container">
      <?php
      $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
      if(mysqli_num_rows($select_orders) > 0){
         while($fetch_orders = mysqli_fetch_assoc($select_orders)){
            $products = explode(', ', $fetch_orders['total_products']);
      ?>
      <div class="box">
        <p>Order ID: <span><?php echo $fetch_orders['id']; ?></span></p>
        <p>User ID: <span><?php echo $fetch_orders['user_id']; ?></span></p>
        <p>Placed on: <span><?php echo $fetch_orders['placed_on']; ?></span></p>
        <p>Name: <span><?php echo $fetch_orders['name']; ?></span></p>
        <p>Email: <span><?php echo $fetch_orders['email']; ?></span></p>
        <p>Total Price: <span>₹<?php echo $fetch_orders['total_price']; ?>/-</span></p>
        <p>Payment Method: <span><?php echo $fetch_orders['method']; ?></span></p>

        <div class="product-details">
          <h3>Products in this order:</h3>
          <?php
            foreach($products as $product){
               if(!empty($product)){
                  $product_name = trim(explode('(', $product)[0]);
                  $product_query = mysqli_query($conn, "SELECT * FROM `products` WHERE name = '$product_name'") or die('query failed');
                  if(mysqli_num_rows($product_query) > 0){
                     $product_data = mysqli_fetch_assoc($product_query);
            ?>
          <div class="product-item">
            <img src="uploaded_img/<?php echo $product_data['image']; ?>" alt="">
            <div>
              <span class="category-label <?php echo $product_data['category']; ?>">
                <?php echo ucfirst($product_data['category']); ?>
              </span>
              <div class="name"><?php echo $product_data['name']; ?></div>
              <div class="price">₹<?php echo $product_data['price']; ?>/-</div>

              <button class="option-btn edit-btn" onclick="toggleEditForm(<?php echo $product_data['id']; ?>)">Edit
                Product</button>

              <div class="edit-form" id="edit-form-<?php echo $product_data['id']; ?>">
                <form action="" method="post">
                  <input type="hidden" name="product_id" value="<?php echo $product_data['id']; ?>">
                  <input type="text" name="update_name" value="<?php echo $product_data['name']; ?>" class="box">
                  <input type="number" name="update_price" value="<?php echo $product_data['price']; ?>" min="0"
                    class="box">
                  <select name="update_category" class="box">
                    <option value="romance" <?php echo $product_data['category'] == 'romance' ? 'selected' : ''; ?>>
                      Romance</option>
                    <option value="action" <?php echo $product_data['category'] == 'action' ? 'selected' : ''; ?>>Action
                    </option>
                    <option value="drama" <?php echo $product_data['category'] == 'drama' ? 'selected' : ''; ?>>Drama
                    </option>
                    <option value="sci-fi" <?php echo $product_data['category'] == 'sci-fi' ? 'selected' : ''; ?>>Sci-Fi
                    </option>
                    <option value="comedy" <?php echo $product_data['category'] == 'comedy' ? 'selected' : ''; ?>>Comedy
                    </option>
                    <option value="horror" <?php echo $product_data['category'] == 'horror' ? 'selected' : ''; ?>>Horror
                    </option>
                    <option value="thriller" <?php echo $product_data['category'] == 'thriller' ? 'selected' : ''; ?>>
                      Thriller</option>
                    <option value="education" <?php echo $product_data['category'] == 'education' ? 'selected' : ''; ?>>
                      Education</option>
                  </select>
                  <input type="submit" value="Update" name="update_product" class="btn">
                  <button type="button" class="option-btn"
                    onclick="toggleEditForm(<?php echo $product_data['id']; ?>)">Cancel</button>
                </form>
              </div>
            </div>
          </div>
          <?php
                  }
               }
            }
            ?>
        </div>

        <form action="" method="post">
          <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
          <select name="update_payment">
            <option value="" selected disabled><?php echo $fetch_orders['payment_status']; ?></option>
            <option value="pending">pending</option>
            <option value="completed">completed</option>
          </select>
          <input type="submit" value="update" name="update_order" class="option-btn">
          <a href="admin_orders.php?delete=<?php echo $fetch_orders['id']; ?>"
            onclick="return confirm('delete this order?');" class="delete-btn">delete</a>
        </form>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no orders placed yet!</p>';
      }
      ?>
    </div>
  </section>

  <script>
  function toggleEditForm(productId) {
    const form = document.getElementById('edit-form-' + productId);
    if (form.style.display === 'block') {
      form.style.display = 'none';
    } else {
      form.style.display = 'block';
    }
  }
  </script>

  <script src="js/admin_script.js"></script>
</body>

</html>