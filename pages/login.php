<?php
require_once '../config/config.php';

if (isset($_SESSION['user_id'])) {
    redirect('pages/home.php');
}

$page_title = 'Login';
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
                <p class="text-gray-600 mt-2">Login Akun</p>
            </div>

            <?php if (isset($_GET['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
            <?php endif; ?>

            <?php if (isset($_GET['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="process_login.php">
                <div class="mb-4">
                    <label for="username" class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-user"></i> Username atau Email
                    </label>
                    <input type="text" id="username" name="username" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"
                           placeholder="Masukkan username atau email">
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <input type="password" id="password" name="password" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"
                           placeholder="Masukkan password">
                </div>

                <div class="mb-6 flex items-center">
                    <input type="checkbox" id="remember" name="remember" 
                           class="w-4 h-4 text-green-600 border-gray-300 rounded">
                    <label for="remember" class="ml-2 text-gray-700">Ingat saya</label>
                </div>

                <button type="submit" class="w-full bg-green-600 text-white font-bold py-2 rounded-lg hover:bg-green-700 transition mb-4">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>


            <div class="border-t pt-6">
                <p class="text-center text-gray-600 mb-4">Belum punya akun?</p>
                <a href="register.php" class="block w-full text-center bg-gray-100 text-gray-800 font-bold py-2 rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-user-plus"></i> Daftar Sekarang
                </a>
            </div>

            <div class="mt-6 pt-6 border-t">
                <a href="../index.php" class="text-center block text-gray-600 hover:text-green-600">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</body>
</html>
