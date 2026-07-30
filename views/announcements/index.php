<div class="breadcrumb-bar">
  <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="?page=dashboard"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">จัดการหน้าหลัก</li>
  </ol></nav>
</div>

<main class="content-area">

  <div class="page-banner mb-3">
    <div class="page-banner-icon bg-info"><i class="fas fa-bullhorn"></i></div>
    <div>
      <div class="page-banner-title">จัดการประกาศและวีดีโอการใช้งาน</div>
      <div class="page-banner-sub">เนื้อหาที่แสดงในหน้าหลักของผู้ใช้งานทุกคน</div>
    </div>
  </div>

  <div class="row g-3">

    <!-- ประกาศ -->
    <div class="col-12 col-lg-6">
      <div class="page-card mb-3">
        <div class="page-card-header"><i class="fas fa-plus-circle me-2 text-success"></i>เพิ่มประกาศใหม่</div>
        <div class="page-card-body">
          <form method="POST" action="?page=announcements&action=store">
            <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
            <div class="mb-2">
              <label class="form-label">หัวข้อประกาศ <span class="text-danger">*</span></label>
              <input type="text" name="title" class="form-control form-control-sm" required maxlength="255">
            </div>
            <div class="mb-2">
              <label class="form-label">เนื้อหา <span class="text-danger">*</span></label>
              <textarea name="content" class="form-control form-control-sm" rows="4" required></textarea>
            </div>
            <div class="form-check mb-3">
              <input type="checkbox" name="is_pinned" value="1" class="form-check-input" id="isPinned">
              <label class="form-check-label small" for="isPinned">ปักหมุดแสดงด้านบนสุด</label>
            </div>
            <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-save me-1"></i>บันทึกประกาศ</button>
          </form>
        </div>
      </div>

      <div class="page-card">
        <div class="page-card-header"><i class="fas fa-list me-2 text-primary"></i>รายการประกาศ</div>
        <div class="page-card-body p-0">
          <?php if (empty($announcements)): ?>
          <div class="px-3 py-4 text-center text-muted small">
            <i class="fas fa-inbox d-block fs-3 mb-2 text-secondary"></i>ยังไม่มีประกาศ
          </div>
          <?php else: foreach ($announcements as $a): ?>
          <div class="d-flex align-items-start gap-2 px-3 py-2 border-bottom <?php echo !$a['is_active'] ? 'bg-light text-muted' : ''; ?>">
            <div class="flex-grow-1" style="min-width:0;">
              <div class="fw-semibold small">
                <?php if ($a['is_pinned']): ?><i class="fas fa-thumbtack text-danger me-1"></i><?php endif; ?>
                <?php echo e($a['title']); ?>
                <?php if (!$a['is_active']): ?><span class="badge bg-secondary ms-1">ปิดใช้งาน</span><?php endif; ?>
              </div>
              <div class="text-muted text-truncate" style="font-size:0.78rem;"><?php echo e($a['content']); ?></div>
              <div class="text-secondary" style="font-size:0.72rem;"><?php echo e($a['author_name']); ?> | <?php echo thaiDate($a['created_at']); ?></div>
            </div>
            <?php if ($a['is_active']): ?>
            <button class="btn btn-sm btn-outline-danger flex-shrink-0"
                    onclick="confirmDelete('?page=announcements&action=delete&id=<?php echo $a['id']; ?>','<?php echo e($a['title']); ?>')">
              <i class="fas fa-trash"></i>
            </button>
            <?php endif; ?>
          </div>
          <?php endforeach; endif; ?>
        </div>
      </div>
    </div>

    <!-- วีดีโอ -->
    <div class="col-12 col-lg-6">
      <div class="page-card mb-3">
        <div class="page-card-header"><i class="fas fa-video me-2 text-danger"></i>เพิ่มวีดีโอสอนการใช้งานโปรแกรม</div>
        <div class="page-card-body">
          <form method="POST" action="?page=announcements&action=store_video">
            <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
            <div class="mb-2">
              <label class="form-label">หัวข้อวีดีโอ <span class="text-danger">*</span></label>
              <input type="text" name="title" class="form-control form-control-sm" required maxlength="255">
            </div>
            <div class="mb-2">
              <label class="form-label">ลิงก์วีดีโอ (YouTube) <span class="text-danger">*</span></label>
              <input type="text" name="video_url" class="form-control form-control-sm" required
                     placeholder="https://www.youtube.com/watch?v=xxxxxxxx">
            </div>
            <div class="mb-3">
              <label class="form-label">คำอธิบาย</label>
              <textarea name="description" class="form-control form-control-sm" rows="2"></textarea>
            </div>
            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-save me-1"></i>บันทึกวีดีโอ</button>
          </form>
        </div>
      </div>

      <div class="page-card">
        <div class="page-card-header"><i class="fas fa-list me-2 text-primary"></i>รายการวีดีโอ</div>
        <div class="page-card-body p-0">
          <?php if (empty($videos)): ?>
          <div class="px-3 py-4 text-center text-muted small">
            <i class="fas fa-inbox d-block fs-3 mb-2 text-secondary"></i>ยังไม่มีวีดีโอ
          </div>
          <?php else: foreach ($videos as $v): ?>
          <div class="d-flex align-items-start gap-2 px-3 py-2 border-bottom <?php echo !$v['is_active'] ? 'bg-light text-muted' : ''; ?>">
            <div class="flex-grow-1" style="min-width:0;">
              <div class="fw-semibold small">
                <i class="fas fa-play-circle text-danger me-1"></i><?php echo e($v['title']); ?>
                <?php if (!$v['is_active']): ?><span class="badge bg-secondary ms-1">ปิดใช้งาน</span><?php endif; ?>
              </div>
              <div class="text-muted text-truncate" style="font-size:0.78rem;"><?php echo e($v['video_url']); ?></div>
              <div class="text-secondary" style="font-size:0.72rem;"><?php echo e($v['author_name']); ?> | <?php echo thaiDate($v['created_at']); ?></div>
            </div>
            <?php if ($v['is_active']): ?>
            <button class="btn btn-sm btn-outline-danger flex-shrink-0"
                    onclick="confirmDelete('?page=announcements&action=delete_video&id=<?php echo $v['id']; ?>','<?php echo e($v['title']); ?>')">
              <i class="fas fa-trash"></i>
            </button>
            <?php endif; ?>
          </div>
          <?php endforeach; endif; ?>
        </div>
      </div>
    </div>

  </div>

</main>
