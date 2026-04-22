<?php
require_once '../includes/header.php';
cek_user();

$tikets = query("SELECT t.*, e.nama_event, e.tanggal, v.nama_venue 
                 FROM tiket t 
                 JOIN event e ON t.id_event = e.id_event 
                 JOIN venue v ON e.id_venue = v.id_venue 
                 WHERE t.kuota > 0 
                 ORDER BY e.tanggal ASC");
?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="h3 border-bottom pb-2">Katalog Tiket Event</h2>
    </div>
</div>

<?php if(isset($_GET['msg'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
<?php endif; ?>
<?php if(isset($_GET['error'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>

<div class="row">
    <?php foreach($tikets as $t): ?>
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($t['nama_event']) ?></h5>
                <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($t['nama_tiket']) ?></h6>
                <p class="card-text mb-1">
                    <small>Venue: <?= htmlspecialchars($t['nama_venue']) ?></small><br>
                    <small>Tanggal: <?= date('d M Y', strtotime($t['tanggal'])) ?></small><br>
                    <small>Sisa Kuota: <strong><?= $t['kuota'] ?></strong></small>
                </p>
                <h5 class="text-primary mt-2">Rp <?= number_format($t['harga'],0,',','.') ?></h5>
            </div>
            <div class="card-footer bg-white border-0">
                <a href="order.php?id_tiket=<?= $t['id_tiket'] ?>" class="btn btn-outline-primary w-100">Pesan Sekarang</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php if(count($tikets)==0): ?>
    <div class="col-12 text-center text-muted">Belum ada tiket yang tersedia.</div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
