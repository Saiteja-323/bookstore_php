<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
$product_id = $_POST['product_id'] ?? null;
$review_text = $_POST['review_text'] ?? '';
$rating = $_POST['rating'] ?? null;

if (!$user_id || !$product_id || empty($review_text) || !$rating) {
    die("Missing required fields.");
}

$review_text = mysqli_real_escape_string($conn, $review_text);
$image_name = null;

// Handle optional review image upload
if (!empty($_FILES['review_image']['name'])) {
    $image_tmp = $_FILES['review_image']['tmp_name'];
    $image_ext = pathinfo($_FILES['review_image']['name'], PATHINFO_EXTENSION);
    $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];

    if (in_array(strtolower($image_ext), $allowed_ext)) {
        $image_name = 'review_' . uniqid() . '.' . $image_ext;
        $upload_dir = 'review_images/';
        $image_path = $upload_dir . $image_name;

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (!move_uploaded_file($image_tmp, $image_path)) {
            die("Failed to upload image.");
        }
    } else {
        die("Invalid image format. Allowed: jpg, jpeg, png, webp.");
    }
}

// Insert review into database
$stmt = $conn->prepare("INSERT INTO reviews (book_id, user_id, review_text, image, rating, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("iissi", $product_id, $user_id, $review_text, $image_name, $rating);
$stmt->execute();
$stmt->close();

// Redirect after successful submission
header("Location: home.php");
exit;
?>
