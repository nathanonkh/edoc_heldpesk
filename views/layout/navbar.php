<?php
global $db;
$unreadCount  = Notification::countUnread($db, $_SESSION['user_id']);
$recentNotifs = Notification::getRecent($db, $_SESSION['user_id'], 10);
$currentPage  = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// small local helper: nav link classes based on active state
function navLinkClass($active) {
    return $active
        ? 'block px-3 py-2 rounded text-sm font-medium text-white bg-white/15'
        : 'block px-3 py-2 rounded text-sm font-medium text-white/90 hover:bg-white/15 hover:text-white';
}
?>
<nav class="bg-[#1565c0]">
  <div class="max-w-full px-2 md:px-3">
    <div class="flex items-center h-[52px]">

      <a class="flex items-center gap-1 font-bold text-white text-base py-2 mr-3 flex-shrink-0" href="?page=dashboard">
        <i class="fas fa-file-alt text-red-200"></i> eDms
      </a>

      <!-- Mobile: bell + hamburger -->
      <div class="flex items-center gap-1 md:hidden ml-auto">
        <div class="relative">
          <a class="px-2 text-white relative block" href="#" data-dropdown-toggle="notifDropdownMobile">
            <i class="fas fa-bell"></i>
            <?php if ($unreadCount > 0): ?>
            <span class="notif-badge absolute -top-0.5 -right-1.5 bg-red-600 text-white text-[10px] leading-4 min-w-[16px] h-4 rounded-full px-1 text-center"><?php echo $unreadCount > 99 ? '99+' : $unreadCount; ?></span>
            <?php endif; ?>
          </a>
          <div id="notifDropdownMobile" data-dropdown class="hidden absolute right-0 mt-2 w-72 max-h-96 overflow-y-auto bg-white rounded-md border border-slate-200 shadow-lg z-50">
            <?php include 'views/layout/_notif_dropdown.php'; ?>
          </div>
        </div>
        <button class="text-white p-1" data-collapse-toggle="mainNavbar">
          <i class="fas fa-bars text-lg"></i>
        </button>
      </div>

      <!-- Desktop nav -->
      <ul class="hidden md:flex items-center gap-1 ml-2">
        <li>
          <a class="<?php echo navLinkClass($currentPage==='dashboard'); ?>" href="?page=dashboard">หน้าหลัก</a>
        </li>
        <li class="relative">
          <button class="<?php echo navLinkClass(in_array($currentPage, array('documents','reports'))); ?> flex items-center gap-1" data-dropdown-toggle="menuDocs">
            เอกสาร <i class="fas fa-chevron-down text-xs"></i>
          </button>
          <div id="menuDocs" data-dropdown class="hidden absolute left-0 mt-1 w-56 bg-white rounded-md border border-slate-200 shadow-lg py-1 z-50">
            <?php if (Auth::hasAnyRole(array('submitter','admin'))): ?>
            <a class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-blue-50" href="?page=documents&action=create">
              <i class="fas fa-plus w-4 text-green-600"></i>นำส่งเอกสาร</a>
            <?php endif; ?>
            <a class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-blue-50" href="?page=documents">
              <i class="fas fa-list w-4 text-blue-600"></i>รายการเอกสาร</a>
            <a class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-blue-50" href="?page=reports">
              <i class="fas fa-chart-bar w-4 text-sky-600"></i>รายงานเอกสาร</a>
          </div>
        </li>
        <li class="relative">
          <button class="<?php echo navLinkClass(in_array($currentPage, array('issues','issue_reports'))); ?> flex items-center gap-1" data-dropdown-toggle="menuIssues">
            แจ้งปัญหา <i class="fas fa-chevron-down text-xs"></i>
          </button>
          <div id="menuIssues" data-dropdown class="hidden absolute left-0 mt-1 w-56 bg-white rounded-md border border-slate-200 shadow-lg py-1 z-50">
            <a class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-blue-50" href="?page=issues&action=create">
              <i class="fas fa-plus w-4 text-red-600"></i>แจ้งปัญหาใหม่</a>
            <a class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-blue-50" href="?page=issues">
              <i class="fas fa-list w-4 text-blue-600"></i>รายการแจ้งปัญหา</a>
            <a class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-blue-50" href="?page=issue_reports">
              <i class="fas fa-chart-bar w-4 text-sky-600"></i>รายงานแจ้งปัญหา</a>
          </div>
        </li>
        <?php if (Auth::hasRole('admin')): ?>
        <li class="relative">
          <button class="<?php echo navLinkClass(in_array($currentPage, array('users','cooperatives','announcements'))); ?> flex items-center gap-1" data-dropdown-toggle="menuAdmin">
            จัดการ <i class="fas fa-chevron-down text-xs"></i>
          </button>
          <div id="menuAdmin" data-dropdown class="hidden absolute left-0 mt-1 w-56 bg-white rounded-md border border-slate-200 shadow-lg py-1 z-50">
            <a class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-blue-50" href="?page=users">
              <i class="fas fa-users w-4 text-blue-600"></i>จัดการสมาชิก</a>
            <a class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-blue-50" href="?page=cooperatives">
              <i class="fas fa-building w-4 text-amber-600"></i>จัดการสหกรณ์</a>
            <a class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-blue-50" href="?page=announcements">
              <i class="fas fa-bullhorn w-4 text-sky-600"></i>จัดการหน้าหลัก</a>
          </div>
        </li>
        <?php endif; ?>
      </ul>

      <!-- Desktop: bell + user -->
      <div class="hidden md:flex items-center gap-1 ml-auto">
        <div class="relative">
          <button class="px-2 text-white relative" data-dropdown-toggle="notifDropdownDesktop">
            <i class="fas fa-bell"></i>
            <?php if ($unreadCount > 0): ?>
            <span class="notif-badge absolute -top-0.5 -right-1.5 bg-red-600 text-white text-[10px] leading-4 min-w-[16px] h-4 rounded-full px-1 text-center"><?php echo $unreadCount > 99 ? '99+' : $unreadCount; ?></span>
            <?php endif; ?>
          </button>
          <div id="notifDropdownDesktop" data-dropdown class="hidden absolute right-0 mt-2 w-80 max-h-96 overflow-y-auto bg-white rounded-md border border-slate-200 shadow-lg z-50">
            <?php include 'views/layout/_notif_dropdown.php'; ?>
          </div>
        </div>
        <div class="relative">
          <button class="flex items-center gap-2 px-2 py-1 text-white" data-dropdown-toggle="userMenu">
            <span class="w-7 h-7 rounded-full bg-white text-[#1565c0] font-bold text-sm flex items-center justify-center">
              <?php echo mb_substr($_SESSION['firstname'], 0, 1, 'UTF-8'); ?>
            </span>
            <span class="text-sm hidden xl:inline"><?php echo e(trim($_SESSION['prefix'].' '.$_SESSION['firstname'].' '.$_SESSION['lastname'])); ?></span>
            <i class="fas fa-chevron-down text-xs"></i>
          </button>
          <div id="userMenu" data-dropdown class="hidden absolute right-0 mt-1 w-56 bg-white rounded-md border border-slate-200 shadow-lg py-1 z-50">
            <a class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-blue-50" href="?page=users&action=profile">
              <i class="fas fa-user w-4"></i>โปรไฟล์ของฉัน</a>
            <hr class="my-1 border-slate-200">
            <a class="flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50" href="?action=logout">
              <i class="fas fa-sign-out-alt w-4"></i>ออกจากระบบ</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Mobile collapse panel -->
    <div id="mainNavbar" class="hidden md:hidden bg-[#1565c0] border-t border-white/15 pb-3">
      <div class="border-b border-white/20 pb-2 mb-2 px-1 pt-2">
        <div class="flex items-center gap-2">
          <span class="w-9 h-9 rounded-full bg-white text-[#1565c0] font-bold flex items-center justify-center flex-shrink-0">
            <?php echo mb_substr($_SESSION['firstname'], 0, 1, 'UTF-8'); ?>
          </span>
          <div class="min-w-0">
            <div class="text-white font-semibold text-sm truncate"><?php echo e(trim($_SESSION['prefix'].' '.$_SESSION['firstname'].' '.$_SESSION['lastname'])); ?></div>
            <div class="text-white/70 text-xs"><?php echo e($_SESSION['office_name']); ?></div>
          </div>
        </div>
      </div>

      <a class="block px-4 py-2 text-sm text-white/90 <?php echo $currentPage==='dashboard' ? 'bg-white/15 text-white' : ''; ?>" href="?page=dashboard">
        <i class="fas fa-home w-4 mr-1"></i>หน้าหลัก
      </a>

      <div class="px-1">
        <div class="text-white/60 text-xs uppercase font-semibold px-3 pt-2 pb-1">เอกสาร</div>
        <?php if (Auth::hasAnyRole(array('submitter','admin'))): ?>
        <a class="block px-4 py-2 text-sm text-white/90 rounded hover:bg-white/15" href="?page=documents&action=create"><i class="fas fa-plus w-4 mr-1"></i>นำส่งเอกสาร</a>
        <?php endif; ?>
        <a class="block px-4 py-2 text-sm text-white/90 rounded hover:bg-white/15" href="?page=documents"><i class="fas fa-list w-4 mr-1"></i>รายการเอกสาร</a>
        <a class="block px-4 py-2 text-sm text-white/90 rounded hover:bg-white/15" href="?page=reports"><i class="fas fa-chart-bar w-4 mr-1"></i>รายงานเอกสาร</a>

        <div class="text-white/60 text-xs uppercase font-semibold px-3 pt-2 pb-1">แจ้งปัญหา</div>
        <a class="block px-4 py-2 text-sm text-white/90 rounded hover:bg-white/15" href="?page=issues&action=create"><i class="fas fa-plus w-4 mr-1"></i>แจ้งปัญหาใหม่</a>
        <a class="block px-4 py-2 text-sm text-white/90 rounded hover:bg-white/15" href="?page=issues"><i class="fas fa-list w-4 mr-1"></i>รายการแจ้งปัญหา</a>
        <a class="block px-4 py-2 text-sm text-white/90 rounded hover:bg-white/15" href="?page=issue_reports"><i class="fas fa-chart-bar w-4 mr-1"></i>รายงานแจ้งปัญหา</a>

        <?php if (Auth::hasRole('admin')): ?>
        <div class="text-white/60 text-xs uppercase font-semibold px-3 pt-2 pb-1">จัดการ</div>
        <a class="block px-4 py-2 text-sm text-white/90 rounded hover:bg-white/15" href="?page=users"><i class="fas fa-users w-4 mr-1"></i>จัดการสมาชิก</a>
        <a class="block px-4 py-2 text-sm text-white/90 rounded hover:bg-white/15" href="?page=cooperatives"><i class="fas fa-building w-4 mr-1"></i>จัดการสหกรณ์</a>
        <a class="block px-4 py-2 text-sm text-white/90 rounded hover:bg-white/15" href="?page=announcements"><i class="fas fa-bullhorn w-4 mr-1"></i>จัดการหน้าหลัก</a>
        <?php endif; ?>
      </div>

      <div class="border-t border-white/20 pt-2 mt-2 px-1">
        <a class="block px-4 py-2 text-sm text-white/90 rounded hover:bg-white/15" href="?page=users&action=profile"><i class="fas fa-user w-4 mr-1"></i>โปรไฟล์ของฉัน</a>
        <a class="block px-4 py-2 text-sm text-white/90 rounded hover:bg-white/15" href="?action=logout"><i class="fas fa-sign-out-alt w-4 mr-1"></i>ออกจากระบบ</a>
      </div>
    </div>
  </div>
</nav>

<div class="flex flex-1 min-w-0">
<div class="flex-1 min-w-0 overflow-x-hidden">
