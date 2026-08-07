<div class="bg-white border-b border-slate-200 px-4 py-1.5 text-sm">
  <nav aria-label="breadcrumb">
    <ol class="flex items-center gap-1.5 text-slate-500">
      <li><a class="hover:text-[#1565c0]" href="?page=dashboard"><i class="fas fa-home"></i></a></li>
      <li class="text-slate-300">/</li>
      <li class="text-slate-700 font-medium">การแจ้งเตือน</li>
    </ol>
  </nav>
</div>

<main class="p-3 md:p-5 pb-6 md:pb-8 max-w-full overflow-x-hidden">

  <div class="rounded-lg border border-blue-200 px-4 md:px-5 py-3.5 flex items-center gap-3.5 flex-wrap mb-4"
       style="background: linear-gradient(135deg,#e3f2fd 0%,#f8f9ff 100%);">
    <div class="w-11 h-11 rounded-[10px] bg-sky-600 text-white flex items-center justify-center text-xl flex-shrink-0">
      <i class="fas fa-bell"></i>
    </div>
    <div class="flex-1">
      <div class="text-base font-bold text-[#1a237e]">การแจ้งเตือน</div>
      <div class="text-sm text-slate-600">ทั้งหมด <?php echo $totalItems; ?> รายการ</div>
    </div>
    <a href="?page=notifications&action=mark_all_read" class="border border-white text-white bg-white/10 hover:bg-white/20 text-sm px-3 py-1.5 rounded-md flex-shrink-0">
      <i class="fas fa-check-double mr-1"></i>อ่านทั้งหมด
    </a>
  </div>

  <?php if (empty($notifications) && $currentPageNum === 1): ?>
  <div class="bg-white border border-slate-200 rounded-md">
    <div class="p-3.5 text-center py-16 text-slate-400">
      <i class="fas fa-bell-slash block text-5xl mb-3 text-slate-300"></i>ไม่มีการแจ้งเตือน
    </div>
  </div>
  <?php else: ?>

  <div class="bg-white border border-slate-200 rounded-md">
    <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm">
      <i class="fas fa-list mr-2 text-sky-600"></i>รายการแจ้งเตือน
      <span class="bg-slate-500 text-white text-xs rounded px-1.5 py-0.5 ml-1"><?php echo $totalItems; ?></span>
    </div>
    <div class="p-0">
      <?php
      $iconMap = array(
        'revision'        => array('i'=>'fas fa-undo',              'c'=>'text-red-600'),
        'completed'       => array('i'=>'fas fa-check-circle',      'c'=>'text-green-600'),
        'resubmitted'     => array('i'=>'fas fa-redo',              'c'=>'text-amber-500'),
        'info'            => array('i'=>'fas fa-info-circle',       'c'=>'text-sky-600'),
        'status_changed'  => array('i'=>'fas fa-bell',              'c'=>'text-blue-600'),
        'issue_status'    => array('i'=>'fas fa-exclamation-circle','c'=>'text-red-600'),
        'issue_escalated' => array('i'=>'fas fa-paper-plane',       'c'=>'text-purple-600'),
        'issue_completed' => array('i'=>'fas fa-check-circle',      'c'=>'text-green-600'),
      );
      $startNo = ($currentPageNum - 1) * 10 + 1;
      foreach ($notifications as $idx => $n):
        $ic = isset($iconMap[$n['type']]) ? $iconMap[$n['type']] : array('i'=>'fas fa-bell','c'=>'text-blue-600');
      ?>
      <a href="?page=notifications&action=read&id=<?php echo $n['id']; ?>&target_id=<?php echo $n['target_id']; ?>&target_type=<?php echo $n['target_type']; ?>"
         class="flex items-start gap-3 px-3.5 py-2.5 border-b border-slate-100 last:border-b-0 no-underline text-slate-800 hover:bg-slate-50
                <?php echo !$n['is_read'] ? 'bg-blue-50 hover:bg-blue-100' : ''; ?>">
        <div class="flex-shrink-0 w-7 text-center pt-0.5">
          <span class="text-slate-400 text-sm"><?php echo $startNo + $idx; ?></span>
        </div>
        <div class="flex-shrink-0 text-lg mt-1 w-[22px] text-center">
          <i class="<?php echo $ic['i']; ?> <?php echo $ic['c']; ?>"></i>
        </div>
        <div class="flex-1 min-w-0">
          <div class="font-semibold"><?php echo e($n['title']); ?></div>
          <div class="text-slate-500 text-sm truncate"><?php echo e($n['message']); ?></div>
          <div class="text-slate-400 mt-1 text-xs">
            <i class="fas fa-tag mr-1"></i><?php echo e($n['ticket_code']); ?>
            &nbsp;|&nbsp;<i class="fas fa-clock mr-1"></i><?php echo thaiDate($n['created_at']); ?>
          </div>
        </div>
        <?php if (!$n['is_read']): ?>
        <div class="w-2 h-2 rounded-full bg-blue-600 flex-shrink-0 mt-2"></div>
        <?php endif; ?>
      </a>
      <?php endforeach; ?>
      <?php if (empty($notifications)): ?>
      <div class="text-center py-8 text-slate-400">
        <i class="fas fa-inbox block text-3xl mb-2 text-slate-300"></i>ไม่พบรายการในหน้านี้
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
