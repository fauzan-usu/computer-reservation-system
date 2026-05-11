<?php
session_start();
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (isset($_SESSION['customer_id'])) {
    redirect('index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize($conn, $_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = sanitize($conn, $_POST['full_name']);
    $email = sanitize($conn, $_POST['email']);
    $phone = sanitize($conn, $_POST['phone']);
    $address = sanitize($conn, $_POST['address']);
    $city = sanitize($conn, $_POST['city']);
    $date_of_birth = $_POST['date_of_birth'];

    // Validation
    if (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter!';
    } elseif ($password != $confirm_password) {
        $error = 'Password tidak cocok!';
    } elseif (!isValidEmail($email)) {
        $error = 'Format email tidak valid!';
    } else {
        // Check if username or email exists
        $check = $conn->prepare("SELECT id FROM customers WHERE username = ? OR email = ?");
        $check->bind_param('ss', $username, $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = 'Username atau email sudah terdaftar!';
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $insert = $conn->prepare("INSERT INTO customers (username, password, full_name, email, phone, address, city, date_of_birth) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $insert->bind_param('ssssssss', $username, $hashed_password, $full_name, $email, $phone, $address, $city, $date_of_birth);

            if ($insert->execute()) {
                $success = 'Registrasi berhasil! Silakan login.';
            } else {
                $error = 'Terjadi kesalahan. Silakan coba lagi.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Customer - CRS SMK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
            width: 100%;
            max-width: 500px;
        }
    </style>
</head>
<body>
    <div class="register-card">
        <h3 class="text-center mb-4">Registrasi Customer</h3>
        <p class="text-center text-muted mb-4">Buat akun baru untuk memesan</p>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo $success; ?>
                <br><a href="login.php" class="alert-link">Klik di sini untuk login</a>
            </div>
        <?php else: ?>
            <form method="POST" action="">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Username *</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" name="full_name" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" class="form-control" required minlength="6">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Konfirmasi Password *</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="address" class="form-control" rows="2"></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kota</label>
                        <input type="text" name="city" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="date_of_birth" class="form-control">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2">
                    <i class="bi bi-person-plus me-2"></i>Daftar
                </button>
            </form>
        <?php endif; ?>

        <div class="mt-3 text-center">
            <p class="mb-2">Sudah punya akun? <a href="login.php">Login di sini</a></p>
            <a href="../index.php" class="text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>