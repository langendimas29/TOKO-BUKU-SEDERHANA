<?php
require_once '../config/config.php';

$page_title = 'Detail Produk';

if (!isset($_GET['id'])) {
    header("Location: catalog.php");
    exit();
}

$book_id = intval($_GET['id']);
$book = $conn->query("SELECT b.*, c.name as category_name FROM books b JOIN categories c ON b.category_id=c.id WHERE b.id=$book_id")->fetch_assoc();

if (!$book) {
    header("Location: catalog.php?error=Produk tidak ditemukan");
    exit();
}

$related = $conn->query("SELECT b.*, c.name as category_name FROM books b JOIN categories c ON b.category_id=c.id WHERE b.category_id={$book['category_id']} AND b.id != $book_id LIMIT 4");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $book['title']; ?> - TokoBuku</title>
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
                <a href="catalog.php" class="hover:text-green-600">Katalog</a>
                <span>/</span>
                <span class="text-gray-800 font-semibold truncate"><?php echo htmlspecialchars($book['title']); ?></span>
            </div>
        </div>
    </div>

    <!-- Product Details -->
    <div class="container mx-auto px-4 py-12">
        <div class="bg-white rounded-lg shadow-lg p-8 mb-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Product Image -->
                <div class="flex items-center justify-center bg-gray-200 rounded-lg h-full min-h-96">
                    <?php 
                        $image_file = __DIR__ . '/../public/images/' . $book['image_url'];
                        if (!empty($book['image_url']) && file_exists($image_file)): 
                    ?>
                        <img src="/public/images/<?php echo htmlspecialchars($book['image_url']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" class="max-h-96 object-cover">
                    <?php else: ?>
                        <div class="w-full h-full bg-green-400 flex items-center justify-center">
                            <i class="fas fa-book text-white text-9xl opacity-30"></i>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Product Info -->
                <div>
                    <div class="mb-4">
                        <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-semibold">
                            <i class="fas fa-tag"></i> <?php echo htmlspecialchars($book['category_name']); ?>
                        </span>
                    </div>

                    <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">
                        <?php echo htmlspecialchars($book['title']); ?>
                    </h1>

                    <p class="text-lg text-gray-600 mb-4">
                        Oleh: <span class="font-semibold"><?php echo htmlspecialchars($book['author']); ?></span>
                    </p>

                    <!-- Price -->
                    <div class="mb-6">
                        <div class="text-4xl font-bold text-green-600">
                            Rp <?php echo number_format($book['price'], 0, ',', '.'); ?>
                        </div>
                    </div>

                    <!-- Stock Info -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <p class="text-gray-600">
                            <i class="fas fa-box"></i> Stok: 
                            <strong class="<?php echo $book['stock'] > 0 ? 'text-green-600' : 'text-red-600'; ?>">
                                <?php echo $book['stock'] > 0 ? $book['stock'] . ' tersedia' : 'Habis Terjual'; ?>
                            </strong>
                        </p>
                        <p class="text-gray-600 text-sm mt-2">ISBN: <?php echo htmlspecialchars($book['isbn']); ?></p>
                    </div>

                    <!-- Add to Cart -->
                    <?php if ($book['stock'] > 0): ?>
                    <form method="POST" action="add-to-cart.php" class="mb-6">
                        <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <button type="submit" class="w-full bg-green-600 text-white px-6 py-3 rounded-lg font-bold hover:shadow-lg transition text-lg mb-3">
                                <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
                            </button>
                        <?php else: ?>
                            <a href="login.php" class="block text-center bg-green-600 text-white px-6 py-3 rounded-lg font-bold hover:shadow-lg transition text-lg mb-3">
                                <i class="fas fa-sign-in-alt"></i> Login untuk Membeli
                            </a>
                        <?php endif; ?>
                    </form>
                    <?php else: ?>
                    <button disabled class="w-full bg-gray-400 text-white px-6 py-3 rounded-lg font-bold text-lg mb-3 cursor-not-allowed">
                        <i class="fas fa-times-circle"></i> Stok Habis
                    </button>
                    <?php endif; ?>

                </div>
            </div>
        </div>

        <!-- Product Description -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Deskripsi Produk</h2>
            <div class="prose max-w-none">
                <p class="text-gray-600 leading-relaxed"><?php echo nl2br(htmlspecialchars($book['description'])); ?></p>
            </div>

            <!-- Details -->
            <div class="mt-8 pt-8 border-t">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Informasi Produk</h3>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-gray-600 text-sm">Pengarang</p>
                        <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($book['author']); ?></p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Kategori</p>
                        <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($book['category_name']); ?></p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">ISBN</p>
                        <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($book['isbn']); ?></p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Harga</p>
                        <p class="font-semibold text-gray-800">Rp <?php echo number_format($book['price'], 0, ',', '.'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <?php if ($related->num_rows > 0): ?>
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-8">
                <i class="fas fa-link"></i> Buku Terkait
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php
                while ($rel_book = $related->fetch_assoc()) {
                    $rel_image_file = __DIR__ . '/../public/images/' . $rel_book['image_url'];
                    $has_image = !empty($rel_book['image_url']) && file_exists($rel_image_file);
                    echo "<div class='bg-white rounded-lg shadow-lg overflow-hidden book-card'>
                        <div class='bg-gray-300 h-48 flex items-center justify-center'>
                            " . ($has_image ? 
                                "<img src='/public/images/{$rel_book['image_url']}' alt='{$rel_book['title']}' class='w-full h-full object-cover'>" :
                                "<i class='fas fa-book text-white text-6xl'></i>"
                            ) . "
                        </div>
                        <div class='p-4'>
                            <span class='text-green-600 text-xs font-semibold'>{$rel_book['category_name']}</span>
                            <h3 class='font-bold text-gray-800 mt-1 line-clamp-2 text-sm'>{$rel_book['title']}</h3>
                            <p class='text-2xl font-bold text-green-600 mt-3'>Rp " . number_format($rel_book['price'], 0, ',', '.') . "</p>
                            <a href='product-detail.php?id={$rel_book['id']}' class='block w-full text-center bg-green-600 text-white px-3 py-2 rounded mt-4 font-semibold text-sm hover:bg-green-700 transition'>
                                Lihat Detail
                            </a>
                        </div>
                    </div>";
                }
                ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <?php require_once '../includes/footer.php'; ?>
</body>
</html>
