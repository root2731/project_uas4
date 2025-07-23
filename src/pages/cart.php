<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT c.*, p.name, p.price, p.stock, (c.quantity * p.price) AS subtotal 
                      FROM carts c
                      JOIN products p ON c.product_id = p.id
                      WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();

$total = 0;
foreach ($cart_items as $item) {
    $total += $item['subtotal'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_cart'])) {
        foreach ($_POST['quantity'] as $product_id => $quantity) {
            $stmt = $pdo->prepare("SELECT stock FROM products WHERE id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch();

            if ($product && $quantity > 0 && $quantity <= $product['stock']) {
                $stmt = $pdo->prepare("UPDATE carts SET quantity = ? WHERE user_id = ? AND product_id = ?");
                $stmt->execute([$quantity, $user_id, $product_id]);
            }
        }
        $_SESSION['success'] = "Keranjang berhasil diperbarui!";
        header('Location: index.php?page=cart');
        exit;
    }

    if (isset($_POST['remove_item'])) {
        $product_id = $_POST['product_id'];
        $stmt = $pdo->prepare("DELETE FROM carts WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        $_SESSION['success'] = "Produk berhasil dihapus dari keranjang!";
        header('Location: index.php?page=cart');
        exit;
    }
}
?>

<div class="container-fluid">
    <h2 class="mb-4"><i class="bi bi-cart me-2"></i>Keranjang Belanja</h2>

    <?php displayAlert(); ?>

    <?php if (empty($cart_items)): ?>
        <div class="text-center py-5">
            <i class="bi bi-cart-x fs-1 text-muted"></i>
            <h4 class="mt-3">Keranjang belanja kosong</h4>
            <a href="index.php?page=products_public" class="btn btn-primary mt-3">
                <i class="bi bi-arrow-left me-2"></i>Belanja Sekarang
            </a>
        </div>
    <?php else: ?>
        <form method="POST">
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Kuantitas</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart_items as $item): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['name']) ?></td>
                                        <td>Rp <?= number_format($item['price'], 2) ?></td>
                                        <td>
                                            <input type="number" name="quantity[<?= $item['product_id'] ?>]"
                                                class="form-control" value="<?= $item['quantity'] ?>"
                                                min="1" max="<?= $item['stock'] ?>">
                                        </td>
                                        <td>Rp <?= number_format($item['subtotal'], 2) ?></td>
                                        <td>
                                            <button type="submit" name="remove_item" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="table-light">
                                    <td colspan="3" class="text-end fw-bold">Total</td>
                                    <td colspan="2" class="fw-bold">Rp <?= number_format($total, 2) ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between">
                        <a href="index.php?page=products_public" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Lanjut Belanja
                        </a>
                        <div>
                            <button type="submit" name="update_cart" class="btn btn-warning me-2">
                                <i class="bi bi-arrow-repeat me-2"></i>Perbarui Keranjang
                            </button>
                            <a href="index.php?page=checkout" class="btn btn-success">
                                <i class="bi bi-check-circle me-2"></i>Checkout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>