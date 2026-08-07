<?php
// Partial: ตารางสมาชิก + pagination
// ตัวแปรที่ต้องมี: $users, $totalItems, $totalPages, $currentPageNum, $empTypeOptions
$validRoles = array('submitter','inspector','approver','operator','admin');
?>
<div class="bg-white border border-slate-200 rounded-md overflow-hidden">
  <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm">
    <i class="fas fa-list mr-2 text-[#1565c0]"></i>รายชื่อสมาชิก
    <span class="bg-slate-500 text-white text-xs rounded px-1.5 py-0.5 ml-1"><?php echo $totalItems; ?></span>
  </div>
  <div class="hidden md:block overflow-x-auto">
    <table class="text-sm border-collapse w-full min-w-[640px]">
      <thead>
        <tr>
          <th class="w-9 bg-slate-100 border border-slate-300 px-2.5 py-2">#</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left font-semibold">ชื่อ-นามสกุล</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left font-semibold">ชื่อผู้ใช้</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left font-semibold">บทบาท</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left font-semibold">ตำแหน่ง</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left font-semibold">สำนักงาน</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left font-semibold">ประเภท</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-left font-semibold">สถานะ</th>
          <th class="bg-slate-100 border border-slate-300 px-2.5 py-2 text-center font-semibold w-[100px]">ดำเนินการ</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $startNo = ($currentPageNum - 1) * 10 + 1;
        foreach ($users as $idx => $u):
          $uRoles = array_map('trim', explode(',', $u['roles']));
        ?>
        <tr class="hover:bg-blue-50/50">
          <td class="border border-slate-200 px-2.5 py-1.5"><?php echo $startNo + $idx; ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5 font-semibold"><?php echo e(getFullname($u)); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5"><code class="tag"><?php echo e($u['username']); ?></code></td>
          <td class="border border-slate-200 px-2.5 py-1.5">
            <?php foreach ($uRoles as $r): if (!in_array($r, $validRoles)) continue; ?>
            <?php echo uiBadge(getRoleLabel($r), getRoleBadgeClass($r), 'text-[0.7rem] m-0.5'); ?>
            <?php endforeach; ?>
          </td>
          <td class="border border-slate-200 px-2.5 py-1.5 text-[0.82rem]"><?php echo e($u['position']); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5 text-[0.78rem]"><?php echo e($u['office_name']); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5 text-[0.82rem]"><?php echo isset($empTypeOptions[$u['employee_type']]) ? $empTypeOptions[$u['employee_type']] : e($u['employee_type']); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5"><?php echo $u['is_active'] ? uiBadge('ใช้งาน','bg-green-600 text-white') : uiBadge('ระงับ','bg-slate-500 text-white'); ?></td>
          <td class="border border-slate-200 px-2.5 py-1.5 text-center">
            <a href="?page=users&action=edit&id=<?php echo $u['id']; ?>" class="<?php echo uiBtnClasses('outline'); ?>"><i class="fas fa-edit"></i></a>
            <?php if ($u['id'] != $_SESSION['user_id']): ?>
            <button class="<?php echo uiBtnClasses('outline-danger'); ?> ml-1"
              onclick="confirmDelete('?page=users&action=delete&id=<?php echo $u['id']; ?>','<?php echo e(getFullname($u)); ?>')">
              <i class="fas fa-ban"></i>
            </button>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($users)): ?>
        <tr><td colspan="9" class="text-center text-slate-400 py-8 border border-slate-200">
          <i class="fas fa-inbox block text-3xl mb-2 text-slate-300"></i>ไม่พบรายการ
        </td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <div class="md:hidden p-2">
    <?php foreach ($users as $u):
      $uRoles = array_map('trim', explode(',', $u['roles']));
    ?>
    <div class="bg-white border border-slate-200 rounded-md px-3 py-2.5 mb-2">
      <div class="flex justify-between items-start mb-1">
        <div class="font-semibold text-sm"><?php echo e(getFullname($u)); ?></div>
        <div>
          <?php foreach ($uRoles as $r): if (!in_array($r, $validRoles)) continue; ?>
          <?php echo uiBadge(getRoleLabel($r), getRoleBadgeClass($r), 'text-[0.68rem]'); ?>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="text-slate-500 text-xs mb-2"><code class="tag"><?php echo e($u['username']); ?></code> | <?php echo e($u['office_name']); ?></div>
      <div class="flex gap-1">
        <a href="?page=users&action=edit&id=<?php echo $u['id']; ?>" class="<?php echo uiBtnClasses('outline'); ?> flex-1">
          <i class="fas fa-edit mr-1"></i>แก้ไข
        </a>
        <?php if ($u['id'] != $_SESSION['user_id']): ?>
        <button class="<?php echo uiBtnClasses('outline-danger'); ?>"
          onclick="confirmDelete('?page=users&action=delete&id=<?php echo $u['id']; ?>','<?php echo e(getFullname($u)); ?>')">
          <i class="fas fa-ban"></i>
        </button>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<?php
$paginationParams = array(
  'page'          => 'users',
  'keyword'       => isset($keyword)       ? $keyword       : '',
  'filter_role'   => isset($filterRole)    ? $filterRole    : '',
  'filter_emp'    => isset($filterEmpType) ? $filterEmpType : '',
  'filter_office' => isset($filterOffice)  ? $filterOffice  : '',
  'filter_status' => isset($filterStatus)  ? $filterStatus  : '',
);
include 'views/layout/pagination.php';
?>
