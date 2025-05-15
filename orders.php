<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Orders</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
  <style>
    .status-pending {
      color: orange;
    }

    .status-completed {
      color: purple;
    }

    .btn-download {
      display: inline-block;
      margin-top: 10px;
      padding: 8px 14px;
      background-color: #007BFF;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
      transition: background-color 0.3s ease;
    }

    .btn-download:hover {
      background-color: #0056b3;
    }

    .order-products {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      margin-top: 10px;
    }

    .order-product {
      width: 150px;
      text-align: center;
    }

    .order-product img {
      width: 100%;
      border-radius: 10px;
    }

    .btn-review {
      display: inline-block;
      margin-top: 8px;
      padding: 6px 12px;
      background-color: green;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      font-size: 14px;
    }

    .btn-review:hover {
      background-color: darkgreen;
    }

    .review-disabled {
      display: inline-block;
      margin-top: 8px;
      font-size: 13px;
      color: gray;
    }
  </style>
</head>

<body>
  <?php include 'header.php'; ?>

  <div class="heading">
    <h3>Your Orders</h3>
    <p><a href="home.php">home</a> / orders</p>
  </div>

  <section class="placed-orders">
    <h1 class="title">Placed Orders</h1>
    <div class="box-container">
      <?php
      $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id' ORDER BY placed_on DESC") or die('query failed');
      if (mysqli_num_rows($order_query) > 0) {
          while ($fetch_orders = mysqli_fetch_assoc($order_query)) {
              $is_completed = strtolower($fetch_orders['payment_status']) === 'completed';
              ?>
              <div class="box">
                <p>Placed on: <span><?php echo $fetch_orders['placed_on']; ?></span></p>
                <p>Name: <span><?php echo $fetch_orders['name']; ?></span></p>
                <p>Number: <span><?php echo $fetch_orders['number']; ?></span></p>
                <p>Email: <span><?php echo $fetch_orders['email']; ?></span></p>
                <p>Address: <span><?php echo $fetch_orders['address']; ?></span></p>
                <p>Payment Method: <span><?php echo $fetch_orders['method']; ?></span></p>
                <p>Total Price: <span>₹<?php echo $fetch_orders['total_price']; ?>/-</span></p>
                <p>Payment Status:
                  <span class="status-<?php echo $fetch_orders['payment_status']; ?>">
                    <?php echo ucfirst($fetch_orders['payment_status']); ?>
                  </span>
                </p>

                <div class="order-products">
                  <?php
                  $ordered_items = explode(',', $fetch_orders['total_products']);
                  foreach ($ordered_items as $item) {
                      preg_match('/^(.*?)\s*\(\d+\)/', trim($item), $matches);
                      $book_name = isset($matches[1]) ? trim($matches[1]) : trim($item);

                      $product_query = mysqli_query($conn, "SELECT * FROM `products` WHERE name = '$book_name' LIMIT 1");
                      if (mysqli_num_rows($product_query) > 0) {
                          $product = mysqli_fetch_assoc($product_query);
                          ?>
                          <div class="order-product">
                              <img src="uploaded_img/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                              <?php if ($is_completed): ?>
                                <a href="write_review.php?product_id=<?php echo $product['id']; ?>" class="btn-review">Write Review</a>
                              <?php else: ?>
                                <div class="review-disabled">Review after completion</div>
                              <?php endif; ?>
                          </div>
                          <?php
                      }
                  }
                  ?>
                </div>

                <a href="download_bill.php?order_id=<?php echo $fetch_orders['id']; ?>" class="btn-download">
                  Download Invoice
                </a>
              </div>
              <?php
          }
      } else {
          echo '<p class="empty">No orders placed yet!</p>';
      }
      ?>
    </div>
  </section>

  <?php include 'footer.php'; ?>
  <script src="js/script.js"></script>
</body>

</html>
