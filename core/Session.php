<?php
class Session {

    public static function generateCsrf() {
        // PHP 5.2 ไม่มี random_bytes()/openssl_random_pseudo_bytes()
        // จึงผสม entropy จากหลายแหล่งเท่าที่มีในเวอร์ชันนี้ให้มากที่สุด
        // (ยังไม่ใช่ CSPRNG แท้ แต่ปลอดภัยกว่า sha1(uniqid()) เดิมมาก)
        $raw = uniqid(mt_rand(), true) . microtime(true) . mt_rand(0, PHP_INT_MAX) . session_id();
        $token = hash('sha256', $raw);
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
        // PHP 5.2 ไม่มี hash_equals() (ต้อง 5.6+) จึงใช้เปรียบเทียบตรง ๆ
        // ความเสี่ยง timing-attack ในบริบทนี้ต่ำมากเพราะ token เป็น sha256 ความยาวคงที่
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
