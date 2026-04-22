<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start(); // Prevent headers already sent error globally
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

$base_url = "http://localhost/tiket"; // Sesuaikan jika ada perubahan path
$is_logged_in = isset($_SESSION['id_user']);
$is_admin = $is_logged_in && $_SESSION['role'] === 'admin';
$is_dashboard_layout = $is_logged_in && (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false || strpos($_SERVER['REQUEST_URI'], '/user/') !== false);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pemesanan Tiket Event</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= $base_url ?>/assets/css/style.css">
</head>
<body class="<?= $is_dashboard_layout ? 'admin-mode' : 'bg-light' ?>">

<?php if($is_dashboard_layout): ?>
    <!-- DASHBOARD LAYOUT: Flex Sidebar & Content -->
    <div class="d-flex" style="min-height: 100vh;">
        
        <!-- Sidebar Kiri -->
        <?php require_once __DIR__ . '/sidebar.php'; ?>

        <!-- Konten Kanan -->
        <div class="flex-grow-1 d-flex flex-column" style="height: 100vh; overflow-y: auto;">
            
            <!-- Navbar Atas Dashboard -->
            <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top py-3 px-4 shadow-sm" style="z-index: 1030;">
                <div class="container-fluid px-0">
                    <span class="navbar-brand mb-0 h1 fw-bold text-dark">
                        <?= $is_admin ? 'Panel Administratif' : 'Portal Pengunjung' ?>
                    </span>
                    
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    
                    <div class="collapse navbar-collapse" id="adminNavbar">
                        <ul class="navbar-nav ms-auto align-items-center">
                            <?php if($is_admin): ?>
                            <!-- Tombol Shortcut Check-in Admin -->
                            <li class="nav-item me-3">
                                <a class="btn btn-danger rounded-pill px-4 shadow-sm" href="<?= $base_url ?>/admin/checkin/index.php">
                                    <i class="bi bi-qr-code-scan me-1"></i> Validasi Check-in
                                </a>
                            </li>
                            <?php else: ?>
                            <!-- Tombol Shortcut Katalog Event User -->
                            <li class="nav-item me-3">
                                <a class="btn btn-primary rounded-pill px-4 shadow-sm" href="<?= $base_url ?>/user/my_tickets.php">
                                    <i class="bi bi-ticket-detailed me-1"></i> Pesan Tiket Baru
                                </a>
                            </li>
                            <?php endif; ?>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-dark fw-semibold" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($_SESSION['nama']) ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                                    <li><a class="dropdown-item" href="<?= $base_url ?>/auth/logout.php">Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Container Konten Halaman Admin/User -->
            <div class="p-4 flex-grow-1 bg-light">

<?php else: ?>
    <!-- PUBLIC LAYOUT (Index / Login / Register) -->
    <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top py-3 shadow-sm" style="z-index: 1030;">
      <div class="container">
        <a class="navbar-brand fw-bold text-dark" href="<?= $base_url ?>/index.php">
            <i class="bi bi-ticket-detailed text-primary fs-4 me-1"></i>
            Ticket<span class="text-primary">App</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <a class="nav-link text-dark fw-medium" href="<?= $base_url ?>/index.php"><i class="bi bi-house"></i> Beranda</a>
            </li>
          </ul>
          <ul class="navbar-nav ms-auto align-items-center">
            <?php if($is_logged_in): ?>
                <?php if($is_admin): ?>
                    <li class="nav-item me-2"><a class="nav-link btn btn-light rounded-pill px-3" href="<?= $base_url ?>/admin/index.php">Panel Admin</a></li>
                <?php else: ?>
                    <li class="nav-item me-2"><a class="nav-link btn btn-light rounded-pill px-3" href="<?= $base_url ?>/user/index.php">Dashboard</a></li>
                <?php endif; ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-dark fw-semibold" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($_SESSION['nama']) ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                        <li><a class="dropdown-item text-danger" href="<?= $base_url ?>/auth/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                    </ul>
                </li>
            <?php else: ?>
                <li class="nav-item"><a class="btn btn-primary rounded-pill px-4" href="<?= $base_url ?>/auth/login.php"><i class="bi bi-box-arrow-in-right"></i> Login</a></li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>
    <div class="container pb-5">
<?php endif; ?>
