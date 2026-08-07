<?php
$empTypeTh    = getEmployeeTypeOptions();
$profileRoles = isset($user['roles']) ? array_map('trim', explode(',', $user['roles'])) : array($user['role']);
?>

<div class="bg-white border-b border-slate-200 px-4 py-1.5 text-sm">
  <nav aria-label="breadcrumb">
    <ol class="flex items-center gap-1.5 text-slate-500">
      <li><a class="hover:text-[#1565c0]" href="?page=dashboard"><i class="fas fa-home"></i></a></li>
      <li class="text-slate-300">/</li>
      <li class="text-slate-700 font-medium">โปรไฟล์ของฉัน</li>
    </ol>
  </nav>
</div>

<main class="p-3 md:p-5 pb-6 md:pb-8 max-w-full overflow-x-hidden">

  <div class="rounded-lg border border-blue-200 px-4 md:px-5 py-3.5 flex items-center gap-3.5 flex-wrap mb-4"
       style="background: linear-gradient(135deg,#e3f2fd 0%,#f8f9ff 100%);">
    <div class="w-11 h-11 rounded-[10px] bg-[#1565c0] text-white flex items-center justify-center text-xl flex-shrink-0">
      <i class="fas fa-user-circle"></i>
    </div>
    <div>
      <div class="text-base font-bold text-[#1a237e]">โปรไฟล์ของฉัน</div>
      <div class="text-sm text-slate-600"><?php echo e(getFullname($user)); ?> | @<?php echo e($user['username']); ?></div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-3">

    <!-- ซ้าย: ข้อมูลส่วนตัว -->
    <div class="lg:col-span-8">

      <div class="bg-white border border-slate-200 rounded-md mb-3">
        <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-user mr-2 text-[#1565c0]"></i>ข้อมูลส่วนตัว</div>
        <div class="p-3.5">
          <div class="flex items-center gap-3 mb-4 p-3 rounded" style="background:linear-gradient(135deg,#e3f2fd 0%,#f8f9ff 100%);">
            <div class="rounded-full text-white flex items-center justify-center font-bold flex-shrink-0"
                 style="width:56px;height:56px;font-size:1.5rem;background:#1565c0;">
              <?php echo mb_substr($user['firstname'], 0, 1, 'UTF-8'); ?>
            </div>
            <div>
              <div class="font-bold"><?php echo e(getFullname($user)); ?></div>
              <div class="text-slate-500 text-sm mb-1">@<?php echo e($user['username']); ?></div>
              <div class="flex flex-wrap gap-1 mt-1">
                <?php foreach ($profileRoles as $pr): if (empty($pr)) continue; ?>
                <?php echo uiBadge('<i class="fas fa-shield-alt mr-1"></i>' . getRoleLabel($pr), getRoleBadgeClass($pr)); ?>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-[0.88rem]">
            <div class="p-2 rounded border border-slate-200 bg-slate-50">
              <div class="text-slate-500 text-xs mb-1"><i class="fas fa-user mr-1"></i>ชื่อ-นามสกุล</div>
              <div class="font-semibold"><?php echo e($user['prefix'].' '.$user['firstname'].' '.$user['lastname']); ?></div>
            </div>
            <div class="p-2 rounded border border-slate-200 bg-slate-50">
              <div class="text-slate-500 text-xs mb-1"><i class="fas fa-briefcase mr-1"></i>ตำแหน่ง</div>
              <div class="font-semibold"><?php echo e($user['position']); ?></div>
            </div>
            <div class="p-2 rounded border border-slate-200 bg-slate-50">
              <div class="text-slate-500 text-xs mb-1"><i class="fas fa-id-badge mr-1"></i>ประเภทพนักงาน</div>
              <div class="font-semibold"><?php echo isset($empTypeTh[$user['employee_type']]) ? $empTypeTh[$user['employee_type']] : e($user['employee_type']); ?></div>
            </div>
            <div class="p-2 rounded border border-slate-200 bg-slate-50">
              <div class="text-slate-500 text-xs mb-1"><i class="fas fa-landmark mr-1"></i>สำนักงาน</div>
              <div class="font-semibold"><?php echo e($user['office_name']); ?></div>
            </div>
            <div class="p-2 rounded border border-slate-200 bg-slate-50">
              <div class="text-slate-500 text-xs mb-1"><i class="fas fa-phone mr-1"></i>เบอร์โทรศัพท์</div>
              <div class="font-semibold"><?php echo $user['phone'] ? e($user['phone']) : '<span class="text-slate-400">-</span>'; ?></div>
            </div>
            <div class="p-2 rounded border border-slate-200 bg-slate-50">
              <div class="text-slate-500 text-xs mb-1"><i class="fas fa-envelope mr-1"></i>อีเมล</div>
              <div class="font-semibold"><?php echo $user['email'] ? e($user['email']) : '<span class="text-slate-400">-</span>'; ?></div>
            </div>
            <div class="p-2 rounded border border-slate-200 bg-slate-50">
              <div class="text-slate-500 text-xs mb-1"><i class="fas fa-toggle-on mr-1"></i>สถานะบัญชี</div>
              <div><?php echo $user['is_active'] ? uiBadge('ใช้งานอยู่','bg-green-600 text-white') : uiBadge('ระงับ','bg-slate-500 text-white'); ?></div>
            </div>
            <div class="p-2 rounded border border-slate-200 bg-slate-50">
              <div class="text-slate-500 text-xs mb-1"><i class="fas fa-calendar-alt mr-1"></i>วันที่สร้างบัญชี</div>
              <div class="font-semibold"><?php echo thaiDate($user['created_at']); ?></div>
            </div>
          </div>
          <div class="border border-slate-200 bg-slate-50 text-slate-600 rounded mt-3 mb-0 px-3 py-2 text-sm">
            <i class="fas fa-info-circle text-[#1565c0] mr-1"></i>
            หากต้องการแก้ไขข้อมูลส่วนตัว กรุณาติดต่อผู้ดูแลระบบ
          </div>
        </div>
      </div>

      <!-- เปลี่ยนรหัสผ่าน -->
      <div class="bg-white border border-slate-200 rounded-md">
        <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-lock mr-2 text-amber-500"></i>เปลี่ยนรหัสผ่าน</div>
        <div class="p-3.5">
          <form method="POST" action="?page=users&action=update_profile" id="passwordForm">
            <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
            <input type="hidden" name="position" value="<?php echo e($user['position']); ?>">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">รหัสผ่านปัจจุบัน <span class="text-red-600">*</span></label>
                <div class="flex rounded-md border border-slate-300 overflow-hidden">
                  <input type="password" name="current_password" id="curPw" class="flex-1 min-w-0 px-2 py-1.5 text-sm outline-none" required autocomplete="current-password">
                  <button class="bg-slate-50 border-l border-slate-300 px-2.5" type="button" onclick="togglePw('curPw','eyeCur')"><i class="fas fa-eye text-slate-500" id="eyeCur"></i></button>
                </div>
              </div>
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">รหัสผ่านใหม่ <span class="text-red-600">*</span></label>
                <div class="flex rounded-md border border-slate-300 overflow-hidden">
                  <input type="password" name="new_password" id="newPw" class="flex-1 min-w-0 px-2 py-1.5 text-sm outline-none"
                         required minlength="6" autocomplete="new-password"
                         oninput="checkStrength(this.value)" placeholder="อย่างน้อย 6 ตัว">
                  <button class="bg-slate-50 border-l border-slate-300 px-2.5" type="button" onclick="togglePw('newPw','eyeNew')"><i class="fas fa-eye text-slate-500" id="eyeNew"></i></button>
                </div>
                <div id="strengthBar" class="hidden mt-1">
                  <div class="h-1 bg-slate-200 rounded overflow-hidden"><div class="h-1" id="strengthBarInner" style="width:0%;transition:width .3s;"></div></div>
                  <small id="strengthText" class="text-slate-500"></small>
                </div>
              </div>
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">ยืนยันรหัสผ่านใหม่ <span class="text-red-600">*</span></label>
                <div class="flex rounded-md border border-slate-300 overflow-hidden">
                  <input type="password" name="confirm_password" id="confPw" class="flex-1 min-w-0 px-2 py-1.5 text-sm outline-none"
                         required autocomplete="new-password" oninput="checkMatch()">
                  <button class="bg-slate-50 border-l border-slate-300 px-2.5" type="button" onclick="togglePw('confPw','eyeConf')"><i class="fas fa-eye text-slate-500" id="eyeConf"></i></button>
                </div>
                <small id="matchMsg" class="mt-1 block"></small>
              </div>
            </div>
            <div class="flex gap-2 mt-3 pt-3 border-t border-slate-200">
              <button type="submit" class="<?php echo uiBtnClasses('warning'); ?> font-semibold">
                <i class="fas fa-save mr-1"></i>บันทึกรหัสผ่านใหม่
              </button>
            </div>
          </form>
        </div>
      </div>

    </div>

    <!-- ขวา: ข้อมูลสรุป -->
    <div class="lg:col-span-4">
      <div class="bg-white border border-slate-200 rounded-md">
        <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-id-card mr-2 text-sky-600"></i>สรุปบัญชีของฉัน</div>
        <div class="p-0">
          <table class="w-full text-[0.82rem]">
            <tbody>
              <tr><td class="text-slate-500 pl-3.5 py-1.5">ชื่อผู้ใช้</td><td class="py-1.5"><code class="tag"><?php echo e($user['username']); ?></code></td></tr>
              <tr><td class="text-slate-500 pl-3.5 py-1.5">บทบาท</td>
                <td class="py-1.5">
                  <?php foreach ($profileRoles as $pr): if (empty($pr)) continue; ?>
                  <?php echo uiBadge(getRoleLabel($pr), getRoleBadgeClass($pr), 'text-[0.7rem] m-0.5'); ?>
                  <?php endforeach; ?>
                </td>
              </tr>
              <tr><td class="text-slate-500 pl-3.5 py-1.5">สำนักงาน</td><td class="py-1.5 text-[0.8rem]"><?php echo e($user['office_name']); ?></td></tr>
              <tr><td class="text-slate-500 pl-3.5 py-1.5">ตำแหน่ง</td><td class="py-1.5"><?php echo e($user['position']); ?></td></tr>
              <tr><td class="text-slate-500 pl-3.5 py-1.5">โทร</td><td class="py-1.5"><?php echo e($user['phone']); ?></td></tr>
              <tr><td class="text-slate-500 pl-3.5 py-1.5 pb-3.5">สร้างเมื่อ</td><td class="pb-3.5 py-1.5"><?php echo thaiDate($user['created_at']); ?></td></tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="border border-amber-200 bg-amber-50 text-amber-700 rounded mt-3 px-3 py-2 text-sm">
        <i class="fas fa-shield-alt mr-1"></i>
        <strong>ความปลอดภัย:</strong> ใช้รหัสผ่านที่ประกอบด้วยตัวอักษร ตัวเลข และอักขระพิเศษ อย่างน้อย 6 ตัว
      </div>
    </div>

  </div>
</main>

<?php
$extraJs = '<script>
function togglePw(id, iconId) {
  var el = document.getElementById(id), ic = document.getElementById(iconId);
  el.type = (el.type === "password") ? "text" : "password";
  ic.className = (el.type === "text") ? "fas fa-eye-slash text-slate-500" : "fas fa-eye text-slate-500";
}
function checkStrength(v) {
  var bar = document.getElementById("strengthBar"), bi = document.getElementById("strengthBarInner"), tx = document.getElementById("strengthText");
  if (!v) { bar.classList.add("hidden"); return; }
  bar.classList.remove("hidden");
  var s = 0;
  if (v.length >= 6) s++;
  if (v.length >= 10) s++;
  if (/[A-Z]/.test(v)) s++;
  if (/[0-9]/.test(v)) s++;
  if (/[^A-Za-z0-9]/.test(v)) s++;
  var c = ["#dc2626","#f97316","#f59e0b","#22c55e","#15803d"];
  var l = ["อ่อนมาก","อ่อน","ปานกลาง","แข็งแรง","แข็งแรงมาก"];
  var p = [20,40,60,80,100]; var i = s > 0 ? s - 1 : 0;
  bi.style.width = p[i] + "%"; bi.style.backgroundColor = c[i];
  tx.textContent = "ความแข็งแรง: " + l[i]; tx.style.color = c[i];
}
function checkMatch() {
  var nw = document.getElementById("newPw").value, cf = document.getElementById("confPw").value, m = document.getElementById("matchMsg");
  if (!cf) { m.textContent = ""; return; }
  m.innerHTML = (nw === cf)
    ? "<span class=\"text-green-600\"><i class=\"fas fa-check mr-1\"></i>ตรงกัน</span>"
    : "<span class=\"text-red-600\"><i class=\"fas fa-times mr-1\"></i>ไม่ตรงกัน</span>";
}
document.getElementById("passwordForm").addEventListener("submit", function(e) {
  var nw = document.getElementById("newPw").value, cf = document.getElementById("confPw").value;
  if (nw !== cf) {
    e.preventDefault();
    showToast("error", "รหัสผ่านไม่ตรงกัน", 3000);
    return;
  }
  if (nw.length < 6) {
    e.preventDefault();
    showToast("warning", "รหัสผ่านต้องมีอย่างน้อย 6 ตัว", 3000);
  }
});
</script>';
?>
