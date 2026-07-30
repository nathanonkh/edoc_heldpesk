<?php
class IssueController extends Controller {

    private $issueModel;

    public function __construct($db) {
        parent::__construct($db);
        $this->issueModel = new IssueModel($db);
    }

    public function index() {
        $user    = Auth::currentUser();
        $filters = array(
            'status'    => isset($_GET['status'])    ? $_GET['status']    : '',
            'keyword'   => isset($_GET['keyword'])   ? $_GET['keyword']   : '',
            'date_from' => isset($_GET['date_from']) ? $_GET['date_from'] : '',
            'date_to'   => isset($_GET['date_to'])   ? $_GET['date_to']   : '',
            'per_page'  => 10,
            'page_num'  => isset($_GET['p']) ? intval($_GET['p']) : 1,
        );

        $issues         = $this->issueModel->getList($user, $filters);
        $totalItems     = $this->issueModel->countList($user, $filters);
        $totalPages     = ceil($totalItems / $filters['per_page']);
        $currentPageNum = $filters['page_num'];

        $pageTitle = 'แจ้งปัญหาการใช้งานโปรแกรม';
        include 'views/layout/header.php';
        include 'views/layout/navbar.php';
        include 'views/issues/index.php';
        include 'views/layout/footer.php';
    }

    public function create() {
        Auth::requireLogin();
        $pageTitle = 'แจ้งปัญหาการใช้งานโปรแกรม';
        include 'views/layout/header.php';
        include 'views/layout/navbar.php';
        include 'views/issues/create.php';
        include 'views/layout/footer.php';
    }

    public function store() {
        Auth::requireLogin();
        Auth::checkCsrf();

        if (empty($_POST['title']) || empty($_POST['detail'])) {
            redirectWithFlash(APP_URL . '/?page=issues&action=create', 'error', 'กรุณากรอกชื่อเรื่องและรายละเอียดปัญหา');
        }
        if (empty($_POST['issue_type']) || empty($_POST['program_name'])) {
            redirectWithFlash(APP_URL . '/?page=issues&action=create', 'error', 'กรุณาเลือกประเภทปัญหาและโปรแกรม');
        }

        $user      = Auth::currentUser();
        $typeName  = $this->db->escape($_POST['cooperative_type_name']);
        $coopId    = intval($_POST['cooperative_id']);
        $issueType = $this->db->escape($_POST['issue_type']);
        $program   = $this->db->escape($_POST['program_name']);
        $title     = $this->db->escape(trim($_POST['title']));
        $detail    = $this->db->escape(trim($_POST['detail']));

        $coop = $this->db->fetchOne(
            "SELECT * FROM cooperatives WHERE id=$coopId AND type_name='$typeName'"
        );
        if (!$coop) {
            redirectWithFlash(APP_URL . '/?page=issues&action=create', 'error', 'ข้อมูลสหกรณ์ไม่ถูกต้อง');
        }

        $ticketCode = $this->issueModel->generateTicketCode();

        // ไฟล์แนบ (ไม่บังคับ)
        $attachmentPath = '';
        $attachmentName = '';
        if (isset($_FILES['attachment'])) {
            $result = FileUpload::uploadAttachment($_FILES['attachment'], 'issues', $ticketCode);
            if (!$result['success']) {
                redirectWithFlash(APP_URL . '/?page=issues&action=create', 'error', 'ไฟล์แนบ: ' . $result['error']);
            }
            $attachmentPath = $result['filename'];
            $attachmentName = $result['original_name'];
        }

        $ticketEsc          = $this->db->escape($ticketCode);
        $coopCode           = $this->db->escape($coop['code']);
        $coopName           = $this->db->escape($coop['name']);
        $officeName         = $this->db->escape($coop['office_name']);
        $submittedBy        = intval($user['id']);
        $attachmentEsc      = $this->db->escape($attachmentPath);
        $attachmentNameEsc  = $this->db->escape($attachmentName);

        $this->db->query(
            "INSERT INTO issues
             (ticket_code, cooperative_type_name, cooperative_id, cooperative_code, cooperative_name,
              office_name, issue_type, program_name, title, detail, attachment, attachment_name,
              submitted_by, status)
             VALUES
             ('$ticketEsc', '$typeName', $coopId, '$coopCode', '$coopName',
              '$officeName', '$issueType', '$program', '$title', '$detail', '$attachmentEsc', '$attachmentNameEsc',
              $submittedBy, 'pending')"
        );
        $issueId = $this->db->insertId();
        $this->issueModel->addLog($issueId, 'แจ้งปัญหา', $submittedBy);

        $staffIds = array_unique(array_merge(
            Notification::getUsersByRoleOfficeOnly($this->db, 'admin', $coop['office_name']),
            Notification::getUsersByRoleOfficeOnly($this->db, 'inspector', $coop['office_name'])
        ));
        // ไม่ต้องแจ้งเตือนตัวเองหากผู้แจ้งเป็นเจ้าหน้าที่ที่ดูแลอยู่แล้ว
        $staffIds = array_values(array_diff($staffIds, array($submittedBy)));
        Notification::createForMultipleIssue($this->db, $staffIds, $issueId, 'issue_status',
            'มีเรื่องแจ้งปัญหาใหม่',
            'เรื่อง ' . $ticketCode . ' จาก ' . $coop['name'] . ' รอตรวจสอบ');

        redirectWithFlash(APP_URL . '/?page=issues', 'success', 'แจ้งปัญหาสำเร็จ รหัส: ' . $ticketCode);
    }

