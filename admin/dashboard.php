<?php
require_once '../config/config.php';
checkAdmin();

$page_title = 'Admin Dashboard';

// Get statistics
$users_count = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='user'")->fetch_assoc()['count'];
$books_count = $conn->query("SELECT COUNT(*) as count FROM books")->fetch_assoc()['count'];
$categories_count = $conn->query("SELECT COUNT(*) as count FROM categories")->fetch_assoc()['count'];
$orders_count = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
$unread_messages = $conn->query("SELECT COUNT(*) as count FROM messages WHERE status='unread'")->fetch_assoc()['count'];
$total_revenue = $conn->query("SELECT SUM(total_price) as total FROM orders WHERE payment_status='paid'")->fetch_assoc()['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - TokoBuku</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        }
        .stat-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="sidebar text-white w-64 shadow-lg">
            <div class="p-6 border-b border-white border-opacity-20">
                <h1 class="text-2xl font-bold flex items-center space-x-2">
                    <i class="fas fa-book"></i>
                    <span>TokoBuku</span>
                </h1>
                <p class="text-sm text-gray-200 mt-1">Admin Panel</p>
            </div>

            <nav class="p-6 space-y-2">
                <a href="dashboard.php" class="block px-4 py-3 rounded-lg bg-white bg-opacity-20 font-semibold">
                    <i class="fas fa-tachometer-alt w-5"></i> Dashboard
                </a>
                <a href="categories.php" class="block px-4 py-3 rounded-lg hover:bg-white hover:bg-opacity-20 transition">
                    <i class="fas fa-list w-5"></i> Kelola Kategori
                </a>
                <a href="books.php" class="block px-4 py-3 rounded-lg hover:bg-white hover:bg-opacity-20 transition">
                    <i class="fas fa-book w-5"></i> Kelola Buku
                </a>
                <a href="users.php" class="block px-4 py-3 rounded-lg hover:bg-white hover:bg-opacity-20 transition">
                    <i class="fas fa-users w-5"></i> Kelola User
                </a>
                <a href="orders.php" class="block px-4 py-3 rounded-lg hover:bg-white hover:bg-opacity-20 transition">
                    <i class="fas fa-shopping-cart w-5"></i> Kelola Pesanan
                </a>
                <a href="messages.php" class="block px-4 py-3 rounded-lg hover:bg-white hover:bg-opacity-20 transition">
                    <i class="fas fa-envelope w-5"></i> Pesan Masuk
                </a>
            </nav>

            <div class="border-t border-white border-opacity-20 p-6 mt-auto">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-sm"><?php echo $_SESSION['full_name']; ?></p>
                        <p class="text-xs text-gray-200">Administrator</p>
                    </div>
                </div>
                <a href="../pages/logout.php" class="block w-full text-center bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-lg transition">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <div class="bg-white shadow px-8 py-4 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800"><i class="fas fa-tachometer-alt text-green-600"></i> Dashboard</h2>
                <div class="text-right">
                    <p class="text-gray-600">Selamat datang, <span class="font-semibold"><?php echo $_SESSION['full_name']; ?></span></p>
                    <p class="text-sm text-gray-400"><?php echo date('d F Y H:i'); ?></p>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1 overflow-auto p-8">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                    <div class="stat-card bg-white rounded-lg p-6 shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm mb-2">Total User</p>
                                <p class="text-3xl font-bold text-gray-800"><?php echo $users_count; ?></p>
                            </div>
                            <div class="text-4xl text-blue-500 bg-blue-100 p-4 rounded-lg">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card bg-white rounded-lg p-6 shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm mb-2">Total Buku</p>
                                <p class="text-3xl font-bold text-gray-800"><?php echo $books_count; ?></p>
                            </div>
                            <div class="text-4xl text-green-500 bg-green-100 p-4 rounded-lg">
                                <i class="fas fa-book"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card bg-white rounded-lg p-6 shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm mb-2">Kategori</p>
                                <p class="text-3xl font-bold text-gray-800"><?php echo $categories_count; ?></p>
                            </div>
                            <div class="text-4xl text-green-500 bg-green-100 p-4 rounded-lg">
                                <i class="fas fa-list"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card bg-white rounded-lg p-6 shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm mb-2">Total Pesanan</p>
                                <p class="text-3xl font-bold text-gray-800"><?php echo $orders_count; ?></p>
                            </div>
                            <div class="text-4xl text-orange-500 bg-orange-100 p-4 rounded-lg">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card bg-white rounded-lg p-6 shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm mb-2">Total Pendapatan</p>
                                <p class="text-2xl font-bold text-gray-800">Rp <?php echo number_format($total_revenue, 0, ',', '.'); ?></p>
                            </div>
                            <div class="text-4xl text-red-500 bg-red-100 p-4 rounded-lg">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Messages Alert Card -->
                <?php if ($unread_messages > 0): ?>
                <div class="bg-blue-50 border-l-4 border-blue-500 p-6 mb-8 rounded">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-blue-900 mb-2">
                                <i class="fas fa-envelope text-blue-600"></i> Pesan Masuk Baru
                            </h3>
                            <p class="text-blue-700">Anda memiliki <strong><?php echo $unread_messages; ?></strong> pesan yang belum dibaca</p>
                        </div>
                        <a href="messages.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                            Lihat Pesan →
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Recent Orders -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-shopping-cart text-green-600"></i> Pesanan Terbaru
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-gray-700 font-semibold">No. Pesanan</th>
                                    <th class="px-6 py-3 text-left text-gray-700 font-semibold">User</th>
                                    <th class="px-6 py-3 text-left text-gray-700 font-semibold">Total</th>
                                    <th class="px-6 py-3 text-left text-gray-700 font-semibold">Status</th>
                                    <th class="px-6 py-3 text-left text-gray-700 font-semibold">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $orders = $conn->query("
                                    SELECT o.*, u.full_name 
                                    FROM orders o 
                                    JOIN users u ON o.user_id = u.id 
                                    ORDER BY o.created_at DESC 
                                    LIMIT 5
                                ");
                                
                                if ($orders->num_rows > 0) {
                                    while ($order = $orders->fetch_assoc()) {
                                        $status_color = 'gray';
                                        if ($order['status'] == 'paid') $status_color = 'green';
                                        if ($order['status'] == 'shipped') $status_color = 'blue';
                                        if ($order['status'] == 'delivered') $status_color = 'green';
                                        
                                        echo "<tr class='border-t hover:bg-gray-50'>
                                            <td class='px-6 py-4 font-semibold text-gray-800'>{$order['order_number']}</td>
                                            <td class='px-6 py-4 text-gray-600'>{$order['full_name']}</td>
                                            <td class='px-6 py-4 font-semibold'>Rp " . number_format($order['total_price'], 0, ',', '.') . "</td>
                                            <td class='px-6 py-4'>
                                                <span class='bg-{$status_color}-100 text-{$status_color}-800 px-3 py-1 rounded-full text-sm font-semibold'>
                                                    " . ucfirst($order['status']) . "
                                                </span>
                                            </td>
                                            <td class='px-6 py-4 text-gray-600'>" . date('d/m/Y H:i', strtotime($order['created_at'])) . "</td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='px-6 py-4 text-center text-gray-500'>Tidak ada pesanan</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 text-right">
                        <a href="orders.php" class="text-green-600 hover:text-green-800 font-semibold">
                            Lihat Semua <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
