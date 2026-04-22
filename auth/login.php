<?php
require_once '../includes/header.php';

if(isset($_SESSION['id_user'])){
    if($_SESSION['role'] === 'admin'){
        header("Location: ../admin/index.php");
    } elseif($_SESSION['role'] === 'petugas'){
        header("Location: ../petugas/checkin/index.php");
    } else {
        header("Location: ../user/index.php");
    }
    exit;
}
?>
<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h3 class="card-title text-center mb-4">Login</h3>
                
                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
                <?php endif; ?>
                <?php if(isset($_GET['msg'])): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
                <?php endif; ?>

                <form action="process_login.php" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
                <div class="mt-3 text-center">
                    <p>Belum punya akun? <a href="register.php">Daftar sekarang</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
