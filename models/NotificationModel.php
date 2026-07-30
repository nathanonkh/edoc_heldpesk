<?php
class NotificationModel {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getByUser($userId, $limit = 50) {
        $userId = intval($userId);
        $limit  = intval($limit);
        return $this->db->fetchAll(
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
}
