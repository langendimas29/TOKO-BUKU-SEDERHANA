<?php
require_once '../config/config.php';

$page_title = 'Hubungi Kami';

// Handle contact form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject = escape($_POST['subject']);
    $message = escape($_POST['message']);
    
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        $insert = $conn->query("INSERT INTO messages (user_id, subject, message) VALUES ($user_id, '$subject', '$message')");
        
        if ($insert) {
            $success = "Pesan Anda berhasil dikirim. Kami akan segera meresponnya.";
        } else {
            $error = "Gagal mengirim pesan. Silakan coba lagi.";
        }
    }
}
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
                <i class="fas fa-envelope text-yellow-300"></i> Hubungi Kami
            </h1>
            <p class="text-xl">Kami siap membantu menjawab pertanyaan Anda</p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
            <!-- Contact Info -->
            <div class="lg:col-span-1">
                <div class="space-y-6">
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div class="flex items-center space-x-4">
                            <div class="text-3xl text-green-600"><i class="fas fa-map-marker-alt"></i></div>
                            <div>
                                <h3 class="font-bold text-gray-800">Lokasi</h3>
                                <p class="text-gray-600 text-sm">Jl. Buku No. 123, Jakarta</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div class="flex items-center space-x-4">
                            <div class="text-3xl text-green-600"><i class="fas fa-phone"></i></div>
                            <div>
                                <h3 class="font-bold text-gray-800">Telepon</h3>
                                <p class="text-gray-600 text-sm">(021) 1234-5678</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div class="flex items-center space-x-4">
                            <div class="text-3xl text-orange-600"><i class="fas fa-envelope"></i></div>
                            <div>
                                <h3 class="font-bold text-gray-800">Email</h3>
                                <p class="text-gray-600 text-sm">info@tokobuku.com</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div class="flex items-center space-x-4">
                            <div class="text-3xl text-green-600"><i class="fas fa-clock"></i></div>
                            <div>
                                <h3 class="font-bold text-gray-800">Jam Operasional</h3>
                                <p class="text-gray-600 text-sm">Senin - Jumat: 09:00 - 17:00 WIB</p>
                                <p class="text-gray-600 text-sm">Sabtu: 10:00 - 15:00 WIB</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Kirim Pesan Kepada Kami</h2>

                    <?php if (isset($success)): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                    </div>
                    <?php endif; ?>

                    <?php if (isset($error)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                    </div>
                    <?php endif; ?>

                    <?php if (!isset($_SESSION['user_id'])): ?>
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6">
                        <i class="fas fa-info-circle"></i> 
                        Silakan <a href="login.php" class="font-bold underline">login</a> untuk mengirim pesan
                    </div>
                    <?php else: ?>

                    <form method="POST" action="">
                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2">Nama</label>
                            <input type="text" value="<?php echo htmlspecialchars($_SESSION['full_name']); ?>" disabled
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2">Subjek</label>
                            <input type="text" name="subject" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"
                                   placeholder="Contoh: Pertanyaan tentang pengiriman">
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2">Pesan</label>
                            <textarea name="message" rows="6" required
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"
                                      placeholder="Tulis pesan Anda di sini..."></textarea>
                        </div>

                        <button type="submit" class="w-full bg-green-600 text-white px-6 py-3 rounded-lg font-bold hover:shadow-lg transition">
                            <i class="fas fa-paper-plane"></i> Kirim Pesan
                        </button>
                    </form>

                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-8">Pertanyaan Umum</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="border-l-4 border-green-600 pl-4">
                    <h3 class="font-bold text-gray-800 mb-2"><i class="fas fa-question-circle text-green-600"></i> Bagaimana cara memesan?</h3></h3>
                    <p class="text-gray-600 text-sm">
                        Anda dapat menjelajahi katalog kami, memilih buku, dan menambahkannya ke keranjang. 
                        Kemudian lanjutkan ke checkout untuk menyelesaikan pesanan.
                    </p>
                </div>

                <div class="border-l-4 border-green-600 pl-4">
                    <h3 class="font-bold text-gray-800 mb-2"><i class="fas fa-question-circle text-green-600"></i> Berapa lama pengiriman?</h3></h3>
                    <p class="text-gray-600 text-sm">
                        Pengiriman biasanya memakan waktu 1-3 hari kerja tergantung lokasi Anda.
                    </p>
                </div>


                <div class="border-l-4 border-green-600 pl-4">
                    <h3 class="font-bold text-gray-800 mb-2"><i class="fas fa-question-circle text-green-600"></i> Bagaimana jika barang rusak?</h3></h3>
                    <p class="text-gray-600 text-sm">
                        Hubungi kami dalam 24 jam setelah menerima barang. Kami akan membantu Anda dengan penuh tanggung jawab.
                    </p>
                </div>

            </div>
        </div>
    </div>

    <?php require_once '../includes/footer.php'; ?>
</body>
</html>
