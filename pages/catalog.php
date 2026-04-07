<?php
require_once '../config/config.php';

$page_title = 'Katalog Buku';

// Get categories for sidebar
$categories = $conn->query("SELECT * FROM categories");

// Build query
$query = "SELECT b.*, c.name as category_name FROM books b JOIN categories c ON b.category_id=c.id WHERE 1=1";
$filters = [];

// Filter by search
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = escape($_GET['search']);
    $query .= " AND (b.title LIKE '%$search%' OR b.author LIKE '%$search%' OR b.description LIKE '%$search%')";
    $filters['search'] = $_GET['search'];
}

// Filter by category
if (isset($_GET['category']) && !empty($_GET['category'])) {
    $category = intval($_GET['category']);
    $query .= " AND b.category_id = $category";
    $filters['category'] = $category;
}

// Sort
$sort = isset($_GET['sort']) ? escape($_GET['sort']) : 'newest';
switch ($sort) {
    case 'price_asc':
        $query .= " ORDER BY b.price ASC";
        break;
    case 'price_desc':
        $query .= " ORDER BY b.price DESC";
        break;
    case 'title':
        $query .= " ORDER BY b.title ASC";
        break;
    default:
        $query .= " ORDER BY b.created_at DESC";
}

$books = $conn->query($query);
$total_books = $books->num_rows;
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
                <span class="text-gray-800 font-semibold">Katalog</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Sidebar Filters -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-filter"></i> Filter</h3>

                    <!-- Search Filter -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-700 mb-3">Cari Buku</h4>
                        <form method="GET" action="">
                            <div class="flex">
                                <input type="text" name="search" placeholder="Judul atau penulis..." 
                                       value="<?php echo isset($filters['search']) ? htmlspecialchars($filters['search']) : ''; ?>"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm">
                                <button type="submit" class="bg-green-600 text-white px-3 py-2 rounded-r-lg hover:bg-green-700">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Category Filter -->
                    <div class="mb-6 pb-6 border-b">
                        <h4 class="font-semibold text-gray-700 mb-3">Kategori</h4>
                        <div class="space-y-2">
                            <a href="catalog.php" class="block px-3 py-2 rounded hover:bg-green-100 <?php echo !isset($filters['category']) ? 'bg-green-100 text-green-600 font-semibold' : 'text-gray-700'; ?>">
                                <i class="fas fa-check"></i> Semua Kategori
                            </a>
                            <?php
                            $categories->data_seek(0);
                            while ($cat = $categories->fetch_assoc()) {
                                $active = isset($filters['category']) && $filters['category'] == $cat['id'];
                                echo "<a href='catalog.php?category={$cat['id']}' class='block px-3 py-2 rounded hover:bg-green-100 \" . ($active ? 'bg-green-100 text-green-600 font-semibold' : 'text-gray-700') . \"'>
                                    <i class='fas fa-check'></i> {$cat['name']}
                                </a>";
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Sort Filter -->
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-3">Urutkan</h4>
                        <form method="GET" action="">
                            <?php 
                            if (isset($filters['search'])) echo "<input type='hidden' name='search' value='" . htmlspecialchars($filters['search']) . "'>";
                            if (isset($filters['category'])) echo "<input type='hidden' name='category' value='" . htmlspecialchars($filters['category']) . "'>";
                            ?>
                            <select name="sort" onchange="this.form.submit()" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 text-sm">
                                <option value="newest" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>Terbaru</option>
                                <option value="title" <?php echo $sort == 'title' ? 'selected' : ''; ?>>Judul (A-Z)</option>
                                <option value="price_asc" <?php echo $sort == 'price_asc' ? 'selected' : ''; ?>>Harga Terendah</option>
                                <option value="price_desc" <?php echo $sort == 'price_desc' ? 'selected' : ''; ?>>Harga Tertinggi</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Products -->
            <div class="md:col-span-3">
                <!-- Results Info -->
                <div class="mb-6 bg-white rounded-lg shadow p-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">
                            Hasil Pencarian
                        </h2>
                        <p class="text-gray-600 text-sm">
                            Menampilkan <strong><?php echo $total_books; ?></strong> buku
                        </p>
                    </div>
                    <?php if (isset($filters['search']) || isset($filters['category'])): ?>
                    <a href="catalog.php" class="text-green-600 hover:text-green-800 font-semibold">
                        <i class="fas fa-times"></i> Hapus Filter
                    </a>
                    <?php endif; ?>
                </div>

                <!-- Books Grid -->
                <?php if ($total_books > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php
                    while ($book = $books->fetch_assoc()) {
                        echo "<div class='bg-white rounded-lg shadow-lg overflow-hidden book-card'>
                            <div class='bg-gray-300 h-48 flex items-center justify-center relative overflow-hidden'>
                                " . (file_exists("../public/images/{$book['image_url']}") ? 
                                    "<img src='../public/images/{$book['image_url']}' alt='{$book['title']}' class='w-full h-full object-cover'>" :
                                    "<div class='w-full h-full bg-green-400 flex items-center justify-center'>
                                        <i class='fas fa-book text-white text-6xl'></i>
                                    </div>"
                                ) . "
                            </div>
                            <div class='p-6'>
                                <span class='text-green-600 text-sm font-semibold'>{$book['category_name']}</span>
                                <h3 class='text-lg font-bold text-gray-800 mt-2 line-clamp-2'>{$book['title']}</h3>
                                <p class='text-gray-600 text-sm mt-1'>{$book['author']}</p>
                                
                                <div class='flex justify-between items-center my-4'>
                                    <span class='text-2xl font-bold text-gray-800'>Rp " . number_format($book['price'], 0, ',', '.') . "</span>
                                    <span class='text-sm " . ($book['stock'] > 0 ? 'text-green-600' : 'text-red-600') . "'>
                                        " . ($book['stock'] > 0 ? "Stok: {$book['stock']}" : 'Habis') . "
                                    </span>
                                </div>
                                
                                <div class='flex space-x-2'>
                                    <a href='product-detail.php?id={$book['id']}' class='flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-center text-sm font-semibold'>
                                        <i class='fas fa-eye'></i> Lihat
                                    </a>
                                    " . (isset($_SESSION['user_id']) && $book['stock'] > 0 ? 
                                        "<form method='POST' action='add-to-cart.php' class='flex-1'>
                                            <input type='hidden' name='book_id' value='{$book['id']}'>
                                            <button type='submit' class='w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-sm font-semibold'>
                                                <i class='fas fa-shopping-cart'></i>
                                            </button>
                                        </form>" :
                                        "<button disabled class='flex-1 bg-gray-400 text-white px-4 py-2 rounded-lg text-sm font-semibold cursor-not-allowed'>
                                            <i class='fas fa-lock'></i>
                                        </button>"
                                    ) . "
                                </div>
                            </div>
                        </div>";
                    }
                    ?>
                </div>
                <?php else: ?>
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Buku Tidak Ditemukan</h3>
                    <p class="text-gray-600 mb-6">Coba ubah filter atau cari dengan kata kunci yang berbeda</p>
                    <a href="catalog.php" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition font-semibold">
                        <i class="fas fa-redo"></i> Kembali ke Katalog
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php require_once '../includes/footer.php'; ?>
</body>
</html>
