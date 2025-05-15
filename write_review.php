<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('location:login.php');
    exit;
}

if (!isset($_GET['product_id']) || !is_numeric($_GET['product_id'])) {
    echo "Invalid product ID.";
    exit;
}

$product_id = intval($_GET['product_id']);
$product_stmt = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
$product_stmt->bind_param("i", $product_id);
$product_stmt->execute();
$product_result = $product_stmt->get_result();

if ($product_result->num_rows == 0) {
    echo "Product not found.";
    exit;
}
$product = $product_result->fetch_assoc();

// Check if user already wrote a review for this book
$review_check_stmt = $conn->prepare("SELECT * FROM `reviews` WHERE book_id = ? AND user_id = ?");
$review_check_stmt->bind_param("ii", $product_id, $user_id);
$review_check_stmt->execute();
$review_check_result = $review_check_stmt->get_result();
$already_reviewed = ($review_check_result->num_rows > 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Write Review</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .review-form {
            max-width: 600px;
            margin: 40px auto;
            padding: 25px;
            border: 1px solid #ccc;
            border-radius: 12px;
            background: #f9f9f9;
            text-align: center;
        }

        .review-form h2 {
            margin-bottom: 15px;
        }

        .review-form img {
            display: block;
            margin: 0 auto 15px;
            max-width: 150px;
            border-radius: 10px;
        }

        .review-form textarea,
        .review-form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .review-form input[type="file"] {
            margin-bottom: 15px;
        }

        .review-form button {
            padding: 10px 20px;
            background-color: #27ae60;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .review-form button:hover {
            background-color: #219150;
        }

        .already-msg {
            font-size: 18px;
            color: #e74c3c;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="review-form">
    <h2>Review for: <?php echo htmlspecialchars($product['name']); ?></h2>
    <img src="uploaded_img/<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image">

    <?php if ($already_reviewed): ?>
        <p class="already-msg">You have already submitted a review for this book.</p>
    <?php else: ?>
        <form action="submit-review.php" method="POST" enctype="multipart/form-data">
            <textarea name="review_text" rows="5" placeholder="Write your review here..." required></textarea>
            
            <label for="rating">Rating (1-5):</label>
            <select name="rating" required>
                <option value="">Select rating</option>
                <option value="5">★★★★★ (5)</option>
                <option value="4">★★★★☆ (4)</option>
                <option value="3">★★★☆☆ (3)</option>
                <option value="2">★★☆☆☆ (2)</option>
                <option value="1">★☆☆☆☆ (1)</option>
            </select>

            <label>Upload Review Image:</label>
            <input type="file" name="review_image" accept=".jpg,.jpeg,.png,.webp">

            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
            <button type="submit">Submit Review</button>
        </form>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
