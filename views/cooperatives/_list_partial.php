<?php
// Partial: ตารางสหกรณ์ + pagination
// ตัวแปรที่ต้องมี: $cooperatives, $totalItems, $totalPages, $currentPageNum
?>
<div class="page-card">
  <div class="page-card-header">
    <span><i class="fas fa-list me-2 text-primary"></i>รายชื่อสหกรณ์ <span class="badge bg-secondary ms-1"><?php echo $totalItems; ?></span></span>
  </div>
  <div class="table-edms-wrap">
    <table class="table-edms">
      <thead>
        <tr>
          <th style="width:40px;">#</th><th>รหัส</th><th>ชื่อสหกรณ์</th><th>ประเภท</th>
          <th>จังหวัด</th><th>สิ้นปีบัญชี</th><th>สำนักงาน</th><th>สถานะ</th>
          <th style="text-align:center;width:100px;">ดำเนินการ</th>
        </tr>
      </thead>
      <tbody>
        <?php $startNo = ($currentPageNum - 1) * 10 + 1; foreach ($cooperatives as $idx => $coop): ?>
        <tr>
          <td><?php echo $startNo + $idx; ?></td>
          <td><code><?php echo e($coop['code']); ?></code></td>
          <td class="fw-semibold"><?php echo e($coop['name']); ?></td>
          <td><?php echo e($coop['type_name']); ?></td>
          <td><?php echo e($coop['province']); ?></td>
          <td><?php echo formatFiscalYear($coop['fiscal_year']); ?></td>
          <td style="font-size:0.78rem;"><?php echo e($coop['office_name']); ?></td>
          <td><span class="badge <?php echo getCooperativeStatusBadge($coop['status']); ?>"><?php echo getCooperativeStatusLabel($coop['status']); ?></span></td>
          <td class="text-center">
            <a href="?page=cooperatives&action=edit&id=<?php echo $coop['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
            <button class="btn btn-sm btn-outline-danger ms-1"
              onclick="confirmDelete('?page=cooperatives&action=delete&id=<?php echo $coop['id']; ?>','<?php echo e($coop['name']); ?>')">
              <i class="fas fa-trash"></i>
            </button>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($cooperatives)): ?>
        <tr><td colspan="9" class="text-center text-muted py-4">
          <i class="fas fa-inbox d-block fs-2 mb-2 text-secondary"></i>ไม่พบรายการ
        </td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <div class="mobile-list p-2">
    <?php foreach ($cooperatives as $coop): ?>
    <div class="mobile-card">
      <div class="d-flex justify-content-between mb-1">
        <code><?php echo e($coop['code']); ?></code>
        <span class="badge <?php echo getCooperativeStatusBadge($coop['status']); ?>"><?php echo getCooperativeStatusLabel($coop['status']); ?></span>
      </div>
      <div class="mobile-card-title"><?php echo e($coop['name']); ?></div>
      <div class="mobile-card-sub mb-2"><?php echo e($coop['type_name']); ?> | <?php echo e($coop['province']); ?></div>
      <div class="d-flex gap-1">
        <a href="?page=cooperatives&action=edit&id=<?php echo $coop['id']; ?>" class="btn btn-outline-primary btn-sm flex-grow-1"><i class="fas fa-edit me-1"></i>แก้ไข</a>
        <button class="btn btn-outline-danger btn-sm"
          onclick="confirmDelete('?page=cooperatives&action=delete&id=<?php echo $coop['id']; ?>','<?php echo e($coop['name']); ?>')">
          <i class="fas fa-trash"></i>
        </button>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<?php
$paginationParams = array(
  'page'          => 'cooperatives',
  'keyword'       => isset($keyword)      ? $keyword      : '',
  'filter_type'   => isset($filterType)   ? $filterType   : '',
  'filter_office' => isset($filterOffice) ? $filterOffice : '',
  'filter_status' => isset($filterStatus) ? $filterStatus : '',
);
include 'views/layout/pagination.php';
?>
