<?php
$user = Auth::currentUser();
$stColors = array(
    'pending'    => array('alert'=>'warning',  'bg'=>'bg-warning text-dark', 'icon'=>'fas fa-clock'),
    'inspecting' => array('alert'=>'primary',  'bg'=>'bg-primary text-white','icon'=>'fas fa-search'),
    'approving'  => array('alert'=>'info',     'bg'=>'bg-info text-dark',    'icon'=>'fas fa-user-check'),
    'operating'  => array('alert'=>'secondary','bg'=>'bg-purple text-white', 'icon'=>'fas fa-tasks'),
    'revision'   => array('alert'=>'danger',   'bg'=>'bg-danger text-white', 'icon'=>'fas fa-undo'),
    'completed'  => array('alert'=>'success',  'bg'=>'bg-success text-white','icon'=>'fas fa-check-circle'),
);
$st = isset($stColors[$doc['status']]) ? $stColors[$doc['status']] : array('alert'=>'secondary','bg'=>'bg-secondary text-white','icon'=>'fas fa-circle');
?>

<div class="breadcrumb-bar">
  <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="?page=dashboard"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item"><a href="?page=documents">รายการเอกสาร</a></li>
    <li class="breadcrumb-item active"><?php echo e($doc['ticket_code']); ?></li>
  </ol></nav>
</div>

