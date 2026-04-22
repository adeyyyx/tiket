<?php
require_once '../includes/header.php';
cek_user();

$id_order = isset($_GET['id_order']) ? (int)$_GET['id_order'] : 0;
$id_user = $_SESSION['id_user'];

// Cek ownership
$cek = query("SELECT id_order FROM orders WHERE id_order = $id_order AND id_user = $id_user AND status = 'paid'");
if(count($cek) == 0){
    header("Location: history.php");
    exit;
}

$attendees = query("SELECT a.*, t.nama_tiket, e.nama_event, e.tanggal, v.nama_venue 
                    FROM attendee a 
                    JOIN order_detail od ON a.id_detail = od.id_detail
                    JOIN tiket t ON od.id_tiket = t.id_tiket
                    JOIN event e ON t.id_event = e.id_event
                    JOIN venue v ON e.id_venue = v.id_venue
                    WHERE od.id_order = $id_order");
?>

<div class="row align-items-center mb-4">
    <div class="col-8">
        <h2 class="h3 mb-0">E-Tiket Anda</h2>
    </div>
    <div class="col-4 text-end">
        <a href="history.php" class="btn btn-outline-secondary">Kembali</a>
    </div>
</div>

<div class="row">
    <?php foreach($attendees as $a): ?>
    <div class="col-md-6 mb-4">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white d-flex justify-content-between">
                <span>🎫 <?= htmlspecialchars($a['nama_tiket']) ?></span>
                <strong><?= $a['kode_tiket'] ?></strong>
            </div>
            <div class="card-body">
                <h4 class="card-title"><?= htmlspecialchars($a['nama_event']) ?></h4>
                <p class="card-text mb-1"><small>Venue: <?= htmlspecialchars($a['nama_venue']) ?></small></p>
                <p class="card-text mb-3"><small>Tanggal: <?= date('d M Y', strtotime($a['tanggal'])) ?></small></p>
                
                <h5 class="text-center mt-4">KODE MASUK</h5>
                <h2 class="text-center tracking-widest fw-bold font-monospace bg-light p-2 border rounded"><?= $a['kode_tiket'] ?></h2>
                
                <div class="text-center mt-3 text-muted">
                    <small>Status: <?= $a['status_checkin'] == 'sudah' ? '✅ Sudah Digunakan' : 'Menunggu Check-in' ?></small>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
