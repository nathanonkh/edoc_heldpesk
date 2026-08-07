<?php
$user = Auth::currentUser();
$st = array(
    'header' => issueStatusHeaderClass($issue['status']),
    'icon'   => issueStatusIcon($issue['status']),
);
// ผู้แจ้งเรื่องเองตัดสินใจได้ว่าจะดำเนินการเองหรือส่งต่อส่วนกลาง หรือเจ้าหน้าที่ประจำสำนักงานที่รับผิดชอบ
$canHandle = canHandleIssue($user, $issue);
$isCentralHandling = !empty($issue['handled_by_central']);
$canComplete = $isCentralHandling ? (userHasRole($user, 'admin') && isHQ($user)) : $canHandle;
?>

<div class="bg-white border-b border-slate-200 px-4 py-1.5 text-sm">
  <nav aria-label="breadcrumb">
    <ol class="flex items-center gap-1.5 text-slate-500">
      <li><a class="hover:text-[#1565c0]" href="?page=dashboard"><i class="fas fa-home"></i></a></li>
      <li class="text-slate-300">/</li>
      <li><a class="hover:text-[#1565c0]" href="?page=issues">แจ้งปัญหาการใช้งาน</a></li>
      <li class="text-slate-300">/</li>
      <li class="text-slate-700 font-medium"><?php echo e($issue['ticket_code']); ?></li>
    </ol>
  </nav>
</div>

