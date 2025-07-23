<?php
// Fungsi untuk memeriksa login
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

// Redirect jika belum login
function redirectIfNotLoggedIn()
{
    if (!isLoggedIn()) {
        header("Location: ../login.php");
        exit();
    }
}

// Redirect jika bukan admin
function redirectIfNotAdmin()
{
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../index.php");
        exit();
    }
}

// Fungsi untuk mendapatkan nama pengguna
function getUsername($user_id)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    return $user['username'] ?? 'Unknown';
}
