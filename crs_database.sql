-- ============================================================
-- COMPUTER RESERVATION SYSTEM (CRS) - SMK PARIWISATA
-- Database: crs_db
-- Created: 2026-05-11
-- Description: Complete CRS for Hotels, Flights, and Tour Packages
-- ============================================================

-- Drop database if exists and create new
DROP DATABASE IF EXISTS crs_db;
CREATE DATABASE crs_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE crs_db;

-- ============================================================
-- TABLE: admins (Admin Users)
-- ============================================================
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    role ENUM('super_admin', 'admin') DEFAULT 'admin',
    is_active TINYINT(1) DEFAULT 1,
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================================
-- TABLE: customers (Customer/Users)
-- ============================================================
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    address TEXT,
    city VARCHAR(50),
    country VARCHAR(50) DEFAULT 'Indonesia',
    id_card_type ENUM('KTP', 'SIM', 'Passport', 'Kartu Pelajar') DEFAULT 'KTP',
    id_card_number VARCHAR(50),
    date_of_birth DATE,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================================
-- TABLE: hotels
-- ============================================================
CREATE TABLE hotels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    location VARCHAR(100) NOT NULL,
    address TEXT,
    city VARCHAR(50) NOT NULL,
    country VARCHAR(50) DEFAULT 'Indonesia',
    star_rating INT CHECK (star_rating BETWEEN 1 AND 5),
    facilities TEXT,
    contact_phone VARCHAR(20),
    contact_email VARCHAR(100),
    image_url VARCHAR(255),
    price_per_night DECIMAL(12,2) NOT NULL,
    total_rooms INT NOT NULL DEFAULT 0,
    available_rooms INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================================
-- TABLE: hotel_rooms (Room types for each hotel)
-- ============================================================
CREATE TABLE hotel_rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT NOT NULL,
    room_type VARCHAR(50) NOT NULL,
    description TEXT,
    bed_type VARCHAR(50),
    max_occupancy INT DEFAULT 2,
    price_per_night DECIMAL(12,2) NOT NULL,
    total_rooms INT NOT NULL DEFAULT 0,
    available_rooms INT NOT NULL DEFAULT 0,
    amenities TEXT,
    image_url VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE
);

-- ============================================================
-- TABLE: flights
-- ============================================================
CREATE TABLE flights (
    id INT AUTO_INCREMENT PRIMARY KEY,
    flight_number VARCHAR(20) NOT NULL UNIQUE,
    airline VARCHAR(50) NOT NULL,
    departure_city VARCHAR(50) NOT NULL,
    arrival_city VARCHAR(50) NOT NULL,
    departure_airport VARCHAR(100),
    arrival_airport VARCHAR(100),
    departure_time TIME NOT NULL,
    arrival_time TIME NOT NULL,
    flight_date DATE NOT NULL,
    return_date DATE,
    flight_type ENUM('one_way', 'round_trip') DEFAULT 'one_way',
    class_type ENUM('economy', 'business', 'first_class') DEFAULT 'economy',
    price DECIMAL(12,2) NOT NULL,
    total_seats INT NOT NULL DEFAULT 0,
    available_seats INT NOT NULL DEFAULT 0,
    baggage_allowance VARCHAR(50),
    facilities TEXT,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================================
-- TABLE: tour_packages
-- ============================================================
CREATE TABLE tour_packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    package_code VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    destination VARCHAR(100) NOT NULL,
    duration_days INT NOT NULL,
    duration_nights INT NOT NULL,
    itinerary TEXT,
    inclusions TEXT,
    exclusions TEXT,
    price_per_person DECIMAL(12,2) NOT NULL,
    max_participants INT DEFAULT 20,
    available_slots INT DEFAULT 20,
    start_date DATE,
    end_date DATE,
    meeting_point VARCHAR(255),
    guide_name VARCHAR(100),
    guide_phone VARCHAR(20),
    image_url VARCHAR(255),
    category ENUM('family', 'adventure', 'honeymoon', 'cultural', 'nature', 'educational') DEFAULT 'cultural',
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================================
-- TABLE: hotel_bookings
-- ============================================================
CREATE TABLE hotel_bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_code VARCHAR(20) NOT NULL UNIQUE,
    customer_id INT NOT NULL,
    hotel_id INT NOT NULL,
    room_id INT,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    nights INT NOT NULL,
    guests INT NOT NULL DEFAULT 1,
    guest_names TEXT,
    special_requests TEXT,
    total_price DECIMAL(12,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled') DEFAULT 'pending',
    payment_status ENUM('unpaid', 'partial', 'paid', 'refunded') DEFAULT 'unpaid',
    payment_method ENUM('cash', 'transfer', 'credit_card', 'e_wallet') DEFAULT 'cash',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id),
    FOREIGN KEY (hotel_id) REFERENCES hotels(id),
    FOREIGN KEY (room_id) REFERENCES hotel_rooms(id)
);

