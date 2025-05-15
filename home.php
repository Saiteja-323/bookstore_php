<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>home</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <?php include 'header.php'; ?>
  <section class="home">
    <div class="content">
      <h3>Hand Picked Book to your door.</h3>
      <p>Discover your next favorite book from our extensive collection. We offer books across all genres at competitive
        prices.</p>
      <a href="about.php" class="white-btn">discover more</a>
    </div>
  </section>
  <section class="about">
    <div class="flex">
      <div class="image">
        <img src="images/about-img.jpg" alt="">
      </div>
      <div class="content">
        <h3>about us</h3>
        <p>We are passionate about books and committed to providing the best reading experience. Our carefully curated
          collection includes bestsellers, classics, and hidden gems across all genres.</p>
        <a href="about.php" class="btn">read more</a>
      </div>
    </div>
  </section>
  <section class="home-contact">
    <div class="content">
      <h3>have any questions?</h3>
      <p>Our team is always ready to help you with any questions about our books, orders, or services.</p>
      <a href="contact.php" class="white-btn">contact us</a>
    </div>
  </section>
  <?php include 'footer.php'; ?>
  <script src="js/script.js"></script>
</body>

</html>