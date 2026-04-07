<?php
// Footer template
?>
    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 mt-12 py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    <h3 class="text-white font-bold text-lg mb-4 flex items-center space-x-2">
                        <i class="fas fa-book text-green-500"></i>
                        <span>TokoBuku</span>
                    </h3>
                    <p class="text-sm">Toko buku online terpercaya dengan koleksi lengkap berbagai Genre.</p>
                </div>
                
                
                
                <div>
                    <h4 class="text-white font-bold mb-4"><i class="fas fa-phone text-green-500 mr-2"></i> Kontak</h4>
                    <ul class="space-y-2 text-sm">
                        <li><i class="fas fa-phone"></i> 0856 7616 261</li>
                        <li><i class="fas fa-envelope"></i> info@tokobuku.com</li>
                        <li><i class="fas fa-map-marker-alt"></i> Jl. Nanas II Jakarta</li>
                    </ul>
                </div>
            </div>
            
            <!-- Social Media -->
            <div class="border-t border-gray-800 pt-8 mb-8">
                <h4 class="text-white font-bold mb-4">Ikuti Kami</h4>
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center hover:bg-green-700">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center hover:bg-green-700">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center hover:bg-green-700">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center hover:bg-green-700">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-8 text-center text-sm">
                <p>&copy; 2026 TokoBuku. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            const menu = document.querySelector('.mobile-menu');
            if (menu) {
                menu.classList.toggle('hidden');
            }
        }

        // Hapus alert setelah 5 detik
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.remove();
                }, 5000);
            });
        });
    </script>
</body>
</html>
