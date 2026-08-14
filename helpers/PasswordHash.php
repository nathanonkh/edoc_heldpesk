<?php
/**
 * PasswordHash
 * -----------------------------------------------------------------
 * PHP 5.2.3 ยังไม่มี password_hash()/password_verify() (ต้อง PHP 5.5+)
 * คลาสนี้จึงใช้ hash_hmac('sha256', ...) ร่วมกับ salt แบบสุ่มต่อผู้ใช้แทน
 * เพื่อกันปัญหา rainbow table ของ sha1() เปล่า ๆ ที่ระบบเดิมใช้อยู่
 *
 * รูปแบบที่เก็บในคอลัมน์ password: "<salt>$<hmac-sha256 hex>"
 * (แนะนำให้ขยายคอลัมน์ password เป็น VARCHAR(255) ดู migration.sql ที่แนบมา)
 *
 * รองรับ backward-compatibility: ถ้าค่าที่เก็บอยู่เป็น sha1 แบบเดิม (ไม่มี $)
 * verify() จะยังตรวจสอบผ่าน sha1 ได้ตามปกติ แล้ว Auth::login() จะ
 * อัปเกรดเป็น hash รูปแบบใหม่ให้อัตโนมัติหลัง login สำเร็จ
 */
class PasswordHash {

    public static function hash($password) {
        $salt = self::generateSalt();
        $hash = self::hmac($password, $salt);
        return $salt . '$' . $hash;
    }

    public static function verify($password, $stored) {
        if (empty($stored)) {
            return false;
        }
        if (strpos($stored, '$') !== false) {
            list($salt, $hash) = explode('$', $stored, 2);
            return self::hmac($password, $salt) === $hash;
        }
        // รูปแบบเดิมของระบบ: sha1($password) ตรง ๆ ไม่มี salt
        return sha1($password) === $stored;
    }

    // ใช้เช็คว่าค่าที่เก็บอยู่เป็น hash รูปแบบเก่า (sha1 ไม่มี salt) ที่ควรอัปเกรดหรือไม่
    public static function needsRehash($stored) {
        return strpos($stored, '$') === false;
    }

    private static function hmac($password, $salt) {
        return hash_hmac('sha256', $password, $salt);
    }

    private static function generateSalt() {
        $raw = uniqid(mt_rand(), true) . microtime(true) . mt_rand(0, PHP_INT_MAX);
        return substr(hash('sha256', $raw), 0, 32);
    }
}
