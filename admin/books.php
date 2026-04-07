<?php
require_once '../config/config.php';
checkAdmin();

$page_title = 'Kelola Buku';

// Handle add/edit/delete book
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action == 'add' || $action == 'edit') {
        $id = intval($_POST['id'] ?? 0);
        
        // Get existing data if editing
        $existing = null;
        if ($action == 'edit') {
            $existing = $conn->query("SELECT * FROM books WHERE id=$id")->fetch_assoc();
            if (!$existing) {
                header("Location: books.php?error=Buku tidak ditemukan");
                exit();
            }
        }
        
        // Use provided values or existing values
        $title = !empty($_POST['title']) ? escape($_POST['title']) : ($existing['title'] ?? '');
        $author = !empty($_POST['author']) ? escape($_POST['author']) : ($existing['author'] ?? '');
        $isbn = !empty($_POST['isbn']) ? escape($_POST['isbn']) : ($existing['isbn'] ?? '');
        $category_id = !empty($_POST['category_id']) ? intval($_POST['category_id']) : ($existing['category_id'] ?? 0);
        $price = !empty($_POST['price']) ? floatval($_POST['price']) : ($existing['price'] ?? 0);
        $stock = !empty($_POST['stock']) ? intval($_POST['stock']) : ($existing['stock'] ?? 0);
        $description = !empty($_POST['description']) ? escape($_POST['description']) : ($existing['description'] ?? '');
        $image_url = $existing['image_url'] ?? '';
        
        // Handle file upload
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $filename = $_FILES['image_file']['name'];
            $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $filesize = $_FILES['image_file']['size'];
            
            // Validate file type and size (max 5MB)
            if (in_array($filetype, $allowed) && $filesize <= 5242880) {
                // Create unique filename
                $new_filename = 'book_' . time() . '.' . $filetype;
                $upload_path = '../public/images/' . $new_filename;
                
                // Create directory if not exists
                if (!is_dir('../public/images')) {
                    mkdir('../public/images', 0755, true);
                }
                
                // Move uploaded file
                if (move_uploaded_file($_FILES['image_file']['tmp_name'], $upload_path)) {
                    // Delete old image if editing
                    if ($action == 'edit' && $image_url && file_exists('../public/images/' . $image_url)) {
                        unlink('../public/images/' . $image_url);
                    }
                    $image_url = $new_filename;
                }
            }
        }
        
        // Validate required fields
        if (!$title || !$category_id || !$price) {
            header("Location: books.php?error=Judul, Kategori, dan Harga wajib diisi");
            exit();
        }
        
        if ($action == 'add') {
            $insert = $conn->query("INSERT INTO books (title, author, isbn, category_id, price, stock, description, image_url) 
                                    VALUES ('$title', '$author', '$isbn', $category_id, $price, $stock, '$description', '$image_url')");
            $msg = 'Buku berhasil ditambahkan';
        } else {
            $update = $conn->query("UPDATE books SET title='$title', author='$author', isbn='$isbn', category_id=$category_id, 
                                    price=$price, stock=$stock, description='$description', image_url='$image_url' WHERE id=$id");
            $msg = 'Buku berhasil diubah';
        }
        
        header("Location: books.php?success=" . urlencode($msg));
        exit();
    } elseif ($action == 'delete') {
        $id = intval($_POST['id']);
        $delete = $conn->query("DELETE FROM books WHERE id=$id");
        header("Location: books.php?success=Buku berhasil dihapus");
        exit();
    }
}

