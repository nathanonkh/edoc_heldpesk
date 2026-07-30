<?php
class CooperativeController extends Controller {

    private $coopModel;

    public function __construct($db) {
        parent::__construct($db);
        $this->coopModel = new CooperativeModel($db);
    }

    public function index() {
        $perPage        = 10;
        $currentPageNum = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
        $offset         = ($currentPageNum - 1) * $perPage;
        $keyword        = isset($_GET['keyword'])       ? trim($_GET['keyword'])       : '';
        $filterType     = isset($_GET['filter_type'])   ? trim($_GET['filter_type'])   : '';
        $filterOffice   = isset($_GET['filter_office']) ? trim($_GET['filter_office']) : '';
        $filterStatus   = isset($_GET['filter_status']) ? trim($_GET['filter_status']) : '';
        $allCoops       = $this->coopModel->getAll($filterOffice ? $filterOffice : null, $filterType ? $filterType : null, $keyword, $filterStatus);
        $totalItems     = count($allCoops);
        $totalPages     = max(1, ceil($totalItems / $perPage));
        $cooperatives   = array_slice($allCoops, $offset, $perPage);
        $pageTitle      = 'จัดการสหกรณ์';
        include 'views/layout/header.php';
        include 'views/layout/navbar.php';
        include 'views/cooperatives/index.php';
        include 'views/layout/footer.php';
    }

    public function create() {
        $pageTitle = 'เพิ่มสหกรณ์';
        include 'views/layout/header.php';
        include 'views/layout/navbar.php';
        include 'views/cooperatives/create.php';
        include 'views/layout/footer.php';
    }

    public function ajax_list() {
        Auth::requireRole(array('admin'));
        if (!isAjax()) {
            http_response_code(403); exit('Forbidden');
        }
        $perPage        = 10;
        $currentPageNum = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
        $offset         = ($currentPageNum - 1) * $perPage;
        $keyword        = isset($_GET['keyword'])       ? trim($_GET['keyword'])       : '';
        $filterType     = isset($_GET['filter_type'])   ? trim($_GET['filter_type'])   : '';
        $filterOffice   = isset($_GET['filter_office']) ? trim($_GET['filter_office']) : '';
        $filterStatus   = isset($_GET['filter_status']) ? trim($_GET['filter_status']) : '';
        $allCoops       = $this->coopModel->getAll($filterOffice ? $filterOffice : null, $filterType ? $filterType : null, $keyword, $filterStatus);
        $totalItems     = count($allCoops);
        $totalPages     = max(1, ceil($totalItems / $perPage));
        $cooperatives   = array_slice($allCoops, $offset, $perPage);

        include 'views/cooperatives/_list_partial.php';
        exit;
    }

    public function store() {
        Auth::checkCsrf();
        $this->coopModel->create($_POST);
        redirectWithFlash(APP_URL . '/?page=cooperatives', 'success', 'เพิ่มข้อมูลสหกรณ์สำเร็จ');
    }

    public function edit() {
        $id   = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $coop = $this->coopModel->getById($id);
        if (!$coop) {
            redirectWithFlash(APP_URL . '/?page=cooperatives', 'error', 'ไม่พบข้อมูลสหกรณ์');
        }
        $pageTitle = 'แก้ไขสหกรณ์';
        include 'views/layout/header.php';
        include 'views/layout/navbar.php';
        include 'views/cooperatives/edit.php';
        include 'views/layout/footer.php';
    }

    public function update() {
        Auth::checkCsrf();
        $id = intval($_POST['id']);
        $this->coopModel->update($id, $_POST);
        redirectWithFlash(APP_URL . '/?page=cooperatives', 'success', 'อัปเดตข้อมูลสหกรณ์สำเร็จ');
    }

    public function delete() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $this->coopModel->delete($id);
        redirectWithFlash(APP_URL . '/?page=cooperatives', 'success', 'ลบข้อมูลสหกรณ์แล้ว');
    }
}
