<?php
$currentPage   = isset($_GET['page'])   ? $_GET['page']   : 'dashboard';
$currentAction = isset($_GET['action']) ? $_GET['action'] : 'index';
$userRoles     = isset($_SESSION['roles']) ? $_SESSION['roles'] : array($_SESSION['role']);

$tips = array();

if ($currentPage === 'dashboard') {
    $tips = array('title'=>'คำแนะนำ: หน้าหลัก','icon'=>'fas fa-house-user','color'=>'primary',
        'items'=>array(
            array('icon'=>'fas fa-bullhorn','color'=>'text-warning','text'=>'ติดตามประกาศล่าสุดจากหน่วยงานได้ที่หน้าหลัก'),
            array('icon'=>'fas fa-video','color'=>'text-danger',   'text'=>'รับชมวีดีโอสอนการใช้งานโปรแกรมต่าง ๆ ได้ที่นี่'),
        )
    );
    if (in_array('admin', $userRoles))
        $tips['items'][] = array('icon'=>'fas fa-cog','color'=>'text-info','text'=>'ผู้ดูแลระบบสามารถเพิ่ม/ลบประกาศและวีดีโอได้ที่ "จัดการหน้าหลัก"');
    $tips['items'][] = array('icon'=>'fas fa-exclamation-circle','color'=>'text-danger','text'=>'พบปัญหาการใช้งานโปรแกรม? กด "แจ้งปัญหาการใช้งาน" ได้ทันที');
} elseif ($currentPage === 'documents' && $currentAction === 'index') {
    $tips = array('title'=>'คำแนะนำ: รายการเอกสาร','icon'=>'fas fa-list','color'=>'primary',
        'items'=>array(
            array('icon'=>'fas fa-filter','color'=>'text-primary','text'=>'กรองรายการด้วย สถานะ / คำค้น / ช่วงวันที่ แล้วกด "ค้นหา"'),
            array('icon'=>'fas fa-eye','color'=>'text-warning',   'text'=>'คลิกปุ่ม "ดู" เพื่อดูรายละเอียดและดำเนินการกับเอกสาร'),
        )
    );
    if (in_array('submitter', $userRoles)) {
        $tips['items'][] = array('icon'=>'fas fa-plus','color'=>'text-success','text'=>'กดปุ่ม "นำส่งเอกสารใหม่" สีเขียวด้านบนขวา');
        $tips['items'][] = array('icon'=>'fas fa-edit','color'=>'text-danger', 'text'=>'เอกสารที่ "ส่งกลับแก้ไข" จะมีปุ่มสีเขียวให้แก้ไขได้');
    }
    if (in_array('inspector', $userRoles)) {
        $tips['items'][] = array('icon'=>'fas fa-search','color'=>'text-info',   'text'=>'กรองสถานะ "รอตรวจสอบ" เพื่อดูงานที่รอดำเนินการ');
        $tips['items'][] = array('icon'=>'fas fa-check','color'=>'text-success', 'text'=>'เปิดเอกสาร กรอกเลขรับหนังสือ แล้วกด "ส่งต่ออนุมัติ"');
    }
    if (in_array('approver', $userRoles)) {
        $tips['items'][] = array('icon'=>'fas fa-check-double','color'=>'text-info',   'text'=>'ทำเครื่องหมายเลือกหลายรายการแล้วกด "อนุมัติที่เลือก" ได้พร้อมกัน');
        $tips['items'][] = array('icon'=>'fas fa-filter',      'color'=>'text-warning','text'=>'กรองสถานะ "รออนุมัติ" เพื่อดูเอกสารที่รอการพิจารณา');
    }
} elseif ($currentPage === 'documents' && $currentAction === 'create') {
    $tips = array('title'=>'คำแนะนำ: นำส่งเอกสาร','icon'=>'fas fa-upload','color'=>'success',
        'items'=>array(
            array('icon'=>'fas fa-1','color'=>'text-primary','text'=>'<strong>ขั้นที่ 1</strong> เลือกประเภทสหกรณ์ก่อน ระบบจะโหลดรายชื่อให้อัตโนมัติ'),
            array('icon'=>'fas fa-2','color'=>'text-primary','text'=>'<strong>ขั้นที่ 2</strong> เลือกชื่อสหกรณ์จากรายการที่ปรากฏ'),
            array('icon'=>'fas fa-3','color'=>'text-primary','text'=>'<strong>ขั้นที่ 3</strong> เลือกปีบัญชีและเลขที่หนังสือ'),
            array('icon'=>'fas fa-4','color'=>'text-primary','text'=>'<strong>ขั้นที่ 4</strong> อัปโหลดไฟล์ PDF ครบทั้ง 4 ไฟล์'),
            array('icon'=>'fas fa-file-pdf','color'=>'text-danger','text'=>'รับเฉพาะไฟล์ <strong>PDF</strong> เท่านั้น ขนาดไม่เกิน <strong>10 MB</strong> ต่อไฟล์'),
        )
    );
} elseif ($currentPage === 'documents' && $currentAction === 'detail') {
    $tips = array('title'=>'คำแนะนำ: รายละเอียด','icon'=>'fas fa-file-alt','color'=>'warning',
        'items'=>array(
            array('icon'=>'fas fa-eye','color'=>'text-info', 'text'=>'คลิกปุ่ม "ดูเอกสาร" เพื่อเปิด PDF ในหน้าต่างใหม่'),
            array('icon'=>'fas fa-history', 'color'=>'text-secondary','text'=>'ดูประวัติการดำเนินการทั้งหมดได้ที่ส่วน "ประวัติ" ด้านล่าง'),
        )
    );
    if (in_array('inspector', $userRoles))
        $tips['items'][] = array('icon'=>'fas fa-hashtag','color'=>'text-primary','text'=>'กรอก <strong>เลขรับหนังสือ</strong> ให้ครบก่อนกด "ส่งต่ออนุมัติ"');
    if (in_array('approver', $userRoles))
        $tips['items'][] = array('icon'=>'fas fa-check','color'=>'text-info','text'=>'ตรวจสอบเอกสารทุกไฟล์ก่อนกด "อนุมัติ"');
} elseif ($currentPage === 'documents' && $currentAction === 'edit') {
    $tips = array('title'=>'คำแนะนำ: แก้ไขเอกสาร','icon'=>'fas fa-edit','color'=>'danger',
        'items'=>array(
            array('icon'=>'fas fa-exclamation-triangle','color'=>'text-danger', 'text'=>'อ่านเหตุผลที่เอกสารถูกส่งกลับก่อนแก้ไข'),
            array('icon'=>'fas fa-file-pdf',            'color'=>'text-secondary','text'=>'ไม่จำเป็นต้องอัปโหลดใหม่ทุกไฟล์ — เลือกเฉพาะไฟล์ที่ต้องการเปลี่ยน'),
            array('icon'=>'fas fa-paper-plane',         'color'=>'text-success','text'=>'กด "ส่งเอกสารใหม่" เพื่อให้ผู้ตรวจสอบดำเนินการต่อ'),
        )
    );
} elseif ($currentPage === 'reports') {
    $tips = array('title'=>'คำแนะนำ: รายงาน','icon'=>'fas fa-chart-bar','color'=>'info',
        'items'=>array(
            array('icon'=>'fas fa-filter',      'color'=>'text-primary','text'=>'กรองข้อมูลด้วยปีบัญชี สถานะ หรือช่วงวันที่ แล้วกด "ค้นหา"'),
            array('icon'=>'fas fa-file-excel',  'color'=>'text-success','text'=>'กด "Export CSV" เพื่อดาวน์โหลดข้อมูลไปใช้ใน Excel'),
            array('icon'=>'fas fa-chart-pie',   'color'=>'text-info',   'text'=>'ดูสรุปจำนวนเอกสารแยกตามสถานะได้จากการ์ดด้านบน'),
        )
    );
} elseif ($currentPage === 'issues' && $currentAction === 'index') {
    $tips = array('title'=>'คำแนะนำ: แจ้งปัญหา','icon'=>'fas fa-exclamation-circle','color'=>'danger',
        'items'=>array(
            array('icon'=>'fas fa-plus','color'=>'text-success','text'=>'กด "แจ้งปัญหาใหม่" เพื่อรายงานปัญหาการใช้งานโปรแกรมของสหกรณ์'),
            array('icon'=>'fas fa-filter','color'=>'text-primary','text'=>'กรองรายการด้วยสถานะหรือคำค้นหา'),
            array('icon'=>'fas fa-landmark','color'=>'text-info','text'=>'สำนักงานจังหวัดจะเห็นเฉพาะรายการของสหกรณ์ในความรับผิดชอบของตนเอง'),
        )
    );
} elseif ($currentPage === 'issues' && $currentAction === 'create') {
    $tips = array('title'=>'คำแนะนำ: แจ้งปัญหา','icon'=>'fas fa-bug','color'=>'danger',
        'items'=>array(
            array('icon'=>'fas fa-building','color'=>'text-primary','text'=>'เลือกประเภทและชื่อสหกรณ์ที่พบปัญหาให้ถูกต้อง'),
            array('icon'=>'fas fa-align-left','color'=>'text-secondary','text'=>'อธิบายรายละเอียดปัญหาให้ชัดเจนเพื่อความรวดเร็วในการแก้ไข'),
        )
    );
} elseif ($currentPage === 'issues' && $currentAction === 'detail') {
    $tips = array('title'=>'คำแนะนำ: รายละเอียดปัญหา','icon'=>'fas fa-tasks','color'=>'danger',
        'items'=>array(
            array('icon'=>'fas fa-user-check','color'=>'text-primary','text'=>'ผู้แจ้งเรื่องเป็นผู้เลือกเองว่าจะ "ดำเนินการเอง" หรือ "ส่งต่อส่วนกลาง"'),
            array('icon'=>'fas fa-user-shield','color'=>'text-purple','text'=>'การ "รับเรื่อง" ที่ส่วนกลาง ทำได้เฉพาะผู้ดูแลระบบ (admin) ของส่วนกลางเท่านั้น'),
            array('icon'=>'fas fa-paperclip','color'=>'text-secondary','text'=>'สามารถแนบไฟล์ภาพหน้าจอหรือ PDF ประกอบการแจ้งปัญหาได้'),
            array('icon'=>'fas fa-bell','color'=>'text-warning','text'=>'ผู้เกี่ยวข้องจะได้รับการแจ้งเตือนทุกครั้งที่มีการเปลี่ยนสถานะ'),
            array('icon'=>'fas fa-check','color'=>'text-success','text'=>'เมื่อแก้ไขปัญหาเสร็จแล้ว กด "เสร็จสิ้น" พร้อมบันทึกผลการดำเนินการ'),
        ),
        'extra'=>array('title'=>'ขั้นตอนการรับเรื่อง','rows'=>array(
            array('icon'=>'fas fa-arrow-right','desc'=>'แบบที่ 1: รอตรวจสอบ → กำลังดำเนินการ → สำเร็จ'),
            array('icon'=>'fas fa-arrow-right','desc'=>'แบบที่ 2: รอตรวจสอบ → ส่งส่วนกลาง → กำลังดำเนินการ → สำเร็จ'),
        ))
    );
} elseif ($currentPage === 'issue_reports') {
    $tips = array('title'=>'คำแนะนำ: รายงานแจ้งปัญหา','icon'=>'fas fa-chart-bar','color'=>'danger',
        'items'=>array(
            array('icon'=>'fas fa-filter',    'color'=>'text-primary','text'=>'กรองข้อมูลด้วยสถานะ ประเภทปัญหา โปรแกรม หรือช่วงวันที่'),
            array('icon'=>'fas fa-file-excel','color'=>'text-success','text'=>'กด "Export CSV" เพื่อดาวน์โหลดข้อมูลไปใช้ใน Excel'),
            array('icon'=>'fas fa-chart-pie', 'color'=>'text-info',   'text'=>'ดูสรุปจำนวนเรื่องแยกตามสถานะได้จากการ์ดด้านบน'),
            array('icon'=>'fas fa-landmark',  'color'=>'text-secondary','text'=>'สำนักงานจังหวัดจะเห็นเฉพาะข้อมูลของสหกรณ์ในความรับผิดชอบของตนเอง'),
        )
    );
} elseif ($currentPage === 'users' && $currentAction === 'index') {
    $tips = array('title'=>'คำแนะนำ: จัดการสมาชิก','icon'=>'fas fa-users','color'=>'primary',
        'items'=>array(
            array('icon'=>'fas fa-plus','color'=>'text-success','text'=>'กด "เพิ่มสมาชิก" เพื่อสร้างบัญชีผู้ใช้ใหม่'),
            array('icon'=>'fas fa-edit','color'=>'text-primary','text'=>'กดปุ่มดินสอเพื่อแก้ไขข้อมูลหรือเปลี่ยนบทบาทผู้ใช้'),
            array('icon'=>'fas fa-ban', 'color'=>'text-danger', 'text'=>'กดปุ่มห้ามเพื่อระงับการใช้งาน (ไม่ได้ลบข้อมูล)'),
        ),
        'extra'=>array('title'=>'บทบาทในระบบ','rows'=>array(
            array('badge'=>'bg-secondary',      'label'=>'submitter','desc'=>'ส่งและติดตามเอกสารของตนเอง'),
            array('badge'=>'bg-primary',        'label'=>'inspector','desc'=>'ตรวจสอบและกรอกเลขรับ'),
            array('badge'=>'bg-info text-dark', 'label'=>'approver', 'desc'=>'อนุมัติเอกสารที่ผ่านการตรวจ'),
            array('badge'=>'badge-purple',      'label'=>'operator', 'desc'=>'บันทึกผลการดำเนินการ'),
            array('badge'=>'bg-danger',         'label'=>'admin',    'desc'=>'เข้าถึงได้ทุกส่วน'),
        ))
    );
} elseif ($currentPage === 'notifications') {
    $tips = array('title'=>'คำแนะนำ: การแจ้งเตือน','icon'=>'fas fa-bell','color'=>'info',
        'items'=>array(
            array('icon'=>'fas fa-circle',        'color'=>'text-primary', 'text'=>'จุดสีน้ำเงิน = ยังไม่ได้อ่าน'),
            array('icon'=>'fas fa-mouse-pointer', 'color'=>'text-secondary','text'=>'คลิกที่การแจ้งเตือนเพื่อไปยังเอกสารที่เกี่ยวข้อง'),
            array('icon'=>'fas fa-check-double',  'color'=>'text-success', 'text'=>'กด "ทำเครื่องหมายอ่านทั้งหมด" เพื่อล้างจำนวนแจ้งเตือน'),
        )
    );
} elseif ($currentPage === 'cooperatives') {
    $tips = array('title'=>'คำแนะนำ: จัดการสหกรณ์','icon'=>'fas fa-building','color'=>'warning',
        'items'=>array(
            array('icon'=>'fas fa-code',              'color'=>'text-primary','text'=>'<strong>รหัสสหกรณ์</strong> ต้องไม่ซ้ำกัน ใช้รูปแบบ เช่น KKN001, UDN002'),
            array('icon'=>'fas fa-link',              'color'=>'text-secondary','text'=>'สหกรณ์ต้องผูกกับ <strong>สำนักงาน</strong> ที่รับผิดชอบ'),
            array('icon'=>'fas fa-exclamation-circle','color'=>'text-danger','text'=>'การลบสหกรณ์จะกระทบเอกสารที่อ้างอิงอยู่ ควรระมัดระวัง'),
        )
    );
} elseif ($currentPage === 'announcements') {
    $tips = array('title'=>'คำแนะนำ: จัดการหน้าหลัก','icon'=>'fas fa-bullhorn','color'=>'info',
        'items'=>array(
            array('icon'=>'fas fa-thumbtack','color'=>'text-danger','text'=>'ประกาศที่ปักหมุดจะแสดงด้านบนสุดของหน้าหลัก'),
            array('icon'=>'fab fa-youtube',  'color'=>'text-danger','text'=>'รองรับลิงก์วีดีโอจาก YouTube เท่านั้น'),
            array('icon'=>'fas fa-trash',    'color'=>'text-secondary','text'=>'การลบเป็นการปิดใช้งาน ไม่ได้ลบข้อมูลถาวร'),
        )
    );
} else {
    $tips = array('title'=>'คำแนะนำการใช้งาน','icon'=>'fas fa-info-circle','color'=>'secondary',
        'items'=>array(
            array('icon'=>'fas fa-bell','color'=>'text-primary',  'text'=>'ระบบแจ้งเตือนเมื่อเอกสารเปลี่ยนสถานะ'),
            array('icon'=>'fas fa-user','color'=>'text-secondary','text'=>'แก้ไขโปรไฟล์และรหัสผ่านได้ที่เมนูชื่อผู้ใช้มุมบนขวา'),
        )
    );
}
?>

