# FourStripesPOS — Project Guidelines (Read Before Coding)

This is a **school project**. Please write code at a **high school / beginner student level** — clear, simple, and easy for me to explain line-by-line to a professor. Avoid advanced or "clever" patterns even if they're more efficient.

## Tech Stack

- **Framework:** Laravel 12 (uses the newer attribute-based model syntax — `#[Fillable]`, `#[Hidden]`, and a `casts()` method instead of `$fillable`/`$hidden`/`$casts` properties. Please match this style in Models.)
- **Frontend:** Blade components only — no Livewire, no Inertia, no Vue/React.
- **Styling:** Tailwind CSS **v4**. Configuration is CSS-first inside `resources/css/app.css` using the `@theme { }` block — **there is no `tailwind.config.js`** in this project. Custom colors/fonts are already defined there (see Brand Style Guide below).
- **JavaScript:** **Avoid JavaScript as much as possible.** No AJAX/fetch, no frontend frameworks, no build-your-own JS interactivity unless absolutely necessary. Forms use normal GET/POST requests and full page reloads — that's intentional, not a limitation to "fix."
- **Database:** MySQL via Eloquent ORM. Keep queries simple — basic `where()`, `paginate()`, no complex query builder chaining or raw SQL unless there's no simpler way.
- **PDF Generation:** dompdf (for receipts/reports, when we get there).

## Code Style Rules

1. **Comments are section headers only**, not explanations. Use ALL CAPS, short labels, like:
   ```php
   // LOGIN CARD
   // STOCK STATUS
   // ADMIN ONLY LINKS
   ```
   Do **not** write line-by-line explanatory comments (e.g. no "// this loops through the array and checks if...").

2. **No dashboard.** After login, both Admin and Staff land directly on the **POS** page.

3. **Sidebar only, no top navbar.** Each page includes its own `<x-page-header>` component inline (title, date/time, profile) instead of a persistent global navbar.

4. **Pagination:** always use Laravel's **default** built-in Tailwind pagination (`{{ $items->links() }}`) — plain white/gray style. Do not build custom-styled pagination.

5. **File delivery:** when generating code, provide files individually (one per file), not zipped.

6. **Keep controllers simple** — standard CRUD methods (`index`, `create`, `store`, `edit`, `update`, `destroy`), no service classes, repositories, or design patterns unless the task clearly needs one.

## Roles & Permissions

Two roles, stored in a `role` enum column on the `users` table: `admin` and `staff`.

| Feature | Admin | Staff |
|---|---|---|
| POS | Yes | Yes |
| Inventory | Yes | Yes |
| Sales Tracking | Yes | Yes |
| Reports | Yes | Yes |
| User Management | Yes | No |
| Audit Log | Yes | No |

Admin and Staff share almost all access — the only Admin-exclusive features are **User Management** and the **Audit Log**. Gate admin-only routes/views with `auth()->user()->isAdmin()` (a helper method already on the `User` model).

## Existing Reusable Components (in `resources/views/components/`)

- `<x-layout active="pos">` — main page shell (sidebar + content). Pass `guest` (no value needed) to skip the sidebar for pages like Login.
- `<x-sidebar>` — dark sidebar with brand colors, role-aware links, inline SVG icons.
- `<x-page-header title="..." subtitle="...">` — per-page header bar (hamburger, title, date/time, user profile).
- `<x-button type="submit" variant="primary">` — reusable button, `variant` can be `primary` or `secondary`.
- `<x-input label="..." name="..." type="...">` — text input with label, supports an optional `<x-slot:icon>` for a left-aligned icon.
- `<x-error name="...">` — shows Laravel validation error messages under a field.

## Brand Style Guide (defined in `resources/css/app.css` under `@theme`)

**Colors** (used as Tailwind classes, e.g. `bg-brand-yellow`, `text-success`):

| Class name | Hex |
|---|---|
| `brand-black` | `#0F1113` |
| `brand-yellow` | `#FCBC18` |
| `brand-yellow-deep` | `#9A6A00` |
| `brand-yellow-tint` | `#FFF8D8` |
| `success` / `success-tint` | `#10B981` / `#D1FAE5` |
| `warning` / `warning-tint` | `#F59E0B` / `#FEF3C7` |
| `danger` / `danger-tint` | `#EF4444` / `#FEE2E2` |
| `info` / `info-tint` | `#2563EB` / `#DBEAFE` |

Neutral ramp: `neutral-950` through `neutral-0` (950 darkest, 0 = white).

**Fonts:**
- `font-heading` → Montserrat (headings, labels, key values)
- `font-body` → Poppins (body text, sentences)

## Icons

- Sidebar nav icons are **inline SVG** directly in `sidebar.blade.php` (no external files).
- Other icons (login screen, etc.) are actual SVG/PNG files in `public/images/icons/`, referenced with `asset('images/icons/filename.svg')`. Ask me for exact filenames before assuming — I have a specific naming pattern (e.g. `grey-person.svg`, `yellow-gear.svg`).

## Business Context (why the system works this way)

- Sells **cacao processing machines and tools** (roasters, grinders, melangers, winnowers, presses, etc.) — not a general retail store.
- Suppliers are **outside the system** — the Owner orders manually via Messenger.
- No payment processing — cash/cashless is recorded as a field for reference only; cashless confirmation happens outside the system (Messenger).
- Reports/BIR export only needs date, transaction #, customer name, and total — one row per transaction, no aggregation.
- Warranty tracking is for **motor parts only, 1 month** — no other warranty types.

