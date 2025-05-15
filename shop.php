<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

$category_filter = isset($_GET['category']) ? $_GET['category'] : '';

if (isset($_POST['add_to_cart'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    if (mysqli_num_rows($check_cart_numbers) > 0) {
        $message[] = 'already added to cart!';
    } else {
        mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
        $message[] = 'product added to cart!';
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
  <style>
  .category-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
    margin-bottom: 2rem;
  }

  .category-btn {
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    background-color: var(--light-bg);
    color: var(--black);
    font-size: 1.6rem;
    cursor: pointer;
    transition: all 0.2s linear;
  }

  .category-btn:hover,
  .category-btn.active {
    background-color: var(--purple);
    color: var(--white);
  }

  .category-top {
    position: absolute;
    top: 1rem;
    right: 1rem;
    padding: 0.3rem 0.8rem;
    border-radius: 0.5rem;
    font-size: 1.4rem;
    font-weight: bold;
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
    background: #e6e6fa;
    color: #483d8b;
  }

  .sci-fi {
    background: #b0e0e6;
    color: #00008b;
  }

  .comedy {
    background: #fffacd;
    color: #8b4513;
  }

  .horror {
    background: #d3d3d3;
    color: #000000;
  }

  .thriller {
    background: #d8bfd8;
    color: #4b0082;
  }

  .education {
    background: #98fb98;
    color: #006400;
  }

  .uncategorized {
    background: #ccc;
    color: #333;
  }

  .products .box-container .box {
    position: relative;
  }
  </style>
</head>

<body>
  <?php include 'header.php'; ?>

  <div class="heading">
    <h3>our shop</h3>
    <p> <a href="home.php">home</a> / shop </p>
  </div>

  <section class="products">
    <h1 class="title">our collection</h1>
    <div class="category-buttons">
      <a href="shop.php" class="category-btn <?php echo empty($category_filter) ? 'active' : ''; ?>">All</a>
      <a href="shop.php?category=romance"
        class="category-btn <?php echo $category_filter == 'romance' ? 'active' : ''; ?>">Romance</a>
      <a href="shop.php?category=action"
        class="category-btn <?php echo $category_filter == 'action' ? 'active' : ''; ?>">Action</a>
      <a href="shop.php?category=drama"
        class="category-btn <?php echo $category_filter == 'drama' ? 'active' : ''; ?>">Drama</a>
      <a href="shop.php?category=sci-fi"
        class="category-btn <?php echo $category_filter == 'sci-fi' ? 'active' : ''; ?>">Sci-Fi</a>
      <a href="shop.php?category=comedy"
        class="category-btn <?php echo $category_filter == 'comedy' ? 'active' : ''; ?>">Comedy</a>
      <a href="shop.php?category=horror"
        class="category-btn <?php echo $category_filter == 'horror' ? 'active' : ''; ?>">Horror</a>
      <a href="shop.php?category=thriller"
        class="category-btn <?php echo $category_filter == 'thriller' ? 'active' : ''; ?>">Thriller</a>
      <a href="shop.php?category=education"
        class="category-btn <?php echo $category_filter == 'education' ? 'active' : ''; ?>">Education</a>
    </div>

    <div class="box-container">
      <?php  
        $query = "SELECT * FROM `products`";
        if (!empty($category_filter)) {
            $query .= " WHERE category = '$category_filter'";
        }
        $select_products = mysqli_query($conn, $query) or die('query failed');
        if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                $category = isset($fetch_products['category']) ? $fetch_products['category'] : 'uncategorized';
      ?>
      <form action="" method="post" class="box">
        <div class="category-top <?php echo htmlspecialchars($category); ?>">
          <?php echo ucfirst(htmlspecialchars($category)); ?>
        </div>
        <img class="image" src="uploaded_img/<?php echo htmlspecialchars($fetch_products['image']); ?>" alt="">
        <div class="name">
          <a href="book_reviews.php?book_id=<?php echo $fetch_products['id']; ?>">
            <?php echo htmlspecialchars($fetch_products['name']); ?>
          </a>
        </div>
        <div class="price">₹<?php echo htmlspecialchars($fetch_products['price']); ?>/-</div>
        <input type="number" min="1" name="product_quantity" value="1" class="qty">
        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($fetch_products['name']); ?>">
        <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($fetch_products['price']); ?>">
        <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($fetch_products['image']); ?>">
        <input type="submit" value="add to cart" name="add_to_cart" class="btn">
      </form>
      <?php
            }
        } else {
            echo '<p class="empty">no products added yet!</p>';
        }
      ?>
    </div>
  </section>

  <?php include 'footer.php'; ?>
  <script src="js/script.js"></script>
</body>

</html>