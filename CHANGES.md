# สรุปการแก้ไข (สำหรับ PHP 5.2.3 / AppServ 2.5.9 / MySQL 5.0.45)

ไฟล์ทั้งหมดในนี้คือไฟล์ **ที่แก้ไขแล้วเท่านั้น** ให้คัดลอกทับไฟล์เดิมตาม path
โครงสร้างโฟลเดอร์เดียวกับโปรเจกต์เดิม (index.php อยู่ root, core/, helpers/,
controllers/, models/ ตามเดิม)

## ⚠️ ขั้นตอนก่อน deploy (ต้องทำตามลำดับ)

1. **สำรองฐานข้อมูลก่อน** (mysqldump ทั้งฐาน) เผื่อ rollback
2. รัน `migration.sql` กับฐานข้อมูลจริง 1 ครั้ง (ขยายคอลัมน์ `users.password`
   เป็น VARCHAR(255) เพื่อรองรับ hash รูปแบบใหม่ที่ยาวขึ้น)
3. คัดลอกไฟล์ทั้งหมดในนี้ทับไฟล์เดิมตาม path
4. **ลบไฟล์ `models/DocumentController.php` ทิ้ง** — เป็นไฟล์ซ้ำกับ
   `controllers/DocumentController.php` ทุกตัวอักษร ที่หลงเหลืออยู่ผิดโฟลเดอร์
   ไม่ต้องใช้งานและไม่ถูก require โดย index.php อยู่แล้ว (ปลอดภัยที่จะลบ)
5. ตรวจสอบ `magic_quotes_gpc` — เปิด phpinfo() ดูค่านี้:
   - ถ้า **Off** อยู่แล้ว → ไม่ต้องทำอะไรเพิ่ม (index.php มีโค้ดเช็คสำรองไว้ให้แล้ว)
   - ถ้า **On** → แนะนำปิดใน php.ini (`magic_quotes_gpc = Off`) แล้ว restart Apache
     ของ AppServ เพื่อความสะอาดของโค้ดในระยะยาว (index.php มี auto-detect +
     stripslashes ป้องกันไว้แล้วเช่นกัน จึงใช้งานได้ทั้งสองกรณี)
6. ทดสอบ login ด้วยบัญชีเดิม 1 บัญชีก่อนปล่อยใช้งานจริง — รหัสผ่านเดิม (sha1)
   จะยัง login ผ่านได้ปกติ และจะถูกอัปเกรดเป็น hash แบบใหม่อัตโนมัติเบื้องหลัง

## รายการไฟล์ที่แก้ไข/เพิ่มใหม่

| ไฟล์ | สถานะ | สรุปการแก้ไข |
|---|---|---|
| `index.php` | แก้ไข | ป้องกัน `magic_quotes_gpc` (strip slashes ซ้ำ), require `PasswordHash.php` |
| `core/Model.php` | แก้ไข | ไม่แสดง `mysql_error()` ให้ผู้ใช้เห็นตรง ๆ (log เข้า error_log แทน) |
| `core/Session.php` | แก้ไข | CSRF token entropy สูงขึ้น (`hash('sha256', ...)` แทน `sha1(uniqid())`) |
| `helpers/PasswordHash.php` | **ใหม่** | Salted hash (hmac-sha256) รองรับ PHP 5.2 + backward-compat กับ sha1 เดิม + auto-rehash |
| `helpers/Auth.php` | แก้ไข | ใช้ `PasswordHash` แทน sha1 ตรง ๆ, เพิ่ม `session_regenerate_id(true)` กัน session fixation |
| `helpers/functions.php` | แก้ไข | `buildDocumentWhereClause()`/`buildIssueWhereClause()` ใช้ `$db->escape()` แทน `addslashes()` |
| `helpers/FileUpload.php` | แก้ไข | Validate `fiscal_year`/`cooperativeCode`/`subDir` ด้วย regex กัน Path Traversal |
| `controllers/DocumentController.php` | แก้ไข | แก้ IDOR ใน `view_file()` (เดิมเช็คแค่ login ไม่เช็คสิทธิ์เอกสาร), validate `fiscal_year` ใน `store()` |
| `controllers/CooperativeController.php` | แก้ไข | เพิ่ม `Auth::checkCsrf()` ใน `delete()` |
| `controllers/AnnouncementController.php` | แก้ไข | เพิ่ม `Auth::checkCsrf()` ใน `delete()` และ `delete_video()` |
| `controllers/UserController.php` | แก้ไข | เพิ่ม `Auth::checkCsrf()` ใน `delete()`, ใช้ `PasswordHash::verify()` ใน `update_profile()` |
| `models/UserModel.php` | แก้ไข | `create()`/`update()`/`updateProfile()` ใช้ `PasswordHash::hash()` แทน `sha1()` |
| `app.js` | แก้ไข | `confirmDelete()` ส่ง POST + CSRF token แทน `window.location.href` (GET เปล่า) — **ไม่ต้องแก้ view ใดเลย** เพราะ signature ฟังก์ชันเหมือนเดิม |
| `migration.sql` | **ใหม่** | ขยายคอลัมน์ `users.password` เป็น VARCHAR(255) |

