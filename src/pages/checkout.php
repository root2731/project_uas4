<?php
require_once __DIR__ . '/../includes/auth.php';
redirectIfNotLoggedIn();

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT c.*, p.name, p.price, (c.quantity * p.price) AS subtotal 
                      FROM carts c
                      JOIN products p ON c.product_id = p.id
                      WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();

if (empty($cart_items)) {
    $_SESSION['error'] = "Keranjang belanja kosong!";
    header('Location: index.php?page=cart');
    exit;
}

$total = 0;
foreach ($cart_items as $item) {
    $total += $item['subtotal'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO transactions (user_id, transaction_date, total, status) 
                              VALUES (?, NOW(), ?, 'pending')");
        $stmt->execute([$user_id, $total]);
        $transaction_id = $pdo->lastInsertId();

        foreach ($cart_items as $item) {
            $stmt = $pdo->prepare("INSERT INTO transaction_details 
                                  (transaction_id, product_id, quantity, price, subtotal) 
                                  VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $transaction_id,
                $item['product_id'],
                $item['quantity'],
                $item['price'],
                $item['subtotal']
            ]);

            $stmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
            $stmt->execute([$item['quantity'], $item['product_id']]);
        }

        $stmt = $pdo->prepare("DELETE FROM carts WHERE user_id = ?");
        $stmt->execute([$user_id]);

        $pdo->commit();

        $_SESSION['success'] = "Pesanan berhasil dibuat! ID Transaksi: TRX" . str_pad($transaction_id, 5, '0', STR_PAD_LEFT);
        header('Location: index.php?page=my_orders');
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Checkout gagal: " . $e->getMessage();
        header('Location: index.php?page=checkout');
        exit;
    }
}
?>

<div class="container-fluid">
    <h2 class="mb-4"><i class="bi bi-check-circle me-2"></i>Checkout</h2>

    <?php displayAlert(); ?>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Ringkasan Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Kuantitas</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart_items as $item): ?>
                                    <tr>
                                        <td><?= $item['name'] ?></td>
                                        <td>Rp <?= number_format($item['price'], 2) ?></td>
                                        <td><?= $item['quantity'] ?></td>
                                        <td>Rp <?= number_format($item['subtotal'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="table-light">
                                    <td colspan="3" class="text-end fw-bold">Total</td>
                                    <td class="fw-bold">Rp <?= number_format($total, 2) ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informasi Pembayaran</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Metode Pembayaran</label>
                            <select class="form-select" name="payment_method" required>
                                <option value="transfer">Transfer Bank</option>
                                <option value="cash">Cash On Delivery (COD)</option>
                                <option value="e-wallet">E-Wallet</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat Pengiriman</label>
                            <textarea class="form-control" name="shipping_address" rows="3" required></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Konfirmasi Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>