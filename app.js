/**
 * eDMS Cooperative — Shared JS Utilities
 * สำนักงานตรวจบัญชีสหกรณ์ที่ 5
 */

// =====================================================
// AJAX Utility (XMLHttpRequest — รองรับ PHP 5.2)
// =====================================================
function ajaxPost(url, data, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', url, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            callback(xhr.status === 200, xhr.responseText);
        }
    };
    var pairs = [];
    for (var key in data) {
        if (data.hasOwnProperty(key)) {
            pairs.push(encodeURIComponent(key) + '=' + encodeURIComponent(data[key]));
        }
    }
    xhr.send(pairs.join('&'));
}

function ajaxGet(url, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            callback(xhr.status === 200, xhr.responseText);
        }
    };
    xhr.send();
}

// =====================================================
// Thai months
// =====================================================
var thaiMonths = {
    '01':'มกราคม','02':'กุมภาพันธ์','03':'มีนาคม',
    '04':'เมษายน','05':'พฤษภาคม','06':'มิถุนายน',
    '07':'กรกฎาคม','08':'สิงหาคม','09':'กันยายน',
    '10':'ตุลาคม','11':'พฤศจิกายน','12':'ธันวาคม'
};

// =====================================================
// Preview วว/ดด/ปปปป -> "31 มีนาคม 2569"
// =====================================================
function previewThaiDate(val, previewId) {
    var el = document.getElementById(previewId);
    if (!el) return;
    val = val.trim();
    var m = val.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);
    if (m) {
        var day   = parseInt(m[1], 10);
        var month = m[2].length === 1 ? '0' + m[2] : m[2];
        var mName = thaiMonths[month] || '';
        el.textContent = mName ? day + ' ' + mName + ' ' + m[3] : val;
    } else {
        el.textContent = val || '-';
    }
}

// =====================================================
// Preview วว/ดด -> "31 มีนาคม"
// =====================================================
function previewFiscalYear(val, previewId) {
    previewId = previewId || 'fiscalPreview';
    var el = document.getElementById(previewId);
    if (!el) return;
    val = val.trim();
    var m = val.match(/^(\d{1,2})\/(\d{1,2})$/);
    if (m) {
        var day   = parseInt(m[1], 10);
        var month = m[2].length === 1 ? '0' + m[2] : m[2];
        var mName = thaiMonths[month] || '';
        el.textContent = mName ? day + ' ' + mName : val;
    } else {
        el.textContent = val || '-';
    }
}

