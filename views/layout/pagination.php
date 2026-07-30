<?php
if (!isset($totalPages) || $totalPages <= 1) return;

$params = isset($paginationParams) ? $paginationParams : array();

function buildPageUrl($p, $params) {
    $params['p'] = $p;
    $pairs = array();
    foreach ($params as $k => $v) {
        if ($v !== '' && $v !== null) {
            $pairs[] = htmlspecialchars($k, ENT_QUOTES) . '=' . htmlspecialchars($v, ENT_QUOTES);
        }
    }
    return '?' . implode('&', $pairs);
}

$range = 2;
$start = max(1, $currentPageNum - $range);
$end   = min($totalPages, $currentPageNum + $range);
?>
<nav aria-label="Page navigation" class="mt-3">
  <ul class="pagination pagination-sm justify-content-center flex-wrap mb-0">
    <li class="page-item <?php echo $currentPageNum <= 1 ? 'disabled' : ''; ?>">
      <a class="page-link" href="<?php echo buildPageUrl(1, $params); ?>" title="หน้าแรก"><i class="fas fa-angle-double-left"></i></a>
    </li>
    <li class="page-item <?php echo $currentPageNum <= 1 ? 'disabled' : ''; ?>">
      <a class="page-link" href="<?php echo buildPageUrl($currentPageNum - 1, $params); ?>" title="ก่อนหน้า"><i class="fas fa-angle-left"></i></a>
    </li>
    <?php if ($start > 1): ?>
    <li class="page-item disabled"><span class="page-link">…</span></li>
    <?php endif; ?>
    <?php for ($i = $start; $i <= $end; $i++): ?>
    <li class="page-item <?php echo $i == $currentPageNum ? 'active' : ''; ?>">
      <a class="page-link" href="<?php echo buildPageUrl($i, $params); ?>"><?php echo $i; ?></a>
    </li>
    <?php endfor; ?>
    <?php if ($end < $totalPages): ?>
    <li class="page-item disabled"><span class="page-link">…</span></li>
    <?php endif; ?>
    <li class="page-item <?php echo $currentPageNum >= $totalPages ? 'disabled' : ''; ?>">
      <a class="page-link" href="<?php echo buildPageUrl($currentPageNum + 1, $params); ?>" title="ถัดไป"><i class="fas fa-angle-right"></i></a>
    </li>
    <li class="page-item <?php echo $currentPageNum >= $totalPages ? 'disabled' : ''; ?>">
      <a class="page-link" href="<?php echo buildPageUrl($totalPages, $params); ?>" title="หน้าสุดท้าย"><i class="fas fa-angle-double-right"></i></a>
    </li>
  </ul>
  <div class="text-center text-muted mt-1" style="font-size:0.78rem;">
    หน้า <?php echo $currentPageNum; ?> / <?php echo $totalPages; ?>
    &nbsp;|&nbsp; ทั้งหมด <?php echo $totalItems; ?> รายการ
  </div>
</nav>
