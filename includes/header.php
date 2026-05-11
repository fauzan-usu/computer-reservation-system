<?php
session_start();
require_once __DIR__ . '/db_connect.php';

// Check if user is logged in
$is_logged_in = isset($_SESSION['customer_id']);
$is_admin_logged_in = isset($_SESSION['admin_id']);
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>CRS SMK Pariwisata</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo isset($base_path) ? $base_path : ''; ?>assets/css/style.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #e74c3c;
            --accent-color: #3498db;
            --light-bg: #f8f9fa;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            color: white;
            padding: 80px 0;
        }

        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .price-tag {
            background: var(--secondary-color);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 600;
        }

        .footer {
            background: var(--primary-color);
            color: white;
            padding: 40px 0 20px;
            margin-top: 50px;
        }

        .btn-custom {
            background: var(--secondary-color);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            transition: all 0.3s;
        }

        .btn-custom:hover {
            background: #c0392b;
            color: white;
            transform: scale(1.05);
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .sidebar {
            min-height: calc(100vh - 56px);
            background: white;
            border-right: 1px solid #dee2e6;
        }

        .nav-link {
            color: var(--primary-color);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 2px 10px;
        }

        .nav-link:hover, .nav-link.active {
            background: var(--accent-color);
            color: white;
        }

        .dashboard-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }
    </style>
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background: var(--primary-color);">
    <div class="container">
        <a class="navbar-brand" href="<?php echo isset($base_path) ? $base_path : ''; ?>index.php">
            <i class="bi bi-airplane-engines-fill me-2"></i>CRS SMK
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?>" href="<?php echo isset($base_path) ? $base_path : ''; ?>index.php">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo isset($base_path) ? $base_path : ''; ?>index.php#hotels">Hotel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo isset($base_path) ? $base_path : ''; ?>index.php#flights">Penerbangan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo isset($base_path) ? $base_path : ''; ?>index.php#packages">Paket Wisata</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <?php if ($is_logged_in): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i><?php echo $_SESSION['customer_name']; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?php echo isset($base_path) ? $base_path : ''; ?>customer/index.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                            <li><a class="dropdown-item" href="<?php echo isset($base_path) ? $base_path : ''; ?>customer/my_bookings.php"><i class="bi bi-calendar-check me-2"></i>Pesanan Saya</a></li>
                            <li><a class="dropdown-item" href="<?php echo isset($base_path) ? $base_path : ''; ?>customer/profile.php"><i class="bi bi-person me-2"></i>Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?php echo isset($base_path) ? $base_path : ''; ?>customer/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                <?php elseif ($is_admin_logged_in): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-shield-lock me-1"></i>Admin
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?php echo isset($base_path) ? $base_path : ''; ?>admin/index.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?php echo isset($base_path) ? $base_path : ''; ?>admin/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo isset($base_path) ? $base_path : ''; ?>customer/login.php"><i class="bi bi-box-arrow-in-right me-1"></i>Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo isset($base_path) ? $base_path : ''; ?>customer/register.php"><i class="bi bi-person-plus me-1"></i>Daftar</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
