<?php
$employeeTypes  = getEmployeeTypeOptions();
$officeOptions  = getOfficeOptions();
$allRoleKeys    = array('submitter','inspector','approver','operator','admin');
$roleDesc       = array(
    'submitter' => 'ส่งเอกสาร ติดตามสถานะ และแก้ไขเมื่อถูกส่งกลับ',
    'inspector' => 'ตรวจสอบเอกสาร กรอกเลขรับหนังสือ',
    'approver'  => 'อนุมัติเอกสารที่ผ่านการตรวจสอบ',
    'operator'  => 'บันทึกผลดำเนินการขั้นสุดท้าย',
    'admin'     => 'เข้าถึงได้ทุกส่วน จัดการสมาชิกและสหกรณ์',
);
$empTypeTh     = getEmployeeTypeOptions();
$userRolesArr  = array_map('trim', explode(',', $user['roles']));
$validRoles    = array('submitter','inspector','approver','operator','admin');
?>

<div class="bg-white border-b border-slate-200 px-4 py-1.5 text-sm">
  <nav aria-label="breadcrumb">
    <ol class="flex items-center gap-1.5 text-slate-500">
      <li><a class="hover:text-[#1565c0]" href="?page=dashboard"><i class="fas fa-home"></i></a></li>
      <li class="text-slate-300">/</li>
      <li><a class="hover:text-[#1565c0]" href="?page=users">จัดการสมาชิก</a></li>
      <li class="text-slate-300">/</li>
      <li class="text-slate-700 font-medium">แก้ไขสมาชิก</li>
    </ol>
  </nav>
</div>

