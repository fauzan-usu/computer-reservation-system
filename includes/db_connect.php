<?php
/**
 * Computer Reservation System (CRS) - Database Connection
 * SMK Pariwisata - XAMPP Configuration
 */

// Database configuration - sesuaikan dengan setting XAMPP Anda
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');      // Default XAMPP
define('DB_PASSWORD', '');            // Default XAMPP (kosong)
define('DB_NAME', 'crs_db');
define('DB_CHARSET', 'utf8mb4');

// Create database connection
try {
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Set charset
    $conn->set_charset(DB_CHARSET);

} catch (Exception $e) {
    die("Database Error: " . $e->getMessage());
}

// Helper function to prevent SQL injection
function sanitize($conn, $data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $conn->real_escape_string($data);
}

// Generate unique booking code
function generateBookingCode($prefix) {
    return $prefix . '-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
}

// Format currency
function formatRupiah($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

// Format date
function formatDate($date) {
    $bulan = array(
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    $split = explode('-', $date);
    return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
}

// Get status badge color
function getStatusColor($status) {
    $colors = [
        'pending' => 'warning',
        'confirmed' => 'primary',
        'checked_in' => 'info',
        'checked_out' => 'success',
        'cancelled' => 'danger',
        'boarded' => 'info',
        'completed' => 'success',
        'ongoing' => 'primary',
        'unpaid' => 'danger',
        'partial' => 'warning',
        'paid' => 'success',
        'refunded' => 'secondary'
    ];
    return $colors[$status] ?? 'secondary';
}

// Translate status to Indonesian
function translateStatus($status) {
    $translations = [
        'pending' => 'Menunggu',
        'confirmed' => 'Dikonfirmasi',
        'checked_in' => 'Check-in',
        'checked_out' => 'Check-out',
        'cancelled' => 'Dibatalkan',
        'boarded' => 'Naik Pesawat',
        'completed' => 'Selesai',
        'ongoing' => 'Berlangsung',
        'unpaid' => 'Belum Bayar',
        'partial' => 'DP',
        'paid' => 'Lunas',
        'refunded' => 'Dikembalikan'
    ];
    return $translations[$status] ?? $status;
}
?>
