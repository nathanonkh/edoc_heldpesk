<?php $isEdit = isset($announcement) && $announcement; ?>
<div class="breadcrumb-bar">
  <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="?page=dashboard"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item"><a href="?page=content&action=announcements">จัดการประกาศ</a></li>
    <li class="breadcrumb-item active"><?php echo $isEdit ? 'แก้ไขประกาศ' : 'เพิ่มประกาศ'; ?></li>
  </ol></nav>
</div>

<main class="content-area">
  <div class="page-banner mb-3">
    <div class="page-banner-icon bg-danger"><i class="fas fa-bullhorn"></i></div>
    <div><div class="page-banner-title"><?php echo $isEdit ? 'แก้ไขประกาศ' : 'เพิ่มประกาศใหม่'; ?></div></div>
  </div>

  <form method="POST" action="?page=content&action=<?php echo $isEdit ? 'announcement_update' : 'announcement_store'; ?>">
    <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
    <?php if ($isEdit): ?><input type="hidden" name="id" value="<?php echo $announcement['id']; ?>"><?php endif; ?>

    <div class="page-card">
      <div class="page-card-body">
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label">หัวข้อ <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control form-control-sm" required maxlength="255"
                   value="<?php echo $isEdit ? e($announcement['title']) : ''; ?>">
          </div>
          <div class="col-12">
            <label class="form-label">เนื้อหา <span class="text-danger">*</span></label>
            <textarea name="content" class="form-control form-control-sm" rows="6" required><?php echo $isEdit ? e($announcement['content']) : ''; ?></textarea>
          </div>
          <div class="col-12">
            <div class="form-check">
              <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActiveChk"
                     <?php echo (!$isEdit || $announcement['is_active']) ? 'checked' : ''; ?>>
              <label class="form-check-label" for="isActiveChk">แสดงผลในหน้าหลัก</label>
            </div>
          </div>
        </div>
        <div class="d-flex gap-2 pt-3 border-top mt-3">
          <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save me-1"></i>บันทึก</button>
          <a href="?page=content&action=announcements" class="btn btn-outline-secondary btn-sm">ยกเลิก</a>
        </div>
      </div>
    </div>
  </form>
</main>