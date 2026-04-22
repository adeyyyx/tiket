<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';
cek_user();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $id_user = $_SESSION['id_user'];
    $id_tiket = (int)$_POST['id_tiket'];
    $qty = (int)$_POST['qty'];
    $id_voucher = !empty($_POST['id_voucher']) ? (int)$_POST['id_voucher'] : "NULL";

    // Validasi kuota tiket
    $c_tiket = query("SELECT harga, kuota FROM tiket WHERE id_tiket = $id_tiket");
    if(count($c_tiket) == 0){
        die("Tiket tidak ditemukan.");
    }
    
    $harga = $c_tiket[0]['harga'];
    $kuota_tiket = $c_tiket[0]['kuota'];

    if($qty > $kuota_tiket) {
        header("Location: index.php?error=Gagal! Kuota tiket tidak mencukupi.");
        exit;
    }

    $subtotal = $harga * $qty;
    $potongan = 0;

    // Jika pakai voucher
    if($id_voucher !== "NULL"){
        $c_voucher = query("SELECT potongan, kuota FROM voucher WHERE id_voucher = $id_voucher AND status='aktif' AND kuota > 0");
        if(count($c_voucher) > 0){
            $potongan = $c_voucher[0]['potongan'];
            
            // Kurangi kuota voucher
            mysqli_query($conn, "UPDATE voucher SET kuota = kuota - 1 WHERE id_voucher = $id_voucher");
        } else {
            $id_voucher = "NULL"; // invalid voucher
        }
    }

    $total = max(0, $subtotal - $potongan);
    $tgl_order = date('Y-m-d H:i:s');

    // Transaksi Database (Mencegah over kuota dan memastikan integrasi data)
    mysqli_begin_transaction($conn);
    try {
        // Insert to orders
        $q_order = "INSERT INTO orders (id_user, tanggal_order, total, status, id_voucher) 
                    VALUES ($id_user, '$tgl_order', $total, 'pending', $id_voucher)";
        mysqli_query($conn, $q_order);
        $id_order = mysqli_insert_id($conn);

        // Insert to order_detail
        $q_detail = "INSERT INTO order_detail (id_order, id_tiket, qty, subtotal) 
                     VALUES ($id_order, $id_tiket, $qty, $subtotal)";
        mysqli_query($conn, $q_detail);
        $id_detail = mysqli_insert_id($conn);

        // Kurangi kuota tiket
        $q_update_tiket = "UPDATE tiket SET kuota = kuota - $qty WHERE id_tiket = $id_tiket";
        mysqli_query($conn, $q_update_tiket);

        mysqli_commit($conn);
        header("Location: pay.php?id=$id_order");
        exit;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        header("Location: index.php?error=Terjadi kesalahan sistem saat memproses.");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>
