<div class="breadcrumb-bar">
  <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="?page=dashboard"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">การแจ้งเตือน</li>
  </ol></nav>
</div>

<main class="content-area">

  <div class="page-banner mb-3">
    <div class="page-banner-icon bg-info"><i class="fas fa-bell"></i></div>
    <div class="flex-grow-1">
      <div class="page-banner-title">การแจ้งเตือน</div>
      <div class="page-banner-sub">ทั้งหมด <?php echo $totalItems; ?> รายการ</div>
    </div>
    <a href="?page=notifications&action=mark_all_read" class="btn btn-outline-light btn-sm flex-shrink-0">
      <i class="fas fa-check-double me-1"></i>อ่านทั้งหมด
    </a>
  </div>

  <?php if (empty($notifications) && $currentPageNum === 1): ?>
  <div class="page-card">
    <div class="page-card-body text-center py-5 text-muted">
      <i class="fas fa-bell-slash d-block fs-1 mb-3 text-secondary"></i>ไม่มีการแจ้งเตือน
    </div>
  </div>
  <?php else: ?>

  <div class="page-card">
    <div class="page-card-header">
      <span><i class="fas fa-list me-2 text-info"></i>รายการแจ้งเตือน
        <span class="badge bg-secondary ms-1"><?php echo $totalItems; ?></span>
      </span>
    </div>
    <div class="page-card-body p-0">
      <?php
      $iconMap = array(
        'revision'        => array('i'=>'fas fa-undo',              'c'=>'text-danger'),
        'completed'       => array('i'=>'fas fa-check-circle',      'c'=>'text-success'),
        'resubmitted'     => array('i'=>'fas fa-redo',              'c'=>'text-warning'),
        'info'            => array('i'=>'fas fa-info-circle',       'c'=>'text-info'),
        'status_changed'  => array('i'=>'fas fa-bell',              'c'=>'text-primary'),
        'issue_status'    => array('i'=>'fas fa-exclamation-circle','c'=>'text-danger'),
        'issue_escalated' => array('i'=>'fas fa-paper-plane',       'c'=>'text-purple'),
        'issue_completed' => array('i'=>'fas fa-check-circle',      'c'=>'text-success'),
      );
      $startNo = ($currentPageNum - 1) * 10 + 1;
      foreach ($notifications as $idx => $n):
        $ic = isset($iconMap[$n['type']]) ? $iconMap[$n['type']] : array('i'=>'fas fa-bell','c'=>'text-primary');
      ?>
      <a href="?page=notifications&action=read&id=<?php echo $n['id']; ?>&target_id=<?php echo $n['target_id']; ?>&target_type=<?php echo $n['target_type']; ?>"
         class="d-flex align-items-start gap-3 px-3 py-2 border-bottom text-decoration-none text-dark
                <?php echo !$n['is_read'] ? 'notif-item-unread' : ''; ?>"
         style="transition:background .12s;">
        <div class="flex-shrink-0" style="width:28px;text-align:center;padding-top:2px;">
          <span class="text-muted small"><?php echo $startNo + $idx; ?></span>
        </div>
        <div class="flex-shrink-0 fs-5 mt-1" style="width:22px;text-align:center;">
          <i class="<?php echo $ic['i']; ?> <?php echo $ic['c']; ?>"></i>
        </div>
        <div class="flex-grow-1" style="min-width:0;">
          <div class="fw-semibold"><?php echo e($n['title']); ?></div>
          <div class="text-muted small text-truncate"><?php echo e($n['message']); ?></div>
          <div class="text-secondary mt-1" style="font-size:0.78rem;">
            <i class="fas fa-tag me-1"></i><?php echo e($n['ticket_code']); ?>
            &nbsp;|&nbsp;<i class="fas fa-clock me-1"></i><?php echo thaiDate($n['created_at']); ?>
          </div>
        </div>
        <?php if (!$n['is_read']): ?>
        <div class="notif-dot flex-shrink-0 mt-2"></div>
        <?php endif; ?>
      </a>
      <?php endforeach; ?>
      <?php if (empty($notifications)): ?>
      <div class="text-center py-4 text-muted">
        <i class="fas fa-inbox d-block fs-2 mb-2 text-secondary"></i>ไม่พบรายการในหน้านี้
      </div>
      <?php endif; ?>
    </div>
  </div>

  <?php
  $paginationParams = array('page' => 'notifications');
  include 'views/layout/pagination.php';
  ?>

  <?php endif; ?>
</main>
