<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="csrf-token" content="<?php echo Session::getCsrf(); ?>">
  <title><?php echo isset($pageTitle) ? e($pageTitle) . ' — ' : ''; ?>ระบบนำส่งเอกสาร สตท.5</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

  <!-- Tailwind CSS (no Bootstrap anywhere in this project) -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <style type="text/tailwindcss">
    @theme {
      --font-sans: 'Sarabun', sans-serif;
    }
  </style>

  <style>
    html, body { height: 100%; }
    body { display: flex; flex-direction: column; min-height: 100vh; font-family: 'Sarabun', sans-serif; background-color: #f0f2f5; font-size: 0.92rem; }
    code.tag { font-size: 0.8rem; background: #f0f0f0; padding: 1px 6px; border-radius: 4px; color: #c62828; }
    ::-webkit-scrollbar { height: 8px; width: 8px; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
  </style>

  <?php
  // -----------------------------------------------------------------
  // หมายเหตุ: เดิม <script src="app.js"> ใช้ attribute "defer" ซึ่งทำให้
  // เบราว์เซอร์รันไฟล์นี้ "หลังจาก parse HTML ทั้งหน้าเสร็จ" เท่านั้น
  // แต่ $extraJs (ที่แต่ละหน้าเรียกใช้ฟังก์ชันจาก app.js เช่น setupDateInput,
  // setupPositionSelector) ถูกพิมพ์เป็น <script> ธรรมดาไว้ท้ายสุดของ
  // views/layout/footer.php ซึ่งรันทันทีตอน parse มาถึง (เร็วกว่า defer script)
  // ผลคือฟังก์ชันใน app.js ยังไม่ถูกประกาศตอนที่ extraJs เรียกใช้
  // ทำให้เกิด "ReferenceError: setupDateInput is not defined" แบบเงียบ ๆ
  // และช่อง preview วันที่ (registerDatePreview, fiscalPreview,
  // submittedDatePreview) รวมถึง event listener อื่น ๆ ใน extraJs ของ
  // หน้านั้นไม่ทำงานเลย
  //
  // แก้โดยตัด defer ออก: app.js มีแต่การประกาศฟังก์ชัน + ผูก event บน
  // document เท่านั้น ไม่ได้อ่าน DOM element ทันทีที่โหลด จึงปลอดภัยที่จะ
  // รันก่อน body ถูก parse เสร็จ พอ extraJs รันตอนท้าย body ฟังก์ชันก็พร้อมใช้แล้ว
  // -----------------------------------------------------------------
  ?>
  <script src="<?php echo APP_URL; ?>/app.js"></script>
</head>
<body class="text-slate-800">
