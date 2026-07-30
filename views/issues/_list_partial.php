<?php
// Partial: ตารางแจ้งปัญหา + pagination
// ตัวแปรที่ต้องมี: $issues, $totalItems, $totalPages, $currentPageNum, $filters
?>
<div class="page-card" id="issueListCard">
  <div class="page-card-header">
    <span><i class="fas fa-list me-2 text-primary"></i>ผลการค้นหา <span class="badge bg-secondary ms-1"><?php echo $totalItems; ?></span></span>
  </div>

  <!-- Desktop table -->
  <div class="table-edms-wrap">
    <table class="table-edms">
      <thead>
        <tr>
          <th>วันที่</th><th>เลขที่แจ้ง</th><th>สหกรณ์</th><th>ประเภทปัญหา</th>
          <th>โปรแกรม</th><th>ชื่อเรื่อง</th><th>สำนักงาน</th>
          <th>สถานะ</th><th style="text-align:center;min-width:80px;">ดำเนินการ</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($issues as $iss): ?>
        <tr>
          <td style="white-space:nowrap;"><?php echo thaiDate($iss['created_at'],true); ?></td>
          <td><code><?php echo e($iss['ticket_code']); ?></code></td>
          <td><?php echo e($iss['cooperative_name']); ?></td>
          <td><?php echo e(issueTypeLabel($iss['issue_type'])); ?></td>
          <td><?php echo e(programLabel($iss['program_name'])); ?></td>
          <td><?php echo e($iss['title']); ?></td>
          <td style="font-size:0.78rem;"><?php echo e($iss['office_name']); ?></td>
          <td><span class="badge <?php echo issueStatusBadgeClass($iss['status']); ?>"><?php echo issueStatusLabel($iss['status'], !empty($iss['handled_by_central'])); ?></span></td>
          <td class="text-center">
            <a href="?page=issues&action=detail&id=<?php echo $iss['id']; ?>" class="btn btn-detail btn-sm">
              <i class="fas fa-eye me-1"></i>ดู
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($issues)): ?>
        <tr><td colspan="9" class="text-center text-muted py-4">
          <i class="fas fa-inbox d-block fs-2 mb-2 text-secondary"></i>ไม่พบรายการแจ้งปัญหา
        </td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Mobile cards -->
  <div class="mobile-list p-2">
    <?php foreach ($issues as $iss): ?>
    <div class="mobile-card">
      <div class="d-flex justify-content-between align-items-start mb-1">
        <code><?php echo e($iss['ticket_code']); ?></code>
        <span class="badge <?php echo issueStatusBadgeClass($iss['status']); ?>"><?php echo issueStatusLabel($iss['status'], !empty($iss['handled_by_central'])); ?></span>
      </div>
      <div class="mobile-card-title"><?php echo e($iss['title']); ?></div>
      <div class="mobile-card-sub mb-2"><?php echo e($iss['cooperative_name']); ?> | <?php echo thaiDate($iss['created_at'],true); ?></div>
      <a href="?page=issues&action=detail&id=<?php echo $iss['id']; ?>" class="btn btn-detail btn-sm w-100">
        <i class="fas fa-eye me-1"></i>ดูรายละเอียด
      </a>
    </div>
    <?php endforeach; ?>
    <?php if (empty($issues)): ?>
    <div class="text-center text-muted py-4">
      <i class="fas fa-inbox d-block fs-2 mb-2 text-secondary"></i>ไม่พบรายการแจ้งปัญหา
    </div>
    <?php endif; ?>
  </div>
</div>

<?php
$paginationParams = array(
  'page'      => 'issues',
  'status'    => $filters['status'],
  'keyword'   => $filters['keyword'],
  'date_from' => $filters['date_from'],
  'date_to'   => $filters['date_to'],
);
include 'views/layout/pagination.php';
?>
