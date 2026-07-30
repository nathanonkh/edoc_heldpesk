<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>เข้าสู่ระบบ — ระบบนำส่งเอกสารอิเล็กทรอนิกส์</title>
  <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <style>
    body { font-family: 'Sarabun', sans-serif; background: linear-gradient(135deg, #1565c0 0%, #0d47a1 50%, #1a237e 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
    .login-card { background: #fff; border-radius: 12px; padding: 2.5rem; width: 100%; max-width: 420px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
    .login-logo { text-align: center; margin-bottom: 1.5rem; }
    .login-logo .logo-icon { width: 70px; height: 70px; background: #1565c0; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
    .login-logo .logo-icon i { font-size: 1.8rem; color: #fff; }
    .login-logo h4 { color: #1565c0; font-weight: 700; margin: 0; font-size: 1.1rem; }
    .login-logo p { color: #666; font-size: 0.85rem; margin: 0; }
    .btn-login { background-color: #1565c0; color: #fff; width: 100%; padding: 0.6rem; font-size: 1rem; font-weight: 600; border: none; border-radius: 6px; }
    .btn-login:hover { background-color: #0d47a1; color: #fff; }
    .form-control:focus { border-color: #1565c0; box-shadow: 0 0 0 0.2rem rgba(21,101,192,0.2); }
    .input-group-text { background-color: #f8f9fa; }
  </style>
</head>
<body>
<div class="login-card">
  <div class="login-logo">
    <div class="logo-icon"><i class="fas fa-file-alt"></i></div>
    <h4>ระบบนำส่งเอกสารอิเล็กทรอนิกส์</h4>
    <p>สำนักงานตรวจบัญชีสหกรณ์ที่ 5</p>
  </div>

  <?php $flash = Session::getFlash(); if ($flash): ?>
  <div class="alert alert-<?php echo $flash['type'] === 'error' ? 'danger' : $flash['type']; ?> py-2 small mb-3">
    <?php echo e($flash['message']); ?>
  </div>
  <?php endif; ?>

  <form method="POST" action="?page=login&action=login">
    <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
    <div class="mb-3">
      <label class="form-label fw-semibold small">ชื่อผู้ใช้</label>
      <div class="input-group">
        <span class="input-group-text"><i class="fas fa-user text-secondary"></i></span>
        <input type="text" name="username" class="form-control"
               placeholder="กรอกชื่อผู้ใช้" autofocus required
               value="<?php echo isset($_POST['username']) ? e($_POST['username']) : ''; ?>">
      </div>
    </div>
    <div class="mb-4">
      <label class="form-label fw-semibold small">รหัสผ่าน</label>
      <div class="input-group">
        <span class="input-group-text"><i class="fas fa-lock text-secondary"></i></span>
        <input type="password" name="password" class="form-control" placeholder="กรอกรหัสผ่าน" required>
      </div>
    </div>
    <button type="submit" class="btn btn-login">
      <i class="fas fa-sign-in-alt me-2"></i>เข้าสู่ระบบ
    </button>
  </form>

  <p class="text-center text-muted small mt-3 mb-0">
    <i class="fas fa-shield-alt me-1"></i>ระบบรักษาความปลอดภัยด้วย Session
  </p>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</body>
</html>
