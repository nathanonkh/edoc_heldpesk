<?php
$user = Auth::currentUser();
$st = array(
    'header' => docStatusHeaderClass($doc['status']),
    'icon'   => docStatusIcon($doc['status']),
);
?>

<div class="bg-white border-b border-slate-200 px-4 py-1.5 text-sm">
  <nav aria-label="breadcrumb">
    <ol class="flex items-center gap-1.5 text-slate-500">
      <li><a class="hover:text-[#1565c0]" href="?page=dashboard"><i class="fas fa-home"></i></a></li>
      <li class="text-slate-300">/</li>
      <li><a class="hover:text-[#1565c0]" href="?page=documents">รายการเอกสาร</a></li>
      <li class="text-slate-300">/</li>
      <li class="text-slate-700 font-medium"><?php echo e($doc['ticket_code']); ?></li>
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
      <div class="text-base font-bold text-[#1a237e]"><?php echo e($doc['ticket_code']); ?></div>
      <div class="text-sm text-slate-600 flex items-center gap-2 flex-wrap">
        <?php echo e($doc['cooperative_name']); ?>
        <span id="docStatusBadge"><?php echo uiBadge(docStatusLabel($doc['status']), docStatusBadgeClass($doc['status'])); ?></span>
        <?php if (!empty($doc['revision_note'])): ?>
        <span class="text-red-600"><i class="fas fa-exclamation-circle mr-1"></i><?php echo e($doc['revision_note']); ?></span>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-3">

    <!-- ซ้าย: ข้อมูลเอกสาร -->
    <div class="lg:col-span-8">

      <div class="bg-white border border-slate-200 rounded-md mb-3">
        <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-file-alt mr-2 text-[#1565c0]"></i>ข้อมูลเอกสาร</div>
        <div class="p-3.5">
          <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
            <div class="p-2 rounded border border-slate-200 bg-slate-50">
              <div class="text-slate-500 text-xs mb-1"><i class="fas fa-hashtag mr-1"></i>เลขที่เอกสาร</div>
              <div class="font-bold"><code class="tag"><?php echo e($doc['ticket_code']); ?></code></div>
            </div>
            <div class="p-2 rounded border border-slate-200 bg-slate-50">
              <div class="text-slate-500 text-xs mb-1"><i class="fas fa-file-signature mr-1"></i>เลขที่หนังสือ</div>
              <div class="font-bold text-sm"><?php echo !empty($doc['document_number']) ? e($doc['document_number']) : '<span class="text-slate-400">-</span>'; ?></div>
            </div>
            <div class="p-2 rounded border border-slate-200 bg-slate-50">
              <div class="text-slate-500 text-xs mb-1"><i class="fas fa-inbox mr-1"></i>เลขรับหนังสือ</div>
              <div class="font-bold text-sm"><?php echo $doc['receive_number'] ? e($doc['receive_number']) : '<span class="text-slate-400">-</span>'; ?></div>
            </div>
            <div class="p-2 rounded border border-slate-200 bg-slate-50">
              <div class="text-slate-500 text-xs mb-1"><i class="fas fa-calendar mr-1"></i>ปีบัญชี</div>
              <div class="font-bold text-sm"><?php echo e($doc['fiscal_year']); ?></div>
            </div>
            <div class="p-2 rounded border border-slate-200 bg-slate-50">
              <div class="text-slate-500 text-xs mb-1"><i class="fas fa-paper-plane mr-1"></i>วันที่นำส่ง</div>
              <div class="font-bold text-sm"><?php echo formatThaiDate2($doc['submitted_date']); ?></div>
            </div>
            <div class="col-span-2 p-2 rounded border border-slate-200 bg-slate-50">
              <div class="text-slate-500 text-xs mb-1"><i class="fas fa-building mr-1"></i>ชื่อสหกรณ์</div>
              <div class="font-bold text-sm"><?php echo e($doc['cooperative_name']); ?></div>
            </div>
            <div class="p-2 rounded border border-slate-200 bg-slate-50">
              <div class="text-slate-500 text-xs mb-1"><i class="fas fa-tag mr-1"></i>ประเภทสหกรณ์</div>
              <div class="font-semibold text-[0.85rem]"><?php echo e($doc['cooperative_type_name']); ?></div>
            </div>
            <div class="p-2 rounded border border-slate-200 bg-slate-50">
              <div class="text-slate-500 text-xs mb-1"><i class="fas fa-landmark mr-1"></i>สำนักงาน</div>
              <div class="font-semibold text-[0.82rem]"><?php echo e($doc['office_name']); ?></div>
            </div>
            <div class="p-2 rounded border border-slate-200 bg-slate-50">
              <div class="text-slate-500 text-xs mb-1"><i class="fas fa-user mr-1"></i>ผู้นำส่ง</div>
              <div class="font-semibold text-sm"><?php echo e($doc['submitter_name']); ?></div>
            </div>
            <div class="p-2 rounded border border-slate-200 bg-slate-50">
              <div class="text-slate-500 text-xs mb-1"><i class="fas fa-clock mr-1"></i>วันที่สร้าง</div>
              <div class="font-semibold text-sm"><?php echo thaiDate($doc['created_at']); ?></div>
            </div>
          </div>
        </div>
      </div>

      <!-- ไฟล์เอกสาร -->
      <div class="bg-white border border-slate-200 rounded-md mb-3">
        <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-paperclip mr-2 text-red-600"></i>ไฟล์เอกสาร PDF</div>
        <div class="p-0">
          <?php for ($i = 1; $i <= 4; $i++):
            $nameField = 'file_doc' . $i . '_name';
          ?>
          <div class="flex items-center gap-3 px-3.5 py-2.5 border-b border-slate-100 last:border-b-0">
            <div class="w-7 h-7 rounded-full bg-red-600 text-white flex items-center justify-center font-bold text-xs flex-shrink-0"><?php echo $i; ?></div>
            <div class="flex-1 min-w-0">
              <div class="font-semibold text-sm"><?php echo docFileLabel($i); ?></div>
              <div class="text-slate-500 truncate text-xs"><?php echo e($doc[$nameField]); ?></div>
            </div>
            <a href="?page=documents&action=view_file&id=<?php echo $doc['id']; ?>&file=<?php echo $i; ?>"
               target="_blank" rel="noopener"
               class="<?php echo uiBtnClasses('info'); ?> flex-shrink-0">
              <i class="fas fa-eye mr-1"></i><span class="hidden sm:inline">ดูเอกสาร</span>
            </a>
          </div>
          <?php endfor; ?>
        </div>
      </div>

      <!-- หมายเหตุ -->
      <div class="bg-white border border-slate-200 rounded-md mb-3">
        <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-sticky-note mr-2 text-amber-500"></i>หมายเหตุการดำเนินการ</div>
        <div class="p-0">
          <?php
          $notes = array(
            array('badge'=>'bg-blue-600 text-white',   'label'=>'ผู้ตรวจสอบ',  'field'=>'inspector_note','icon'=>'fas fa-search'),
            array('badge'=>'bg-sky-600 text-white',    'label'=>'ผู้อนุมัติ',  'field'=>'approver_note', 'icon'=>'fas fa-user-check'),
            array('badge'=>'bg-purple-600 text-white', 'label'=>'ผู้ดำเนินการ','field'=>'operator_note', 'icon'=>'fas fa-tasks'),
            array('badge'=>'bg-red-600 text-white',    'label'=>'ส่งกลับแก้ไข','field'=>'revision_note', 'icon'=>'fas fa-undo'),
          );
          $hasNote = false;
          foreach ($notes as $n) { if (!empty($doc[$n['field']])) { $hasNote = true; break; } }
          if (!$hasNote):
          ?>
          <div class="px-3.5 py-3 text-slate-400 text-sm"><i class="fas fa-minus mr-1"></i>ยังไม่มีหมายเหตุ</div>
          <?php else: ?>
          <?php foreach ($notes as $n): ?>
          <?php if (!empty($doc[$n['field']])): ?>
          <div class="flex items-start gap-3 px-3.5 py-2.5 border-b border-slate-100 last:border-b-0">
            <span class="<?php echo $n['badge']; ?> text-xs rounded px-2 py-1 flex-shrink-0 mt-0.5">
              <i class="<?php echo $n['icon']; ?> mr-1"></i><?php echo $n['label']; ?>
            </span>
            <span class="text-[0.87rem]"><?php echo e($doc[$n['field']]); ?></span>
          </div>
          <?php endif; ?>
          <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

      <!-- ประวัติการดำเนินการ -->
      <div class="bg-white border border-slate-200 rounded-md mb-3">
        <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm flex items-center justify-between">
          <span><i class="fas fa-history mr-2 text-slate-500"></i>ประวัติการดำเนินการ</span>
          <?php if (!empty($logs)): ?><span class="bg-slate-500 text-white text-xs rounded px-1.5 py-0.5"><?php echo count($logs); ?> รายการ</span><?php endif; ?>
        </div>
        <div class="p-0">
          <?php if (empty($logs)): ?>
          <div class="px-3.5 py-3 text-slate-400 text-sm"><i class="fas fa-minus mr-1"></i>ยังไม่มีประวัติ</div>
          <?php else:
          $logIcons = array(
            'นำส่งเอกสาร'             => array('icon'=>'fas fa-paper-plane','color'=>'bg-slate-500'),
            'ตรวจสอบและส่งต่ออนุมัติ' => array('icon'=>'fas fa-check',      'color'=>'bg-blue-600'),
            'อนุมัติเอกสาร'           => array('icon'=>'fas fa-thumbs-up',  'color'=>'bg-green-600'),
            'bulk_approved'            => array('icon'=>'fas fa-check-double','color'=>'bg-green-600'),
            'ดำเนินการเสร็จสิ้น'      => array('icon'=>'fas fa-flag',        'color'=>'bg-green-600'),
            'ส่งกลับแก้ไข'            => array('icon'=>'fas fa-undo',        'color'=>'bg-red-600'),
            'แก้ไขและส่งใหม่'         => array('icon'=>'fas fa-redo',        'color'=>'bg-amber-500'),
          );
          foreach ($logs as $idx => $log):
            $li = isset($logIcons[$log['action']]) ? $logIcons[$log['action']] : array('icon'=>'fas fa-circle','color'=>'bg-slate-500');
          ?>
          <div class="flex items-start gap-3 px-3.5 py-2.5 border-b border-slate-100 last:border-b-0 <?php echo $idx % 2 == 0 ? '' : 'bg-slate-50'; ?>">
            <div class="w-[30px] h-[30px] rounded-full text-white flex items-center justify-center flex-shrink-0 text-xs mt-0.5 <?php echo $li['color']; ?>">
              <i class="<?php echo $li['icon']; ?>"></i>
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
            <div class="flex-shrink-0 text-slate-400 text-xs">#<?php echo $idx + 1; ?></div>
          </div>
          <?php endforeach; endif; ?>
        </div>
      </div>

    </div>

    <!-- ขวา: Action Panel -->
    <div class="lg:col-span-4">
    <div class="lg:sticky lg:top-4">

      <!-- สถานะปัจจุบัน -->
      <div class="bg-white border border-slate-200 rounded-md mb-3">
        <div class="<?php echo $st['header']; ?> text-white px-3.5 py-2.5 font-semibold text-sm">
          <i class="<?php echo $st['icon']; ?> mr-2"></i>สถานะปัจจุบัน
        </div>
        <div class="p-3.5">
          <div class="text-center py-2">
            <div class="font-bold text-lg"><?php echo docStatusLabel($doc['status']); ?></div>
            <?php if ($doc['updated_at'] && $doc['updated_at'] !== '0000-00-00 00:00:00'): ?>
            <div class="text-slate-500 text-sm mt-1">อัปเดตล่าสุด: <?php echo thaiDate($doc['updated_at']); ?></div>
            <?php endif; ?>
          </div>
          <div class="mt-3 pt-2 border-t border-slate-200">
            <?php
            $steps    = array('pending','inspecting','approving','operating','completed');
            $curIdx   = array_search($doc['status'], $steps);
            if ($doc['status'] === 'revision') $curIdx = -1;
            $stLabels = array('pending'=>'นำส่ง','inspecting'=>'ตรวจสอบ','approving'=>'อนุมัติ','operating'=>'ดำเนินการ','completed'=>'เสร็จสิ้น');
            foreach ($steps as $si => $step):
              $done    = ($curIdx !== false && $curIdx !== -1 && $si < $curIdx);
              $current = ($doc['status'] === $step);
              $cls     = $done ? 'bg-green-600' : ($current ? 'bg-blue-600' : 'bg-slate-100 border border-slate-300');
            ?>
            <div class="flex items-center gap-2 mb-1">
              <div class="w-[22px] h-[22px] rounded-full flex items-center justify-center flex-shrink-0 text-[0.65rem] <?php echo $cls; ?>">
                <?php if ($done): ?><i class="fas fa-check text-white"></i>
                <?php elseif ($current): ?><i class="fas fa-dot-circle text-white"></i>
                <?php else: ?><span class="text-slate-400"><?php echo $si+1; ?></span>
                <?php endif; ?>
              </div>
              <span class="text-[0.82rem] <?php echo $current ? 'font-bold text-[#1565c0]' : ($done ? 'text-green-600' : 'text-slate-400'); ?>">
                <?php echo $stLabels[$step]; ?>
              </span>
            </div>
            <?php endforeach; ?>
            <?php if ($doc['status'] === 'revision'): ?>
            <div class="flex items-center gap-2 mt-1">
              <div class="w-[22px] h-[22px] rounded-full flex items-center justify-center flex-shrink-0 bg-red-600">
                <i class="fas fa-undo text-white text-[0.6rem]"></i>
              </div>
              <span class="font-bold text-red-600 text-[0.82rem]">ส่งกลับแก้ไข</span>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Inspector form -->
      <?php if (userHasRole($user,'inspector') && in_array($doc['status'], array('pending','inspecting')) && canActionDocument($user, $doc)): ?>
      <div class="bg-white border border-slate-200 rounded-md mb-3">
        <div class="bg-blue-600 text-white px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-search mr-2"></i>ตรวจสอบเอกสาร</div>
        <div class="p-3.5">
          <form method="POST" action="?page=documents&action=inspect">
            <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
            <input type="hidden" name="doc_id" value="<?php echo $doc['id']; ?>">
            <div class="mb-2">
              <label class="block text-sm font-semibold text-slate-700 mb-1">เลขรับหนังสือ <span class="text-red-600">*</span></label>
              <input type="text" name="receive_number" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" required
                     value="<?php echo $doc['receive_number'] ? e($doc['receive_number']) : ''; ?>">
            </div>
            <div class="mb-3">
              <label class="block text-sm font-semibold text-slate-700 mb-1">หมายเหตุ</label>
              <textarea name="inspector_note" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" rows="2"><?php echo e($doc['inspector_note']); ?></textarea>
            </div>
            <div class="flex gap-2">
              <button type="submit" class="<?php echo uiBtnClasses('primary'); ?> flex-1">
                <i class="fas fa-check mr-1"></i>ส่งต่ออนุมัติ
              </button>
              <button type="button" class="<?php echo uiBtnClasses('outline-danger'); ?>" onclick="openModal('revisionModal')" title="ส่งกลับแก้ไข">
                <i class="fas fa-undo"></i>
              </button>
            </div>
          </form>
        </div>
      </div>
      <?php endif; ?>

      <!-- Approver form -->
      <?php if (userHasRole($user,'approver') && $doc['status'] === 'approving' && canActionDocument($user, $doc)): ?>
      <div class="bg-white border border-slate-200 rounded-md mb-3">
        <div class="bg-sky-600 text-white px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-user-check mr-2"></i>อนุมัติเอกสาร</div>
        <div class="p-3.5">
          <form method="POST" action="?page=documents&action=approve">
            <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
            <input type="hidden" name="doc_id" value="<?php echo $doc['id']; ?>">
            <div class="mb-3">
              <label class="block text-sm font-semibold text-slate-700 mb-1">หมายเหตุ</label>
              <textarea name="approver_note" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" rows="2"></textarea>
            </div>
            <div class="flex gap-2">
              <button type="submit" class="<?php echo uiBtnClasses('info'); ?> flex-1 font-semibold">
                <i class="fas fa-thumbs-up mr-1"></i>อนุมัติเอกสาร
              </button>
              <button type="button" class="<?php echo uiBtnClasses('outline-danger'); ?>" onclick="openModal('revisionModal')" title="ส่งกลับแก้ไข">
                <i class="fas fa-undo"></i>
              </button>
            </div>
          </form>
        </div>
      </div>
      <?php endif; ?>

      <!-- Operator form -->
      <?php if (userHasRole($user,'operator') && $doc['status'] === 'operating' && canActionDocument($user, $doc)): ?>
      <div class="bg-white border border-slate-200 rounded-md mb-3">
        <div class="bg-purple-700 text-white px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-tasks mr-2"></i>ดำเนินการเอกสาร</div>
        <div class="p-3.5">
          <form method="POST" action="?page=documents&action=operate">
            <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
            <input type="hidden" name="doc_id" value="<?php echo $doc['id']; ?>">
            <div class="mb-3">
              <label class="block text-sm font-semibold text-slate-700 mb-1">หมายเหตุ</label>
              <textarea name="operator_note" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" rows="2"></textarea>
            </div>
            <div class="flex gap-2">
              <button type="submit" class="<?php echo uiBtnClasses('purple'); ?> flex-1 font-semibold">
                <i class="fas fa-check-double mr-1"></i>ดำเนินการเสร็จสิ้น
              </button>
              <button type="button" class="<?php echo uiBtnClasses('outline-danger'); ?>" onclick="openModal('revisionModal')" title="ส่งกลับแก้ไข">
                <i class="fas fa-undo"></i>
              </button>
            </div>
          </form>
        </div>
      </div>
      <?php endif; ?>

      <!-- Submitter: resubmit -->
      <?php if (userHasRole($user,'submitter') && $doc['status'] === 'revision' && $doc['submitted_by'] == $user['id']): ?>
      <div class="bg-white border border-slate-200 rounded-md mb-3">
        <div class="bg-amber-500 text-white px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-redo mr-2"></i>แก้ไขและส่งใหม่</div>
        <div class="p-3.5">
          <p class="text-slate-500 text-sm mb-2">เอกสารถูกส่งกลับ กรุณาแก้ไขและส่งใหม่</p>
          <a href="?page=documents&action=edit&id=<?php echo $doc['id']; ?>" class="<?php echo uiBtnClasses('warning'); ?> w-full font-semibold">
            <i class="fas fa-edit mr-1"></i>แก้ไขเอกสาร
          </a>
        </div>
      </div>
      <?php endif; ?>

      <?php if ($doc['status'] === 'completed'): ?>
      <div class="bg-white border border-slate-200 rounded-md mb-3">
        <div class="bg-green-600 text-white px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-check-circle mr-2"></i>ดำเนินการเสร็จสิ้น</div>
        <div class="p-3.5 text-center py-6">
          <i class="fas fa-check-circle text-green-600 text-4xl"></i>
          <p class="mt-2 font-bold text-green-600 mb-0">เอกสารนี้ดำเนินการเสร็จสิ้นแล้ว</p>
          <?php if ($doc['operated_at']): ?>
          <p class="text-slate-500 text-sm mt-1"><?php echo thaiDate($doc['operated_at']); ?></p>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>

      <a href="?page=documents" class="<?php echo uiBtnClasses('outline'); ?> w-full">
        <i class="fas fa-arrow-left mr-1"></i>กลับรายการเอกสาร
      </a>

    </div>
    </div>

  </div>
</main>

<!-- Revision Modal (replaces Bootstrap modal) -->
<?php if (userHasAnyRole($user, array('inspector','approver','operator')) && canActionDocument($user, $doc)): ?>
<div id="revisionModal" data-modal class="hidden fixed inset-0 z-[1060] flex items-center justify-center p-4">
  <div class="absolute inset-0 bg-black/50" data-modal-backdrop></div>
  <div class="relative bg-white rounded-lg shadow-2xl w-full max-w-md">
    <div class="bg-red-600 text-white rounded-t-lg px-4 py-2.5 flex items-center justify-between">
      <h6 class="font-bold m-0 text-sm"><i class="fas fa-undo mr-1"></i>ส่งกลับให้แก้ไข</h6>
      <button type="button" class="text-white/80 hover:text-white" onclick="closeModal('revisionModal')"><i class="fas fa-times"></i></button>
    </div>
    <div class="p-4">
      <label class="block font-semibold text-sm text-slate-700 mb-1">เหตุผลที่ส่งกลับ <span class="text-red-600">*</span></label>
      <textarea id="revisionNoteInput" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" rows="3"
                placeholder="ระบุเหตุผลที่ส่งกลับ..."></textarea>
    </div>
    <div class="px-4 py-3 border-t border-slate-200 flex justify-end gap-2">
      <button type="button" class="<?php echo uiBtnClasses('outline'); ?>" onclick="closeModal('revisionModal')">ยกเลิก</button>
      <button type="button" class="<?php echo uiBtnClasses('danger'); ?>" id="revisionSubmitBtn" onclick="submitRevision()">
        <i class="fas fa-undo mr-1"></i>ส่งกลับแก้ไข
      </button>
    </div>
  </div>
</div>
<?php endif; ?>

<?php
$docId  = $doc['id'];
$csrf   = Session::getCsrf();
$canRev = userHasAnyRole($user, array('inspector','approver','operator')) && canActionDocument($user, $doc) ? 'true' : 'false';

$extraJs = '<script>
function submitRevision() {
  var note = document.getElementById("revisionNoteInput").value.trim();
  if (!note) {
    showToast("warning", "กรุณากรอกเหตุผล");
    return;
  }
  var btn = document.getElementById("revisionSubmitBtn");
  btn.disabled = true;
  btn.innerHTML = "<i class=\"fas fa-spinner fa-spin mr-1\"></i>กำลังส่ง...";

  ajaxPost("?page=documents&action=revision", {
    csrf_token:    "' . $csrf . '",
    doc_id:        "' . $docId . '",
    revision_note: note
  }, function(ok, text) {
    btn.disabled = false;
    btn.innerHTML = "<i class=\"fas fa-undo mr-1\"></i>ส่งกลับแก้ไข";
    try {
      var res = JSON.parse(text);
      if (ok && res.success) {
        closeModal("revisionModal");
        showToast("success", "ส่งกลับแก้ไขสำเร็จ");
        setTimeout(function() { window.location.href = "?page=documents"; }, 1500);
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
        if (badge) { badge.innerHTML = "<span class=\"inline-flex items-center rounded px-2 py-0.5 text-xs font-medium " + data.badge + "\">" + data.label + "</span>"; }
      }
    } catch(e) {}
  });
}, 60000);
</script>';
?>
