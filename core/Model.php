<?php
class Model {

    protected $conn;

    public function __construct() {
        $this->conn = mysql_connect(DB_HOST, DB_USER, DB_PASS);
        if (!$this->conn) {
            die('ไม่สามารถเชื่อมต่อฐานข้อมูลได้: ' . mysql_error());
        }
        if (!mysql_select_db(DB_NAME, $this->conn)) {
            die('ไม่พบฐานข้อมูล: ' . mysql_error());
        }
        mysql_set_charset('utf8', $this->conn);
    }

    public function query($sql) {
        $result = mysql_query($sql, $this->conn);
        if ($result === false) {
            error_log('DB Error: ' . mysql_error($this->conn) . ' | SQL: ' . $sql);
        }
        return $result;
    }

    public function fetchAll($sql) {
        $result = $this->query($sql);
        $rows = array();
        if ($result) {
            while ($row = mysql_fetch_assoc($result)) {
                $rows[] = $row;
            }
            mysql_free_result($result);
        }
        return $rows;
    }

    public function fetchOne($sql) {
        $result = $this->query($sql);
        if ($result) {
            $row = mysql_fetch_assoc($result);
            mysql_free_result($result);
            return $row ? $row : null;
        }
        return null;
    }

    public function insertId() {
        return mysql_insert_id($this->conn);
    }

    public function escape($str) {
        return mysql_real_escape_string($str, $this->conn);
    }
}
