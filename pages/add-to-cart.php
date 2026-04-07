<?php
require_once '../config/config.php';
checkLogin();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_id'])) {
    $user_id = $_SESSION['user_id'];
    $book_id = intval($_POST['book_id']);
    
    // Check if book exists
    $check_book = $conn->query("SELECT id, stock FROM books WHERE id=$book_id");
    if ($check_book->num_rows == 0) {
        header("Location: catalog.php?error=Buku tidak ditemukan");
        exit();
    }
    
    $book = $check_book->fetch_assoc();
    if ($book['stock'] <= 0) {
        header("Location: product-detail.php?id=$book_id&error=Stok tidak tersedia");
        exit();
    }
    
    // Check if already in cart
    $check_cart = $conn->query("SELECT id, quantity FROM cart WHERE user_id=$user_id AND book_id=$book_id");
    
    if ($check_cart->num_rows > 0) {
        // Update quantity
        $cart_item = $check_cart->fetch_assoc();
        $new_qty = $cart_item['quantity'] + 1;
        if ($new_qty > $book['stock']) {
            header("Location: product-detail.php?id=$book_id&error=Stok tidak cukup");
            exit();
        }
        $conn->query("UPDATE cart SET quantity=$new_qty WHERE user_id=$user_id AND book_id=$book_id");
    } else {
        // Add to cart
        $conn->query("INSERT INTO cart (user_id, book_id, quantity) VALUES ($user_id, $book_id, 1)");
    }
    
    header("Location: cart.php?success=Buku berhasil ditambahkan ke keranjang");
    exit();
} else {
    header("Location: catalog.php");
    exit();
}
?>
