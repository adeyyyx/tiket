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
            header("Location: history.php?msg=Pembayaran berhasil. Tiket Anda siap!");
            exit;
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $error = "Terjadi kesalahan saat pembayaran.";
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <h2 class="h3 mb-4 text-center">Pembayaran</h2>

        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h4 class="mb-3">Total Tagihan</h4>
                <h1 class="text-primary mb-4">Rp <?= number_format($order['total'],0,',','.') ?></h1>
                
                <?php if($order['status'] == 'pending'): ?>
                    <form action="" method="POST">
                        <button type="submit" class="btn btn-success btn-lg w-100">Bayar Sekarang</button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-success">Pesanan ini sudah dibayar!</div>
                    <a href="history.php" class="btn btn-primary w-100">Lihat Tiket</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
