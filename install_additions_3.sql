-- ============================================
-- เพิ่มเติมจาก install_additions.sql / install_additions_2.sql
-- ทำให้ตาราง notifications รองรับการแจ้งเตือนของ "แจ้งปัญหา" (issues) ได้ด้วย
-- นอกเหนือจากการแจ้งเตือนเอกสาร (documents) เดิม
-- MySQL 5.0.45 syntax เท่านั้น
-- ============================================

USE edoc_cooperative;

ALTER TABLE `notifications`
  MODIFY `document_id` int(11) NULL COMMENT 'FK: documents.id (สำหรับการแจ้งเตือนเอกสาร)',
  ADD COLUMN `issue_id` int(11) NULL COMMENT 'FK: issues.id (สำหรับการแจ้งเตือนเรื่องแจ้งปัญหา)' AFTER `document_id`;

ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_3` FOREIGN KEY (`issue_id`) REFERENCES `issues` (`id`);
