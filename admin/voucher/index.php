<?php
require_once '../../includes/header.php';
cek_admin();

// Hapus Data
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM voucher WHERE id_voucher = $id");
    header("Location: index.php?msg=Data dihapus");
    exit;
}

$vouchers = query("SELECT * FROM voucher ORDER BY id_voucher DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0">Kelola Voucher</h2>
    <a href="form.php" class="btn btn-primary">Tambah Voucher</a>
</div>

<?php if(isset($_GET['msg'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Kode Voucher</th>
                    <th>Potongan (Rp)</th>
                    <th>Sisa Kuota</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no=1; foreach($vouchers as $v): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($v['kode_voucher']) ?></td>
                    <td>Rp <?= number_format($v['potongan'],0,',','.') ?></td>
                    <td><?= $v['kuota'] ?></td>
                    <td>
                        <?php if($v['status'] == 'aktif'): ?>
                            <span class="badge bg-success">Aktif</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Nonaktif</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="form.php?id=<?= $v['id_voucher'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="index.php?delete=<?= $v['id_voucher'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?');">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(count($vouchers)==0): ?>
                <tr><td colspan="6" class="text-center">Belum ada data voucher.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
