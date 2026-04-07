<?php
require_once '../config/config.php';
checkLogin();

$page_title = 'Checkout';
$user_id = $_SESSION['user_id'];

// Get user data
$user = $conn->query("SELECT * FROM users WHERE id=$user_id")->fetch_assoc();

// Get cart items
$cart_items = $conn->query("
    SELECT c.id, c.quantity, b.id as book_id, b.title, b.price
    FROM cart c
    JOIN books b ON c.book_id = b.id
    WHERE c.user_id = $user_id
");

if ($cart_items->num_rows == 0) {
    header("Location: cart.php");
    exit();
}

$subtotal = 0;
$items = [];
while ($item = $cart_items->fetch_assoc()) {
    $items[] = $item;
    $subtotal += $item['price'] * $item['quantity'];
}

$shipping = 14000;
$total = $subtotal + $shipping;
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
                <a href="cart.php" class="hover:text-green-600">Keranjang</a>
                <span>/</span>
                <span class="text-gray-800 font-semibold">Checkout</span>
            </div>
        </div>
    </div>

    <!-- Progress Steps -->
    <div class="bg-white border-b">
        <div class="container mx-auto px-4 py-6">
            <div class="flex justify-between max-w-md mx-auto">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center font-bold">1</div>
                    <span class="text-sm text-gray-600 mt-2">Keranjang</span>
                </div>
                <div class="flex-1 border-t border-gray-300 mx-4 mt-5"></div>
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center font-bold">2</div>
                    <span class="text-sm text-gray-600 mt-2">Checkout</span>
                </div>
                <div class="flex-1 border-t border-gray-300 mx-4 mt-5"></div>
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-bold">3</div>
                    <span class="text-sm text-gray-600 mt-2">Konfirmasi</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Checkout Form -->
            <div class="lg:col-span-2">
                <!-- Shipping Address -->
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-map-marker-alt text-green-600"></i> Alamat Pengiriman
                    </h3>
                    <form method="POST" action="process_checkout.php">
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Nama Lengkap</label>
                            <input type="text" name="full_name" required value="<?php echo htmlspecialchars($user['full_name']); ?>"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">No. Telepon</label>
                            <input type="tel" name="phone" required value="<?php echo htmlspecialchars($user['phone']); ?>"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Email</label>
                            <input type="email" name="email" required value="<?php echo htmlspecialchars($user['email']); ?>"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2">Alamat Lengkap</label>
                            <textarea name="address" required rows="4"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"><?php echo htmlspecialchars($user['address']); ?></textarea>
                        </div>

                        <!-- Payment Method -->
                        <div class="bg-gray-50 rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">
                                <i class="fas fa-credit-card text-green-600"></i> Metode Pembayaran
                            </h3>

                            <div class="mb-4">
                                <label class="flex items-center p-4 border-2 border-green-600 rounded-lg cursor-pointer bg-green-50">
                                    <input type="radio" name="payment_method" value="cod" checked class="w-4 h-4 text-green-600">
                                    <div class="ml-4">
                                        <h4 class="font-semibold text-gray-800">Bank Digital</h4>
                                        <p class="text-sm text-gray-600">Pembayaran dilakukan dengan akun bank anda</p>
                                    </div>
                                </label>
                            </div>

                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm text-blue-700">
                                <i class="fas fa-info-circle"></i> Pastikan alamat dan nomor telepon sudah benar sebelum checkout
                            </div>
                        </div>

                        <!-- Items Summary in Form -->
                        <input type="hidden" name="total_price" value="<?php echo $total; ?>">

                        <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-green-600 text-white px-6 py-3 rounded-lg font-bold hover:shadow-lg transition text-lg">
                            <i class="fas fa-check-circle"></i> Lanjutkan ke Pembayaran
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-20">
                    <h3 class="text-xl font-bold text-gray-800 mb-6"><i class="fas fa-receipt"></i> Ringkasan Pesanan</h3>

                    <div class="space-y-4 mb-6 pb-6 border-b max-h-64 overflow-y-auto">
                        <?php foreach ($items as $item): ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600"><?php echo $item['title']; ?> x<?php echo $item['quantity']; ?></span>
                            <span class="font-semibold">Rp <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="space-y-4 mb-6 pb-6 border-b">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-semibold">Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Biaya Pengiriman</span>
                            <span class="font-semibold">Rp <?php echo number_format($shipping, 0, ',', '.'); ?></span>
                        </div>
                    </div>

                    <div class="flex justify-between mb-6 text-lg">
                        <span class="font-bold text-gray-800">Total Bayar</span>
                        <span class="font-bold text-green-600 text-2xl">Rp <?php echo number_format($total, 0, ',', '.'); ?></span>
                    </div>

                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <p class="text-green-700 text-sm text-center">
                            <i class="fas fa-lock"></i> Transaksi Aman & Terpercaya
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once '../includes/footer.php'; ?>
</body>
</html>
