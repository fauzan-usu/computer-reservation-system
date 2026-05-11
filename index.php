<?php
$page_title = 'Beranda';
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';

// Fetch featured hotels
$hotels_sql = "SELECT * FROM hotels WHERE is_active = 1 ORDER BY star_rating DESC LIMIT 4";
$hotels_result = $conn->query($hotels_sql);

// Fetch featured flights
$flights_sql = "SELECT * FROM flights WHERE is_active = 1 AND flight_date >= CURDATE() ORDER BY flight_date LIMIT 4";
$flights_result = $conn->query($flights_sql);

// Fetch featured packages
$packages_sql = "SELECT * FROM tour_packages WHERE is_active = 1 AND start_date >= CURDATE() ORDER BY start_date LIMIT 4";
$packages_result = $conn->query($packages_sql);

// Statistics
$stats_sql = "SELECT 
    (SELECT COUNT(*) FROM hotels WHERE is_active = 1) as total_hotels,
    (SELECT COUNT(*) FROM flights WHERE is_active = 1) as total_flights,
    (SELECT COUNT(*) FROM tour_packages WHERE is_active = 1) as total_packages,
    (SELECT COUNT(*) FROM customers) as total_customers";
$stats = $conn->query($stats_sql)->fetch_assoc();
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3">Computer Reservation System</h1>
        <p class="lead mb-4">Sistem Reservasi Hotel, Penerbangan & Paket Wisata untuk SMK Pariwisata</p>
        <div class="row justify-content-center mt-5">
            <div class="col-md-3 mb-3">
                <div class="card bg-white text-dark p-3">
                    <h3 class="text-primary"><?php echo $stats['total_hotels']; ?></h3>
                    <p class="mb-0">Hotel Tersedia</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-white text-dark p-3">
                    <h3 class="text-primary"><?php echo $stats['total_flights']; ?></h3>
                    <p class="mb-0">Penerbangan</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-white text-dark p-3">
                    <h3 class="text-primary"><?php echo $stats['total_packages']; ?></h3>
                    <p class="mb-0">Paket Wisata</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-white text-dark p-3">
                    <h3 class="text-primary"><?php echo $stats['total_customers']; ?></h3>
                    <p class="mb-0">Pelanggan</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Hotels Section -->
<section id="hotels" class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-building me-2"></i>Hotel Populer</h2>
            <a href="customer/book_hotel.php" class="btn btn-custom">Lihat Semua</a>
        </div>
        <div class="row">
            <?php while($hotel = $hotels_result->fetch_assoc()): ?>
            <div class="col-md-3 mb-4">
                <div class="card card-hover h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <h5 class="card-title"><?php echo $hotel['name']; ?></h5>
                            <span class="badge bg-warning text-dark"><?php echo $hotel['star_rating']; ?>⭐</span>
                        </div>
                        <p class="text-muted"><i class="bi bi-geo-alt me-1"></i><?php echo $hotel['city']; ?></p>
                        <p class="card-text small"><?php echo substr($hotel['description'], 0, 100); ?>...</p>
                        <div class="mt-auto">
                            <span class="price-tag"><?php echo formatRupiah($hotel['price_per_night']); ?>/malam</span>
                            <a href="customer/book_hotel.php?hotel_id=<?php echo $hotel['id']; ?>" class="btn btn-sm btn-outline-primary float-end">Pesan</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- Flights Section -->
<section id="flights" class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-airplane me-2"></i>Penerbangan Tersedia</h2>
            <a href="customer/book_flight.php" class="btn btn-custom">Lihat Semua</a>
        </div>
        <div class="row">
            <?php while($flight = $flights_result->fetch_assoc()): ?>
            <div class="col-md-3 mb-4">
                <div class="card card-hover h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <h5 class="card-title"><?php echo $flight['airline']; ?></h5>
                            <span class="badge bg-info"><?php echo $flight['class_type']; ?></span>
                        </div>
                        <p class="text-muted"><?php echo $flight['flight_number']; ?></p>
                        <div class="d-flex justify-content-between align-items-center my-3">
                            <div class="text-center">
                                <h6><?php echo $flight['departure_city']; ?></h6>
                                <small><?php echo substr($flight['departure_time'], 0, 5); ?></small>
                            </div>
                            <div class="text-center px-2">
                                <i class="bi bi-arrow-right"></i>
                                <br><small class="text-muted"><?php echo $flight['flight_type'] == 'round_trip' ? 'PP' : 'Sekali'; ?></small>
                            </div>
                            <div class="text-center">
                                <h6><?php echo $flight['arrival_city']; ?></h6>
                                <small><?php echo substr($flight['arrival_time'], 0, 5); ?></small>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <span class="price-tag"><?php echo formatRupiah($flight['price']); ?></span>
                            <a href="customer/book_flight.php?flight_id=<?php echo $flight['id']; ?>" class="btn btn-sm btn-outline-primary float-end">Pesan</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- Packages Section -->
<section id="packages" class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-suitcase-lg me-2"></i>Paket Wisata</h2>
            <a href="customer/book_package.php" class="btn btn-custom">Lihat Semua</a>
        </div>
        <div class="row">
            <?php while($package = $packages_result->fetch_assoc()): ?>
            <div class="col-md-3 mb-4">
                <div class="card card-hover h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <h5 class="card-title"><?php echo $package['name']; ?></h5>
                            <span class="badge bg-success"><?php echo $package['duration_days']; ?>H <?php echo $package['duration_nights']; ?>M</span>
                        </div>
                        <p class="text-muted"><i class="bi bi-geo-alt me-1"></i><?php echo $package['destination']; ?></p>
                        <p class="card-text small"><?php echo substr($package['description'], 0, 100); ?>...</p>
                        <p class="small text-muted">Guide: <?php echo $package['guide_name']; ?></p>
                        <div class="mt-auto">
                            <span class="price-tag"><?php echo formatRupiah($package['price_per_person']); ?>/org</span>
                            <a href="customer/book_package.php?package_id=<?php echo $package['id']; ?>" class="btn btn-sm btn-outline-primary float-end">Pesan</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Mengapa Menggunakan CRS?</h2>
        <div class="row">
            <div class="col-md-4 text-center mb-4">
                <i class="bi bi-shield-check display-4 text-primary mb-3"></i>
                <h4>Aman & Terpercaya</h4>
                <p>Sistem dengan keamanan password hashing dan validasi data</p>
            </div>
            <div class="col-md-4 text-center mb-4">
                <i class="bi bi-lightning-charge display-4 text-primary mb-3"></i>
                <h4>Cepat & Responsif</h4>
                <p>Desain modern dengan Bootstrap 5 yang mobile-friendly</p>
            </div>
            <div class="col-md-4 text-center mb-4">
                <i class="bi bi-book display-4 text-primary mb-3"></i>
                <h4>Edukasi</h4>
                <p>Dibuat khusus untuk pembelajaran siswa SMK Pariwisata</p>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>