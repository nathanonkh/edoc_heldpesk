    </div><!-- /app-content -->
    <?php include 'views/layout/sidebar.php'; ?>
  </div><!-- /app-wrapper -->

  <footer class="page-footer">
    <div>
      <i class="fas fa-file-alt me-1"></i>
      <strong>ระบบนำส่งเอกสารอิเล็กทรอนิกส์</strong>
      &nbsp;|&nbsp; สำนักงานตรวจบัญชีสหกรณ์ที่ 5
    </div>
    <div>
      <i class="fas fa-user me-1"></i>
      <?php echo e(trim($_SESSION['prefix'].' '.$_SESSION['firstname'].' '.$_SESSION['lastname'])); ?>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <?php $flash = Session::getFlash(); if ($flash): ?>
  <script>
  (function(){
    var iconMap = { success:'success', error:'error', warning:'warning', info:'info' };
    Swal.fire({
      icon: iconMap['<?php echo $flash['type']; ?>'] || 'info',
      title: '<?php echo addslashes($flash['message']); ?>',
      toast: true, position: 'top-end', showConfirmButton: false, timer: 3000
    });
  })();
  </script>
  <?php endif; ?>

  <script>
  // =====================================================
  // Global UI helpers
  // =====================================================
  function confirmDelete(url, name) {
    Swal.fire({
      title: 'ยืนยันการลบ?',
      text: 'ระงับการใช้งาน: ' + name,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'ยืนยัน',
      cancelButtonText: 'ยกเลิก'
    }).then(function(result) {
      if (result.isConfirmed) { window.location.href = url; }
    });
  }

  function toggleHelpSidebar() {
    var sidebar = document.getElementById('helpSidebar');
    if (sidebar) {
      sidebar.classList.toggle('open');
    }
  }

  document.addEventListener('click', function(e) {
    var sidebar   = document.getElementById('helpSidebar');
    var toggleBtn = document.getElementById('helpToggleBtn');
    if (!sidebar || !toggleBtn) return;
    if (window.innerWidth >= 992) return;
    if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
      sidebar.classList.remove('open');
    }
  });

  // Poll unread count ทุก 30 วินาที
  startNotifPolling();
  </script>

  <?php if (isset($extraJs)) echo $extraJs; ?>
</body>
</html>
