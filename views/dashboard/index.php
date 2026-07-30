<div class="breadcrumb-bar">
  <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
    <li class="breadcrumb-item active"><i class="fas fa-home me-1"></i>หน้าหลัก</li>
  </ol></nav>
</div>

<main class="content-area">

  <div class="page-banner mb-3">
    <div class="page-banner-icon"><i class="fas fa-house-user"></i></div>
    <div class="flex-grow-1">
      <div class="page-banner-title">ยินดีต้อนรับ</div>
      <div class="page-banner-sub">สวัสดี <?php echo e(trim($_SESSION['prefix'].' '.$_SESSION['firstname'].' '.$_SESSION['lastname'])); ?> | <?php echo e($_SESSION['office_name']); ?></div>
    </div>
    <?php if (Auth::hasRole('admin')): ?>
    <a href="?page=announcements" class="btn btn-outline-light btn-sm flex-shrink-0">
      <i class="fas fa-cog me-1"></i>จัดการหน้าหลัก
    </a>
    <?php endif; ?>
  </div>

  <!-- ทางลัด -->
  <div class="row g-3 mb-3">
    <div class="col-6 col-lg-3">
      <a href="?page=documents&action=create" class="text-decoration-none">
        <div class="stat-card stat-card-success">
          <div class="stat-icon"><i class="fas fa-upload"></i></div>
          <div class="text-dark fw-semibold" style="font-size:0.85rem;">นำส่งเอกสาร</div>
        </div>
      </a>
    </div>
    <div class="col-6 col-lg-3">
      <a href="?page=documents" class="text-decoration-none">
        <div class="stat-card stat-card-primary">
          <div class="stat-icon"><i class="fas fa-folder-open"></i></div>
          <div class="text-dark fw-semibold" style="font-size:0.85rem;">รายการเอกสาร</div>
        </div>
      </a>
    </div>
    <div class="col-6 col-lg-3">
      <a href="?page=issues&action=create" class="text-decoration-none">
        <div class="stat-card stat-card-danger">
          <div class="stat-icon"><i class="fas fa-exclamation-circle"></i></div>
          <div class="text-dark fw-semibold" style="font-size:0.85rem;">แจ้งปัญหาการใช้งาน</div>
        </div>
      </a>
    </div>
    <div class="col-6 col-lg-3">
      <a href="?page=reports" class="text-decoration-none">
        <div class="stat-card stat-card-info">
          <div class="stat-icon"><i class="fas fa-chart-bar"></i></div>
          <div class="text-dark fw-semibold" style="font-size:0.85rem;">รายงานเอกสาร</div>
        </div>
      </a>
    </div>
  </div>

  <div class="row g-3">

    <!-- ประกาศ -->
    <div class="col-12 col-lg-6">
      <div class="page-card h-100">
        <div class="page-card-header"><span><i class="fas fa-bullhorn me-2 text-warning"></i>ประกาศ</span></div>
        <div class="page-card-body p-0" style="max-height:520px;overflow-y:auto;">
          <?php if (empty($announcements)): ?>
          <div class="px-3 py-5 text-center text-muted">
            <i class="fas fa-bullhorn d-block fs-1 mb-3 text-secondary"></i>ยังไม่มีประกาศ
          </div>
          <?php else: foreach ($announcements as $a): ?>
          <div class="px-3 py-3 border-bottom">
            <div class="fw-bold" style="font-size:0.92rem;">
              <?php if ($a['is_pinned']): ?><span class="badge bg-danger me-1"><i class="fas fa-thumbtack me-1"></i>ปักหมุด</span><?php endif; ?>
              <?php echo e($a['title']); ?>
            </div>
            <div class="text-secondary mt-1" style="font-size:0.85rem;white-space:pre-line;"><?php echo e($a['content']); ?></div>
            <div class="text-muted mt-2" style="font-size:0.75rem;">
              <i class="fas fa-user me-1"></i><?php echo e($a['author_name']); ?>
              &nbsp;|&nbsp;<i class="fas fa-clock me-1"></i><?php echo thaiDate($a['created_at']); ?>
            </div>
          </div>
          <?php endforeach; endif; ?>
        </div>
      </div>
    </div>

    <!-- วีดีโอสอนการใช้งาน -->
    <div class="col-12 col-lg-6">
      <div class="page-card h-100">
        <div class="page-card-header"><span><i class="fas fa-video me-2 text-danger"></i>วีดีโอสอนการใช้งานโปรแกรม</span></div>
        <div class="page-card-body p-0" style="max-height:520px;overflow-y:auto;">
          <?php if (empty($videos)): ?>
          <div class="px-3 py-5 text-center text-muted">
            <i class="fas fa-video-slash d-block fs-1 mb-3 text-secondary"></i>ยังไม่มีวีดีโอ
          </div>
          <?php else: ?>
          <div class="row g-3 p-3">
            <?php foreach ($videos as $v): ?>
            <div class="col-12 col-md-6">
              <div class="border rounded overflow-hidden h-100">
                <div class="ratio ratio-16x9">
                  <iframe src="<?php echo e(youtubeEmbedUrl($v['video_url'])); ?>"
                          title="<?php echo e($v['title']); ?>" allowfullscreen loading="lazy"></iframe>
                </div>
                <div class="p-2">
                  <div class="fw-semibold small text-truncate"><?php echo e($v['title']); ?></div>
                  <?php if (!empty($v['description'])): ?>
                  <div class="text-muted text-truncate" style="font-size:0.76rem;"><?php echo e($v['description']); ?></div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

  </div>

</main>
