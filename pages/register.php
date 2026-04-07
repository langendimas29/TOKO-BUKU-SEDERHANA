<?php
require_once '../config/config.php';

if (isset($_SESSION['user_id'])) {
    redirect('home.php');
}

$page_title = 'Daftar Akun';
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
<body class="min-h-screen flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <i class="fas fa-book text-4xl text-green-600 mb-2"></i>
                <h1 class="text-3xl font-bold text-gray-800">TokoBuku</h1>
                <p class="text-gray-600 mt-2">Daftar Akun Baru</p>
            </div>

            <?php if (isset($_GET['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="process_register.php">
                <div class="mb-4">
                    <label for="username" class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-user"></i> Username
                    </label>
                    <input type="text" id="username" name="username" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"
                           placeholder="Masukkan username">
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-envelope"></i> Email
                    </label>
                    <input type="email" id="email" name="email" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"
                           placeholder="Masukkan email">
                </div>

                <div class="mb-4">
                    <label for="full_name" class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-id-card"></i> Nama Lengkap
                    </label>
                    <input type="text" id="full_name" name="full_name" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"
                           placeholder="Masukkan nama lengkap">
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <input type="password" id="password" name="password" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"
                           placeholder="Masukkan password (min. 6 karakter)">
                </div>

                <div class="mb-6">
                    <label for="confirm_password" class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-lock"></i> Konfirmasi Password
                    </label>
                    <input type="password" id="confirm_password" name="confirm_password" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"
                           placeholder="Konfirmasi password">
                </div>

                <button type="submit" class="w-full bg-green-600 text-white font-bold py-2 rounded-lg hover:bg-green-700 transition mb-4">
                    <i class="fas fa-user-plus"></i> Daftar
                </button>
            </form>

            <div class="text-center">
                <p class="text-gray-600">Sudah punya akun? 
                    <a href="login.php" class="text-green-600 font-semibold hover:underline">
                        <i class="fas fa-sign-in-alt"></i> Login di sini
                    </a>
                </p>
            </div>

            <div class="mt-6 pt-6 border-t">
                <a href="../index.php" class="text-center block text-gray-600 hover:text-green-600">
                    <i class="fas fa-arrow-left"></i> Kembali ke Home
                </a>
            </div>
        </div>
    </div>
</body>
</html>
