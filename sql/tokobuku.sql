-- Database Schema untuk Aplikasi BookStore

CREATE DATABASE IF NOT EXISTS tokobuku_db;
USE tokobuku_db;

-- Tabel Users
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Categories
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Books
CREATE TABLE books (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    author VARCHAR(100),
    isbn VARCHAR(20) UNIQUE,
    category_id INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    stock INT DEFAULT 0,
    description TEXT,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT
);

-- Tabel Shopping Cart
CREATE TABLE cart (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    quantity INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    UNIQUE KEY unique_cart (user_id, book_id)
);

-- Tabel Orders
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'paid', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    payment_status ENUM('unpaid', 'paid') DEFAULT 'unpaid',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tabel Order Items
CREATE TABLE order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    book_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE RESTRICT
);

-- Tabel Messages
CREATE TABLE messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    subject VARCHAR(200),
    message TEXT NOT NULL,
    status ENUM('unread', 'read') DEFAULT 'unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert Admin Account
INSERT INTO users (username, email, password, full_name, role) VALUES 
('admin', 'admin@tokobuku.com', '$2y$10$abcdefghijklmnopqrstuvwxyz123456789', 'Administrator', 'admin');

-- Insert Sample Categories
INSERT INTO categories (name, description) VALUES 
('Fiksi', 'Buku cerita fiksi dan novel'),
('Non-Fiksi', 'Buku pengetahuan dan edukasi'),
('Teknologi', 'Buku tentang teknologi dan programming'),
('Bisnis', 'Buku panduan bisnis dan entrepreneurship'),
('Sejarah', 'Buku sejarah dan biografi');

-- Insert Sample Books
INSERT INTO books (title, author, isbn, category_id, price, stock, description, image_url) VALUES 
('Harry Potter and The Philosopher Stone', 'J.K. Rowling', '978-0747532699', 1, 85000, 10, 'Novel fantasi paling terkenal', 'harry-potter.jpg'),
('Imparsial Cinta', 'Dee Lestari', '978-9793061885', 1, 45000, 15, 'Novel romantis berkualitas', 'imparsial-cinta.jpg'),
('Sapiens', 'Yuval Noah Harari', '978-0062316097', 2, 95000, 8, 'Sejarah singkat umat manusia', 'sapiens.jpg'),
('Clean Code', 'Robert C. Martin', '978-0132350884', 3, 120000, 5, 'Panduan menulis kode yang rapi', 'clean-code.jpg'),
('Atomic Habits', 'James Clear', '978-0735211292', 4, 110000, 12, 'Cara mengubah kebiasaan', 'atomic-habits.jpg');

CREATE INDEX idx_book_category ON books(category_id);
CREATE INDEX idx_cart_user ON cart(user_id);
CREATE INDEX idx_cart_book ON cart(book_id);
CREATE INDEX idx_order_user ON orders(user_id);
CREATE INDEX idx_order_items ON order_items(order_id);
CREATE INDEX idx_messages_user ON messages(user_id);
