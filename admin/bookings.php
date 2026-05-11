<?php
$page_title = 'Semua Pemesanan';
$base_path = '../';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';

if (!isset($_SESSION['admin_id'])) {
    redirect('login.php');
}

// Update status
if (isset($_POST['update_status'])) {
    $booking_id = (int)$_POST['booking_id'];
    $booking_type = $_POST['booking_type'];
    $new_status = $_POST['status'];

    $table = $booking_type . '_bookings';
    $update = $conn->prepare("UPDATE $table SET status = ? WHERE id = ?");
    $update->bind_param('si', $new_status, $booking_id);
    $update->execute();
}

// Fetch all bookings with customer info
$hotel_bookings = $conn->query("SELECT hb.*, h.name as hotel_name, c.full_name as customer_name, c.phone as customer_phone 
    FROM hotel_bookings hb 
    JOIN hotels h ON hb.hotel_id = h.id 
    JOIN customers c ON hb.customer_id = c.id 
    ORDER BY hb.created_at DESC");

$flight_bookings = $conn->query("SELECT fb.*, f.airline, f.flight_number, f.departure_city, f.arrival_city, c.full_name as customer_name 
    FROM flight_bookings fb 
    JOIN flights f ON fb.flight_id = f.id 
    JOIN customers c ON fb.customer_id = c.id 
    ORDER BY fb.created_at DESC");

$package_bookings = $conn->query("SELECT pb.*, tp.name as package_name, tp.destination, c.full_name as customer_name 
    FROM package_bookings pb 
    JOIN tour_packages tp ON pb.package_id = tp.id 
    JOIN customers c ON pb.customer_id = c.id 
    ORDER BY pb.created_at DESC");
?>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="index.php"><i class="bi bi-speedometer2"></i>Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="hotels.php"><i class="bi bi-building"></i>Kelola Hotel</a></li>
                    <li class="nav-item"><a class="nav-link" href="flights.php"><i class="bi bi-airplane"></i>Kelola Penerbangan</a></li>
                    <li class="nav-item"><a class="nav-link" href="packages.php"><i class="bi bi-suitcase-lg"></i>Kelola Paket Wisata</a></li>
                    <li class="nav-item"><a class="nav-link active" href="bookings.php"><i class="bi bi-calendar-check"></i>Semua Pemesanan</a></li>
                    <li class="nav-item"><a class="nav-link" href="customers.php"><i class="bi bi-people"></i>Kelola Customer</a></li>
                    <li class="nav-item mt-4"><a class="nav-link text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i>Logout</a></li>
                </ul>
            </div>
        </nav>

        <main class="col-md-10 ms-sm-auto px-md-4 py-4">
            <h1 class="h2 mb-4"><i class="bi bi-calendar-check me-2"></i>Semua Pemesanan</h1>

            <!-- Hotel Bookings -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-building me-2"></i>Pemesanan Hotel</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Customer</th>
                                    <th>Hotel</th>
                                    <th>Check-in</th>
                                    <th>Check-out</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($booking = $hotel_bookings->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $booking['booking_code']; ?></td>
                                    <td><?php echo $booking['customer_name']; ?><br><small><?php echo $booking['customer_phone']; ?></small></td>
                                    <td><?php echo $booking['hotel_name']; ?></td>
                                    <td><?php echo formatDate($booking['check_in']); ?></td>
                                    <td><?php echo formatDate($booking['check_out']); ?></td>
                                    <td><?php echo formatRupiah($booking['total_price']); ?></td>
                                    <td>
                                        <span class="status-badge bg-<?php echo getStatusColor($booking['status']); ?>">
                                            <?php echo translateStatus($booking['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST" action="" class="d-flex gap-1">
                                            <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                            <input type="hidden" name="booking_type" value="hotel">
                                            <select name="status" class="form-select form-select-sm" style="width: 120px;">
                                                <option value="pending" <?php echo $booking['status'] == 'pending' ? 'selected' : ''; ?>>Menunggu</option>
                                                <option value="confirmed" <?php echo $booking['status'] == 'confirmed' ? 'selected' : ''; ?>>Dikonfirmasi</option>
                                                <option value="checked_in" <?php echo $booking['status'] == 'checked_in' ? 'selected' : ''; ?>>Check-in</option>
                                                <option value="checked_out" <?php echo $booking['status'] == 'checked_out' ? 'selected' : ''; ?>>Check-out</option>
                                                <option value="cancelled" <?php echo $booking['status'] == 'cancelled' ? 'selected' : ''; ?>>Dibatalkan</option>
                                            </select>
                                            <button type="submit" name="update_status" class="btn btn-sm btn-primary">Update</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Flight Bookings -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-airplane me-2"></i>Pemesanan Penerbangan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Customer</th>
                                    <th>Penerbangan</th>
                                    <th>Rute</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($booking = $flight_bookings->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $booking['booking_code']; ?></td>
                                    <td><?php echo $booking['customer_name']; ?></td>
                                    <td><?php echo $booking['airline']; ?> <?php echo $booking['flight_number']; ?></td>
                                    <td><?php echo $booking['departure_city']; ?> → <?php echo $booking['arrival_city']; ?></td>
                                    <td><?php echo formatRupiah($booking['total_price']); ?></td>
                                    <td>
                                        <span class="status-badge bg-<?php echo getStatusColor($booking['status']); ?>">
                                            <?php echo translateStatus($booking['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST" action="" class="d-flex gap-1">
                                            <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                            <input type="hidden" name="booking_type" value="flight">
                                            <select name="status" class="form-select form-select-sm" style="width: 120px;">
                                                <option value="pending" <?php echo $booking['status'] == 'pending' ? 'selected' : ''; ?>>Menunggu</option>
                                                <option value="confirmed" <?php echo $booking['status'] == 'confirmed' ? 'selected' : ''; ?>>Dikonfirmasi</option>
                                                <option value="boarded" <?php echo $booking['status'] == 'boarded' ? 'selected' : ''; ?>>Naik Pesawat</option>
                                                <option value="completed" <?php echo $booking['status'] == 'completed' ? 'selected' : ''; ?>>Selesai</option>
                                                <option value="cancelled" <?php echo $booking['status'] == 'cancelled' ? 'selected' : ''; ?>>Dibatalkan</option>
                                            </select>
                                            <button type="submit" name="update_status" class="btn btn-sm btn-primary">Update</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Package Bookings -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-suitcase-lg me-2"></i>Pemesanan Paket Wisata</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Customer</th>
                                    <th>Paket</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($booking = $package_bookings->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $booking['booking_code']; ?></td>
                                    <td><?php echo $booking['customer_name']; ?></td>
                                    <td><?php echo $booking['package_name']; ?><br><small><?php echo $booking['destination']; ?></small></td>
                                    <td><?php echo formatDate($booking['travel_date']); ?></td>
                                    <td><?php echo formatRupiah($booking['total_price']); ?></td>
                                    <td>
                                        <span class="status-badge bg-<?php echo getStatusColor($booking['status']); ?>">
                                            <?php echo translateStatus($booking['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST" action="" class="d-flex gap-1">
                                            <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                            <input type="hidden" name="booking_type" value="package">
                                            <select name="status" class="form-select form-select-sm" style="width: 120px;">
                                                <option value="pending" <?php echo $booking['status'] == 'pending' ? 'selected' : ''; ?>>Menunggu</option>
                                                <option value="confirmed" <?php echo $booking['status'] == 'confirmed' ? 'selected' : ''; ?>>Dikonfirmasi</option>
                                                <option value="ongoing" <?php echo $booking['status'] == 'ongoing' ? 'selected' : ''; ?>>Berlangsung</option>
                                                <option value="completed" <?php echo $booking['status'] == 'completed' ? 'selected' : ''; ?>>Selesai</option>
                                                <option value="cancelled" <?php echo $booking['status'] == 'cancelled' ? 'selected' : ''; ?>>Dibatalkan</option>
                                            </select>
                                            <button type="submit" name="update_status" class="btn btn-sm btn-primary">Update</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>