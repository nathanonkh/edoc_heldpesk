<?php
class ContentController extends Controller {

    private $annModel;
    private $vidModel;

    public function __construct($db) {
        parent::__construct($db);
        $this->annModel = new AnnouncementModel($db);
        $this->vidModel = new TutorialVideoModel($db);
    }

    // ===================== ประกาศ =====================

    public function announcements() {
        $announcements = $this->annModel->getAll();
        $pageTitle = 'จัดการประกาศ';
        include 'views/layout/header.php';
        include 'views/layout/navbar.php';
        include 'views/content/announcements.php';
        include 'views/layout/footer.php';
    }

    public function announcement_create() {
        $announcement = null;
        $pageTitle = 'เพิ่มประกาศ';
        include 'views/layout/header.php';
        include 'views/layout/navbar.php';
        include 'views/content/announcement_form.php';
        include 'views/layout/footer.php';
    }

    public function announcement_store() {
        Auth::checkCsrf();
        $this->annModel->create($_POST, $_SESSION['user_id']);
        redirectWithFlash(APP_URL . '/?page=content&action=announcements', 'success', 'เพิ่มประกาศสำเร็จ');
    }

    public function announcement_edit() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $announcement = $this->annModel->getById($id);
        if (!$announcement) {
            redirectWithFlash(APP_URL . '/?page=content&action=announcements', 'error', 'ไม่พบประกาศ');
        }
        $pageTitle = 'แก้ไขประกาศ';
        include 'views/layout/header.php';
        include 'views/layout/navbar.php';
        include 'views/content/announcement_form.php';
        include 'views/layout/footer.php';
    }

    public function announcement_update() {
        Auth::checkCsrf();
        $id = intval($_POST['id']);
        $this->annModel->update($id, $_POST);
        redirectWithFlash(APP_URL . '/?page=content&action=announcements', 'success', 'บันทึกประกาศสำเร็จ');
    }

    public function announcement_delete() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $this->annModel->delete($id);
        redirectWithFlash(APP_URL . '/?page=content&action=announcements', 'success', 'ลบประกาศแล้ว');
    }

    // ===================== วีดีโอ =====================

    public function videos() {
        $videos = $this->vidModel->getAll();
        $pageTitle = 'จัดการวีดีโอสอนการใช้งาน';
        include 'views/layout/header.php';
        include 'views/layout/navbar.php';
        include 'views/content/videos.php';
        include 'views/layout/footer.php';
    }

    public function video_create() {
        $video = null;
        $pageTitle = 'เพิ่มวีดีโอ';
        include 'views/layout/header.php';
        include 'views/layout/navbar.php';
        include 'views/content/video_form.php';
        include 'views/layout/footer.php';
    }

    public function video_store() {
        Auth::checkCsrf();
        $this->vidModel->create($_POST, $_SESSION['user_id']);
        redirectWithFlash(APP_URL . '/?page=content&action=videos', 'success', 'เพิ่มวีดีโอสำเร็จ');
    }

    public function video_edit() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $video = $this->vidModel->getById($id);
        if (!$video) {
            redirectWithFlash(APP_URL . '/?page=content&action=videos', 'error', 'ไม่พบวีดีโอ');
        }
        $pageTitle = 'แก้ไขวีดีโอ';
        include 'views/layout/header.php';
        include 'views/layout/navbar.php';
        include 'views/content/video_form.php';
        include 'views/layout/footer.php';
    }

    public function video_update() {
        Auth::checkCsrf();
        $id = intval($_POST['id']);
        $this->vidModel->update($id, $_POST);
        redirectWithFlash(APP_URL . '/?page=content&action=videos', 'success', 'บันทึกวีดีโอสำเร็จ');
    }

    public function video_delete() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $this->vidModel->delete($id);
        redirectWithFlash(APP_URL . '/?page=content&action=videos', 'success', 'ลบวีดีโอแล้ว');
    }
}