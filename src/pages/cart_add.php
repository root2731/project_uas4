<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

error_log("=== START CART ADD PROCESS ===");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    error_log("POST Data: " . print_r($_POST, true));

    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $user_id = (int)$_SESSION['user_id'];

    error_log("Session Data: " . print_r($_SESSION, true));

    try {
        $stmt = $pdo->prepare("SELECT id, stock FROM products WHERE id = ? FOR UPDATE");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();

        if (!$product) {
            error_log("Product not found: $product_id");
            $_SESSION['error'] = "Produk tidak ditemukan!";
            header('Location: index.php?page=products_public');
            exit;
        }

        if ($product['stock'] < $quantity) {
            error_log("Insufficient stock: Available {$product['stock']}, Requested $quantity");
            $_SESSION['error'] = "Stok tidak mencukupi!";
            header('Location: index.php?page=products_public');
            exit;
        }

        $sql = "INSERT INTO carts (user_id, product_id, quantity) 
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)";

        error_log("Executing SQL: $sql");
        error_log("Parameters: $user_id, $product_id, $quantity");

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$user_id, $product_id, $quantity]);

        if ($result) {
            $rowsAffected = $stmt->rowCount();
            error_log("Query executed successfully. Rows affected: $rowsAffected");

            $stmt = $pdo->prepare("SELECT * FROM carts WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$user_id, $product_id]);
            $cartItem = $stmt->fetch();

            error_log("Verification query result: " . print_r($cartItem, true));

            $_SESSION['success'] = "Produk berhasil ditambahkan ke keranjang!";
        } else {
            error_log("Query execution failed");
            $_SESSION['error'] = "Gagal menambahkan produk ke keranjang!";
        }
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage());
        $_SESSION['error'] = "Terjadi kesalahan sistem. Silakan coba lagi.";
    }

    error_log("=== END CART ADD PROCESS ===");
    header('Location: index.php?page=cart');
    exit;
}

header('Location: index.php?page=products_public');
exit;