## ไฟล์ที่ควรลบ (ไม่ได้แนบมาเพราะเป็นการลบ ไม่ใช่แก้ไข)

- ❌ `models/DocumentController.php` — ไฟล์ซ้ำ ให้ลบทิ้ง (ดูข้อ 4 ด้านบน)

## สิ่งที่ยังเป็นข้อจำกัดของ PHP 5.2 (ทำได้แค่ mitigate ไม่ใช่แก้เต็มรูปแบบ)

- **CSRF token / salt generation**: ยังไม่ใช่ cryptographically secure random
  100% เพราะ PHP 5.2 ไม่มี `random_bytes()`/`openssl_random_pseudo_bytes()`
  (ต้อง PHP 7.0/5.3+ ตามลำดับ) ใช้การผสม entropy จาก `uniqid()` + `microtime()`
  + `mt_rand()` + `session_id()` แทน ซึ่งดีขึ้นกว่าเดิมมากแต่ในทางทฤษฎียัง
  แข็งแกร่งน้อยกว่า CSPRNG แท้
- **`hash_equals()`**: ไม่มีใน PHP 5.2 (ต้อง 5.6+) การเทียบ CSRF token ยังใช้
  `===` ตรง ๆ ซึ่งมีความเสี่ยง timing-attack ในทางทฤษฎี แต่ในบริบทเว็บแอปนี้
  (ผ่าน network, token เป็น sha256 คงที่) ความเสี่ยงต่ำมาก
- **`mysql_*` extension**: ยังคงใช้ตามเดิมเพราะเป็น extension เดียวที่ใช้ได้กับ
  PHP 5.2.3 (`mysqli` เริ่มมีตั้งแต่ 5.0 แต่ `PDO_MySQL` ที่ query แบบ
  prepared statement เต็มรูปแบบมักไม่ได้ compile มาให้ใน AppServ รุ่นนี้)
  ระบบยังพึ่งพา `mysql_real_escape_string()` ผ่าน `$db->escape()` เป็นหลัก
  ซึ่งถ้าใช้ถูกต้องสม่ำเสมอ (ตามที่แก้ไขในรอบนี้) ก็ปลอดภัยเพียงพอสำหรับ
  โค้ดที่ escape ทุกจุดแล้ว

## สิ่งที่ยังไม่ได้แก้ในรอบนี้ (นอกขอบเขตคำขอ แต่ควรพิจารณาต่อ)

- **DB credentials ใน `config/config.php`** เป็น plaintext ในซอร์สโค้ด —
  แนะนำแยกไฟล์ config ที่ไม่ commit เข้า repo หรือเปลี่ยนรหัสผ่านหลัง deploy จริง
- **`.htaccess`** ป้องกันการเข้าถึง `core/`, `helpers/`, `models/`, `controllers/`,
  `config/` ตรง ๆ ผ่าน URL — ยังไม่ได้เพิ่มให้ เพราะขึ้นกับการตั้งค่า Apache
  ของ AppServ จริงซึ่งไม่สามารถทดสอบแทนได้ในสภาพแวดล้อมนี้
- **Rate limiting การ login** — ยังไม่มีการจำกัดจำนวนครั้งที่ login ผิด
