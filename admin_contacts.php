<?php
include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:login.php');
}
;

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `message` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_contacts.php');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>messages</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="css/admin_style.css">
</head>

<body>
  <?php include 'admin_header.php'; ?>
  <section class="messages">
    <h1 class="title">messages</h1>
    <div class="box-container"
      style="display: grid; grid-template-columns: repeat(auto-fit, minmax(30rem, 1fr)); gap: 2rem;">
      <?php
         $select_message = mysqli_query($conn, "SELECT * FROM `message` ORDER BY id ASC") or die('query failed');
         if (mysqli_num_rows($select_message) > 0) {
            while ($fetch_message = mysqli_fetch_assoc($select_message)) {
               ?>
      <div class="box"
        style="padding: 2rem; border: var(--border); border-radius: .5rem; background-color: var(--white); box-shadow: var(--box-shadow);">
        <p style="font-size: 1.8rem; margin-bottom: 1rem;">ID: <span><?php echo $fetch_message['id']; ?></span></p>
        <p style="font-size: 1.8rem; margin-bottom: 1rem;">User ID:
          <span><?php echo $fetch_message['user_id']; ?></span>
        </p>
        <p style="font-size: 1.8rem; margin-bottom: 1rem;">Name: <span><?php echo $fetch_message['name']; ?></span>
        </p>
        <p style="font-size: 1.8rem; margin-bottom: 1rem;">Number:
          <span><?php echo $fetch_message['number']; ?></span>
        </p>
        <p style="font-size: 1.8rem; margin-bottom: 1rem;">Email:
          <span><?php echo $fetch_message['email']; ?></span>
        </p>
        <p style="font-size: 1.8rem; margin-bottom: 1rem;">Message:
          <span><?php echo $fetch_message['message']; ?></span>
        </p>
        <a href="admin_contacts.php?delete=<?php echo $fetch_message['id']; ?>"
          onclick="return confirm('delete this message?');" class="delete-btn">delete message</a>
      </div>
      <?php
            }
            ;
         } else {
            echo '<p class="empty">you have no messages!</p>';
         }
         ?>
    </div>
  </section>
  <script src="js/admin_script.js"></script>
</body>

</html>