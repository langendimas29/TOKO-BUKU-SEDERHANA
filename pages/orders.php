<?php
require_once '../config/config.php';
checkLogin();

$page_title = 'Pesanan Saya';
$user_id = $_SESSION['user_id'];

$orders = $conn->query("
    SELECT o.* 
    FROM orders o 
    WHERE o.user_id = $user_id 
    ORDER BY o.created_at DESC
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
                <span class="text-gray-800 font-semibold">Pesanan Saya</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto max-w-4xl px-4 py-12">
        <div class="max-w-4xl">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">
                <i class="fas fa-shopping-bag"></i> Pesanan Saya
            </h1>

            <?php if ($orders->num_rows > 0): ?>
            <div class="space-y-4">
                <?php
                while ($order = $orders->fetch_assoc()) {
                    $status_colors = [
                        'pending' => ['bg' => 'yellow-100', 'text' => 'yellow-800', 'icon' => 'fa-clock'],
                        'paid' => ['bg' => 'green-100', 'text' => 'green-800', 'icon' => 'fa-check-circle'],
                        'shipped' => ['bg' => 'blue-100', 'text' => 'blue-800', 'icon' => 'fa-truck'],
                        'delivered' => ['bg' => 'green-100', 'text' => 'green-800', 'icon' => 'fa-box'],
                        'cancelled' => ['bg' => 'red-100', 'text' => 'red-800', 'icon' => 'fa-times-circle']
                    ];
                    
                    $colors = $status_colors[$order['status']] ?? $status_colors['pending'];
                    
                    // Get order items count
                    $items_count = $conn->query("SELECT COUNT(*) as count FROM order_items WHERE order_id={$order['id']}")->fetch_assoc()['count'];
                    
                    echo "<div class='bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition'>
                        <div class='flex flex-col md:flex-row justify-between items-start md:items-center gap-4'>
                            <div class='flex-1'>
                                <h3 class='text-lg font-bold text-gray-800'>
                                    <i class='fas fa-receipt'></i> {$order['order_number']}
                                </h3>
                                <p class='text-gray-600 text-sm mt-1'>" . date('d F Y H:i', strtotime($order['created_at'])) . "</p>
                                <p class='text-gray-600 text-sm'>Total: <strong>Rp " . number_format($order['total_price'], 0, ',', '.') . "</strong></p>
                                <p class='text-gray-600 text-sm'>Items: <strong>$items_count</strong></p>
                            </div>
                            
                            <div class='flex flex-col gap-2'>
                                <span class='bg-{$colors['bg']} text-{$colors['text']} px-4 py-2 rounded-full text-sm font-semibold text-center whitespace-nowrap'>
                                    <i class='fas {$colors['icon']}'></i> " . ucfirst($order['status']) . "
                                </span>
                                <span class='bg-" . ($order['status'] == 'paid' || $order['status'] == 'shipped' || $order['status'] == 'delivered' ? 'green' : 'red') . "-100 text-" . ($order['status'] == 'paid' || $order['status'] == 'shipped' || $order['status'] == 'delivered' ? 'green' : 'red') . "-800 px-4 py-2 rounded-full text-sm font-semibold text-center'>
                                    <i class='fas fa-" . ($order['status'] == 'paid' || $order['status'] == 'shipped' || $order['status'] == 'delivered' ? 'check-circle' : 'exclamation-circle') . "'></i> " . ($order['status'] == 'paid' || $order['status'] == 'shipped' || $order['status'] == 'delivered' ? 'Sudah Bayar' : 'Belum Bayar') . "
                                </span>
                            </div>
                            
                            <a href='order-detail.php?id={$order['id']}' class='bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition font-semibold'>
                                <i class='fas fa-arrow-right'></i> Lihat Detail
                            </a>
                        </div>
                    </div>";
                }
                ?>
            </div>
            <?php else: ?>
            <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                <i class="fas fa-shopping-bag text-6xl text-gray-300 mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Belum Ada Pesanan</h2>
                <p class="text-gray-600 mb-6">Anda belum melakukan pembelian apapun</p>
                <a href="catalog.php" class="inline-block bg-gradient-to-r from-green-600 to-green-600 text-white px-8 py-3 rounded-lg font-bold hover:shadow-lg transition">
                    <i class="fas fa-shopping-bag"></i> Mulai Belanja
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php require_once '../includes/footer.php'; ?>
</body>
</html>
