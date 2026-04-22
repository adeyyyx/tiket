<?php
require_once '../../includes/header.php';
cek_admin();

$orders = query("SELECT o.*, u.nama, u.email 
                 FROM orders o 
                 JOIN users u ON o.id_user = u.id_user 
                 ORDER BY o.tanggal_order DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0">Kelola Pesanan (Orders)</h2>
    <a href="../index.php" class="btn btn-secondary">Kembali</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID Order</th>
                    <th>Tanggal</th>
                    <th>Pembeli</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($orders as $o): ?>
                <tr>
                    <td>#<?= $o['id_order'] ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($o['tanggal_order'])) ?></td>
                    <td>
                        <strong><?= htmlspecialchars($o['nama']) ?></strong><br>
                        <small class="text-muted"><?= htmlspecialchars($o['email']) ?></small>
                    </td>
                    <td>Rp <?= number_format($o['total'],0,',','.') ?></td>
                    <td>
                        <?php if($o['status'] == 'pending'): ?>
                            <span class="badge bg-warning text-dark">Pending</span>
                        <?php elseif($o['status'] == 'paid'): ?>
                            <span class="badge bg-success">Paid</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Cancel</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(count($orders) == 0): ?>
                <tr><td colspan="5" class="text-center py-4">Belum ada transaksi.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
