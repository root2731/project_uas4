<?php
require_once __DIR__ . '/../includes/auth.php';

if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$status = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';

$sql = "SELECT t.*, u.username 
        FROM transactions t
        JOIN users u ON t.user_id = u.id
        WHERE 1=1";

$params = [];

if ($status) {
    $sql .= " AND t.status = ?";
    $params[] = $status;
}

if ($search) {
    $sql .= " AND (u.username LIKE ? OR t.id LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$sql .= " ORDER BY t.transaction_date DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$transactions = $stmt->fetchAll();
?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-cash-coin me-2"></i>Manajemen Transaksi</h2>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Transaksi</h6>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <input type="hidden" name="page" value="transactions">
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="">Semua Status</option>
                        <option value="pending" <?= $status == 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="completed" <?= $status == 'completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="cancelled" <?= $status == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cari (ID/Username)</label>
                    <input type="text" class="form-control" name="search" value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-funnel me-2"></i>Filter
                    </button>
                    <a href="?page=transactions" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-repeat me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>ID Transaksi</th>
                            <th>Tanggal</th>
                            <th>Customer</th>
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
                                <td><?= $t['username'] ?></td>
                                <td>Rp <?= number_format($t['total'], 2) ?></td>
                                <td>
                                    <span class="badge bg-<?=
                                                            $t['status'] == 'completed' ? 'success' : ($t['status'] == 'pending' ? 'warning' : 'danger')
                                                            ?>">
                                        <?= ucfirst($t['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="?page=transaction_detail&id=<?= $t['id'] ?>" class="btn btn-sm btn-info">
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