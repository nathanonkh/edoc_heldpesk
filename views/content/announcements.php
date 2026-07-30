<div class="breadcrumb-bar">
  <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="?page=dashboard"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">จัดการประกาศ</li>
  </ol></nav>
</div>

<main class="content-area">

  <div class="page-banner mb-3">
    <div class="page-banner-icon bg-danger"><i class="fas fa-bullhorn"></i></div>
    <div class="flex-grow-1">
      <div class="page-banner-title">จัดการประกาศ</div>
      <div class="page-banner-sub">ประกาศจะแสดงในหน้าหลักของทุกคน</div>
    </div>
    <a href="?page=content&action=announcement_create" class="btn btn-success btn-sm flex-shrink-0">
      <i class="fas fa-plus me-1"></i>เพิ่มประกาศ
    </a>
  </div>

  <div class="page-card">
    <div class="page-card-header"><i class="fas fa-list me-2 text-primary"></i>รายการประกาศ <span class="badge bg-secondary ms-1"><?php echo count($announcements); ?></span></div>
    <div class="table-edms-wrap">
      <table class="table-edms">
        <thead>
          <tr><th style="width:40px;">#</th><th>หัวข้อ</th><th>ผู้สร้าง</th><th>วันที่สร้าง</th><th>สถานะ</th><th style="text-align:center;width:100px;">ดำเนินการ</th></tr>
        </thead>
        <tbody>
          <?php foreach ($announcements as $idx => $a): ?>
          <tr>
            <td><?php echo $idx + 1; ?></td>
            <td class="fw-semibold"><?php echo e($a['title']); ?></td>
            <td><?php echo e($a['author_name']); ?></td>
            <td><?php echo thaiDate($a['created_at']); ?></td>
            <td><?php echo $a['is_active'] ? '<span class="badge bg-success">แสดงผล</span>' : '<span class="badge bg-secondary">ซ่อน</span>'; ?></td>
            <td class="text-center">
              <a href="?page=content&action=announcement_edit&id=<?php echo $a['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
              <button class="btn btn-sm btn-outline-danger ms-1"
                onclick="confirmDelete('?page=content&action=announcement_delete&id=<?php echo $a['id']; ?>','<?php echo e($a['title']); ?>')">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php if (empty($announcements)): ?>
          <tr><td colspan="6" class="text-center text-muted py-4"><i class="fas fa-inbox d-block fs-2 mb-2 text-secondary"></i>ไม่พบรายการ</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</main>