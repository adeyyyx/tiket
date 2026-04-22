<?php
require_once '../includes/header.php';
cek_user();

$id_order = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$id_user = $_SESSION['id_user'];

$order_q = query("SELECT * FROM orders WHERE id_order = $id_order AND id_user = $id_user");
if(count($order_q) == 0){
    header("Location: history.php");
    exit;
}

$order = $order_q[0];

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Proses Pembayaran (Mockup)
    if($order['status'] == 'pending'){
        mysqli_begin_transaction($conn);
        try {
            // Update status
            mysqli_query($conn, "UPDATE orders SET status = 'paid' WHERE id_order = $id_order");

            // Generate Attendee
            $details = query("SELECT * FROM order_detail WHERE id_order = $id_order");
            foreach($details as $d){
                $id_detail = $d['id_detail'];
                $qty = $d['qty'];
                for($i=0; $i<$qty; $i++){
                    // generate unique code
                    $kode = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
                    mysqli_query($conn, "INSERT INTO attendee (id_detail, kode_tiket, status_checkin) VALUES ($id_detail, '$kode', 'belum')");
                }
            }

            mysqli_commit($conn);
            $payment_success = true;
            $order['status'] = 'paid';
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $error = "Terjadi kesalahan saat pembayaran.";
        }
    }
}
?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="h3 border-bottom pb-2">Proses Pembayaran</h2>
        <p class="text-muted">Silakan selesaikan pembayaran Anda melalui pop-up yang muncul di layar.</p>
    </div>
</div>

<!-- Modal Pembayaran (Auto Show) -->
<div class="modal fade" id="paymentActionModal" tabindex="-1" aria-labelledby="paymentActionModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-bottom">
        <h5 class="modal-title fw-bold" id="paymentActionModalLabel">Pembayaran Pesanan</h5>
        <?php if($order['status'] !== 'pending'): ?>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="window.location='history.php'"></button>
        <?php else: ?>
            <a href="history.php" class="btn-close" aria-label="Close"></a>
        <?php endif; ?>
      </div>
      <div class="modal-body text-center p-4">
        <h5 class="mb-3 text-muted">Total Tagihan</h5>
        <h1 class="text-primary mb-4 fw-bold">Rp <?= number_format($order['total'],0,',','.') ?></h1>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <?php if($order['status'] == 'pending'): ?>
            <form action="" method="POST">
                <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill shadow-sm">
                    <i class="bi bi-wallet2 me-2"></i>Bayar Sekarang
                </button>
            </form>
        <?php else: ?>
            <div class="alert alert-success mt-3 mb-4"><i class="bi bi-check-circle me-1"></i> Pesanan ini sudah dibayar!</div>
            <a href="history.php" class="btn btn-primary w-100 rounded-pill">Lihat E-Tiket Saya</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php if(!isset($payment_success) || !$payment_success): ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var payModal = new bootstrap.Modal(document.getElementById('paymentActionModal'), {
        backdrop: 'static',
        keyboard: false
    });
    payModal.show();
});
</script>
<?php endif; ?>

<?php if(isset($payment_success) && $payment_success): ?>
<!-- Modal Notifikasi Pembayaran Berhasil -->
<div class="modal fade" id="paymentSuccessModal" tabindex="-1" aria-labelledby="paymentSuccessModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center border-0 shadow">
      <div class="modal-header border-0 pb-0 justify-content-end">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pt-0 pb-4 px-4">
        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
        <h4 class="mt-3 mb-2 fw-bold">Pembayaran Berhasil!</h4>
        <p class="text-muted">Terima kasih, tiket Anda sudah siap dan dapat digunakan untuk check-in.</p>
        <a href="history.php" class="btn btn-primary w-100 mt-3 rounded-pill py-2 fw-semibold">Lihat E-Tiket Saya</a>
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var myModal = new bootstrap.Modal(document.getElementById('paymentSuccessModal'), {
        backdrop: 'static',
        keyboard: false
    });
    myModal.show();
});
</script>
<?php endif; ?>

<?php require_once '../includes/footer.php'; ?>
