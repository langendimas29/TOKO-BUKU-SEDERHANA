<?php
require_once '../config/config.php';

$page_title = 'Home';

// Get featured books
$featured_books = $conn->query("SELECT b.*, c.name as category_name FROM books b JOIN categories c ON b.category_id=c.id LIMIT 6");

// Get categories for filter
$categories = $conn->query("SELECT * FROM categories");
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

    <!-- Hero Section -->
    <section class="bg-green-600 text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                <i class="fas fa-book-open text-yellow-300"></i> Selamat Datang di TokoBuku
            </h1>
            <p class="text-xl mb-8">Temukan koleksi buku terbaik dengan harga terjangkau</p>
            
            <!-- Search Bar -->
            <form method="GET" action="catalog.php" class="max-w-md mx-auto">
                <div class="flex">
                    <input type="text" name="search" placeholder="Cari buku..." required
                           class="flex-1 px-4 py-3 rounded-l-lg text-gray-800 focus:outline-none">
                    <button type="submit" class="bg-green-700 hover:bg-green-800 px-6 py-3 rounded-r-lg font-semibold transition">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </section>


    <!-- Featured Books Section -->
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-star text-yellow-400"></i> RekomendasiBuku Pilihan
                </h2>
                <p class="text-gray-600">Koleksi buku terdidik dari berbagai kategori</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <?php
                while ($book = $featured_books->fetch_assoc()) {
                    echo "<div class='bg-white rounded-lg shadow-lg overflow-hidden book-card'>
                        <div class='bg-gray-300 h-48 flex items-center justify-center relative overflow-hidden group'>
                            " . (file_exists("../public/images/{$book['image_url']}") ? 
                                "<img src='../public/images/{$book['image_url']}' alt='{$book['title']}' class='w-full h-full object-cover'>" :
                                "<div class='w-full h-full bg-green-400 flex items-center justify-center'>
                                    <i class='fas fa-book text-white text-6xl'></i>
                                </div>"
                            ) . "
                            <div class='absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold'>
                                Sale
                            </div>
                        </div>
                        <div class='p-6'>
                            <span class='text-green-600 text-sm font-semibold'>{$book['category_name']}</span>
                            <h3 class='text-lg font-bold text-gray-800 mt-2 line-clamp-2'>{$book['title']}</h3>
                            <p class='text-gray-600 text-sm mt-1'>{$book['author']}</p>
                            
                            
                            <div class='flex justify-between items-center mb-4'>
                                <div>
                                    <span class='text-2xl font-bold text-gray-800'>Rp " . number_format($book['price'], 0, ',', '.') . "</span>
                                </div>
                                <span class='text-sm text-gray-600'>Stok: {$book['stock']}</span>
                            </div>
                            
                            <div class='flex space-x-2'>
                                <a href='product-detail.php?id={$book['id']}' class='flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-center font-semibold'>
                                    <i class='fas fa-eye'></i> Lihat
                                </a>
                                " . (isset($_SESSION['user_id']) ? 
                                    "<form method='POST' action='add-to-cart.php' class='flex-1'>
                                        <input type='hidden' name='book_id' value='{$book['id']}'>
                                        <button type='submit' class='w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition font-semibold'>
                                            <i class='fas fa-shopping-cart'></i> Keranjang
                                        </button>
                                    </form>" :
                                    "<a href='login.php' class='flex-1 bg-gray-400 text-white px-4 py-2 rounded-lg hover:bg-gray-500 transition text-center font-semibold'>
                                        <i class='fas fa-lock'></i> Login
                                    </a>"
                                ) . "
                            </div>
                        </div>
                    </div>";
                }
                ?>
            </div>

            <div class="text-center">
                <a href="catalog.php" class="bg-green-600 text-white px-8 py-3 rounded-lg font-bold hover:shadow-lg transition">
                    <i class="fas fa-th"></i> Lihat Semua Buku
                </a>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">
                <i class="fas fa-list text-green-600"></i> Kategori Buku
            </h2>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <?php
                $categories->data_seek(0);
                while ($cat = $categories->fetch_assoc()) {
                    echo "<a href='catalog.php?category={$cat['id']}' class='bg-green-600 text-white p-6 rounded-lg hover:shadow-lg transition text-center'>
                        <div class='text-3xl mb-2'><i class='fas fa-book'></i></div>
                        <h3 class='font-bold'>{$cat['name']}</h3>
                    </a>";
                }
                ?>
            </div>
        </div>
    </section>

    <?php require_once '../includes/footer.php'; ?>
</body>
</html>
