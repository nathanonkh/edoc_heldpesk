<?php
class Session {

    public static function generateCsrf() {
        $token = sha1(uniqid(mt_rand(), true));
        $_SESSION[CSRF_TOKEN_NAME] = $token;
        return $token;
    }

    public static function getCsrf() {
        if (empty($_SESSION[CSRF_TOKEN_NAME])) {
            self::generateCsrf();
        }
        return $_SESSION[CSRF_TOKEN_NAME];
    }

    public static function validateCsrf($token) {
        if (empty($token) || empty($_SESSION[CSRF_TOKEN_NAME])) {
            return false;
        }
        return $token === $_SESSION[CSRF_TOKEN_NAME];
    }

    public static function setFlash($type, $message) {
        $_SESSION['flash'] = array('type' => $type, 'message' => $message);
    }

    public static function getFlash() {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }

    public static function destroy() {
        session_unset();
        session_destroy();
    }
}
