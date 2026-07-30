<?php
class VideoModel {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getActive($limit = 20) {
        $limit = intval($limit);
        return $this->db->fetchAll(
            "SELECT v.*, CONCAT(u.prefix,' ',u.firstname,' ',u.lastname) AS author_name
             FROM tutorial_videos v
             JOIN users u ON u.id = v.created_by
             WHERE v.is_active = 1
             ORDER BY v.created_at DESC
             LIMIT $limit"
        );
    }

    public function getAll() {
        return $this->db->fetchAll(
            "SELECT v.*, CONCAT(u.prefix,' ',u.firstname,' ',u.lastname) AS author_name
             FROM tutorial_videos v
             JOIN users u ON u.id = v.created_by
             ORDER BY v.created_at DESC"
        );
    }

    public function create($data, $userId) {
        $title       = $this->db->escape($data['title']);
        $videoUrl    = $this->db->escape($data['video_url']);
        $description = $this->db->escape(isset($data['description']) ? $data['description'] : '');
        $userId      = intval($userId);
        $this->db->query(
            "INSERT INTO tutorial_videos (title, video_url, description, created_by)
             VALUES ('$title', '$videoUrl', '$description', $userId)"
        );
        return $this->db->insertId();
    }

    public function delete($id) {
        $this->db->query("UPDATE tutorial_videos SET is_active = 0 WHERE id=" . intval($id));
    }
}