// =====================================================
// Setup date input: auto insert /, block non-digit
// =====================================================
function setupDateInput(inputId, previewId, withYear) {
    var el = document.getElementById(inputId);
    if (!el) return;
    el.addEventListener('keypress', function(e) {
        if (!/[\d\/]/.test(String.fromCharCode(e.which))) e.preventDefault();
    });
    el.addEventListener('input', function() {
        var v      = this.value.replace(/[^\d\/]/g, '');
        var digits = v.replace(/\//g, '');
        if (withYear) {
            if (digits.length >= 2 && v.indexOf('/') === -1) {
                v = digits.slice(0, 2) + '/' + digits.slice(2);
            }
            if (v.length >= 5 && v.split('/').length === 2) {
                var parts = v.split('/');
                if (parts[1].length >= 2) {
                    v = parts[0] + '/' + parts[1].slice(0, 2) + '/' + (digits.slice(4) || '');
                }
            }
            this.value = v;
            previewThaiDate(v, previewId);
        } else {
            if (digits.length >= 2 && v.indexOf('/') === -1) {
                v = digits.slice(0, 2) + '/' + digits.slice(2);
            }
            this.value = v;
            previewFiscalYear(v, previewId);
        }
    });
    if (withYear) {
        previewThaiDate(el.value, previewId);
    } else {
        previewFiscalYear(el.value, previewId);
    }
}

// =====================================================
// Role visibility by office
// =====================================================
var HQ_OFFICE   = '\u0e2a\u0e33\u0e19\u0e31\u0e01\u0e07\u0e32\u0e19\u0e15\u0e23\u0e27\u0e08\u0e1a\u0e31\u0e0d\u0e0a\u0e35\u0e2a\u0e2b\u0e01\u0e23\u0e13\u0e4c\u0e17\u0e35\u0e48 5';
var nonHqRoles  = ['inspector', 'approver', 'operator', 'admin'];

function updateRolesByOffice(officeName) {
    var isHQ = (officeName === HQ_OFFICE);
    for (var i = 0; i < nonHqRoles.length; i++) {
        var role = nonHqRoles[i];
        var card = document.getElementById('role-card-' + role);
        var chk  = document.getElementById('role_' + role);
        if (!card || !chk) continue;
        if (isHQ) {
            card.style.display      = '';
            card.style.opacity      = '1';
            card.style.pointerEvents = '';
            chk.disabled = false;
        } else {
            card.style.display = 'none';
            chk.checked        = false;
            chk.disabled       = true;
            card.style.borderColor = '';
            card.style.background  = '';
        }
    }
    if (!isHQ) {
        var subChk  = document.getElementById('role_submitter');
        var subCard = document.getElementById('role-card-submitter');
        if (subChk)  subChk.checked = true;
        if (subCard) { subCard.style.borderColor = '#0d6efd'; subCard.style.background = '#f0f7ff'; }
    }
}

var officeSelect = document.getElementById('officeSelect');
if (officeSelect) {
    officeSelect.addEventListener('change', function() {
        updateRolesByOffice(this.value);
    });
    updateRolesByOffice(officeSelect.value);
}

// =====================================================
// Position selector (employee_type -> position)
// =====================================================
function setupPositionSelector(empTypeId, posSelectId, currentPos) {
    var posMap = {
        civil:     ['ผู้อำนวยการสำนักงานตรวจบัญชีสหกรณ์ที่ 5','ผู้เชี่ยวชาญด้านการบัญชีและการสอบบัญชี','นักวิชาการตรวจสอบบัญชีชำนาญการพิเศษ','นักวิชาการตรวจสอบบัญชีชำนาญการ','นักวิชาการตรวจสอบบัญชีปฏิบัติการ'],
        contract:  ['เจ้าหน้าที่ระบบงานคอมพิวเตอร์','นักจัดการงานทั่วไป','นักวิชาการตรวจสอบบัญชี'],
        temporary: ['ลูกจ้างประจำ'],
        outsource: ['เจ้าหน้าที่ธุรการ','เจ้าหน้าที่บันทึกข้อมูล','เจ้าหน้าที่โครงการฯ']
    };
    function fillPositions(resetCurrent) {
        var t  = document.getElementById(empTypeId).value;
        var s  = document.getElementById(posSelectId);
        var ps = posMap[t] || [];
        s.innerHTML = '';
        for (var i = 0; i < ps.length; i++) {
            var sel = (!resetCurrent && ps[i] === currentPos) ? ' selected' : '';
            s.innerHTML += '<option value="' + ps[i] + '"' + sel + '>' + ps[i] + '</option>';
        }
    }
    document.getElementById(empTypeId).addEventListener('change', function() {
        currentPos = '';
        fillPositions(true);
    });
    fillPositions(false);
}

// =====================================================
// Poll unread notification count ทุก 30 วินาที
// =====================================================
function startNotifPolling() {
    function refresh() {
        ajaxGet('?page=documents&action=ajax_unread_count', function(ok, text) {
            if (!ok) return;
            try {
                var data   = JSON.parse(text);
                var badges = document.querySelectorAll('.notif-badge');
                for (var i = 0; i < badges.length; i++) {
                    if (data.count > 0) {
                        badges[i].textContent   = data.count > 99 ? '99+' : data.count;
                        badges[i].style.display = '';
                    } else {
                        badges[i].style.display = 'none';
                    }
                }
            } catch (e) {}
        });
    }
    setInterval(refresh, 30000);
}