-- ============================================================
-- TABLE: flight_bookings
-- ============================================================
CREATE TABLE flight_bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_code VARCHAR(20) NOT NULL UNIQUE,
    customer_id INT NOT NULL,
    flight_id INT NOT NULL,
    passengers INT NOT NULL DEFAULT 1,
    passenger_details TEXT,
    seat_preference VARCHAR(50),
    meal_preference VARCHAR(50),
    total_price DECIMAL(12,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'boarded', 'completed', 'cancelled') DEFAULT 'pending',
    payment_status ENUM('unpaid', 'partial', 'paid', 'refunded') DEFAULT 'unpaid',
    payment_method ENUM('cash', 'transfer', 'credit_card', 'e_wallet') DEFAULT 'cash',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id),
    FOREIGN KEY (flight_id) REFERENCES flights(id)
);

-- ============================================================
-- TABLE: package_bookings
-- ============================================================
CREATE TABLE package_bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_code VARCHAR(20) NOT NULL UNIQUE,
    customer_id INT NOT NULL,
    package_id INT NOT NULL,
    participants INT NOT NULL DEFAULT 1,
    participant_details TEXT,
    travel_date DATE NOT NULL,
    special_requests TEXT,
    total_price DECIMAL(12,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'ongoing', 'completed', 'cancelled') DEFAULT 'pending',
    payment_status ENUM('unpaid', 'partial', 'paid', 'refunded') DEFAULT 'unpaid',
    payment_method ENUM('cash', 'transfer', 'credit_card', 'e_wallet') DEFAULT 'cash',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id),
    FOREIGN KEY (package_id) REFERENCES tour_packages(id)
);

-- ============================================================
-- TABLE: payments
-- ============================================================
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_type ENUM('hotel', 'flight', 'package') NOT NULL,
    booking_id INT NOT NULL,
    customer_id INT NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    payment_method ENUM('cash', 'transfer', 'credit_card', 'e_wallet') NOT NULL,
    transaction_id VARCHAR(100),
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'success', 'failed', 'refunded') DEFAULT 'pending',
    notes TEXT,
    FOREIGN KEY (customer_id) REFERENCES customers(id)
);

-- ============================================================
-- TABLE: reviews
-- ============================================================
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    booking_type ENUM('hotel', 'flight', 'package') NOT NULL,
    booking_id INT NOT NULL,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id)
);

