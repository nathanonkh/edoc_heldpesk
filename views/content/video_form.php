<?php $isEdit = isset($video) && $video; $programOptions = getProgramOptions(); ?>
<div class="breadcrumb-bar">
  <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="?page=dashboard"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item"><a href="?page=content&action=videos">จัดการวีดีโอสอนการใช้งาน</a></li>
    <li class="breadcrumb-item active"><?php echo $isEdit ? 'แก้ไขวีดีโอ' : 'เพิ่มวีดีโอ'; ?></li>
  </ol></nav>
</div>

<main class="content-area">
  <div class="page-banner mb-3">
    <div class="page-banner-icon bg-info"><i class="fas fa-video"></i></div>
    <div><div class="page-banner-title"><?php echo $isEdit ? 'แก้ไขวีดีโอ' : 'เพิ่มวีดีโอใหม่'; ?></div></div>
  </div>

  <form method="POST" action="?page=content&action=<?php echo $isEdit ? 'video_update' : 'video_store'; ?>">
    <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
    <?php if ($isEdit): ?><input type="hidden" name="id" value="<?php echo $video['id']; ?>"><?php endif; ?>

    <div class="page-card">
      <div class="page-card-body">
        <div class="row g-3">
          <div class="col-12 col-md-8">
            <label class="form-label">ชื่อวีดีโอ <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control form-control-sm" required maxlength="255"
                   value="<?php echo $isEdit ? e($video['title']) : ''; ?>">
          </div>
          <div class="col-12 col-md-4">
            <label class="form-label">โปรแกรมที่เกี่ยวข้อง</label>
            <select name="program_name" class="form-select form-select-sm">
              <option value="">— ไม่ระบุ —</option>
              <?php foreach ($programOptions as $k => $v): ?>
              <option value="<?php echo $k; ?>" <?php echo ($isEdit && $video['program_name']===$k) ? 'selected' : ''; ?>><?php echo $v; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-12">
            <label class="form-label">ลิงก์วีดีโอ (YouTube) <span class="text-danger">*</span></label>
            <input type="text" name="video_url" class="form-control form-control-sm" required
                   placeholder="เช่น https://www.youtube.com/watch?v=xxxxxxxx"
                   value="<?php echo $isEdit ? e($video['video_url']) : ''; ?>">
          </div>
          <div class="col-12">
            <label class="form-label">คำอธิบาย</label>
            <textarea name="description" class="form-control form-control-sm" rows="3"><?php echo $isEdit ? e($video['description']) : ''; ?></textarea>
          </div>
          <div class="col-12">
            <div class="form-check">
              <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActiveChk"
                     <?php echo (!$isEdit || $video['is_active']) ? 'checked' : ''; ?>>
              <label class="form-check-label" for="isActiveChk">แสดงผลในหน้าหลัก</label>
            </div>
          </div>
        </div>
        <div class="d-flex gap-2 pt-3 border-top mt-3">
          <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save me-1"></i>บันทึก</button>
          <a href="?page=content&action=videos" class="btn btn-outline-secondary btn-sm">ยกเลิก</a>
        </div>
      </div>
    </div>
  </form>
</main>