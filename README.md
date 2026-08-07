# eDMS — Bootstrap → Tailwind conversion

## Status
This is **Batch 1 (Foundation)**. Bootstrap CSS + Bootstrap JS bundle are removed
completely. Nothing in this batch references `bootstrap`, `btn-*`, `card`, `badge`,
`dropdown-*`, `navbar-*`, `modal`, `collapse`, etc. Everything is Tailwind utility
classes (via the Tailwind CDN browser build, per your snippet) plus a small
`app.js` that replaces the Bootstrap JS behaviors (dropdowns, mobile nav
collapse, modal, toasts) with plain JS. SweetAlert2 is kept — it's an
independent library, not part of Bootstrap.

## What's included in this batch
- `views/layout/header.php` — Tailwind CDN, fonts, opens `<body>`
- `views/layout/navbar.php` — top nav, dropdowns, notification bell (pure JS, no Bootstrap)
- `views/layout/sidebar.php` — the contextual "help" sidebar
- `views/layout/footer.php` — closes layout, flash-message toast, script includes
- `views/layout/pagination.php` — Tailwind pagination
- `app.js` — **deduplicated** shared JS (ajax helpers, date inputs, dropdown/modal/toast
  engine, notif polling, role-visibility logic) — this is the single source of
  truth other pages call into, instead of each view reinventing small bits of JS
- `helpers/functions.php` — status/role/cooperative badge helpers now return
  Tailwind classes instead of Bootstrap `bg-*`/`badge-*` classes. Kept as the
  single place these mappings live (they were already centralized here, just
  updated for Tailwind).
- `views/auth/login.php` — fully converted example page
- `views/dashboard/index.php` — fully converted example page

## What's NOT in this batch yet (next batches)
- `views/documents/*` (index, create, edit, detail, _list_partial)
- `views/users/*` (index, create, edit, profile, _list_partial)
- `views/cooperatives/*` (index, create, edit, _list_partial)
- `views/reports/*`, `views/issue_reports/*`
- `views/issues/*` (index, create, detail, _list_partial)
- `views/notifications/*`, `views/announcements/*`
- `views/layout/_notif_dropdown.php`

Controllers/models are unaffected by this conversion (no markup there), so
they don't need to be resent — only view files change.

## Design tokens used (Tailwind arbitrary values, matching your original palette)
- Primary blue: `#1565c0` (navbar, banners, primary buttons)
- Success: `#2e7d32` / Danger: `#c62828` / Warning: `#e65100` / Info: `#0277bd`
- Purple (operator role / operating status): `#7b1fa2`
- Font: Sarabun (Google Fonts, same as before)
- Icons: Font Awesome 6 (kept, it's not Bootstrap)

## Modal/Dropdown/Collapse replacement
`app.js` now includes a tiny framework:
- `data-dropdown-toggle="id"` / `data-dropdown="id"` — click-to-open dropdown, closes on outside click
- `data-collapse-toggle="id"` — mobile nav collapse
- `openModal(id)` / `closeModal(id)` — for the revision modal etc.
- `showToast(type, message)` — thin wrapper still using SweetAlert2 toast (unchanged, not Bootstrap)

Say "continue" and I'll do the next batch (Documents pages), then Users,
Cooperatives, Reports/Issues, and Notifications/Announcements in that order.
