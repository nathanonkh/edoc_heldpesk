<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>เข้าสู่ระบบ — ระบบนำส่งเอกสารอิเล็กทรอนิกส์</title>
  <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <style>body{font-family:'Sarabun',sans-serif;}</style>
</head>
<body class="min-h-screen flex items-center justify-center"
      style="background: linear-gradient(135deg, #1565c0 0%, #0d47a1 50%, #1a237e 100%);">

<div class="bg-white rounded-xl p-10 w-full max-w-md shadow-2xl">
  <div class="text-center mb-6">
    <div class="w-[70px] h-[70px] bg-[#1565c0] rounded-full flex items-center justify-center mx-auto mb-4">
      <i class="fas fa-file-alt text-2xl text-white"></i>
    </div>
    <h4 class="text-[#1565c0] font-bold text-lg m-0">ระบบนำส่งเอกสารอิเล็กทรอนิกส์</h4>
    <p class="text-slate-500 text-sm m-0">สำนักงานตรวจบัญชีสหกรณ์ที่ 5</p>
  </div>

  <?php $flash = Session::getFlash(); if ($flash):
    $colorMap = array('error'=>'bg-red-50 text-red-700 border-red-200', 'success'=>'bg-green-50 text-green-700 border-green-200', 'warning'=>'bg-amber-50 text-amber-700 border-amber-200', 'info'=>'bg-sky-50 text-sky-700 border-sky-200');
    $flashCls = isset($colorMap[$flash['type']]) ? $colorMap[$flash['type']] : $colorMap['info'];
  ?>
  <div class="border rounded-md px-3 py-2 text-sm mb-4 <?php echo $flashCls; ?>">
    <?php echo e($flash['message']); ?>
  </div>
  <?php endif; ?>

  <form method="POST" action="?page=login&action=login">
    <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
    <div class="mb-4">
      <label class="block font-semibold text-sm text-slate-700 mb-1.5">ชื่อผู้ใช้</label>
      <div class="flex rounded-md border border-slate-300 overflow-hidden focus-within:ring-2 focus-within:ring-blue-200 focus-within:border-[#1565c0]">
        <span class="bg-slate-50 px-3 flex items-center border-r border-slate-300"><i class="fas fa-user text-slate-400"></i></span>
        <input type="text" name="username" class="flex-1 px-3 py-2 outline-none text-sm"
               placeholder="กรอกชื่อผู้ใช้" autofocus required
               value="<?php echo isset($_POST['username']) ? e($_POST['username']) : ''; ?>">
      </div>
    </div>
    <div class="mb-6">
      <label class="block font-semibold text-sm text-slate-700 mb-1.5">รหัสผ่าน</label>
      <div class="flex rounded-md border border-slate-300 overflow-hidden focus-within:ring-2 focus-within:ring-blue-200 focus-within:border-[#1565c0]">
        <span class="bg-slate-50 px-3 flex items-center border-r border-slate-300"><i class="fas fa-lock text-slate-400"></i></span>
        <input type="password" name="password" class="flex-1 px-3 py-2 outline-none text-sm" placeholder="กรอกรหัสผ่าน" required>
      </div>
    </div>
    <button type="submit" class="w-full bg-[#1565c0] hover:bg-[#0d47a1] text-white font-semibold py-2.5 rounded-md text-sm transition-colors">
      <i class="fas fa-sign-in-alt mr-2"></i>เข้าสู่ระบบ
    </button>
  </form>

  <p class="text-center text-slate-400 text-xs mt-4 mb-0">
    <i class="fas fa-shield-alt mr-1"></i>ระบบรักษาความปลอดภัยด้วย Session
  </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</body>
</html>
