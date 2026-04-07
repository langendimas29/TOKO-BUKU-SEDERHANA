<?php
if (!isset($conn)) {
    require_once 'config/config.php';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - TokoBuku' : 'TokoBuku'; ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary: #22c55e;
            --primary-dark: #16a34a;
            --secondary: #22c55e;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #0ea5e9;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background-color: var(--primary);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .btn-primary {
            background-color: var(--primary);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
        }
        
        .card {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border: none;
        }
        
        .book-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="navbar text-white sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-book text-2xl"></i>
                    <a href="<?php echo BASE_URL; ?>" class="text-2xl font-bold">TokoBuku</a>
                </div>
                
                <div class="hidden md:flex items-center space-x-6">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="<?php echo BASE_URL; ?>pages/home.php" class="hover:text-gray-200">
                            <i class="fas fa-home"></i> Home
                        </a>
                        <a href="<?php echo BASE_URL; ?>pages/catalog.php" class="hover:text-gray-200">
                            <i class="fas fa-book"></i> Katalog
                        </a>
                        <a href="<?php echo BASE_URL; ?>pages/about.php" class="hover:text-gray-200">
                            <i class="fas fa-info-circle"></i> Tentang Kami
                        </a>
                        <a href="<?php echo BASE_URL; ?>pages/cart.php" class="hover:text-gray-200 relative">
                            <i class="fas fa-shopping-cart"></i> Keranjang
                            <?php 
                            // Show cart count
                            $user_id = $_SESSION['user_id'];
                            $cart_result = $conn->query("SELECT COUNT(*) as count FROM cart WHERE user_id = $user_id");
                            $cart_count = $cart_result->fetch_assoc()['count'];
                            if ($cart_count > 0):
                            ?>
                            <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                <?php echo $cart_count; ?>
                            </span>
                            <?php endif; ?>
                        </a>
                        
                        <?php if ($_SESSION['role'] == 'admin'): ?>
                        <a href="<?php echo BASE_URL; ?>admin/dashboard.php" class="hover:text-gray-200">
                            <i class="fas fa-tachometer-alt"></i> Admin
                        </a>
                        <?php endif; ?>
                        
                        <div class="relative group">
                            <button class="hover:text-gray-200 flex items-center space-x-1">
                                <i class="fas fa-user"></i>
                                <span><?php echo $_SESSION['username']; ?></span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div class="hidden group-hover:block absolute right-0 bg-white text-gray-800 rounded-md shadow-lg py-2 w-40">
                                <a href="<?php echo BASE_URL; ?>pages/profile.php" class="block px-4 py-2 hover:bg-gray-100">
                                    <i class="fas fa-user-circle"></i> Profile
                                </a>
                                <a href="<?php echo BASE_URL; ?>pages/orders.php" class="block px-4 py-2 hover:bg-gray-100">
                                    <i class="fas fa-list"></i> Pesanan Saya
                                </a>
                                <hr class="my-2">
                                <a href="<?php echo BASE_URL; ?>pages/logout.php" class="block px-4 py-2 hover:bg-gray-100 text-red-600">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo BASE_URL; ?>pages/home.php" class="hover:text-gray-200">
                            <i class="fas fa-home"></i> Home
                        </a>
                        <a href="<?php echo BASE_URL; ?>pages/about.php" class="hover:text-gray-200">
                            <i class="fas fa-info-circle"></i> Tentang Kami
                        </a>
                        <a href="<?php echo BASE_URL; ?>pages/login.php" class="bg-white text-green-600 px-6 py-2 rounded-full font-semibold hover:bg-gray-100">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    <?php endif; ?>
                </div>
                
                <!-- Mobile Menu Button -->
                <button class="md:hidden text-white" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden bg-green-700 border-t border-green-600">
            <div class="container mx-auto px-4 py-4 space-y-2">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo BASE_URL; ?>pages/home.php" class="block px-4 py-2 text-white hover:bg-green-600 rounded">
                        <i class="fas fa-home"></i> Home
                    </a>
                    <a href="<?php echo BASE_URL; ?>pages/catalog.php" class="block px-4 py-2 text-white hover:bg-green-600 rounded">
                        <i class="fas fa-book"></i> Katalog
                    </a>
                    <a href="<?php echo BASE_URL; ?>pages/about.php" class="block px-4 py-2 text-white hover:bg-green-600 rounded">
                        <i class="fas fa-info-circle"></i> Tentang Kami
                    </a>
                    <a href="<?php echo BASE_URL; ?>pages/cart.php" class="block px-4 py-2 text-white hover:bg-green-600 rounded">
                        <i class="fas fa-shopping-cart"></i> Keranjang
                    </a>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                    <a href="<?php echo BASE_URL; ?>admin/dashboard.php" class="block px-4 py-2 text-white hover:bg-green-600 rounded">
                        <i class="fas fa-tachometer-alt"></i> Admin
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo BASE_URL; ?>pages/profile.php" class="block px-4 py-2 text-white hover:bg-green-600 rounded">
                        <i class="fas fa-user-circle"></i> Profile
                    </a>
                    <a href="<?php echo BASE_URL; ?>pages/orders.php" class="block px-4 py-2 text-white hover:bg-green-600 rounded">
                        <i class="fas fa-list"></i> Pesanan Saya
                    </a>
                    <a href="<?php echo BASE_URL; ?>pages/logout.php" class="block px-4 py-2 text-red-300 hover:bg-green-600 rounded">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>pages/home.php" class="block px-4 py-2 text-white hover:bg-green-600 rounded">
                        <i class="fas fa-home"></i> Home
                    </a>
                    <a href="<?php echo BASE_URL; ?>pages/about.php" class="block px-4 py-2 text-white hover:bg-green-600 rounded">
                        <i class="fas fa-info-circle"></i> Tentang Kami
                    </a>
                    <a href="<?php echo BASE_URL; ?>pages/login.php" class="block px-4 py-2 bg-white text-green-600 rounded font-semibold hover:bg-gray-100">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }
    </script>
</body>
</html>
