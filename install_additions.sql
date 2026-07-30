-- ============================================
-- เพิ่มเติมจาก install.sql เดิม
-- 1) ระบบแจ้งปัญหาการใช้งานโปรแกรมของสหกรณ์ (issues)
-- 2) ระบบประกาศ/วีดีโอสอนการใช้งานโปรแกรม สำหรับหน้าหลัก (announcements, tutorial_videos)
-- MySQL 5.0.45 syntax เท่านั้น (สอดคล้องกับ install.sql เดิม)
-- ============================================

USE edoc_cooperative;

-- --------------------------------------------------------
-- ตาราง `issues` - รายการแจ้งปัญหาการใช้งานโปรแกรมของสหกรณ์
-- --------------------------------------------------------
CREATE TABLE `issues` (
  `id` int(11) NOT NULL auto_increment,
  `ticket_code` varchar(50) NOT NULL COMMENT 'รหัส Ticket (ISS-ปี-xxxxx)',
  `cooperative_type_name` varchar(100) NOT NULL COMMENT 'ประเภทสหกรณ์',
  `cooperative_id` int(11) NOT NULL COMMENT 'FK: cooperatives.id',
  `cooperative_code` varchar(20) NOT NULL COMMENT 'รหัสสหกรณ์',
  `cooperative_name` varchar(255) NOT NULL COMMENT 'ชื่อสหกรณ์',
  `office_name` varchar(255) NOT NULL COMMENT 'สำนักงานที่รับผิดชอบสหกรณ์นี้ (ใช้กำหนดสิทธิ์การมองเห็น)',
  `issue_type` varchar(50) NOT NULL COMMENT 'ประเภทปัญหา (อ้างอิง helpers/functions.php::getIssueTypeOptions)',
  `program_name` varchar(50) NOT NULL COMMENT 'โปรแกรมที่พบปัญหา (อ้างอิง helpers/functions.php::getProgramOptions)',
  `title` varchar(255) NOT NULL COMMENT 'ชื่อเรื่อง',
  `detail` text NOT NULL COMMENT 'รายละเอียดปัญหา',
  `status` enum('pending','sent_central','in_progress','completed') default 'pending' COMMENT 'สถานะ',
  `submitted_by` int(11) NOT NULL COMMENT 'FK: users.id (ผู้แจ้งปัญหา)',
  `created_at` timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT 'วันที่แจ้ง',
  `updated_at` datetime default NULL COMMENT 'วันที่อัปเดตสถานะล่าสุด',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `ticket_code` (`ticket_code`),
  KEY `cooperative_id` (`cooperative_id`),
  KEY `submitted_by` (`submitted_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
-- ตาราง `issue_logs` - ประวัติการดำเนินการของแจ้งปัญหา
-- --------------------------------------------------------
CREATE TABLE `issue_logs` (
  `id` int(11) NOT NULL auto_increment,
  `issue_id` int(11) NOT NULL COMMENT 'FK: issues.id',
  `action` varchar(100) NOT NULL COMMENT 'การกระทำ',
  `action_by` int(11) NOT NULL COMMENT 'FK: users.id',
  `note` text COMMENT 'หมายเหตุ',
  `created_at` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `issue_id` (`issue_id`),
  KEY `action_by` (`action_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
-- ตาราง `announcements` - ประกาศที่แสดงในหน้าหลัก
-- --------------------------------------------------------
CREATE TABLE `announcements` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL COMMENT 'หัวข้อประกาศ',
  `content` text NOT NULL COMMENT 'เนื้อหาประกาศ',
  `is_pinned` tinyint(1) default '0' COMMENT 'ปักหมุดแสดงบนสุด',
  `is_active` tinyint(1) default '1' COMMENT 'สถานะการแสดงผล',
  `created_by` int(11) NOT NULL COMMENT 'FK: users.id',
  `created_at` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
-- ตาราง `tutorial_videos` - วีดีโอสอนการใช้งานโปรแกรม แสดงในหน้าหลัก
-- --------------------------------------------------------
CREATE TABLE `tutorial_videos` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL COMMENT 'หัวข้อวีดีโอ',
  `video_url` varchar(500) NOT NULL COMMENT 'ลิงก์วีดีโอ (แนะนำ YouTube)',
  `description` text COMMENT 'คำอธิบาย',
  `is_active` tinyint(1) default '1' COMMENT 'สถานะการแสดงผล',
  `created_by` int(11) NOT NULL COMMENT 'FK: users.id',
  `created_at` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
-- Constraints
-- --------------------------------------------------------
ALTER TABLE `issues`
  ADD CONSTRAINT `issues_ibfk_1` FOREIGN KEY (`cooperative_id`) REFERENCES `cooperatives` (`id`),
  ADD CONSTRAINT `issues_ibfk_2` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`);

ALTER TABLE `issue_logs`
  ADD CONSTRAINT `issue_logs_ibfk_1` FOREIGN KEY (`issue_id`) REFERENCES `issues` (`id`),
  ADD CONSTRAINT `issue_logs_ibfk_2` FOREIGN KEY (`action_by`) REFERENCES `users` (`id`);

ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

ALTER TABLE `tutorial_videos`
  ADD CONSTRAINT `tutorial_videos_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);
