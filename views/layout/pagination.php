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

function pageLinkClass($disabled, $active) {
    if ($disabled) return 'w-8 h-8 flex items-center justify-center rounded text-slate-300 cursor-not-allowed text-sm';
    if ($active)   return 'w-8 h-8 flex items-center justify-center rounded bg-[#1565c0] text-white text-sm font-semibold';
    return 'w-8 h-8 flex items-center justify-center rounded text-slate-600 hover:bg-slate-100 text-sm';
}
?>
<nav aria-label="Page navigation" class="mt-3">
  <ul class="flex items-center justify-center flex-wrap gap-1 mb-0 list-none p-0">
    <li>
      <a class="<?php echo pageLinkClass($currentPageNum <= 1, false); ?>" href="<?php echo buildPageUrl(1, $params); ?>" title="หน้าแรก"><i class="fas fa-angle-double-left"></i></a>
    </li>
    <li>
      <a class="<?php echo pageLinkClass($currentPageNum <= 1, false); ?>" href="<?php echo buildPageUrl($currentPageNum - 1, $params); ?>" title="ก่อนหน้า"><i class="fas fa-angle-left"></i></a>
    </li>
    <?php if ($start > 1): ?>
    <li><span class="w-8 h-8 flex items-center justify-center text-slate-400 text-sm">…</span></li>
    <?php endif; ?>
    <?php for ($i = $start; $i <= $end; $i++): ?>
    <li>
      <a class="<?php echo pageLinkClass(false, $i == $currentPageNum); ?>" href="<?php echo buildPageUrl($i, $params); ?>"><?php echo $i; ?></a>
    </li>
    <?php endfor; ?>
    <?php if ($end < $totalPages): ?>
    <li><span class="w-8 h-8 flex items-center justify-center text-slate-400 text-sm">…</span></li>
    <?php endif; ?>
    <li>
      <a class="<?php echo pageLinkClass($currentPageNum >= $totalPages, false); ?>" href="<?php echo buildPageUrl($currentPageNum + 1, $params); ?>" title="ถัดไป"><i class="fas fa-angle-right"></i></a>
    </li>
    <li>
      <a class="<?php echo pageLinkClass($currentPageNum >= $totalPages, false); ?>" href="<?php echo buildPageUrl($totalPages, $params); ?>" title="หน้าสุดท้าย"><i class="fas fa-angle-double-right"></i></a>
    </li>
  </ul>
  <div class="text-center text-slate-500 mt-1 text-[0.78rem]">
    หน้า <?php echo $currentPageNum; ?> / <?php echo $totalPages; ?>
    &nbsp;|&nbsp; ทั้งหมด <?php echo $totalItems; ?> รายการ
  </div>
</nav>
