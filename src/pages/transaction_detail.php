<?php
require_once __DIR__ . '/../includes/auth.php';

if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$transaction_id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT t.*, u.username 
                      FROM transactions t
                      JOIN users u ON t.user_id = u.id
                      WHERE t.id = ?");
$stmt->execute([$transaction_id]);
$transaction = $stmt->fetch();

if (!$transaction) {
    $_SESSION['error'] = "Transaksi tidak ditemukan!";
    header('Location: index.php?page=transactions');
    exit;
}

$stmt = $pdo->prepare("SELECT td.*, p.name, p.price 
                      FROM transaction_details td
                      JOIN products p ON td.product_id = p.id
                      WHERE td.transaction_id = ?");
$stmt->execute([$transaction_id]);
$details = $stmt->fetchAll();
?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-receipt me-2"></i>Detail Transaksi #TRX<?= str_pad($transaction['id'], 5, '0', STR_PAD_LEFT) ?></h2>
        <a href="?page=transactions" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informasi Transaksi</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">ID Transaksi:</div>
                        <div class="col-md-8">TRX<?= str_pad($transaction['id'], 5, '0', STR_PAD_LEFT) ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Tanggal:</div>
                        <div class="col-md-8"><?= date('d M Y H:i', strtotime($transaction['transaction_date'])) ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Customer:</div>
                        <div class="col-md-8"><?= $transaction['username'] ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Status:</div>
                        <div class="col-md-8">
                            <span class="badge bg-<?=
                                                    $transaction['status'] == 'completed' ? 'success' : ($transaction['status'] == 'pending' ? 'warning' : 'danger')
                                                    ?>">
                                <?= ucfirst($transaction['status']) ?>
                            </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Total:</div>
                        <div class="col-md-8 fw-bold">Rp <?= number_format($transaction['total'], 2) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Detail Produk</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($details as $d): ?>
                                    <tr>
                                        <td><?= $d['name'] ?></td>
                                        <td>Rp <?= number_format($d['price'], 2) ?></td>
                                        <td><?= $d['quantity'] ?></td>
                                        <td>Rp <?= number_format($d['subtotal'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="table-light">
                                    <td colspan="3" class="text-end fw-bold">Total</td>
                                    <td class="fw-bold">Rp <?= number_format($transaction['total'], 2) ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>