<div class="help-sidebar" id="helpSidebar">
  <button class="help-toggle-btn d-lg-none" id="helpToggleBtn" onclick="toggleHelpSidebar()" title="คำแนะนำ">
    <i class="fas fa-question"></i>
  </button>

  <div class="help-sidebar-inner">
    <div class="help-sidebar-header bg-<?php echo $tips['color']; ?> text-white">
      <span><i class="<?php echo $tips['icon']; ?> me-2"></i><?php echo $tips['title']; ?></span>
      <button class="btn-close btn-close-white btn-sm d-lg-none" onclick="toggleHelpSidebar()"></button>
    </div>

    <div class="help-sidebar-body">
      <ul class="help-list">
        <?php foreach ($tips['items'] as $tip): ?>
        <li class="help-list-item">
          <i class="<?php echo $tip['icon']; ?> <?php echo $tip['color']; ?> help-list-icon"></i>
          <span><?php echo $tip['text']; ?></span>
        </li>
        <?php endforeach; ?>
      </ul>

      <?php if (!empty($tips['extra'])): ?>
      <div class="help-extra">
        <div class="help-extra-title"><?php echo $tips['extra']['title']; ?></div>
        <?php foreach ($tips['extra']['rows'] as $row): ?>
        <div class="help-extra-row">
          <?php if (isset($row['badge'])): ?>
          <span class="badge <?php echo $row['badge']; ?> me-1" style="font-size:0.7rem;"><?php echo getRoleLabel($row['label']); ?></span>
          <span class="text-muted" style="font-size:0.78rem;"><?php echo $row['desc']; ?></span>
          <?php else: ?>
          <i class="<?php echo $row['icon']; ?> me-2" style="width:16px;text-align:center;"></i>
          <span class="text-muted" style="font-size:0.78rem;"><?php echo $row['desc']; ?></span>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <?php if ($currentPage === 'documents' || $currentPage === 'dashboard'): ?>
      <div class="help-workflow mt-3">
        <div class="help-extra-title">ขั้นตอนการนำส่งเอกสาร</div>
        <div class="workflow-steps">
          <?php
          $wfSteps = array('นำส่ง','ตรวจสอบ','อนุมัติ','ดำเนินการ','เสร็จสิ้น');
          $wfColors = array('bg-warning','bg-primary','bg-info','bg-purple','bg-success');
          foreach ($wfSteps as $wi => $wlabel):
          ?>
          <?php if ($wi > 0): ?><div class="wf-arrow">›</div><?php endif; ?>
          <div class="wf-step">
            <div class="wf-dot <?php echo $wfColors[$wi]; ?>"></div>
            <div class="wf-label"><?php echo $wlabel; ?></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <?php if ($currentPage === 'issues' || $currentPage === 'issue_reports' || $currentPage === 'dashboard'): ?>
      <div class="help-workflow mt-3">
        <div class="help-extra-title">ขั้นตอนการแจ้งปัญหา</div>
        <div class="workflow-steps">
          <?php
          $issSteps = array('รอตรวจสอบ','ส่งส่วนกลาง','ดำเนินการ','สำเร็จ');
          $issColors = array('bg-warning','badge-purple','bg-primary','bg-success');
          foreach ($issSteps as $wi => $wlabel):
          ?>
          <?php if ($wi > 0): ?><div class="wf-arrow">›</div><?php endif; ?>
          <div class="wf-step">
            <div class="wf-dot <?php echo $issColors[$wi]; ?>"></div>
            <div class="wf-label"><?php echo $wlabel; ?></div>
          </div>
          <?php endforeach; ?>
        </div>
        <div class="text-muted mt-1" style="font-size:0.72rem;">* "ส่งส่วนกลาง" เป็นทางเลือก ใช้เฉพาะกรณีสำนักงานจังหวัดส่งต่อให้ส่วนกลางดำเนินการ</div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
