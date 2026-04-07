<?php
require_once '../config/config.php';
checkAdmin();

$page_title = 'Pantau Pesanan';

// Handle order status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_status') {
    $order_id = intval($_POST['order_id']);
    $status = escape($_POST['status']);
    
    $conn->query("UPDATE orders SET status='$status' WHERE id=$order_id");
    header("Location: orders.php?success=Status pesanan berhasil diubah");
    exit();
}

$orders = $conn->query("
    SELECT o.*, u.full_name, u.email, u.phone 
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
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
    <style>
        .sidebar {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="sidebar text-white w-64 shadow-lg overflow-y-auto">
            <div class="p-6 border-b border-white border-opacity-20">
                <h1 class="text-2xl font-bold flex items-center space-x-2">
                    <i class="fas fa-book"></i>
                    <span>TokoBuku</span>
                </h1>
                <p class="text-sm text-gray-200 mt-1">Admin Panel</p>
            </div>

            <nav class="p-6 space-y-2">
                <a href="dashboard.php" class="block px-4 py-3 rounded-lg hover:bg-white hover:bg-opacity-20 transition">
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
                <a href="orders.php" class="block px-4 py-3 rounded-lg bg-white bg-opacity-20 font-semibold">
                    <i class="fas fa-shopping-cart w-5"></i> Kelola Pesanan
                </a>
                <a href="messages.php" class="block px-4 py-3 rounded-lg hover:bg-white hover:bg-opacity-20 transition">
                    <i class="fas fa-envelope w-5"></i> Pesan Masuk
                </a>
            </nav>

            <div class="border-t border-white border-opacity-20 p-6 mt-auto">
                <a href="../pages/logout.php" class="block w-full text-center bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-lg transition">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <div class="bg-white shadow px-8 py-4">
                <h2 class="text-2xl font-bold text-gray-800"><i class="fas fa-shopping-cart text-green-600"></i> Daftar Pesanan</h2>
            </div>

            <div class="flex-1 overflow-auto p-8">
                <?php if (isset($_GET['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex justify-between items-center">
                    <span><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_GET['success']); ?></span>
                    <button onclick="this.parentElement.style.display='none'" class="text-green-700">&times;</button>
                </div>
                <?php endif; ?>

                <!-- Orders Table -->
                <div class="bg-white rounded-lg shadow overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">No. Pesanan</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Nama Pelanggan</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Email</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Total</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Status Pesanan</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Status Pembayaran</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($orders->num_rows > 0) {
                                while ($order = $orders->fetch_assoc()) {
                                    $status_colors = [
                                        'pending' => 'yellow',
                                        'paid' => 'green',
                                        'shipped' => 'blue',
                                        'delivered' => 'green',
                                        'cancelled' => 'red'
                                    ];
                                    $status_color = $status_colors[$order['status']] ?? 'gray';
                                    
                                    echo "<tr class='border-t hover:bg-gray-50'>
                                        <td class='px-6 py-4 font-semibold text-gray-800'>{$order['order_number']}</td>
                                        <td class='px-6 py-4 text-gray-600'>{$order['full_name']}</td>
                                        <td class='px-6 py-4 text-gray-600'>{$order['email']}</td>
                                        <td class='px-6 py-4 font-semibold'>Rp " . number_format($order['total_price'], 0, ',', '.') . "</td>
                                        <td class='px-6 py-4'>
                                            <form method='POST' class='inline'>
                                                <input type='hidden' name='action' value='update_status'>
                                                <input type='hidden' name='order_id' value='{$order['id']}'>
                                                <select name='status' onchange='this.form.submit()' class='px-3 py-1 border border-gray-300 rounded text-sm'>
                                                    <option value='pending' " . ($order['status'] == 'pending' ? 'selected' : '') . ">Pending</option>
                                                    <option value='paid' " . ($order['status'] == 'paid' ? 'selected' : '') . ">Dibayar</option>
                                                    <option value='shipped' " . ($order['status'] == 'shipped' ? 'selected' : '') . ">Dikirim</option>
                                                    <option value='delivered' " . ($order['status'] == 'delivered' ? 'selected' : '') . ">Terkirim</option>
                                                    <option value='cancelled' " . ($order['status'] == 'cancelled' ? 'selected' : '') . ">Dibatalkan</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td class='px-6 py-4'>
                                            <span class='bg-" . ($order['status'] == 'paid' || $order['status'] == 'shipped' || $order['status'] == 'delivered' || $order['status'] == 'cancelled' ? 'green' : 'red') . "-100 text-" . ($order['status'] == 'paid' || $order['status'] == 'shipped' || $order['status'] == 'delivered' ? 'green' : 'red') . "-800 px-3 py-1 rounded-full text-sm font-semibold'>
                                                " . ($order['status'] == 'paid' || $order['status'] == 'shipped' || $order['status'] == 'delivered' || $order['status'] == 'cancelled' ? 'Sudah Bayar' : 'Belum Bayar') . "
                                            </span>
                                        </td>
                                        <td class='px-6 py-4 text-gray-600'>" . date('d/m/Y H:i', strtotime($order['created_at'])) . "</td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8' class='px-6 py-4 text-center text-gray-500'>Tidak ada pesanan</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function viewOrder(id) {
            alert('Fitur detail pesanan akan ditambahkan di masa depan');
        }
    </script>
</body>
</html>
