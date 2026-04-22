<?php
require_once '../../includes/header.php';
cek_admin();

// Hapus Data
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM venue WHERE id_venue = $id");
    header("Location: index.php?msg=Data dihapus");
    exit;
}

$venues = query("SELECT * FROM venue ORDER BY id_venue DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0">Kelola Venue</h2>
    <a href="form.php" class="btn btn-primary">Tambah Venue</a>
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
                    <th>Nama Venue</th>
                    <th>Alamat</th>
                    <th>Kapasitas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no=1; foreach($venues as $v): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($v['nama_venue']) ?></td>
                    <td><?= htmlspecialchars($v['alamat']) ?></td>
                    <td><?= number_format($v['kapasitas']) ?></td>
                    <td>
                        <a href="form.php?id=<?= $v['id_venue'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="index.php?delete=<?= $v['id_venue'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?');">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(count($venues)==0): ?>
                <tr><td colspan="5" class="text-center">Belum ada data venue.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
