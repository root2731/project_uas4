<?php
require_once __DIR__ . '/../includes/auth.php';

if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-t');

$stmt = $pdo->prepare("SELECT t.*, u.username 
                      FROM transactions t
                      JOIN users u ON t.user_id = u.id
                      WHERE t.transaction_date BETWEEN ? AND ?
                      ORDER BY t.transaction_date DESC");
$stmt->execute([$start_date, $end_date]);
$transactions = $stmt->fetchAll();

$total = 0;
foreach ($transactions as $t) {
    $total += $t['total'];
}
?>
<div class="container-fluid">
    <h2 class="mb-4"><i class="bi bi-bar-chart me-2"></i>Laporan Transaksi</h2>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <input type="hidden" name="page" value="reports">
                <div class="col-md-4">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" name="start_date"
                        value="<?= $start_date ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control" name="end_date"
                        value="<?= $end_date ?>">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel me-2"></i>Filter
                    </button>
                    <a href="?page=reports" class="btn btn-outline-secondary ms-2">
                        <i class="bi bi-arrow-repeat me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Total Transaksi</div>
                            <div class="h5 mb-0 font-weight-bold"><?= count($transactions) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-receipt-cutoff fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Total Pendapatan</div>
                            <div class="h5 mb-0 font-weight-bold">Rp <?= number_format($total, 2) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-currency-exchange fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Transaksi</h6>
            <button class="btn btn-success" id="exportBtn">
                <i class="bi bi-file-earmark-spreadsheet me-2"></i>Export to Excel
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="reportTable">
                    <thead>
                        <tr>
                            <th>ID Transaksi</th>
                            <th>Tanggal</th>
                            <th>Customer</th>
                            <th>Jumlah Item</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $t): ?>
                            <tr>
                                <td>TRX<?= str_pad($t['id'], 5, '0', STR_PAD_LEFT) ?></td>
                                <td><?= date('d M Y H:i', strtotime($t['transaction_date'])) ?></td>
                                <td><?= $t['username'] ?></td>
                                <td><?= $t['item_count'] ?></td>
                                <td class="text-end">Rp <?= number_format($t['total'], 2) ?></td>
                                <td>
                                    <span class="badge bg-<?=
                                                            $t['status'] == 'completed' ? 'success' : ($t['status'] == 'pending' ? 'warning' : 'danger')
                                                            ?>">
                                        <?= ucfirst($t['status']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end">Total Keseluruhan</th>
                            <th class="text-end">Rp <?= number_format($total, 2) ?></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('exportBtn').addEventListener('click', function() {
        const start = "<?= $start_date ?>";
        const end = "<?= $end_date ?>";
        const fileName = `Laporan_Transaksi_${start}_sampai_${end}.xls`;

        let tableHTML = `
        <table border="1">
            <thead>
                <tr>
                    <th colspan="6" style="text-align:center;font-size:16px;background-color:#e9ecef;">
                        Laporan Transaksi (${start} s/d ${end})
                    </th>
                </tr>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Tanggal</th>
                    <th>Customer</th>
                    <th>Jumlah Item</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
    `;

        <?php foreach ($transactions as $t): ?>
            tableHTML += `
        <tr>
            <td>TRX<?= str_pad($t['id'], 5, '0', STR_PAD_LEFT) ?></td>
            <td><?= date('d M Y H:i', strtotime($t['transaction_date'])) ?></td>
            <td><?= $t['username'] ?></td>
            <td><?= $t['item_count'] ?></td>
            <td>Rp <?= number_format($t['total'], 2) ?></td>
            <td><?= ucfirst($t['status']) ?></td>
        </tr>
    `;
        <?php endforeach; ?>

        tableHTML += `
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align:right;font-weight:bold;">Total Keseluruhan</td>
                    <td style="font-weight:bold;">Rp <?= number_format($total, 2) ?></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    `;

        const blob = new Blob([tableHTML], {
            type: 'application/vnd.ms-excel'
        });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = fileName;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    });
</script>