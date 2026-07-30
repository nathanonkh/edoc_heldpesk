<?php
$user = Auth::currentUser();
$stColors = array(
    'pending'      => array('bg'=>'bg-warning text-dark',   'icon'=>'fas fa-clock'),
    'sent_central' => array('bg'=>'badge-purple text-white','icon'=>'fas fa-paper-plane'),
    'in_progress'  => array('bg'=>'bg-primary text-white',  'icon'=>'fas fa-cogs'),
    'completed'    => array('bg'=>'bg-success text-white',  'icon'=>'fas fa-check-circle'),
);
$st = isset($stColors[$issue['status']]) ? $stColors[$issue['status']] : array('bg'=>'bg-secondary text-white','icon'=>'fas fa-circle');
// ผู้แจ้งเรื่องเองตัดสินใจได้ว่าจะดำเนินการเองหรือส่งต่อส่วนกลาง หรือเจ้าหน้าที่ประจำสำนักงานที่รับผิดชอบ
$canHandle = canHandleIssue($user, $issue);
// เรื่องที่เคยถูกส่งไปส่วนกลาง (ไม่ว่าจะกำลังดำเนินการหรือสำเร็จแล้ว) ให้เฉพาะ admin ส่วนกลางบันทึกผลได้
// เรื่องที่จังหวัดดำเนินการเอง (ไม่เคยส่งส่วนกลาง) ให้ผู้แจ้ง/เจ้าหน้าที่จังหวัดบันทึกผลได้เท่านั้น
$isCentralHandling = !empty($issue['handled_by_central']);
$canComplete = $isCentralHandling ? (userHasRole($user, 'admin') && isHQ($user)) : $canHandle;
?>

<div class="breadcrumb-bar">
  <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="?page=dashboard"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item"><a href="?page=issues">แจ้งปัญหาการใช้งาน</a></li>
    <li class="breadcrumb-item active"><?php echo e($issue['ticket_code']); ?></li>
  </ol></nav>
</div>

