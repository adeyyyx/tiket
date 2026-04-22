<?php
function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function cek_login() {
    if(!isset($_SESSION['id_user'])) {
        header("Location: ../auth/login.php");
        exit;
    }
}

function cek_admin() {
    cek_login();
    if($_SESSION['role'] !== 'admin') {
        echo "<script>alert('Akses Ditolak! Anda bukan admin.'); window.location='../user/index.php';</script>";
        exit;
    }
}

function cek_user() {
    cek_login();
    if($_SESSION['role'] !== 'user') {
        echo "<script>alert('Akses Ditolak! Anda bukan user biasa.'); window.location='../admin/index.php';</script>";
        exit;
    }
}
?>
