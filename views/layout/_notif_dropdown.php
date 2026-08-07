<?php
// ใช้ตัวแปร $unreadCount และ $recentNotifs จาก navbar.php
?>
<div class="flex items-center justify-between px-3 py-2 border-b border-slate-200 bg-slate-50 rounded-t-md">
  <span class="font-semibold text-sm text-slate-700">การแจ้งเตือน
    <?php if ($unreadCount > 0): ?>
    <span class="bg-red-600 text-white text-xs rounded-full px-1.5 py-0.5 ml-1"><?php echo $unreadCount; ?></span>
    <?php endif; ?>
  </span>
  <a href="?page=notifications&action=mark_all_read" class="text-xs text-blue-600 hover:underline">อ่านทั้งหมด</a>
</div>

<?php if (empty($recentNotifs)): ?>
<div class="text-center py-8 text-slate-400 text-sm">
  <i class="fas fa-bell-slash block text-2xl mb-2 text-slate-300"></i>ไม่มีการแจ้งเตือน
</div>
<?php else: ?>
<?php foreach ($recentNotifs as $notif):
  $iconMap = array(
    'revision'        => 'fas fa-undo text-red-600',
    'completed'       => 'fas fa-check-circle text-green-600',
    'resubmitted'     => 'fas fa-redo text-amber-600',
    'info'            => 'fas fa-info-circle text-sky-600',
    'status_changed'  => 'fas fa-bell text-blue-600',
    'issue_status'    => 'fas fa-exclamation-circle text-red-600',
    'issue_escalated' => 'fas fa-paper-plane text-purple-600',
    'issue_completed' => 'fas fa-check-circle text-green-600',
  );
  $ic = isset($iconMap[$notif['type']]) ? $iconMap[$notif['type']] : 'fas fa-bell text-blue-600';
?>
<a href="?page=notifications&action=read&id=<?php echo $notif['id']; ?>&target_id=<?php echo $notif['target_id']; ?>&target_type=<?php echo $notif['target_type']; ?>"
   class="flex gap-2.5 items-start px-3.5 py-2.5 border-b border-slate-100 text-sm text-slate-700 hover:bg-slate-50 <?php echo !$notif['is_read'] ? 'bg-blue-50 hover:bg-blue-100' : ''; ?>">
  <div class="flex-shrink-0 mt-0.5 w-5 text-center">
    <i class="<?php echo $ic; ?>"></i>
  </div>
  <div class="flex-1 min-w-0">
    <div class="font-semibold text-[0.82rem] truncate"><?php echo e($notif['title']); ?></div>
    <div class="text-slate-500 text-xs truncate"><?php echo e($notif['message']); ?></div>
    <div class="text-slate-400 text-[0.72rem]"><?php echo timeAgo($notif['created_at']); ?></div>
  </div>
  <?php if (!$notif['is_read']): ?>
  <div class="w-2 h-2 rounded-full bg-blue-600 flex-shrink-0 mt-1.5"></div>
  <?php endif; ?>
</a>
<?php endforeach; ?>
<?php endif; ?>

<div class="text-center py-2 border-t border-slate-200 bg-slate-50 rounded-b-md">
  <a href="?page=notifications" class="text-blue-600 text-xs hover:underline">ดูทั้งหมด</a>
</div>
