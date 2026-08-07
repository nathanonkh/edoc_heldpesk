<?php
// Partial: ตารางสหกรณ์ + pagination
// ตัวแปรที่ต้องมี: $cooperatives, $totalItems, $totalPages, $currentPageNum
?>
<div class="bg-white border border-slate-200 rounded-md overflow-hidden">
  <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm">
    <i class="fas fa-list mr-2 text-[#1565c0]"></i>รายชื่อสหกรณ์ <span class="bg-slate-500 text-white text-xs rounded px-1.5 py-0.5 ml-1"><?php echo $totalItems; ?></span>
  </div>
  <div class="hidden md:block overflow-x-auto">
    <table class="text-sm border-collapse w-full min-w-[640px]">
      <thead>
        <tr>
          <th class="w-9 bg-slate-100 border border-slate-300 px-2.5 py-2">#</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left font-semibold">รหัส</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left font-semibold">ชื่อสหกรณ์</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left font-semibold">ประเภท</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left font-semibold">จังหวัด</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left font-semibold">สิ้นปีบัญชี</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left font-semibold">สำนักงาน</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left font-semibold">สถานะ</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-center font-semibold w-[100px]">ดำเนินการ</th>
        </tr>
      </thead>
      <tbody>
        <?php $startNo = ($currentPageNum - 1) * 10 + 1; foreach ($cooperatives as $idx => $coop): ?>
        <tr class="hover:bg-blue-50/50">
          <td class="border border-slate-200 px-2.5 py-1.5"><?php echo $startNo + $idx; ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5"><code class="tag"><?php echo e($coop['code']); ?></code></td>
          <td class="border border-slate-200 px-2.5 py-1.5 font-semibold"><?php echo e($coop['name']); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5"><?php echo e($coop['type_name']); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5"><?php echo e($coop['province']); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5"><?php echo formatFiscalYear($coop['fiscal_year']); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5 text-[0.78rem]"><?php echo e($coop['office_name']); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5"><?php echo uiBadge(getCooperativeStatusLabel($coop['status']), getCooperativeStatusBadge($coop['status'])); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5 text-center">
            <a href="?page=cooperatives&action=edit&id=<?php echo $coop['id']; ?>" class="<?php echo uiBtnClasses('outline'); ?>"><i class="fas fa-edit"></i></a>
            <button class="<?php echo uiBtnClasses('outline-danger'); ?> ml-1"
              onclick="confirmDelete('?page=cooperatives&action=delete&id=<?php echo $coop['id']; ?>','<?php echo e($coop['name']); ?>')">
              <i class="fas fa-trash"></i>
            </button>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($cooperatives)): ?>
        <tr><td colspan="9" class="text-center text-slate-400 py-8 border border-slate-200">
          <i class="fas fa-inbox block text-3xl mb-2 text-slate-300"></i>ไม่พบรายการ
        </td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <div class="md:hidden p-2">
    <?php foreach ($cooperatives as $coop): ?>
    <div class="bg-white border border-slate-200 rounded-md px-3 py-2.5 mb-2">
      <div class="flex justify-between mb-1">
        <code class="tag"><?php echo e($coop['code']); ?></code>
        <?php echo uiBadge(getCooperativeStatusLabel($coop['status']), getCooperativeStatusBadge($coop['status'])); ?>
      </div>
      <div class="font-semibold text-sm"><?php echo e($coop['name']); ?></div>
      <div class="text-slate-500 text-xs mb-2"><?php echo e($coop['type_name']); ?> | <?php echo e($coop['province']); ?></div>
      <div class="flex gap-1">
        <a href="?page=cooperatives&action=edit&id=<?php echo $coop['id']; ?>" class="<?php echo uiBtnClasses('outline'); ?> flex-1"><i class="fas fa-edit mr-1"></i>แก้ไข</a>
        <button class="<?php echo uiBtnClasses('outline-danger'); ?>"
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
