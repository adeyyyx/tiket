<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';
cek_user();

$id_order = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$id_user = $_SESSION['id_user'];

if($id_order > 0){
    // Pastikan order ini milik user yang sedang login dan statusnya masih pending
    $order = query("SELECT * FROM orders WHERE id_order = $id_order AND id_user = $id_user AND status = 'pending'");
    
    if(count($order) > 0){
        $o = $order[0];
        
        // Memulai transaksi database agar data aman jika terjadi gagal proses di tengah jalan
        mysqli_begin_transaction($conn);
        try {
            // 1. Ubah status order menjadi cancel
            mysqli_query($conn, "UPDATE orders SET status = 'cancel' WHERE id_order = $id_order");
            
            // 2. Kembalikan stok / kuota tiket yang sebelumnya di-reserve
            $details = query("SELECT * FROM order_detail WHERE id_order = $id_order");
            foreach($details as $d){
                $id_tiket = $d['id_tiket'];
                $qty = $d['qty'];
                mysqli_query($conn, "UPDATE tiket SET kuota = kuota + $qty WHERE id_tiket = $id_tiket");
            }
            
            // 3. Kembalikan kuota voucher jika user menggunakan voucher pada order ini
            if(!empty($o['id_voucher'])){
                $id_v = $o['id_voucher'];
                mysqli_query($conn, "UPDATE voucher SET kuota = kuota + 1 WHERE id_voucher = $id_v");
            }
            
            // Simpan semua perubahan
            mysqli_commit($conn);
            header("Location: history.php?msg=Pesanan berhasil dibatalkan. Kuota tiket telah dikembalikan.");
            exit;
        } catch(Exception $e){
            // Batalkan semua perubahan jika ada error
            mysqli_rollback($conn);
            header("Location: history.php?error=Sistem gagal membatalkan pesanan. Silakan coba lagi.");
            exit;
        }
    }
}

// Jika ID tidak valid atau status bukan pending, kembalikan ke history
header("Location: history.php");
exit;
?>
