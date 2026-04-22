<?php
require_once '../../includes/header.php';
cek_admin();

// Hapus Data
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM tiket WHERE id_tiket = $id");
    header("Location: index.php?msg=Data dihapus");
    exit;
}

$tikets = query("SELECT t.*, e.nama_event FROM tiket t LEFT JOIN event e ON t.id_event = e.id_event ORDER BY t.id_tiket DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0">Kelola Tiket</h2>
    <a href="form.php" class="btn btn-primary">Tambah Tiket</a>
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
                    <th>Nama Tiket</th>
                    <th>Event</th>
                    <th>Harga</th>
                    <th>Kuota</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no=1; foreach($tikets as $t): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($t['nama_tiket']) ?></td>
                    <td><?= htmlspecialchars($t['nama_event'] ?? '-') ?></td>
                    <td>Rp <?= number_format($t['harga'],0,',','.') ?></td>
                    <td><?= $t['kuota'] ?></td>
                    <td>
                        <a href="form.php?id=<?= $t['id_tiket'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="index.php?delete=<?= $t['id_tiket'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?');">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(count($tikets)==0): ?>
                <tr><td colspan="6" class="text-center">Belum ada data tiket.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
