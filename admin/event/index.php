<?php
require_once '../../includes/header.php';
cek_admin();

// Hapus Data
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM event WHERE id_event = $id");
    header("Location: index.php?msg=Data dihapus");
    exit;
}

$events = query("SELECT e.*, v.nama_venue FROM event e LEFT JOIN venue v ON e.id_venue = v.id_venue ORDER BY e.tanggal DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0">Kelola Event</h2>
    <a href="form.php" class="btn btn-primary">Tambah Event</a>
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
                    <th>Gambar</th>
                    <th>Nama Event</th>
                    <th>Tanggal</th>
                    <th>Venue</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no=1; foreach($events as $e): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td>
                        <?php if(!empty($e['gambar'])): ?>
                            <img src="<?= $base_url ?>/assets/images/events/<?= htmlspecialchars($e['gambar']) ?>" alt="Img" style="width: 50px; height: 50px; object-fit: cover;" class="rounded shadow-sm">
                        <?php else: ?>
                            <span class="text-muted small"><i class="bi bi-image"></i></span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($e['nama_event']) ?></td>
                    <td><?= date('d M Y', strtotime($e['tanggal'])) ?></td>
                    <td><?= htmlspecialchars($e['nama_venue'] ?? '-') ?></td>
                    <td>
                        <a href="form.php?id=<?= $e['id_event'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="index.php?delete=<?= $e['id_event'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?');">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(count($events)==0): ?>
                <tr><td colspan="6" class="text-center">Belum ada data event.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