$books = $conn->query("SELECT b.*, c.name as category_name FROM books b JOIN categories c ON b.category_id=c.id ORDER BY b.created_at DESC");
$categories = $conn->query("SELECT * FROM categories ORDER BY name");
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
                <a href="books.php" class="block px-4 py-3 rounded-lg bg-white bg-opacity-20 font-semibold">
                    <i class="fas fa-book w-5"></i> Kelola Buku
                </a>
                <a href="users.php" class="block px-4 py-3 rounded-lg hover:bg-white hover:bg-opacity-20 transition">
                    <i class="fas fa-users w-5"></i> kelola User
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
            <div class="bg-white shadow px-8 py-4 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800"><i class="fas fa-book text-green-600"></i> Kelola Buku</h2>
                <button onclick="openAddModal()" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition font-semibold">
                    <i class="fas fa-plus"></i> Tambah Buku
                </button>
            </div>

            <div class="flex-1 overflow-auto p-8">
                <?php if (isset($_GET['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex justify-between items-center">
                    <span><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_GET['success']); ?></span>
                    <button onclick="this.parentElement.style.display='none'" class="text-green-700">&times;</button>
                </div>
                <?php endif; ?>

                <!-- Books Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">No</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Judul</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Pengarang</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Kategori</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Harga</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Stok</th>
                                <th class="px-6 py-4 text-center text-gray-700 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            while ($book = $books->fetch_assoc()) {
                                echo "<tr class='border-t hover:bg-gray-50'>
                                    <td class='px-6 py-4'>{$no}</td>
                                    <td class='px-6 py-4 font-semibold text-gray-800'>{$book['title']}</td>
                                    <td class='px-6 py-4 text-gray-600'>{$book['author']}</td>
                                    <td class='px-6 py-4'><span class='bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm'>{$book['category_name']}</span></td>
                                    <td class='px-6 py-4'>Rp " . number_format($book['price'], 0, ',', '.') . "</td>
                                    <td class='px-6 py-4'>
                                        <span class='bg-" . ($book['stock'] > 0 ? 'green' : 'red') . "-100 text-" . ($book['stock'] > 0 ? 'green' : 'red') . "-800 px-3 py-1 rounded text-sm'>
                                            {$book['stock']}
                                        </span>
                                    </td>
                                    <td class='px-6 py-4 text-center space-x-2'>
                                        <button onclick=\"editBook({$book['id']})\" class='bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition'>
                                            <i class='fas fa-edit'></i>
                                        </button>
                                        <form method='POST' class='inline' onsubmit=\"return confirm('Yakin ingin menghapus?')\">
                                            <input type='hidden' name='action' value='delete'>
                                            <input type='hidden' name='id' value='{$book['id']}'>
                                            <button type='submit' class='bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition'>
                                                <i class='fas fa-trash'></i>
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

    <!-- Add/Edit Modal -->
    <div id="bookModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-y-auto">
        <div class="bg-white rounded-lg p-8 max-w-2xl w-full mx-4 my-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4" id="modalTitle">Tambah Buku</h3>
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="action" id="book_action" value="add">
                <input type="hidden" name="id" id="book_id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Judul Buku <span class="required">*</span></label>
                        <input type="text" name="title" id="book_title" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Pengarang</label>
                        <input type="text" name="author" id="book_author" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">ISBN</label>
                        <input type="text" name="isbn" id="book_isbn" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Kategori <span class="required">*</span></label>
                        <select name="category_id" id="book_category" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                            <option value="">Pilih Kategori</option>
                            <?php 
                            $categories->data_seek(0);
                            while ($cat = $categories->fetch_assoc()) {
                                echo "<option value='{$cat['id']}'>{$cat['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Harga (Rp) <span class="required">*</span></label>
                        <input type="number" name="price" id="book_price" step="0.01" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Stok</label>
                        <input type="number" name="stock" id="book_stock" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
                    <textarea name="description" id="book_description" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"></textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Gambar Cover Buku</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-green-600 transition bg-gray-50" onclick="document.getElementById('book_image').click()">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2 block"></i>
                        <p class="text-gray-700 font-medium">Klik untuk upload gambar</p>
                        <p class="text-gray-500 text-sm mt-1">Format: JPG, PNG, GIF, WEBP (Max 5MB)</p>
                    </div>
                    <input type="file" name="image_file" id="book_image" accept="image/jpeg,image/png,image/gif,image/webp" 
                           class="hidden" onchange="previewImage(event)">
                    <div id="image_preview" class="mt-4"></div>
                </div>

                <div class="flex space-x-4">
                    <button type="submit" class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition font-semibold">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <button type="button" onclick="closeModal()" class="flex-1 bg-gray-400 text-white px-4 py-2 rounded-lg hover:bg-gray-500 transition">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // All books data
        const booksData = {
            <?php
            $books->data_seek(0);
            while ($book = $books->fetch_assoc()) {
                echo $book['id'] . ': ' . json_encode($book) . ',';
            }
            ?>
        };

        function openAddModal() {
            document.getElementById('book_action').value = 'add';
            document.getElementById('modalTitle').textContent = 'Tambah Buku';
            document.getElementById('book_id').value = '';
            document.getElementById('book_title').value = '';
            document.getElementById('book_author').value = '';
            document.getElementById('book_isbn').value = '';
            document.getElementById('book_category').value = '';
            document.getElementById('book_price').value = '';
            document.getElementById('book_stock').value = '';
            document.getElementById('book_description').value = '';
            document.getElementById('book_image').value = '';
            document.getElementById('image_preview').innerHTML = '';
            setFieldsRequired(true);
            document.getElementById('bookModal').classList.remove('hidden');
        }

        function editBook(id) {
            const book = booksData[id];
            if (!book) {
                alert('Data buku tidak ditemukan');
                return;
            }

            document.getElementById('book_action').value = 'edit';
            document.getElementById('modalTitle').textContent = 'Edit Buku';
            document.getElementById('book_id').value = book.id;
            document.getElementById('book_title').value = book.title || '';
            document.getElementById('book_author').value = book.author || '';
            document.getElementById('book_isbn').value = book.isbn || '';
            document.getElementById('book_category').value = book.category_id || '';
            document.getElementById('book_price').value = book.price || '';
            document.getElementById('book_stock').value = book.stock || '';
            document.getElementById('book_description').value = book.description || '';
            document.getElementById('book_image').value = '';
            
            // Show existing image preview
            if (book.image_url && book.image_url !== '') {
                document.getElementById('image_preview').innerHTML = 
                    '<img src="../public/images/' + book.image_url + '" class="max-w-xs max-h-64 mx-auto rounded-lg border border-gray-300 shadow-md">' +
                    '<p class="text-sm text-gray-600 text-center mt-2">Gambar saat ini (upload gambar baru untuk mengubah)</p>';
            } else {
                document.getElementById('image_preview').innerHTML = '';
            }
            
            setFieldsRequired(false);
            document.getElementById('bookModal').classList.remove('hidden');
        }

        function setFieldsRequired(isRequired) {
            document.getElementById('book_title').required = isRequired;
            document.getElementById('book_category').required = isRequired;
            document.getElementById('book_price').required = isRequired;
        }

        function closeModal() {
            document.getElementById('bookModal').classList.add('hidden');
        }

        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('image_preview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = '<img src="' + e.target.result + '" class="max-w-xs max-h-64 mx-auto rounded-lg border border-gray-300 shadow-md">';
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
    
    <style>
        .required { color: red; }
    </style>
</body>
</html>
