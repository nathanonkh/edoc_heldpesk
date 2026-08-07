<?php
// Partial: แสดงตารางเอกสาร + pagination
// ตัวแปรที่ต้องมี: $documents, $totalItems, $totalPages, $currentPageNum, $filters
?>
<div class="bg-white border border-slate-200 rounded-md overflow-hidden" id="docListCard">
  <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm flex items-center justify-between flex-wrap gap-2">
    <span><i class="fas fa-list mr-2 text-[#1565c0]"></i>ผลการค้นหา <span class="bg-slate-500 text-white text-xs rounded px-1.5 py-0.5 ml-1"><?php echo $totalItems; ?></span></span>
    <?php if (Auth::hasRole('approver')): ?>
    <button type="button" class="<?php echo uiBtnClasses('info'); ?>" onclick="submitBulkApprove()">
      <i class="fas fa-check-double mr-1"></i>อนุมัติที่เลือก
    </button>
    <?php endif; ?>
  </div>

  <!-- Desktop table -->
  <div class="hidden md:block overflow-x-auto">
    <table class="text-sm border-collapse w-full min-w-[640px]">
      <thead>
        <tr>
          <?php if (Auth::hasRole('approver')): ?>
          <th class="w-9 bg-slate-100 border border-slate-300 px-2.5 py-2"><input type="checkbox" id="select-all"></th>
          <?php endif; ?>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left whitespace-nowrap font-semibold">วันที่</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left whitespace-nowrap font-semibold">เลขที่เอกสาร</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left whitespace-nowrap font-semibold">เลขที่หนังสือ</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left whitespace-nowrap font-semibold">เลขรับหนังสือ</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left whitespace-nowrap font-semibold">สหกรณ์</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left whitespace-nowrap font-semibold">ประเภท</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left whitespace-nowrap font-semibold">ปีบัญชี</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left whitespace-nowrap font-semibold">สำนักงาน</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left whitespace-nowrap font-semibold">สถานะ</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-center whitespace-nowrap font-semibold min-w-[100px]">ดำเนินการ</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($documents as $doc): ?>
        <tr class="hover:bg-blue-50/50">
          <?php if (Auth::hasRole('approver')): ?>
          <td class="border border-slate-200 px-2.5 py-1.5 text-center">
            <?php if ($doc['status']==='approving'): ?>
            <input type="checkbox" name="doc_ids[]" value="<?php echo $doc['id']; ?>" class="doc-checkbox" form="bulkApproveForm">
            <?php endif; ?>
          </td>
          <?php endif; ?>
          <td class="border border-slate-200 px-2.5 py-1.5 whitespace-nowrap"><?php echo thaiDate($doc['created_at'],true); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5"><code class="tag"><?php echo e($doc['ticket_code']); ?></code></td>
          <td class="border border-slate-200 px-2.5 py-1.5"><?php echo e($doc['document_number']); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5"><?php echo e($doc['receive_number']); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5"><?php echo e($doc['cooperative_name']); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5"><?php echo e($doc['cooperative_type_name']); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5"><?php echo e($doc['fiscal_year']); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5 text-xs"><?php echo e($doc['office_name']); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5"><?php echo uiBadge(docStatusLabel($doc['status']), docStatusBadgeClass($doc['status'])); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5 text-center whitespace-nowrap">
            <a href="?page=documents&action=detail&id=<?php echo $doc['id']; ?>" class="<?php echo uiBtnClasses('warning'); ?>">
              <i class="fas fa-eye mr-1"></i>ดู
            </a>
            <?php if (Auth::hasRole('submitter') && $doc['status']==='revision'): ?>
            <a href="?page=documents&action=edit&id=<?php echo $doc['id']; ?>" class="<?php echo uiBtnClasses('success'); ?> ml-1">
              <i class="fas fa-edit"></i>
            </a>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($documents)): ?>
        <tr><td colspan="11" class="text-center text-slate-400 py-8 border border-slate-200">
          <i class="fas fa-inbox block text-3xl mb-2 text-slate-300"></i>ไม่พบรายการเอกสาร
        </td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Mobile cards -->
  <div class="md:hidden p-2">
    <?php foreach ($documents as $doc): ?>
    <div class="bg-white border border-slate-200 rounded-md px-3 py-2.5 mb-2">
      <div class="flex justify-between items-start mb-1">
        <code class="tag"><?php echo e($doc['ticket_code']); ?></code>
        <?php echo uiBadge(docStatusLabel($doc['status']), docStatusBadgeClass($doc['status'])); ?>
      </div>
      <div class="font-semibold text-sm"><?php echo e($doc['cooperative_name']); ?></div>
      <div class="text-slate-500 text-xs mb-2">ปีบัญชี <?php echo e($doc['fiscal_year']); ?> | <?php echo thaiDate($doc['created_at'],true); ?></div>
      <div class="flex gap-1">
        <a href="?page=documents&action=detail&id=<?php echo $doc['id']; ?>" class="<?php echo uiBtnClasses('warning'); ?> flex-1">
          <i class="fas fa-eye mr-1"></i>ดูรายละเอียด
        </a>
        <?php if (Auth::hasRole('submitter') && $doc['status']==='revision'): ?>
        <a href="?page=documents&action=edit&id=<?php echo $doc['id']; ?>" class="<?php echo uiBtnClasses('success'); ?>">
          <i class="fas fa-edit"></i>
        </a>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
    <?php if (empty($documents)): ?>
    <div class="text-center text-slate-400 py-8">
      <i class="fas fa-inbox block text-3xl mb-2 text-slate-300"></i>ไม่พบรายการเอกสาร
    </div>
    <?php endif; ?>
  </div>
</div>

<?php
$paginationParams = array(
  'page'      => 'documents',
  'status'    => $filters['status'],
  'keyword'   => $filters['keyword'],
  'date_from' => $filters['date_from'],
  'date_to'   => $filters['date_to'],
);
include 'views/layout/pagination.php';
?>
