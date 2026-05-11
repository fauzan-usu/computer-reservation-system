<?php
$page_title = 'Pesan Paket Wisata';
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

$selected_package = isset($_GET['package_id']) ? (int)$_GET['package_id'] : 0;

// Fetch all active packages
$packages = $conn->query("SELECT * FROM tour_packages WHERE is_active = 1 AND start_date >= CURDATE() ORDER BY start_date");

// Process booking
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_package'])) {
    $package_id = (int)$_POST['package_id'];
    $participants = (int)$_POST['participants'];
    $participant_details = sanitize($conn, $_POST['participant_details']);
    $travel_date = $_POST['travel_date'];
    $special_requests = sanitize($conn, $_POST['special_requests']);
    $payment_method = $_POST['payment_method'];

    // Get package price
    $price_query = $conn->prepare("SELECT price_per_person FROM tour_packages WHERE id = ?");
    $price_query->bind_param('i', $package_id);
    $price_query->execute();
    $price_result = $price_query->get_result()->fetch_assoc();
    $total_price = $price_result['price_per_person'] * $participants;

    $booking_code = generateBookingCode('PBK');

    $insert = $conn->prepare("INSERT INTO package_bookings (booking_code, customer_id, package_id, participants, participant_details, travel_date, special_requests, total_price, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $insert->bind_param('siiisssds', $booking_code, $customer_id, $package_id, $participants, $participant_details, $travel_date, $special_requests, $total_price, $payment_method);

    if ($insert->execute()) {
        $success = 'Pemesanan paket wisata berhasil! Kode booking: ' . $booking_code;
    } else {
        $error = 'Gagal memesan paket wisata. Silakan coba lagi.';
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
                <a href="book_package.php" class="list-group-item list-group-item-action active">
                    <i class="bi bi-suitcase-lg me-2"></i>Pesan Paket Wisata
                </a>
                <a href="profile.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-person me-2"></i>Profil
                </a>
            </div>
        </div>

        <div class="col-md-9">
            <h2 class="mb-4"><i class="bi bi-suitcase-lg me-2"></i>Pesan Paket Wisata</h2>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="card booking-form mb-4">
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Pilih Paket Wisata *</label>
                            <select name="package_id" class="form-select" required>
                                <option value="">-- Pilih Paket --</option>
                                <?php while($package = $packages->fetch_assoc()): ?>
                                    <option value="<?php echo $package['id']; ?>" <?php echo $selected_package == $package['id'] ? 'selected' : ''; ?>>
                                        <?php echo $package['name']; ?> - <?php echo $package['destination']; ?> 
                                        (<?php echo $package['duration_days']; ?>H <?php echo $package['duration_nights']; ?>M)
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jumlah Peserta *</label>
                            <input type="number" name="participants" class="form-control" required min="1" max="20" value="1">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Detail Peserta</label>
                            <textarea name="participant_details" class="form-control" rows="3" placeholder="Contoh: 1. John Doe (Dewasa), 2. Jane Doe (Anak 10 tahun)"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Keberangkatan *</label>
                            <input type="date" name="travel_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Permintaan Khusus</label>
                            <textarea name="special_requests" class="form-control" rows="2" placeholder="Contoh: Vegetarian meal, kamar twin bed"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Metode Pembayaran *</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="cash">Cash / Tunai</option>
                                <option value="transfer">Bank Transfer</option>
                                <option value="credit_card">Kartu Kredit</option>
                                <option value="e_wallet">E-Wallet</option>
                            </select>
                        </div>

                        <button type="submit" name="book_package" class="btn btn-custom w-100">
                            <i class="bi bi-check-circle me-2"></i>Pesan Sekarang
                        </button>
                    </form>
                </div>
            </div>

            <h4 class="mb-3">Paket Wisata Tersedia</h4>
            <div class="row">
                <?php 
                $packages->data_seek(0);
                while($package = $packages->fetch_assoc()): 
                ?>
                <div class="col-md-6 mb-3">
                    <div class="card card-hover h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5><?php echo $package['name']; ?></h5>
                                <span class="badge bg-success"><?php echo $package['duration_days']; ?>H <?php echo $package['duration_nights']; ?>M</span>
                            </div>
                            <p class="text-muted small">
                                <i class="bi bi-geo-alt me-1"></i><?php echo $package['destination']; ?> | 
                                <i class="bi bi-people me-1"></i><?php echo $package['available_slots']; ?> slot tersedia
                            </p>
                            <p class="small"><?php echo substr($package['description'], 0, 100); ?>...</p>
                            <p class="small"><i class="bi bi-person-badge me-1"></i>Guide: <?php echo $package['guide_name']; ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price-tag"><?php echo formatRupiah($package['price_per_person']); ?>/org</span>
                                <a href="?package_id=<?php echo $package['id']; ?>" class="btn btn-sm btn-outline-primary">Pilih</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>