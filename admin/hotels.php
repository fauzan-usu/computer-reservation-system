<?php
$page_title = 'Kelola Hotel';
$base_path = '../';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';

if (!isset($_SESSION['admin_id'])) {
    redirect('login.php');
}

$success = '';
$error = '';

// Add/Edit Hotel
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_hotel'])) {
        $name = sanitize($conn, $_POST['name']);
        $description = sanitize($conn, $_POST['description']);
        $location = sanitize($conn, $_POST['location']);
        $city = sanitize($conn, $_POST['city']);
        $star_rating = (int)$_POST['star_rating'];
        $facilities = sanitize($conn, $_POST['facilities']);
        $price = (float)$_POST['price_per_night'];
        $total_rooms = (int)$_POST['total_rooms'];
        $available_rooms = (int)$_POST['available_rooms'];

        $insert = $conn->prepare("INSERT INTO hotels (name, description, location, city, star_rating, facilities, price_per_night, total_rooms, available_rooms) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insert->bind_param('ssssisdii', $name, $description, $location, $city, $star_rating, $facilities, $price, $total_rooms, $available_rooms);

        if ($insert->execute()) {
            $success = 'Hotel berhasil ditambahkan!';
        } else {
            $error = 'Gagal menambahkan hotel.';
        }
    }

    if (isset($_POST['update_hotel'])) {
        $id = (int)$_POST['hotel_id'];
        $name = sanitize($conn, $_POST['name']);
        $city = sanitize($conn, $_POST['city']);
        $star_rating = (int)$_POST['star_rating'];
        $price = (float)$_POST['price_per_night'];
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        $update = $conn->prepare("UPDATE hotels SET name = ?, city = ?, star_rating = ?, price_per_night = ?, is_active = ? WHERE id = ?");
        $update->bind_param('ssidsi', $name, $city, $star_rating, $price, $is_active, $id);

        if ($update->execute()) {
            $success = 'Hotel berhasil diperbarui!';
        } else {
            $error = 'Gagal memperbarui hotel.';
        }
    }

    if (isset($_POST['delete_hotel'])) {
        $id = (int)$_POST['hotel_id'];
        $delete = $conn->prepare("DELETE FROM hotels WHERE id = ?");
        $delete->bind_param('i', $id);

        if ($delete->execute()) {
            $success = 'Hotel berhasil dihapus!';
        } else {
            $error = 'Gagal menghapus hotel.';
        }
    }
}

// Fetch all hotels
$hotels = $conn->query("SELECT * FROM hotels ORDER BY created_at DESC");
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 d-none d-md-block sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="index.php"><i class="bi bi-speedometer2"></i>Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active" href="hotels.php"><i class="bi bi-building"></i>Kelola Hotel</a></li>
                    <li class="nav-item"><a class="nav-link" href="flights.php"><i class="bi bi-airplane"></i>Kelola Penerbangan</a></li>
                    <li class="nav-item"><a class="nav-link" href="packages.php"><i class="bi bi-suitcase-lg"></i>Kelola Paket Wisata</a></li>
                    <li class="nav-item"><a class="nav-link" href="bookings.php"><i class="bi bi-calendar-check"></i>Semua Pemesanan</a></li>
                    <li class="nav-item"><a class="nav-link" href="customers.php"><i class="bi bi-people"></i>Kelola Customer</a></li>
                    <li class="nav-item mt-4"><a class="nav-link text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i>Logout</a></li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-10 ms-sm-auto px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
                <h1 class="h2"><i class="bi bi-building me-2"></i>Kelola Hotel</h1>
                <button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#addHotelModal">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Hotel
                </button>
            </div>

            <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
            <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Hotel</th>
                            <th>Kota</th>
                            <th>Bintang</th>
                            <th>Harga/Malam</th>
                            <th>Kamar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($hotel = $hotels->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $hotel['id']; ?></td>
                            <td><strong><?php echo $hotel['name']; ?></strong></td>
                            <td><?php echo $hotel['city']; ?></td>
                            <td><?php echo str_repeat('⭐', $hotel['star_rating']); ?></td>
                            <td><?php echo formatRupiah($hotel['price_per_night']); ?></td>
                            <td><?php echo $hotel['available_rooms']; ?>/<?php echo $hotel['total_rooms']; ?></td>
                            <td>
                                <?php if($hotel['is_active']): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editHotel<?php echo $hotel['id']; ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST" action="" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                                    <input type="hidden" name="hotel_id" value="<?php echo $hotel['id']; ?>">
                                    <button type="submit" name="delete_hotel" class="btn btn-sm btn-danger">
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

<!-- Add Hotel Modal -->
<div class="modal fade" id="addHotelModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Hotel Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Hotel *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kota *</label>
                            <input type="text" name="city" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lokasi</label>
                        <input type="text" name="location" class="form-control">
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Bintang *</label>
                            <select name="star_rating" class="form-select" required>
                                <option value="1">1 Bintang</option>
                                <option value="2">2 Bintang</option>
                                <option value="3">3 Bintang</option>
                                <option value="4">4 Bintang</option>
                                <option value="5">5 Bintang</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Harga/Malam *</label>
                            <input type="number" name="price_per_night" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Total Kamar *</label>
                            <input type="number" name="total_rooms" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kamar Tersedia</label>
                        <input type="number" name="available_rooms" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fasilitas</label>
                        <textarea name="facilities" class="form-control" rows="2" placeholder="WiFi, Kolam Renang, Spa, dll"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="add_hotel" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>