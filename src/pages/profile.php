<?php
require_once __DIR__ . '/../includes/auth.php';
redirectIfNotLoggedIn();

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT u.*, up.full_name, up.email, up.address, up.phone 
                      FROM users u 
                      LEFT JOIN user_profiles up ON u.id = up.user_id
                      WHERE u.id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = sanitizeInput($_POST['full_name']);
    $email = sanitizeInput($_POST['email']);
    $address = sanitizeInput($_POST['address']);
    $phone = sanitizeInput($_POST['phone']);

    $stmt = $pdo->prepare("SELECT * FROM user_profiles WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $profile_exists = $stmt->fetch();

    if ($profile_exists) {
        $stmt = $pdo->prepare("UPDATE user_profiles SET 
                              full_name = ?, 
                              email = ?, 
                              address = ?, 
                              phone = ?
                              WHERE user_id = ?");
        $stmt->execute([$full_name, $email, $address, $phone, $user_id]);
    } else {

        $stmt = $pdo->prepare("INSERT INTO user_profiles 
                              (user_id, full_name, email, address, phone) 
                              VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $full_name, $email, $address, $phone]);
    }

    $_SESSION['success'] = "Profil berhasil diperbarui!";
    header('Location: index.php?page=profile');
    exit;
}
?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-person me-2"></i>Profil Saya</h2>
    </div>

    <?php displayAlert(); ?>

    <div class="card shadow">
        <div class="card-body">
            <form method="POST">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" value="<?= $user['username'] ?>" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Role</label>
                        <input type="text" class="form-control" value="<?= ucfirst($user['role']) ?>" disabled>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" name="full_name"
                            value="<?= $user['full_name'] ?? '' ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email"
                            value="<?= $user['email'] ?? '' ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" class="form-control" name="phone"
                            value="<?= $user['phone'] ?? '' ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control" name="address" rows="3"><?= $user['address'] ?? '' ?></textarea>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Simpan Profil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>