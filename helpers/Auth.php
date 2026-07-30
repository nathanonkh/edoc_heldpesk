<?php
class Auth {

    public static function requireLogin() {
        if (empty($_SESSION['user_id'])) {
            Session::setFlash('warning', 'กรุณาเข้าสู่ระบบก่อน');
            header('Location: ' . APP_URL . '/?page=login');
            exit;
        }
    }

    public static function requireRole($roles) {
        self::requireLogin();
        if (!is_array($roles)) {
            $roles = array($roles);
        }
        $userRoles = isset($_SESSION['roles']) ? $_SESSION['roles'] : array($_SESSION['role']);
        $matched   = false;
        foreach ($roles as $r) {
            if (in_array($r, $userRoles)) { $matched = true; break; }
        }
        if (!$matched) {
            Session::setFlash('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
            header('Location: ' . APP_URL . '/?page=dashboard');
            exit;
        }
    }

    public static function hasRole($role) {
        $userRoles = isset($_SESSION['roles']) ? $_SESSION['roles'] : array($_SESSION['role']);
        return in_array($role, $userRoles);
    }

    public static function hasAnyRole($roles) {
        $userRoles = isset($_SESSION['roles']) ? $_SESSION['roles'] : array($_SESSION['role']);
        foreach ($roles as $r) {
            if (in_array($r, $userRoles)) return true;
        }
        return false;
    }

    public static function checkCsrf() {
        $token = isset($_POST[CSRF_TOKEN_NAME]) ? $_POST[CSRF_TOKEN_NAME] : '';
        if (!Session::validateCsrf($token)) {
            Session::setFlash('error', 'คำขอไม่ถูกต้อง (CSRF) กรุณาลองใหม่');
            header('Location: ' . APP_URL . '/?page=dashboard');
            exit;
        }
    }

    public static function login($db, $username, $password) {
        $username = $db->escape($username);
        $hashed   = sha1($password);
        $row = $db->fetchOne(
            "SELECT * FROM users WHERE username = '$username' AND password = '$hashed' AND is_active = 1"
        );
        if ($row) {
            $_SESSION['user_id']     = $row['id'];
            $_SESSION['username']    = $row['username'];
            $_SESSION['prefix']      = $row['prefix'];
            $_SESSION['firstname']   = $row['firstname'];
            $_SESSION['lastname']    = $row['lastname'];
            $rolesRaw = isset($row['roles']) ? $row['roles'] : (isset($row['role']) ? $row['role'] : 'submitter');
            $rolesArr = array_map('trim', explode(',', $rolesRaw));
            $_SESSION['roles']       = $rolesArr;
            $_SESSION['role']        = $rolesArr[0];
            $_SESSION['office_name'] = $row['office_name'];
            Session::generateCsrf();
            return true;
        }
        return false;
    }

    public static function logout() {
        Session::destroy();
        header('Location: ' . APP_URL . '/?page=login');
        exit;
    }

    public static function currentUser() {
        $roles = isset($_SESSION['roles']) ? $_SESSION['roles'] : array($_SESSION['role']);
        return array(
            'id'          => $_SESSION['user_id'],
            'username'    => $_SESSION['username'],
            'prefix'      => $_SESSION['prefix'],
            'firstname'   => $_SESSION['firstname'],
            'lastname'    => $_SESSION['lastname'],
            'fullname'    => trim($_SESSION['prefix'] . ' ' . $_SESSION['firstname'] . ' ' . $_SESSION['lastname']),
            'role'        => $_SESSION['role'],
            'roles'       => $roles,
            'office_name' => $_SESSION['office_name'],
        );
    }
}
