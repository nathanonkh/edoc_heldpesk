<?php
$currentPage   = isset($_GET['page'])   ? $_GET['page']   : 'dashboard';
$currentAction = isset($_GET['action']) ? $_GET['action'] : 'index';
$userRoles     = isset($_SESSION['roles']) ? $_SESSION['roles'] : array($_SESSION['role']);

$tips = array();

if ($currentPage === 'dashboard') {
    $tips = array('title'=>'คำแนะนำ: หน้าหลัก','icon'=>'fas fa-house-user','color'=>'blue',
        'items'=>array(
            array('icon'=>'fas fa-bullhorn','color'=>'text-amber-500','text'=>'ติดตามประกาศล่าสุดจากหน่วยงานได้ที่หน้าหลัก'),
            array('icon'=>'fas fa-video','color'=>'text-red-600',   'text'=>'รับชมวีดีโอสอนการใช้งานโปรแกรมต่าง ๆ ได้ที่นี่'),
        )
    );
    if (in_array('admin', $userRoles))
        $tips['items'][] = array('icon'=>'fas fa-cog','color'=>'text-sky-600','text'=>'ผู้ดูแลระบบสามารถเพิ่ม/ลบประกาศและวีดีโอได้ที่ "จัดการหน้าหลัก"');
    $tips['items'][] = array('icon'=>'fas fa-exclamation-circle','color'=>'text-red-600','text'=>'พบปัญหาการใช้งานโปรแกรม? กด "แจ้งปัญหาการใช้งาน" ได้ทันที');
} elseif ($currentPage === 'documents' && $currentAction === 'index') {
    $tips = array('title'=>'คำแนะนำ: รายการเอกสาร','icon'=>'fas fa-list','color'=>'blue',
        'items'=>array(
            array('icon'=>'fas fa-filter','color'=>'text-blue-600','text'=>'กรองรายการด้วย สถานะ / คำค้น / ช่วงวันที่ แล้วกด "ค้นหา"'),
            array('icon'=>'fas fa-eye','color'=>'text-amber-500',   'text'=>'คลิกปุ่ม "ดู" เพื่อดูรายละเอียดและดำเนินการกับเอกสาร'),
        )
    );
    if (in_array('submitter', $userRoles)) {
        $tips['items'][] = array('icon'=>'fas fa-plus','color'=>'text-green-600','text'=>'กดปุ่ม "นำส่งเอกสารใหม่" สีเขียวด้านบนขวา');
        $tips['items'][] = array('icon'=>'fas fa-edit','color'=>'text-red-600', 'text'=>'เอกสารที่ "ส่งกลับแก้ไข" จะมีปุ่มสีเขียวให้แก้ไขได้');
    }
    if (in_array('inspector', $userRoles)) {
        $tips['items'][] = array('icon'=>'fas fa-search','color'=>'text-sky-600',   'text'=>'กรองสถานะ "รอตรวจสอบ" เพื่อดูงานที่รอดำเนินการ');
        $tips['items'][] = array('icon'=>'fas fa-check','color'=>'text-green-600', 'text'=>'เปิดเอกสาร กรอกเลขรับหนังสือ แล้วกด "ส่งต่ออนุมัติ"');
    }
    if (in_array('approver', $userRoles)) {
        $tips['items'][] = array('icon'=>'fas fa-check-double','color'=>'text-sky-600',   'text'=>'ทำเครื่องหมายเลือกหลายรายการแล้วกด "อนุมัติที่เลือก" ได้พร้อมกัน');
        $tips['items'][] = array('icon'=>'fas fa-filter',      'color'=>'text-amber-500','text'=>'กรองสถานะ "รออนุมัติ" เพื่อดูเอกสารที่รอการพิจารณา');
    }
} elseif ($currentPage === 'documents' && $currentAction === 'create') {
    $tips = array('title'=>'คำแนะนำ: นำส่งเอกสาร','icon'=>'fas fa-upload','color'=>'green',
        'items'=>array(
            array('icon'=>'fas fa-1','color'=>'text-blue-600','text'=>'<strong>ขั้นที่ 1</strong> เลือกประเภทสหกรณ์ก่อน ระบบจะโหลดรายชื่อให้อัตโนมัติ'),
            array('icon'=>'fas fa-2','color'=>'text-blue-600','text'=>'<strong>ขั้นที่ 2</strong> เลือกชื่อสหกรณ์จากรายการที่ปรากฏ'),
            array('icon'=>'fas fa-3','color'=>'text-blue-600','text'=>'<strong>ขั้นที่ 3</strong> เลือกปีบัญชีและเลขที่หนังสือ'),
            array('icon'=>'fas fa-4','color'=>'text-blue-600','text'=>'<strong>ขั้นที่ 4</strong> อัปโหลดไฟล์ PDF ครบทั้ง 4 ไฟล์'),
            array('icon'=>'fas fa-file-pdf','color'=>'text-red-600','text'=>'รับเฉพาะไฟล์ <strong>PDF</strong> เท่านั้น ขนาดไม่เกิน <strong>10 MB</strong> ต่อไฟล์'),
        )
    );
} elseif ($currentPage === 'documents' && $currentAction === 'detail') {
    $tips = array('title'=>'คำแนะนำ: รายละเอียด','icon'=>'fas fa-file-alt','color'=>'amber',
        'items'=>array(
            array('icon'=>'fas fa-eye','color'=>'text-sky-600', 'text'=>'คลิกปุ่ม "ดูเอกสาร" เพื่อเปิด PDF ในหน้าต่างใหม่'),
            array('icon'=>'fas fa-history', 'color'=>'text-slate-500','text'=>'ดูประวัติการดำเนินการทั้งหมดได้ที่ส่วน "ประวัติ" ด้านล่าง'),
        )
    );
    if (in_array('inspector', $userRoles))
        $tips['items'][] = array('icon'=>'fas fa-hashtag','color'=>'text-blue-600','text'=>'กรอก <strong>เลขรับหนังสือ</strong> ให้ครบก่อนกด "ส่งต่ออนุมัติ"');
    if (in_array('approver', $userRoles))
        $tips['items'][] = array('icon'=>'fas fa-check','color'=>'text-sky-600','text'=>'ตรวจสอบเอกสารทุกไฟล์ก่อนกด "อนุมัติ"');
} elseif ($currentPage === 'documents' && $currentAction === 'edit') {
    $tips = array('title'=>'คำแนะนำ: แก้ไขเอกสาร','icon'=>'fas fa-edit','color'=>'red',
        'items'=>array(
            array('icon'=>'fas fa-exclamation-triangle','color'=>'text-red-600', 'text'=>'อ่านเหตุผลที่เอกสารถูกส่งกลับก่อนแก้ไข'),
            array('icon'=>'fas fa-file-pdf',            'color'=>'text-slate-500','text'=>'ไม่จำเป็นต้องอัปโหลดใหม่ทุกไฟล์ — เลือกเฉพาะไฟล์ที่ต้องการเปลี่ยน'),
            array('icon'=>'fas fa-paper-plane',         'color'=>'text-green-600','text'=>'กด "ส่งเอกสารใหม่" เพื่อให้ผู้ตรวจสอบดำเนินการต่อ'),
        )
    );
} elseif ($currentPage === 'reports') {
    $tips = array('title'=>'คำแนะนำ: รายงาน','icon'=>'fas fa-chart-bar','color'=>'sky',
        'items'=>array(
            array('icon'=>'fas fa-filter',      'color'=>'text-blue-600','text'=>'กรองข้อมูลด้วยปีบัญชี สถานะ หรือช่วงวันที่ แล้วกด "ค้นหา"'),
            array('icon'=>'fas fa-file-excel',  'color'=>'text-green-600','text'=>'กด "Export CSV" เพื่อดาวน์โหลดข้อมูลไปใช้ใน Excel'),
            array('icon'=>'fas fa-chart-pie',   'color'=>'text-sky-600',   'text'=>'ดูสรุปจำนวนเอกสารแยกตามสถานะได้จากการ์ดด้านบน'),
        )
    );
} elseif ($currentPage === 'issues' && $currentAction === 'index') {
    $tips = array('title'=>'คำแนะนำ: แจ้งปัญหา','icon'=>'fas fa-exclamation-circle','color'=>'red',
        'items'=>array(
            array('icon'=>'fas fa-plus','color'=>'text-green-600','text'=>'กด "แจ้งปัญหาใหม่" เพื่อรายงานปัญหาการใช้งานโปรแกรมของสหกรณ์'),
            array('icon'=>'fas fa-filter','color'=>'text-blue-600','text'=>'กรองรายการด้วยสถานะหรือคำค้นหา'),
            array('icon'=>'fas fa-landmark','color'=>'text-sky-600','text'=>'สำนักงานจังหวัดจะเห็นเฉพาะรายการของสหกรณ์ในความรับผิดชอบของตนเอง'),
        )
    );
} elseif ($currentPage === 'issues' && $currentAction === 'create') {
    $tips = array('title'=>'คำแนะนำ: แจ้งปัญหา','icon'=>'fas fa-bug','color'=>'red',
        'items'=>array(
            array('icon'=>'fas fa-building','color'=>'text-blue-600','text'=>'เลือกประเภทและชื่อสหกรณ์ที่พบปัญหาให้ถูกต้อง'),
            array('icon'=>'fas fa-align-left','color'=>'text-slate-500','text'=>'อธิบายรายละเอียดปัญหาให้ชัดเจนเพื่อความรวดเร็วในการแก้ไข'),
        )
    );
} elseif ($currentPage === 'issues' && $currentAction === 'detail') {
    $tips = array('title'=>'คำแนะนำ: รายละเอียดปัญหา','icon'=>'fas fa-tasks','color'=>'red',
        'items'=>array(
            array('icon'=>'fas fa-user-check','color'=>'text-blue-600','text'=>'ผู้แจ้งเรื่องเป็นผู้เลือกเองว่าจะ "ดำเนินการเอง" หรือ "ส่งต่อส่วนกลาง"'),
            array('icon'=>'fas fa-user-shield','color'=>'text-purple-600','text'=>'การ "รับเรื่อง" ที่ส่วนกลาง ทำได้เฉพาะผู้ดูแลระบบ (admin) ของส่วนกลางเท่านั้น'),
            array('icon'=>'fas fa-paperclip','color'=>'text-slate-500','text'=>'สามารถแนบไฟล์ภาพหน้าจอหรือ PDF ประกอบการแจ้งปัญหาได้'),
            array('icon'=>'fas fa-bell','color'=>'text-amber-500','text'=>'ผู้เกี่ยวข้องจะได้รับการแจ้งเตือนทุกครั้งที่มีการเปลี่ยนสถานะ'),
            array('icon'=>'fas fa-check','color'=>'text-green-600','text'=>'เมื่อแก้ไขปัญหาเสร็จแล้ว กด "เสร็จสิ้น" พร้อมบันทึกผลการดำเนินการ'),
        ),
        'extra'=>array('title'=>'ขั้นตอนการรับเรื่อง','rows'=>array(
            array('icon'=>'fas fa-arrow-right','desc'=>'แบบที่ 1: รอตรวจสอบ → กำลังดำเนินการ → สำเร็จ'),
            array('icon'=>'fas fa-arrow-right','desc'=>'แบบที่ 2: รอตรวจสอบ → ส่งส่วนกลาง → กำลังดำเนินการ → สำเร็จ'),
        ))
    );
} elseif ($currentPage === 'issue_reports') {
    $tips = array('title'=>'คำแนะนำ: รายงานแจ้งปัญหา','icon'=>'fas fa-chart-bar','color'=>'red',
        'items'=>array(
            array('icon'=>'fas fa-filter',    'color'=>'text-blue-600','text'=>'กรองข้อมูลด้วยสถานะ ประเภทปัญหา โปรแกรม หรือช่วงวันที่'),
            array('icon'=>'fas fa-file-excel','color'=>'text-green-600','text'=>'กด "Export CSV" เพื่อดาวน์โหลดข้อมูลไปใช้ใน Excel'),
            array('icon'=>'fas fa-chart-pie', 'color'=>'text-sky-600',   'text'=>'ดูสรุปจำนวนเรื่องแยกตามสถานะได้จากการ์ดด้านบน'),
            array('icon'=>'fas fa-landmark',  'color'=>'text-slate-500','text'=>'สำนักงานจังหวัดจะเห็นเฉพาะข้อมูลของสหกรณ์ในความรับผิดชอบของตนเอง'),
        )
    );
} elseif ($currentPage === 'users' && $currentAction === 'index') {
    $tips = array('title'=>'คำแนะนำ: จัดการสมาชิก','icon'=>'fas fa-users','color'=>'blue',
        'items'=>array(
            array('icon'=>'fas fa-plus','color'=>'text-green-600','text'=>'กด "เพิ่มสมาชิก" เพื่อสร้างบัญชีผู้ใช้ใหม่'),
            array('icon'=>'fas fa-edit','color'=>'text-blue-600','text'=>'กดปุ่มดินสอเพื่อแก้ไขข้อมูลหรือเปลี่ยนบทบาทผู้ใช้'),
            array('icon'=>'fas fa-ban', 'color'=>'text-red-600', 'text'=>'กดปุ่มห้ามเพื่อระงับการใช้งาน (ไม่ได้ลบข้อมูล)'),
        ),
        'extra'=>array('title'=>'บทบาทในระบบ','rows'=>array(
            array('badge'=>'bg-slate-500',        'label'=>'submitter','desc'=>'ส่งและติดตามเอกสารของตนเอง'),
            array('badge'=>'bg-blue-600',         'label'=>'inspector','desc'=>'ตรวจสอบและกรอกเลขรับ'),
            array('badge'=>'bg-sky-600',          'label'=>'approver', 'desc'=>'อนุมัติเอกสารที่ผ่านการตรวจ'),
            array('badge'=>'bg-purple-600',       'label'=>'operator', 'desc'=>'บันทึกผลการดำเนินการ'),
            array('badge'=>'bg-red-600',          'label'=>'admin',    'desc'=>'เข้าถึงได้ทุกส่วน'),
        ))
    );
} elseif ($currentPage === 'notifications') {
    $tips = array('title'=>'คำแนะนำ: การแจ้งเตือน','icon'=>'fas fa-bell','color'=>'sky',
        'items'=>array(
            array('icon'=>'fas fa-circle',        'color'=>'text-blue-600', 'text'=>'จุดสีน้ำเงิน = ยังไม่ได้อ่าน'),
            array('icon'=>'fas fa-mouse-pointer', 'color'=>'text-slate-500','text'=>'คลิกที่การแจ้งเตือนเพื่อไปยังเอกสารที่เกี่ยวข้อง'),
            array('icon'=>'fas fa-check-double',  'color'=>'text-green-600', 'text'=>'กด "ทำเครื่องหมายอ่านทั้งหมด" เพื่อล้างจำนวนแจ้งเตือน'),
        )
    );
} elseif ($currentPage === 'cooperatives') {
    $tips = array('title'=>'คำแนะนำ: จัดการสหกรณ์','icon'=>'fas fa-building','color'=>'amber',
        'items'=>array(
            array('icon'=>'fas fa-code',              'color'=>'text-blue-600','text'=>'<strong>รหัสสหกรณ์</strong> ต้องไม่ซ้ำกัน ใช้รูปแบบ เช่น KKN001, UDN002'),
            array('icon'=>'fas fa-link',              'color'=>'text-slate-500','text'=>'สหกรณ์ต้องผูกกับ <strong>สำนักงาน</strong> ที่รับผิดชอบ'),
            array('icon'=>'fas fa-exclamation-circle','color'=>'text-red-600','text'=>'การลบสหกรณ์จะกระทบเอกสารที่อ้างอิงอยู่ ควรระมัดระวัง'),
        )
    );
} elseif ($currentPage === 'announcements') {
    $tips = array('title'=>'คำแนะนำ: จัดการหน้าหลัก','icon'=>'fas fa-bullhorn','color'=>'sky',
        'items'=>array(
            array('icon'=>'fas fa-thumbtack','color'=>'text-red-600','text'=>'ประกาศที่ปักหมุดจะแสดงด้านบนสุดของหน้าหลัก'),
            array('icon'=>'fab fa-youtube',  'color'=>'text-red-600','text'=>'รองรับลิงก์วีดีโอจาก YouTube เท่านั้น'),
            array('icon'=>'fas fa-trash',    'color'=>'text-slate-500','text'=>'การลบเป็นการปิดใช้งาน ไม่ได้ลบข้อมูลถาวร'),
        )
    );
} else {
    $tips = array('title'=>'คำแนะนำการใช้งาน','icon'=>'fas fa-info-circle','color'=>'slate',
        'items'=>array(
            array('icon'=>'fas fa-bell','color'=>'text-blue-600',  'text'=>'ระบบแจ้งเตือนเมื่อเอกสารเปลี่ยนสถานะ'),
            array('icon'=>'fas fa-user','color'=>'text-slate-500','text'=>'แก้ไขโปรไฟล์และรหัสผ่านได้ที่เมนูชื่อผู้ใช้มุมบนขวา'),
        )
    );
}

