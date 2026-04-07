<?php
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = escape($_POST['username']);
    $email = escape($_POST['email']);
    $full_name = escape($_POST['full_name']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi
    $error = '';

    if (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter';
    }

    if ($password !== $confirm_password) {
        $error = 'Password tidak cocok';
    }

    // Check username exist
    $check_username = $conn->query("SELECT id FROM users WHERE username = '$username'");
    if ($check_username->num_rows > 0) {
        $error = 'Username sudah digunakan';
    }

    // Check email exist
    $check_email = $conn->query("SELECT id FROM users WHERE email = '$email'");
    if ($check_email->num_rows > 0) {
        $error = 'Email sudah terdaftar';
    }

    if ($error) {
        header("Location: register.php?error=" . urlencode($error));
        exit();
    }

    // Hash password
    $hashed_password = hashPassword($password);

    // Insert user
    $insert = $conn->query("INSERT INTO users (username, email, password, full_name, role) 
                            VALUES ('$username', '$email', '$hashed_password', '$full_name', 'user')");

    if ($insert) {
        header("Location: login.php?success=" . urlencode('Akun berhasil dibuat. Silakan login.'));
        exit();
    } else {
        header("Location: register.php?error=" . urlencode('Gagal membuat akun'));
        exit();
    }
} else {
    header("Location: register.php");
    exit();
}
?>
