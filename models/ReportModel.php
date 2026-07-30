<?php
class ReportModel {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getSummaryByStatus($user, $filters = array()) {
        $where = buildDocumentWhereClause($user);
        if (!empty($filters['fiscal_year'])) {
            $fy = $this->db->escape($filters['fiscal_year']);
            $where .= " AND d.fiscal_year = '$fy'";
        }
        if (!empty($filters['office_name'])) {
            $on = $this->db->escape($filters['office_name']);
            $where .= " AND d.office_name = '$on'";
        }
        return $this->db->fetchAll(
            "SELECT d.status, COUNT(*) AS cnt
             FROM documents d
             JOIN users u ON u.id = d.submitted_by
             WHERE $where
             GROUP BY d.status"
        );
    }

    public function getDetailReport($user, $filters = array()) {
        $where = buildDocumentWhereClause($user);
        if (!empty($filters['status'])) {
            $s = $this->db->escape($filters['status']);
            $where .= " AND d.status = '$s'";
        }
        if (!empty($filters['fiscal_year'])) {
            $fy = $this->db->escape($filters['fiscal_year']);
            $where .= " AND d.fiscal_year = '$fy'";
        }
        if (!empty($filters['office_name'])) {
            $on = $this->db->escape($filters['office_name']);
            $where .= " AND d.office_name = '$on'";
        }
        if (!empty($filters['date_from'])) {
            $df = $this->db->escape($filters['date_from']);
            $where .= " AND DATE(d.created_at) >= '$df'";
        }
        if (!empty($filters['date_to'])) {
            $dt = $this->db->escape($filters['date_to']);
            $where .= " AND DATE(d.created_at) <= '$dt'";
        }
        return $this->db->fetchAll(
            "SELECT d.*, CONCAT(u.prefix,' ',u.firstname,' ',u.lastname) AS submitter_name
             FROM documents d
             JOIN users u ON u.id = d.submitted_by
             WHERE $where
             ORDER BY d.created_at DESC"
        );
    }
}