$headerColorMap = array(
    'blue'=>'bg-blue-600','green'=>'bg-green-700','amber'=>'bg-amber-600','red'=>'bg-red-600',
    'sky'=>'bg-sky-600','slate'=>'bg-slate-500',
);
$headerBg = isset($headerColorMap[$tips['color']]) ? $headerColorMap[$tips['color']] : 'bg-slate-500';
?>

<div id="helpSidebar" class="bg-white border-l border-slate-200 flex flex-col
       fixed top-[52px] right-0 w-64 h-[calc(100vh-92px)] z-40 shadow-xl overflow-y-auto overflow-x-hidden
       transition-transform duration-300 translate-x-full
       md:static md:translate-x-0 md:w-56 xl:w-60 md:h-auto md:shadow-none md:flex-shrink-0">
  <button id="helpToggleBtn" onclick="toggleHelpSidebar()" title="คำแนะนำ"
          class="md:hidden fixed bottom-14 right-4 w-11 h-11 rounded-full bg-[#1565c0] text-white shadow-lg flex items-center justify-center text-lg z-[1055] hover:scale-105 transition-transform">
    <i class="fas fa-question"></i>
  </button>

  <div class="<?php echo $headerBg; ?> text-white px-3.5 py-2.5 text-sm font-semibold flex items-center justify-between flex-shrink-0 sticky top-0 z-10">
    <span><i class="<?php echo $tips['icon']; ?> mr-2"></i><?php echo $tips['title']; ?></span>
    <button class="md:hidden text-white/80 hover:text-white" onclick="toggleHelpSidebar()"><i class="fas fa-times"></i></button>
  </div>

  <div class="p-3 overflow-y-auto flex-1">
    <ul class="list-none p-0 m-0">
      <?php foreach ($tips['items'] as $tip): ?>
      <li class="flex gap-2 items-start py-1.5 border-b border-slate-100 text-[0.8rem] leading-relaxed text-slate-600 last:border-b-0">
        <i class="<?php echo $tip['icon']; ?> <?php echo $tip['color']; ?> flex-shrink-0 mt-0.5 w-3.5 text-center"></i>
        <span><?php echo $tip['text']; ?></span>
      </li>
      <?php endforeach; ?>
    </ul>

    <?php if (!empty($tips['extra'])): ?>
    <div class="bg-slate-50 rounded-md px-2.5 py-2 mt-2.5">
      <div class="text-[0.75rem] font-bold text-slate-500 mb-1.5 uppercase tracking-wide"><?php echo $tips['extra']['title']; ?></div>
      <?php foreach ($tips['extra']['rows'] as $row): ?>
      <div class="flex items-center gap-1 py-0.5 text-[0.78rem] flex-wrap">
        <?php if (isset($row['badge'])): ?>
        <span class="<?php echo $row['badge']; ?> text-white text-[0.7rem] rounded px-1.5 py-0.5 mr-1"><?php echo getRoleLabel($row['label']); ?></span>
        <span class="text-slate-500 text-[0.78rem]"><?php echo $row['desc']; ?></span>
        <?php else: ?>
        <i class="<?php echo $row['icon']; ?> mr-2 w-4 text-center"></i>
        <span class="text-slate-500 text-[0.78rem]"><?php echo $row['desc']; ?></span>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if ($currentPage === 'documents' || $currentPage === 'dashboard'): ?>
    <div class="mt-3">
      <div class="text-[0.75rem] font-bold text-slate-500 mb-1.5 uppercase tracking-wide">ขั้นตอนการนำส่งเอกสาร</div>
      <div class="flex items-center flex-wrap gap-0.5 mt-1.5">
        <?php
        $wfSteps = array('นำส่ง','ตรวจสอบ','อนุมัติ','ดำเนินการ','เสร็จสิ้น');
        $wfColors = array('bg-amber-500','bg-blue-600','bg-sky-600','bg-purple-600','bg-green-600');
        foreach ($wfSteps as $wi => $wlabel):
        ?>
        <?php if ($wi > 0): ?><div class="text-slate-400 text-sm px-0.5 pb-2">›</div><?php endif; ?>
        <div class="flex flex-col items-center gap-0.5">
          <div class="w-2.5 h-2.5 rounded-full <?php echo $wfColors[$wi]; ?>"></div>
          <div class="text-[0.68rem] text-slate-500 whitespace-nowrap"><?php echo $wlabel; ?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <?php if ($currentPage === 'issues' || $currentPage === 'issue_reports' || $currentPage === 'dashboard'): ?>
    <div class="mt-3">
      <div class="text-[0.75rem] font-bold text-slate-500 mb-1.5 uppercase tracking-wide">ขั้นตอนการแจ้งปัญหา</div>
      <div class="flex items-center flex-wrap gap-0.5 mt-1.5">
        <?php
        $issSteps = array('รอตรวจสอบ','ส่งส่วนกลาง','ดำเนินการ','สำเร็จ');
        $issColors = array('bg-amber-500','bg-purple-600','bg-blue-600','bg-green-600');
        foreach ($issSteps as $wi => $wlabel):
        ?>
        <?php if ($wi > 0): ?><div class="text-slate-400 text-sm px-0.5 pb-2">›</div><?php endif; ?>
        <div class="flex flex-col items-center gap-0.5">
          <div class="w-2.5 h-2.5 rounded-full <?php echo $issColors[$wi]; ?>"></div>
          <div class="text-[0.68rem] text-slate-500 whitespace-nowrap"><?php echo $wlabel; ?></div>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="text-slate-400 mt-1 text-[0.72rem]">* "ส่งส่วนกลาง" เป็นทางเลือก ใช้เฉพาะกรณีสำนักงานจังหวัดส่งต่อให้ส่วนกลางดำเนินการ</div>
    </div>
    <?php endif; ?>
  </div>
</div>
