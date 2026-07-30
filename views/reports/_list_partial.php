<?php
// Partial: summary cards + detail table + pagination
// ตัวแปรที่ต้องมี: $summary, $details, $totalItems, $totalPages, $currentPageNum, $filters
$statusMap = array();
foreach ($summary as $row) { $statusMap[$row['status']] = intval($row['cnt']); }

$sc = array(
  'pending'    => array('label'=>'รอตรวจสอบ',  'icon'=>'fas fa-clock',       'color'=>'warning'),
  'inspecting' => array('label'=>'ตรวจสอบ',     'icon'=>'fas fa-search',      'color'=>'primary'),
  'approving'  => array('label'=>'รออนุมัติ',   'icon'=>'fas fa-user-check',  'color'=>'info'),
  'operating'  => array('label'=>'รอดำเนินการ', 'icon'=>'fas fa-tasks',       'color'=>'purple'),
  'revision'   => array('label'=>'ส่งกลับ',     'icon'=>'fas fa-undo',        'color'=>'danger'),
  'completed'  => array('label'=>'เสร็จสิ้น',   'icon'=>'fas fa-check-circle','color'=>'success'),
);
?>
<div class="row g-3 mb-3" id="reportSummaryCards">
  <?php foreach ($sc as $st => $cfg): ?>
  <div class="col-6 col-md-4 col-xl-2">
    <div class="stat-card stat-card-<?php echo $cfg['color']; ?>">
      <div class="stat-icon"><i class="<?php echo $cfg['icon']; ?>"></i></div>
      <div class="stat-num"><?php echo isset($statusMap[$st]) ? $statusMap[$st] : 0; ?></div>
      <div class="stat-lbl"><?php echo $cfg['label']; ?></div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<div class="page-card">
  <div class="page-card-header">
    <span><i class="fas fa-table me-2 text-primary"></i>รายละเอียด <span class="badge bg-secondary ms-1"><?php echo $totalItems; ?></span></span>
    <button class="btn btn-success btn-sm" onclick="exportCsv()">
      <i class="fas fa-file-excel me-1"></i>Export CSV
    </button>
  </div>
  <div class="table-edms-wrap">
    <table class="table-edms" id="reportTable">
      <thead>
        <tr><th>#</th><th>วันที่</th><th>เลขที่เอกสาร</th><th>สหกรณ์</th><th>ประเภท</th><th>ปีบัญชี</th><th>สำนักงาน</th><th>ผู้นำส่ง</th><th>เลขรับ</th><th>สถานะ</th></tr>
      </thead>
      <tbody>
        <?php foreach ($details as $idx => $doc): ?>
        <tr>
          <td><?php echo ($currentPageNum - 1) * 10 + $idx + 1; ?></td>
          <td style="white-space:nowrap;"><?php echo thaiDate($doc['created_at'], true); ?></td>
          <td><code style="font-size:0.78rem;"><?php echo e($doc['ticket_code']); ?></code></td>
          <td><?php echo e($doc['cooperative_name']); ?></td>
          <td><?php echo e($doc['cooperative_type_name']); ?></td>
          <td><?php echo e($doc['fiscal_year']); ?></td>
          <td style="font-size:0.78rem;"><?php echo e($doc['office_name']); ?></td>
          <td><?php echo e($doc['submitter_name']); ?></td>
          <td><?php echo e($doc['receive_number']); ?></td>
          <td><span class="badge <?php echo docStatusBadgeClass($doc['status']); ?>"><?php echo docStatusLabel($doc['status']); ?></span></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($details)): ?>
        <tr><td colspan="10" class="text-center text-muted py-4">
          <i class="fas fa-inbox d-block fs-2 mb-2 text-secondary"></i>ไม่พบข้อมูล
        </td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php
$paginationParams = array(
  'page'        => 'reports',
  'status'      => $filters['status'],
  'fiscal_year' => $filters['fiscal_year'],
  'office_name' => $filters['office_name'],
  'date_from'   => $filters['date_from'],
  'date_to'     => $filters['date_to'],
);
include 'views/layout/pagination.php';
?>