<main class="content-area">

  <div class="page-banner mb-3">
    <div class="page-banner-icon <?php echo $st['bg']; ?>">
      <i class="<?php echo $st['icon']; ?>"></i>
    </div>
    <div class="flex-grow-1">
      <div class="page-banner-title"><?php echo e($doc['ticket_code']); ?></div>
      <div class="page-banner-sub">
        <?php echo e($doc['cooperative_name']); ?> &nbsp;|&nbsp;
        <span class="badge <?php echo $st['bg']; ?>" id="docStatusBadge"><?php echo docStatusLabel($doc['status']); ?></span>
        <?php if (!empty($doc['revision_note'])): ?>
        &nbsp;<span class="text-danger small"><i class="fas fa-exclamation-circle me-1"></i><?php echo e($doc['revision_note']); ?></span>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="row g-3">

    <!-- ซ้าย: ข้อมูลเอกสาร -->
    <div class="col-12 col-lg-8">

      <div class="page-card mb-3">
        <div class="page-card-header"><span><i class="fas fa-file-alt me-2 text-primary"></i>ข้อมูลเอกสาร</span></div>
        <div class="page-card-body">
          <div class="row g-2">
            <div class="col-6 col-md-3">
              <div class="p-2 rounded border bg-light">
                <div class="text-muted small mb-1"><i class="fas fa-hashtag fa-fw me-1"></i>เลขที่เอกสาร</div>
                <div class="fw-bold"><code><?php echo e($doc['ticket_code']); ?></code></div>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="p-2 rounded border bg-light">
                <div class="text-muted small mb-1"><i class="fas fa-file-signature fa-fw me-1"></i>เลขที่หนังสือ</div>
                <div class="fw-bold"><?php echo !empty($doc['document_number']) ? e($doc['document_number']) : '<span class="text-muted">-</span>'; ?></div>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="p-2 rounded border bg-light">
                <div class="text-muted small mb-1"><i class="fas fa-inbox fa-fw me-1"></i>เลขรับหนังสือ</div>
                <div class="fw-bold"><?php echo $doc['receive_number'] ? e($doc['receive_number']) : '<span class="text-muted">-</span>'; ?></div>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="p-2 rounded border bg-light">
                <div class="text-muted small mb-1"><i class="fas fa-calendar fa-fw me-1"></i>ปีบัญชี</div>
                <div class="fw-bold"><?php echo e($doc['fiscal_year']); ?></div>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="p-2 rounded border bg-light">
                <div class="text-muted small mb-1"><i class="fas fa-paper-plane fa-fw me-1"></i>วันที่นำส่ง</div>
                <div class="fw-bold"><?php echo formatThaiDate2($doc['submitted_date']); ?></div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="p-2 rounded border bg-light">
                <div class="text-muted small mb-1"><i class="fas fa-building fa-fw me-1"></i>ชื่อสหกรณ์</div>
                <div class="fw-bold"><?php echo e($doc['cooperative_name']); ?></div>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="p-2 rounded border bg-light">
                <div class="text-muted small mb-1"><i class="fas fa-tag fa-fw me-1"></i>ประเภทสหกรณ์</div>
                <div class="fw-semibold" style="font-size:0.85rem;"><?php echo e($doc['cooperative_type_name']); ?></div>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="p-2 rounded border bg-light">
                <div class="text-muted small mb-1"><i class="fas fa-landmark fa-fw me-1"></i>สำนักงาน</div>
                <div class="fw-semibold" style="font-size:0.82rem;"><?php echo e($doc['office_name']); ?></div>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="p-2 rounded border bg-light">
                <div class="text-muted small mb-1"><i class="fas fa-user fa-fw me-1"></i>ผู้นำส่ง</div>
                <div class="fw-semibold" style="font-size:0.85rem;"><?php echo e($doc['submitter_name']); ?></div>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="p-2 rounded border bg-light">
                <div class="text-muted small mb-1"><i class="fas fa-clock fa-fw me-1"></i>วันที่สร้าง</div>
                <div class="fw-semibold" style="font-size:0.85rem;"><?php echo thaiDate($doc['created_at']); ?></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ไฟล์เอกสาร -->
      <div class="page-card mb-3">
        <div class="page-card-header"><span><i class="fas fa-paperclip me-2 text-danger"></i>ไฟล์เอกสาร PDF</span></div>
        <div class="page-card-body p-0">
          <?php for ($i = 1; $i <= 4; $i++):
            $nameField = 'file_doc' . $i . '_name';
          ?>
          <div class="d-flex align-items-center gap-3 px-3 py-2 border-bottom">
            <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                 style="width:28px;height:28px;font-size:0.8rem;"><?php echo $i; ?></div>
            <div class="flex-grow-1" style="min-width:0;">
              <div class="fw-semibold small"><?php echo docFileLabel($i); ?></div>
              <div class="text-muted text-truncate" style="font-size:0.78rem;"><?php echo e($doc[$nameField]); ?></div>
            </div>
            <a href="?page=documents&action=view_file&id=<?php echo $doc['id']; ?>&file=<?php echo $i; ?>"
               target="_blank" rel="noopener"
               class="btn btn-view-file btn-sm flex-shrink-0">
              <i class="fas fa-eye me-1"></i><span class="d-none d-sm-inline">ดูเอกสาร</span>
            </a>
          </div>
          <?php endfor; ?>
        </div>
      </div>

      <!-- หมายเหตุ -->
      <div class="page-card mb-3">
        <div class="page-card-header"><span><i class="fas fa-sticky-note me-2 text-warning"></i>หมายเหตุการดำเนินการ</span></div>
        <div class="page-card-body p-0">
          <?php
          $notes = array(
            array('badge'=>'bg-primary',       'label'=>'ผู้ตรวจสอบ',  'field'=>'inspector_note','icon'=>'fas fa-search'),
            array('badge'=>'bg-info text-dark', 'label'=>'ผู้อนุมัติ',  'field'=>'approver_note', 'icon'=>'fas fa-user-check'),
            array('badge'=>'badge-purple',      'label'=>'ผู้ดำเนินการ','field'=>'operator_note', 'icon'=>'fas fa-tasks'),
            array('badge'=>'bg-danger',         'label'=>'ส่งกลับแก้ไข','field'=>'revision_note', 'icon'=>'fas fa-undo'),
          );
          $hasNote = false;
          foreach ($notes as $n) { if (!empty($doc[$n['field']])) { $hasNote = true; break; } }
          if (!$hasNote):
          ?>
          <div class="px-3 py-3 text-muted small"><i class="fas fa-minus me-1"></i>ยังไม่มีหมายเหตุ</div>
          <?php else: ?>
          <?php foreach ($notes as $n): ?>
          <?php if (!empty($doc[$n['field']])): ?>
          <div class="d-flex align-items-start gap-3 px-3 py-2 border-bottom">
            <span class="badge <?php echo $n['badge']; ?> flex-shrink-0 mt-1">
              <i class="<?php echo $n['icon']; ?> me-1"></i><?php echo $n['label']; ?>
            </span>
            <span style="font-size:0.87rem;"><?php echo e($doc[$n['field']]); ?></span>
          </div>
          <?php endif; ?>
          <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

      <!-- ประวัติการดำเนินการ -->
      <div class="page-card mb-3">
        <div class="page-card-header">
          <span><i class="fas fa-history me-2 text-secondary"></i>ประวัติการดำเนินการ</span>
          <?php if (!empty($logs)): ?><span class="badge bg-secondary"><?php echo count($logs); ?> รายการ</span><?php endif; ?>
        </div>
        <div class="page-card-body p-0">
          <?php if (empty($logs)): ?>
          <div class="px-3 py-3 text-muted small"><i class="fas fa-minus me-1"></i>ยังไม่มีประวัติ</div>
          <?php else:
          $logIcons = array(
            'นำส่งเอกสาร'             => array('icon'=>'fas fa-paper-plane','color'=>'bg-secondary'),
            'ตรวจสอบและส่งต่ออนุมัติ' => array('icon'=>'fas fa-check',      'color'=>'bg-primary'),
            'อนุมัติเอกสาร'           => array('icon'=>'fas fa-thumbs-up',  'color'=>'bg-success'),
            'bulk_approved'            => array('icon'=>'fas fa-check-double','color'=>'bg-success'),
            'ดำเนินการเสร็จสิ้น'      => array('icon'=>'fas fa-flag',        'color'=>'bg-success'),
            'ส่งกลับแก้ไข'            => array('icon'=>'fas fa-undo',        'color'=>'bg-danger'),
            'แก้ไขและส่งใหม่'         => array('icon'=>'fas fa-redo',        'color'=>'bg-warning'),
          );
          foreach ($logs as $idx => $log):
            $li = isset($logIcons[$log['action']]) ? $logIcons[$log['action']] : array('icon'=>'fas fa-circle','color'=>'bg-secondary');
          ?>
          <div class="d-flex align-items-start gap-3 px-3 py-2 border-bottom <?php echo $idx % 2 == 0 ? '' : 'bg-light'; ?>">
            <div class="rounded-circle text-white d-flex align-items-center justify-content-center flex-shrink-0 <?php echo $li['color']; ?>"
                 style="width:30px;height:30px;font-size:0.75rem;margin-top:2px;">
              <i class="<?php echo $li['icon']; ?>"></i>
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
            <div class="flex-shrink-0 text-muted" style="font-size:0.72rem;">#<?php echo $idx + 1; ?></div>
          </div>
          <?php endforeach; endif; ?>
        </div>
      </div>

    </div>

    <!-- ขวา: Action Panel -->
    <div class="col-12 col-lg-4">
    <div class="action-panel">

      <!-- สถานะปัจจุบัน -->
      <div class="page-card mb-3">
        <div class="page-card-header <?php echo $st['bg']; ?>">
          <span><i class="<?php echo $st['icon']; ?> me-2"></i>สถานะปัจจุบัน</span>
        </div>
        <div class="page-card-body">
          <div class="text-center py-2">
            <div class="fw-bold fs-5"><?php echo docStatusLabel($doc['status']); ?></div>
            <?php if ($doc['updated_at'] && $doc['updated_at'] !== '0000-00-00 00:00:00'): ?>
            <div class="text-muted small mt-1">อัปเดตล่าสุด: <?php echo thaiDate($doc['updated_at']); ?></div>
            <?php endif; ?>
          </div>
          <div class="mt-3 pt-2 border-top">
            <?php
            $steps    = array('pending','inspecting','approving','operating','completed');
            $curIdx   = array_search($doc['status'], $steps);
            if ($doc['status'] === 'revision') $curIdx = -1;
            $stLabels = array('pending'=>'นำส่ง','inspecting'=>'ตรวจสอบ','approving'=>'อนุมัติ','operating'=>'ดำเนินการ','completed'=>'เสร็จสิ้น');
            foreach ($steps as $si => $step):
              $done    = ($curIdx !== false && $curIdx !== -1 && $si < $curIdx);
              $current = ($doc['status'] === $step);
              $cls     = $done ? 'bg-success' : ($current ? 'bg-primary' : 'bg-light border');
            ?>
            <div class="d-flex align-items-center gap-2 mb-1">
              <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 <?php echo $cls; ?>"
                   style="width:22px;height:22px;font-size:0.65rem;">
                <?php if ($done): ?><i class="fas fa-check text-white"></i>
                <?php elseif ($current): ?><i class="fas fa-dot-circle text-white"></i>
                <?php else: ?><span class="text-muted" style="font-size:0.65rem;"><?php echo $si+1; ?></span>
                <?php endif; ?>
              </div>
              <span style="font-size:0.82rem;" class="<?php echo $current ? 'fw-bold text-primary' : ($done ? 'text-success' : 'text-muted'); ?>">
                <?php echo $stLabels[$step]; ?>
              </span>
            </div>
            <?php endforeach; ?>
            <?php if ($doc['status'] === 'revision'): ?>
            <div class="d-flex align-items-center gap-2 mt-1">
              <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 bg-danger" style="width:22px;height:22px;">
                <i class="fas fa-undo text-white" style="font-size:0.6rem;"></i>
              </div>
              <span class="fw-bold text-danger" style="font-size:0.82rem;">ส่งกลับแก้ไข</span>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Inspector form -->
      <?php if (userHasRole($user,'inspector') && in_array($doc['status'], array('pending','inspecting')) && canActionDocument($user, $doc)): ?>
      <div class="page-card mb-3">
        <div class="page-card-header bg-primary text-white"><span><i class="fas fa-search me-2"></i>ตรวจสอบเอกสาร</span></div>
        <div class="page-card-body">
          <form method="POST" action="?page=documents&action=inspect">
            <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
            <input type="hidden" name="doc_id" value="<?php echo $doc['id']; ?>">
            <div class="mb-2">
              <label class="form-label">เลขรับหนังสือ <span class="text-danger">*</span></label>
              <input type="text" name="receive_number" class="form-control form-control-sm" required
                     value="<?php echo $doc['receive_number'] ? e($doc['receive_number']) : ''; ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">หมายเหตุ</label>
              <textarea name="inspector_note" class="form-control form-control-sm" rows="2"><?php echo e($doc['inspector_note']); ?></textarea>
            </div>
            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                <i class="fas fa-check me-1"></i>ส่งต่ออนุมัติ
              </button>
              <button type="button" class="btn btn-outline-danger btn-sm" onclick="openRevisionModal()" title="ส่งกลับแก้ไข">
                <i class="fas fa-undo"></i>
              </button>
            </div>
          </form>
        </div>
      </div>
      <?php endif; ?>

      <!-- Approver form -->
      <?php if (userHasRole($user,'approver') && $doc['status'] === 'approving' && canActionDocument($user, $doc)): ?>
      <div class="page-card mb-3">
        <div class="page-card-header bg-info text-dark"><span><i class="fas fa-user-check me-2"></i>อนุมัติเอกสาร</span></div>
        <div class="page-card-body">
          <form method="POST" action="?page=documents&action=approve">
            <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
            <input type="hidden" name="doc_id" value="<?php echo $doc['id']; ?>">
            <div class="mb-3">
              <label class="form-label">หมายเหตุ</label>
              <textarea name="approver_note" class="form-control form-control-sm" rows="2"></textarea>
            </div>
            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-info btn-sm flex-grow-1 text-dark fw-semibold">
                <i class="fas fa-thumbs-up me-1"></i>อนุมัติเอกสาร
              </button>
              <button type="button" class="btn btn-outline-danger btn-sm" onclick="openRevisionModal()" title="ส่งกลับแก้ไข">
                <i class="fas fa-undo"></i>
              </button>
            </div>
          </form>
        </div>
      </div>
      <?php endif; ?>

      <!-- Operator form -->
      <?php if (userHasRole($user,'operator') && $doc['status'] === 'operating' && canActionDocument($user, $doc)): ?>
      <div class="page-card mb-3">
        <div class="page-card-header text-white" style="background:#7b1fa2;"><span><i class="fas fa-tasks me-2"></i>ดำเนินการเอกสาร</span></div>
        <div class="page-card-body">
          <form method="POST" action="?page=documents&action=operate">
            <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
            <input type="hidden" name="doc_id" value="<?php echo $doc['id']; ?>">
            <div class="mb-3">
              <label class="form-label">หมายเหตุ</label>
              <textarea name="operator_note" class="form-control form-control-sm" rows="2"></textarea>
            </div>
            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-sm flex-grow-1 text-white fw-semibold" style="background:#7b1fa2;">
                <i class="fas fa-check-double me-1"></i>ดำเนินการเสร็จสิ้น
              </button>
              <button type="button" class="btn btn-outline-danger btn-sm" onclick="openRevisionModal()" title="ส่งกลับแก้ไข">
                <i class="fas fa-undo"></i>
              </button>
            </div>
          </form>
        </div>
      </div>
      <?php endif; ?>

      <!-- Submitter: resubmit -->
      <?php if (userHasRole($user,'submitter') && $doc['status'] === 'revision' && $doc['submitted_by'] == $user['id']): ?>
      <div class="page-card mb-3">
        <div class="page-card-header bg-warning text-dark"><span><i class="fas fa-redo me-2"></i>แก้ไขและส่งใหม่</span></div>
        <div class="page-card-body">
          <p class="small text-muted mb-2">เอกสารถูกส่งกลับ กรุณาแก้ไขและส่งใหม่</p>
          <a href="?page=documents&action=edit&id=<?php echo $doc['id']; ?>" class="btn btn-warning btn-sm w-100 fw-semibold">
            <i class="fas fa-edit me-1"></i>แก้ไขเอกสาร
          </a>
        </div>
      </div>
      <?php endif; ?>

      <?php if ($doc['status'] === 'completed'): ?>
      <div class="page-card mb-3">
        <div class="page-card-header bg-success text-white"><span><i class="fas fa-check-circle me-2"></i>ดำเนินการเสร็จสิ้น</span></div>
        <div class="page-card-body text-center py-3">
          <i class="fas fa-check-circle text-success" style="font-size:2.5rem;"></i>
          <p class="mt-2 fw-bold text-success mb-0">เอกสารนี้ดำเนินการเสร็จสิ้นแล้ว</p>
          <?php if ($doc['operated_at']): ?>
          <p class="text-muted small mt-1"><?php echo thaiDate($doc['operated_at']); ?></p>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>

      <a href="?page=documents" class="btn btn-outline-secondary btn-sm w-100">
        <i class="fas fa-arrow-left me-1"></i>กลับรายการเอกสาร
      </a>

    </div>
    </div>

  </div>