<main class="content-area">

  <div class="page-banner mb-3">
    <div class="page-banner-icon <?php echo $st['bg']; ?>">
      <i class="<?php echo $st['icon']; ?>"></i>
    </div>
    <div class="flex-grow-1">
      <div class="page-banner-title"><?php echo e($issue['ticket_code']); ?></div>
      <div class="page-banner-sub">
        <?php echo e($issue['title']); ?> &nbsp;|&nbsp;
        <span class="badge <?php echo $st['bg']; ?>"><?php echo issueStatusLabel($issue['status'], !empty($issue['handled_by_central'])); ?></span>
      </div>
    </div>
  </div>

  <div class="row g-3">

    <!-- ซ้าย: ข้อมูล -->
    <div class="col-12 col-lg-8">

      <div class="page-card mb-3">
        <div class="page-card-header"><span><i class="fas fa-info-circle me-2 text-primary"></i>ข้อมูลการแจ้งปัญหา</span></div>
        <div class="page-card-body">
          <div class="row g-2">
            <div class="col-6 col-md-4">
              <div class="p-2 rounded border bg-light">
                <div class="text-muted small mb-1"><i class="fas fa-hashtag fa-fw me-1"></i>เลขที่แจ้ง</div>
                <div class="fw-bold"><code><?php echo e($issue['ticket_code']); ?></code></div>
              </div>
            </div>
            <div class="col-6 col-md-4">
              <div class="p-2 rounded border bg-light">
                <div class="text-muted small mb-1"><i class="fas fa-clock fa-fw me-1"></i>วันที่แจ้ง</div>
                <div class="fw-semibold" style="font-size:0.85rem;"><?php echo thaiDate($issue['created_at']); ?></div>
              </div>
            </div>
            <div class="col-6 col-md-4">
              <div class="p-2 rounded border bg-light">
                <div class="text-muted small mb-1"><i class="fas fa-user fa-fw me-1"></i>ผู้แจ้ง</div>
                <div class="fw-semibold" style="font-size:0.85rem;"><?php echo e($issue['submitter_name']); ?></div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="p-2 rounded border bg-light">
                <div class="text-muted small mb-1"><i class="fas fa-building fa-fw me-1"></i>ชื่อสหกรณ์</div>
                <div class="fw-bold"><?php echo e($issue['cooperative_name']); ?></div>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="p-2 rounded border bg-light">
                <div class="text-muted small mb-1"><i class="fas fa-tag fa-fw me-1"></i>ประเภทสหกรณ์</div>
                <div class="fw-semibold" style="font-size:0.82rem;"><?php echo e($issue['cooperative_type_name']); ?></div>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="p-2 rounded border bg-light">
                <div class="text-muted small mb-1"><i class="fas fa-landmark fa-fw me-1"></i>สำนักงาน</div>
                <div class="fw-semibold" style="font-size:0.8rem;"><?php echo e($issue['office_name']); ?></div>
              </div>
            </div>
            <div class="col-6 col-md-6">
              <div class="p-2 rounded border bg-light">
                <div class="text-muted small mb-1"><i class="fas fa-exclamation-circle fa-fw me-1"></i>ประเภทปัญหา</div>
                <div class="fw-semibold" style="font-size:0.85rem;"><?php echo e(issueTypeLabel($issue['issue_type'])); ?></div>
              </div>
            </div>
            <div class="col-6 col-md-6">
              <div class="p-2 rounded border bg-light">
                <div class="text-muted small mb-1"><i class="fas fa-laptop-code fa-fw me-1"></i>โปรแกรม</div>
                <div class="fw-semibold" style="font-size:0.85rem;"><?php echo e(programLabel($issue['program_name'])); ?></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="page-card mb-3">
        <div class="page-card-header"><span><i class="fas fa-align-left me-2 text-secondary"></i>รายละเอียดปัญหา</span></div>
        <div class="page-card-body">
          <p class="mb-0" style="white-space:pre-line;"><?php echo e($issue['detail']); ?></p>
        </div>
      </div>

      <?php if (!empty($issue['attachment'])): ?>
      <div class="page-card mb-3">
        <div class="page-card-header"><span><i class="fas fa-paperclip me-2 text-danger"></i>ไฟล์แนบ</span></div>
        <div class="page-card-body p-0">
          <div class="d-flex align-items-center gap-3 px-3 py-2">
            <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                 style="width:28px;height:28px;font-size:0.8rem;"><i class="fas fa-file"></i></div>
            <div class="flex-grow-1" style="min-width:0;">
              <div class="text-muted text-truncate" style="font-size:0.82rem;"><?php echo e($issue['attachment_name']); ?></div>
            </div>
            <a href="?page=issues&action=view_attachment&id=<?php echo $issue['id']; ?>"
               target="_blank" rel="noopener"
               class="btn btn-view-file btn-sm flex-shrink-0">
              <i class="fas fa-eye me-1"></i><span class="d-none d-sm-inline">ดูไฟล์แนบ</span>
            </a>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <div class="page-card mb-3">
        <div class="page-card-header">
          <span><i class="fas fa-history me-2 text-secondary"></i>ประวัติการดำเนินการ</span>
          <?php if (!empty($logs)): ?><span class="badge bg-secondary"><?php echo count($logs); ?> รายการ</span><?php endif; ?>
        </div>
        <div class="page-card-body p-0">
          <?php if (empty($logs)): ?>
          <div class="px-3 py-3 text-muted small"><i class="fas fa-minus me-1"></i>ยังไม่มีประวัติ</div>
          <?php else: foreach ($logs as $idx => $log): ?>
          <div class="d-flex align-items-start gap-3 px-3 py-2 border-bottom <?php echo $idx % 2 == 0 ? '' : 'bg-light'; ?>">
            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width:30px;height:30px;font-size:0.75rem;margin-top:2px;">
              <i class="fas fa-circle-notch"></i>
            </div>
            <div class="flex-grow-1">
              <div class="fw-semibold small"><?php echo e($log['action']); ?></div>
              <div class="text-muted" style="font-size:0.78rem;">
                <i class="fas fa-user me-1"></i><?php echo e($log['actor_name']); ?>
                &nbsp;|&nbsp;<i class="fas fa-clock me-1"></i><?php echo thaiDate($log['created_at']); ?>
              </div>
              <?php if (!empty($log['note'])): ?>
              <div class="mt-1 px-2 py-1 rounded" style="background:#f8f9fa;font-size:0.8rem;color:#555;"><?php echo e($log['note']); ?></div>
              <?php endif; ?>
            </div>
          </div>
          <?php endforeach; endif; ?>
        </div>
      </div>

    </div>

    <!-- ขวา: Action panel -->
    <div class="col-12 col-lg-4">
    <div class="action-panel">

      <div class="page-card mb-3">
        <div class="page-card-header <?php echo $st['bg']; ?>">
          <span><i class="<?php echo $st['icon']; ?> me-2"></i>สถานะปัจจุบัน</span>
        </div>
        <div class="page-card-body text-center py-2">
          <div class="fw-bold fs-5"><?php echo issueStatusLabel($issue['status'], !empty($issue['handled_by_central'])); ?></div>
          <?php if ($issue['updated_at']): ?>
          <div class="text-muted small mt-1">อัปเดตล่าสุด: <?php echo thaiDate($issue['updated_at']); ?></div>
          <?php endif; ?>
        </div>
      </div>

      <?php if ($canHandle && $issue['status'] === 'pending'): ?>
      <div class="page-card mb-3">
        <div class="page-card-header bg-primary text-white"><span><i class="fas fa-cogs me-2"></i>รับเรื่องดำเนินการ</span></div>
        <div class="page-card-body">
          <p class="small text-muted mb-2">
            <?php echo $issue['submitted_by'] == $user['id'] ? 'เลือกวิธีดำเนินการกับเรื่องที่คุณแจ้ง' : 'เลือกวิธีดำเนินการกับเรื่องนี้'; ?>
          </p>
          <form method="POST" action="?page=issues&action=start" class="mb-2">
            <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
            <input type="hidden" name="issue_id" value="<?php echo $issue['id']; ?>">
            <button type="submit" class="btn btn-primary btn-sm w-100">
              <i class="fas fa-play me-1"></i>ดำเนินการเอง
            </button>
          </form>
          <form method="POST" action="?page=issues&action=escalate">
            <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
            <input type="hidden" name="issue_id" value="<?php echo $issue['id']; ?>">
            <button type="submit" class="btn btn-sm w-100 text-white" style="background:#7b1fa2;">
              <i class="fas fa-paper-plane me-1"></i>ส่งต่อส่วนกลาง
            </button>
          </form>
        </div>
      </div>
      <?php endif; ?>

      <?php if (userHasRole($user, 'admin') && isHQ($user) && $issue['status'] === 'sent_central'): ?>
      <div class="page-card mb-3">
        <div class="page-card-header text-white" style="background:#7b1fa2;"><span><i class="fas fa-inbox me-2"></i>ส่วนกลาง</span></div>
        <div class="page-card-body">
          <p class="small text-muted mb-2">เรื่องนี้ถูกส่งมาให้ส่วนกลางดำเนินการ</p>
          <form method="POST" action="?page=issues&action=accept_central">
            <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
            <input type="hidden" name="issue_id" value="<?php echo $issue['id']; ?>">
            <button type="submit" class="btn btn-sm w-100 text-white" style="background:#7b1fa2;">
              <i class="fas fa-check me-1"></i>รับเรื่องดำเนินการ
            </button>
          </form>
        </div>
      </div>
      <?php elseif ($issue['status'] === 'sent_central'): ?>
      <div class="page-card mb-3">
        <div class="page-card-header badge-purple text-white"><span><i class="fas fa-paper-plane me-2"></i>ส่งส่วนกลางแล้ว</span></div>
        <div class="page-card-body text-center py-3 small text-muted">
          รอส่วนกลางรับเรื่องดำเนินการ
        </div>
      </div>
      <?php endif; ?>

      <?php if ($issue['status'] === 'in_progress' && $canComplete): ?>
      <div class="page-card mb-3">
        <div class="page-card-header bg-success text-white"><span><i class="fas fa-check-double me-2"></i>ดำเนินการเสร็จสิ้น</span></div>
        <div class="page-card-body">
          <form method="POST" action="?page=issues&action=complete">
            <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
            <input type="hidden" name="issue_id" value="<?php echo $issue['id']; ?>">
            <div class="mb-2">
              <label class="form-label">บันทึกผลการดำเนินการ</label>
              <textarea name="note" class="form-control form-control-sm" rows="3" placeholder="สรุปวิธีแก้ไข / ผลลัพธ์"></textarea>
            </div>
            <button type="submit" class="btn btn-success btn-sm w-100">
              <i class="fas fa-check me-1"></i>เสร็จสิ้น
            </button>
          </form>
        </div>
      </div>
      <?php elseif ($issue['status'] === 'in_progress'): ?>
      <div class="page-card mb-3">
        <div class="page-card-header bg-primary text-white"><span><i class="fas fa-cogs me-2"></i>กำลังดำเนินการ</span></div>
        <div class="page-card-body text-center py-3 small text-muted">
          <?php echo $isCentralHandling ? 'ส่วนกลางเป็นผู้รับเรื่องนี้ไปดำเนินการ รอส่วนกลางบันทึกผลและปิดเรื่อง' : 'สำนักงานกำลังดำเนินการเรื่องนี้อยู่ รอบันทึกผลและปิดเรื่อง'; ?>
        </div>
      </div>
      <?php endif; ?>

      <?php if ($issue['status'] === 'completed'): ?>
      <div class="page-card mb-3">
        <div class="page-card-header bg-success text-white"><span><i class="fas fa-check-circle me-2"></i>ดำเนินการสำเร็จ</span></div>
        <div class="page-card-body text-center py-3">
          <i class="fas fa-check-circle text-success" style="font-size:2.5rem;"></i>
          <p class="mt-2 fw-bold text-success mb-0">ปัญหานี้ดำเนินการสำเร็จแล้ว</p>
        </div>
      </div>
      <?php endif; ?>

      <a href="?page=issues" class="btn btn-outline-secondary btn-sm w-100">
        <i class="fas fa-arrow-left me-1"></i>กลับรายการแจ้งปัญหา
      </a>

    </div>
    </div>

  </div>
</main>
