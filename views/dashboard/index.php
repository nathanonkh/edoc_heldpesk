<div class="bg-white border-b border-slate-200 px-4 py-1.5 text-sm">
  <nav aria-label="breadcrumb">
    <ol class="flex items-center gap-1.5 text-slate-500">
      <li class="text-slate-700 font-medium"><i class="fas fa-home mr-1"></i>หน้าหลัก</li>
    </ol>
  </nav>
</div>

<main class="p-3 md:p-5 pb-6 md:pb-8 max-w-full overflow-x-hidden">

  <div class="rounded-lg border border-blue-200 px-4 md:px-5 py-3.5 flex items-center gap-3.5 flex-wrap mb-4"
       style="background: linear-gradient(135deg,#e3f2fd 0%,#f8f9ff 100%);">
    <div class="w-11 h-11 rounded-[10px] bg-[#1565c0] text-white flex items-center justify-center text-xl flex-shrink-0">
      <i class="fas fa-house-user"></i>
    </div>
    <div class="flex-1">
      <div class="text-base font-bold text-[#1a237e]">ยินดีต้อนรับ</div>
      <div class="text-sm text-slate-600">สวัสดี <?php echo e(trim($_SESSION['prefix'].' '.$_SESSION['firstname'].' '.$_SESSION['lastname'])); ?> | <?php echo e($_SESSION['office_name']); ?></div>
    </div>
    <?php if (Auth::hasRole('admin')): ?>
    <a href="?page=announcements" class="border border-white text-white bg-white/10 hover:bg-white/20 text-sm px-3 py-1.5 rounded-md flex-shrink-0">
      <i class="fas fa-cog mr-1"></i>จัดการหน้าหลัก
    </a>
    <?php endif; ?>
  </div>

  <!-- ทางลัด -->
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
    <a href="?page=documents&action=create" class="bg-white border border-slate-200 rounded-md p-3.5 text-center hover:-translate-y-0.5 hover:shadow-md transition-all">
      <div class="text-2xl text-green-700 mb-1"><i class="fas fa-upload"></i></div>
      <div class="text-slate-800 font-semibold text-sm">นำส่งเอกสาร</div>
    </a>
    <a href="?page=documents" class="bg-white border border-slate-200 rounded-md p-3.5 text-center hover:-translate-y-0.5 hover:shadow-md transition-all">
      <div class="text-2xl text-[#1565c0] mb-1"><i class="fas fa-folder-open"></i></div>
      <div class="text-slate-800 font-semibold text-sm">รายการเอกสาร</div>
    </a>
    <a href="?page=issues&action=create" class="bg-white border border-slate-200 rounded-md p-3.5 text-center hover:-translate-y-0.5 hover:shadow-md transition-all">
      <div class="text-2xl text-red-700 mb-1"><i class="fas fa-exclamation-circle"></i></div>
      <div class="text-slate-800 font-semibold text-sm">แจ้งปัญหาการใช้งาน</div>
    </a>
    <a href="?page=reports" class="bg-white border border-slate-200 rounded-md p-3.5 text-center hover:-translate-y-0.5 hover:shadow-md transition-all">
      <div class="text-2xl text-sky-700 mb-1"><i class="fas fa-chart-bar"></i></div>
      <div class="text-slate-800 font-semibold text-sm">รายงานเอกสาร</div>
    </a>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">

    <!-- ประกาศ -->
    <div class="bg-white border border-slate-200 rounded-md overflow-hidden h-full">
      <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm">
        <i class="fas fa-bullhorn mr-2 text-amber-500"></i>ประกาศ
      </div>
      <div class="p-0 max-h-[520px] overflow-y-auto">
        <?php if (empty($announcements)): ?>
        <div class="px-3 py-16 text-center text-slate-400">
          <i class="fas fa-bullhorn block text-4xl mb-3 text-slate-300"></i>ยังไม่มีประกาศ
        </div>
        <?php else: foreach ($announcements as $a): ?>
        <div class="px-3.5 py-3 border-b border-slate-100">
          <div class="font-bold text-[0.92rem]">
            <?php if ($a['is_pinned']): ?><span class="bg-red-600 text-white text-xs rounded px-1.5 py-0.5 mr-1"><i class="fas fa-thumbtack mr-1"></i>ปักหมุด</span><?php endif; ?>
            <?php echo e($a['title']); ?>
          </div>
          <div class="text-slate-600 mt-1 text-sm whitespace-pre-line"><?php echo e($a['content']); ?></div>
          <div class="text-slate-400 mt-2 text-xs">
            <i class="fas fa-user mr-1"></i><?php echo e($a['author_name']); ?>
            &nbsp;|&nbsp;<i class="fas fa-clock mr-1"></i><?php echo thaiDate($a['created_at']); ?>
          </div>
        </div>
        <?php endforeach; endif; ?>
      </div>
    </div>

    <!-- วีดีโอสอนการใช้งาน -->
    <div class="bg-white border border-slate-200 rounded-md overflow-hidden h-full">
      <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm">
        <i class="fas fa-video mr-2 text-red-600"></i>วีดีโอสอนการใช้งานโปรแกรม
      </div>
      <div class="p-0 max-h-[520px] overflow-y-auto">
        <?php if (empty($videos)): ?>
        <div class="px-3 py-16 text-center text-slate-400">
          <i class="fas fa-video-slash block text-4xl mb-3 text-slate-300"></i>ยังไม่มีวีดีโอ
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 p-3">
          <?php foreach ($videos as $v): ?>
          <div class="border border-slate-200 rounded overflow-hidden h-full">
            <div class="aspect-video">
              <iframe class="w-full h-full" src="<?php echo e(youtubeEmbedUrl($v['video_url'])); ?>"
                      title="<?php echo e($v['title']); ?>" allowfullscreen loading="lazy"></iframe>
            </div>
            <div class="p-2">
              <div class="font-semibold text-sm truncate"><?php echo e($v['title']); ?></div>
              <?php if (!empty($v['description'])): ?>
              <div class="text-slate-500 truncate text-xs"><?php echo e($v['description']); ?></div>
              <?php endif; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>

  </div>

</main>
