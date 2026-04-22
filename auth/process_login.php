<?php
session_start();
require_once '../config/database.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    
    if(mysqli_num_rows($result) === 1){
        $row = mysqli_fetch_assoc($result);
        
        // Cek password. Kami asumsikan default menggunakan password_hash, 
        // fallback ke pengecekan plain-text untuk jaga-jaga kalau datanya dummy manual.
        if(password_verify($password, $row['password']) || $password === $row['password']){
            // Set session
            $_SESSION['id_user'] = $row['id_user'];
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['role'];

            if($row['role'] === 'admin'){
                header("Location: ../admin/index.php");
            } elseif($row['role'] === 'petugas') {
                header("Location: ../petugas/checkin/index.php");
            } else {
                header("Location: ../user/index.php");
            }
            exit;
        } else {
            header("Location: login.php?error=Password salah!");
            exit;
        }
    } else {
        header("Location: login.php?error=Email tidak terdaftar!");
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
?>
