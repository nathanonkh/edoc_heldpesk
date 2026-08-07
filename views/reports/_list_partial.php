<?php
// Partial: summary cards + detail table + pagination
// ตัวแปรที่ต้องมี: $summary, $details, $totalItems, $totalPages, $currentPageNum, $filters
$statusMap = array();
foreach ($summary as $row) { $statusMap[$row['status']] = intval($row['cnt']); }

$sc = array(
  'pending'    => array('label'=>'รอตรวจสอบ',  'icon'=>'fas fa-clock',       'color'=>'amber'),
  'inspecting' => array('label'=>'ตรวจสอบ',     'icon'=>'fas fa-search',      'color'=>'blue'),
  'approving'  => array('label'=>'รออนุมัติ',   'icon'=>'fas fa-user-check',  'color'=>'sky'),
  'operating'  => array('label'=>'รอดำเนินการ', 'icon'=>'fas fa-tasks',       'color'=>'purple'),
  'revision'   => array('label'=>'ส่งกลับ',     'icon'=>'fas fa-undo',        'color'=>'red'),
  'completed'  => array('label'=>'เสร็จสิ้น',   'icon'=>'fas fa-check-circle','color'=>'green'),
);
$scIconColor = array(
  'amber'=>'text-amber-500','blue'=>'text-blue-600','sky'=>'text-sky-600',
  'purple'=>'text-purple-600','red'=>'text-red-600','green'=>'text-green-600',
);
?>
<div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3 mb-4" id="reportSummaryCards">
  <?php foreach ($sc as $st => $cfg): ?>
  <div class="bg-white border border-slate-200 rounded-md p-3.5 text-center">
    <div class="text-2xl mb-1 <?php echo $scIconColor[$cfg['color']]; ?>"><i class="<?php echo $cfg['icon']; ?>"></i></div>
    <div class="text-2xl font-bold leading-tight"><?php echo isset($statusMap[$st]) ? $statusMap[$st] : 0; ?></div>
    <div class="text-slate-500 text-xs mt-0.5"><?php echo $cfg['label']; ?></div>
  </div>
  <?php endforeach; ?>
</div>

<div class="bg-white border border-slate-200 rounded-md overflow-hidden">
  <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm flex items-center justify-between">
    <span><i class="fas fa-table mr-2 text-[#1565c0]"></i>รายละเอียด <span class="bg-slate-500 text-white text-xs rounded px-1.5 py-0.5 ml-1"><?php echo $totalItems; ?></span></span>
    <button class="<?php echo uiBtnClasses('success'); ?>" onclick="exportCsv()">
      <i class="fas fa-file-excel mr-1"></i>Export CSV
    </button>
  </div>
  <div class="overflow-x-auto">
    <table class="text-sm border-collapse w-full min-w-[640px]" id="reportTable">
      <thead>
        <tr>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left font-semibold">#</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left font-semibold">วันที่</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left font-semibold">เลขที่เอกสาร</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left font-semibold">สหกรณ์</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left font-semibold">ประเภท</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left font-semibold">ปีบัญชี</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left font-semibold">สำนักงาน</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left font-semibold">ผู้นำส่ง</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left font-semibold">เลขรับ</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left font-semibold">สถานะ</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($details as $idx => $doc): ?>
        <tr class="hover:bg-blue-50/50">
          <td class="border border-slate-200 px-2.5 py-1.5"><?php echo ($currentPageNum - 1) * 10 + $idx + 1; ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5 whitespace-nowrap"><?php echo thaiDate($doc['created_at'], true); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5"><code class="tag text-[0.78rem]"><?php echo e($doc['ticket_code']); ?></code></td>
          <td class="border border-slate-200 px-2.5 py-1.5"><?php echo e($doc['cooperative_name']); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5"><?php echo e($doc['cooperative_type_name']); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5"><?php echo e($doc['fiscal_year']); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5 text-[0.78rem]"><?php echo e($doc['office_name']); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5"><?php echo e($doc['submitter_name']); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5"><?php echo e($doc['receive_number']); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5"><?php echo uiBadge(docStatusLabel($doc['status']), docStatusBadgeClass($doc['status'])); ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($details)): ?>
        <tr><td colspan="10" class="text-center text-slate-400 py-8 border border-slate-200">
          <i class="fas fa-inbox block text-3xl mb-2 text-slate-300"></i>ไม่พบข้อมูล
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
