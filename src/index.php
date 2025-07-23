<?php
ob_start();
session_start();
require_once 'config/database.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['user_id']) && basename($_SERVER['PHP_SELF']) !== 'login.php') {
    header('Location: login.php');
    exit;
}

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$allowed_pages = [
    'dashboard',
    'users',
    'products',
    'categories',
    'transactions',
    'reports',
    'settings',
    'profile',
    'products_public',
    'cart',
    'checkout',
    'my_orders'
];

if (!in_array($page, $allowed_pages)) {
    $page = 'dashboard';
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="stylesheet" href="assets/css/style.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StoreManagerPro - Sistem Manajemen Toko</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">

</head>

<body>
    <nav class="navbar navbar-expand-lg+6 navbar-dark" style="background: linear-gradient(90deg, #4361ee, #3f37c9);">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-shop me-2"></i>
                <strong>StoreManagerPro</strong>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-2"></i>
                            <?= $_SESSION['username'] ?? 'Pengguna' ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="?page=profile"><i class="bi bi-person me-2"></i> Profil</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i> Pengaturan</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2 sidebar d-none d-lg-block">
                <div class="p-3">
                    <h5 class="text-center mb-4">MENU UTAMA</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?= $page == 'dashboard' ? 'active' : '' ?>" href="?page=dashboard">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link <?= $page == 'users' ? 'active' : '' ?>" href="?page=users">
                                    <i class="bi bi-people"></i> Manajemen User
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $page == 'products' ? 'active' : '' ?>" href="?page=products">
                                    <i class="bi bi-box-seam"></i> Produk
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $page == 'categories' ? 'active' : '' ?>" href="?page=categories">
                                    <i class="bi bi-tags"></i> Kategori
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $page == 'transactions' ? 'active' : '' ?>" href="?page=transactions">
                                    <i class="bi bi-cash-coin"></i> Transaksi
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $page == 'reports' ? 'active' : '' ?>" href="?page=reports">
                                    <i class="bi bi-bar-chart"></i> Laporan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $page == 'settings' ? 'active' : '' ?>" href="?page=settings">
                                    <i class="bi bi-gear"></i> Pengaturan
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'customer'): ?>
                            <li class="nav-item">
                                <a class="nav-link <?= $page == 'products_public' ? 'active' : '' ?>" href="?page=products_public">
                                    <i class="bi bi-cart"></i> Belanja
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $page == 'cart' ? 'active' : '' ?>" href="?page=cart">
                                    <i class="bi bi-cart-check"></i> Keranjang
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $page == 'my_orders' ? 'active' : '' ?>" href="?page=my_orders">
                                    <i class="bi bi-clock-history"></i> Riwayat Saya
                                </a>
                            </li>
                        <?php endif; ?>

                        <li class="nav-item mt-4">
                            <a class="nav-link text-center text-warning" href="logout.php">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-10 main-content">
                <?php
                $page_file = "pages/{$page}.php";
                if (file_exists($page_file)) {
                    include $page_file;
                } else {
                    include 'pages/dashboard.php';
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('salesChart').getContext('2d');
            const salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Pendapatan (juta)',
                        data: [5.2, 7.8, 6.2, 8.5, 9.1, 10.3, 12.4, 11.2, 9.8, 10.5, 11.7, 15.2],
                        borderColor: '#4361ee',
                        backgroundColor: 'rgba(67, 97, 238, 0.1)',
                        borderWidth: 3,
                        pointBackgroundColor: '#4361ee',
                        pointRadius: 5,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Pendapatan Tahunan',
                            font: {
                                size: 16
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });

        function confirmDelete(itemName) {
            return confirm(`Apakah Anda yakin ingin menghapus ${itemName}? Tindakan ini tidak dapat dibatalkan.`);
        }
    </script>

</body>

</html>
<?php ob_end_flush(); ?>