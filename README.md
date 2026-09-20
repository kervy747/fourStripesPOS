# FourStripesPOS

<p align="center">
  <img src="https://i.imgur.com/PeEdYw7.png" alt="Four Stripes Equipment and Machines Corp. Logo" width="200">
</p>

A Laravel-based Point of Sale (POS) system built for **Four Stripes Equipment and Machines Corp.**, a seller of cacao machines, tools, and related equipment.

> Cacao Machines. Better Possibilities.

## About

FourStripesPOS digitizes the company's manual logbook-based operations (Sales Logbook, Stocks Logbook, Pending-Order Logbook, Reports Logbook) into a single unified system. It handles point-of-sale transactions, inventory tracking, pending/backorders, shipping, reporting for BIR compliance, and full activity auditing.

## Roles

| Role | Access |
|---|---|
| **Owner/Manager (Admin)** | POS, Inventory, Sales Tracking, Reports, User Management, Audit Log |
| **Staff 1 (Main Staff)** | POS, Inventory, Sales Tracking, Reports |
| **Staff 2 (Owner's Wife)** | POS, Inventory, Sales Tracking, Reports (backup for Staff 1) |

Admin and Staff share nearly identical access — the only Admin-exclusive tools are **User Management** and the **Audit Log**. Staff can view/export Reports the same as Admin.

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
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Sale.php
│   │   ├── SaleItem.php
│   │   ├── Customer.php
│   │   ├── ShippingZone.php
│   │   └── AuditLog.php
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   └── LoginController.php
│   │   │   │
│   │   │   ├── Admin/
│   │   │   │   ├── UserManagementController.php
│   │   │   │   └── AuditLogController.php
│   │   │   │
│   │   │   ├── Staff/
│   │   │   │   └── (staff shares controllers below, gated by middleware)
│   │   │   │
│   │   │   ├── PosController.php
│   │   │   ├── InventoryController.php
│   │   │   ├── SaleController.php
│   │   │   ├── ReportController.php
│   │   │   └── ReceiptController.php
│   │   │
│   │   └── Middleware/
│   │       ├── IsAdmin.php
│   │       └── LogsActivity.php
│   │
│   └── Services/
│       ├── ShippingCalculator.php
│       └── PdfReportGenerator.php
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
│       │   ├── layout.blade.php
│       │   ├── sidebar.blade.php
│       │   ├── page-header.blade.php
│       │   ├── button.blade.php
│       │   ├── input.blade.php
│       │   ├── error.blade.php
│       │   └── ui/
│       │       ├── table.blade.php
│       │       ├── modal.blade.php
│       │       └── status-badge.blade.php
│       │
│       ├── auth/
│       │   └── login.blade.php
│       │
│       ├── admin/
│       │   ├── users/
│       │   │   ├── index.blade.php
│       │   │   ├── create.blade.php
│       │   │   └── edit.blade.php
│       │   └── audit-log/
│       │       └── index.blade.php
│       │
│       ├── reports/
│       │   └── index.blade.php
│       │
│       ├── pos/
│       │   ├── index.blade.php
│       │   └── partials/
│       │       ├── cart.blade.php
│       │       └── shipping-fields.blade.php
│       │
│       ├── inventory/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── form.blade.php
│       │
│       ├── sales/
│       │   ├── index.blade.php
│       │   └── show.blade.php
│       │
│       └── receipts/
│           └── pdf.blade.php
│
├── routes/
│   └── web.php
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
