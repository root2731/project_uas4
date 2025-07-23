<?php
require_once __DIR__ . '/../includes/auth.php';

if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$settings = $pdo->query("SELECT * FROM settings WHERE id = 1")->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $store_name = sanitizeInput($_POST['store_name']);
    $store_address = sanitizeInput($_POST['store_address']);
    $tax_rate = (float)$_POST['tax_rate'];
    $currency = sanitizeInput($_POST['currency']);

    $stmt = $pdo->prepare("UPDATE settings SET 
                          store_name = ?, 
                          store_address = ?, 
                          tax_rate = ?, 
                          currency = ?
                          WHERE id = 1");
    if ($stmt->execute([$store_name, $store_address, $tax_rate, $currency])) {
        $_SESSION['success'] = "Pengaturan berhasil diperbarui!";
    } else {
        $_SESSION['error'] = "Gagal memperbarui pengaturan!";
    }
    header('Location: index.php?page=settings');
    exit;
}
?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-gear me-2"></i>Pengaturan Sistem</h2>
    </div>

    <?php displayAlert(); ?>

    <div class="card shadow">
        <div class="card-body">
            <form method="POST">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Toko</label>
                        <input type="text" class="form-control" name="store_name"
                            value="<?= $settings['store_name'] ?? 'StoreManagerPro' ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Mata Uang</label>
                        <select class="form-select" name="currency">
                            <option value="IDR" <?= ($settings['currency'] ?? 'IDR') == 'IDR' ? 'selected' : '' ?>>Rupiah (IDR)</option>
                            <option value="USD" <?= ($settings['currency'] ?? 'IDR') == 'USD' ? 'selected' : '' ?>>Dollar (USD)</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <label class="form-label">Alamat Toko</label>
                        <textarea class="form-control" name="store_address" rows="3"><?= $settings['store_address'] ?? '' ?></textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Pajak (%)</label>
                        <input type="number" class="form-control" name="tax_rate"
                            value="<?= $settings['tax_rate'] ?? 10 ?>" step="0.1" min="0" max="100" required>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>