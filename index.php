<?php
require_once 'includes/header.php';
?>

<div class="row align-items-center mb-5">
    <div class="col-md-6">
        <h1 class="display-4 fw-bold">Selamat Datang di TicketApp</h1>
        <p class="lead">Platform pemesanan tiket event termudah dan tercepat. Temukan berbagai acara menarik di sekitar Anda.</p>
        <a href="auth/login.php" class="btn btn-primary btn-lg mt-3">Mulai Sekarang</a>
    </div>
    <div class="col-md-6">
        <!-- Placeholder for hero image -->
        <div class="p-5 bg-light rounded text-center">
            <h3>🎫 Event Seru Menanti Anda!</h3>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="h3 border-bottom pb-2">Event Mendatang</h2>
    </div>
</div>
<div class="row">
    <?php
    $events = query("SELECT e.*, v.nama_venue FROM event e LEFT JOIN venue v ON e.id_venue = v.id_venue ORDER BY tanggal ASC LIMIT 4");
    if(count($events) > 0):
        foreach($events as $ev):
    ?>
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm h-100">
            <?php if(!empty($ev['gambar'])): ?>
                <img src="<?= $base_url ?>/assets/images/events/<?= htmlspecialchars($ev['gambar']) ?>" class="card-img-top" alt="Event Image" style="height: 150px; object-fit: cover;">
            <?php else: ?>
                <div class="bg-light d-flex align-items-center justify-content-center text-muted border-bottom" style="height: 150px;">
                    <i class="bi bi-image fs-1"></i>
                </div>
            <?php endif; ?>
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($ev['nama_event']) ?></h5>
                <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($ev['nama_venue'] ?? 'TBA') ?></h6>
                <p class="card-text">
                    <small>Tanggal: <?= date('d M Y', strtotime($ev['tanggal'])) ?></small>
                </p>
                <a href="auth/login.php" class="btn btn-sm btn-outline-primary w-100">Pesan Tiket</a>
            </div>
        </div>
    </div>
    <?php 
        endforeach;
    else: 
    ?>
    <div class="col-12 text-center">
        <p class="text-muted">Jadwal event belum tersedia.</p>
    </div>
    <?php endif; ?>
</div>

<?php
require_once 'includes/footer.php';
?>
