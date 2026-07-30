<?php
// Partial: ตารางสมาชิก + pagination
// ตัวแปรที่ต้องมี: $users, $totalItems, $totalPages, $currentPageNum, $empTypeOptions
$validRoles = array('submitter','inspector','approver','operator','admin');
?>
<div class="page-card">
  <div class="page-card-header">
    <span><i class="fas fa-list me-2 text-primary"></i>รายชื่อสมาชิก
      <span class="badge bg-secondary ms-1"><?php echo $totalItems; ?></span>
    </span>
  </div>
  <div class="table-edms-wrap">
    <table class="table-edms">
      <thead>
        <tr>
          <th style="width:40px;">#</th>
          <th>ชื่อ-นามสกุล</th><th>ชื่อผู้ใช้</th><th>บทบาท</th>
          <th>ตำแหน่ง</th><th>สำนักงาน</th><th>ประเภท</th>
          <th>สถานะ</th><th style="text-align:center;width:100px;">ดำเนินการ</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $startNo = ($currentPageNum - 1) * 10 + 1;
        foreach ($users as $idx => $u):
          $uRoles = array_map('trim', explode(',', $u['roles']));
        ?>
        <tr>
          <td><?php echo $startNo + $idx; ?></td>
          <td class="fw-semibold"><?php echo e(getFullname($u)); ?></td>
          <td><code><?php echo e($u['username']); ?></code></td>
          <td>
            <?php foreach ($uRoles as $r): if (!in_array($r, $validRoles)) continue; ?>
            <span class="badge <?php echo getRoleBadgeClass($r); ?>" style="font-size:0.7rem;margin:1px;">
              <?php echo getRoleLabel($r); ?>
            </span>
            <?php endforeach; ?>
          </td>
          <td style="font-size:0.82rem;"><?php echo e($u['position']); ?></td>
          <td style="font-size:0.78rem;"><?php echo e($u['office_name']); ?></td>
          <td style="font-size:0.82rem;"><?php echo isset($empTypeOptions[$u['employee_type']]) ? $empTypeOptions[$u['employee_type']] : e($u['employee_type']); ?></td>
          <td><?php echo $u['is_active'] ? '<span class="badge bg-success">ใช้งาน</span>' : '<span class="badge bg-secondary">ระงับ</span>'; ?></td>
          <td class="text-center">
            <a href="?page=users&action=edit&id=<?php echo $u['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
            <?php if ($u['id'] != $_SESSION['user_id']): ?>
            <button class="btn btn-sm btn-outline-danger ms-1"
              onclick="confirmDelete('?page=users&action=delete&id=<?php echo $u['id']; ?>','<?php echo e(getFullname($u)); ?>')">
              <i class="fas fa-ban"></i>
            </button>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($users)): ?>
        <tr><td colspan="9" class="text-center text-muted py-4">
          <i class="fas fa-inbox d-block fs-2 mb-2 text-secondary"></i>ไม่พบรายการ
        </td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <div class="mobile-list p-2">
    <?php foreach ($users as $u):
      $uRoles = array_map('trim', explode(',', $u['roles']));
    ?>
    <div class="mobile-card">
      <div class="d-flex justify-content-between align-items-start mb-1">
        <div class="mobile-card-title"><?php echo e(getFullname($u)); ?></div>
        <div>
          <?php foreach ($uRoles as $r): if (!in_array($r, $validRoles)) continue; ?>
          <span class="badge <?php echo getRoleBadgeClass($r); ?>" style="font-size:0.68rem;"><?php echo getRoleLabel($r); ?></span>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="mobile-card-sub mb-2"><code><?php echo e($u['username']); ?></code> | <?php echo e($u['office_name']); ?></div>
      <div class="d-flex gap-1">
        <a href="?page=users&action=edit&id=<?php echo $u['id']; ?>" class="btn btn-outline-primary btn-sm flex-grow-1">
          <i class="fas fa-edit me-1"></i>แก้ไข
        </a>
        <?php if ($u['id'] != $_SESSION['user_id']): ?>
        <button class="btn btn-outline-danger btn-sm"
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
