<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
redirectIfNotLoggedIn();

$stmt = $pdo->query("SELECT p.*, c.name AS category_name 
                    FROM products p 
                    JOIN categories c ON p.category_id = c.id
                    WHERE p.stock > 0
                    ORDER BY p.name");
$products = $stmt->fetchAll();
?>

<div class="container-fluid">
    <h2 class="mb-4"><i class="bi bi-cart me-2"></i>Daftar Produk</h2>

    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow">
                    <div class="card-body">
                        <h5 class="card-title"><?= $product['name'] ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?= $product['category_name'] ?></h6>
                        <p class="card-text"><?= $product['description'] ?: 'Deskripsi tidak tersedia' ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Rp <?= number_format($product['price'], 2) ?></span>
                            <span class="badge bg-info">Stok: <?= $product['stock'] ?></span>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <form method="POST" action="?page=cart_add">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <div class="input-group">
                                <input type="number" name="quantity" class="form-control" value="1" min="1" max="<?= $product['stock'] ?>">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>