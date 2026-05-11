<?php
$page_title = 'Pesan Hotel';
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

// Get hotel ID from URL if provided
$selected_hotel = isset($_GET['hotel_id']) ? (int)$_GET['hotel_id'] : 0;

// Fetch all active hotels
$hotels = $conn->query("SELECT * FROM hotels WHERE is_active = 1 ORDER BY name");

// Process booking
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_hotel'])) {
    $hotel_id = (int)$_POST['hotel_id'];
    $room_id = !empty($_POST['room_id']) ? (int)$_POST['room_id'] : null;
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $guests = (int)$_POST['guests'];
    $guest_names = sanitize($conn, $_POST['guest_names']);
    $special_requests = sanitize($conn, $_POST['special_requests']);
    $payment_method = $_POST['payment_method'];

    // Calculate nights and total price
    $check_in_date = new DateTime($check_in);
    $check_out_date = new DateTime($check_out);
    $nights = $check_in_date->diff($check_out_date)->days;

    // Get hotel/room price
    if ($room_id) {
        $price_query = $conn->prepare("SELECT price_per_night FROM hotel_rooms WHERE id = ? AND hotel_id = ?");
        $price_query->bind_param('ii', $room_id, $hotel_id);
    } else {
        $price_query = $conn->prepare("SELECT price_per_night FROM hotels WHERE id = ?");
        $price_query->bind_param('i', $hotel_id);
    }
    $price_query->execute();
    $price_result = $price_query->get_result()->fetch_assoc();
    $price_per_night = $price_result['price_per_night'];
    $total_price = $price_per_night * $nights * $guests;

    $booking_code = generateBookingCode('HBK');

    $insert = $conn->prepare("INSERT INTO hotel_bookings (booking_code, customer_id, hotel_id, room_id, check_in, check_out, nights, guests, guest_names, special_requests, total_price, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $insert->bind_param('siiissiissds', $booking_code, $customer_id, $hotel_id, $room_id, $check_in, $check_out, $nights, $guests, $guest_names, $special_requests, $total_price, $payment_method);

    if ($insert->execute()) {
        $success = 'Pemesanan hotel berhasil! Kode booking: ' . $booking_code;
    } else {
        $error = 'Gagal memesan hotel. Silakan coba lagi.';
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
                <a href="book_hotel.php" class="list-group-item list-group-item-action active">
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
            <h2 class="mb-4"><i class="bi bi-building me-2"></i>Pesan Hotel</h2>

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
                            <label class="form-label">Pilih Hotel *</label>
                            <select name="hotel_id" class="form-select" id="hotel_select" required onchange="loadRooms(this.value)">
                                <option value="">-- Pilih Hotel --</option>
                                <?php while($hotel = $hotels->fetch_assoc()): ?>
                                    <option value="<?php echo $hotel['id']; ?>" <?php echo $selected_hotel == $hotel['id'] ? 'selected' : ''; ?>>
                                        <?php echo $hotel['name']; ?> - <?php echo $hotel['city']; ?> (<?php echo $hotel['star_rating']; ?>⭐)
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipe Kamar (Opsional)</label>
                            <select name="room_id" class="form-select" id="room_select">
                                <option value="">-- Pilih Kamar --</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Check-in *</label>
                                <input type="date" name="check_in" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Check-out *</label>
                                <input type="date" name="check_out" class="form-control" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jumlah Tamu *</label>
                            <input type="number" name="guests" class="form-control" required min="1" max="10" value="1">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Tamu</label>
                            <textarea name="guest_names" class="form-control" rows="2" placeholder="Contoh: John Doe, Jane Doe"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Permintaan Khusus</label>
                            <textarea name="special_requests" class="form-control" rows="2" placeholder="Contoh: Kamar non-smoking, lantai tinggi"></textarea>
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

                        <button type="submit" name="book_hotel" class="btn btn-custom w-100">
                            <i class="bi bi-check-circle me-2"></i>Pesan Sekarang
                        </button>
                    </form>
                </div>
            </div>

            <h4 class="mb-3">Daftar Hotel</h4>
            <div class="row">
                <?php 
                $hotels->data_seek(0);
                while($hotel = $hotels->fetch_assoc()): 
                ?>
                <div class="col-md-6 mb-3">
                    <div class="card card-hover">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5><?php echo $hotel['name']; ?></h5>
                                <span class="badge bg-warning text-dark"><?php echo $hotel['star_rating']; ?>⭐</span>
                            </div>
                            <p class="text-muted small">
                                <i class="bi bi-geo-alt me-1"></i><?php echo $hotel['city']; ?> | 
                                <i class="bi bi-door-open me-1"></i><?php echo $hotel['available_rooms']; ?> kamar tersedia
                            </p>
                            <p class="small"><?php echo substr($hotel['description'], 0, 80); ?>...</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price-tag"><?php echo formatRupiah($hotel['price_per_night']); ?>/malam</span>
                                <a href="?hotel_id=<?php echo $hotel['id']; ?>" class="btn btn-sm btn-outline-primary">Pilih</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>

<script>
function loadRooms(hotelId) {
    if (!hotelId) return;

    // In real implementation, this would fetch rooms via AJAX
    // For now, we'll just show a placeholder
    var roomSelect = document.getElementById('room_select');
    roomSelect.innerHTML = '<option value="">-- Memuat kamar... --</option>';

    // Simulate loading - in production, use fetch() to get rooms from server
    setTimeout(function() {
        roomSelect.innerHTML = '<option value="">-- Pilih Kamar --</option><option value="">Kamar Standar</option><option value="">Kamar Deluxe</option><option value="">Suite</option>';
    }, 500);
}

// Pre-select hotel if provided in URL
<?php if ($selected_hotel > 0): ?>
document.addEventListener('DOMContentLoaded', function() {
    loadRooms(<?php echo $selected_hotel; ?>);
});
<?php endif; ?>
</script>

<?php require_once '../includes/footer.php'; ?>