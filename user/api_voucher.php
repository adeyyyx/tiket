<?php
require_once '../config/database.php';

header('Content-Type: application/json');

if(!isset($_GET['kode'])) {
    echo json_encode(['valid' => false, 'pesan' => 'Kode kosong!']);
    exit;
}

$kode = mysqli_real_escape_string($conn, $_GET['kode']);

// Cek voucher
$v_query = mysqli_query($conn, "SELECT * FROM voucher WHERE kode_voucher = '$kode' AND status = 'aktif' AND kuota > 0");
if(mysqli_num_rows($v_query) > 0){
    $v = mysqli_fetch_assoc($v_query);
    echo json_encode([
        'valid' => true,
        'id_voucher' => $v['id_voucher'],
        'potongan' => $v['potongan']
    ]);
} else {
    echo json_encode([
        'valid' => false,
        'pesan' => 'Voucher tidak valid, sudah nonaktif, atau kuota habis.'
    ]);
}
?>
