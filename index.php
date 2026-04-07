<?php
require_once 'config/config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: pages/home.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TokoBuku - Toko Buku Online Terpercaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-green-600 text-white sticky top-0 z-50 shadow-lg">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-2 text-2xl font-bold">
                <i class="fas fa-book"></i>
                <span>TokoBuku</span>
            </div>
            <div class="hidden md:flex items-center space-x-6">
                <a href="pages/about.php" class="text-white hover:text-gray-200">
                    <i class="fas fa-info-circle"></i> Tentang Kami
                </a>
                <a href="pages/login.php" class="bg-white text-green-600 px-6 py-2 rounded-full font-semibold hover:bg-gray-100">
                    Login
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-green-600 text-white py-24">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6">
                Selamat Datang di <br>
                <i class="fas fa-book-open text-yellow-300"></i> TokoBuku
            </h1>
            <p class="text-xl md:text-2xl mb-8">
                Temukan koleksi buku terbaik dari berbagai kategori dengan harga terjangkau
            </p>
            <div class="flex flex-col md:flex-row gap-4 justify-center">
                <a href="pages/register.php" class="bg-white text-green-600 px-8 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition">
                    <i class="fas fa-user-plus"></i> Daftar Gratis
                </a>
                <a href="pages/login.php" class="border-2 border-white text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-white hover:text-green-600 transition">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center text-gray-800 mb-16">
                <i class="fas fa-star text-yellow-400"></i> Mengapa Memilih TokoBuku?
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center hover:shadow-lg transition p-6 rounded-lg">
                    <div class="text-5xl text-green-600 mb-4"><i class="fas fa-shipping-fast"></i></div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Pengiriman Cepat</h3>
                    <p class="text-gray-600">Pengiriman ke seluruh Indonesia dalam 1-3 hari kerja</p>
                </div>

                <div class="text-center hover:shadow-lg transition p-6 rounded-lg">
                    <div class="text-5xl text-green-600 mb-4"><i class="fas fa-lock"></i></div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Aman & Terpercaya</h3>
                    <p class="text-gray-600">Sistem pembayaran aman dengan enkripsi tingkat tinggi</p>
                </div>

                <div class="text-center hover:shadow-lg transition p-6 rounded-lg">
                    <div class="text-5xl text-orange-600 mb-4"><i class="fas fa-book"></i></div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Koleksi Lengkap</h3>
                    <p class="text-gray-600">Ribuan judul buku dari berbagai kategori dan genre</p>
                </div>

                <div class="text-center hover:shadow-lg transition p-6 rounded-lg">
                    <div class="text-5xl text-green-600 mb-4"><i class="fas fa-headset"></i></div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Layanan Pelanggan 24/7</h3>
                    <p class="text-gray-600">Tim support siap membantu kapan saja</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-green-600 text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">Siap Mulai Berbelanja?</h2>
            <p class="text-lg mb-8">Bergabunglah dengan ribuan pelanggan puas kami</p>
            <a href="pages/register.php" class="inline-block bg-white text-green-600 px-10 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition">
                <i class="fas fa-user-plus"></i> Daftar Sekarang
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12">
        <div class="container mx-auto px-4">
            <p class="text-center">&copy; 2026 TokoBuku. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
