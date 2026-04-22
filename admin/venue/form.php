<?php
require_once '../../includes/header.php';
cek_admin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$venue = ['nama_venue'=>'', 'alamat'=>'', 'kapasitas'=>''];

if($id > 0){
    $v_data = query("SELECT * FROM venue WHERE id_venue = $id");
    if(count($v_data) > 0) $venue = $v_data[0];
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $nama = mysqli_real_escape_string($conn, $_POST['nama_venue']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $kapasitas = (int)$_POST['kapasitas'];

    if($id > 0){
        $q = "UPDATE venue SET nama_venue='$nama', alamat='$alamat', kapasitas=$kapasitas WHERE id_venue=$id";
    } else {
        $q = "INSERT INTO venue (nama_venue, alamat, kapasitas) VALUES ('$nama', '$alamat', $kapasitas)";
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
        <h2 class="h3 mb-4"><?= $id > 0 ? 'Edit' : 'Tambah' ?> Venue</h2>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Venue</label>
                        <input type="text" class="form-control" name="nama_venue" value="<?= htmlspecialchars($venue['nama_venue']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control" name="alamat" rows="3" required><?= htmlspecialchars($venue['alamat']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kapasitas</label>
                        <input type="number" class="form-control" name="kapasitas" value="<?= $venue['kapasitas'] ?>" required min="1">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="index.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
