<?php
global $db;
$unreadCount  = Notification::countUnread($db, $_SESSION['user_id']);
$recentNotifs = Notification::getRecent($db, $_SESSION['user_id'], 10);
$currentPage  = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>
<nav class="navbar navbar-expand-lg navbar-edms py-0">
  <div class="container-fluid px-2 px-md-3">

    <a class="navbar-brand fw-bold py-2 me-3" href="?page=dashboard">
      <i class="fas fa-file-alt me-1" style="color:#ef9a9a;"></i> eDms
    </a>

    <!-- Mobile: bell + toggler -->
    <div class="d-flex align-items-center gap-1 d-lg-none ms-auto">
      <div class="dropdown">
        <a class="nav-link px-2 text-white position-relative" href="#" data-bs-toggle="dropdown">
          <i class="fas fa-bell"></i>
          <?php if ($unreadCount > 0): ?>
          <span class="badge bg-danger notif-badge"><?php echo $unreadCount > 99 ? '99+' : $unreadCount; ?></span>
          <?php endif; ?>
        </a>
        <div class="dropdown-menu dropdown-menu-end p-0 shadow-lg" style="width:280px;max-height:360px;overflow-y:auto;">
          <?php include 'views/layout/_notif_dropdown.php'; ?>
        </div>
      </div>
      <button class="navbar-toggler border-0 text-white p-1" type="button"
              data-bs-toggle="collapse" data-bs-target="#mainNavbar">
        <i class="fas fa-bars fa-lg"></i>
      </button>
    </div>

    <div class="collapse navbar-collapse" id="mainNavbar">
      <!-- Mobile user header -->
      <div class="d-lg-none border-bottom pb-2 mb-2 px-1">
        <div class="d-flex align-items-center gap-2">
          <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
               style="width:38px;height:38px;font-size:1rem;">
            <?php echo mb_substr($_SESSION['firstname'], 0, 1, 'UTF-8'); ?>
          </div>
          <div style="min-width:0;">
            <div class="text-white fw-semibold small text-truncate"><?php echo e(trim($_SESSION['prefix'].' '.$_SESSION['firstname'].' '.$_SESSION['lastname'])); ?></div>
            <div class="text-white-50" style="font-size:0.75rem;"><?php echo e($_SESSION['office_name']); ?></div>
          </div>
        </div>
      </div>

      <!-- Nav links -->
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link px-3 <?php echo $currentPage==='dashboard' ? 'active' : ''; ?>" href="?page=dashboard">
            <i class="fas fa-home fa-fw me-1 d-lg-none"></i>หน้าหลัก
          </a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle px-3 <?php echo in_array($currentPage, array('documents','reports')) ? 'active' : ''; ?>"
             href="#" data-bs-toggle="dropdown">
            <i class="fas fa-folder fa-fw me-1 d-lg-none"></i>เอกสาร
          </a>
          <ul class="dropdown-menu">
            <?php if (Auth::hasAnyRole(array('submitter','admin'))): ?>
            <li><a class="dropdown-item" href="?page=documents&action=create">
              <i class="fas fa-plus fa-fw me-2 text-success"></i>นำส่งเอกสาร</a></li>
            <?php endif; ?>
            <li><a class="dropdown-item" href="?page=documents">
              <i class="fas fa-list fa-fw me-2 text-primary"></i>รายการเอกสาร</a></li>
            <li><a class="dropdown-item" href="?page=reports">
              <i class="fas fa-chart-bar fa-fw me-2 text-info"></i>รายงานเอกสาร</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle px-3 <?php echo in_array($currentPage, array('issues','issue_reports')) ? 'active' : ''; ?>"
             href="#" data-bs-toggle="dropdown">
            <i class="fas fa-exclamation-circle fa-fw me-1 d-lg-none"></i>แจ้งปัญหา
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="?page=issues&action=create">
              <i class="fas fa-plus fa-fw me-2 text-danger"></i>แจ้งปัญหาใหม่</a></li>
            <li><a class="dropdown-item" href="?page=issues">
              <i class="fas fa-list fa-fw me-2 text-primary"></i>รายการแจ้งปัญหา</a></li>
            <li><a class="dropdown-item" href="?page=issue_reports">
              <i class="fas fa-chart-bar fa-fw me-2 text-info"></i>รายงานแจ้งปัญหา</a></li>
          </ul>
        </li>
        <?php if (Auth::hasRole('admin')): ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle px-3 <?php echo in_array($currentPage, array('users','cooperatives','announcements')) ? 'active' : ''; ?>"
             href="#" data-bs-toggle="dropdown">
            <i class="fas fa-users-cog fa-fw me-1 d-lg-none"></i>จัดการ
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="?page=users">
              <i class="fas fa-users fa-fw me-2 text-primary"></i>จัดการสมาชิก</a></li>
            <li><a class="dropdown-item" href="?page=cooperatives">
              <i class="fas fa-building fa-fw me-2 text-warning"></i>จัดการสหกรณ์</a></li>
            <li><a class="dropdown-item" href="?page=announcements">
              <i class="fas fa-bullhorn fa-fw me-2 text-info"></i>จัดการหน้าหลัก</a></li>
          </ul>
        </li>
        <?php endif; ?>
      </ul>

      <!-- Desktop: bell + user -->
      <ul class="navbar-nav ms-auto align-items-center d-none d-lg-flex">
        <li class="nav-item dropdown me-1">
          <a class="nav-link px-2 position-relative" href="#" data-bs-toggle="dropdown">
            <i class="fas fa-bell"></i>
            <?php if ($unreadCount > 0): ?>
            <span class="badge bg-danger notif-badge"><?php echo $unreadCount > 99 ? '99+' : $unreadCount; ?></span>
            <?php endif; ?>
          </a>
          <div class="dropdown-menu dropdown-menu-end p-0 notif-dropdown shadow-lg">
            <?php include 'views/layout/_notif_dropdown.php'; ?>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle px-2" href="#" data-bs-toggle="dropdown">
            <span class="badge rounded-circle bg-white text-primary me-1 fw-bold"
                  style="width:28px;height:28px;line-height:20px;font-size:0.85rem;display:inline-flex;align-items:center;justify-content:center;">
              <?php echo mb_substr($_SESSION['firstname'], 0, 1, 'UTF-8'); ?>
            </span>
            <span class="small d-none d-xl-inline"><?php echo e(trim($_SESSION['prefix'].' '.$_SESSION['firstname'].' '.$_SESSION['lastname'])); ?></span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="?page=users&action=profile">
              <i class="fas fa-user fa-fw me-2"></i>โปรไฟล์ของฉัน</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="?action=logout">
              <i class="fas fa-sign-out-alt fa-fw me-2"></i>ออกจากระบบ</a></li>
          </ul>
        </li>
      </ul>

      <!-- Mobile: profile + logout -->
      <div class="d-lg-none border-top pt-2 mt-1 px-1">
        <a class="nav-link text-white py-2" href="?page=users&action=profile">
          <i class="fas fa-user fa-fw me-2"></i>โปรไฟล์ของฉัน
        </a>
        <a class="nav-link text-white py-2" href="?action=logout">
          <i class="fas fa-sign-out-alt fa-fw me-2"></i>ออกจากระบบ
        </a>
      </div>
    </div>
  </div>
</nav>

<style>
@media (max-width: 991px) {
  #mainNavbar { background-color: #1565c0; border-top: 1px solid rgba(255,255,255,0.15); padding: 8px 4px 12px; max-height: calc(100vh - 52px); overflow-y: auto; }
  #mainNavbar .dropdown-menu { position: static !important; float: none; background-color: rgba(0,0,0,0.15); border: none; border-radius: 4px; margin: 2px 8px 4px; padding: 4px 0; box-shadow: none; }
  #mainNavbar .dropdown-menu .dropdown-item { color: rgba(255,255,255,0.9); padding: 7px 16px; font-size: 0.88rem; border-radius: 3px; }
  #mainNavbar .dropdown-menu .dropdown-item:hover { background-color: rgba(255,255,255,0.15); color: #fff; }
  #mainNavbar .nav-link.dropdown-toggle::after { float: right; margin-top: 6px; }
}
</style>

<div class="app-wrapper">
<div class="app-content">
