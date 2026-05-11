<?php
$page_title = 'Dashboard Admin';
$base_path = '../';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';

// Check admin login
if (!isset($_SESSION['admin_id'])) {
    redirect('login.php');
}

// Statistics
$stats_sql = "SELECT 
    (SELECT COUNT(*) FROM hotels WHERE is_active = 1) as total_hotels,
    (SELECT COUNT(*) FROM flights WHERE is_active = 1) as total_flights,
    (SELECT COUNT(*) FROM tour_packages WHERE is_active = 1) as total_packages,
    (SELECT COUNT(*) FROM customers) as total_customers,
    (SELECT COUNT(*) FROM hotel_bookings) as total_hotel_bookings,
    (SELECT COUNT(*) FROM flight_bookings) as total_flight_bookings,
    (SELECT COUNT(*) FROM package_bookings) as total_package_bookings,
    (SELECT SUM(total_price) FROM hotel_bookings WHERE payment_status = 'paid') as hotel_revenue,
    (SELECT SUM(total_price) FROM flight_bookings WHERE payment_status = 'paid') as flight_revenue,
    (SELECT SUM(total_price) FROM package_bookings WHERE payment_status = 'paid') as package_revenue";
$stats = $conn->query($stats_sql)->fetch_assoc();

$total_revenue = ($stats['hotel_revenue'] ?? 0) + ($stats['flight_revenue'] ?? 0) + ($stats['package_revenue'] ?? 0);

// Recent bookings
$recent_bookings = $conn->query("SELECT 'hotel' as type, booking_code, created_at, total_price, status FROM hotel_bookings 
    UNION ALL 
    SELECT 'flight' as type, booking_code, created_at, total_price, status FROM flight_bookings 
    UNION ALL 
    SELECT 'package' as type, booking_code, created_at, total_price, status FROM package_bookings 
    ORDER BY created_at DESC LIMIT 10");
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 d-none d-md-block sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">
                            <i class="bi bi-speedometer2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="hotels.php">
                            <i class="bi bi-building"></i>Kelola Hotel
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="flights.php">
                            <i class="bi bi-airplane"></i>Kelola Penerbangan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="packages.php">
                            <i class="bi bi-suitcase-lg"></i>Kelola Paket Wisata
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="bookings.php">
                            <i class="bi bi-calendar-check"></i>Semua Pemesanan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="customers.php">
                            <i class="bi bi-people"></i>Kelola Customer
                        </a>
                    </li>
                    <li class="nav-item mt-4">
                        <a class="nav-link text-danger" href="logout.php">
                            <i class="bi bi-box-arrow-right"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-10 ms-sm-auto px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
                <h1 class="h2"><i class="bi bi-speedometer2 me-2"></i>Dashboard Admin</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <span class="text-muted">Selamat datang, <?php echo $_SESSION['admin_name']; ?></span>
                </div>
            </div>

            <?php showFlash(); ?>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card dashboard-card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Total Hotel</h6>
                                    <h3><?php echo $stats['total_hotels']; ?></h3>
                                </div>
                                <i class="bi bi-building display-4 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card dashboard-card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Total Penerbangan</h6>
                                    <h3><?php echo $stats['total_flights']; ?></h3>
                                </div>
                                <i class="bi bi-airplane display-4 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card dashboard-card bg-warning text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Paket Wisata</h6>
                                    <h3><?php echo $stats['total_packages']; ?></h3>
                                </div>
                                <i class="bi bi-suitcase-lg display-4 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card dashboard-card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Total Customer</h6>
                                    <h3><?php echo $stats['total_customers']; ?></h3>
                                </div>
                                <i class="bi bi-people display-4 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <h6 class="text-muted">Pemesanan Hotel</h6>
                            <h3 class="text-primary"><?php echo $stats['total_hotel_bookings']; ?></h3>
                            <small class="text-success">
                                <i class="bi bi-cash-stack me-1"></i>
                                <?php echo formatRupiah($stats['hotel_revenue'] ?? 0); ?>
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <h6 class="text-muted">Pemesanan Penerbangan</h6>
                            <h3 class="text-success"><?php echo $stats['total_flight_bookings']; ?></h3>
                            <small class="text-success">
                                <i class="bi bi-cash-stack me-1"></i>
                                <?php echo formatRupiah($stats['flight_revenue'] ?? 0); ?>
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <h6 class="text-muted">Pemesanan Paket Wisata</h6>
                            <h3 class="text-warning"><?php echo $stats['total_package_bookings']; ?></h3>
                            <small class="text-success">
                                <i class="bi bi-cash-stack me-1"></i>
                                <?php echo formatRupiah($stats['package_revenue'] ?? 0); ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="card dashboard-card mb-4">
                <div class="card-body text-center">
                    <h5 class="text-muted">Total Pendapatan</h5>
                    <h2 class="text-primary"><?php echo formatRupiah($total_revenue); ?></h2>
                </div>
            </div>

            <!-- Recent Bookings -->
            <h4 class="mb-3"><i class="bi bi-clock-history me-2"></i>Pemesanan Terbaru</h4>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Kode Booking</th>
                            <th>Tipe</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($booking = $recent_bookings->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?php echo $booking['booking_code']; ?></strong></td>
                            <td>
                                <?php 
                                $icons = ['hotel' => 'building', 'flight' => 'airplane', 'package' => 'suitcase-lg'];
                                $colors = ['hotel' => 'primary', 'flight' => 'success', 'package' => 'warning'];
                                ?>
                                <span class="badge bg-<?php echo $colors[$booking['type']]; ?>">
                                    <i class="bi bi-<?php echo $icons[$booking['type']]; ?> me-1"></i>
                                    <?php echo ucfirst($booking['type']); ?>
                                </span>
                            </td>
                            <td><?php echo date('d M Y', strtotime($booking['created_at'])); ?></td>
                            <td><?php echo formatRupiah($booking['total_price']); ?></td>
                            <td>
                                <span class="status-badge bg-<?php echo getStatusColor($booking['status']); ?>">
                                    <?php echo translateStatus($booking['status']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>