-- ============================================
-- เพิ่มเติมจาก install_additions.sql (ครั้งที่ 1)
-- เพิ่มคอลัมน์ไฟล์แนบสำหรับตาราง issues (แจ้งปัญหาการใช้งานโปรแกรม)
-- MySQL 5.0.45 syntax เท่านั้น
-- ============================================

USE edoc_cooperative;

ALTER TABLE `issues`
  ADD COLUMN `attachment` varchar(255) default NULL COMMENT 'ไฟล์แนบ (path)' AFTER `detail`,
  ADD COLUMN `attachment_name` varchar(255) default NULL COMMENT 'ชื่อไฟล์แนบต้นฉบับ' AFTER `attachment`;
