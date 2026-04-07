<?php
require_once '../config/config.php';
checkAdmin();

$page_title = 'Kelola Pesan';

// Handle delete message
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action == 'delete') {
        $id = intval($_POST['id']);
        $delete = $conn->query("DELETE FROM messages WHERE id=$id");
        header("Location: messages.php?success=Pesan berhasil dihapus");
        exit();
    } elseif ($action == 'mark_read') {
        $id = intval($_POST['id']);
        $update = $conn->query("UPDATE messages SET status='read' WHERE id=$id");
        header("Location: messages.php?success=Pesan ditandai sudah dibaca");
        exit();
    }
}

// Get all messages
$messages = $conn->query("SELECT m.*, u.username, u.email FROM messages m LEFT JOIN users u ON m.user_id=u.id ORDER BY m.created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - TokoBuku</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="sidebar text-white w-64 shadow-lg overflow-y-auto">
            <div class="p-6 border-b border-white border-opacity-20">
                <h1 class="text-2xl font-bold flex items-center space-x-2">
                    <i class="fas fa-book"></i>
                    <span>TokoBuku</span>
                </h1>
                <p class="text-sm text-gray-200 mt-1">Admin Panel</p>
            </div>

            <nav class="p-6 space-y-2">
                <a href="dashboard.php" class="block px-4 py-3 rounded-lg hover:bg-white hover:bg-opacity-20 transition">
                    <i class="fas fa-tachometer-alt w-5"></i> Dashboard
                </a>
                <a href="categories.php" class="block px-4 py-3 rounded-lg hover:bg-white hover:bg-opacity-20 transition">
                    <i class="fas fa-list w-5"></i> Kelola Kategori
                </a>
                <a href="books.php" class="block px-4 py-3 rounded-lg hover:bg-white hover:bg-opacity-20 transition">
                    <i class="fas fa-book w-5"></i> Kelola Buku
                </a>
                <a href="users.php" class="block px-4 py-3 rounded-lg hover:bg-white hover:bg-opacity-20 transition">
                    <i class="fas fa-users w-5"></i> Pantau User
                </a>
                <a href="orders.php" class="block px-4 py-3 rounded-lg hover:bg-white hover:bg-opacity-20 transition">
                    <i class="fas fa-shopping-cart w-5"></i> Pantau Pesanan
                </a>
                <a href="messages.php" class="block px-4 py-3 rounded-lg bg-white bg-opacity-20 font-semibold">
                    <i class="fas fa-envelope w-5"></i> Pesan Masuk
                </a>
            </nav>

            <div class="border-t border-white border-opacity-20 p-6 mt-auto">
                <a href="../pages/logout.php" class="block w-full text-center bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-lg transition">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <div class="bg-white shadow px-8 py-4">
                <h2 class="text-2xl font-bold text-gray-800"><i class="fas fa-envelope text-green-600"></i> Pesan Masuk</h2>
            </div>

            <div class="flex-1 overflow-auto p-8">
                <?php if (isset($_GET['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex justify-between items-center">
                    <span><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_GET['success']); ?></span>
                    <button onclick="this.parentElement.style.display='none'" class="text-green-700">&times;</button>
                </div>
                <?php endif; ?>

                <!-- Messages Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <?php if ($messages->num_rows > 0): ?>
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">No</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Dari</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Email</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Subjek</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Tanggal</th>
                                <th class="px-6 py-4 text-center text-gray-700 font-semibold">Status</th>
                                <th class="px-6 py-4 text-center text-gray-700 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            while ($msg = $messages->fetch_assoc()) {
                                $status_badge = $msg['status'] == 'read' ? 
                                    '<span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm">Dibaca</span>' :
                                    '<span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">Baru</span>';
                                    
                                echo "<tr class='border-t hover:bg-gray-50 " . ($msg['status'] == 'unread' ? 'bg-blue-50' : '') . "'>
                                    <td class='px-6 py-4'>{$no}</td>
                                    <td class='px-6 py-4 font-semibold text-gray-800'>" . htmlspecialchars($msg['username'] ?? 'Guest') . "</td>
                                    <td class='px-6 py-4'><a href='mailto:{$msg['email']}' class='text-green-600 hover:underline'>" . htmlspecialchars($msg['email'] ?? '-') . "</a></td>
                                    <td class='px-6 py-4'>" . htmlspecialchars(substr($msg['subject'], 0, 40)) . (strlen($msg['subject']) > 40 ? '...' : '') . "</td>
                                    <td class='px-6 py-4 text-sm text-gray-600'>" . date('d M Y H:i', strtotime($msg['created_at'])) . "</td>
                                    <td class='px-6 py-4 text-center'>{$status_badge}</td>
                                    <td class='px-6 py-4 text-center space-x-2'>
                                        <button onclick=\"viewMessage({$msg['id']})\" class='bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition'>
                                            <i class='fas fa-eye'></i>
                                        </button>
                                        <form method='POST' class='inline' onsubmit=\"return confirm('Yakin ingin menghapus?')\">
                                            <input type='hidden' name='action' value='delete'>
                                            <input type='hidden' name='id' value='{$msg['id']}'>
                                            <button type='submit' class='bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition'>
                                                <i class='fas fa-trash'></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>";
                                $no++;
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <div class="p-8 text-center text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-4 block" style="color: #d1d5db;"></i>
                        <p class="text-lg">Belum ada pesan masuk</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Message Detail Modal -->
    <div id="messageModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-y-auto">
        <div class="bg-white rounded-lg p-8 max-w-2xl w-full mx-4 my-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-2xl font-bold text-gray-800" id="modalSubject">Subjek Pesan</h3>
                <button type="button" onclick="closeModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>
            
            <div class="space-y-4 mb-6">
                <div>
                    <p class="text-gray-600 text-sm">Dari</p>
                    <p class="text-gray-800 font-semibold" id="modalFrom">-</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Email</p>
                    <p class="text-gray-800 font-semibold" id="modalEmail">-</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Tanggal</p>
                    <p class="text-gray-800 font-semibold" id="modalDate">-</p>
                </div>
            </div>

            <hr class="my-4">

            <div>
                <p class="text-gray-600 text-sm mb-2">Pesan</p>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200" id="modalMessage">
                    -
                </div>
            </div>

            <div class="flex space-x-4 mt-6">
                <button type="button" onclick="markAsRead()" class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition font-semibold">
                    <i class="fas fa-check"></i> Tandai Sudah Dibaca
                </button>
                <button type="button" onclick="closeModal()" class="flex-1 bg-gray-400 text-white px-4 py-2 rounded-lg hover:bg-gray-500 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        const messages_data = <?php 
            // Get all messages as JSON for JavaScript
            $messages->data_seek(0);
            $msgs = [];
            while ($m = $messages->fetch_assoc()) {
                $msgs[] = $m;
            }
            echo json_encode($msgs);
        ?>;

        let current_message_id = null;

        function viewMessage(id) {
            const msg = messages_data.find(m => m.id == id);
            if (!msg) return;

            current_message_id = id;
            document.getElementById('modalSubject').textContent = msg.subject;
            document.getElementById('modalFrom').textContent = msg.username || 'Guest';
            document.getElementById('modalEmail').textContent = msg.email || '-';
            document.getElementById('modalDate').textContent = new Date(msg.created_at).toLocaleString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            document.getElementById('modalMessage').textContent = msg.message;
            
            document.getElementById('messageModal').classList.remove('hidden');

            // Mark as read
            if (msg.status == 'unread') {
                fetch('messages.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=mark_read&id=' + id
                }).then(() => {
                    location.reload();
                });
            }
        }

        function closeModal() {
            document.getElementById('messageModal').classList.add('hidden');
        }

        function markAsRead() {
            if (current_message_id) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type='hidden' name='action' value='mark_read'>
                    <input type='hidden' name='id' value='${current_message_id}'>
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>
