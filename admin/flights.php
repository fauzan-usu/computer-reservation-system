<?php
$page_title = 'Kelola Penerbangan';
$base_path = '../';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';

if (!isset($_SESSION['admin_id'])) {
    redirect('login.php');
}

$success = '';
$error = '';

// Add Flight
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_flight'])) {
    $flight_number = sanitize($conn, $_POST['flight_number']);
    $airline = sanitize($conn, $_POST['airline']);
    $departure_city = sanitize($conn, $_POST['departure_city']);
    $arrival_city = sanitize($conn, $_POST['arrival_city']);
    $departure_time = $_POST['departure_time'];
    $arrival_time = $_POST['arrival_time'];
    $flight_date = $_POST['flight_date'];
    $class_type = $_POST['class_type'];
    $price = (float)$_POST['price'];
    $total_seats = (int)$_POST['total_seats'];

    $insert = $conn->prepare("INSERT INTO flights (flight_number, airline, departure_city, arrival_city, departure_time, arrival_time, flight_date, class_type, price, total_seats, available_seats) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $insert->bind_param('ssssssssdii', $flight_number, $airline, $departure_city, $arrival_city, $departure_time, $arrival_time, $flight_date, $class_type, $price, $total_seats, $total_seats);

    if ($insert->execute()) {
        $success = 'Penerbangan berhasil ditambahkan!';
    } else {
        $error = 'Gagal menambahkan penerbangan.';
    }
}

// Delete Flight
if (isset($_POST['delete_flight'])) {
    $id = (int)$_POST['flight_id'];
    $delete = $conn->prepare("DELETE FROM flights WHERE id = ?");
    $delete->bind_param('i', $id);
    if ($delete->execute()) {
        $success = 'Penerbangan berhasil dihapus!';
    }
}

$flights = $conn->query("SELECT * FROM flights ORDER BY flight_date DESC, departure_time");
?>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="index.php"><i class="bi bi-speedometer2"></i>Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="hotels.php"><i class="bi bi-building"></i>Kelola Hotel</a></li>
                    <li class="nav-item"><a class="nav-link active" href="flights.php"><i class="bi bi-airplane"></i>Kelola Penerbangan</a></li>
                    <li class="nav-item"><a class="nav-link" href="packages.php"><i class="bi bi-suitcase-lg"></i>Kelola Paket Wisata</a></li>
                    <li class="nav-item"><a class="nav-link" href="bookings.php"><i class="bi bi-calendar-check"></i>Semua Pemesanan</a></li>
                    <li class="nav-item"><a class="nav-link" href="customers.php"><i class="bi bi-people"></i>Kelola Customer</a></li>
                    <li class="nav-item mt-4"><a class="nav-link text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i>Logout</a></li>
                </ul>
            </div>
        </nav>

        <main class="col-md-10 ms-sm-auto px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
                <h1 class="h2"><i class="bi bi-airplane me-2"></i>Kelola Penerbangan</h1>
                <button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#addFlightModal">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Penerbangan
                </button>
            </div>

            <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
            <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No. Penerbangan</th>
                            <th>Maskapai</th>
                            <th>Rute</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Kelas</th>
                            <th>Harga</th>
                            <th>Kursi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($flight = $flights->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?php echo $flight['flight_number']; ?></strong></td>
                            <td><?php echo $flight['airline']; ?></td>
                            <td><?php echo $flight['departure_city']; ?> → <?php echo $flight['arrival_city']; ?></td>
                            <td><?php echo date('d M Y', strtotime($flight['flight_date'])); ?></td>
                            <td><?php echo substr($flight['departure_time'], 0, 5); ?> - <?php echo substr($flight['arrival_time'], 0, 5); ?></td>
                            <td><span class="badge bg-info"><?php echo $flight['class_type']; ?></span></td>
                            <td><?php echo formatRupiah($flight['price']); ?></td>
                            <td><?php echo $flight['available_seats']; ?>/<?php echo $flight['total_seats']; ?></td>
                            <td>
                                <form method="POST" action="" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                                    <input type="hidden" name="flight_id" value="<?php echo $flight['id']; ?>">
                                    <button type="submit" name="delete_flight" class="btn btn-sm btn-danger">
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

<!-- Add Flight Modal -->
<div class="modal fade" id="addFlightModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Penerbangan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Penerbangan *</label>
                            <input type="text" name="flight_number" class="form-control" placeholder="GA-123" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Maskapai *</label>
                            <input type="text" name="airline" class="form-control" placeholder="Garuda Indonesia" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kota Keberangkatan *</label>
                            <input type="text" name="departure_city" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kota Tujuan *</label>
                            <input type="text" name="arrival_city" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Waktu Berangkat *</label>
                            <input type="time" name="departure_time" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Waktu Tiba *</label>
                            <input type="time" name="arrival_time" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tanggal *</label>
                            <input type="date" name="flight_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Kelas *</label>
                            <select name="class_type" class="form-select" required>
                                <option value="economy">Economy</option>
                                <option value="business">Business</option>
                                <option value="first_class">First Class</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Harga *</label>
                            <input type="number" name="price" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Total Kursi *</label>
                            <input type="number" name="total_seats" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="add_flight" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>