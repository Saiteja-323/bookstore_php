<?php
include 'config.php';
session_start();

if (isset($_POST['submit'])) {
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));

   // Admin login
   if ($email === 'admin@gmail.com' && $_POST['password'] === 'admin123') {
      $_SESSION['admin_name'] = 'Admin';
      $_SESSION['admin_email'] = 'admin@gmail.com';
      $_SESSION['admin_id'] = 0;
      header('location:admin_page.php');
      exit;
   }

   $select_users = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email' AND password = '$pass'") or die('query failed');

   if (mysqli_num_rows($select_users) > 0) {
      $row = mysqli_fetch_assoc($select_users);
      $_SESSION['user_name'] = $row['name'];
      $_SESSION['user_email'] = $row['email'];
      $_SESSION['user_id'] = $row['id'];
      header('location:home.php');
      exit;
   } else {
      $message[] = 'Incorrect email or password!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="css/style.css"> <!-- Make sure you have the same CSS -->
  <style>
    .welcome-message {
      color: #8e44ad;
      background:#f5f5f5;
      text-align: center;
      font-size: 24px;
      
    }
  </style>
</head>

<body>
<?php
if (isset($message)) {
  foreach ($message as $msg) {
    echo '
    <div class="message">
      <span>' . $msg . '</span>
      <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
    </div>';
  }
}
?>

  <div class="welcome-message">
   <h1> Welcome to Bookstore</h1>
  </div>

  <div class="form-container">
    <form action="" method="post">
      <h3>Login Now</h3>
      <input type="email" name="email" placeholder="Enter your email" required class="box" />
      <input type="password" name="password" placeholder="Enter your password" required class="box" />
      <input type="submit" name="submit" value="Login Now" class="btn" />
      <p>Don't have an account? <a href="register.php">Register now</a></p>
    </form>
  </div>
</body>
</html>
