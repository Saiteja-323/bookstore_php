<?php
include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

if(isset($_POST['add_product'])){
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $price = $_POST['price'];
   $category = mysqli_real_escape_string($conn, $_POST['category']);
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$name'") or die('query failed');

   if(mysqli_num_rows($select_product_name) > 0){
      $message[] = '<div class="message error"><span>Product name already exists!</span><i class="fas fa-times" onclick="this.parentElement.remove();"></i></div>';
   }else{
      $add_product_query = mysqli_query($conn, "INSERT INTO `products`(name, price, category, image) VALUES('$name', '$price', '$category', '$image')") or die('query failed');

      if($add_product_query){
         if($image_size > 2000000){
            $message[] = '<div class="message error"><span>Image size is too large!</span><i class="fas fa-times" onclick="this.parentElement.remove();"></i></div>';
         }else{
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = '<div class="message success"><span>Product added successfully!</span><i class="fas fa-times" onclick="this.parentElement.remove();"></i></div>';
         }
      }else{
         $message[] = '<div class="message error"><span>Failed to add product!</span><i class="fas fa-times" onclick="this.parentElement.remove();"></i></div>';
      }
   }
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_image_query = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
   unlink('uploaded_img/'.$fetch_delete_image['image']);
   mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_products.php');
}

if(isset($_POST['update_product'])){
   $update_p_id = $_POST['update_p_id'];
   $update_name = $_POST['update_name'];
   $update_price = $_POST['update_price'];
   $update_category = $_POST['update_category'];

   mysqli_query($conn, "UPDATE `products` SET name = '$update_name', price = '$update_price', category = '$update_category' WHERE id = '$update_p_id'") or die('query failed');

   $update_image = $_FILES['update_image']['name'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_folder = 'uploaded_img/'.$update_image;
   $update_old_image = $_POST['update_old_image'];

   if(!empty($update_image)){
      if($update_image_size > 2000000){
         $message[] = '<div class="message error"><span>Image file size is too large!</span><i class="fas fa-times" onclick="this.parentElement.remove();"></i></div>';
      }else{
         mysqli_query($conn, "UPDATE `products` SET image = '$update_image' WHERE id = '$update_p_id'") or die('query failed');
         move_uploaded_file($update_image_tmp_name, $update_folder);
         unlink('uploaded_img/'.$update_old_image);
         $message[] = '<div class="message success"><span>Product updated successfully!</span><i class="fas fa-times" onclick="this.parentElement.remove();"></i></div>';
      }
   }

   header('location:admin_products.php');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>products</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="css/admin_style.css">
  <style>
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

  .message.success {
    background-color: #d4edda;
    color: #155724;
    border-color: #c3e6cb;
  }

  .message.error {
    background-color: #f8d7da;
    color: #721c24;
    border-color: #f5c6cb;
  }

  .show-products .box-container .box {
    position: relative;
  }
  </style>
</head>

<body>
  <?php include 'admin_header.php'; ?>

  <section class="add-products">
    <h1 class="title">shop products</h1>
    <form action="" method="post" enctype="multipart/form-data">
      <h3>add product</h3>
      <input type="text" name="name" class="box" placeholder="enter product name" required>
      <input type="number" min="0" name="price" class="box" placeholder="enter product price in ₹" required>
      <select name="category" class="box" required>
        <option value="" disabled selected>Select Category</option>
        <option value="romance">Romance</option>
        <option value="action">Action</option>
        <option value="drama">Drama</option>
        <option value="sci-fi">Sci-Fi</option>
        <option value="comedy">Comedy</option>
        <option value="horror">Horror</option>
        <option value="thriller">Thriller</option>
        <option value="education">Education</option>
      </select>
      <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
      <input type="submit" value="add product" name="add_product" class="btn">
    </form>
  </section>

  <section class="show-products">
    <div class="box-container">
      <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
      <div class="box">
        <div class="category-top <?php echo $fetch_products['category']; ?>">
          <?php echo ucfirst($fetch_products['category']); ?>
        </div>
        <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
        <div class="name"><?php echo $fetch_products['name']; ?></div>
        <div class="price">₹<?php echo $fetch_products['price']; ?>/-</div>
        <a href="admin_products.php?update=<?php echo $fetch_products['id']; ?>" class="option-btn">update</a>
        <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>" class="delete-btn"
          onclick="return confirm('delete this product?');">delete</a>
      </div>
      <?php
            }
         }else{
            echo '<p class="empty">no products added yet!</p>';
         }
      ?>
    </div>
  </section>

  <section class="edit-product-form">
    <?php
      if(isset($_GET['update'])){
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$update_id'") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
    <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
      <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
      <img src="uploaded_img/<?php echo $fetch_update['image']; ?>" alt="">
      <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required
        placeholder="enter product name">
      <input type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" min="0" class="box"
        required placeholder="enter product price in ₹">
      <select name="update_category" class="box" required>
        <option value="romance" <?php echo ($fetch_update['category'] == 'romance') ? 'selected' : ''; ?>>Romance
        </option>
        <option value="action" <?php echo ($fetch_update['category'] == 'action') ? 'selected' : ''; ?>>Action</option>
        <option value="drama" <?php echo ($fetch_update['category'] == 'drama') ? 'selected' : ''; ?>>Drama</option>
        <option value="sci-fi" <?php echo ($fetch_update['category'] == 'sci-fi') ? 'selected' : ''; ?>>Sci-Fi</option>
        <option value="comedy" <?php echo ($fetch_update['category'] == 'comedy') ? 'selected' : ''; ?>>Comedy</option>
        <option value="horror" <?php echo ($fetch_update['category'] == 'horror') ? 'selected' : ''; ?>>Horror</option>
        <option value="thriller" <?php echo ($fetch_update['category'] == 'thriller') ? 'selected' : ''; ?>>Thriller
        </option>
        <option value="education" <?php echo ($fetch_update['category'] == 'education') ? 'selected' : ''; ?>>Education
        </option>
      </select>
      <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
      <input type="submit" value="update" name="update_product" class="btn">
      <input type="reset" value="cancel" id="close-update" class="option-btn">
    </form>
    <?php
            }
         }
      }else{
         echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
      }
   ?>
  </section>

  <script src="js/admin_script.js"></script>
</body>

</html>