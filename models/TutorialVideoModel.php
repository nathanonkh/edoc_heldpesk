<?php
class TutorialVideoModel {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllActive($limit = 12) {
        $limit = intval($limit);
        return $this->db->fetchAll(
            "SELECT * FROM tutorial_videos WHERE is_active = 1 ORDER BY created_at DESC LIMIT $limit"
        );
    }

    public function getAll() {
        return $this->db->fetchAll("SELECT * FROM tutorial_videos ORDER BY created_at DESC");
    }

    public function getById($id) {
        $id = intval($id);
        return $this->db->fetchOne("SELECT * FROM tutorial_videos WHERE id = $id");
    }

    public function create($data, $userId) {
        $title    = $this->db->escape($data['title']);
        $desc     = $this->db->escape(isset($data['description']) ? $data['description'] : '');
        $program  = $this->db->escape(isset($data['program_name']) ? $data['program_name'] : '');
        $url      = $this->db->escape($data['video_url']);
        $isActive = isset($data['is_active']) ? 1 : 0;
        $userId   = intval($userId);
        $this->db->query(
            "INSERT INTO tutorial_videos (title, description, program_name, video_url, is_active, created_by)
             VALUES ('$title', '$desc', '$program', '$url', $isActive, $userId)"
        );
        return $this->db->insertId();
    }

    public function update($id, $data) {
        $id       = intval($id);
        $title    = $this->db->escape($data['title']);
        $desc     = $this->db->escape(isset($data['description']) ? $data['description'] : '');
        $program  = $this->db->escape(isset($data['program_name']) ? $data['program_name'] : '');
        $url      = $this->db->escape($data['video_url']);
        $isActive = isset($data['is_active']) ? 1 : 0;
        $now      = date('Y-m-d H:i:s');
        $this->db->query(
            "UPDATE tutorial_videos SET title='$title', description='$desc', program_name='$program',
             video_url='$url', is_active=$isActive, updated_at='$now' WHERE id=$id"
        );
    }

    public function delete($id) {
        $id = intval($id);
        $this->db->query("DELETE FROM tutorial_videos WHERE id=$id");
    }
}