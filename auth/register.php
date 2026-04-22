<?php
require_once '../includes/header.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'user'; // default

    // Cek email eksis
    $cek = mysqli_query($conn, "SELECT id_user FROM users WHERE email = '$email'");
    if(mysqli_num_rows($cek) > 0){
        $error = "Email sudah digunakan!";
    } else {
        $query = "INSERT INTO users (nama, email, password, role) VALUES ('$nama', '$email', '$password', '$role')";
        if(mysqli_query($conn, $query)){
            header("Location: login.php?msg=Registrasi Berhasil. Silahkan login.");
            exit;
        } else {
            $error = "Gagal mendaftar: " . mysqli_error($conn);
        }
    }
}
?>
<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h3 class="card-title text-center mb-4">Daftar Akun Baru</h3>
                
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Daftar</button>
                </form>
                <div class="mt-3 text-center">
                    <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
