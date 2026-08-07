/**
 * eDMS Cooperative — Shared JS Utilities (Tailwind edition)
 * สำนักงานตรวจบัญชีสหกรณ์ที่ 5
 *
 * This file is the single source of truth for behaviors that used to be
 * spread across Bootstrap's JS bundle (dropdowns, collapse, modal) plus the
 * page-level duplicated helpers (ajax, date inputs, role visibility, notif
 * polling). Every view should call into these instead of re-implementing.
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
// Toast helper (SweetAlert2 — independent of Bootstrap, kept)
// =====================================================
function showToast(icon, title, timer) {
    if (typeof Swal === 'undefined') { return; }
    Swal.fire({
        icon: icon,
        title: title,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: timer || 2500
    });
}

// =====================================================
// Dropdown engine (replaces Bootstrap's dropdown JS)
// Usage: <button data-dropdown-toggle="myMenu">...</button>
//        <div id="myMenu" data-dropdown class="hidden ...">...</div>
// =====================================================
(function() {
    document.addEventListener('click', function(e) {
        var toggle = e.target.closest('[data-dropdown-toggle]');
        if (toggle) {
            e.preventDefault();
            var id = toggle.getAttribute('data-dropdown-toggle');
            var menu = document.getElementById(id);
            if (!menu) return;
            var isOpen = !menu.classList.contains('hidden');
            closeAllDropdowns();
            if (!isOpen) menu.classList.remove('hidden');
            return;
        }
        // click outside any dropdown menu -> close all
        if (!e.target.closest('[data-dropdown]')) {
            closeAllDropdowns();
        }
    });

    function closeAllDropdowns() {
        var menus = document.querySelectorAll('[data-dropdown]');
        for (var i = 0; i < menus.length; i++) menus[i].classList.add('hidden');
    }
    window.closeAllDropdowns = closeAllDropdowns;
})();

// =====================================================
// Mobile nav collapse (replaces Bootstrap's collapse JS)
// Usage: <button data-collapse-toggle="mainNavbar">...</button>
//        <div id="mainNavbar" class="hidden md:block">...</div>
// =====================================================
(function() {
    document.addEventListener('click', function(e) {
        var toggle = e.target.closest('[data-collapse-toggle]');
        if (!toggle) return;
        e.preventDefault();
        var id = toggle.getAttribute('data-collapse-toggle');
        var el = document.getElementById(id);
        if (el) el.classList.toggle('hidden');
    });
})();

// =====================================================
// Modal engine (replaces Bootstrap's modal JS)
// Usage: <div id="myModal" class="fixed inset-0 hidden ...">...</div>
// =====================================================
function openModal(id) {
    var modal = document.getElementById(id);
    if (!modal) return;
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeModal(id) {
    var modal = document.getElementById(id);
    if (!modal) return;
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Close modal when clicking its backdrop (element with data-modal-backdrop)
document.addEventListener('click', function(e) {
    var backdrop = e.target.closest('[data-modal-backdrop]');
    if (backdrop) {
        var modal = backdrop.closest('[data-modal]');
        if (modal) closeModal(modal.id);
    }
});

// =====================================================
// Confirm-delete dialog (SweetAlert2)
// =====================================================
function confirmDelete(url, name) {
    Swal.fire({
        title: 'ยืนยันการลบ?',
        text: 'ระงับการใช้งาน: ' + name,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ยกเลิก'
    }).then(function(result) {
        if (result.isConfirmed) { window.location.href = url; }
    });
}

// =====================================================
// Thai months / date helpers
// =====================================================
var thaiMonths = {
    '01':'มกราคม','02':'กุมภาพันธ์','03':'มีนาคม',
    '04':'เมษายน','05':'พฤษภาคม','06':'มิถุนายน',
    '07':'กรกฎาคม','08':'สิงหาคม','09':'กันยายน',
    '10':'ตุลาคม','11':'พฤศจิกายน','12':'ธันวาคม'
};

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
// Role visibility by office (users/create.php + edit.php)
// =====================================================
var HQ_OFFICE  = '\u0e2a\u0e33\u0e19\u0e31\u0e01\u0e07\u0e32\u0e19\u0e15\u0e23\u0e27\u0e08\u0e1a\u0e31\u0e0d\u0e0a\u0e35\u0e2a\u0e2b\u0e01\u0e23\u0e13\u0e4c\u0e17\u0e35\u0e48 5';
var nonHqRoles = ['inspector', 'approver', 'operator', 'admin'];

// Tailwind "selected" style applied to a role-card element
function setRoleCardSelected(card, selected) {
    if (!card) return;
    if (selected) {
        card.classList.add('border-blue-600', 'bg-blue-50');
        card.classList.remove('border-slate-200');
    } else {
        card.classList.remove('border-blue-600', 'bg-blue-50');
        card.classList.add('border-slate-200');
    }
}

function updateRolesByOffice(officeName) {
    var isHQ = (officeName === HQ_OFFICE);
    for (var i = 0; i < nonHqRoles.length; i++) {
        var role = nonHqRoles[i];
        var card = document.getElementById('role-card-' + role);
        var chk  = document.getElementById('role_' + role);
        if (!card || !chk) continue;
        if (isHQ) {
            card.classList.remove('hidden');
            chk.disabled = false;
        } else {
            card.classList.add('hidden');
            chk.checked  = false;
            chk.disabled = true;
            setRoleCardSelected(card, false);
        }
    }
    if (!isHQ) {
        var subChk  = document.getElementById('role_submitter');
        var subCard = document.getElementById('role-card-submitter');
        if (subChk)  subChk.checked = true;
        if (subCard) setRoleCardSelected(subCard, true);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var officeSelect = document.getElementById('officeSelect');
    if (officeSelect) {
        officeSelect.addEventListener('change', function() {
            updateRolesByOffice(this.value);
        });
        updateRolesByOffice(officeSelect.value);
    }

    var checkboxes = document.querySelectorAll('.role-checkbox');
    for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].addEventListener('change', function() {
            var card = document.getElementById('role-card-' + this.value);
            setRoleCardSelected(card, this.checked);
        });
        var card0 = document.getElementById('role-card-' + checkboxes[i].value);
        if (card0 && checkboxes[i].checked) setRoleCardSelected(card0, true);
    }
});

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
// Help sidebar toggle (mobile) — dedup'd out of footer.php
// =====================================================
function toggleHelpSidebar() {
    var sidebar = document.getElementById('helpSidebar');
    if (sidebar) sidebar.classList.toggle('translate-x-full');
}

document.addEventListener('click', function(e) {
    var sidebar   = document.getElementById('helpSidebar');
    var toggleBtn = document.getElementById('helpToggleBtn');
    if (!sidebar || !toggleBtn) return;
    if (window.innerWidth >= 992) return;
    if (sidebar.classList.contains('translate-x-full')) return;
    if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
        sidebar.classList.add('translate-x-full');
    }
});

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
                        badges[i].classList.remove('hidden');
                    } else {
                        badges[i].classList.add('hidden');
                    }
                }
            } catch (e) {}
        });
    }
    setInterval(refresh, 30000);
}
