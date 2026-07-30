<?php
class AnnouncementController extends Controller {

    private $announcementModel;
    private $videoModel;

    public function __construct($db) {
        parent::__construct($db);
        $this->announcementModel = new AnnouncementModel($db);
        $this->videoModel        = new VideoModel($db);
    }

    public function index() {
        Auth::requireRole('admin');
        $announcements = $this->announcementModel->getAll();
        $videos        = $this->videoModel->getAll();
        $pageTitle     = 'จัดการหน้าหลัก';
        include 'views/layout/header.php';
        include 'views/layout/navbar.php';
        include 'views/announcements/index.php';
        include 'views/layout/footer.php';
    }

    public function store() {
        Auth::requireRole('admin');
        Auth::checkCsrf();
        if (empty($_POST['title']) || empty($_POST['content'])) {
            redirectWithFlash(APP_URL . '/?page=announcements', 'error', 'กรุณากรอกหัวข้อและเนื้อหาประกาศ');
        }
        $this->announcementModel->create($_POST, $_SESSION['user_id']);
        redirectWithFlash(APP_URL . '/?page=announcements', 'success', 'เพิ่มประกาศสำเร็จ');
    }

    public function delete() {
        Auth::requireRole('admin');
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $this->announcementModel->delete($id);
        redirectWithFlash(APP_URL . '/?page=announcements', 'success', 'ลบประกาศแล้ว');
    }

    public function store_video() {
        Auth::requireRole('admin');
        Auth::checkCsrf();
        if (empty($_POST['title']) || empty($_POST['video_url'])) {
            redirectWithFlash(APP_URL . '/?page=announcements', 'error', 'กรุณากรอกหัวข้อและลิงก์วีดีโอ');
        }
        $this->videoModel->create($_POST, $_SESSION['user_id']);
        redirectWithFlash(APP_URL . '/?page=announcements', 'success', 'เพิ่มวีดีโอสำเร็จ');
    }

    public function delete_video() {
        Auth::requireRole('admin');
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $this->videoModel->delete($id);
        redirectWithFlash(APP_URL . '/?page=announcements', 'success', 'ลบวีดีโอแล้ว');
    }
}
