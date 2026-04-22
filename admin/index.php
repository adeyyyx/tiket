<?php
require_once '../includes/header.php';
cek_admin();

// Hitung Statistik
$user_count = query("SELECT COUNT(id_user) as total FROM users WHERE role = 'user'")[0]['total'];
$order_count = query("SELECT COUNT(id_order) as total FROM orders")[0]['total'];
$pendapatan = query("SELECT SUM(total) as total FROM orders WHERE status = 'paid'")[0]['total'] ?? 0;
?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="h3 border-bottom pb-2">Admin Dashboard</h2>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card bg-primary text-white shadow">
            <div class="card-body">
                <h5 class="card-title">Total User</h5>
                <h2 class="display-5"><?= $user_count ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card bg-success text-white shadow">
            <div class="card-body">
                <h5 class="card-title">Total Order</h5>
                <h2 class="display-5"><?= $order_count ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card bg-info text-white shadow">
            <div class="card-body">
                <h5 class="card-title">Total Pendapatan</h5>
                <h2 class="display-5">Rp <?= number_format((float)$pendapatan, 0, ',', '.') ?></h2>
            </div>
        </div>
    </div>
</div>



<?php require_once '../includes/footer.php'; ?>
