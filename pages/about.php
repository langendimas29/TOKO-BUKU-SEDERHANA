<?php
require_once '../config/config.php';

$page_title = 'Tentang Kami';
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
    <section class="bg-gradient-to-r from-green-600 to-green-600 text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                <i class="fas fa-book-open text-yellow-300"></i> Tentang TokoBuku
            </h1>
            <p class="text-xl">Membantu Anda menemukan buku terbaik untuk setiap momen</p>
        </div>
    </section>

    <!-- Values Section -->
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-800 mb-12 text-center">Nilai-Nilai Kami</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <div class="text-4xl text-green-600 mb-4"><i class="fas fa-handshake"></i></div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Terpercaya</h3>
                    <p class="text-gray-600">
                        Kami menjamin kepercayaan pelanggan melalui transparansi, integritas, dan komitmen pada kualitas layanan.
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <div class="text-4xl text-green-600 mb-4"><i class="fas fa-star"></i></div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Berkualitas</h3>
                    <p class="text-gray-600">
                        Setiap buku yang kami jual adalah pilihan terbaik dari penerbit terpercaya dengan kualitas terjamin.
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <div class="text-4xl text-orange-600 mb-4"><i class="fas fa-rocket"></i></div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Inovatif</h3>
                    <p class="text-gray-600">
                        Kami terus berinovasi untuk memberikan pengalaman berbelanja terbaik dengan teknologi terkini.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div>
                    <p class="text-4xl font-bold text-green-600">10K+</p>
                    <p class="text-gray-600 text-sm mt-2">Pelanggan Puas</p>
                </div>
                <div>
                    <p class="text-4xl font-bold text-green-600">5K+</p>
                    <p class="text-gray-600 text-sm mt-2">Koleksi Buku</p>
                </div>
                <div>
                    <p class="text-4xl font-bold text-orange-600">100+</p>
                    <p class="text-gray-600 text-sm mt-2">Penerbit</p>
                </div>
                <div>
                    <p class="text-4xl font-bold text-green-600">24/7</p>
                    <p class="text-gray-600 text-sm mt-2">Customer Support</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-800 mb-12 text-center">Mengapa Memilih TokoBuku?</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-2xl mx-auto">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 text-2xl text-green-600"><i class="fas fa-check-circle"></i></div>
                    <div>
                        <h3 class="font-bold text-gray-800 mb-1">Harga Terjangkau</h3>
                        <p class="text-gray-600 text-sm">Kami menawarkan harga yang kompetitif dengan berbagai diskon menarik</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 text-2xl text-green-600"><i class="fas fa-check-circle"></i></div>
                    <div>
                        <h3 class="font-bold text-gray-800 mb-1">Pengiriman Cepat</h3>
                        <p class="text-gray-600 text-sm">Pengiriman ke seluruh Indonesia dalam 1-3 hari kerja</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 text-2xl text-green-600"><i class="fas fa-check-circle"></i></div>
                    <div>
                        <h3 class="font-bold text-gray-800 mb-1">Aman & Aman</h3>
                        <p class="text-gray-600 text-sm">Sistem pembayaran aman dan barang diasuransikan</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 text-2xl text-green-600"><i class="fas fa-check-circle"></i></div>
                    <div>
                        <h3 class="font-bold text-gray-800 mb-1">Garansi Uang Kembali</h3>
                        <p class="text-gray-600 text-sm">Jaminan 100% uang kembali jika tidak puas</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 text-2xl text-green-600"><i class="fas fa-check-circle"></i></div>
                    <div>
                        <h3 class="font-bold text-gray-800 mb-1">Koleksi Lengkap</h3>
                        <p class="text-gray-600 text-sm">Ribuan judul buku dari berbagai kategori dan genre</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 text-2xl text-green-600"><i class="fas fa-check-circle"></i></div>
                    <div>
                        <h3 class="font-bold text-gray-800 mb-1">Support 24/7</h3>
                        <p class="text-gray-600 text-sm">Tim customer service siap membantu kapan saja</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="bg-gradient-to-r from-green-600 to-green-600 text-white py-12">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">Siap Menemukan Buku Favorit Anda?</h2>
            <p class="text-lg mb-6">Jelajahi koleksi buku terbaik kami sekarang</p>
            <a href="catalog.php" class="inline-block bg-white text-green-600 px-8 py-3 rounded-lg font-bold hover:shadow-lg transition">
                <i class="fas fa-shopping-bag"></i> Belanja Sekarang
            </a>
        </div>
    </section>

    <?php require_once '../includes/footer.php'; ?>
</body>
</html>