<main class="p-3 md:p-5 pb-6 md:pb-8 max-w-full overflow-x-hidden">

  <div class="rounded-lg border border-blue-200 px-4 md:px-5 py-3.5 flex items-center gap-3.5 flex-wrap mb-4"
       style="background: linear-gradient(135deg,#e3f2fd 0%,#f8f9ff 100%);">
    <div class="w-11 h-11 rounded-[10px] bg-[#1565c0] text-white flex items-center justify-center text-xl flex-shrink-0">
      <i class="fas fa-user-edit"></i>
    </div>
    <div>
      <div class="text-base font-bold text-[#1a237e]">แก้ไขข้อมูลสมาชิก</div>
      <div class="text-sm text-slate-600"><?php echo e(getFullname($user)); ?> | @<?php echo e($user['username']); ?></div>
    </div>
  </div>

  <form method="POST" action="?page=users&action=update">
    <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-3">
      <div class="lg:col-span-8">

        <!-- ข้อมูลส่วนตัว -->
        <div class="bg-white border border-slate-200 rounded-md mb-3">
          <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-user mr-2 text-[#1565c0]"></i>ข้อมูลส่วนตัว</div>
          <div class="p-3.5">
            <div class="grid grid-cols-6 md:grid-cols-12 gap-3">
              <div class="col-span-2 md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-1">คำนำหน้า <span class="text-red-600">*</span></label>
                <select name="prefix" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" required>
                  <?php foreach (array('นาย','นาง','นางสาว') as $p): ?>
                  <option value="<?php echo $p; ?>" <?php echo $user['prefix']===$p ? 'selected' : ''; ?>><?php echo $p; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-span-4 md:col-span-5">
                <label class="block text-sm font-semibold text-slate-700 mb-1">ชื่อ <span class="text-red-600">*</span></label>
                <input type="text" name="firstname" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" required value="<?php echo e($user['firstname']); ?>">
              </div>
              <div class="col-span-6 md:col-span-5">
                <label class="block text-sm font-semibold text-slate-700 mb-1">นามสกุล <span class="text-red-600">*</span></label>
                <input type="text" name="lastname" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" required value="<?php echo e($user['lastname']); ?>">
              </div>
              <div class="col-span-6 md:col-span-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">เบอร์โทร</label>
                <input type="text" name="phone" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" value="<?php echo e($user['phone']); ?>">
              </div>
              <div class="col-span-6 md:col-span-8">
                <label class="block text-sm font-semibold text-slate-700 mb-1">อีเมล</label>
                <input type="email" name="email" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" value="<?php echo e($user['email']); ?>">
              </div>
            </div>
          </div>
        </div>

        <!-- บัญชีและสิทธิ์ -->
        <div class="bg-white border border-slate-200 rounded-md mb-3">
          <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-shield-alt mr-2 text-amber-500"></i>บัญชีและสิทธิ์</div>
          <div class="p-3.5">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">ชื่อผู้ใช้</label>
                <input type="text" class="w-full text-sm border border-slate-200 bg-slate-100 rounded-md px-2 py-1.5" readonly value="<?php echo e($user['username']); ?>">
              </div>
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">สถานะบัญชี</label>
                <select name="is_active" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5">
                  <option value="1" <?php echo $user['is_active'] ? 'selected' : ''; ?>>ใช้งาน</option>
                  <option value="0" <?php echo !$user['is_active'] ? 'selected' : ''; ?>>ระงับ</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">สำนักงาน <span class="text-red-600">*</span></label>
                <select name="office_name" id="officeSelect" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" required>
                  <?php foreach ($officeOptions as $off): ?>
                  <option value="<?php echo e($off); ?>" <?php echo $user['office_name']===$off ? 'selected' : ''; ?>><?php echo e($off); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-1">รหัสผ่านใหม่ <small class="text-slate-400 font-normal">(เว้นว่างถ้าไม่เปลี่ยน)</small></label>
                <input type="password" name="password" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" autocomplete="new-password">
              </div>
              <div class="md:col-span-3">
                <label class="block text-sm font-semibold text-slate-700 mb-1">บทบาท <span class="text-red-600">*</span> <small class="text-slate-400 font-normal">(เลือกได้มากกว่า 1 บทบาท)</small></label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                  <?php foreach ($allRoleKeys as $rk):
                    $checked = in_array($rk, $userRolesArr);
                  ?>
                  <div class="border rounded-md p-2 flex items-start gap-2 role-card <?php echo $checked ? 'border-blue-600 bg-blue-50' : 'border-slate-200'; ?>" id="role-card-<?php echo $rk; ?>">
                    <input type="checkbox" name="roles[]" value="<?php echo $rk; ?>"
                           id="role_<?php echo $rk; ?>" class="mt-1 role-checkbox"
                           <?php echo $checked ? 'checked' : ''; ?>>
                    <label for="role_<?php echo $rk; ?>" class="mb-0 flex-1 cursor-pointer">
                      <?php echo uiBadge(getRoleLabel($rk), getRoleBadgeClass($rk), 'mr-1'); ?>
                      <span class="text-slate-500 text-sm block mt-1"><?php echo $roleDesc[$rk]; ?></span>
                    </label>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- ตำแหน่งงาน -->
        <div class="bg-white border border-slate-200 rounded-md mb-3">
          <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-briefcase mr-2 text-sky-600"></i>ตำแหน่งงาน</div>
          <div class="p-3.5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">ประเภทพนักงาน <span class="text-red-600">*</span></label>
                <select name="employee_type" id="empType" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" required>
                  <?php foreach ($employeeTypes as $k => $v): ?>
                  <option value="<?php echo $k; ?>" <?php echo $user['employee_type']===$k ? 'selected' : ''; ?>><?php echo $v; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">ตำแหน่ง <span class="text-red-600">*</span></label>
                <select name="position" id="posSelect" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" required></select>
              </div>
            </div>
          </div>
        </div>

        <div class="flex gap-2">
          <button type="submit" class="<?php echo uiBtnClasses('primary'); ?>"><i class="fas fa-save mr-1"></i>บันทึกการแก้ไข</button>
          <a href="?page=users" class="<?php echo uiBtnClasses('outline'); ?>">ยกเลิก</a>
        </div>
      </div>

      <!-- ขวา: ข้อมูลปัจจุบัน -->
      <div class="lg:col-span-4">
        <div class="bg-white border border-slate-200 rounded-md">
          <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-id-card mr-2 text-sky-600"></i>ข้อมูลปัจจุบัน</div>
          <div class="p-3.5">
            <div class="flex items-center gap-3 mb-3 p-3 rounded" style="background:linear-gradient(135deg,#e3f2fd,#f8f9ff);">
              <div class="rounded-full text-white flex items-center justify-center font-bold flex-shrink-0"
                   style="width:48px;height:48px;font-size:1.3rem;background:#1565c0;">
                <?php echo mb_substr($user['firstname'], 0, 1, 'UTF-8'); ?>
              </div>
              <div>
                <div class="font-bold text-sm"><?php echo e(getFullname($user)); ?></div>
                <div class="flex flex-wrap gap-1 mt-1">
                  <?php foreach ($userRolesArr as $r): if (!in_array($r, $validRoles)) continue; ?>
                  <?php echo uiBadge(getRoleLabel($r), getRoleBadgeClass($r), 'text-[0.7rem]'); ?>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
            <table class="w-full text-[0.82rem]">
              <tbody>
                <tr><td class="text-slate-500 py-1 pr-2">ชื่อผู้ใช้</td><td class="py-1"><code class="tag"><?php echo e($user['username']); ?></code></td></tr>
                <tr><td class="text-slate-500 py-1 pr-2">สำนักงาน</td><td class="py-1 text-[0.8rem]"><?php echo e($user['office_name']); ?></td></tr>
                <tr><td class="text-slate-500 py-1 pr-2">ประเภท</td><td class="py-1"><?php echo isset($empTypeTh[$user['employee_type']]) ? $empTypeTh[$user['employee_type']] : e($user['employee_type']); ?></td></tr>
                <tr><td class="text-slate-500 py-1 pr-2">ตำแหน่ง</td><td class="py-1"><?php echo e($user['position']); ?></td></tr>
                <tr><td class="text-slate-500 py-1 pr-2">สถานะ</td><td class="py-1"><?php echo $user['is_active'] ? uiBadge('ใช้งาน','bg-green-600 text-white') : uiBadge('ระงับ','bg-slate-500 text-white'); ?></td></tr>
                <tr><td class="text-slate-500 py-1 pr-2">สร้างเมื่อ</td><td class="py-1"><?php echo thaiDate($user['created_at']); ?></td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </form>
</main>

<?php
$currentPos = e($user['position']);
$extraJs = '<script>
setupPositionSelector("empType", "posSelect", "' . $currentPos . '");
</script>';
?>
