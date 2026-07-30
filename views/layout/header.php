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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

  <style>
    *, *::before, *::after { box-sizing: border-box; }
    html { overflow-x: hidden; }
    body { font-family: 'Sarabun', sans-serif; background-color: #f0f2f5; font-size: 0.92rem; overflow-x: hidden; }

    /* Navbar */
    .navbar-edms { background-color: #1565c0; padding: 0 8px; min-height: 52px; }
    .navbar-edms .navbar-brand { color: #fff !important; font-weight: 700; font-size: 1rem; padding: 8px 4px; }
    .navbar-edms .nav-link { color: rgba(255,255,255,0.9) !important; padding: 10px 12px; font-size: 0.88rem; border-radius: 4px; }
    .navbar-edms .nav-link:hover,
    .navbar-edms .nav-link.active { background-color: rgba(255,255,255,0.15); color: #fff !important; }
    .navbar-edms .dropdown-menu { background-color: #fff; border: 1px solid #dee2e6; border-radius: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.12); min-width: 200px; }
    .navbar-edms .dropdown-item { font-size: 0.88rem; padding: 7px 16px; }
    .navbar-edms .dropdown-item:hover { background-color: #e3f2fd; }
    .navbar-toggler { border: 0 !important; box-shadow: none !important; padding: 6px; }

    .notif-badge { position: absolute; top: -2px; right: -6px; font-size: 0.6rem; min-width: 16px; height: 16px; line-height: 16px; padding: 0 4px; border-radius: 8px; }
    .notif-dropdown { width: 300px; max-height: 380px; overflow-y: auto; }
    .notif-item { display: flex; gap: 10px; align-items: flex-start; padding: 10px 14px; border-bottom: 1px solid #f0f0f0; text-decoration: none; color: #333; font-size: 0.82rem; transition: background .12s; }
    .notif-item:hover { background-color: #f5f5f5; }
    .notif-item-unread { background-color: #e8f4fd; }
    .notif-item-unread:hover { background-color: #d4ecfb; }
    .notif-dot { width: 8px; height: 8px; border-radius: 50%; background: #1565c0; flex-shrink: 0; margin-top: 5px; }

    /* Breadcrumb */
    .breadcrumb-bar { background-color: #fff; border-bottom: 1px solid #e0e0e0; padding: 6px 16px; font-size: 0.82rem; }
    .breadcrumb-bar .breadcrumb { margin: 0; }

    /* Content */
    main.content-area { padding: 12px 12px 24px; max-width: 100%; overflow-x: hidden; }
    @media (min-width: 768px) { main.content-area { padding: 16px 20px 32px; } }

    /* Cards */
    .page-card { background: #fff; border: 1px solid #e0e0e0; border-radius: 6px; margin-bottom: 16px; overflow: hidden; }
    .page-card-header { background: #fafafa; border-bottom: 1px solid #e0e0e0; padding: 10px 14px; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; }
    .page-card-body { padding: 14px; }
    @media (max-width: 575px) { .page-card-body { padding: 10px; } }

    /* Stat cards */
    .stat-card { background: #fff; border: 1px solid #e0e0e0; border-radius: 6px; padding: 14px 10px; text-align: center; height: 100%; cursor: default; transition: transform .15s, box-shadow .15s; }
    a .stat-card { cursor: pointer; }
    a:hover .stat-card { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.12); }
    .stat-card .stat-icon { font-size: 1.6rem; margin-bottom: 4px; }
    .stat-card .stat-num  { font-size: 1.5rem; font-weight: 700; line-height: 1.1; }
    .stat-card .stat-lbl  { font-size: 0.78rem; color: #777; margin-top: 2px; }
    .stat-card-warning .stat-icon { color: #d97706; }
    .stat-card-primary .stat-icon { color: #1565c0; }
    .stat-card-info    .stat-icon { color: #0277bd; }
    .stat-card-purple  .stat-icon { color: #7b1fa2; }
    .stat-card-danger  .stat-icon { color: #c62828; }
    .stat-card-success .stat-icon { color: #2e7d32; }

    /* Tables */
    .table-edms-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .mobile-list { display: none; }
    @media (max-width: 767px) { .table-edms-wrap { display: none; } .mobile-list { display: block; } }
    @media (min-width: 768px) { .table-edms-wrap { display: block; } .mobile-list { display: none; } }
    .table-edms { font-size: 0.82rem; border-collapse: collapse; width: 100%; min-width: 640px; }
    .table-edms thead th { background: #e8edf3; color: #333; border: 1px solid #c8d0da; padding: 8px 10px; white-space: nowrap; font-weight: 600; }
    .table-edms tbody td { border: 1px solid #e0e0e0; padding: 7px 10px; vertical-align: middle; }
    .table-edms tbody tr:hover { background-color: #f5f8ff; }

    /* Buttons */
    .btn-view-file { background: #0277bd; color: #fff; font-size: 0.78rem; }
    .btn-view-file:hover { background: #01579b; color: #fff; }
    .btn-detail { background: #e65100; color: #fff; font-size: 0.78rem; }
    .btn-detail:hover { background: #bf360c; color: #fff; }
    .btn-edit-doc { background: #2e7d32; color: #fff; font-size: 0.78rem; }
    .btn-edit-doc:hover { background: #1b5e20; color: #fff; }

    /* Badges */
    .badge-purple { background-color: #7b1fa2; }
    .text-purple  { color: #7b1fa2; }
    .bg-purple    { background-color: #7b1fa2; }

    /* Mobile card list */
    .mobile-card { background: #fff; border: 1px solid #e0e0e0; border-radius: 6px; padding: 10px 12px; margin-bottom: 8px; }
    .mobile-card-title { font-weight: 600; font-size: 0.88rem; }
    .mobile-card-sub   { font-size: 0.78rem; color: #777; }

    /* Forms */
    .form-label { font-size: 0.83rem; font-weight: 600; margin-bottom: 3px; }
    .form-control, .form-select { font-size: 0.88rem; }
    @media (max-width: 575px) { .form-control, .form-select { font-size: 0.9rem; } }

    /* Timeline */
    .timeline { border-left: 2px solid #1565c0; padding-left: 16px; margin-left: 6px; }
    .timeline-item { position: relative; margin-bottom: 14px; }
    .timeline-item::before { content: ''; position: absolute; left: -21px; top: 5px; width: 10px; height: 10px; background: #1565c0; border-radius: 50%; border: 2px solid #fff; }
    .timeline-item .t-action { font-weight: 600; font-size: 0.85rem; }
    .timeline-item .t-meta   { font-size: 0.78rem; color: #888; }
    .timeline-item .t-note   { font-size: 0.82rem; color: #555; margin-top: 2px; }

    /* File cards */
    .file-card { border: 1px solid #e0e0e0; border-radius: 6px; padding: 10px 12px; display: flex; align-items: center; gap: 10px; background: #fafafa; }
    .file-card .file-label { font-size: 0.82rem; font-weight: 600; }
    .file-card .file-name  { font-size: 0.78rem; color: #666; word-break: break-all; }

    /* Action panel */
    .action-panel { position: sticky; top: 16px; }
    @media (max-width: 991px) { .action-panel { position: static; } }

    /* Misc */
    code { font-size: 0.8rem; background: #f0f0f0; padding: 1px 5px; border-radius: 3px; color: #c62828; }

    /* Help Sidebar */
    .help-sidebar { background: #fff; border-left: 1px solid #e0e0e0; display: flex; flex-direction: column; }
    .help-sidebar-inner { display: flex; flex-direction: column; height: 100%; }
    .help-sidebar-header { padding: 10px 14px; font-size: 0.83rem; font-weight: 600; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; position: sticky; top: 0; z-index: 1; }
    .help-sidebar-body { padding: 12px; overflow-y: auto; flex: 1; }
    .help-list { list-style: none; padding: 0; margin: 0; }
    .help-list-item { display: flex; gap: 8px; align-items: flex-start; padding: 6px 0; border-bottom: 1px solid #f5f5f5; font-size: 0.8rem; line-height: 1.4; color: #444; }
    .help-list-item:last-child { border-bottom: 0; }
    .help-list-icon { flex-shrink: 0; margin-top: 2px; width: 14px; text-align: center; font-size: 0.78rem; }
    .help-extra { background: #f8f9fa; border-radius: 6px; padding: 8px 10px; margin-top: 10px; }
    .help-extra-title { font-size: 0.75rem; font-weight: 700; color: #555; margin-bottom: 6px; text-transform: uppercase; letter-spacing: .4px; }
    .help-extra-row { display: flex; align-items: center; gap: 4px; padding: 3px 0; font-size: 0.78rem; flex-wrap: wrap; }
    .help-workflow { margin-top: 8px; }
    .workflow-steps { display: flex; align-items: center; flex-wrap: wrap; gap: 2px; margin-top: 6px; }
    .wf-step { display: flex; flex-direction: column; align-items: center; gap: 2px; }
    .wf-dot { width: 10px; height: 10px; border-radius: 50%; }
    .wf-label { font-size: 0.68rem; color: #777; white-space: nowrap; }
    .wf-arrow { color: #aaa; font-size: 0.9rem; line-height: 1; padding-bottom: 8px; }
    .wf-active .wf-dot { box-shadow: 0 0 0 3px rgba(0,0,0,0.15); transform: scale(1.3); }
    .wf-active .wf-label { color: #333; font-weight: 700; }
    .help-toggle-btn { position: fixed; bottom: 52px; right: 16px; width: 44px; height: 44px; border-radius: 50%; background: #1565c0; color: #fff; border: none; box-shadow: 0 3px 10px rgba(0,0,0,0.3); font-size: 1.1rem; z-index: 1055; display: none; align-items: center; justify-content: center; transition: transform 0.2s; }
    .help-toggle-btn:hover { transform: scale(1.08); }

    /* Footer */
    html, body { height: 100%; }
    body { display: flex; flex-direction: column; min-height: 100vh; }
    .app-wrapper { flex: 1; display: flex; }
    .app-content { flex: 1; min-width: 0; overflow-x: hidden; }
    .page-footer { background: #1565c0; color: rgba(255,255,255,0.85); font-size: 0.78rem; padding: 10px 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 4px; flex-shrink: 0; position: relative; z-index: 1; }
    .page-footer a { color: rgba(255,255,255,0.85); text-decoration: none; }
    .page-footer a:hover { color: #fff; text-decoration: underline; }
    @media (max-width: 575px) { .page-footer { justify-content: center; text-align: center; font-size: 0.75rem; padding: 8px 12px; } }

    /* Page Banner */
    .page-banner { background: linear-gradient(135deg,#e3f2fd 0%,#f8f9ff 100%); border: 1px solid #c5d8f0; border-radius: 8px; padding: 14px 18px; display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
    .page-banner-icon { width: 46px; height: 46px; border-radius: 10px; background: #1565c0; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0; }
    .page-banner-icon.bg-success { background: #2e7d32 !important; }
    .page-banner-icon.bg-info    { background: #0277bd !important; }
    .page-banner-icon.bg-warning { background: #e65100 !important; }
    .page-banner-icon.bg-danger  { background: #c62828 !important; }
    .page-banner-title { font-size: 1.05rem; font-weight: 700; color: #1a237e; margin: 0; line-height: 1.2; }
    .page-banner-sub   { font-size: 0.82rem; color: #666; margin: 0; }

    /* Mobile sidebar */
    @media (max-width: 991px) {
      .app-wrapper { display: block; }
      .help-sidebar { position: fixed; top: 52px; right: -260px; width: 260px; height: calc(100vh - 92px); z-index: 1040; transition: right 0.25s ease; box-shadow: -4px 0 20px rgba(0,0,0,0.18); overflow-y: auto; overflow-x: hidden; }
      .help-sidebar.open { right: 0; }
      .help-sidebar.open::before { content: ''; position: fixed; inset: 0; background: rgba(0,0,0,0.25); z-index: -1; }
      .help-toggle-btn { display: flex; }
    }
    @media (min-width: 992px) {
      .app-wrapper { display: flex; }
      .help-sidebar { width: 220px; flex-shrink: 0; position: static; height: auto; overflow-y: auto; }
      .help-toggle-btn { display: none !important; }
    }
    @media (min-width: 1200px) { .help-sidebar { width: 240px; } }
  </style>

  <script src="<?php echo APP_URL; ?>/app.js"></script>
</head>
<body>