</main>

<!-- Revision Modal -->
<?php if (userHasAnyRole($user, array('inspector','approver','operator')) && canActionDocument($user, $doc)): ?>
<div class="modal fade" id="revisionModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white py-2">
        <h6 class="modal-title mb-0"><i class="fas fa-undo me-1"></i>ส่งกลับให้แก้ไข</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <label class="form-label fw-semibold">เหตุผลที่ส่งกลับ <span class="text-danger">*</span></label>
        <textarea id="revisionNoteInput" class="form-control form-control-sm" rows="3"
                  placeholder="ระบุเหตุผลที่ส่งกลับ..."></textarea>
      </div>
      <div class="modal-footer py-2">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">ยกเลิก</button>
        <button type="button" class="btn btn-danger btn-sm" id="revisionSubmitBtn" onclick="submitRevision()">
          <i class="fas fa-undo me-1"></i>ส่งกลับแก้ไข
        </button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<?php
$docId  = $doc['id'];
$csrf   = Session::getCsrf();
$canRev = userHasAnyRole($user, array('inspector','approver','operator')) && canActionDocument($user, $doc) ? 'true' : 'false';

$extraJs = '<script>
var _revisionModal = null;

function openRevisionModal() {
  if (!_revisionModal) {
    _revisionModal = new bootstrap.Modal(document.getElementById("revisionModal"));
  }
  document.getElementById("revisionNoteInput").value = "";
  _revisionModal.show();
}

