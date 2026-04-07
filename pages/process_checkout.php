<?php
require_once '../config/config.php';
checkLogin();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: checkout.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$full_name = escape($_POST['full_name']);
$phone = escape($_POST['phone']);
$email = escape($_POST['email']);
$address = escape($_POST['address']);
$total_price = floatval($_POST['total_price']);

// Update user address
$conn->query("UPDATE users SET full_name='$full_name', phone='$phone', address='$address' WHERE id=$user_id");

// Get cart items
$cart_items = $conn->query("
    SELECT c.*, b.title, b.price
    FROM cart c
    JOIN books b ON c.book_id = b.id
    WHERE c.user_id = $user_id
");

// Create order
$order_number = 'ORD-' . $user_id . '-' . time();
$conn->query("INSERT INTO orders (user_id, order_number, total_price, payment_status, status) 
             VALUES ($user_id, '$order_number', $total_price, 'unpaid', 'pending')");

$order_id = $conn->insert_id;

// Add order items
while ($item = $cart_items->fetch_assoc()) {
    $conn->query("INSERT INTO order_items (order_id, book_id, quantity, price) 
                 VALUES ($order_id, {$item['book_id']}, {$item['quantity']}, {$item['price']})");
    
    // Reduce stock
    $conn->query("UPDATE books SET stock = stock - {$item['quantity']} WHERE id = {$item['book_id']}");
}

// Clear cart
$conn->query("DELETE FROM cart WHERE user_id=$user_id");

// Redirect to payment page
header("Location: payment.php?order_id=$order_id&order_number=$order_number");
exit();
?>
