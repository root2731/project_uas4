<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
redirectIfNotLoggedIn();

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY transaction_date DESC");
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll();
?>

<div class="container-fluid">
    <h2 class="mb-4"><i class="bi bi-clock-history me-2"></i>Riwayat Transaksi Saya</h2>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID Transaksi</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $t): ?>
                            <tr>
                                <td>TRX<?= str_pad($t['id'], 5, '0', STR_PAD_LEFT) ?></td>
                                <td><?= date('d M Y H:i', strtotime($t['transaction_date'])) ?></td>
                                <td>Rp <?= number_format($t['total'], 2) ?></td>
                                <td>
                                    <span class="badge bg-<?=
                                                            $t['status'] == 'completed' ? 'success' : ($t['status'] == 'pending' ? 'warning' : 'danger')
                                                            ?>">
                                        <?= ucfirst($t['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="index.php?page=transaction_detail&id=<?= $t['id'] ?>"
                                        class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>