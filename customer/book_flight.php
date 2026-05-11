<?php
$page_title = 'Pesan Penerbangan';
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

$selected_flight = isset($_GET['flight_id']) ? (int)$_GET['flight_id'] : 0;

// Fetch all active flights
$flights = $conn->query("SELECT * FROM flights WHERE is_active = 1 AND flight_date >= CURDATE() ORDER BY flight_date, departure_time");

// Process booking
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_flight'])) {
    $flight_id = (int)$_POST['flight_id'];
    $passengers = (int)$_POST['passengers'];
    $passenger_details = sanitize($conn, $_POST['passenger_details']);
    $seat_preference = $_POST['seat_preference'];
    $meal_preference = $_POST['meal_preference'];
    $payment_method = $_POST['payment_method'];

    // Get flight price
    $price_query = $conn->prepare("SELECT price FROM flights WHERE id = ?");
    $price_query->bind_param('i', $flight_id);
    $price_query->execute();
    $price_result = $price_query->get_result()->fetch_assoc();
    $total_price = $price_result['price'] * $passengers;

    $booking_code = generateBookingCode('FBK');

    $insert = $conn->prepare("INSERT INTO flight_bookings (booking_code, customer_id, flight_id, passengers, passenger_details, seat_preference, meal_preference, total_price, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $insert->bind_param('siiisssds', $booking_code, $customer_id, $flight_id, $passengers, $passenger_details, $seat_preference, $meal_preference, $total_price, $payment_method);

    if ($insert->execute()) {
        $success = 'Pemesanan penerbangan berhasil! Kode booking: ' . $booking_code;
    } else {
        $error = 'Gagal memesan penerbangan. Silakan coba lagi.';
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
                <a href="book_flight.php" class="list-group-item list-group-item-action active">
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
            <h2 class="mb-4"><i class="bi bi-airplane me-2"></i>Pesan Penerbangan</h2>

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
                            <label class="form-label">Pilih Penerbangan *</label>
                            <select name="flight_id" class="form-select" required>
                                <option value="">-- Pilih Penerbangan --</option>
                                <?php while($flight = $flights->fetch_assoc()): ?>
                                    <option value="<?php echo $flight['id']; ?>" <?php echo $selected_flight == $flight['id'] ? 'selected' : ''; ?>>
                                        <?php echo $flight['airline']; ?> <?php echo $flight['flight_number']; ?> - 
                                        <?php echo $flight['departure_city']; ?> ke <?php echo $flight['arrival_city']; ?> 
                                        (<?php echo date('d M Y', strtotime($flight['flight_date'])); ?>)
                                        [<?php echo $flight['class_type']; ?>]
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jumlah Penumpang *</label>
                            <input type="number" name="passengers" class="form-control" required min="1" max="10" value="1">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Detail Penumpang</label>
                            <textarea name="passenger_details" class="form-control" rows="3" placeholder="Contoh: 1. John Doe (Dewasa), 2. Jane Doe (Anak)"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Preferensi Kursi</label>
                                <select name="seat_preference" class="form-select">
                                    <option value="">Tidak ada preferensi</option>
                                    <option value="Window">Window (Jendela)</option>
                                    <option value="Aisle">Aisle (Gang)</option>
                                    <option value="Middle">Middle (Tengah)</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Preferensi Makanan</label>
                                <select name="meal_preference" class="form-select">
                                    <option value="">Tidak ada preferensi</option>
                                    <option value="Halal">Halal</option>
                                    <option value="Vegetarian">Vegetarian</option>
                                    <option value="Vegan">Vegan</option>
                                    <option value="Kosher">Kosher</option>
                                </select>
                            </div>
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

                        <button type="submit" name="book_flight" class="btn btn-custom w-100">
                            <i class="bi bi-check-circle me-2"></i>Pesan Sekarang
                        </button>
                    </form>
                </div>
            </div>

            <h4 class="mb-3">Jadwal Penerbangan</h4>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Maskapai</th>
                            <th>Rute</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $flights->data_seek(0);
                        while($flight = $flights->fetch_assoc()): 
                        ?>
                        <tr>
                            <td>
                                <strong><?php echo $flight['airline']; ?></strong><br>
                                <small><?php echo $flight['flight_number']; ?></small>
                            </td>
                            <td>
                                <?php echo $flight['departure_city']; ?> 
                                <i class="bi bi-arrow-right"></i> 
                                <?php echo $flight['arrival_city']; ?>
                                <?php if($flight['flight_type'] == 'round_trip'): ?>
                                    <span class="badge bg-info">PP</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('d M Y', strtotime($flight['flight_date'])); ?></td>
                            <td>
                                <?php echo substr($flight['departure_time'], 0, 5); ?> - 
                                <?php echo substr($flight['arrival_time'], 0, 5); ?>
                            </td>
                            <td><?php echo formatRupiah($flight['price']); ?></td>
                            <td>
                                <a href="?flight_id=<?php echo $flight['id']; ?>" class="btn btn-sm btn-outline-primary">Pilih</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>