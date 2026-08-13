# 🚀 VIBE-STARTER: LARAVEL 13.X MASTER CONTEXT & DIRECTIVES

## 1. TECH STACK & ARCHITECTURE (STRICT)
- **Framework**: Laravel 13.x, PHP 8.5+, Vite 8
- **Frontend/Admin**: Filament 3.x, Tailwind CSS v4, Livewire 3
- **Database**: SQLite (Local Default) -> PostgreSQL/MySQL (Production). Primary keys MUST use UUID v7 (`HasUuids`).
- **Auth & Audit**: Spatie Permission + Spatie Activitylog.
- **Testing**: Pest v5 (Feature & Unit).
- **Queue**: Code for Laravel Horizon/Redis (Production) but anticipate `sync` or `database` on local Windows environments. Always use `afterCommit()` for DB transactions.
- **Architecture Boundaries**: Action-Oriented. Use `app/Actions/`, `app/DTOs/`, `app/Enums/`, `app/Services/`, `app/Contracts/`. 
- **Coding Standard**: `declare(strict_types=1)`, PSR-12/PER, Thin Controllers, Form Requests, `$fillable` only (No `$guarded = []`), No `env()` outside config.

## 2. UI/UX & FRONTEND PRINCIPLES (THE "VIBE")
When generating views, Filament panels, or frontend components, the AI MUST adhere to:
- **Mobile-First & Responsive**: Layouts must be flawless on mobile, tablet, and desktop.
- **Fast & Smart Loading**: Utilize lazy loading, skeleton loaders, and optimized asset delivery.
- **Intuitive Navigation**: Always include breadcrumbs, accessible search bars, and logical sidebar grouping.
- **Visual Excellence**: Implement custom UI components, consistent typography/branding, and modern iconography (Heroicons/Lucide).
- **Clean & DRY Code**: Reusable Blade/Livewire components. Never duplicate frontend markup unnecessarily.

## 3. BUSINESS MODULES & FLEXIBILITY CANVAS
This starter kit is a blank canvas designed to instantly scale into complex systems (UMKM, Corporate, Government, BUMN). When prompted to build a system (e.g., ERP, POS, SIMRS), automatically provision these core modules:
- **User Management**: Advanced RBAC, secure authentication (login/register/logout).
- **Content Management**: Dynamic page builders, settings, blog/article management.
- **Smart Business Transactions**: Smart CRUD for Quotations, Invoices, Payments, PO, DO, SO, and Project Tracking.
- **Data Analytics**: Interactive Filament Dashboards (Widgets, Charts, KPIs).
- **Domain-Specific Scaffolding Capable**: The architecture must flex to support:
  - **ERP & Asset Management**: Financials, HRD, Asset depreciation, maintenance.
  - **POS & Dropshipping**: Online cashiers (retail/resto/warung), reseller/supplier management.
  - **Reservations & Marketplaces**: Booking engines (hotel, medical, sports), custom multi-vendor platforms.
  - **Education & Health**: Custom LMS (Moodle-like), School Admin (PPDB), SIMRS (EMR, Queue management).
  - **Logistics & Gov**: Cargo tracking, Smart Village (Desa Digital) citizen DB.

## 4. INTEGRATIONS & WEBHOOK STANDARDS
- **Payment Gateways**: Midtrans, Xendit, PayPal. Use dedicated API client services and verify webhook signatures.
- **Email & Notifications**: SMTP (Postmark/Gmail), WhatsApp API (Meta Cloud/Baileys), Firebase/OneSignal for realtime. Dispatch via background queues.
- **External APIs & Automations**: Webhook handlers for ERP/CRM/Google Workspace. Anticipate n8n, Google App Scripts, and strict Cron Job (`routes/console.php`) configurations.

## 5. HARDCORE SECURITY & COMPLIANCE GUARDRAILS
Never generate code that compromises these security pillars:
- **Data Protection**: AES-256 encryption for sensitive strings. Force HTTPS/SSL schemas.
- **Hardening**: Prepared statements ONLY (zero SQLi). strict CSRF, XSS escaping, and API Rate Limiting.
- **DDOS & Firewall**: Anticipate Cloudflare CDN layers; do not expose internal IP headers directly.
- **Privacy (GDPR)**: Support "Right to be Forgotten" (Soft deletes, irreversible anonymization).
- **Bot Protection**: Integrate Google reCAPTCHA v3 or Cloudflare Turnstile on public forms.
- **Maintenance**: Architect for automated Object Storage backups. Implement comprehensive audit logging.
- **SEO**: Dynamic Meta tags and OpenGraph configurations for public-facing web pages.