-- ============================================================
-- INSERT DUMMY DATA: ADMINS
-- ============================================================
INSERT INTO admins (username, password, full_name, email, phone, role, is_active) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator Utama', 'admin@crs.sch.id', '081234567890', 'super_admin', 1),
('admin_hotel', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin Hotel', 'hotel@crs.sch.id', '081234567891', 'admin', 1),
('admin_flight', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin Penerbangan', 'flight@crs.sch.id', '081234567892', 'admin', 1),
('admin_tour', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin Paket Wisata', 'tour@crs.sch.id', '081234567893', 'admin', 1),
('guru1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Pak Budi Santoso', 'budi.santoso@crs.sch.id', '081234567894', 'admin', 1);

-- ============================================================
-- INSERT DUMMY DATA: CUSTOMERS
-- ============================================================
INSERT INTO customers (username, password, full_name, email, phone, address, city, country, id_card_type, id_card_number, date_of_birth, is_active) VALUES
('customer1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ahmad Rizky', 'ahmad.rizky@email.com', '081298765432', 'Jl. Merdeka No. 123', 'Jakarta', 'Indonesia', 'KTP', '3175012345678', '2005-03-15', 1),
('customer2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Siti Nurhaliza', 'siti.nur@email.com', '081298765433', 'Jl. Sudirman No. 45', 'Bandung', 'Indonesia', 'KTP', '3275012345679', '2005-07-22', 1),
('customer3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Budi Pratama', 'budi.pratama@email.com', '081298765434', 'Jl. Gatot Subroto No. 78', 'Surabaya', 'Indonesia', 'KTP', '3575012345680', '2005-11-05', 1),
('customer4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dewi Kusuma', 'dewi.kusuma@email.com', '081298765435', 'Jl. Thamrin No. 90', 'Yogyakarta', 'Indonesia', 'KTP', '3475012345681', '2005-01-18', 1),
('customer5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Eko Wijaya', 'eko.wijaya@email.com', '081298765436', 'Jl. Ahmad Yani No. 56', 'Semarang', 'Indonesia', 'KTP', '3375012345682', '2005-09-30', 1),
('siswa1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Rina Amelia', 'rina.amelia@email.com', '081298765437', 'Jl. Diponegoro No. 12', 'Malang', 'Indonesia', 'Kartu Pelajar', '202301001', '2006-04-12', 1),
('siswa2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Fajar Hidayat', 'fajar.hidayat@email.com', '081298765438', 'Jl. Imam Bonjol No. 34', 'Denpasar', 'Indonesia', 'Kartu Pelajar', '202301002', '2006-08-25', 1),
('siswa3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Maya Sari', 'maya.sari@email.com', '081298765439', 'Jl. Veteran No. 67', 'Medan', 'Indonesia', 'Kartu Pelajar', '202301003', '2006-12-03', 1);

-- ============================================================
-- INSERT DUMMY DATA: HOTELS
-- ============================================================
INSERT INTO hotels (name, description, location, address, city, country, star_rating, facilities, contact_phone, contact_email, price_per_night, total_rooms, available_rooms, is_active) VALUES
('Grand Hyatt Jakarta', 'Hotel mewah bintang 5 di pusat kota Jakarta dengan fasilitas lengkap', 'Jakarta Pusat', 'Jl. M.H. Thamrin Kav. 28-30', 'Jakarta', 'Indonesia', 5, 'Kolam Renang, Spa, Gym, Restoran, WiFi, Parkir', '021-29921234', 'jakarta.grand@hyatt.com', 2500000.00, 300, 120, 1),
('Hotel Borobudur', 'Hotel bersejarah dengan nuansa klasik di Jakarta', 'Jakarta Pusat', 'Jl. Lapangan Banteng Selatan', 'Jakarta', 'Indonesia', 4, 'Kolam Renang, Restoran, Ballroom, WiFi', '021-3805555', 'info@hotelborobudur.com', 1200000.00, 200, 85, 1),
('Padma Resort Ubud', 'Resort mewah dengan pemandangan sawah dan lembah di Ubud', 'Ubud', 'Banjar Carik, Desa Puhu', 'Gianyar', 'Indonesia', 5, 'Kolam Renang Infinity, Spa, Yoga, Restoran, WiFi', '0361-3011111', 'ubud@padmaresortbali.com', 3500000.00, 150, 45, 1),
('Swiss-Belhotel Danum', 'Hotel modern di pusat kota Palangkaraya', 'Palangkaraya', 'Jl. Tjilik Riwut Km. 5', 'Palangkaraya', 'Indonesia', 4, 'Kolam Renang, Restoran, Meeting Room, WiFi', '0536-3222000', 'danum@swiss-belhotel.com', 850000.00, 120, 60, 1),
('Novotel Bandung', 'Hotel internasional strategis di Bandung', 'Bandung', 'Jl. Cihampelas No. 23', 'Bandung', 'Indonesia', 4, 'Kolam Renang, Gym, Restoran, WiFi, Parkir', '022-4211111', 'bandung@novotel.com', 950000.00, 150, 70, 1),
('Aston Marina Ancol', 'Hotel tepi pantai di kawasan wisata Ancol', 'Jakarta Utara', 'Jl. Lodan Timur No. 7A', 'Jakarta', 'Indonesia', 4, 'Akses Pantai, Kolam Renang, Restoran, WiFi', '021-29268888', 'marina@astoninternational.com', 1100000.00, 180, 90, 1),
('The Phoenix Hotel Yogyakarta', 'Hotel bersejarah bergaya kolonial di Malioboro', 'Yogyakarta', 'Jl. Jenderal Sudirman No. 9', 'Yogyakarta', 'Indonesia', 5, 'Kolam Renang, Spa, Restoran, WiFi, Parkir', '0274-566617', 'phoenix@muenhotels.com', 1800000.00, 200, 95, 1),
('Four Points by Sheraton', 'Hotel modern di kawasan bisnis Surabaya', 'Surabaya', 'Jl. Embong Malang No. 78', 'Surabaya', 'Indonesia', 4, 'Gym, Restoran, WiFi, Meeting Room', '031-3312345', 'surabaya@fourpoints.com', 900000.00, 160, 80, 1);

-- ============================================================
-- INSERT DUMMY DATA: HOTEL ROOMS
-- ============================================================
INSERT INTO hotel_rooms (hotel_id, room_type, description, bed_type, max_occupancy, price_per_night, total_rooms, available_rooms, amenities) VALUES
(1, 'Deluxe Room', 'Kamar luas dengan pemandangan kota', 'King Bed', 2, 2500000.00, 100, 40, 'AC, TV, Mini Bar, Safe Deposit Box, WiFi'),
(1, 'Grand Suite', 'Suite mewah dengan ruang tamu terpisah', 'King Bed + Sofa', 3, 4500000.00, 50, 20, 'AC, TV, Mini Bar, Jacuzzi, WiFi, Butler'),
(1, 'Executive Room', 'Kamar bisnis dengan akses lounge', 'Twin Bed', 2, 3200000.00, 80, 35, 'AC, TV, Work Desk, WiFi, Executive Lounge'),
(2, 'Superior Room', 'Kamar nyaman dengan fasilitas standar', 'Queen Bed', 2, 1200000.00, 100, 45, 'AC, TV, WiFi, Mini Bar'),
(2, 'Junior Suite', 'Suite dengan ruang tamu kecil', 'King Bed', 2, 1800000.00, 60, 25, 'AC, TV, Mini Bar, WiFi, Living Room'),
(3, 'Garden View Room', 'Kamar dengan pemandangan taman', 'King Bed', 2, 3500000.00, 60, 20, 'AC, TV, Balcony, WiFi, Mini Bar'),
(3, 'Valley View Villa', 'Villa pribadi dengan kolam renang', 'King Bed', 4, 8500000.00, 30, 10, 'Private Pool, AC, TV, Kitchen, WiFi, Butler'),
(4, 'Deluxe Room', 'Kamar modern dengan fasilitas lengkap', 'Queen Bed', 2, 850000.00, 80, 40, 'AC, TV, WiFi, Mini Bar'),
(5, 'Superior Room', 'Kamar strategis di pusat kota', 'Twin Bed', 2, 950000.00, 90, 40, 'AC, TV, WiFi, Work Desk'),
(6, 'Ocean View Room', 'Kamar dengan pemandangan laut', 'King Bed', 2, 1400000.00, 100, 50, 'AC, TV, Balcony, WiFi, Mini Bar'),
(7, 'Heritage Room', 'Kamar dengan nuansa klasik Jawa', 'King Bed', 2, 1800000.00, 100, 50, 'AC, TV, WiFi, Bathtub, Mini Bar'),
(8, 'Business Room', 'Kamar untuk traveler bisnis', 'Queen Bed', 2, 900000.00, 100, 50, 'AC, TV, WiFi, Work Desk, Coffee Maker');

-- ============================================================
-- INSERT DUMMY DATA: FLIGHTS
-- ============================================================
INSERT INTO flights (flight_number, airline, departure_city, arrival_city, departure_airport, arrival_airport, departure_time, arrival_time, flight_date, return_date, flight_type, class_type, price, total_seats, available_seats, baggage_allowance, facilities, is_active) VALUES
('GA-123', 'Garuda Indonesia', 'Jakarta', 'Bali/Denpasar', 'Soekarno-Hatta (CGK)', 'Ngurah Rai (DPS)', '08:00:00', '10:30:00', '2026-06-15', '2026-06-20', 'round_trip', 'economy', 1850000.00, 180, 45, '20kg', 'Makanan, Hiburan, WiFi', 1),
('GA-124', 'Garuda Indonesia', 'Jakarta', 'Bali/Denpasar', 'Soekarno-Hatta (CGK)', 'Ngurah Rai (DPS)', '14:00:00', '16:30:00', '2026-06-15', NULL, 'one_way', 'business', 4500000.00, 24, 12, '30kg', 'Makanan Premium, Lounge, WiFi', 1),
('JT-456', 'Lion Air', 'Jakarta', 'Yogyakarta', 'Soekarno-Hatta (CGK)', 'Adisutjipto (JOG)', '09:30:00', '10:45:00', '2026-06-16', NULL, 'one_way', 'economy', 650000.00, 220, 85, '15kg', 'Makanan Ringan', 1),
('JT-457', 'Lion Air', 'Jakarta', 'Yogyakarta', 'Soekarno-Hatta (CGK)', 'Adisutjipto (JOG)', '16:00:00', '17:15:00', '2026-06-16', '2026-06-18', 'round_trip', 'economy', 1200000.00, 220, 90, '15kg', 'Makanan Ringan', 1),
('QZ-789', 'AirAsia', 'Surabaya', 'Jakarta', 'Juanda (SUB)', 'Soekarno-Hatta (CGK)', '07:15:00', '08:45:00', '2026-06-17', NULL, 'one_way', 'economy', 550000.00, 180, 60, '15kg', 'Makanan Berbayar', 1),
('QZ-790', 'AirAsia', 'Surabaya', 'Jakarta', 'Juanda (SUB)', 'Soekarno-Hatta (CGK)', '19:30:00', '21:00:00', '2026-06-17', NULL, 'one_way', 'economy', 480000.00, 180, 75, '15kg', 'Makanan Berbayar', 1),
('ID-321', 'Batik Air', 'Jakarta', 'Makassar', 'Soekarno-Hatta (CGK)', 'Sultan Hasanuddin (UPG)', '11:00:00', '14:30:00', '2026-06-18', '2026-06-22', 'round_trip', 'economy', 2100000.00, 180, 55, '20kg', 'Makanan, Hiburan', 1),
('ID-322', 'Batik Air', 'Jakarta', 'Makassar', 'Soekarno-Hatta (CGK)', 'Sultan Hasanuddin (UPG)', '20:00:00', '23:30:00', '2026-06-18', NULL, 'one_way', 'business', 5200000.00, 12, 6, '30kg', 'Makanan Premium, Lounge', 1),
('SJ-654', 'Sriwijaya Air', 'Bandung', 'Palembang', 'Husein Sastranegara (BDO)', 'Sultan Mahmud Badaruddin II (PLM)', '10:00:00', '11:30:00', '2026-06-19', NULL, 'one_way', 'economy', 780000.00, 150, 50, '15kg', 'Makanan Ringan', 1),
('GA-555', 'Garuda Indonesia', 'Jakarta', 'Singapore', 'Soekarno-Hatta (CGK)', 'Changi (SIN)', '09:00:00', '12:15:00', '2026-06-20', '2026-06-25', 'round_trip', 'economy', 3200000.00, 200, 40, '25kg', 'Makanan, Hiburan, WiFi', 1),
('SQ-111', 'Singapore Airlines', 'Singapore', 'Jakarta', 'Changi (SIN)', 'Soekarno-Hatta (CGK)', '14:30:00', '15:45:00', '2026-06-20', NULL, 'one_way', 'business', 8500000.00, 30, 15, '35kg', 'Makanan Premium, Lounge, WiFi', 1),
('JT-888', 'Lion Air', 'Jakarta', 'Lombok', 'Soekarno-Hatta (CGK)', 'Lombok (LOP)', '06:30:00', '09:00:00', '2026-06-21', '2026-06-24', 'round_trip', 'economy', 1400000.00, 180, 70, '15kg', 'Makanan Ringan', 1);

-- ============================================================
-- INSERT DUMMY DATA: TOUR PACKAGES
-- ============================================================
INSERT INTO tour_packages (package_code, name, description, destination, duration_days, duration_nights, itinerary, inclusions, exclusions, price_per_person, max_participants, available_slots, start_date, end_date, meeting_point, guide_name, guide_phone, category, is_active) VALUES
('PKG-001', 'Wisata Bali 3 Hari 2 Malam', 'Nikmati keindahan Bali dengan kunjungan ke pantai, pura, dan sawah terasering', 'Bali', 3, 2, 'Hari 1: Kedatangan - Tanah Lot - Pantai Kuta; Hari 2: Tari Kecak Uluwatu - Pantai Pandawa - Jimbaran; Hari 3: Tirta Empul - Tegalalang - Keberangkatan', 'Hotel bintang 3, Transportasi AC, Makan 3x/hari, Tiket masuk, Guide lokal', 'Tiket pesawat, Pengeluaran pribadi, Asuransi perjalanan', 2500000.00, 25, 18, '2026-07-15', '2026-07-17', 'Bandara Ngurah Rai', 'Ketut Sudarsana', '081234567001', 'cultural', 1),
('PKG-002', 'Jogja Heritage Tour 4 Hari 3 Malam', 'Jelajahi warisan budaya Yogyakarta dari candi hingga keraton', 'Yogyakarta', 4, 3, 'Hari 1: Kedatangan - Malioboro - Tugu Jogja; Hari 2: Borobudur - Mendut - Pawon; Hari 3: Prambanan - Keraton Yogyakarta - Tamansari; Hari 4: Pantai Parangtritis - Keberangkatan', 'Hotel bintang 3, Transportasi, Makan 3x/hari, Tiket masuk, Guide', 'Tiket pesawat/kereta, Pengeluaran pribadi', 3200000.00, 20, 15, '2026-07-20', '2026-07-23', 'Bandara Adisutjipto', 'Pak Surya', '081234567002', 'cultural', 1),
('PKG-003', 'Raja Ampat Diving Adventure 5 Hari 4 Malam', 'Petualangan menyelam di surga bawah laut Raja Ampat', 'Raja Ampat', 5, 4, 'Hari 1: Kedatangan Sorong - Transfer Waisai; Hari 2-4: Diving/Snorkeling di spot terbaik; Hari 5: Keberangkatan', 'Penginapan resort, Equipment diving, Makan 3x/hari, Boat, Guide diving', 'Tiket pesawat ke Sorong, Asuransi diving, Tips', 8500000.00, 12, 8, '2026-08-01', '2026-08-05', 'Bandara Domine Eduard Osok', 'Pak Yansen', '081234567003', 'adventure', 1),
('PKG-004', 'Bromo Sunrise & Ijen Blue Fire', 'Saksikan matahari terbit di Bromo dan api biru di Kawah Ijen', 'Malang - Banyuwangi', 3, 2, 'Hari 1: Kedatangan Malang - Tumpak Sewu; Hari 2: Sunrise Bromo - Transfer Banyuwangi; Hari 3: Blue Fire Ijen - Keberangkatan', 'Hotel/homestay, Jeep Bromo, Transportasi, Makan, Guide', 'Tiket pesawat/kereta, Sewa kuda Bromo', 2800000.00, 15, 12, '2026-08-10', '2026-08-12', 'Stasiun Malang', 'Pak Widodo', '081234567004', 'adventure', 1),
('PKG-005', 'Lombok Gili Islands 4 Hari 3 Malam', 'Nikmati keindahan pantai dan snorkeling di Gili Trawangan, Meno, Air', 'Lombok', 4, 3, 'Hari 1: Kedatangan - Senggigi; Hari 2: Gili Trawangan - Gili Meno - Gili Air; Hari 3: Pantai Pink - Bukit Malimbu; Hari 4: Keberangkatan', 'Hotel bintang 3, Boat ke Gili, Snorkeling, Makan, Transportasi', 'Tiket pesawat, Pengeluaran pribadi', 3500000.00, 20, 16, '2026-08-15', '2026-08-18', 'Bandara Lombok', 'Pak Lalu', '081234567005', 'nature', 1),
('PKG-006', 'Jakarta City Tour & Shopping', 'Jelajahi ibu kota dengan kunjungan ke tempat bersejarah dan pusat perbelanjaan', 'Jakarta', 2, 1, 'Hari 1: Monas - Kota Tua - Ancol; Hari 2: Taman Mini - Grand Indonesia - Keberangkatan', 'Hotel bintang 3, Transportasi, Makan, Guide', 'Tiket pesawat, Pengeluaran pribadi', 1500000.00, 30, 25, '2026-09-01', '2026-09-02', 'Bandara Soekarno-Hatta', 'Bu Ani', '081234567006', 'educational', 1),
('PKG-007', 'Toraja Culture Experience 4 Hari 3 Malam', 'Mengenal budaya unik Toraja dengan rumah adat dan upacara tradisional', 'Toraja', 4, 3, 'Hari 1: Kedatangan Makassar - Transfer Toraja; Hari 2: Lemo - Kete Kesu; Hari 3: Londa - Bori; Hari 4: Keberangkatan', 'Hotel, Transportasi, Makan, Guide lokal', 'Tiket pesawat ke Makassar', 3800000.00, 15, 10, '2026-09-10', '2026-09-13', 'Bandara Sultan Hasanuddin', 'Pak Ne Pua', '081234567007', 'cultural', 1),
('PKG-008', 'Komodo Island Expedition', 'Petualangan melihat Komodo dan snorkeling di Pink Beach', 'Labuan Bajo', 3, 2, 'Hari 1: Kedatangan - Sunset di Bukit Cinta; Hari 2: Pulau Komodo - Pink Beach - Manta Point; Hari 3: Keberangkatan', 'Hotel, Boat trip, Makan, Guide, Snorkeling gear', 'Tiket pesawat ke Labuan Bajo', 4500000.00, 15, 10, '2026-09-20', '2026-09-22', 'Bandara Komodo', 'Pak Thomas', '081234567008', 'adventure', 1);

-- ============================================================
-- INSERT DUMMY DATA: HOTEL BOOKINGS
-- ============================================================
INSERT INTO hotel_bookings (booking_code, customer_id, hotel_id, room_id, check_in, check_out, nights, guests, guest_names, special_requests, total_price, status, payment_status, payment_method) VALUES
('HBK-001', 1, 1, 1, '2026-06-15', '2026-06-18', 3, 2, 'Ahmad Rizky, Siti Aminah', 'Kamar non-smoking, lantai tinggi', 7500000.00, 'confirmed', 'paid', 'transfer'),
('HBK-002', 2, 3, 6, '2026-07-01', '2026-07-05', 4, 2, 'Siti Nurhaliza, Rina Putri', 'Early check-in jika memungkinkan', 14000000.00, 'confirmed', 'paid', 'credit_card'),
('HBK-003', 3, 5, 9, '2026-06-20', '2026-06-22', 2, 1, 'Budi Pratama', 'Kamar dengan view kota', 1900000.00, 'pending', 'unpaid', 'cash'),
('HBK-004', 4, 7, 11, '2026-08-10', '2026-08-15', 5, 2, 'Dewi Kusuma, Agus Santoso', 'Anniversary celebration', 9000000.00, 'confirmed', 'partial', 'transfer'),
('HBK-005', 5, 2, 4, '2026-07-15', '2026-07-17', 2, 3, 'Eko Wijaya, Keluarga', 'Extra bed untuk anak', 2400000.00, 'confirmed', 'paid', 'e_wallet');

-- ============================================================
-- INSERT DUMMY DATA: FLIGHT BOOKINGS
-- ============================================================
INSERT INTO flight_bookings (booking_code, customer_id, flight_id, passengers, passenger_details, seat_preference, meal_preference, total_price, status, payment_status, payment_method) VALUES
('FBK-001', 1, 1, 2, 'Ahmad Rizky (Dewasa), Siti Aminah (Dewasa)', 'Window', 'Halal', 3700000.00, 'confirmed', 'paid', 'transfer'),
('FBK-002', 2, 3, 1, 'Siti Nurhaliza (Dewasa)', 'Aisle', 'Vegetarian', 650000.00, 'confirmed', 'paid', 'credit_card'),
('FBK-003', 3, 7, 2, 'Budi Pratama (Dewasa), Ani Wulandari (Dewasa)', 'Window', 'Halal', 4200000.00, 'pending', 'unpaid', 'cash'),
('FBK-004', 6, 10, 1, 'Rina Amelia (Pelajar)', 'Window', 'Halal', 3200000.00, 'confirmed', 'paid', 'e_wallet'),
('FBK-005', 7, 12, 2, 'Fajar Hidayat (Pelajar), Maya Sari (Pelajar)', 'Aisle', 'Halal', 2800000.00, 'confirmed', 'paid', 'transfer');

-- ============================================================
-- INSERT DUMMY DATA: PACKAGE BOOKINGS
-- ============================================================
INSERT INTO package_bookings (booking_code, customer_id, package_id, participants, participant_details, travel_date, special_requests, total_price, status, payment_status, payment_method) VALUES
('PBK-001', 1, 1, 2, 'Ahmad Rizky (Dewasa), Siti Aminah (Dewasa)', '2026-07-15', 'Kamar twin bed', 5000000.00, 'confirmed', 'paid', 'transfer'),
('PBK-002', 2, 2, 1, 'Siti Nurhaliza (Dewasa)', '2026-07-20', 'Kamar single', 3200000.00, 'confirmed', 'paid', 'credit_card'),
('PBK-003', 4, 4, 2, 'Dewi Kusuma (Dewasa), Agus Santoso (Dewasa)', '2026-08-10', 'Vegetarian meal', 5600000.00, 'pending', 'partial', 'transfer'),
('PBK-004', 5, 6, 3, 'Eko Wijaya (Dewasa), Istri, Anak', '2026-09-01', 'Kamar family', 4500000.00, 'confirmed', 'paid', 'cash'),
('PBK-005', 8, 5, 1, 'Maya Sari (Pelajar)', '2026-08-15', 'Kamar sharing dengan peserta lain', 3500000.00, 'pending', 'unpaid', 'cash');

-- ============================================================
-- INSERT DUMMY DATA: REVIEWS
-- ============================================================
INSERT INTO reviews (customer_id, booking_type, booking_id, rating, comment) VALUES
(1, 'hotel', 1, 5, 'Pelayanan sangat baik, kamar bersih dan nyaman. Lokasi strategis di pusat kota.'),
(2, 'hotel', 2, 5, 'Resortnya indah sekali! Pemandangan sawahnya memukau. Staff ramah dan helpful.'),
(1, 'flight', 1, 4, 'Penerbangan tepat waktu, makanan enak. Hanya saja hiburannya terbatas.'),
(2, 'flight', 2, 4, 'Crew ramah, penerbangan lancar. Kursi agak sempit untuk ukuran saya.'),
(1, 'package', 1, 5, 'Tour guide sangat berpengetahuan. Destinasi yang dikunjungi semuanya menarik!'),
(4, 'hotel', 4, 5, 'Hotel bersejarah dengan nuansa klasik. Sarapannya enak dan bervariasi.');

-- ============================================================
-- CREATE INDEXES FOR BETTER PERFORMANCE
-- ============================================================
CREATE INDEX idx_hotel_city ON hotels(city);
CREATE INDEX idx_flight_route ON flights(departure_city, arrival_city);
CREATE INDEX idx_flight_date ON flights(flight_date);
CREATE INDEX idx_package_destination ON tour_packages(destination);
CREATE INDEX idx_booking_customer ON hotel_bookings(customer_id);
CREATE INDEX idx_booking_status ON hotel_bookings(status);
CREATE INDEX idx_flight_booking_customer ON flight_bookings(customer_id);
CREATE INDEX idx_package_booking_customer ON package_bookings(customer_id);

-- ============================================================
-- CREATE VIEWS FOR REPORTING
-- ============================================================
CREATE VIEW v_hotel_booking_summary AS
SELECT 
    h.name AS hotel_name,
    h.city,
    COUNT(hb.id) AS total_bookings,
    SUM(hb.total_price) AS total_revenue,
    AVG(hb.nights) AS avg_nights
FROM hotels h
LEFT JOIN hotel_bookings hb ON h.id = hb.hotel_id
GROUP BY h.id, h.name, h.city;

CREATE VIEW v_flight_booking_summary AS
SELECT 
    f.airline,
    f.departure_city,
    f.arrival_city,
    COUNT(fb.id) AS total_bookings,
    SUM(fb.total_price) AS total_revenue
FROM flights f
LEFT JOIN flight_bookings fb ON f.id = fb.flight_id
GROUP BY f.id, f.airline, f.departure_city, f.arrival_city;

CREATE VIEW v_package_booking_summary AS
SELECT 
    tp.name AS package_name,
    tp.destination,
    COUNT(pb.id) AS total_bookings,
    SUM(pb.total_price) AS total_revenue
FROM tour_packages tp
LEFT JOIN package_bookings pb ON tp.id = pb.package_id
GROUP BY tp.id, tp.name, tp.destination;

-- ============================================================
-- END OF DATABASE SCRIPT
-- ============================================================
SELECT 'Database CRS berhasil dibuat dengan data dummy!' AS status;
