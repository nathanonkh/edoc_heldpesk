<?php
class AnnouncementModel {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getActive($limit = 10) {
        $limit = intval($limit);
        return $this->db->fetchAll(
            "SELECT a.*, CONCAT(u.prefix,' ',u.firstname,' ',u.lastname) AS author_name
             FROM announcements a
             JOIN users u ON u.id = a.created_by
             WHERE a.is_active = 1
             ORDER BY a.is_pinned DESC, a.created_at DESC
             LIMIT $limit"
        );
    }

    public function getAll() {
        return $this->db->fetchAll(
            "SELECT a.*, CONCAT(u.prefix,' ',u.firstname,' ',u.lastname) AS author_name
             FROM announcements a
             JOIN users u ON u.id = a.created_by
             ORDER BY a.is_pinned DESC, a.created_at DESC"
        );
    }

    public function create($data, $userId) {
        $title    = $this->db->escape($data['title']);
        $content  = $this->db->escape($data['content']);
        $isPinned = !empty($data['is_pinned']) ? 1 : 0;
        $userId   = intval($userId);
        $this->db->query(
            "INSERT INTO announcements (title, content, is_pinned, created_by)
             VALUES ('$title', '$content', $isPinned, $userId)"
        );
        return $this->db->insertId();
    }

    public function delete($id) {
        $this->db->query("UPDATE announcements SET is_active = 0 WHERE id=" . intval($id));
    }
}
