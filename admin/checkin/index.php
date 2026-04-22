<?php
require_once '../../includes/header.php';
cek_admin();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $kode = mysqli_real_escape_string($conn, trim(strtoupper($_POST['kode_tiket'])));
    
    // Cari tiket
    $cek = query("SELECT a.*, t.nama_tiket, e.nama_event, u.nama as pembeli 
                  FROM attendee a 
                  JOIN order_detail od ON a.id_detail = od.id_detail 
                  JOIN tiket t ON od.id_tiket = t.id_tiket 
                  JOIN event e ON t.id_event = e.id_event
                  JOIN orders o ON od.id_order = o.id_order
                  JOIN users u ON o.id_user = u.id_user
                  WHERE a.kode_tiket = '$kode'");
                  
    if(count($cek) > 0){
        $att = $cek[0];
        if($att['status_checkin'] == 'belum'){
            // Lakukan checkin
            $waktu = date('Y-m-d H:i:s');
            mysqli_query($conn, "UPDATE attendee SET status_checkin = 'sudah', waktu_checkin = '$waktu' WHERE kode_tiket = '$kode'");
            $success = "Check-in Berhasil untuk <strong>{$att['pembeli']}</strong> - Tiket: {$att['nama_tiket']} ({$att['nama_event']})";
        } else {
            $error = "Tiket sudah digunakan sebelumnya pada: " . date('d M Y H:i', strtotime($att['waktu_checkin']));
        }
    } else {
        $error = "Kode tiket tidak ditemukan atau tidak valid!";
    }
}
?>

<div class="row justify-content-center mt-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 text-center">
            <div class="card-body p-5">
                <h2 class="mb-4">Validasi Check-in</h2>
                
                <?php if(isset($success)): ?>
                    <div class="alert alert-success fs-5"><?= $success ?></div>
                <?php endif; ?>
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger fs-5"><?= $error ?></div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <input type="text" class="form-control form-control-lg text-center" name="kode_tiket" placeholder="Masukkan Kode Tiket (Misal: 4A8B9C)" required autocomplete="off">
                    </div>
                    <button type="submit" class="btn btn-danger btn-lg w-100">Check-in Tiket</button>
                    <a href="../index.php" class="btn btn-secondary mt-3">Kembali ke Dashboard</a>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-12">
        <h4 class="border-bottom pb-2">Riwayat Check-in Terbaru</h4>
        <?php
        $recent = query("SELECT a.*, o.tanggal_order, u.nama 
                         FROM attendee a 
                         JOIN order_detail od ON a.id_detail = od.id_detail 
                         JOIN orders o ON od.id_order = o.id_order 
                         JOIN users u ON o.id_user = u.id_user 
                         WHERE a.status_checkin = 'sudah' 
                         ORDER BY a.waktu_checkin DESC LIMIT 5");
        ?>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Waktu Check-in</th>
                    <th>Kode Tiket</th>
                    <th>Nama Pembeli</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($recent as $r): ?>
                <tr>
                    <td><?= date('d/m/Y H:i', strtotime($r['waktu_checkin'])) ?></td>
                    <td><span class="badge bg-secondary"><?= $r['kode_tiket'] ?></span></td>
                    <td><?= htmlspecialchars($r['nama']) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if(count($recent) == 0): ?>
                <tr><td colspan="3" class="text-center">Belum ada peserta yang check-in.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
