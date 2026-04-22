<?php
global $base_url;
$uri = $_SERVER['REQUEST_URI'];
?>

<style>
/* Reset Body if Admin */
body.admin-mode {
    background-color: #f4f7fa;
    overflow-x: hidden;
}

/* Sidebar Wrapper */
.modern-sidebar {
    width: 280px;
    height: 100vh;
    background-color: #ffffff;
    border-right: 1px solid #eef2f7;
    position: sticky;
    top: 0;
    display: flex;
    flex-direction: column;
    box-shadow: 2px 0 15px rgba(0,0,0,0.02);
    z-index: 1040;
}

/* Sidebar Branding */
.sidebar-brand {
    padding: 1.5rem;
    font-size: 1.25rem;
    font-weight: 700;
    color: #313a46;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #eef2f7;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Sidebar Section Headers */
.sidebar-title {
    padding: 1rem 1.5rem 0.5rem;
    font-size: 0.7rem;
    text-transform: uppercase;
    font-weight: 700;
    color: #98a6ad;
    letter-spacing: 0.8px;
}

/* Sidebar Links */
.sidebar-menu {
    flex-grow: 1;
    overflow-y: auto;
    padding: 0.75rem;
}

.sidebar-menu::-webkit-scrollbar {
    width: 6px;
}
.sidebar-menu::-webkit-scrollbar-thumb {
    background-color: #eef2f7;
    border-radius: 10px;
}

.nav-pills .nav-link {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    color: #6c757d;
    font-weight: 500;
    padding: 0.7rem 1rem;
    border-radius: 0.4rem;
    margin-bottom: 0.3rem;
    transition: all 0.2s ease-in-out;
}

.nav-pills .nav-link i {
    font-size: 1.2rem;
}

/* Hover Effect */
.nav-pills .nav-link:hover {
    color: #4361ee;
    background-color: #f8f9fa;
}

/* Active State (Pills yang kontras tapi tenang) */
.nav-pills .nav-link.active {
    color: #4361ee;
    background-color: #eff3ff;
}

/* Sidebar Footer (Settings & Logout) */
.sidebar-footer {
    padding: 1rem;
    border-top: 1px solid #eef2f7;
    background: #fff;
    margin-top: auto;
}

.btn-sidebar-footer {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    color: #6c757d;
    padding: 0.6rem 1rem;
    border-radius: 0.4rem;
    transition: background 0.2s;
    text-decoration: none;
    font-weight: 500;
}

.btn-sidebar-footer:hover {
    background-color: #f8f9fa;
    color: #313a46;
}

.btn-sidebar-footer.text-danger:hover {
    background-color: #feefef;
    color: #fa5c7c !important;
}
</style>

<div class="modern-sidebar">
    <div class="sidebar-brand">
        <i class="bi bi-ticket-detailed text-primary fs-4"></i>
        Ticket<span class="text-primary">App</span>
    </div>

    <!-- User Profile Snippet -->
    <div class="d-flex align-items-center px-4 py-3 border-bottom">
        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2 flex-shrink-0" style="width: 36px; height: 36px;">
            <i class="bi bi-person"></i>
        </div>
        <div class="text-truncate">
            <h6 class="mb-0 fw-bold text-truncate" style="font-size: 0.9rem;" title="<?= htmlspecialchars($_SESSION['nama'] ?? 'User') ?>"><?= htmlspecialchars($_SESSION['nama'] ?? 'User') ?></h6>
            <small class="text-muted" style="font-size: 0.75rem;"><?= $is_admin ? 'Administrator' : ($is_petugas ? 'Petugas' : 'Pengguna/Reguler') ?></small>
        </div>
    </div>

    <div class="sidebar-menu nav-pills flex-column mb-auto">
        
        <?php if($is_admin): ?>
        <div class="sidebar-title">Utama</div>
        <a href="<?= $base_url ?>/admin/index.php" class="nav-link <?= strpos($uri, '/admin/index.php') !== false ? 'active' : '' ?>">
            <i class="bi bi-house"></i> Dashboard
        </a>

        <div class="sidebar-title">Manajemen Data</div>
        <a href="<?= $base_url ?>/admin/venue/index.php" class="nav-link <?= strpos($uri, '/admin/venue/index.php') !== false ? 'active' : '' ?>">
            <i class="bi bi-geo-alt"></i> Kelola Venue
        </a>
        <a href="<?= $base_url ?>/admin/event/index.php" class="nav-link <?= strpos($uri, '/admin/event/index.php') !== false ? 'active' : '' ?>">
            <i class="bi bi-calendar-event"></i> Kelola Event
        </a>
        <a href="<?= $base_url ?>/admin/tiket/index.php" class="nav-link <?= strpos($uri, '/admin/tiket/index.php') !== false ? 'active' : '' ?>">
            <i class="bi bi-ticket-perforated"></i> Kelola Tiket
        </a>
        <a href="<?= $base_url ?>/admin/voucher/index.php" class="nav-link <?= strpos($uri, '/admin/voucher/index.php') !== false ? 'active' : '' ?>">
            <i class="bi bi-tags"></i> Kelola Voucher
        </a>

        <div class="sidebar-title">Laporan</div>
        <a href="<?= $base_url ?>/admin/orders/index.php" class="nav-link <?= strpos($uri, '/admin/orders/index.php') !== false ? 'active' : '' ?>">
            <i class="bi bi-receipt"></i> Riwayat Pesanan
        </a>
        
        <?php elseif($is_petugas): ?>
        <div class="sidebar-title">Utama</div>
        <a href="<?= $base_url ?>/petugas/checkin/index.php" class="nav-link <?= strpos($uri, '/petugas/checkin') !== false ? 'active' : '' ?>">
            <i class="bi bi-qr-code-scan"></i> Validasi Check-in
        </a>
        <a href="<?= $base_url ?>/petugas/history/index.php" class="nav-link <?= strpos($uri, '/petugas/history') !== false ? 'active' : '' ?>">
            <i class="bi bi-clock-history"></i> Riwayat Check-in
        </a>

        <?php else: ?>
        <div class="sidebar-title">Eksplorasi</div>
        <a href="<?= $base_url ?>/user/index.php" class="nav-link <?= strpos($uri, '/user/index.php') !== false || strpos($uri, '/user/order.php') !== false || strpos($uri, '/user/pay.php') !== false ? 'active' : '' ?>">
            <i class="bi bi-ticket-detailed"></i> Katalog Event
        </a>

        <div class="sidebar-title">Transaksi & Tiket</div>
        <a href="<?= $base_url ?>/user/history.php" class="nav-link <?= strpos($uri, '/user/history.php') !== false || strpos($uri, '/user/my_tickets.php') !== false ? 'active' : '' ?>">
            <i class="bi bi-receipt"></i> Riwayat & E-Tiket
        </a>
        <?php endif; ?>

    </div>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <a href="<?= $base_url ?>/auth/logout.php" class="btn-sidebar-footer w-100 text-danger mt-1">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</div>
