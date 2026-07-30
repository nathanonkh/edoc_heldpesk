<?php
class IssueReportModel {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getSummaryByStatus($user, $filters = array()) {
        $where = buildIssueWhereClause($user);
        if (!empty($filters['office_name'])) {
            $on = $this->db->escape($filters['office_name']);
            $where .= " AND i.office_name = '$on'";
        }
        if (!empty($filters['issue_type'])) {
            $it = $this->db->escape($filters['issue_type']);
            $where .= " AND i.issue_type = '$it'";
        }
        if (!empty($filters['program_name'])) {
            $pn = $this->db->escape($filters['program_name']);
            $where .= " AND i.program_name = '$pn'";
        }
        if (!empty($filters['date_from'])) {
            $df = $this->db->escape($filters['date_from']);
            $where .= " AND DATE(i.created_at) >= '$df'";
        }
        if (!empty($filters['date_to'])) {
            $dt = $this->db->escape($filters['date_to']);
            $where .= " AND DATE(i.created_at) <= '$dt'";
        }
        return $this->db->fetchAll(
            "SELECT i.status, COUNT(*) AS cnt
             FROM issues i
             JOIN users u ON u.id = i.submitted_by
             WHERE $where
             GROUP BY i.status"
        );
    }

    public function getDetailReport($user, $filters = array()) {
        $where = buildIssueWhereClause($user);
        if (!empty($filters['status'])) {
            $s = $this->db->escape($filters['status']);
            $where .= " AND i.status = '$s'";
        }
        if (!empty($filters['office_name'])) {
            $on = $this->db->escape($filters['office_name']);
            $where .= " AND i.office_name = '$on'";
        }
        if (!empty($filters['issue_type'])) {
            $it = $this->db->escape($filters['issue_type']);
            $where .= " AND i.issue_type = '$it'";
        }
        if (!empty($filters['program_name'])) {
            $pn = $this->db->escape($filters['program_name']);
            $where .= " AND i.program_name = '$pn'";
        }
        if (!empty($filters['date_from'])) {
            $df = $this->db->escape($filters['date_from']);
            $where .= " AND DATE(i.created_at) >= '$df'";
        }
        if (!empty($filters['date_to'])) {
            $dt = $this->db->escape($filters['date_to']);
            $where .= " AND DATE(i.created_at) <= '$dt'";
        }
        return $this->db->fetchAll(
            "SELECT i.*, CONCAT(u.prefix,' ',u.firstname,' ',u.lastname) AS submitter_name
             FROM issues i
             JOIN users u ON u.id = i.submitted_by
             WHERE $where
             ORDER BY i.created_at DESC"
        );
    }
}
