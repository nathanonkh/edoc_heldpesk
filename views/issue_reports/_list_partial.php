<?php
// Partial: summary cards + detail table + pagination
// ตัวแปรที่ต้องมี: $summary, $details, $totalItems, $totalPages, $currentPageNum, $filters
$statusMap = array();
foreach ($summary as $row) { $statusMap[$row['status']] = intval($row['cnt']); }

$sc = array(
  'pending'      => array('label'=>'รอตรวจสอบ',     'icon'=>'fas fa-clock',        'color'=>'warning'),
  'sent_central' => array('label'=>'ส่งส่วนกลาง',    'icon'=>'fas fa-paper-plane',  'color'=>'purple'),
  'in_progress'  => array('label'=>'กำลังดำเนินการ', 'icon'=>'fas fa-cogs',         'color'=>'primary'),
  'completed'    => array('label'=>'สำเร็จ',         'icon'=>'fas fa-check-circle', 'color'=>'success'),
);
?>
<div class="row g-3 mb-3" id="issueReportSummaryCards">
  <?php foreach ($sc as $st => $cfg): ?>
  <div class="col-6 col-md-3">
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
    <button class="btn btn-success btn-sm" onclick="exportIssueReportCsv()">
      <i class="fas fa-file-excel me-1"></i>Export CSV
    </button>
  </div>
  <div class="table-edms-wrap">
    <table class="table-edms" id="issueReportTable">
      <thead>
        <tr><th>#</th><th>วันที่</th><th>เลขที่แจ้ง</th><th>สหกรณ์</th><th>ประเภทปัญหา</th><th>โปรแกรม</th><th>สำนักงาน</th><th>ผู้แจ้ง</th><th>สถานะ</th></tr>
      </thead>
      <tbody>
        <?php foreach ($details as $idx => $iss): ?>
        <tr>
          <td><?php echo ($currentPageNum - 1) * 10 + $idx + 1; ?></td>
          <td style="white-space:nowrap;"><?php echo thaiDate($iss['created_at'], true); ?></td>
          <td><code style="font-size:0.78rem;"><?php echo e($iss['ticket_code']); ?></code></td>
          <td><?php echo e($iss['cooperative_name']); ?></td>
          <td><?php echo e(issueTypeLabel($iss['issue_type'])); ?></td>
          <td><?php echo e(programLabel($iss['program_name'])); ?></td>
          <td style="font-size:0.78rem;"><?php echo e($iss['office_name']); ?></td>
          <td><?php echo e($iss['submitter_name']); ?></td>
          <td><span class="badge <?php echo issueStatusBadgeClass($iss['status']); ?>"><?php echo issueStatusLabel($iss['status'], !empty($iss['handled_by_central'])); ?></span></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($details)): ?>
        <tr><td colspan="9" class="text-center text-muted py-4">
          <i class="fas fa-inbox d-block fs-2 mb-2 text-secondary"></i>ไม่พบข้อมูล
        </td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php
$paginationParams = array(
  'page'         => 'issue_reports',
  'status'       => $filters['status'],
  'office_name'  => $filters['office_name'],
  'issue_type'   => $filters['issue_type'],
  'program_name' => $filters['program_name'],
  'date_from'    => $filters['date_from'],
  'date_to'      => $filters['date_to'],
);
include 'views/layout/pagination.php';
?>
