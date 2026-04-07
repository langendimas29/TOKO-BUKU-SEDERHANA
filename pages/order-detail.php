<?php
require_once '../config/config.php';
checkLogin();

$page_title = 'Detail Pesanan';

if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit();
}

$order_id = intval($_GET['id']);
$order = $conn->query("
    SELECT o.* 
    FROM orders o 
    WHERE o.id=$order_id AND o.user_id=" . $_SESSION['user_id']
)->fetch_assoc();

if (!$order) {
    header("Location: orders.php");
    exit();
}

// Get order items
$order_items = $conn->query("
    SELECT oi.*, b.title, b.image_url 
    FROM order_items oi 
    JOIN books b ON oi.book_id = b.id 
    WHERE oi.order_id=$order_id
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - TokoBuku</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <?php require_once '../includes/header.php'; ?>

    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center space-x-2 text-gray-600">
                <a href="home.php" class="hover:text-green-600"><i class="fas fa-home"></i></a>
                <span>/</span>
                <a href="orders.php" class="hover:text-green-600">Pesanan Saya</a>
                <span>/</span>
                <span class="text-gray-800 font-semibold">Detail Pesanan</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto max-w-4xl px-4 py-12">
        <div class="max-w-4xl">
            <!-- Order Header -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
                <div class="flex justify-between items-start mb-6 pb-6 border-b">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">
                            <i class="fas fa-receipt"></i> <?php echo htmlspecialchars($order['order_number']); ?>
                        </h1>
                        <p class="text-gray-600 text-sm mt-1">
                            <?php echo date('d F Y H:i', strtotime($order['created_at'])); ?>
                        </p>
                    </div>
                    <?php 
                    $status_colors = [
                        'pending' => ['bg' => 'yellow-100', 'text' => 'yellow-800'],
                        'paid' => ['bg' => 'green-100', 'text' => 'green-800'],
                        'shipped' => ['bg' => 'blue-100', 'text' => 'blue-800'],
                        'delivered' => ['bg' => 'green-100', 'text' => 'green-800'],
                        'cancelled' => ['bg' => 'red-100', 'text' => 'red-800']
                    ];
                    $colors = $status_colors[$order['status']] ?? $status_colors['pending'];
                    ?>
                    <span class="bg-{$colors['bg']} text-{$colors['text']} px-4 py-2 rounded-full text-sm font-semibold">
                        <?php echo ucfirst($order['status']); ?>
                    </span>
                </div>

                <!-- Order Status Info -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-gray-600 text-sm">Total Pembayaran</p>
                        <p class="font-bold text-2xl text-gray-800">Rp <?php echo number_format($order['total_price'], 0, ',', '.'); ?></p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Status Pembayaran</p>
                        <p class="font-bold text-gray-800">
                            <?php echo $order['status'] == 'paid' || $order['status'] == 'shipped' || $order['status'] == 'delivered' ? '<span class="text-green-600"><i class="fas fa-check-circle"></i> Sudah Bayar</span>' : '<span class="text-red-600"><i class="fas fa-clock"></i> Menunggu Bayar</span>'; ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Daftar Barang</h2>
                <div class="divide-y">
                    <?php
                    $subtotal = 0;
                    if ($order_items->num_rows > 0) {
                        while ($item = $order_items->fetch_assoc()) {
                            $subtotal += $item['price'] * $item['quantity'];
                            echo "<div class='py-4 flex gap-4'>
                                <div class='w-16 h-20 bg-gray-200 rounded flex-shrink-0 flex items-center justify-center'>
                                    " . (file_exists("../public/images/{$item['image_url']}") ? 
                                        "<img src='../public/images/{$item['image_url']}' alt='{$item['title']}' class='w-full h-full object-cover'>" :
                                        "<i class='fas fa-book text-2xl'></i>"
                                    ) . "
                                </div>
                                <div class='flex-1'>
                                    <h3 class='font-bold text-gray-800'>{$item['title']}</h3>
                                    <p class='text-gray-600 text-sm'>Qty: {$item['quantity']} x Rp " . number_format($item['price'], 0, ',', '.') . "</p>
                                </div>
                                <div class='text-right'>
                                    <p class='font-bold text-gray-800'>Rp " . number_format($item['price'] * $item['quantity'], 0, ',', '.') . "</p>
                                </div>
                            </div>";
                        }
                    }
                    ?>
                </div>

                <!-- Price Summary -->
                <div class="mt-6 pt-6 border-t space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-semibold">Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Biaya Pengiriman</span>
                        <span class="font-semibold">Rp <?php echo number_format($order['total_price'] - $subtotal, 0, ',', '.'); ?></span>
                    </div>
                    <div class="flex justify-between border-t pt-2 text-lg">
                        <span class="font-bold text-gray-800">Total</span>
                        <span class="font-bold text-green-600">Rp <?php echo number_format($order['total_price'], 0, ',', '.'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="orders.php" class="block text-center bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition font-semibold">
                    <i class="fas fa-arrow-left"></i> Kembali ke Pesanan
                </a>
                <a href="contact.php" class="block text-center bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition font-semibold">
                    <i class="fas fa-envelope"></i> Hubungi Support
                </a>
            </div>
        </div>
    </div>

    <?php require_once '../includes/footer.php'; ?>
</body>
</html>
