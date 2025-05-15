<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('location:login.php');
    exit;
}

// Fetch reviews with user name, rating, and associated book name
$review_query = $conn->query("
    SELECT r.review_text, r.image AS review_image, r.rating, u.name AS user_name, p.name AS product_name
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    JOIN products p ON r.book_id = p.id  -- Joining on book_id
    ORDER BY r.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>About</title>
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
   <h3>about us</h3>
   <p><a href="home.php">home</a> / about</p>
</div>

<section class="about">
   <div class="flex">
      <div class="image">
         <img src="images/about-img.jpg" alt="">
      </div>
      <div class="content">
         <h3>why choose us?</h3>
         <p>We are committed to delivering the best service to our customers...</p>
         <a href="contact.php" class="btn">contact us</a>
      </div>
   </div>
</section>

<section class="reviews">
   <h1 class="title">client's reviews</h1>
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
                        $rating = (int)$row['rating'];
                        $half_star = ($row['rating'] - $rating >= 0.5);
                        for ($i = 0; $i < $rating; $i++) echo '<i class="fas fa-star"></i>';
                        if ($half_star) echo '<i class="fas fa-star-half-alt"></i>';
                        for ($i = $rating + $half_star; $i < 5; $i++) echo '<i class="far fa-star"></i>';
                     ?>
                  </div>
                  <h3><?php echo htmlspecialchars($row['user_name']); ?></h3>
                  <div class="product-name">Reviewed on: <strong><?php echo htmlspecialchars($row['product_name']); ?></strong></div>
               </div>
            </div>
         <?php endwhile; ?>
      <?php else: ?>
         <p>No reviews yet. Be the first to leave one!</p>
      <?php endif; ?>
   </div>
</section>

<?php include 'footer.php'; ?>
</body>
</html>
