<?php
$page_title = 'Kelola Paket Wisata';
$base_path = '../';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';

if (!isset($_SESSION['admin_id'])) {
    redirect('login.php');
}

$success = '';
$error = '';

// Add Package
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_package'])) {
    $package_code = sanitize($conn, $_POST['package_code']);
    $name = sanitize($conn, $_POST['name']);
    $description = sanitize($conn, $_POST['description']);
    $destination = sanitize($conn, $_POST['destination']);
    $duration_days = (int)$_POST['duration_days'];
    $duration_nights = (int)$_POST['duration_nights'];
    $price = (float)$_POST['price_per_person'];
    $max_participants = (int)$_POST['max_participants'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $guide_name = sanitize($conn, $_POST['guide_name']);
    $category = $_POST['category'];

    $insert = $conn->prepare("INSERT INTO tour_packages (package_code, name, description, destination, duration_days, duration_nights, price_per_person, max_participants, available_slots, start_date, end_date, guide_name, category) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $insert->bind_param('ssssiiiiiisss', $package_code, $name, $description, $destination, $duration_days, $duration_nights, $price, $max_participants, $max_participants, $start_date, $end_date, $guide_name, $category);

    if ($insert->execute()) {
        $success = 'Paket wisata berhasil ditambahkan!';
    } else {
        $error = 'Gagal menambahkan paket wisata.';
    }
}

// Delete Package
if (isset($_POST['delete_package'])) {
    $id = (int)$_POST['package_id'];
    $delete = $conn->prepare("DELETE FROM tour_packages WHERE id = ?");
    $delete->bind_param('i', $id);
    if ($delete->execute()) {
        $success = 'Paket wisata berhasil dihapus!';
    }
}

$packages = $conn->query("SELECT * FROM tour_packages ORDER BY created_at DESC");
?>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="index.php"><i class="bi bi-speedometer2"></i>Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="hotels.php"><i class="bi bi-building"></i>Kelola Hotel</a></li>
                    <li class="nav-item"><a class="nav-link" href="flights.php"><i class="bi bi-airplane"></i>Kelola Penerbangan</a></li>
                    <li class="nav-item"><a class="nav-link active" href="packages.php"><i class="bi bi-suitcase-lg"></i>Kelola Paket Wisata</a></li>
                    <li class="nav-item"><a class="nav-link" href="bookings.php"><i class="bi bi-calendar-check"></i>Semua Pemesanan</a></li>
                    <li class="nav-item"><a class="nav-link" href="customers.php"><i class="bi bi-people"></i>Kelola Customer</a></li>
                    <li class="nav-item mt-4"><a class="nav-link text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i>Logout</a></li>
                </ul>
            </div>
        </nav>

        <main class="col-md-10 ms-sm-auto px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
                <h1 class="h2"><i class="bi bi-suitcase-lg me-2"></i>Kelola Paket Wisata</h1>
                <button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#addPackageModal">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Paket
                </button>
            </div>

            <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
            <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Paket</th>
                            <th>Destinasi</th>
                            <th>Durasi</th>
                            <th>Harga</th>
                            <th>Slot</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($package = $packages->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?php echo $package['package_code']; ?></strong></td>
                            <td><?php echo $package['name']; ?></td>
                            <td><?php echo $package['destination']; ?></td>
                            <td><?php echo $package['duration_days']; ?>H <?php echo $package['duration_nights']; ?>M</td>
                            <td><?php echo formatRupiah($package['price_per_person']); ?></td>
                            <td><?php echo $package['available_slots']; ?>/<?php echo $package['max_participants']; ?></td>
                            <td><?php echo date('d M', strtotime($package['start_date'])); ?> - <?php echo date('d M Y', strtotime($package['end_date'])); ?></td>
                            <td>
                                <form method="POST" action="" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                                    <input type="hidden" name="package_id" value="<?php echo $package['id']; ?>">
                                    <button type="submit" name="delete_package" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<!-- Add Package Modal -->
<div class="modal fade" id="addPackageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Paket Wisata Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kode Paket *</label>
                            <input type="text" name="package_code" class="form-control" placeholder="PKG-001" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Paket *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Destinasi *</label>
                            <input type="text" name="destination" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori *</label>
                            <select name="category" class="form-select" required>
                                <option value="family">Family</option>
                                <option value="adventure">Adventure</option>
                                <option value="honeymoon">Honeymoon</option>
                                <option value="cultural">Cultural</option>
                                <option value="nature">Nature</option>
                                <option value="educational">Educational</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Durasi (Hari) *</label>
                            <input type="number" name="duration_days" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Durasi (Malam) *</label>
                            <input type="number" name="duration_nights" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Max Peserta *</label>
                            <input type="number" name="max_participants" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Harga/Orang *</label>
                            <input type="number" name="price_per_person" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tanggal Mulai *</label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tanggal Selesai *</label>
                            <input type="date" name="end_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Guide</label>
                        <input type="text" name="guide_name" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="add_package" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>