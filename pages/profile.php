<?php
require_once '../config/config.php';
checkLogin();

$page_title = 'Profile';
$user_id = $_SESSION['user_id'];

// Get user data
$user = $conn->query("SELECT * FROM users WHERE id=$user_id")->fetch_assoc();

// Handle update profile
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = escape($_POST['full_name']);
    $phone = escape($_POST['phone']);
    $address = escape($_POST['address']);
    
    $update = $conn->query("UPDATE users SET full_name='$full_name', phone='$phone', address='$address' WHERE id=$user_id");
    
    if ($update) {
        $success = "Profile berhasil diperbarui";
        $_SESSION['full_name'] = $full_name;
        $user['full_name'] = $full_name;
        $user['phone'] = $phone;
        $user['address'] = $address;
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

    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center space-x-2 text-gray-600">
                <a href="home.php" class="hover:text-green-600"><i class="fas fa-home"></i></a>
                <span>/</span>
                <span class="text-gray-800 font-semibold">Profile</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Sidebar -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 text-center sticky top-20">
                    <div class="w-20 h-20 bg-gradient-to-br from-green-400 to-green-400 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user text-white text-4xl"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800"><?php echo htmlspecialchars($user['full_name']); ?></h2>
                    <p class="text-gray-600 text-sm">@<?php echo htmlspecialchars($user['username']); ?></p>
                    <p class="text-gray-600 text-sm mt-1"><?php echo htmlspecialchars($user['email']); ?></p>
                    
                    <div class="mt-6 pt-6 border-t space-y-2">
                        <a href="orders.php" class="block text-green-600 hover:text-green-800 font-semibold">
                            <i class="fas fa-shopping-bag"></i> Pesanan Saya
                        </a>
                        <a href="contact.php" class="block text-green-600 hover:text-green-800 font-semibold">
                            <i class="fas fa-envelope"></i> Hubungi Kami
                        </a>
                        <a href="logout.php" class="block text-red-600 hover:text-red-800 font-semibold mt-4">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profile Form -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h1 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-user-circle"></i> Informasi Profil
                    </h1>

                    <?php if (isset($success)): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                    </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2">Username</label>
                            <input type="text" value="<?php echo htmlspecialchars($user['username']); ?>" disabled 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600">
                            <p class="text-gray-500 text-sm mt-1">Username tidak dapat diubah</p>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2">Email</label>
                            <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600">
                            <p class="text-gray-500 text-sm mt-1">Email tidak dapat diubah</p>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2">Nama Lengkap</label>
                            <input type="text" name="full_name" required value="<?php echo htmlspecialchars($user['full_name']); ?>" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2">No. Telepon</label>
                            <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"
                                   placeholder="Contoh: 08123456789">
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2">Alamat</label>
                            <textarea name="address" rows="4" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"
                                      placeholder="Masukkan alamat lengkap Anda"><?php echo htmlspecialchars($user['address']); ?></textarea>
                        </div>

                        <button type="submit" class="bg-gradient-to-r from-green-600 to-green-600 text-white px-8 py-3 rounded-lg font-bold hover:shadow-lg transition">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </form>

                    <!-- Account Info -->
                    <div class="mt-12 pt-12 border-t">
                        <h2 class="text-xl font-bold text-gray-800 mb-6">Informasi Akun</h2>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-600 text-sm">Tanggal Registrasi</p>
                                <p class="font-bold text-gray-800"><?php echo date('d F Y', strtotime($user['created_at'])); ?></p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-600 text-sm">Terakhir Diperbarui</p>
                                <p class="font-bold text-gray-800"><?php echo date('d F Y H:i', strtotime($user['updated_at'])); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once '../includes/footer.php'; ?>
</body>
</html>
