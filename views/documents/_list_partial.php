<?php
// Partial: แสดงตารางเอกสาร + pagination
// ตัวแปรที่ต้องมี: $documents, $totalItems, $totalPages, $currentPageNum, $filters
?>
<div class="page-card" id="docListCard">
  <div class="page-card-header">
    <span><i class="fas fa-list me-2 text-primary"></i>ผลการค้นหา <span class="badge bg-secondary ms-1"><?php echo $totalItems; ?></span></span>
    <?php if (Auth::hasRole('approver')): ?>
    <button type="button" class="btn btn-info btn-sm" onclick="submitBulkApprove()">
      <i class="fas fa-check-double me-1"></i>อนุมัติที่เลือก
    </button>
    <?php endif; ?>
  </div>

  <!-- Desktop table -->
  <div class="table-edms-wrap">
    <table class="table-edms">
      <thead>
        <tr>
          <?php if (Auth::hasRole('approver')): ?>
          <th style="width:36px;"><input type="checkbox" id="select-all"></th>
          <?php endif; ?>
          <th>วันที่</th><th>เลขที่เอกสาร</th><th>เลขที่หนังสือ</th><th>เลขรับหนังสือ</th>
          <th>สหกรณ์</th><th>ประเภท</th><th>ปีบัญชี</th><th>สำนักงาน</th>
          <th>สถานะ</th><th style="text-align:center;min-width:100px;">ดำเนินการ</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($documents as $doc): ?>
        <tr>
          <?php if (Auth::hasRole('approver')): ?>
          <td class="text-center">
            <?php if ($doc['status']==='approving'): ?>
            <input type="checkbox" name="doc_ids[]" value="<?php echo $doc['id']; ?>" class="doc-checkbox" form="bulkApproveForm">
            <?php endif; ?>
          </td>
          <?php endif; ?>
          <td style="white-space:nowrap;"><?php echo thaiDate($doc['created_at'],true); ?></td>
          <td><code><?php echo e($doc['ticket_code']); ?></code></td>
          <td><?php echo e($doc['document_number']); ?></td>
          <td><?php echo e($doc['receive_number']); ?></td>
          <td><?php echo e($doc['cooperative_name']); ?></td>
          <td><?php echo e($doc['cooperative_type_name']); ?></td>
          <td><?php echo e($doc['fiscal_year']); ?></td>
          <td style="font-size:0.78rem;"><?php echo e($doc['office_name']); ?></td>
          <td><span class="badge <?php echo docStatusBadgeClass($doc['status']); ?>"><?php echo docStatusLabel($doc['status']); ?></span></td>
          <td class="text-center">
            <a href="?page=documents&action=detail&id=<?php echo $doc['id']; ?>" class="btn btn-detail btn-sm">
              <i class="fas fa-eye me-1"></i>ดู
            </a>
            <?php if (Auth::hasRole('submitter') && $doc['status']==='revision'): ?>
            <a href="?page=documents&action=edit&id=<?php echo $doc['id']; ?>" class="btn btn-edit-doc btn-sm ms-1">
              <i class="fas fa-edit"></i>
            </a>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($documents)): ?>
        <tr><td colspan="11" class="text-center text-muted py-4">
          <i class="fas fa-inbox d-block fs-2 mb-2 text-secondary"></i>ไม่พบรายการเอกสาร
        </td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Mobile cards -->
  <div class="mobile-list p-2">
    <?php foreach ($documents as $doc): ?>
    <div class="mobile-card">
      <div class="d-flex justify-content-between align-items-start mb-1">
        <code><?php echo e($doc['ticket_code']); ?></code>
        <span class="badge <?php echo docStatusBadgeClass($doc['status']); ?>"><?php echo docStatusLabel($doc['status']); ?></span>
      </div>
      <div class="mobile-card-title"><?php echo e($doc['cooperative_name']); ?></div>
      <div class="mobile-card-sub mb-2">ปีบัญชี <?php echo e($doc['fiscal_year']); ?> | <?php echo thaiDate($doc['created_at'],true); ?></div>
      <div class="d-flex gap-1">
        <a href="?page=documents&action=detail&id=<?php echo $doc['id']; ?>" class="btn btn-detail btn-sm flex-grow-1">
          <i class="fas fa-eye me-1"></i>ดูรายละเอียด
        </a>
        <?php if (Auth::hasRole('submitter') && $doc['status']==='revision'): ?>
        <a href="?page=documents&action=edit&id=<?php echo $doc['id']; ?>" class="btn btn-edit-doc btn-sm">
          <i class="fas fa-edit"></i>
        </a>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
    <?php if (empty($documents)): ?>
    <div class="text-center text-muted py-4">
      <i class="fas fa-inbox d-block fs-2 mb-2 text-secondary"></i>ไม่พบรายการเอกสาร
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
