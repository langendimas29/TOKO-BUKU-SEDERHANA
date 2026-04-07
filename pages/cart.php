<?php
require_once '../config/config.php';
checkLogin();

$page_title = 'Keranjang Belanja';
$user_id = $_SESSION['user_id'];

// Handle update quantity or remove
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'update') {
            $cart_id = intval($_POST['cart_id']);
            $quantity = intval($_POST['quantity']);
            
            if ($quantity <= 0) {
                $conn->query("DELETE FROM cart WHERE id=$cart_id AND user_id=$user_id");
            } else {
                $conn->query("UPDATE cart SET quantity=$quantity WHERE id=$cart_id AND user_id=$user_id");
            }
            header("Location: cart.php?success=Keranjang diperbarui");
            exit();
        } elseif ($_POST['action'] == 'remove') {
            $cart_id = intval($_POST['cart_id']);
            $conn->query("DELETE FROM cart WHERE id=$cart_id AND user_id=$user_id");
            header("Location: cart.php?success=Item dihapus dari keranjang");
            exit();
        }
    }
}

// Get cart items
$cart_items = $conn->query("
    SELECT c.id, c.quantity, b.id as book_id, b.title, b.price, b.stock, b.image_url
    FROM cart c
    JOIN books b ON c.book_id = b.id
    WHERE c.user_id = $user_id
    ORDER BY c.created_at DESC
");
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
                <span class="text-gray-800 font-semibold">Keranjang Belanja</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <?php if (isset($_GET['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex justify-between items-center">
            <span><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_GET['success']); ?></span>
            <button onclick="this.parentElement.style.display='none'" class="text-green-700">&times;</button>
        </div>
        <?php endif; ?>

        <?php if (count($items) > 0): ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg">
                    <div class="bg-gradient-to-r from-green-600 to-green-600 text-white px-6 py-4">
                        <h2 class="text-2xl font-bold"><i class="fas fa-shopping-cart"></i> Keranjang Belanja Anda</h2>
                    </div>

                    <div class="divide-y">
                        <?php foreach ($items as $item): ?>
                        <div class="p-6 flex gap-4">
                            <div class="w-24 h-32 bg-gray-300 rounded flex-shrink-0 flex items-center justify-center overflow-hidden">
                                <?php 
                                $image_path = __DIR__ . '/../public/images/' . $item['image_url'];
                                if (!empty($item['image_url']) && file_exists($image_path)):
                                ?>
                                    <img src="/public/images/<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <i class="fas fa-book text-white text-3xl"></i>
                                <?php endif; ?>
                            </div>

                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-800"><?php echo $item['title']; ?></h3>
                                <p class="text-gray-600 text-sm mb-2">Harga: <strong>Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></strong></p>
                                
                                <div class="flex items-center space-x-4">
                                    <form method="POST" class="flex items-center space-x-2">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                        <button type="button" onclick="decreaseQty(this)" class="bg-gray-200 hover:bg-gray-300 text-gray-800 w-8 h-8 rounded flex items-center justify-center">
                                            <i class="fas fa-minus text-xs"></i>
                                        </button>
                                        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['stock']; ?>" 
                                               class="w-12 text-center border border-gray-300 rounded py-1" onchange="this.form.submit()">
                                        <button type="button" onclick="increaseQty(this, <?php echo $item['stock']; ?>)" class="bg-gray-200 hover:bg-gray-300 text-gray-800 w-8 h-8 rounded flex items-center justify-center">
                                            <i class="fas fa-plus text-xs"></i>
                                        </button>
                                    </form>

                                    <form method="POST" class="ml-auto">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>

                                <p class="text-sm text-gray-500 mt-2">Stok tersedia: <?php echo $item['stock']; ?></p>
                            </div>

                            <div class="text-right">
                                <p class="text-2xl font-bold text-gray-800">Rp <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <a href="catalog.php" class="text-green-600 hover:text-green-800 font-semibold">
                        <i class="fas fa-arrow-left"></i> Lanjut Belanja
                    </a>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-20">
                    <h3 class="text-xl font-bold text-gray-800 mb-6"><i class="fas fa-receipt"></i> Ringkasan Pesanan</h3>

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
                        <span class="font-bold text-gray-800">Total</span>
                        <span class="font-bold text-green-600">Rp <?php echo number_format($total, 0, ',', '.'); ?></span>
                    </div>

                    <a href="checkout.php" class="w-full block text-center bg-gradient-to-r from-green-600 to-green-600 text-white px-6 py-3 rounded-lg font-bold hover:shadow-lg transition">
                        <i class="fas fa-credit-card"></i> Lanjut ke Checkout
                    </a>

                    <div class="mt-6 pt-6 border-t">
                        <div class="flex items-center space-x-2 text-green-600 text-sm mb-2">
                            <i class="fas fa-check-circle"></i>
                            <span>Garansi Uang Kembali 100%</span>
                        </div>
                        <div class="flex items-center space-x-2 text-green-600 text-sm">
                            <i class="fas fa-check-circle"></i>
                            <span>Pembayaran Aman</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="bg-white rounded-lg shadow-lg p-12 text-center">
            <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Keranjang Belanja Kosong</h2>
            <p class="text-gray-600 mb-8">Anda belum menambahkan buku apapun ke keranjang</p>
            <a href="catalog.php" class="inline-block bg-gradient-to-r from-green-600 to-green-600 text-white px-8 py-3 rounded-lg font-bold hover:shadow-lg transition">
                <i class="fas fa-shopping-bag"></i> Mulai Belanja
            </a>
        </div>
        <?php endif; ?>
    </div>

    <?php require_once '../includes/footer.php'; ?>

    <script>
        function increaseQty(btn, max) {
            const input = btn.parentElement.querySelector('input[name="quantity"]');
            if (parseInt(input.value) < max) {
                input.value = parseInt(input.value) + 1;
                input.dispatchEvent(new Event('change'));
            }
        }

        function decreaseQty(btn) {
            const input = btn.parentElement.querySelector('input[name="quantity"]');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
                input.dispatchEvent(new Event('change'));
            }
        }
    </script>
</body>
</html>
