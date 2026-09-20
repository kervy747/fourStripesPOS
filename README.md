# FourStripesPOS

A Laravel-based Point of Sale (POS) system built for **Four Stripes Equipment and Machines Corp.**, a seller of cacao machines, tools, and related equipment.

> Cacao Machines

## About

FourStripesPOS digitizes the company's manual logbook-based operations (Sales Logbook, Stocks Logbook, Pending-Order Logbook, Reports Logbook) into a single unified system. It handles point-of-sale transactions, inventory tracking, pending/backorders, shipping, reporting for BIR compliance, and full activity auditing.

## Roles

| Role | Access |
|---|---|
| **Owner/Manager (Admin)** | POS, Inventory, Sales Tracking, Reports, User Management, Audit Log |
| **Staff 1 (Main Staff)** | POS, Inventory, Sales Tracking |
| **Staff 2 (Owner's Wife)** | POS, Inventory, Sales Tracking (backup for Staff 1) |

Staff accounts share identical permissions. Reports, User Management, and the Audit Log are Admin-only.

## Core Features

- **POS** — checkout screen with cart, pending vs. completed order status (replaces the old Pending-Order Logbook), shipping toggle (pickup/shipped)
- **Inventory** — stock list with quantity tracking; staff deducts stock on sale and updates quantity after restock validation
- **Sales Tracking / Transaction History** — unified sales logbook (pending and completed orders, filterable by status)
- **Shipping** — zone-based dropdown + weight bracket calculator for shipping fees, shown as a separate line item on the receipt
- **Receipts** — itemized PDF generated per completed transaction
- **Reports** — BIR-compliant summary export (date, transaction #, customer name, total) plus detailed itemized view on demand
- **Warranty Tracking** — expiration field per sale item (1 month for motor parts, 1 year for service)
- **Audit Log** — tracks every system action (logins, sales, inventory changes, report exports, user management) across all roles for full transparency

## Tech Stack

- **Framework:** Laravel
- **Frontend:** Blade components (`<x-layout>`, `<x-sidebar>`, `<x-page-header>`, `<x-button>`, `<x-input>`, `<x-error>`, etc.)
- **Styling:** Tailwind CSS v4 — configured CSS-first inside `resources/css/app.css` via the `@theme` block (no `tailwind.config.js`)
- **Database:** MySQL (via Eloquent ORM)
- **PDF Generation:** dompdf (for receipts and reports)

## Brand Style Guide

Colors and fonts are defined once in `resources/css/app.css` under `@theme`, then used anywhere as Tailwind classes (e.g. `bg-brand-yellow`, `text-success`, `font-heading`).

**Colors**

| Name | Hex |
|---|---|
| Brand Black | `#0F1113` |
| Brand Yellow | `#FCBC18` |
| Brand Yellow Deep | `#9A6A00` |
| Brand Yellow Tint | `#FFF8D8` |

Neutral ramp: `950` `#0F1113` → `900` `#111827` → `700` `#4B5563` → `600` `#6B7280` → `400` `#9CA3AF` → `200` `#E5E7EB` → `100` `#F4F5F7` → `0` `#FFFFFF`

Semantic status: Success `#10B981` / tint `#D1FAE5` · Warning `#F59E0B` / tint `#FEF3C7` · Danger `#EF4444` / tint `#FEE2E2` · Info `#2563EB` / tint `#DBEAFE`

**Typography**

- `font-heading` → Montserrat (headings, labels, key values)
- `font-body` → Poppins (body text, sentences, metadata)

## File Structure

```
FourStripesPOS/
│
├── app/
│   ├── Models/
│   │   ├── User.php                      # staff/admin accounts, has role field
│   │   ├── Product.php                   # includes weight, price, warranty_type
│   │   ├── Sale.php                      # unified sales/orders (status: pending/completed)
│   │   ├── SaleItem.php                  # line items per sale, warranty_expires_at
│   │   ├── Customer.php                  # name, phone, address
│   │   ├── ShippingZone.php              # zone name, base rate, weight bracket rules
│   │   └── AuditLog.php                  # tracks every action, all roles
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   └── LoginController.php
│   │   │   │
│   │   │   ├── Admin/
│   │   │   │   ├── UserManagementController.php
│   │   │   │   ├── ReportController.php          # BIR summary + PDF export
│   │   │   │   └── AuditLogController.php
│   │   │   │
│   │   │   ├── Staff/
│   │   │   │   └── (staff shares controllers below, gated by middleware)
│   │   │   │
│   │   │   ├── PosController.php                 # checkout screen, cart, shipping calc
│   │   │   ├── InventoryController.php           # stock list, quantity updates
│   │   │   ├── SaleController.php                # sales logbook, pending->completed
│   │   │   └── ReceiptController.php             # generates per-sale PDF
│   │   │
│   │   └── Middleware/
│   │       ├── IsAdmin.php
│   │       └── LogsActivity.php                  # auto-writes to AuditLog
│   │
│   └── Services/
│       ├── ShippingCalculator.php                # zone + weight bracket logic
│       └── PdfReportGenerator.php                # wraps dompdf calls
│
├── database/
│   ├── migrations/
│   │   ├── xxxx_create_users_table.php
│   │   ├── xxxx_create_products_table.php
│   │   ├── xxxx_create_customers_table.php
│   │   ├── xxxx_create_sales_table.php
│   │   ├── xxxx_create_sale_items_table.php
│   │   ├── xxxx_create_shipping_zones_table.php
│   │   └── xxxx_create_audit_logs_table.php
│   └── seeders/
│       ├── UserSeeder.php
│       ├── ProductSeeder.php
│       └── ShippingZoneSeeder.php
│
├── resources/
│   └── views/
│       ├── components/
│       │   ├── layout.blade.php                  # <x-layout> — sidebar + slot shell (no navbar)
│       │   ├── sidebar.blade.php                 # role-aware nav links, brand colors
│       │   ├── page-header.blade.php             # per-page header (hamburger, title, date/time, profile)
│       │   ├── button.blade.php                  # <x-button> — reusable button styles
│       │   ├── input.blade.php                   # <x-input> — textfield with built-in label
│       │   ├── error.blade.php                   # <x-error> — validation error message
│       │   └── ui/
│       │       ├── table.blade.php
│       │       ├── modal.blade.php
│       │       └── status-badge.blade.php        # pending/completed pill
│       │
│       ├── auth/
│       │   └── login.blade.php
│       │
│       ├── admin/
│       │   ├── users/
│       │   │   ├── index.blade.php
│       │   │   ├── create.blade.php
│       │   │   └── edit.blade.php
│       │   ├── reports/
│       │   │   └── index.blade.php               # date range filter + "Generate PDF"
│       │   └── audit-log/
│       │       └── index.blade.php
│       │
│       ├── pos/
│       │   ├── index.blade.php                   # main POS/checkout screen
│       │   └── partials/
│       │       ├── cart.blade.php
│       │       └── shipping-fields.blade.php     # pickup/shipped toggle + address
│       │
│       ├── inventory/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   └── edit.blade.php
│       │
│       ├── sales/
│       │   ├── index.blade.php                   # Sales Logbook (filter: pending/completed)
│       │   └── show.blade.php                    # single transaction detail
│       │
│       └── receipts/
│           └── pdf.blade.php                     # dompdf template
│
├── routes/
│   └── web.php                                   # grouped by role middleware
│
└── public/
    └── ... (standard Laravel public assets)
```

## Build Order (Planned)

1. Auth & Users
2. Layout & Navigation shell
3. Products & Inventory
4. Customers
5. POS + Sales
6. Shipping Calculator
7. Receipts (PDF)
8. Reports (BIR export)
9. Audit Log
10. User Management (Admin)

## Notes

- No dashboard page — after login, both Admin and Staff land directly on **POS**.
- Suppliers are outside the system's scope — ordering is handled manually by the Owner via Messenger.
- Payment processing is not implemented (school project scope) — cash/cashless is recorded for reference only, confirmed manually outside the system.
- "Print" refers to PDF generation, not physical printing.