function submitRevision() {
  var note = document.getElementById("revisionNoteInput").value.trim();
  if (!note) {
    Swal.fire({icon:"warning",title:"กรุณากรอกเหตุผล",toast:true,position:"top-end",showConfirmButton:false,timer:2000});
    return;
  }
  var btn = document.getElementById("revisionSubmitBtn");
  btn.disabled = true;
  btn.innerHTML = "<i class=\"fas fa-spinner fa-spin me-1\"></i>กำลังส่ง...";

  ajaxPost("?page=documents&action=revision", {
    csrf_token:    "' . $csrf . '",
    doc_id:        "' . $docId . '",
    revision_note: note
  }, function(ok, text) {
    btn.disabled = false;
    btn.innerHTML = "<i class=\"fas fa-undo me-1\"></i>ส่งกลับแก้ไข";
    try {
      var res = JSON.parse(text);
      if (ok && res.success) {
        if (_revisionModal) _revisionModal.hide();
        Swal.fire({icon:"success",title:"ส่งกลับแก้ไขสำเร็จ",toast:true,position:"top-end",showConfirmButton:false,timer:2000});
        setTimeout(function() { window.location.href = "?page=documents"; }, 2000);
      } else {
        Swal.fire({icon:"error",title:res.message || "เกิดข้อผิดพลาด"});
      }
    } catch(e) {
      Swal.fire({icon:"error",title:"เกิดข้อผิดพลาด"});
    }
  });
}

// Auto-refresh สถานะทุก 60 วินาที
setInterval(function() {
  ajaxGet("?page=documents&action=ajax_status&id=' . $docId . '", function(ok, text) {
    if (!ok) return;
    try {
      var data = JSON.parse(text);
      if (data.success) {
        var badge = document.getElementById("docStatusBadge");
        if (badge) { badge.textContent = data.label; badge.className = "badge " + data.badge; }
      }
    } catch(e) {}
  });
}, 60000);
</script>';
?>
