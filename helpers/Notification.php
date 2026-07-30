<?php
class Notification {

    public static function create($db, $userId, $documentId, $type, $title, $message) {
        $userId     = intval($userId);
        $documentId = intval($documentId);
        $type       = $db->escape($type);
        $title      = $db->escape($title);
        $message    = $db->escape($message);
        $db->query("INSERT INTO notifications (user_id, document_id, type, title, message)
                    VALUES ($userId, $documentId, '$type', '$title', '$message')");
    }

    public static function createForMultiple($db, $userIds, $documentId, $type, $title, $message) {
        if (!is_array($userIds) || empty($userIds)) return;
        foreach ($userIds as $userId) {
            self::create($db, $userId, $documentId, $type, $title, $message);
        }
    }

    // แจ้งเตือนที่ผูกกับเรื่องแจ้งปัญหา (issues) แทนเอกสาร
    public static function createForIssue($db, $userId, $issueId, $type, $title, $message) {
        $userId  = intval($userId);
        $issueId = intval($issueId);
        $type    = $db->escape($type);
        $title   = $db->escape($title);
        $message = $db->escape($message);
        $db->query("INSERT INTO notifications (user_id, issue_id, type, title, message)
                    VALUES ($userId, $issueId, '$type', '$title', '$message')");
    }

    public static function createForMultipleIssue($db, $userIds, $issueId, $type, $title, $message) {
        if (!is_array($userIds) || empty($userIds)) return;
        foreach ($userIds as $userId) {
            self::createForIssue($db, $userId, $issueId, $type, $title, $message);
        }
    }

    public static function getUsersByRole($db, $role, $officeName) {
        $roleEsc = $db->escape($role);
        $offEsc  = $db->escape($officeName);
        $hqEsc   = $db->escape(HQ_OFFICE);
        $rows = $db->fetchAll(
            "SELECT id FROM users
             WHERE FIND_IN_SET('$roleEsc', REPLACE(roles, ', ', ','))
               AND is_active = 1
               AND (office_name = '$offEsc' OR office_name = '$hqEsc')"
        );
        $ids = array();
        foreach ($rows as $row) { $ids[] = $row['id']; }
        return $ids;
    }

    // เหมือน getUsersByRole แต่ไม่รวมส่วนกลางโดยอัตโนมัติ ใช้เมื่อยังไม่มีการส่งเรื่องไปส่วนกลาง
    public static function getUsersByRoleOfficeOnly($db, $role, $officeName) {
        $roleEsc = $db->escape($role);
        $offEsc  = $db->escape($officeName);
        $rows = $db->fetchAll(
            "SELECT id FROM users
             WHERE FIND_IN_SET('$roleEsc', REPLACE(roles, ', ', ','))
               AND is_active = 1
               AND office_name = '$offEsc'"
        );
        $ids = array();
        foreach ($rows as $row) { $ids[] = $row['id']; }
        return $ids;
    }

    public static function notifyStatusChange($db, $doc, $newStatus, $actorId) {
        $docId      = intval($doc['id']);
        $ticket     = $doc['ticket_code'];
        $officeName = $doc['office_name'];
        $submitter  = intval($doc['submitted_by']);

        switch ($newStatus) {
            case 'inspecting':
                $ids = self::getUsersByRole($db, 'inspector', $officeName);
                self::createForMultiple($db, $ids, $docId, 'status_changed',
                    'มีเอกสารใหม่รอตรวจสอบ',
                    'เอกสาร ' . $ticket . ' รอการตรวจสอบ กรุณาดำเนินการ');
                break;

            case 'approving':
                $ids = self::getUsersByRole($db, 'approver', $officeName);
                self::createForMultiple($db, $ids, $docId, 'status_changed',
                    'เอกสารรออนุมัติ',
                    'เอกสาร ' . $ticket . ' ผ่านการตรวจสอบแล้ว รออนุมัติ');
                self::create($db, $submitter, $docId, 'info',
                    'เอกสารผ่านการตรวจสอบ',
                    'เอกสาร ' . $ticket . ' ผ่านการตรวจสอบ กำลังรอขั้นตอนอนุมัติ');
                break;

            case 'operating':
                $ids = self::getUsersByRole($db, 'operator', $officeName);
                self::createForMultiple($db, $ids, $docId, 'status_changed',
                    'เอกสารรอดำเนินการ',
                    'เอกสาร ' . $ticket . ' อนุมัติแล้ว รอดำเนินการ');
                self::create($db, $submitter, $docId, 'info',
                    'เอกสารผ่านการอนุมัติ',
                    'เอกสาร ' . $ticket . ' ผ่านการอนุมัติ กำลังดำเนินการ');
                break;

            case 'completed':
                self::create($db, $submitter, $docId, 'completed',
                    'เอกสารเสร็จสิ้น',
                    'เอกสาร ' . $ticket . ' ดำเนินการเสร็จสิ้นแล้ว');
                $ids = self::getUsersByRole($db, 'inspector', $officeName);
                self::createForMultiple($db, $ids, $docId, 'completed',
                    'เอกสารเสร็จสิ้น',
                    'เอกสาร ' . $ticket . ' ดำเนินการเสร็จสิ้นแล้ว');
                break;

            case 'revision':
                self::create($db, $submitter, $docId, 'revision',
                    'เอกสารถูกส่งกลับแก้ไข',
                    'เอกสาร ' . $ticket . ' ถูกส่งกลับ กรุณาแก้ไขและส่งใหม่');
                break;

            case 'pending':
                $ids = self::getUsersByRole($db, 'inspector', $officeName);
                self::createForMultiple($db, $ids, $docId, 'resubmitted',
                    'เอกสารถูกส่งใหม่หลังแก้ไข',
                    'เอกสาร ' . $ticket . ' แก้ไขและส่งใหม่แล้ว รอตรวจสอบ');
                break;
        }
    }

    public static function countUnread($db, $userId) {
        $userId = intval($userId);
        $row    = $db->fetchOne(
            "SELECT COUNT(*) as cnt FROM notifications WHERE user_id = $userId AND is_read = 0"
        );
        return $row ? intval($row['cnt']) : 0;
    }

    public static function getRecent($db, $userId, $limit) {
        $userId = intval($userId);
        $limit  = intval($limit);
        return $db->fetchAll(
            "SELECT n.*,
                    COALESCE(d.ticket_code, i.ticket_code) AS ticket_code,
                    CASE WHEN n.document_id IS NOT NULL THEN 'document' ELSE 'issue' END AS target_type,
                    COALESCE(n.document_id, n.issue_id) AS target_id
             FROM notifications n
             LEFT JOIN documents d ON d.id = n.document_id
             LEFT JOIN issues i ON i.id = n.issue_id
             WHERE n.user_id = $userId
             ORDER BY n.created_at DESC LIMIT $limit"
        );
    }

    public static function markRead($db, $notificationId, $userId) {
        $notificationId = intval($notificationId);
        $userId         = intval($userId);
        $db->query(
            "UPDATE notifications SET is_read = 1
             WHERE id = $notificationId AND user_id = $userId"
        );
    }

    public static function markAllRead($db, $userId) {
        $userId = intval($userId);
        $db->query(
            "UPDATE notifications SET is_read = 1
             WHERE user_id = $userId AND is_read = 0"
        );
    }
}
