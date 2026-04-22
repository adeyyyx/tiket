<?php
require_once '../../includes/header.php';
cek_admin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$tiket = ['nama_tiket'=>'', 'id_event'=>'', 'harga'=>'', 'kuota'=>''];

if($id > 0){
    $t_data = query("SELECT * FROM tiket WHERE id_tiket = $id");
    if(count($t_data) > 0) $tiket = $t_data[0];
}

$events = query("SELECT * FROM event ORDER BY tanggal DESC");

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $nama = mysqli_real_escape_string($conn, $_POST['nama_tiket']);
    $id_event = (int)$_POST['id_event'];
    $harga = (int)$_POST['harga'];
    $kuota = (int)$_POST['kuota'];

    if($id > 0){
        $q = "UPDATE tiket SET nama_tiket='$nama', id_event=$id_event, harga=$harga, kuota=$kuota WHERE id_tiket=$id";
    } else {
        $q = "INSERT INTO tiket (nama_tiket, id_event, harga, kuota) VALUES ('$nama', $id_event, $harga, $kuota)";
    }

    if(mysqli_query($conn, $q)){
        header("Location: index.php?msg=" . ($id > 0 ? "Data diupdate" : "Data ditambah"));
        exit;
    } else {
        $error = "Terjadi kesalahan: " . mysqli_error($conn);
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <h2 class="h3 mb-4"><?= $id > 0 ? 'Edit' : 'Tambah' ?> Tiket</h2>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Tiket</label>
                        <input type="text" class="form-control" name="nama_tiket" value="<?= htmlspecialchars($tiket['nama_tiket']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Event</label>
                        <select class="form-select" name="id_event" required>
                            <option value="">Pilih Event</option>
                            <?php foreach($events as $e): ?>
                            <option value="<?= $e['id_event'] ?>" <?= $tiket['id_event'] == $e['id_event'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($e['nama_event']) ?> (<?= date('d M Y', strtotime($e['tanggal'])) ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="number" class="form-control" name="harga" value="<?= $tiket['harga'] ?>" required min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kuota</label>
                        <input type="number" class="form-control" name="kuota" value="<?= $tiket['kuota'] ?>" required min="1">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="index.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
