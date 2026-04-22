<?php
require_once '../includes/header.php';
cek_user();

$id_tiket = isset($_GET['id_tiket']) ? (int)$_GET['id_tiket'] : 0;
if($id_tiket == 0){
    header("Location: index.php");
    exit;
}

$tiket_data = query("SELECT t.*, e.nama_event, v.nama_venue, e.tanggal 
                     FROM tiket t 
                     JOIN event e ON t.id_event = e.id_event 
                     JOIN venue v ON e.id_venue = v.id_venue 
                     WHERE t.id_tiket = $id_tiket");

if(count($tiket_data) == 0){
    header("Location: index.php?error=Tiket tidak ditemukan!");
    exit;
}

$tiket = $tiket_data[0];
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <h2 class="h3 mb-4">Checkout Tiket</h2>

        <div class="card shadow-sm border-0">
            <div class="card-body row">
                <div class="col-md-6 border-end">
                    <h5>Detail Event</h5>
                    <p><strong>Event:</strong> <?= htmlspecialchars($tiket['nama_event']) ?><br>
                    <strong>Venue:</strong> <?= htmlspecialchars($tiket['nama_venue']) ?><br>
                    <strong>Tanggal:</strong> <?= date('d M Y', strtotime($tiket['tanggal'])) ?></p>
                    <hr>
                    <h5>Detail Tiket</h5>
                    <p><strong>Kategori:</strong> <?= htmlspecialchars($tiket['nama_tiket']) ?><br>
                    <strong>Harga:</strong> Rp <span id="harga"><?= $tiket['harga'] ?></span><br>
                    <strong>Sisa Kuota:</strong> <?= $tiket['kuota'] ?></p>
                </div>
                <div class="col-md-6">
                    <form action="process_order.php" method="POST">
                        <input type="hidden" name="id_tiket" value="<?= $tiket['id_tiket'] ?>">
                        <div class="mb-3">
                            <label class="form-label">Jumlah (Qty)</label>
                            <input type="number" class="form-control" name="qty" id="qty" required min="1" max="<?= $tiket['kuota'] ?>" value="1">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kode Voucher (Opsional)</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="kode_voucher" id="kode_voucher" placeholder="Masukkan jika ada">
                                <button type="button" class="btn btn-outline-secondary" onclick="cekVoucher()">Cek</button>
                            </div>
                            <small id="voucher_msg" class="text-danger"></small>
                        </div>
                        <hr>
                        <h5 class="d-flex justify-content-between">
                            <span>Subtotal:</span>
                            <span id="label_subtotal">Rp <?= number_format($tiket['harga'],0,',','.') ?></span>
                        </h5>
                        <h5 class="d-flex justify-content-between text-success">
                            <span>Diskon:</span>
                            <span id="label_diskon">Rp 0</span>
                        </h5>
                        <h4 class="d-flex justify-content-between text-primary mt-3">
                            <span>Total Bayar:</span>
                            <span id="label_total">Rp <?= number_format($tiket['harga'],0,',','.') ?></span>
                        </h4>
                        
                        <input type="hidden" name="id_voucher" id="id_voucher" value="">
                        <button type="submit" class="btn btn-primary w-100 mt-4">Proses Pesanan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let harga_satuan = <?= $tiket['harga'] ?>;
let diskon = 0;

document.getElementById('qty').addEventListener('input', updateHitungan);

function updateHitungan() {
    let qty = parseInt(document.getElementById('qty').value) || 0;
    let subtotal = qty * harga_satuan;
    let total = Math.max(0, subtotal - diskon);

    document.getElementById('label_subtotal').innerText = 'Rp ' + Number(subtotal).toLocaleString('id-ID');
    document.getElementById('label_total').innerText = 'Rp ' + Number(total).toLocaleString('id-ID');
}

function cekVoucher() {
    let kode = document.getElementById('kode_voucher').value;
    if(kode === '') return;

    fetch('api_voucher.php?kode=' + kode)
    .then(r => r.json())
    .then(data => {
        let msg = document.getElementById('voucher_msg');
        if(data.valid) {
            msg.className = "text-success";
            msg.innerText = "Voucher diterapkan! Potongan: Rp " + Number(data.potongan).toLocaleString('id-ID');
            diskon = parseInt(data.potongan);
            document.getElementById('id_voucher').value = data.id_voucher;
            document.getElementById('label_diskon').innerText = '-Rp ' + Number(diskon).toLocaleString('id-ID');
        } else {
            msg.className = "text-danger";
            msg.innerText = data.pesan;
            diskon = 0;
            document.getElementById('id_voucher').value = "";
            document.getElementById('label_diskon').innerText = 'Rp 0';
        }
        updateHitungan();
    });
}
</script>

<?php require_once '../includes/footer.php'; ?>
