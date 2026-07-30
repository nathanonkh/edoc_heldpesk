<?php
// ใช้ตัวแปร $unreadCount และ $recentNotifs จาก navbar.php
?>
<div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom bg-light">
  <span class="fw-semibold small">การแจ้งเตือน
    <?php if ($unreadCount > 0): ?>
    <span class="badge bg-danger ms-1"><?php echo $unreadCount; ?></span>
    <?php endif; ?>
  </span>
  <a href="?page=notifications&action=mark_all_read" class="small text-primary text-decoration-none">อ่านทั้งหมด</a>
</div>

<?php if (empty($recentNotifs)): ?>
<div class="text-center py-4 text-muted small">
  <i class="fas fa-bell-slash d-block fs-3 mb-2 text-secondary"></i>ไม่มีการแจ้งเตือน
</div>
<?php else: ?>
<?php foreach ($recentNotifs as $notif):
  $iconMap = array(
    'revision'        => 'fas fa-undo text-danger',
    'completed'       => 'fas fa-check-circle text-success',
    'resubmitted'     => 'fas fa-redo text-warning',
    'info'            => 'fas fa-info-circle text-info',
    'status_changed'  => 'fas fa-bell text-primary',
    'issue_status'    => 'fas fa-exclamation-circle text-danger',
    'issue_escalated' => 'fas fa-paper-plane text-purple',
    'issue_completed' => 'fas fa-check-circle text-success',
  );
  $ic = isset($iconMap[$notif['type']]) ? $iconMap[$notif['type']] : 'fas fa-bell text-primary';
?>
<a href="?page=notifications&action=read&id=<?php echo $notif['id']; ?>&target_id=<?php echo $notif['target_id']; ?>&target_type=<?php echo $notif['target_type']; ?>"
   class="notif-item <?php echo !$notif['is_read'] ? 'notif-item-unread' : ''; ?>">
  <div class="flex-shrink-0 mt-1" style="width:20px;text-align:center;">
    <i class="<?php echo $ic; ?>"></i>
  </div>
  <div class="flex-grow-1" style="min-width:0;">
    <div class="fw-semibold text-truncate" style="font-size:0.82rem;"><?php echo e($notif['title']); ?></div>
    <div class="text-muted text-truncate" style="font-size:0.78rem;"><?php echo e($notif['message']); ?></div>
    <div class="text-secondary" style="font-size:0.72rem;"><?php echo timeAgo($notif['created_at']); ?></div>
  </div>
  <?php if (!$notif['is_read']): ?>
  <div class="notif-dot flex-shrink-0"></div>
  <?php endif; ?>
</a>
<?php endforeach; ?>
<?php endif; ?>

<div class="text-center py-2 border-top bg-light">
  <a href="?page=notifications" class="small text-primary text-decoration-none">ดูทั้งหมด</a>
</div>
