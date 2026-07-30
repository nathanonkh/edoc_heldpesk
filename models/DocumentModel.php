<?php
class DocumentModel {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getList($user, $filters = array()) {
        $where = buildDocumentWhereClause($user);

        if (!empty($filters['status'])) {
            $s = $this->db->escape($filters['status']);
            $where .= " AND d.status = '$s'";
        }
        if (!empty($filters['keyword'])) {
            $kw = $this->db->escape($filters['keyword']);
            $where .= " AND (d.cooperative_name LIKE '%$kw%' OR d.ticket_code LIKE '%$kw%')";
        }
        if (!empty($filters['date_from'])) {
            $df = $this->db->escape($filters['date_from']);
            $where .= " AND DATE(d.created_at) >= '$df'";
        }
        if (!empty($filters['date_to'])) {
            $dt = $this->db->escape($filters['date_to']);
            $where .= " AND DATE(d.created_at) <= '$dt'";
        }

        $perPage = isset($filters['per_page']) ? intval($filters['per_page']) : 30;
        $page    = isset($filters['page_num']) ? intval($filters['page_num']) : 1;
        if ($perPage < 1) $perPage = 30;
        if ($page < 1)    $page    = 1;
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT d.*, CONCAT(u.prefix,' ',u.firstname,' ',u.lastname) AS submitter_name
                FROM documents d
                JOIN users u ON u.id = d.submitted_by
                WHERE $where
                ORDER BY d.created_at DESC
                LIMIT $perPage OFFSET $offset";

        return $this->db->fetchAll($sql);
    }

    public function countList($user, $filters = array()) {
        $where = buildDocumentWhereClause($user);
        if (!empty($filters['status'])) {
            $s = $this->db->escape($filters['status']);
            $where .= " AND d.status = '$s'";
        }
        if (!empty($filters['keyword'])) {
            $kw = $this->db->escape($filters['keyword']);
            $where .= " AND (d.cooperative_name LIKE '%$kw%' OR d.ticket_code LIKE '%$kw%')";
        }
        if (!empty($filters['date_from'])) {
            $df = $this->db->escape($filters['date_from']);
            $where .= " AND DATE(d.created_at) >= '$df'";
        }
        if (!empty($filters['date_to'])) {
            $dt = $this->db->escape($filters['date_to']);
            $where .= " AND DATE(d.created_at) <= '$dt'";
        }
        $row = $this->db->fetchOne(
            "SELECT COUNT(*) as cnt FROM documents d JOIN users u ON u.id = d.submitted_by WHERE $where"
        );
        return intval($row['cnt']);
    }

    public function getById($id) {
        $id = intval($id);
        return $this->db->fetchOne(
            "SELECT d.*, CONCAT(u.prefix,' ',u.firstname,' ',u.lastname) AS submitter_name
             FROM documents d
             JOIN users u ON u.id = d.submitted_by
             WHERE d.id = $id"
        );
    }

    public function getLogs($documentId) {
        $documentId = intval($documentId);
        return $this->db->fetchAll(
            "SELECT dl.*, CONCAT(u.prefix,' ',u.firstname,' ',u.lastname) AS actor_name
             FROM document_logs dl
             JOIN users u ON u.id = dl.action_by
             WHERE dl.document_id = $documentId
             ORDER BY dl.created_at ASC"
        );
    }

    public function addLog($documentId, $action, $actionBy, $note = '') {
        $documentId = intval($documentId);
        $actionBy   = intval($actionBy);
        $action     = $this->db->escape($action);
        $note       = $this->db->escape($note);
        $this->db->query(
            "INSERT INTO document_logs (document_id, action, action_by, note)
             VALUES ($documentId, '$action', $actionBy, '$note')"
        );
    }

    public function generateTicketCode() {
        $year = date('Y') + 543;
        $row  = $this->db->fetchOne(
            "SELECT COUNT(*) AS cnt FROM documents WHERE fiscal_year = '$year'"
        );
        $seq  = intval($row['cnt']) + 1;
        return 'TKT-' . $year . '-' . str_pad($seq, 5, '0', STR_PAD_LEFT);
    }

    public function countByStatus($user) {
        $where = buildDocumentWhereClause($user);
        $rows = $this->db->fetchAll(
            "SELECT d.status, COUNT(*) AS cnt
             FROM documents d
             JOIN users u ON u.id = d.submitted_by
             WHERE $where
             GROUP BY d.status"
        );
        $result = array(
            'pending'    => 0,
            'inspecting' => 0,
            'approving'  => 0,
            'operating'  => 0,
            'revision'   => 0,
            'completed'  => 0,
        );
        foreach ($rows as $row) {
            $result[$row['status']] = intval($row['cnt']);
        }
        return $result;
    }

    public function countMonthly($user) {
        $where = buildDocumentWhereClause($user);
        return $this->db->fetchAll(
            "SELECT DATE_FORMAT(d.created_at, '%Y-%m') AS month, COUNT(*) AS count
             FROM documents d
             JOIN users u ON u.id = d.submitted_by
             WHERE $where
               AND d.created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
             GROUP BY DATE_FORMAT(d.created_at, '%Y-%m')
             ORDER BY month ASC"
        );
    }

    public function countByOfficeName() {
        return $this->db->fetchAll(
            "SELECT d.office_name AS office, COUNT(d.id) AS count
             FROM documents d
             GROUP BY d.office_name
             ORDER BY count DESC"
        );
    }
}