    public function detail() {
        $id    = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $issue = $this->issueModel->getById($id);
        if (!$issue) {
            redirectWithFlash(APP_URL . '/?page=issues', 'error', 'ไม่พบรายการแจ้งปัญหา');
        }

        $user    = Auth::currentUser();
        $canView = userHasRole($user, 'admin') || isHQ($user)
                 || $issue['office_name'] === $user['office_name']
                 || $issue['submitted_by'] == $user['id'];
        if (!$canView) {
            redirectWithFlash(APP_URL . '/?page=issues', 'error', 'คุณไม่มีสิทธิ์ดูรายการนี้');
        }

        $logs = $this->issueModel->getLogs($issue['id']);

        $pageTitle = 'รายละเอียดปัญหา ' . $issue['ticket_code'];
        include 'views/layout/header.php';
        include 'views/layout/navbar.php';
        include 'views/issues/detail.php';
        include 'views/layout/footer.php';
    }

    // ผู้แจ้งเรื่องเป็นผู้เลือกเอง: ดำเนินการเอง (รอตรวจสอบ -> กำลังดำเนินการ)
    public function start() {
        $this->changeStatus('pending', 'in_progress', 'รับเรื่องดำเนินการเอง', 'local');
    }

    // ผู้แจ้งเรื่องเป็นผู้เลือกเอง: ส่งต่อให้ส่วนกลาง (รอตรวจสอบ -> ส่งส่วนกลาง)
    public function escalate() {
        $this->changeStatus('pending', 'sent_central', 'ส่งต่อให้ส่วนกลาง', 'local');
    }

    // ส่วนกลางรับเรื่องมาดำเนินการ (ส่งส่วนกลาง -> กำลังดำเนินการ)
    public function accept_central() {
        $this->changeStatus('sent_central', 'in_progress', 'ส่วนกลางรับเรื่องดำเนินการ', 'hq_admin');
    }

    // ดำเนินการเสร็จสิ้น (กำลังดำเนินการ -> สำเร็จ)
    // สิทธิ์ขึ้นอยู่กับว่าใครเป็นผู้รับผิดชอบขั้นตอนนี้อยู่จริง (ดูใน changeStatus โหมด 'context')
    public function complete() {
        $this->changeStatus('in_progress', 'completed', 'ดำเนินการสำเร็จ', 'context');
    }

    private function changeStatus($fromStatus, $toStatus, $actionLabel, $mode) {
        Auth::requireLogin();
        $id    = isset($_POST['issue_id']) ? intval($_POST['issue_id']) : 0;
        $issue = $this->db->fetchOne("SELECT * FROM issues WHERE id=$id AND status='$fromStatus'");
        if (!$issue) {
            redirectWithFlash(APP_URL . '/?page=issues', 'error', 'ไม่พบรายการหรือสถานะไม่ถูกต้อง');
        }

        $user = Auth::currentUser();
        switch ($mode) {
            case 'hq_admin':
                // ขั้นตอนนี้เฉพาะผู้ดูแลระบบ (admin) ที่สังกัดส่วนกลางเท่านั้น
                $allowed = userHasRole($user, 'admin') && isHQ($user);
                break;
            case 'context':
                // เรื่องที่เคยถูกส่งไปส่วนกลาง (จังหวัดกดส่งต่อ) ให้เฉพาะ admin ส่วนกลางบันทึกผลได้
                // เรื่องที่จังหวัดดำเนินการเอง (ไม่เคยส่งส่วนกลาง) ส่วนกลางจะบันทึกผลแทนไม่ได้
                if (!empty($issue['handled_by_central'])) {
                    $allowed = userHasRole($user, 'admin') && isHQ($user);
                } else {
                    $allowed = canHandleIssue($user, $issue);
                }
                break;
            default: // 'local'
                // ผู้แจ้งเรื่องเองเป็นผู้ตัดสินใจ หรือเจ้าหน้าที่ประจำสำนักงานที่รับผิดชอบ
                $allowed = canHandleIssue($user, $issue);
                break;
        }
        if (!$allowed) {
            redirectWithFlash(APP_URL . '/?page=issues', 'error', 'คุณไม่มีสิทธิ์ดำเนินการ');
        }
        Auth::checkCsrf();

        $userId = intval($user['id']);
        $now    = date('Y-m-d H:i:s');
        $note   = isset($_POST['note']) ? trim($_POST['note']) : '';

        $centralSet = ($toStatus === 'sent_central') ? ", handled_by_central=1" : "";
        $this->db->query(
            "UPDATE issues SET status='$toStatus', updated_at='$now'$centralSet WHERE id=$id"
        );
        $this->issueModel->addLog($id, $actionLabel, $userId, $note);
        $this->notifyIssueChange($issue, $fromStatus, $toStatus, $userId);

        redirectWithFlash(APP_URL . '/?page=issues&action=detail&id=' . $id, 'success', 'อัปเดตสถานะสำเร็จ');
    }

