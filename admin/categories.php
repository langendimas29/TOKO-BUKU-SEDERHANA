<?php
require_once '../config/config.php';
checkAdmin();

$page_title = 'Kelola Kategori';

// Handle add category
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action == 'add') {
        $name = escape($_POST['name']);
        $description = escape($_POST['description']);
        
        $insert = $conn->query("INSERT INTO categories (name, description) VALUES ('$name', '$description')");
        if ($insert) {
            header("Location: categories.php?success=Kategori berhasil ditambahkan");
            exit();
        }
    } elseif ($action == 'edit') {
        $id = intval($_POST['id']);
        $name = escape($_POST['name']);
        $description = escape($_POST['description']);
        
        $update = $conn->query("UPDATE categories SET name='$name', description='$description' WHERE id=$id");
        if ($update) {
            header("Location: categories.php?success=Kategori berhasil diubah");
            exit();
        }
    } elseif ($action == 'delete') {
        $id = intval($_POST['id']);
        $delete = $conn->query("DELETE FROM categories WHERE id=$id");
        if ($delete) {
            header("Location: categories.php?success=Kategori berhasil dihapus");
            exit();
        }
    }
}

$categories = $conn->query("SELECT * FROM categories ORDER BY created_at DESC");
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
        <div class="sidebar text-white w-64 shadow-lg">
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
                <a href="categories.php" class="block px-4 py-3 rounded-lg bg-white bg-opacity-20 font-semibold">
                    <i class="fas fa-list w-5"></i> Kelola Kategori
                </a>
                <a href="books.php" class="block px-4 py-3 rounded-lg hover:bg-white hover:bg-opacity-20 transition">
                    <i class="fas fa-book w-5"></i> Kelola Buku
                </a>
                <a href="users.php" class="block px-4 py-3 rounded-lg hover:bg-white hover:bg-opacity-20 transition">
                    <i class="fas fa-users w-5"></i> Pantau User
                </a>
                <a href="orders.php" class="block px-4 py-3 rounded-lg hover:bg-white hover:bg-opacity-20 transition">
                    <i class="fas fa-shopping-cart w-5"></i> Pantau Pesanan
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
            <div class="bg-white shadow px-8 py-4 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800"><i class="fas fa-list text-green-600"></i> Kelola Kategori</h2>
            </div>

            <div class="flex-1 overflow-auto p-8">
                <?php if (isset($_GET['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex justify-between items-center">
                    <span><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_GET['success']); ?></span>
                    <button onclick="this.parentElement.style.display='none'" class="text-green-700">&times;</button>
                </div>
                <?php endif; ?>

                <!-- Add Category Form -->
                <div class="bg-white rounded-lg shadow p-6 mb-8">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Tambah Kategori Baru</h3>
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="add">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Nama Kategori</label>
                                <input type="text" name="name" required 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"
                                       placeholder="Contoh: Novel, Teknologi, dll">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
                                <input type="text" name="description" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"
                                       placeholder="Deskripsi singkat">
                            </div>
                            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition font-semibold">
                                <i class="fas fa-plus"></i> Tambah
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Categories Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">No</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Nama Kategori</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Deskripsi</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Tanggal Dibuat</th>
                                <th class="px-6 py-4 text-center text-gray-700 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            while ($cat = $categories->fetch_assoc()) {
                                echo "<tr class='border-t hover:bg-gray-50'>
                                    <td class='px-6 py-4'>{$no}</td>
                                    <td class='px-6 py-4 font-semibold text-gray-800'>{$cat['name']}</td>
                                    <td class='px-6 py-4 text-gray-600'>{$cat['description']}</td>
                                    <td class='px-6 py-4 text-gray-600'>" . date('d/m/Y H:i', strtotime($cat['created_at'])) . "</td>
                                    <td class='px-6 py-4 text-center space-x-2'>
                                        <button onclick=\"editCategory({$cat['id']}, '{$cat['name']}', '{$cat['description']}')\" class='bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition'>
                                            <i class='fas fa-edit'></i> Edit
                                        </button>
                                        <form method='POST' class='inline' onsubmit=\"return confirm('Yakin ingin menghapus?')\">
                                            <input type='hidden' name='action' value='delete'>
                                            <input type='hidden' name='id' value='{$cat['id']}'>
                                            <button type='submit' class='bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition'>
                                                <i class='fas fa-trash'></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>";
                                $no++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Edit Kategori</h3>
            <form method="POST" action="">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" id="edit_id">
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Nama Kategori</label>
                    <input type="text" name="name" id="edit_name" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                </div>
                
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
                    <textarea name="description" id="edit_description" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"></textarea>
                </div>

                <div class="flex space-x-4">
                    <button type="submit" class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition font-semibold">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <button type="button" onclick="closeEditModal()" class="flex-1 bg-gray-400 text-white px-4 py-2 rounded-lg hover:bg-gray-500 transition">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editCategory(id, name, description) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_description').value = description;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
</body>
</html>
