<?php
$page_title = 'Pesanan Saya';
$base_path = '../';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';

if (!isset($_SESSION['customer_id'])) {
    redirect('login.php');
}

$customer_id = $_SESSION['customer_id'];

// Fetch all bookings
$hotel_bookings = $conn->prepare("SELECT hb.*, h.name as hotel_name, h.city 
    FROM hotel_bookings hb 
    JOIN hotels h ON hb.hotel_id = h.id 
    WHERE hb.customer_id = ? 
    ORDER BY hb.created_at DESC");
$hotel_bookings->bind_param('i', $customer_id);
$hotel_bookings->execute();
$hotel_result = $hotel_bookings->get_result();

$flight_bookings = $conn->prepare("SELECT fb.*, f.airline, f.flight_number, f.departure_city, f.arrival_city 
    FROM flight_bookings fb 
    JOIN flights f ON fb.flight_id = f.id 
    WHERE fb.customer_id = ? 
    ORDER BY fb.created_at DESC");
$flight_bookings->bind_param('i', $customer_id);
$flight_bookings->execute();
$flight_result = $flight_bookings->get_result();

$package_bookings = $conn->prepare("SELECT pb.*, tp.name as package_name, tp.destination 
    FROM package_bookings pb 
    JOIN tour_packages tp ON pb.package_id = tp.id 
    WHERE pb.customer_id = ? 
    ORDER BY pb.created_at DESC");
$package_bookings->bind_param('i', $customer_id);
$package_bookings->execute();
$package_result = $package_bookings->get_result();
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="list-group mb-4">
                <a href="index.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>
                <a href="my_bookings.php" class="list-group-item list-group-item-action active">
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
            </div>
        </div>

        <div class="col-md-9">
            <h2 class="mb-4"><i class="bi bi-calendar-check me-2"></i>Pesanan Saya</h2>

            <?php showFlash(); ?>

            <!-- Hotel Bookings -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-building me-2"></i>Pemesanan Hotel</h5>
                </div>
                <div class="card-body">
                    <?php if ($hotel_result->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Hotel</th>
                                        <th>Check-in/out</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($booking = $hotel_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><strong><?php echo $booking['booking_code']; ?></strong></td>
                                        <td>
                                            <?php echo $booking['hotel_name']; ?><br>
                                            <small class="text-muted"><?php echo $booking['city']; ?></small>
                                        </td>
                                        <td>
                                            <?php echo formatDate($booking['check_in']); ?> -<br>
                                            <?php echo formatDate($booking['check_out']); ?>
                                        </td>
                                        <td><?php echo formatRupiah($booking['total_price']); ?></td>
                                        <td>
                                            <span class="status-badge bg-<?php echo getStatusColor($booking['status']); ?>">
                                                <?php echo translateStatus($booking['status']); ?>
                                            </span>
                                            <br>
                                            <small class="text-muted"><?php echo translateStatus($booking['payment_status']); ?></small>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center mb-0">Belum ada pemesanan hotel</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Flight Bookings -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-airplane me-2"></i>Pemesanan Penerbangan</h5>
                </div>
                <div class="card-body">
                    <?php if ($flight_result->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Penerbangan</th>
                                        <th>Rute</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($booking = $flight_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><strong><?php echo $booking['booking_code']; ?></strong></td>
                                        <td>
                                            <?php echo $booking['airline']; ?><br>
                                            <small class="text-muted"><?php echo $booking['flight_number']; ?></small>
                                        </td>
                                        <td>
                                            <?php echo $booking['departure_city']; ?> 
                                            <i class="bi bi-arrow-right"></i> 
                                            <?php echo $booking['arrival_city']; ?>
                                        </td>
                                        <td><?php echo formatRupiah($booking['total_price']); ?></td>
                                        <td>
                                            <span class="status-badge bg-<?php echo getStatusColor($booking['status']); ?>">
                                                <?php echo translateStatus($booking['status']); ?>
                                            </span>
                                            <br>
                                            <small class="text-muted"><?php echo translateStatus($booking['payment_status']); ?></small>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center mb-0">Belum ada pemesanan penerbangan</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Package Bookings -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-suitcase-lg me-2"></i>Pemesanan Paket Wisata</h5>
                </div>
                <div class="card-body">
                    <?php if ($package_result->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Paket</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($booking = $package_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><strong><?php echo $booking['booking_code']; ?></strong></td>
                                        <td>
                                            <?php echo $booking['package_name']; ?><br>
                                            <small class="text-muted"><?php echo $booking['destination']; ?></small>
                                        </td>
                                        <td><?php echo formatDate($booking['travel_date']); ?></td>
                                        <td><?php echo formatRupiah($booking['total_price']); ?></td>
                                        <td>
                                            <span class="status-badge bg-<?php echo getStatusColor($booking['status']); ?>">
                                                <?php echo translateStatus($booking['status']); ?>
                                            </span>
                                            <br>
                                            <small class="text-muted"><?php echo translateStatus($booking['payment_status']); ?></small>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center mb-0">Belum ada pemesanan paket wisata</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>