    // ส่งการแจ้งเตือนตามการเปลี่ยนสถานะของเรื่องแจ้งปัญหา
    private function notifyIssueChange($issue, $fromStatus, $toStatus, $actorId) {
        $issueId   = intval($issue['id']);
        $ticket    = $issue['ticket_code'];
        $submitter = intval($issue['submitted_by']);

        if ($toStatus === 'sent_central') {
            $adminIds = Notification::getUsersByRole($this->db, 'admin', HQ_OFFICE);
            Notification::createForMultipleIssue($this->db, $adminIds, $issueId, 'issue_escalated',
                'มีเรื่องแจ้งปัญหาส่งมาให้ส่วนกลาง',
                'เรื่อง ' . $ticket . ' จาก ' . $issue['office_name'] . ' รอส่วนกลางรับเรื่องดำเนินการ');
            if ($submitter != $actorId) {
                Notification::createForIssue($this->db, $submitter, $issueId, 'issue_status',
                    'เรื่องของคุณถูกส่งต่อให้ส่วนกลาง',
                    'เรื่อง ' . $ticket . ' ถูกส่งต่อให้ส่วนกลางดำเนินการ');
            }
        } elseif ($fromStatus === 'pending' && $toStatus === 'in_progress') {
            if ($submitter != $actorId) {
                Notification::createForIssue($this->db, $submitter, $issueId, 'issue_status',
                    'เรื่องแจ้งปัญหาเริ่มดำเนินการแล้ว',
                    'เรื่อง ' . $ticket . ' กำลังดำเนินการแก้ไข');
            }
        } elseif ($fromStatus === 'sent_central' && $toStatus === 'in_progress') {
            Notification::createForIssue($this->db, $submitter, $issueId, 'issue_status',
                'ส่วนกลางรับเรื่องดำเนินการแล้ว',
                'เรื่อง ' . $ticket . ' ส่วนกลางรับเรื่องและกำลังดำเนินการ');
        } elseif ($toStatus === 'completed') {
            Notification::createForIssue($this->db, $submitter, $issueId, 'issue_completed',
                'เรื่องแจ้งปัญหาดำเนินการสำเร็จ',
                'เรื่อง ' . $ticket . ' ดำเนินการแก้ไขเสร็จสิ้นแล้ว');
        }
    }

    public function view_attachment() {
        Auth::requireLogin();
        $id    = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $issue = $this->issueModel->getById($id);
        if (!$issue || empty($issue['attachment'])) {
            exit('ไม่พบไฟล์แนบ');
        }

        $user    = Auth::currentUser();
        $canView = userHasRole($user, 'admin') || isHQ($user)
                 || $issue['office_name'] === $user['office_name']
                 || $issue['submitted_by'] == $user['id'];
        if (!$canView) {
            exit('คุณไม่มีสิทธิ์เข้าถึงไฟล์นี้');
        }

        $filePath = UPLOAD_DIR . $issue['attachment'];
        if (!file_exists($filePath)) {
            exit('ไม่พบไฟล์ในระบบ');
        }

        $ext     = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mimeMap = array(
            'pdf'  => 'application/pdf',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
        );
        $mime = isset($mimeMap[$ext]) ? $mimeMap[$ext] : 'application/octet-stream';

        header('Content-Type: ' . $mime);
        header('Content-Disposition: inline; filename="' . basename($issue['attachment_name']) . '"');
        header('Content-Length: ' . filesize($filePath));
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');
        readfile($filePath);
        exit;
    }

    public function ajax_list() {
        Auth::requireLogin();
        if (!isAjax()) {
            http_response_code(403); exit('Forbidden');
        }

        $user    = Auth::currentUser();
        $filters = array(
            'status'    => isset($_GET['status'])    ? $_GET['status']    : '',
            'keyword'   => isset($_GET['keyword'])   ? $_GET['keyword']   : '',
            'date_from' => isset($_GET['date_from']) ? $_GET['date_from'] : '',
            'date_to'   => isset($_GET['date_to'])   ? $_GET['date_to']   : '',
            'per_page'  => 10,
            'page_num'  => isset($_GET['p']) ? intval($_GET['p']) : 1,
        );

        $issues         = $this->issueModel->getList($user, $filters);
        $totalItems     = $this->issueModel->countList($user, $filters);
        $totalPages     = ceil($totalItems / $filters['per_page']);
        $currentPageNum = $filters['page_num'];

        include 'views/issues/_list_partial.php';
        exit;
    }
}
