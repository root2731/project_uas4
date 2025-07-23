<?php
redirectIfNotLoggedIn();

$product_count = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$user_count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$transaction_count = $pdo->query("SELECT COUNT(*) FROM transactions")->fetchColumn();
$revenue = $pdo->query("SELECT SUM(total) FROM transactions WHERE status = 'completed'")->fetchColumn();
?>

<div class="container-fluid">
    <div class="dashboard-header p-4 mb-4 text-white rounded">
        <div class="row">
            <div class="col-md-8">
                <h2 class="user-greeting">Selamat Datang, <?= $_SESSION['username'] ?></h2>
                <p class="mb-0">Anda login sebagai <?= ucfirst($_SESSION['role']) ?></p>
            </div>
            <div class="col-md-4 text-end">
                <p class="mb-0"><?= date('l, d F Y') ?></p>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card shadow">
                <div class="card-body">
                    <i class="bi bi-cart fs-1 text-primary"></i>
                    <div class="number"><?= $product_count ?></div>
                    <div class="label">Total Produk</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card shadow">
                <div class="card-body">
                    <i class="bi bi-people fs-1 text-success"></i>
                    <div class="number"><?= $user_count ?></div>
                    <div class="label">Pengguna</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card shadow">
                <div class="card-body">
                    <i class="bi bi-receipt fs-1 text-warning"></i>
                    <div class="number"><?= $transaction_count ?></div>
                    <div class="label">Transaksi</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card shadow">
                <div class="card-body">
                    <i class="bi bi-currency-exchange fs-1 text-info"></i>
                    <div class="number">Rp <?= number_format($revenue, 2) ?></div>
                    <div class="label">Pendapatan</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="card-title mb-0">Grafik Penjualan</h5>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="card-title mb-0">Aktivitas Terbaru</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold">Admin</div>
                                <div class="text-muted">Menambahkan produk baru</div>
                            </div>
                            <small class="text-muted">2 menit lalu</small>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold">Budi Santoso</div>
                                <div class="text-muted">Melakukan pembelian</div>
                            </div>
                            <small class="text-muted">15 menit lalu</small>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold">Siti Rahayu</div>
                                <div class="text-muted">Mendaftar akun baru</div>
                            </div>
                            <small class="text-muted">1 jam lalu</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>