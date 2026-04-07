<?php
require_once '../config/config.php';
checkLogin();

$page_title = 'Pembayaran';
$order_id = intval($_GET['order_id']);
$order_number = isset($_GET['order_number']) ? escape($_GET['order_number']) : '';

$order = $conn->query("
    SELECT o.*, u.full_name, u.email, u.phone, u.address 
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    WHERE o.id=$order_id AND o.user_id=" . $_SESSION['user_id']
)->fetch_assoc();

if (!$order) {
    header("Location: home.php");
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
                <span class="text-gray-800 font-semibold">Pembayaran</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <!-- Payment Status -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-credit-card text-3xl text-blue-600"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">Menunggu Pembayaran</h2>
                    <p class="text-gray-600 mt-2">Pesanan Anda akan diproses setelah pembayaran diterima</p>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-blue-900 mb-2"><i class="fas fa-info-circle"></i> Informasi Pembayaran</h4>
                    <p class="text-blue-800 text-sm">
                        Pembayaran dilakukan sebelum barang dikirim (COD - Cash on Delivery). 
                        Silakan transfer uang ke nomor rekening yang akan diberikan kurir saat mengantarkan barang.
                    </p>
                </div>

                <!-- Order Details -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Detail Pesanan</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm mb-6">
                        <div>
                            <p class="text-gray-600">Nomor Pesanan</p>
                            <p class="font-bold text-gray-800"><?php echo htmlspecialchars($order['order_number']); ?></p>
                        </div>
                        <div>
                            <p class="text-gray-600">Tanggal</p>
                            <p class="font-bold text-gray-800"><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>
                        </div>
                        <div>
                            <p class="text-gray-600">Status Pembayaran</p>
                            <p class="font-bold text-red-600"><i class="fas fa-clock"></i> Menunggu Bayar</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Total Bayar</p>
                            <p class="font-bold text-gray-800 text-lg">Rp <?php echo number_format($order['total_price'], 0, ',', '.'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Daftar Barang</h3>
                <div class="divide-y">
                    <?php
                    while ($item = $order_items->fetch_assoc()) {
                        echo "<div class='py-4 flex justify-between items-start'>
                            <div>
                                <h4 class='font-bold text-gray-800'>{$item['title']}</h4>
                                <p class='text-sm text-gray-600'>Qty: {$item['quantity']}</p>
                            </div>
                            <span class='font-bold text-gray-800'>Rp " . number_format($item['price'] * $item['quantity'], 0, ',', '.') . "</span>
                        </div>";
                    }
                    ?>
                </div>
            </div>

            <!-- Shipping Info -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-truck"></i> Alamat Pengiriman</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="font-bold text-gray-800"><?php echo htmlspecialchars($order['full_name']); ?></p>
                    <p class="text-gray-600 text-sm mt-1"><?php echo htmlspecialchars($order['address']); ?></p>
                    <p class="text-gray-600 text-sm">Telepon: <?php echo htmlspecialchars($order['phone']); ?></p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <a href="orders.php" class="block text-center bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition font-semibold">
                    <i class="fas fa-list"></i> Lihat Pesanan Saya
                </a>
                <a href="home.php" class="block text-center bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition font-semibold">
                    <i class="fas fa-home"></i> Kembali ke Home
                </a>
            </div>

            <!-- Important Notice -->
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <h4 class="font-bold text-yellow-800 mb-2"><i class="fas fa-exclamation-triangle"></i> Penting</h4>
                <ul class="text-yellow-800 text-sm space-y-1">
                    <li>• Kurir akan menghubungi Anda untuk konfirmasi pengiriman</li>
                    <li>• Pastikan nomor telepon aktif untuk menerima panggilan kurir</li>
                    <li>• Pembayaran dilakukan saat barang tiba (COD)</li>
                    <li>• Jika ada pertanyaan, hubungi customer service kami</li>
                </ul>
            </div>
        </div>
    </div>

    <?php require_once '../includes/footer.php'; ?>
</body>
</html>
