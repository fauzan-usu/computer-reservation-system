<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5><i class="bi bi-airplane-engines-fill me-2"></i>CRS SMK Pariwisata</h5>
                <p class="mt-3">Sistem reservasi komputer untuk pembelajaran siswa SMK Pariwisata. Mencakup reservasi hotel, penerbangan, dan paket wisata.</p>
            </div>
            <div class="col-md-4 mb-4">
                <h5>Menu Cepat</h5>
                <ul class="list-unstyled mt-3">
                    <li><a href="<?php echo isset($base_path) ? $base_path : ''; ?>index.php" class="text-light text-decoration-none">Beranda</a></li>
                    <li><a href="<?php echo isset($base_path) ? $base_path : ''; ?>customer/login.php" class="text-light text-decoration-none">Login Customer</a></li>
                    <li><a href="<?php echo isset($base_path) ? $base_path : ''; ?>admin/login.php" class="text-light text-decoration-none">Login Admin</a></li>
                    <li><a href="<?php echo isset($base_path) ? $base_path : ''; ?>customer/register.php" class="text-light text-decoration-none">Daftar Akun</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-4">
                <h5>Informasi</h5>
                <ul class="list-unstyled mt-3">
                    <li><i class="bi bi-building me-2"></i>SMK Pariwisata</li>
                    <li><i class="bi bi-geo-alt me-2"></i>Indonesia</li>
                    <li><i class="bi bi-envelope me-2"></i>info@crs.sch.id</li>
                    <li><i class="bi bi-github me-2"></i><a href="#" class="text-light">GitHub Repository</a></li>
                </ul>
            </div>
        </div>
        <hr class="my-4" style="border-color: rgba(255,255,255,0.2);">
        <div class="text-center">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> CRS SMK Pariwisata. Dibuat untuk tujuan edukasi.</p>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="<?php echo isset($base_path) ? $base_path : ''; ?>assets/js/main.js"></script>
</body>
</html>
