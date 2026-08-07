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

  <script src="<?php echo APP_URL; ?>/app.js" defer></script>
</head>
<body class="text-slate-800">
