<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit;
}

if (!isset($_GET['order_id'])) {
    echo "Invalid request.";
    exit;
}

$order_id = $_GET['order_id'];

// Fetch order details
$order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE id = '$order_id' AND user_id = '$user_id'") or die('Query failed');

if (mysqli_num_rows($order_query) == 0) {
    echo "Order not found.";
    exit;
}

$order = mysqli_fetch_assoc($order_query);

// HTML content for PDF
$html = '
<style>
    body { font-family: sans-serif; }
    .bill-header { text-align: center; margin-bottom: 20px; }
    .bill-section { margin: 10px 0; font-size: 14px; }
    .bill-footer { text-align: center; margin-top: 40px; font-size: 12px; color: #888; }
</style>

<div class="bill">
  <h2 class="bill-header">Order Invoice</h2>
  <div class="bill-section"><strong>Order ID:</strong> ' . $order['id'] . '</div>
  <div class="bill-section"><strong>Name:</strong> ' . $order['name'] . '</div>
  <div class="bill-section"><strong>Address:</strong> ' . $order['address'] . '</div>
  <div class="bill-section"><strong>Email:</strong> ' . $order['email'] . '</div>
  <div class="bill-section"><strong>Phone:</strong> ' . $order['number'] . '</div>
  <div class="bill-section"><strong>Payment Method:</strong> ' . $order['method'] . '</div>
  <div class="bill-section"><strong>Date:</strong> ' . $order['placed_on'] . '</div>
  <div class="bill-section"><strong>Items:</strong> ' . $order['total_products'] . '</div>
  <div class="bill-section"><strong>Total Price:</strong> ₹' . $order['total_price'] . '/-</div>
  <div class="bill-section"><strong>Status:</strong> ' . ucfirst($order['payment_status']) . '</div>
  <div class="bill-footer">Thank you for your order!</div>
</div>
';

// Setup Dompdf options
$options = new Options();
$options->set('defaultFont', 'DejaVu Sans'); // Unicode support
$dompdf = new Dompdf($options);

// Load HTML and render
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Preview in-browser instead of downloading
$dompdf->stream('Bill_Order_' . $order['id'] . '.pdf', ["Attachment" => false]);
exit;
