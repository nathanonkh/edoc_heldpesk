<?php
$employeeTypes = getEmployeeTypeOptions();
$officeOptions = getOfficeOptions();
$allRoleKeys   = array('submitter','inspector','approver','operator','admin');
$roleDesc      = array(
    'submitter' => 'ส่งเอกสาร ติดตามสถานะ และแก้ไขเมื่อถูกส่งกลับ',
    'inspector' => 'ตรวจสอบเอกสาร กรอกเลขรับหนังสือ',
    'approver'  => 'อนุมัติเอกสารที่ผ่านการตรวจสอบ',
    'operator'  => 'บันทึกผลดำเนินการขั้นสุดท้าย',
    'admin'     => 'เข้าถึงได้ทุกส่วน จัดการสมาชิกและสหกรณ์',
);
?>

<div class="bg-white border-b border-slate-200 px-4 py-1.5 text-sm">
  <nav aria-label="breadcrumb">
    <ol class="flex items-center gap-1.5 text-slate-500">
      <li><a class="hover:text-[#1565c0]" href="?page=dashboard"><i class="fas fa-home"></i></a></li>
      <li class="text-slate-300">/</li>
      <li><a class="hover:text-[#1565c0]" href="?page=users">จัดการสมาชิก</a></li>
      <li class="text-slate-300">/</li>
      <li class="text-slate-700 font-medium">เพิ่มสมาชิก</li>
    </ol>
  </nav>
</div>

<main class="p-3 md:p-5 pb-6 md:pb-8 max-w-full overflow-x-hidden">

  <div class="rounded-lg border border-blue-200 px-4 md:px-5 py-3.5 flex items-center gap-3.5 flex-wrap mb-4"
       style="background: linear-gradient(135deg,#e3f2fd 0%,#f8f9ff 100%);">
    <div class="w-11 h-11 rounded-[10px] bg-green-700 text-white flex items-center justify-center text-xl flex-shrink-0">
      <i class="fas fa-user-plus"></i>
    </div>
    <div>
      <div class="text-base font-bold text-[#1a237e]">เพิ่มสมาชิกใหม่</div>
      <div class="text-sm text-slate-600">สมาชิก 1 คนสามารถมีได้หลายบทบาท</div>
    </div>
  </div>

  <form method="POST" action="?page=users&action=store">
    <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">

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
                  <option value="นาย">นาย</option>
                  <option value="นาง">นาง</option>
                  <option value="นางสาว">นางสาว</option>
                </select>
              </div>
              <div class="col-span-4 md:col-span-5">
                <label class="block text-sm font-semibold text-slate-700 mb-1">ชื่อ <span class="text-red-600">*</span></label>
                <input type="text" name="firstname" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" required>
              </div>
              <div class="col-span-6 md:col-span-5">
                <label class="block text-sm font-semibold text-slate-700 mb-1">นามสกุล <span class="text-red-600">*</span></label>
                <input type="text" name="lastname" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" required>
              </div>
              <div class="col-span-6 md:col-span-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">เบอร์โทร</label>
                <input type="text" name="phone" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5">
              </div>
              <div class="col-span-6 md:col-span-8">
                <label class="block text-sm font-semibold text-slate-700 mb-1">อีเมล</label>
                <input type="email" name="email" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5">
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
                <label class="block text-sm font-semibold text-slate-700 mb-1">ชื่อผู้ใช้ <span class="text-red-600">*</span></label>
                <input type="text" name="username" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" required autocomplete="off">
              </div>
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">รหัสผ่าน <span class="text-red-600">*</span></label>
                <input type="password" name="password" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" required autocomplete="new-password">
              </div>
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">สำนักงาน <span class="text-red-600">*</span></label>
                <select name="office_name" id="officeSelect" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" required>
                  <?php foreach ($officeOptions as $off): ?>
                  <option value="<?php echo e($off); ?>"><?php echo e($off); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="md:col-span-3">
                <label class="block text-sm font-semibold text-slate-700 mb-1">บทบาท <span class="text-red-600">*</span> <small class="text-slate-400 font-normal">(เลือกได้มากกว่า 1 บทบาท)</small></label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                  <?php foreach ($allRoleKeys as $rk): ?>
                  <div class="border border-slate-200 rounded-md p-2 flex items-start gap-2 role-card" id="role-card-<?php echo $rk; ?>">
                    <input type="checkbox" name="roles[]" value="<?php echo $rk; ?>"
                           id="role_<?php echo $rk; ?>" class="mt-1 role-checkbox"
                           <?php echo $rk === 'submitter' ? 'checked' : ''; ?>>
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
                  <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
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
          <button type="submit" class="<?php echo uiBtnClasses('success'); ?>"><i class="fas fa-save mr-1"></i>บันทึกสมาชิก</button>
          <a href="?page=users" class="<?php echo uiBtnClasses('outline'); ?>">ยกเลิก</a>
        </div>
      </div>

      <!-- ขวา: คำแนะนำ -->
      <div class="lg:col-span-4">
        <div class="bg-white border border-slate-200 rounded-md">
          <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-info-circle mr-2 text-sky-600"></i>การทำงานของบทบาท</div>
          <div class="p-0">
            <div class="px-3.5 py-2.5 border-b border-slate-100">
              <div class="text-sm font-semibold text-slate-500 mb-2">ลำดับ Workflow เอกสาร</div>
              <div class="flex items-center gap-2 flex-wrap text-sm">
                <span class="bg-slate-500 text-white text-xs rounded px-1.5 py-0.5">submitter</span>
                <i class="fas fa-arrow-right text-slate-400"></i>
                <span class="bg-blue-600 text-white text-xs rounded px-1.5 py-0.5">inspector</span>
                <i class="fas fa-arrow-right text-slate-400"></i>
                <span class="bg-sky-600 text-white text-xs rounded px-1.5 py-0.5">approver</span>
                <i class="fas fa-arrow-right text-slate-400"></i>
                <span class="bg-purple-600 text-white text-xs rounded px-1.5 py-0.5">operator</span>
              </div>
            </div>
            <?php foreach ($allRoleKeys as $rk): ?>
            <div class="flex items-start gap-2 px-3.5 py-2.5 border-b border-slate-100 last:border-b-0">
              <?php echo uiBadge(getRoleLabel($rk), getRoleBadgeClass($rk), 'flex-shrink-0 mt-1'); ?>
              <span class="text-slate-500 text-sm"><?php echo $roleDesc[$rk]; ?></span>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </form>
</main>

<?php
$extraJs = '<script>
setupPositionSelector("empType", "posSelect", "");
</script>';
?>
