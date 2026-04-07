<?php
require_once '../config/config.php';
checkAdmin();

$page_title = 'Pantau User';

// Handle role change
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'change_role') {
    $user_id = intval($_POST['user_id']);
    $new_role = $_POST['new_role'];
    
    if (in_array($new_role, ['user', 'admin'])) {
        $conn->query("UPDATE users SET role='$new_role' WHERE id=$user_id");
        header("Location: users.php?success=Role user berhasil diubah");
        exit();
    }
}

$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
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
                <a href="users.php" class="block px-4 py-3 rounded-lg bg-white bg-opacity-20 font-semibold">
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
                <a href="../pages/logout.php" class="block w-full text-center bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-lg transition">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <div class="bg-white shadow px-8 py-4">
                <h2 class="text-2xl font-bold text-gray-800"><i class="fas fa-users text-green-600"></i> Daftar User</h2>
            </div>

            <div class="flex-1 overflow-auto p-8">
                <?php if (isset($_GET['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex justify-between items-center">
                    <span><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_GET['success']); ?></span>
                    <button onclick="this.parentElement.style.display='none'" class="text-green-700">&times;</button>
                </div>
                <?php endif; ?>
                
                <!-- Users Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">No</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Username</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Nama Lengkap</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Email</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">No. Telepon</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Role</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Tanggal Daftar</th>
                                <th class="px-6 py-4 text-center text-gray-700 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            if ($users->num_rows > 0) {
                                while ($user = $users->fetch_assoc()) {
                                    echo "<tr class='border-t hover:bg-gray-50'>
                                        <td class='px-6 py-4'>{$no}</td>
                                        <td class='px-6 py-4 font-semibold text-gray-800'>{$user['username']}</td>
                                        <td class='px-6 py-4 text-gray-600'>{$user['full_name']}</td>
                                        <td class='px-6 py-4 text-gray-600'>{$user['email']}</td>
                                        <td class='px-6 py-4 text-gray-600'>{$user['phone']}</td>
                                        <td class='px-6 py-4'>
                                            <span class='bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm'>{$user['role']}</span>
                                        </td>
                                        <td class='px-6 py-4 text-gray-600'>" . date('d/m/Y H:i', strtotime($user['created_at'])) . "</td>
                                        <td class='px-6 py-4 text-center'>
                                            <button onclick=\"openRoleModal({$user['id']}, '{$user['full_name']}')\" class='bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm transition'>
                                                <i class='fas fa-edit'></i> Ubah Role
                                            </button>
                                        </td>
                                    </tr>";
                                    $no++;
                                }
                            } else {
                                echo "<tr><td colspan='8' class='px-6 py-4 text-center text-gray-500'>Tidak ada user</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Role Change Modal -->
    <div id="roleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Ubah Role User</h3>
            <p class="text-gray-600 mb-4">Ubah role untuk <strong id="userName"></strong></p>
            
            <form method="POST" action="">
                <input type="hidden" name="action" value="change_role">
                <input type="hidden" name="user_id" id="user_id">
                
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Pilih Role Baru</label>
                    <select name="new_role" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                        <option value="">-- Pilih Role --</option>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition font-semibold">
                        <i class="fas fa-check"></i> Ubah
                    </button>
                    <button type="button" onclick="closeRoleModal()" class="flex-1 bg-gray-400 text-white px-4 py-2 rounded-lg hover:bg-gray-500 transition">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRoleModal(userId, userName) {
            document.getElementById('user_id').value = userId;
            document.getElementById('userName').textContent = userName;
            document.getElementById('roleModal').classList.remove('hidden');
        }

        function closeRoleModal() {
            document.getElementById('roleModal').classList.add('hidden');
        }
    </script>
</body>
</html>
