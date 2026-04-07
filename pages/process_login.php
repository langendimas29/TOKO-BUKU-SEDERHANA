<?php
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = escape($_POST['username']);
    $password = $_POST['password'];

    // Check user by username or email
    $check = $conn->query("SELECT id, username, password, role, full_name FROM users 
                          WHERE username = '$username' OR email = '$username'");

    if ($check->num_rows == 0) {
        header("Location: login.php?error=" . urlencode('Username/Email atau Password salah'));
        exit();
    }

    $user = $check->fetch_assoc();

    // Verify password
    if (!verifyPassword($password, $user['password'])) {
        header("Location: login.php?error=" . urlencode('Username/Email atau Password salah'));
        exit();
    }

    // Set session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['full_name'] = $user['full_name'];

    // Redirect based on role
    if ($user['role'] == 'admin') {
        redirect('admin/dashboard.php');
    } else {
        redirect('pages/home.php');
    }
} else {
    header("Location: login.php");
    exit();
}
?>
