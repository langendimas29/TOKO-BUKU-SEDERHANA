<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'tokobuku');

// Create Database Connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8
$conn->set_charset("utf8mb4");

// Session configuration
session_start();

define('BASE_URL', 'http://localhost/tokobuku-usk/');

// Function untuk escape string
function escape($str) {
    global $conn;
    return $conn->real_escape_string($str);
}

// Function untuk password hashing
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Function untuk verify password
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Function untuk alert
function alert($message, $type = 'info') {
    echo "<div class='alert alert-{$type} alert-dismissible fade show' role='alert'>
            {$message}
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
}

// Function untuk redirect
function redirect($url) {
    header("Location: " . BASE_URL . $url);
    exit();
}

// Function untuk check session
function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        redirect('pages/login.php');
    }
}

// Function untuk check admin
function checkAdmin() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
        redirect('../pages/home.php');
    }
}
?>
