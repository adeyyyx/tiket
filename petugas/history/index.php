<?php
require_once '../../includes/header.php';
cek_petugas();

$recent = query("SELECT a.*, o.tanggal_order, u.nama 
                 FROM attendee a 
                 JOIN order_detail od ON a.id_detail = od.id_detail 
                 JOIN orders o ON od.id_order = o.id_order 
                 JOIN users u ON o.id_user = u.id_user 
                 WHERE a.status_checkin = 'sudah' 
                 ORDER BY a.waktu_checkin DESC LIMIT 50");
?>

<div class="row mt-4">
    <div class="col-12">
        <h4 class="border-bottom pb-2">Riwayat Check-in</h4>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <table class="table table-hover mt-3">
                    <thead class="table-light">
                        <tr>
                            <th>Waktu Check-in</th>
                            <th>Kode Tiket</th>
                            <th>Nama Pembeli</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($recent as $r): ?>
                        <tr>
                            <td><?= date('d/m/Y H:i', strtotime($r['waktu_checkin'])) ?></td>
                            <td><span class="badge bg-secondary"><?= $r['kode_tiket'] ?></span></td>
                            <td><?= htmlspecialchars($r['nama']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(count($recent) == 0): ?>
                        <tr><td colspan="3" class="text-center py-4">Belum ada peserta yang check-in.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
