# Mayer Health Services (MHS) — Project Reference

## What This App Is

A **healthcare home-care management system** for a company called **Mayer Health Services (MHS)**. It lets an admin manage caregivers, assign them to clients, schedule services, generate invoices, and track payments. A separate user-facing portal lets clients sign up and pay for subscription packages via Stripe.

Live URL: `https://raymondpoku.rummanitsolution.com/`

---

## Technology Stack

| Layer | Technology |
|---|---|
| Language | PHP (CodeIgniter 3) |
| Database | MySQL via MySQLi |
| Frontend | Bootstrap, jQuery, SCSS |
| UI Extras | Flatpickr (dates), TinyMCE (rich text), Feather Icons |
| Payments | Stripe API (subscriptions) |
| Data Tables | Custom Datatables.php library (server-side rendering) |

---

## Project Structure

```
raymondpoku/
├── application/
│   ├── config/
│   │   ├── config.php          # base_url, session settings
│   │   ├── database.php        # DB credentials (host/user/pass/db)
│   │   ├── constants.php       # Table name constants, COMPANY, SHORTNAME
│   │   ├── routes.php          # URL routing
│   │   └── autoload.php        # Auto-loaded libraries/helpers
│   ├── controllers/
│   │   ├── Site.php            # Public: login, signup, profile, Stripe
│   │   └── Admin.php           # Admin: all CRUD + invoicing + reporting
│   ├── models/
│   │   ├── Site_model.php      # Auth, user creation, Stripe subscriptions
│   │   └── Admin_model.php     # Core business logic (caregivers, clients, services, invoices)
│   ├── core/
│   │   └── MY_Controller.php   # Base controller: auth guards, view helpers
│   ├── views/
│   │   ├── admin/              # 30+ admin panel views
│   │   └── site/               # login.php, signup.php, profile.php, error.php, success.php
│   ├── libraries/
│   │   ├── Datatables.php      # Server-side datatable builder
│   │   └── Utility.php
│   └── helpers/
│       └── utility_helper.php  # getSession(), isAdmin(), isUser(), sendJson(), etc.
├── assets/
│   ├── css/, scss/, js/
│   └── vendors/                # TinyMCE, Feather Icons, Flatpickr
├── uploads/                    # File uploads
├── images/                     # User profile images
└── index.php                   # CI3 front controller
```

---

## Database Tables

Defined via constants in `application/config/constants.php`:

| Constant | Table | Purpose |
|---|---|---|
| `TABLE_USERS` | `users` | Login accounts (admin + users) |
| `TABLE_CAREGIVERS` | `caregivers` | Healthcare workers |
| `TABLE_CLIENTS` | `clients` | People receiving care |
| `TABLE_SERVICES` | `services` | Scheduled care appointments |
| `TABLE_INVOICES` | `invoices` | Billing records |
| *(undefined)* | `packagehistory` | Stripe subscription history — **constant missing from constants.php** |

No SQL migration files exist in the repo. The DB name is `rummanit_raymondpoku`.

---

## Key Features

### Admin Panel (`Admin.php` / `Admin_model.php`)

**Caregivers**
- Full CRUD + archive/restore (soft delete via status flag)
- Fields: name, address, phone, email, SIN, DOB, hire date, position, base hourly rate, notes

**Clients**
- Full CRUD + archive/restore
- Fields: name, address, phone, referral source, budget, bill rate, adjuster/insurance info, budgeted hours
- Computed: totalBilled, totalPaid, outstanding balance

**Services / Scheduling**
- Bulk date entry (multiple dates at once)
- Assign caregiver → client with start/end times (auto-calculates hours)
- Service types, billable amount, cost amount
- Copy services to next month (recurring scheduling)
- Full calendar view with color coding
- Caregiver weekly hours report

**Invoicing**
- Generate invoices from services
- Statuses: Sent / Partially Paid / Fully Paid
- Tax, PO number, due date, payment tracking
- Print-ready invoice view
- Monthly/yearly financial reporting on dashboard

**Dashboard KPIs**
- Today's and weekly service hours
- Revenue totals, billed vs paid
- Monthly and year-over-year comparisons

### User Portal (`Site.php` / `Site_model.php`)

- Signup with Stripe subscription (1-month, 6-month, 12-month plans)
- Login / logout (MD5 passwords — known legacy issue)
- Profile management with image upload
- Package/subscription status display

---

## Authentication & Roles

Two role types: **admin** and **user** (stored in `users` table).

Auth guards in `MY_Controller.php`:
- `ifNotAdmin()` — redirects non-admins away from `/admin/*`
- `ifNotUser()` — redirects non-users
- `ifLogin()` / `ifNotLogin()` — redirect based on session state

Session cookie: `SameSite=None`, `Secure=True`.

---

## URL Routing

| URL | Controller → Method |
|---|---|
| `/` or `/site` | `Site::index()` (login page) |
| `/site/verify` | Login form POST handler |
| `/site/signup` | Registration page |
| `/admin` | `Admin::index()` (dashboard) |
| `/admin/caregivers` | Caregiver list |
| `/admin/clients` | Client list |
| `/admin/services` | Service list |
| `/admin/calendar` | Calendar view |
| `/admin/invoice` | Invoice list |

---

## Known Issues / Tech Debt

1. **MD5 passwords** — `Site.php::verify()` uses MD5. Should be migrated to `password_hash()` / `password_verify()`.
2. **CSRF disabled** — `$config['csrf_protection'] = FALSE` in config.php.
3. **XSS filtering disabled** — `$config['global_xss_filtering'] = FALSE`.
4. **Missing constant** — `TABLE_PACKAGEHISTORY` is used in models but not defined in `constants.php`.
5. **No DB migrations** — Schema must be managed manually.
6. **Stripe keys** — Were previously hardcoded in `application/config/stripe.php` (now removed from repo). Must be provided via environment or local config outside of version control.

---

## Helper Functions (utility_helper.php)

```php
getSession()          // returns current user session data
isAdmin()             // bool — is current user admin?
isUser()              // bool — is current user a regular user?
login_url($path)      // generate login-relative URL
admin_url($path)      // generate admin-relative URL
user_url($path)       // generate user-relative URL
sendJson($data)       // output JSON response and exit
currentUserType()     // returns 'admin' or 'user'
dnp($var)             // debug print (var_dump + exit)
dnd($var)             // debug print (print_r + exit)
```

---

## Development Notes

- **No test suite** exists.
- CodeIgniter 3 is EOL — upgrade path would be CI4 or Laravel.
- The `uploads/` and `images/` directories should be writable by the web server.
- The app expects to run from the repo root as the document root (standard CI3 setup).
- Stripe API keys must never be committed — use a local `application/config/stripe.php` excluded via `.gitignore`.
