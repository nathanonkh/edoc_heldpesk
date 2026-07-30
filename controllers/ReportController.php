<?php
class ReportController extends Controller {

    public function __construct($db) {
        parent::__construct($db);
    }

    public function ajax_list() {
        Auth::requireLogin();
        if (!isAjax()) {
            http_response_code(403); exit('Forbidden');
        }

        $user        = Auth::currentUser();
        $reportModel = new ReportModel($this->db);

        $filters = array(
            'status'      => isset($_GET['status'])      ? $_GET['status']      : '',
            'fiscal_year' => isset($_GET['fiscal_year']) ? $_GET['fiscal_year'] : '',
            'office_name' => isset($_GET['office_name']) ? $_GET['office_name'] : '',
            'date_from'   => isset($_GET['date_from'])   ? $_GET['date_from']   : '',
            'date_to'     => isset($_GET['date_to'])     ? $_GET['date_to']     : '',
        );

        $summary        = $reportModel->getSummaryByStatus($user, $filters);
        $allDetails     = $reportModel->getDetailReport($user, $filters);
        $perPage        = 10;
        $currentPageNum = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
        $totalItems     = count($allDetails);
        $totalPages     = max(1, ceil($totalItems / $perPage));
        $offset         = ($currentPageNum - 1) * $perPage;
        $details        = array_slice($allDetails, $offset, $perPage);

        include 'views/reports/_list_partial.php';
        exit;
    }

    public function index() {
        $user        = Auth::currentUser();
        $reportModel = new ReportModel($this->db);

        $filters = array(
            'status'      => isset($_GET['status'])      ? $_GET['status']      : '',
            'fiscal_year' => isset($_GET['fiscal_year']) ? $_GET['fiscal_year'] : '',
            'office_name' => isset($_GET['office_name']) ? $_GET['office_name'] : '',
            'date_from'   => isset($_GET['date_from'])   ? $_GET['date_from']   : '',
            'date_to'     => isset($_GET['date_to'])     ? $_GET['date_to']     : '',
        );

        $summary        = $reportModel->getSummaryByStatus($user, $filters);
        $allDetails     = $reportModel->getDetailReport($user, $filters);
        $perPage        = 10;
        $currentPageNum = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
        $totalItems     = count($allDetails);
        $totalPages     = max(1, ceil($totalItems / $perPage));
        $offset         = ($currentPageNum - 1) * $perPage;
        $details        = array_slice($allDetails, $offset, $perPage);
        $pageTitle      = 'รายงานเอกสาร';
        include 'views/layout/header.php';
        include 'views/layout/navbar.php';
        include 'views/reports/index.php';
        include 'views/layout/footer.php';
    }
}
