<?php
require_once '../../includes/header.php';
cek_admin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$event = ['nama_event'=>'', 'tanggal'=>'', 'id_venue'=>'', 'gambar'=>''];

if($id > 0){
    $e_data = query("SELECT * FROM event WHERE id_event = $id");
    if(count($e_data) > 0) $event = $e_data[0];
}

$venues = query("SELECT * FROM venue ORDER BY nama_venue ASC");

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $nama = mysqli_real_escape_string($conn, $_POST['nama_event']);
    $tanggal = $_POST['tanggal'];
    $id_venue = (int)$_POST['id_venue'];

    // Handle file upload
    $gambar = $event['gambar'] ?? null;
    
    if(isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0){
        $target_dir = "../../assets/images/events/";
        if(!is_dir($target_dir)){
            mkdir($target_dir, 0777, true);
        }
        $file_name = time() . '_' . basename($_FILES["gambar"]["name"]);
        $target_file = $target_dir . $file_name;
        
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if(in_array($imageFileType, $allowed)){
            if(move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)){
                $gambar = $file_name;
            } else {
                $error = "Gagal mengupload gambar.";
            }
        } else {
            $error = "Format gambar tidak didukung.";
        }
    }

    if(!isset($error)){
        if($id > 0){
            $q = "UPDATE event SET nama_event='$nama', tanggal='$tanggal', id_venue=$id_venue";
            if($gambar) $q .= ", gambar='$gambar'";
            $q .= " WHERE id_event=$id";
        } else {
            $gambar_val = $gambar ? "'$gambar'" : "NULL";
            $q = "INSERT INTO event (nama_event, tanggal, id_venue, gambar) VALUES ('$nama', '$tanggal', $id_venue, $gambar_val)";
        }

        if(mysqli_query($conn, $q)){
            header("Location: index.php?msg=" . ($id > 0 ? "Data diupdate" : "Data ditambah"));
            exit;
        } else {
            $error = "Terjadi kesalahan: " . mysqli_error($conn);
        }
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
                <form action="" method="POST" enctype="multipart/form-data">
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
                    <div class="mb-3">
                        <label class="form-label">Gambar Event</label>
                        <?php if(!empty($event['gambar'])): ?>
                            <div class="mb-2">
                                <img src="<?= $base_url ?>/assets/images/events/<?= htmlspecialchars($event['gambar']) ?>" alt="Event Image" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                        <?php endif; ?>
                        <input type="file" class="form-control" name="gambar" accept="image/*">
                        <div class="form-text">Format didukung: JPG, PNG, GIF, WEBP. Biarkan kosong jika tidak ingin mengubah gambar.</div>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="index.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
