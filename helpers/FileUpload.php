<?php
class FileUpload {

    private static $allowedExtensions = array('pdf');
    private static $allowedMimes      = array('application/pdf', 'application/x-pdf');

    private static $allowedAttachmentExtensions = array('pdf','jpg','jpeg','png');
    private static $allowedAttachmentMimes      = array(
        'application/pdf', 'application/x-pdf',
        'image/jpeg', 'image/pjpeg', 'image/png',
    );

    public static function upload($file, $cooperativeCode, $ticketCode, $docNumber, $fiscalYear) {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            $errMsgs = array(
                UPLOAD_ERR_INI_SIZE   => 'ไฟล์ขนาดใหญ่เกินที่ server อนุญาต',
                UPLOAD_ERR_FORM_SIZE  => 'ไฟล์ขนาดใหญ่เกินที่ฟอร์มอนุญาต',
                UPLOAD_ERR_PARTIAL    => 'ไฟล์อัปโหลดไม่สมบูรณ์',
                UPLOAD_ERR_NO_FILE    => 'ไม่พบไฟล์ที่อัปโหลด',
                UPLOAD_ERR_NO_TMP_DIR => 'ไม่พบโฟลเดอร์ชั่วคราว',
                UPLOAD_ERR_CANT_WRITE => 'ไม่สามารถเขียนไฟล์ได้',
            );
            $errCode = isset($file['error']) ? $file['error'] : -1;
            $errMsg  = isset($errMsgs[$errCode]) ? $errMsgs[$errCode] : 'เกิดข้อผิดพลาดในการอัปโหลด';
            return array('success' => false, 'error' => $errMsg);
        }

        if ($file['size'] > MAX_FILE_SIZE) {
            return array('success' => false, 'error' => 'ไฟล์มีขนาดเกิน 10MB');
        }

        $originalName = $file['name'];
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        if (!in_array($ext, self::$allowedExtensions)) {
            return array('success' => false, 'error' => 'รับเฉพาะไฟล์ PDF เท่านั้น');
        }

        if (function_exists('mime_content_type')) {
            $mime = mime_content_type($file['tmp_name']);
            if (!in_array($mime, self::$allowedMimes)) {
                return array('success' => false, 'error' => 'ไฟล์ไม่ใช่ PDF จริง (MIME ไม่ถูกต้อง)');
            }
        }

        if (!is_uploaded_file($file['tmp_name'])) {
            return array('success' => false, 'error' => 'ไฟล์ไม่ถูกต้อง');
        }

        $targetDir = UPLOAD_DIR . $fiscalYear . '/' . $cooperativeCode . '/';
        if (!is_dir($targetDir)) {
            if (!mkdir($targetDir, 0755, true)) {
                return array('success' => false, 'error' => 'ไม่สามารถสร้างโฟลเดอร์ได้');
            }
        }

        $safeName    = preg_replace('/[^a-zA-Z0-9_\-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
        $filename    = $cooperativeCode . '_' . $ticketCode . '_doc' . $docNumber . '_' . $safeName . '.pdf';
        $destination = $targetDir . $filename;
        $relativePath = $fiscalYear . '/' . $cooperativeCode . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return array('success' => false, 'error' => 'ไม่สามารถบันทึกไฟล์ได้');
        }

        return array(
            'success'       => true,
            'filename'      => $relativePath,
            'original_name' => $originalName,
        );
    }

    // อัปโหลดไฟล์แนบทั่วไป (ใช้กับการแจ้งปัญหา) รองรับ PDF และรูปภาพ (JPG, PNG)
    // ถ้าไม่มีไฟล์แนบ (ผู้ใช้ไม่เลือกไฟล์) จะคืนค่าสำเร็จโดยไม่มีชื่อไฟล์ เนื่องจากเป็นฟิลด์ที่ไม่บังคับ
    public static function uploadAttachment($file, $subDir, $ticketCode) {
        if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return array('success' => true, 'filename' => '', 'original_name' => '');
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errMsgs = array(
                UPLOAD_ERR_INI_SIZE   => 'ไฟล์ขนาดใหญ่เกินที่ server อนุญาต',
                UPLOAD_ERR_FORM_SIZE  => 'ไฟล์ขนาดใหญ่เกินที่ฟอร์มอนุญาต',
                UPLOAD_ERR_PARTIAL    => 'ไฟล์อัปโหลดไม่สมบูรณ์',
                UPLOAD_ERR_NO_TMP_DIR => 'ไม่พบโฟลเดอร์ชั่วคราว',
                UPLOAD_ERR_CANT_WRITE => 'ไม่สามารถเขียนไฟล์ได้',
            );
            $errMsg = isset($errMsgs[$file['error']]) ? $errMsgs[$file['error']] : 'เกิดข้อผิดพลาดในการอัปโหลด';
            return array('success' => false, 'error' => $errMsg);
        }

        if ($file['size'] > MAX_FILE_SIZE) {
            return array('success' => false, 'error' => 'ไฟล์มีขนาดเกิน 10MB');
        }

        $originalName = $file['name'];
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        if (!in_array($ext, self::$allowedAttachmentExtensions)) {
            return array('success' => false, 'error' => 'รับเฉพาะไฟล์ PDF หรือรูปภาพ (JPG, PNG) เท่านั้น');
        }

        if (function_exists('mime_content_type')) {
            $mime = mime_content_type($file['tmp_name']);
            if (!in_array($mime, self::$allowedAttachmentMimes)) {
                return array('success' => false, 'error' => 'ไฟล์ไม่ถูกต้อง (MIME ไม่ตรงกับประเภทไฟล์)');
            }
        }

        if (!is_uploaded_file($file['tmp_name'])) {
            return array('success' => false, 'error' => 'ไฟล์ไม่ถูกต้อง');
        }

        $targetDir = UPLOAD_DIR . $subDir . '/';
        if (!is_dir($targetDir)) {
            if (!mkdir($targetDir, 0755, true)) {
                return array('success' => false, 'error' => 'ไม่สามารถสร้างโฟลเดอร์ได้');
            }
        }

        $safeName    = preg_replace('/[^a-zA-Z0-9_\-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
        $filename    = $ticketCode . '_' . $safeName . '.' . $ext;
        $destination = $targetDir . $filename;
        $relativePath = $subDir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return array('success' => false, 'error' => 'ไม่สามารถบันทึกไฟล์ได้');
        }

        return array(
            'success'       => true,
            'filename'      => $relativePath,
            'original_name' => $originalName,
        );
    }

    public static function deleteFile($relativePath) {
        $fullPath = UPLOAD_DIR . $relativePath;
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        return true;
    }
}
