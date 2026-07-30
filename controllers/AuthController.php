<?php
class AuthController extends Controller {

    public function __construct($db) {
        parent::__construct($db);
    }

    public function index() {
        if (!empty($_SESSION['user_id'])) {
            header('Location: ' . APP_URL . '/?page=dashboard');
            exit;
        }
        require_once 'views/auth/login.php';
    }

    public function login() {
        if (!empty($_SESSION['user_id'])) {
            header('Location: ' . APP_URL . '/?page=dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Auth::checkCsrf();
            $username = isset($_POST['username']) ? trim($_POST['username']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';

            if (empty($username) || empty($password)) {
                Session::setFlash('error', 'กรุณากรอกชื่อผู้ใช้และรหัสผ่าน');
                header('Location: ' . APP_URL . '/?page=login');
                exit;
            }

            if (Auth::login($this->db, $username, $password)) {
                header('Location: ' . APP_URL . '/?page=dashboard');
                exit;
            } else {
                Session::setFlash('error', 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง');
                header('Location: ' . APP_URL . '/?page=login');
                exit;
            }
        }

        require_once 'views/auth/login.php';
    }
}
