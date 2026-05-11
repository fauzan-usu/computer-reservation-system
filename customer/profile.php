<?php
$page_title = 'Profil Saya';
$base_path = '../';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';

if (!isset($_SESSION['customer_id'])) {
    redirect('login.php');
}

$customer_id = $_SESSION['customer_id'];
$success = '';
$error = '';

// Get customer info
$customer = $conn->prepare("SELECT * FROM customers WHERE id = ?");
$customer->bind_param('i', $customer_id);
$customer->execute();
$customer_info = $customer->get_result()->fetch_assoc();

// Update profile
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $full_name = sanitize($conn, $_POST['full_name']);
    $email = sanitize($conn, $_POST['email']);
    $phone = sanitize($conn, $_POST['phone']);
    $address = sanitize($conn, $_POST['address']);
    $city = sanitize($conn, $_POST['city']);
    $date_of_birth = $_POST['date_of_birth'];

    if (!isValidEmail($email)) {
        $error = 'Format email tidak valid!';
    } else {
        $update = $conn->prepare("UPDATE customers SET full_name = ?, email = ?, phone = ?, address = ?, city = ?, date_of_birth = ? WHERE id = ?");
        $update->bind_param('ssssssi', $full_name, $email, $phone, $address, $city, $date_of_birth, $customer_id);

        if ($update->execute()) {
            $_SESSION['customer_name'] = $full_name;
            $success = 'Profil berhasil diperbarui!';

            // Refresh data
            $customer->execute();
            $customer_info = $customer->get_result()->fetch_assoc();
        } else {
            $error = 'Gagal memperbarui profil.';
        }
    }
}

// Change password
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (!password_verify($current_password, $customer_info['password'])) {
        $error = 'Password saat ini salah!';
    } elseif (strlen($new_password) < 6) {
        $error = 'Password baru minimal 6 karakter!';
    } elseif ($new_password != $confirm_password) {
        $error = 'Password baru tidak cocok!';
    } else {
        $hashed = password_hash($new_password, PASSWORD_BCRYPT);
        $update = $conn->prepare("UPDATE customers SET password = ? WHERE id = ?");
        $update->bind_param('si', $hashed, $customer_id);

        if ($update->execute()) {
            $success = 'Password berhasil diubah!';
        } else {
            $error = 'Gagal mengubah password.';
        }
    }
}
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="list-group mb-4">
                <a href="index.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>
                <a href="my_bookings.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-calendar-check me-2"></i>Pesanan Saya
                </a>
                <a href="book_hotel.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-building me-2"></i>Pesan Hotel
                </a>
                <a href="book_flight.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-airplane me-2"></i>Pesan Penerbangan
                </a>
                <a href="book_package.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-suitcase-lg me-2"></i>Pesan Paket Wisata
                </a>
                <a href="profile.php" class="list-group-item list-group-item-action active">
                    <i class="bi bi-person me-2"></i>Profil
                </a>
            </div>
        </div>

        <div class="col-md-9">
            <h2 class="mb-4"><i class="bi bi-person me-2"></i>Profil Saya</h2>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Profil</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control" value="<?php echo $customer_info['username']; ?>" disabled>
                                    <small class="text-muted">Username tidak dapat diubah</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap *</label>
                                    <input type="text" name="full_name" class="form-control" value="<?php echo $customer_info['full_name']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email *</label>
                                    <input type="email" name="email" class="form-control" value="<?php echo $customer_info['email']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">No. Telepon</label>
                                    <input type="text" name="phone" class="form-control" value="<?php echo $customer_info['phone']; ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <textarea name="address" class="form-control" rows="2"><?php echo $customer_info['address']; ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Kota</label>
                                    <input type="text" name="city" class="form-control" value="<?php echo $customer_info['city']; ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="date" name="date_of_birth" class="form-control" value="<?php echo $customer_info['date_of_birth']; ?>">
                                </div>
                                <button type="submit" name="update_profile" class="btn btn-primary">
                                    <i class="bi bi-save me-2"></i>Simpan Perubahan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-key me-2"></i>Ubah Password</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label class="form-label">Password Saat Ini *</label>
                                    <input type="password" name="current_password" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password Baru *</label>
                                    <input type="password" name="new_password" class="form-control" required minlength="6">
                                    <small class="text-muted">Minimal 6 karakter</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Konfirmasi Password Baru *</label>
                                    <input type="password" name="confirm_password" class="form-control" required>
                                </div>
                                <button type="submit" name="change_password" class="btn btn-warning">
                                    <i class="bi bi-key me-2"></i>Ubah Password
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informasi Akun</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>ID Customer</strong></td>
                                    <td>: <?php echo $customer_info['id']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Tipe ID</strong></td>
                                    <td>: <?php echo $customer_info['id_card_type']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>No. ID</strong></td>
                                    <td>: <?php echo $customer_info['id_card_number']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Negara</strong></td>
                                    <td>: <?php echo $customer_info['country']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Status</strong></td>
                                    <td>: 
                                        <?php if($customer_info['is_active']): ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Nonaktif</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Bergabung</strong></td>
                                    <td>: <?php echo date('d M Y', strtotime($customer_info['created_at'])); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>