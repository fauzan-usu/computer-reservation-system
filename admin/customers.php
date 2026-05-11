<?php
$page_title = 'Kelola Customer';
$base_path = '../';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';

if (!isset($_SESSION['admin_id'])) {
    redirect('login.php');
}

// Toggle active status
if (isset($_POST['toggle_status'])) {
    $id = (int)$_POST['customer_id'];
    $current = $conn->query("SELECT is_active FROM customers WHERE id = $id")->fetch_assoc()['is_active'];
    $new_status = $current ? 0 : 1;
    $conn->query("UPDATE customers SET is_active = $new_status WHERE id = $id");
}

$customers = $conn->query("SELECT c.*, 
    (SELECT COUNT(*) FROM hotel_bookings WHERE customer_id = c.id) as hotel_bookings,
    (SELECT COUNT(*) FROM flight_bookings WHERE customer_id = c.id) as flight_bookings,
    (SELECT COUNT(*) FROM package_bookings WHERE customer_id = c.id) as package_bookings
    FROM customers c ORDER BY c.created_at DESC");
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
                    <li class="nav-item"><a class="nav-link" href="bookings.php"><i class="bi bi-calendar-check"></i>Semua Pemesanan</a></li>
                    <li class="nav-item"><a class="nav-link active" href="customers.php"><i class="bi bi-people"></i>Kelola Customer</a></li>
                    <li class="nav-item mt-4"><a class="nav-link text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i>Logout</a></li>
                </ul>
            </div>
        </nav>

        <main class="col-md-10 ms-sm-auto px-md-4 py-4">
            <h1 class="h2 mb-4"><i class="bi bi-people me-2"></i>Kelola Customer</h1>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Kota</th>
                            <th>Pemesanan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($customer = $customers->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $customer['id']; ?></td>
                            <td><?php echo $customer['username']; ?></td>
                            <td><?php echo $customer['full_name']; ?></td>
                            <td><?php echo $customer['email']; ?></td>
                            <td><?php echo $customer['phone']; ?></td>
                            <td><?php echo $customer['city']; ?></td>
                            <td>
                                <span class="badge bg-primary"><?php echo $customer['hotel_bookings']; ?> Hotel</span>
                                <span class="badge bg-success"><?php echo $customer['flight_bookings']; ?> Flight</span>
                                <span class="badge bg-warning"><?php echo $customer['package_bookings']; ?> Paket</span>
                            </td>
                            <td>
                                <?php if($customer['is_active']): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form method="POST" action="" class="d-inline">
                                    <input type="hidden" name="customer_id" value="<?php echo $customer['id']; ?>">
                                    <button type="submit" name="toggle_status" class="btn btn-sm <?php echo $customer['is_active'] ? 'btn-warning' : 'btn-success'; ?>">
                                        <?php echo $customer['is_active'] ? 'Nonaktifkan' : 'Aktifkan'; ?>
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

<?php require_once '../includes/footer.php'; ?>