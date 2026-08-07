<div class="bg-white border-b border-slate-200 px-4 py-1.5 text-sm">
  <nav aria-label="breadcrumb">
    <ol class="flex items-center gap-1.5 text-slate-500">
      <li><a class="hover:text-[#1565c0]" href="?page=dashboard"><i class="fas fa-home"></i></a></li>
      <li class="text-slate-300">/</li>
      <li class="text-slate-700 font-medium">จัดการหน้าหลัก</li>
    </ol>
  </nav>
</div>

<main class="p-3 md:p-5 pb-6 md:pb-8 max-w-full overflow-x-hidden">

  <div class="rounded-lg border border-blue-200 px-4 md:px-5 py-3.5 flex items-center gap-3.5 flex-wrap mb-4"
       style="background: linear-gradient(135deg,#e3f2fd 0%,#f8f9ff 100%);">
    <div class="w-11 h-11 rounded-[10px] bg-sky-600 text-white flex items-center justify-center text-xl flex-shrink-0">
      <i class="fas fa-bullhorn"></i>
    </div>
    <div>
      <div class="text-base font-bold text-[#1a237e]">จัดการประกาศและวีดีโอการใช้งาน</div>
      <div class="text-sm text-slate-600">เนื้อหาที่แสดงในหน้าหลักของผู้ใช้งานทุกคน</div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">

    <!-- ประกาศ -->
    <div class="lg:col-span-1">
      <div class="bg-white border border-slate-200 rounded-md mb-3">
        <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-plus-circle mr-2 text-green-600"></i>เพิ่มประกาศใหม่</div>
        <div class="p-3.5">
          <form method="POST" action="?page=announcements&action=store">
            <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
            <div class="mb-2">
              <label class="block text-sm font-semibold text-slate-700 mb-1">หัวข้อประกาศ <span class="text-red-600">*</span></label>
              <input type="text" name="title" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" required maxlength="255">
            </div>
            <div class="mb-2">
              <label class="block text-sm font-semibold text-slate-700 mb-1">เนื้อหา <span class="text-red-600">*</span></label>
              <textarea name="content" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" rows="4" required></textarea>
            </div>
            <div class="flex items-center gap-2 mb-3">
              <input type="checkbox" name="is_pinned" value="1" class="rounded" id="isPinned">
              <label class="text-sm text-slate-600" for="isPinned">ปักหมุดแสดงด้านบนสุด</label>
            </div>
            <button type="submit" class="<?php echo uiBtnClasses('success'); ?>"><i class="fas fa-save mr-1"></i>บันทึกประกาศ</button>
          </form>
        </div>
      </div>

      <div class="bg-white border border-slate-200 rounded-md">
        <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-list mr-2 text-[#1565c0]"></i>รายการประกาศ</div>
        <div class="p-0">
          <?php if (empty($announcements)): ?>
          <div class="px-3.5 py-8 text-center text-slate-400 text-sm">
            <i class="fas fa-inbox block text-3xl mb-2 text-slate-300"></i>ยังไม่มีประกาศ
          </div>
          <?php else: foreach ($announcements as $a): ?>
          <div class="flex items-start gap-2 px-3.5 py-2.5 border-b border-slate-100 last:border-b-0 <?php echo !$a['is_active'] ? 'bg-slate-50 text-slate-400' : ''; ?>">
            <div class="flex-1 min-w-0">
              <div class="font-semibold text-sm">
                <?php if ($a['is_pinned']): ?><i class="fas fa-thumbtack text-red-600 mr-1"></i><?php endif; ?>
                <?php echo e($a['title']); ?>
                <?php if (!$a['is_active']): ?><span class="bg-slate-500 text-white text-xs rounded px-1.5 py-0.5 ml-1">ปิดใช้งาน</span><?php endif; ?>
              </div>
              <div class="text-slate-500 truncate text-xs"><?php echo e($a['content']); ?></div>
              <div class="text-slate-400 text-[0.72rem]"><?php echo e($a['author_name']); ?> | <?php echo thaiDate($a['created_at']); ?></div>
            </div>
            <?php if ($a['is_active']): ?>
            <button class="<?php echo uiBtnClasses('outline-danger'); ?> flex-shrink-0"
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
    <div class="lg:col-span-1">
      <div class="bg-white border border-slate-200 rounded-md mb-3">
        <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-video mr-2 text-red-600"></i>เพิ่มวีดีโอสอนการใช้งานโปรแกรม</div>
        <div class="p-3.5">
          <form method="POST" action="?page=announcements&action=store_video">
            <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
            <div class="mb-2">
              <label class="block text-sm font-semibold text-slate-700 mb-1">หัวข้อวีดีโอ <span class="text-red-600">*</span></label>
              <input type="text" name="title" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" required maxlength="255">
            </div>
            <div class="mb-2">
              <label class="block text-sm font-semibold text-slate-700 mb-1">ลิงก์วีดีโอ (YouTube) <span class="text-red-600">*</span></label>
              <input type="text" name="video_url" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" required
                     placeholder="https://www.youtube.com/watch?v=xxxxxxxx">
            </div>
            <div class="mb-3">
              <label class="block text-sm font-semibold text-slate-700 mb-1">คำอธิบาย</label>
              <textarea name="description" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" rows="2"></textarea>
            </div>
            <button type="submit" class="<?php echo uiBtnClasses('danger'); ?>"><i class="fas fa-save mr-1"></i>บันทึกวีดีโอ</button>
          </form>
        </div>
      </div>

      <div class="bg-white border border-slate-200 rounded-md">
        <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-list mr-2 text-[#1565c0]"></i>รายการวีดีโอ</div>
        <div class="p-0">
          <?php if (empty($videos)): ?>
          <div class="px-3.5 py-8 text-center text-slate-400 text-sm">
            <i class="fas fa-inbox block text-3xl mb-2 text-slate-300"></i>ยังไม่มีวีดีโอ
          </div>
          <?php else: foreach ($videos as $v): ?>
          <div class="flex items-start gap-2 px-3.5 py-2.5 border-b border-slate-100 last:border-b-0 <?php echo !$v['is_active'] ? 'bg-slate-50 text-slate-400' : ''; ?>">
            <div class="flex-1 min-w-0">
              <div class="font-semibold text-sm">
                <i class="fas fa-play-circle text-red-600 mr-1"></i><?php echo e($v['title']); ?>
                <?php if (!$v['is_active']): ?><span class="bg-slate-500 text-white text-xs rounded px-1.5 py-0.5 ml-1">ปิดใช้งาน</span><?php endif; ?>
              </div>
              <div class="text-slate-500 truncate text-xs"><?php echo e($v['video_url']); ?></div>
              <div class="text-slate-400 text-[0.72rem]"><?php echo e($v['author_name']); ?> | <?php echo thaiDate($v['created_at']); ?></div>
            </div>
            <?php if ($v['is_active']): ?>
            <button class="<?php echo uiBtnClasses('outline-danger'); ?> flex-shrink-0"
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
