<?php
include 'config.php';
session_start();

if (!isset($_GET['book_id'])) {
    header('location:shop.php'); // Redirect if no book ID is passed
    exit;
}

$book_id = $_GET['book_id'];

// Fetch the product details
$product_query = $conn->query("SELECT * FROM `products` WHERE id = '$book_id'");
$product = $product_query->fetch_assoc();

// Fetch the reviews for this book
$review_query = $conn->query("
    SELECT r.review_text, r.image AS review_image, r.rating, u.name AS user_name
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    WHERE r.book_id = '$book_id'
    ORDER BY r.created_at DESC
");

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reviews for <?php echo htmlspecialchars($product['name']); ?></title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    .reviews .box {
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 10px;
      text-align: center;
      margin-bottom: 20px;
      background-color: #fff;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .reviews .box p {
      font-size: 15px;
      margin: 10px 0;
      color: #333;
    }

    .reviews .box .review-img {
      max-width: 100px;
      border-radius: 8px;
      margin-top: 10px;
    }

    .review-footer {
      margin-top: 15px;
    }

    .review-footer .stars {
      color: #f39c12;
      font-size: 16px;
      margin-bottom: 5px;
    }

    .review-footer h3 {
      font-size: 16px;
      color: #333;
      margin: 5px 0 0;
    }

    .review-footer .product-name {
      font-size: 14px;
      color: #777;
      margin-top: 3px;
    }
  </style>
</head>
<body>

  <?php include 'header.php'; ?>

  <div class="heading">
    <h3>Reviews for <?php echo htmlspecialchars($product['name']); ?></h3>
    <p><a href="shop.php">Back to Shop</a></p>
  </div>

  <section class="reviews">
    <h1 class="title">Client's Reviews</h1>
    <div class="box-container">
      <?php if ($review_query->num_rows > 0): ?>
        <?php while($row = $review_query->fetch_assoc()): ?>
          <div class="box">
            <p><?php echo htmlspecialchars($row['review_text']); ?></p>

            <?php if (!empty($row['review_image'])): ?>
              <img src="review_images/<?php echo htmlspecialchars($row['review_image']); ?>" alt="Review Image" class="review-img">
            <?php endif; ?>

            <div class="review-footer">
              <div class="stars">
                <?php
                  $rating = (int)$row['rating']; // Ensure rating is an integer
                  $half_star = ($row['rating'] - $rating >= 0.5);

                  // Display full stars
                  for ($i = 0; $i < $rating; $i++) {
                      echo '<i class="fas fa-star"></i>';
                  }

                  // Display half star if needed
                  if ($half_star) {
                      echo '<i class="fas fa-star-half-alt"></i>';
                  }

                  // Display empty stars
                  for ($i = $rating + $half_star; $i < 5; $i++) {
                      echo '<i class="far fa-star"></i>';
                  }
                ?>
              </div>
              <h3><?php echo htmlspecialchars($row['user_name']); ?></h3>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No reviews yet for this book. Be the first to leave one!</p>
      <?php endif; ?>
    </div>
  </section>

  <?php include 'footer.php'; ?>

</body>
</html>