<main class="p-3 md:p-5 pb-6 md:pb-8 max-w-full overflow-x-hidden">

  <div class="rounded-lg border border-blue-200 px-4 md:px-5 py-3.5 flex items-center gap-3.5 flex-wrap mb-4"
       style="background: linear-gradient(135deg,#e3f2fd 0%,#f8f9ff 100%);">
    <div class="w-11 h-11 rounded-[10px] <?php echo $st['header']; ?> text-white flex items-center justify-center text-xl flex-shrink-0">
      <i class="<?php echo $st['icon']; ?>"></i>
    </div>
    <div class="flex-1">
      <div class="text-base font-bold text-[#1a237e]"><?php echo e($issue['ticket_code']); ?></div>
      <div class="text-sm text-slate-600 flex items-center gap-2 flex-wrap">
        <?php echo e($issue['title']); ?>
        <?php echo uiBadge(issueStatusLabel($issue['status'], !empty($issue['handled_by_central'])), issueStatusBadgeClass($issue['status'])); ?>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-3">

    <!-- ซ้าย: ข้อมูล -->
    <div class="lg:col-span-8">

      <div class="bg-white border border-slate-200 rounded-md mb-3">
        <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-info-circle mr-2 text-[#1565c0]"></i>ข้อมูลการแจ้งปัญหา</div>
        <div class="p-3.5">
          <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
            <div class="p-2 rounded border border-slate-200 bg-slate-50">
              <div class="text-slate-500 text-xs mb-1"><i class="fas fa-hashtag mr-1"></i>เลขที่แจ้ง</div>
              <div class="font-bold"><code class="tag"><?php echo e($issue['ticket_code']); ?></code></div>
            </div>
            <div class="p-2 rounded border border-slate-200 bg-slate-50">
              <div class="text-slate-500 text-xs mb-1"><i class="fas fa-clock mr-1"></i>วันที่แจ้ง</div>
              <div class="font-semibold text-sm"><?php echo thaiDate($issue['created_at']); ?></div>
            </div>
            <div class="p-2 rounded border border-slate-200 bg-slate-50">
              <div class="text-slate-500 text-xs mb-1"><i class="fas fa-user mr-1"></i>ผู้แจ้ง</div>
              <div class="font-semibold text-sm"><?php echo e($issue['submitter_name']); ?></div>
            </div>
            <div class="col-span-2 p-2 rounded border border-slate-200 bg-slate-50">
              <div class="text-slate-500 text-xs mb-1"><i class="fas fa-building mr-1"></i>ชื่อสหกรณ์</div>
              <div class="font-bold text-sm"><?php echo e($issue['cooperative_name']); ?></div>
            </div>
            <div class="p-2 rounded border border-slate-200 bg-slate-50">
              <div class="text-slate-500 text-xs mb-1"><i class="fas fa-tag mr-1"></i>ประเภทสหกรณ์</div>
              <div class="font-semibold text-[0.82rem]"><?php echo e($issue['cooperative_type_name']); ?></div>
            </div>
            <div class="p-2 rounded border border-slate-200 bg-slate-50">
              <div class="text-slate-500 text-xs mb-1"><i class="fas fa-landmark mr-1"></i>สำนักงาน</div>
              <div class="font-semibold text-[0.8rem]"><?php echo e($issue['office_name']); ?></div>
            </div>
            <div class="p-2 rounded border border-slate-200 bg-slate-50">
              <div class="text-slate-500 text-xs mb-1"><i class="fas fa-exclamation-circle mr-1"></i>ประเภทปัญหา</div>
              <div class="font-semibold text-sm"><?php echo e(issueTypeLabel($issue['issue_type'])); ?></div>
            </div>
            <div class="p-2 rounded border border-slate-200 bg-slate-50">
              <div class="text-slate-500 text-xs mb-1"><i class="fas fa-laptop-code mr-1"></i>โปรแกรม</div>
              <div class="font-semibold text-sm"><?php echo e(programLabel($issue['program_name'])); ?></div>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white border border-slate-200 rounded-md mb-3">
        <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-align-left mr-2 text-slate-500"></i>รายละเอียดปัญหา</div>
        <div class="p-3.5">
          <p class="mb-0 whitespace-pre-line"><?php echo e($issue['detail']); ?></p>
        </div>
      </div>

      <?php if (!empty($issue['attachment'])): ?>
      <div class="bg-white border border-slate-200 rounded-md mb-3">
        <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-paperclip mr-2 text-red-600"></i>ไฟล์แนบ</div>
        <div class="p-0">
          <div class="flex items-center gap-3 px-3.5 py-2.5">
            <div class="w-7 h-7 rounded-full bg-red-600 text-white flex items-center justify-center font-bold text-xs flex-shrink-0"><i class="fas fa-file"></i></div>
            <div class="flex-1 min-w-0">
              <div class="text-slate-500 truncate text-[0.82rem]"><?php echo e($issue['attachment_name']); ?></div>
            </div>
            <a href="?page=issues&action=view_attachment&id=<?php echo $issue['id']; ?>"
               target="_blank" rel="noopener"
               class="<?php echo uiBtnClasses('info'); ?> flex-shrink-0">
              <i class="fas fa-eye mr-1"></i><span class="hidden sm:inline">ดูไฟล์แนบ</span>
            </a>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <div class="bg-white border border-slate-200 rounded-md mb-3">
        <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm flex items-center justify-between">
          <span><i class="fas fa-history mr-2 text-slate-500"></i>ประวัติการดำเนินการ</span>
          <?php if (!empty($logs)): ?><span class="bg-slate-500 text-white text-xs rounded px-1.5 py-0.5"><?php echo count($logs); ?> รายการ</span><?php endif; ?>
        </div>
        <div class="p-0">
          <?php if (empty($logs)): ?>
          <div class="px-3.5 py-3 text-slate-400 text-sm"><i class="fas fa-minus mr-1"></i>ยังไม่มีประวัติ</div>
          <?php else: foreach ($logs as $idx => $log): ?>
          <div class="flex items-start gap-3 px-3.5 py-2.5 border-b border-slate-100 last:border-b-0 <?php echo $idx % 2 == 0 ? '' : 'bg-slate-50'; ?>">
            <div class="w-[30px] h-[30px] rounded-full bg-slate-500 text-white flex items-center justify-center flex-shrink-0 text-xs mt-0.5">
              <i class="fas fa-circle-notch"></i>
            </div>
            <div class="flex-1">
              <div class="font-semibold text-sm"><?php echo e($log['action']); ?></div>
              <div class="text-slate-500 text-xs">
                <i class="fas fa-user mr-1"></i><?php echo e($log['actor_name']); ?>
                &nbsp;|&nbsp;<i class="fas fa-clock mr-1"></i><?php echo thaiDate($log['created_at']); ?>
              </div>
              <?php if (!empty($log['note'])): ?>
              <div class="mt-1 px-2 py-1 rounded bg-slate-50 text-[0.8rem] text-slate-600"><?php echo e($log['note']); ?></div>
              <?php endif; ?>
            </div>
          </div>
          <?php endforeach; endif; ?>
        </div>
      </div>

    </div>

    <!-- ขวา: Action panel -->
    <div class="lg:col-span-4">
    <div class="lg:sticky lg:top-4">

      <div class="bg-white border border-slate-200 rounded-md mb-3">
        <div class="<?php echo $st['header']; ?> text-white px-3.5 py-2.5 font-semibold text-sm">
          <i class="<?php echo $st['icon']; ?> mr-2"></i>สถานะปัจจุบัน
        </div>
        <div class="p-3.5 text-center py-3">
          <div class="font-bold text-lg"><?php echo issueStatusLabel($issue['status'], !empty($issue['handled_by_central'])); ?></div>
          <?php if ($issue['updated_at']): ?>
          <div class="text-slate-500 text-sm mt-1">อัปเดตล่าสุด: <?php echo thaiDate($issue['updated_at']); ?></div>
          <?php endif; ?>
        </div>
      </div>

      <?php if ($canHandle && $issue['status'] === 'pending'): ?>
      <div class="bg-white border border-slate-200 rounded-md mb-3">
        <div class="bg-blue-600 text-white px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-cogs mr-2"></i>รับเรื่องดำเนินการ</div>
        <div class="p-3.5">
          <p class="text-slate-500 text-sm mb-2">
            <?php echo $issue['submitted_by'] == $user['id'] ? 'เลือกวิธีดำเนินการกับเรื่องที่คุณแจ้ง' : 'เลือกวิธีดำเนินการกับเรื่องนี้'; ?>
          </p>
          <form method="POST" action="?page=issues&action=start" class="mb-2">
            <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
            <input type="hidden" name="issue_id" value="<?php echo $issue['id']; ?>">
            <button type="submit" class="<?php echo uiBtnClasses('primary'); ?> w-full">
              <i class="fas fa-play mr-1"></i>ดำเนินการเอง
            </button>
          </form>
          <form method="POST" action="?page=issues&action=escalate">
            <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
            <input type="hidden" name="issue_id" value="<?php echo $issue['id']; ?>">
            <button type="submit" class="<?php echo uiBtnClasses('purple'); ?> w-full">
              <i class="fas fa-paper-plane mr-1"></i>ส่งต่อส่วนกลาง
            </button>
          </form>
        </div>
      </div>
      <?php endif; ?>

      <?php if (userHasRole($user, 'admin') && isHQ($user) && $issue['status'] === 'sent_central'): ?>
      <div class="bg-white border border-slate-200 rounded-md mb-3">
        <div class="bg-purple-700 text-white px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-inbox mr-2"></i>ส่วนกลาง</div>
        <div class="p-3.5">
          <p class="text-slate-500 text-sm mb-2">เรื่องนี้ถูกส่งมาให้ส่วนกลางดำเนินการ</p>
          <form method="POST" action="?page=issues&action=accept_central">
            <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
            <input type="hidden" name="issue_id" value="<?php echo $issue['id']; ?>">
            <button type="submit" class="<?php echo uiBtnClasses('purple'); ?> w-full">
              <i class="fas fa-check mr-1"></i>รับเรื่องดำเนินการ
            </button>
          </form>
        </div>
      </div>
      <?php elseif ($issue['status'] === 'sent_central'): ?>
      <div class="bg-white border border-slate-200 rounded-md mb-3">
        <div class="bg-purple-600 text-white px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-paper-plane mr-2"></i>ส่งส่วนกลางแล้ว</div>
        <div class="p-3.5 text-center py-3 text-sm text-slate-500">
          รอส่วนกลางรับเรื่องดำเนินการ
        </div>
      </div>
      <?php endif; ?>

      <?php if ($issue['status'] === 'in_progress' && $canComplete): ?>
      <div class="bg-white border border-slate-200 rounded-md mb-3">
        <div class="bg-green-600 text-white px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-check-double mr-2"></i>ดำเนินการเสร็จสิ้น</div>
        <div class="p-3.5">
          <form method="POST" action="?page=issues&action=complete">
            <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
            <input type="hidden" name="issue_id" value="<?php echo $issue['id']; ?>">
            <div class="mb-2">
              <label class="block text-sm font-semibold text-slate-700 mb-1">บันทึกผลการดำเนินการ</label>
              <textarea name="note" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" rows="3" placeholder="สรุปวิธีแก้ไข / ผลลัพธ์"></textarea>
            </div>
            <button type="submit" class="<?php echo uiBtnClasses('success'); ?> w-full">
              <i class="fas fa-check mr-1"></i>เสร็จสิ้น
            </button>
          </form>
        </div>
      </div>
      <?php elseif ($issue['status'] === 'in_progress'): ?>
      <div class="bg-white border border-slate-200 rounded-md mb-3">
        <div class="bg-blue-600 text-white px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-cogs mr-2"></i>กำลังดำเนินการ</div>
        <div class="p-3.5 text-center py-3 text-sm text-slate-500">
          <?php echo $isCentralHandling ? 'ส่วนกลางเป็นผู้รับเรื่องนี้ไปดำเนินการ รอส่วนกลางบันทึกผลและปิดเรื่อง' : 'สำนักงานกำลังดำเนินการเรื่องนี้อยู่ รอบันทึกผลและปิดเรื่อง'; ?>
        </div>
      </div>
      <?php endif; ?>

      <?php if ($issue['status'] === 'completed'): ?>
      <div class="bg-white border border-slate-200 rounded-md mb-3">
        <div class="bg-green-600 text-white px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-check-circle mr-2"></i>ดำเนินการสำเร็จ</div>
        <div class="p-3.5 text-center py-6">
          <i class="fas fa-check-circle text-green-600 text-4xl"></i>
          <p class="mt-2 font-bold text-green-600 mb-0">ปัญหานี้ดำเนินการสำเร็จแล้ว</p>
        </div>
      </div>
      <?php endif; ?>

      <a href="?page=issues" class="<?php echo uiBtnClasses('outline'); ?> w-full">
        <i class="fas fa-arrow-left mr-1"></i>กลับรายการแจ้งปัญหา
      </a>

    </div>
    </div>

  </div>
</main>
