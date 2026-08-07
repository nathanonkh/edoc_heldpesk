<?php
// Partial: ตารางแจ้งปัญหา + pagination
// ตัวแปรที่ต้องมี: $issues, $totalItems, $totalPages, $currentPageNum, $filters
?>
<div class="bg-white border border-slate-200 rounded-md overflow-hidden" id="issueListCard">
  <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm">
    <i class="fas fa-list mr-2 text-[#1565c0]"></i>ผลการค้นหา <span class="bg-slate-500 text-white text-xs rounded px-1.5 py-0.5 ml-1"><?php echo $totalItems; ?></span>
  </div>

  <!-- Desktop table -->
  <div class="hidden md:block overflow-x-auto">
    <table class="text-sm border-collapse w-full min-w-[640px]">
      <thead>
        <tr>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left font-semibold">วันที่</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left font-semibold">เลขที่แจ้ง</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left font-semibold">สหกรณ์</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left font-semibold">ประเภทปัญหา</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left font-semibold">โปรแกรม</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left font-semibold">ชื่อเรื่อง</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left font-semibold">สำนักงาน</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left font-semibold">สถานะ</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-center font-semibold min-w-[80px]">ดำเนินการ</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($issues as $iss): ?>
        <tr class="hover:bg-blue-50/50">
          <td class="border border-slate-200 px-2.5 py-1.5 whitespace-nowrap"><?php echo thaiDate($iss['created_at'],true); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5"><code class="tag"><?php echo e($iss['ticket_code']); ?></code></td>
          <td class="border border-slate-200 px-2.5 py-1.5"><?php echo e($iss['cooperative_name']); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5"><?php echo e(issueTypeLabel($iss['issue_type'])); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5"><?php echo e(programLabel($iss['program_name'])); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5"><?php echo e($iss['title']); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5 text-[0.78rem]"><?php echo e($iss['office_name']); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5"><?php echo uiBadge(issueStatusLabel($iss['status'], !empty($iss['handled_by_central'])), issueStatusBadgeClass($iss['status'])); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5 text-center">
            <a href="?page=issues&action=detail&id=<?php echo $iss['id']; ?>" class="<?php echo uiBtnClasses('warning'); ?>">
              <i class="fas fa-eye mr-1"></i>ดู
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($issues)): ?>
        <tr><td colspan="9" class="text-center text-slate-400 py-8 border border-slate-200">
          <i class="fas fa-inbox block text-3xl mb-2 text-slate-300"></i>ไม่พบรายการแจ้งปัญหา
        </td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Mobile cards -->
  <div class="md:hidden p-2">
    <?php foreach ($issues as $iss): ?>
    <div class="bg-white border border-slate-200 rounded-md px-3 py-2.5 mb-2">
      <div class="flex justify-between items-start mb-1">
        <code class="tag"><?php echo e($iss['ticket_code']); ?></code>
        <?php echo uiBadge(issueStatusLabel($iss['status'], !empty($iss['handled_by_central'])), issueStatusBadgeClass($iss['status'])); ?>
      </div>
      <div class="font-semibold text-sm"><?php echo e($iss['title']); ?></div>
      <div class="text-slate-500 text-xs mb-2"><?php echo e($iss['cooperative_name']); ?> | <?php echo thaiDate($iss['created_at'],true); ?></div>
      <a href="?page=issues&action=detail&id=<?php echo $iss['id']; ?>" class="<?php echo uiBtnClasses('warning'); ?> w-full">
        <i class="fas fa-eye mr-1"></i>ดูรายละเอียด
      </a>
    </div>
    <?php endforeach; ?>
    <?php if (empty($issues)): ?>
    <div class="text-center text-slate-400 py-8">
      <i class="fas fa-inbox block text-3xl mb-2 text-slate-300"></i>ไม่พบรายการแจ้งปัญหา
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
