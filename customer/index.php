<?php
$page_title = 'Dashboard Customer';
$base_path = '../';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';

if (!isset($_SESSION['customer_id'])) {
    redirect('login.php');
}

$customer_id = $_SESSION['customer_id'];

// Get customer info
$customer = $conn->prepare("SELECT * FROM customers WHERE id = ?");
$customer->bind_param('i', $customer_id);
$customer->execute();
$customer_info = $customer->get_result()->fetch_assoc();

// Count bookings
$hotel_count = $conn->query("SELECT COUNT(*) as count FROM hotel_bookings WHERE customer_id = $customer_id")->fetch_assoc()['count'];
$flight_count = $conn->query("SELECT COUNT(*) as count FROM flight_bookings WHERE customer_id = $customer_id")->fetch_assoc()['count'];
$package_count = $conn->query("SELECT COUNT(*) as count FROM package_bookings WHERE customer_id = $customer_id")->fetch_assoc()['count'];

// Recent bookings
$recent_sql = "SELECT 'hotel' as type, hb.booking_code, h.name as item_name, hb.total_price, hb.status, hb.created_at 
    FROM hotel_bookings hb JOIN hotels h ON hb.hotel_id = h.id WHERE hb.customer_id = ?
    UNION ALL
    SELECT 'flight' as type, fb.booking_code, f.flight_number as item_name, fb.total_price, fb.status, fb.created_at 
    FROM flight_bookings fb JOIN flights f ON fb.flight_id = f.id WHERE fb.customer_id = ?
    UNION ALL
    SELECT 'package' as type, pb.booking_code, tp.name as item_name, pb.total_price, pb.status, pb.created_at 
    FROM package_bookings pb JOIN tour_packages tp ON pb.package_id = tp.id WHERE pb.customer_id = ?
    ORDER BY created_at DESC LIMIT 5";

$recent = $conn->prepare($recent_sql);
$recent->bind_param('iii', $customer_id, $customer_id, $customer_id);
$recent->execute();
$recent_bookings = $recent->get_result();
?>

<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-person-circle display-1 text-primary"></i>
                    </div>
                    <h5><?php echo $customer_info['full_name']; ?></h5>
                    <p class="text-muted mb-1">@<?php echo $customer_info['username']; ?></p>
                    <p class="text-muted small"><?php echo $customer_info['email']; ?></p>
                </div>
            </div>

            <div class="list-group mb-4">
                <a href="index.php" class="list-group-item list-group-item-action active">
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
                <a href="profile.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-person me-2"></i>Profil
                </a>
                <a href="logout.php" class="list-group-item list-group-item-action text-danger">
                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <h2 class="mb-4"><i class="bi bi-speedometer2 me-2"></i>Dashboard</h2>

            <?php showFlash(); ?>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card dashboard-card border-primary">
                        <div class="card-body text-center">
                            <i class="bi bi-building display-4 text-primary mb-2"></i>
                            <h4><?php echo $hotel_count; ?></h4>
                            <p class="text-muted mb-0">Pemesanan Hotel</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card dashboard-card border-success">
                        <div class="card-body text-center">
                            <i class="bi bi-airplane display-4 text-success mb-2"></i>
                            <h4><?php echo $flight_count; ?></h4>
                            <p class="text-muted mb-0">Pemesanan Penerbangan</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card dashboard-card border-warning">
                        <div class="card-body text-center">
                            <i class="bi bi-suitcase-lg display-4 text-warning mb-2"></i>
                            <h4><?php echo $package_count; ?></h4>
                            <p class="text-muted mb-0">Pemesanan Paket Wisata</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <h4 class="mb-3">Pesan Sekarang</h4>
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <a href="book_hotel.php" class="text-decoration-none">
                        <div class="card card-hover h-100">
                            <div class="card-body text-center">
                                <i class="bi bi-building display-4 text-primary mb-3"></i>
                                <h5>Hotel</h5>
                                <p class="text-muted small">Pesan kamar hotel terbaik</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 mb-3">
                    <a href="book_flight.php" class="text-decoration-none">
                        <div class="card card-hover h-100">
                            <div class="card-body text-center">
                                <i class="bi bi-airplane display-4 text-success mb-3"></i>
                                <h5>Penerbangan</h5>
                                <p class="text-muted small">Pesan tiket pesawat</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 mb-3">
                    <a href="book_package.php" class="text-decoration-none">
                        <div class="card card-hover h-100">
                            <div class="card-body text-center">
                                <i class="bi bi-suitcase-lg display-4 text-warning mb-3"></i>
                                <h5>Paket Wisata</h5>
                                <p class="text-muted small">Jelajahi destinasi menarik</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Recent Bookings -->
            <h4 class="mb-3"><i class="bi bi-clock-history me-2"></i>Pemesanan Terbaru</h4>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Kode</th>
                            <th>Tipe</th>
                            <th>Item</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($recent_bookings->num_rows > 0): ?>
                            <?php while($booking = $recent_bookings->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $booking['booking_code']; ?></td>
                                <td>
                                    <?php 
                                    $type_labels = ['hotel' => 'Hotel', 'flight' => 'Penerbangan', 'package' => 'Paket'];
                                    $type_colors = ['hotel' => 'primary', 'flight' => 'success', 'package' => 'warning'];
                                    ?>
                                    <span class="badge bg-<?php echo $type_colors[$booking['type']]; ?>">
                                        <?php echo $type_labels[$booking['type']]; ?>
                                    </span>
                                </td>
                                <td><?php echo $booking['item_name']; ?></td>
                                <td><?php echo formatRupiah($booking['total_price']); ?></td>
                                <td>
                                    <span class="status-badge bg-<?php echo getStatusColor($booking['status']); ?>">
                                        <?php echo translateStatus($booking['status']); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada pemesanan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="text-center">
                <a href="my_bookings.php" class="btn btn-outline-primary">
                    <i class="bi bi-list me-2"></i>Lihat Semua Pesanan
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>