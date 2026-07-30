<div class="breadcrumb-bar">
  <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="?page=dashboard"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">จัดการวีดีโอสอนการใช้งาน</li>
  </ol></nav>
</div>

<main class="content-area">

  <div class="page-banner mb-3">
    <div class="page-banner-icon bg-info"><i class="fas fa-video"></i></div>
    <div class="flex-grow-1">
      <div class="page-banner-title">จัดการวีดีโอสอนการใช้งาน</div>
      <div class="page-banner-sub">วีดีโอจะแสดงในหน้าหลักของทุกคน</div>
    </div>
    <a href="?page=content&action=video_create" class="btn btn-success btn-sm flex-shrink-0">
      <i class="fas fa-plus me-1"></i>เพิ่มวีดีโอ
    </a>
  </div>

  <div class="page-card">
    <div class="page-card-header"><i class="fas fa-list me-2 text-primary"></i>รายการวีดีโอ <span class="badge bg-secondary ms-1"><?php echo count($videos); ?></span></div>
    <div class="table-edms-wrap">
      <table class="table-edms">
        <thead>
          <tr><th style="width:40px;">#</th><th>ชื่อวีดีโอ</th><th>โปรแกรม</th><th>ลิงก์</th><th>สถานะ</th><th style="text-align:center;width:100px;">ดำเนินการ</th></tr>
        </thead>
        <tbody>
          <?php $programOpts = getProgramOptions(); foreach ($videos as $idx => $v): ?>
          <tr>
            <td><?php echo $idx + 1; ?></td>
            <td class="fw-semibold"><?php echo e($v['title']); ?></td>
            <td><?php echo isset($programOpts[$v['program_name']]) ? $programOpts[$v['program_name']] : e($v['program_name']); ?></td>
            <td><a href="<?php echo e($v['video_url']); ?>" target="_blank" rel="noopener" class="small"><i class="fas fa-external-link-alt me-1"></i>เปิดลิงก์</a></td>
            <td><?php echo $v['is_active'] ? '<span class="badge bg-success">แสดงผล</span>' : '<span class="badge bg-secondary">ซ่อน</span>'; ?></td>
            <td class="text-center">
              <a href="?page=content&action=video_edit&id=<?php echo $v['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
              <button class="btn btn-sm btn-outline-danger ms-1"
                onclick="confirmDelete('?page=content&action=video_delete&id=<?php echo $v['id']; ?>','<?php echo e($v['title']); ?>')">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php if (empty($videos)): ?>
          <tr><td colspan="6" class="text-center text-muted py-4"><i class="fas fa-inbox d-block fs-2 mb-2 text-secondary"></i>ไม่พบรายการ</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</main>