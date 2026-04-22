<?php
require_once '../../includes/header.php';
cek_admin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$voucher = ['kode_voucher'=>'', 'potongan'=>'', 'kuota'=>'', 'status'=>'aktif'];

if($id > 0){
    $v_data = query("SELECT * FROM voucher WHERE id_voucher = $id");
    if(count($v_data) > 0) $voucher = $v_data[0];
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $kode = mysqli_real_escape_string($conn, strtoupper($_POST['kode_voucher']));
    $potongan = (int)$_POST['potongan'];
    $kuota = (int)$_POST['kuota'];
    $status = $_POST['status'];

    if($id > 0){
        $q = "UPDATE voucher SET kode_voucher='$kode', potongan=$potongan, kuota=$kuota, status='$status' WHERE id_voucher=$id";
    } else {
        $q = "INSERT INTO voucher (kode_voucher, potongan, kuota, status) VALUES ('$kode', $potongan, $kuota, '$status')";
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
        <h2 class="h3 mb-4"><?= $id > 0 ? 'Edit' : 'Tambah' ?> Voucher</h2>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Kode Voucher</label>
                        <input type="text" class="form-control" name="kode_voucher" value="<?= htmlspecialchars($voucher['kode_voucher']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Potongan Harga (Rp)</label>
                        <input type="number" class="form-control" name="potongan" value="<?= $voucher['potongan'] ?>" required min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kuota</label>
                        <input type="number" class="form-control" name="kuota" value="<?= $voucher['kuota'] ?>" required min="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" required>
                            <option value="aktif" <?= $voucher['status'] == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="nonaktif" <?= $voucher['status'] == 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
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
