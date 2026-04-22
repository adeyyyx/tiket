<?php
require_once '../includes/header.php';
cek_user();

$id_user = $_SESSION['id_user'];
$histories = query("SELECT o.*, t.nama_tiket, e.nama_event, d.qty 
                    FROM orders o
                    JOIN order_detail d ON o.id_order = d.id_order
                    JOIN tiket t ON d.id_tiket = t.id_tiket
                    JOIN event e ON t.id_event = e.id_event
                    WHERE o.id_user = $id_user 
                    ORDER BY o.tanggal_order DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0">Riwayat Pesanan</h2>
</div>

<?php if(isset($_GET['msg'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
<?php endif; ?>

            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tgl Order</th>
                        <th>Event & Tiket</th>
                        <th>Qty</th>
                        <th>Total Bayar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($histories as $h): ?>
                    <tr>
                        <td><?= date('d/m/Y H:i', strtotime($h['tanggal_order'])) ?></td>
                        <td>
                            <strong><?= htmlspecialchars($h['nama_event']) ?></strong><br>
                            <small class="text-muted"><?= htmlspecialchars($h['nama_tiket']) ?></small>
                        </td>
                        <td><?= $h['qty'] ?></td>
                        <td>Rp <?= number_format($h['total'],0,',','.') ?></td>
                        <td>
                            <?php if($h['status'] == 'pending'): ?>
                                <span class="badge bg-warning text-dark">Pending</span>
                            <?php elseif($h['status'] == 'paid'): ?>
                                <span class="badge bg-success">Lunas</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Batal</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($h['status'] == 'pending'): ?>
                                <a href="pay.php?id=<?= $h['id_order'] ?>" class="btn btn-sm btn-primary">Bayar</a>
                                <a href="cancel_order.php?id=<?= $h['id_order'] ?>" class="btn btn-sm btn-outline-danger ms-1" onclick="return confirm('Yakin ingin membatalkan pesanan ini? Kuota tiket akan dikembalikan.');">Batalkan</a>
                            <?php elseif($h['status'] == 'paid'): ?>
                                <a href="my_tickets.php?id_order=<?= $h['id_order'] ?>" class="btn btn-sm btn-info text-white">Lihat E-Tiket</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(count($histories) == 0): ?>
                    <tr><td colspan="6" class="text-center py-4">Belum ada riwayat pesanan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>


<?php require_once '../includes/footer.php'; ?>
