<?php
session_start();

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Or a similar secure method
}
?>
<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <?php include '../includes/header.php' ?>
  <!--end::Head-->
  <!--begin::Body-->
  <body class="login-page bg-body-secondary">
    <div class="login-box">
      <div class="login-logo">
        <a href="#"><b>Admin</b>LTE 4</a>
      </div>
      <div class="card">
        <div class="card-body login-card-body">
          <p class="login-box-msg">Masuk untuk memulai</p>
          <form action="login_process.php" method="post">
            <input type="hidden" name="csrf_token" value="<?= isset($_SESSION['csrf_token']) ? htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') : ''; ?>">
            <div class="input-group mb-3">
              <input type="text" name="username" class="form-control" placeholder="Username" required />
              <div class="input-group-text"><span class="bi bi-envelope"></span></div>
            </div>
            <div class="input-group mb-3">
              <input type="password" name="password" class="form-control" placeholder="Password" required />
              <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
            </div>
            <div class="row">
              <div class="col-8">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                  <label class="form-check-label" for="flexCheckDefault"> Ingat saya </label>
                </div>
              </div>
              <div class="col-4">
                <div class="d-grid gap-2">
                  <button type="submit" class="btn btn-primary">Masuk</button>
                </div>
              </div>
            </div>
          </form>
          <?php if (isset($_SESSION['error'])): ?>
              <div class="alert alert-danger mt-3">
                  <?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8'); unset($_SESSION['error']); ?>
              </div>
          <?php endif; ?>
          <div class="social-auth-links text-center mb-3 d-grid gap-2">
            <p>- Atau -</p>
            <a href="#" class="btn btn-primary">
              <i class="bi bi-facebook me-2"></i> Masuk menggunakan Facebook
            </a>
            <a href="#" class="btn btn-danger">
              <i class="bi bi-google me-2"></i> Masuk menggunakan Google+
            </a>
          </div>
          <p class="mb-1"><a href="forgot-password.html">Lupa Password</a></p>
          <p class="mb-0">
            <a href="register.html" class="text-center"> Pendaftaran </a>
          </p>
        </div>
      </div>
    </div>
    <?php include '../includes/script.php' ?>
  </body>
</html>