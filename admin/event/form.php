<?php
require_once '../../includes/header.php';
cek_admin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$event = ['nama_event'=>'', 'tanggal'=>'', 'id_venue'=>''];

if($id > 0){
    $e_data = query("SELECT * FROM event WHERE id_event = $id");
    if(count($e_data) > 0) $event = $e_data[0];
}

$venues = query("SELECT * FROM venue ORDER BY nama_venue ASC");

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $nama = mysqli_real_escape_string($conn, $_POST['nama_event']);
    $tanggal = $_POST['tanggal'];
    $id_venue = (int)$_POST['id_venue'];

    if($id > 0){
        $q = "UPDATE event SET nama_event='$nama', tanggal='$tanggal', id_venue=$id_venue WHERE id_event=$id";
    } else {
        $q = "INSERT INTO event (nama_event, tanggal, id_venue) VALUES ('$nama', '$tanggal', $id_venue)";
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
        <h2 class="h3 mb-4"><?= $id > 0 ? 'Edit' : 'Tambah' ?> Event</h2>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Event</label>
                        <input type="text" class="form-control" name="nama_event" value="<?= htmlspecialchars($event['nama_event']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" value="<?= $event['tanggal'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Venue</label>
                        <select class="form-select" name="id_venue" required>
                            <option value="">Pilih Venue</option>
                            <?php foreach($venues as $v): ?>
                            <option value="<?= $v['id_venue'] ?>" <?= $event['id_venue'] == $v['id_venue'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($v['nama_venue']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="index.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
