# 🌾 cFarm — Kenya's Farm Produce Marketplace

> A full-stack Laravel marketplace connecting Kenyan farmers directly with buyers,
> powered by real government price data from KAMIS updated daily.

[![CI Pipeline](https://img.shields.io/badge/CI-Jenkins-blue?logo=jenkins)](https://github.com/AlvinMwatati/cFarm)
[![PHP](https://img.shields.io/badge/PHP-8.5-777BB4?logo=php)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel)](https://laravel.com)
[![Docker](https://img.shields.io/badge/Docker-Sail-2496ED?logo=docker)](https://laravel.com/docs/sail)
[![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)

---

## The Problem

Smallholder farmers in Kenya — who make up over 70% of the country's food
producers — often sell their produce without knowing the real market price.
They rely on brokers and middlemen who exploit this information gap, buying
cheap at the farm gate and selling at a significant markup in markets.

The Kenya government collects and publishes this price data through KAMIS
(Kenya Agricultural Market Information System), but it's locked behind a
website that most farmers never visit.

## What cFarm Does

cFarm is a marketplace that puts that information in farmers' hands. It:

- **Scrapes KAMIS daily** — pulls 30,000+ price records across 170+ commodities
  and 47 counties every morning at 6AM automatically
- **Shows real market prices** — farmers and buyers see what maize sells for
  in Meru, Nairobi, Kakamega — before they drive to market
- **Connects buyers and sellers directly** — no middlemen, no commission
- **Sends price alerts** — users follow commodities and get notified when
  prices move beyond their threshold via in-app, email, or SMS

---

## Live Features

| Feature | Description |
|---|---|
| 🔐 **Authentication** | Register/login with phone number and county. Role-based (user/admin) via Spatie |
| 🌽 **Commodity Catalogue** | 12+ Kenyan commodities with categories and units |
| 📋 **Listings Marketplace** | Post produce with photos, price, quantity. Filter by commodity, county, price range |
| 📊 **Market Insights** | Price trends, county averages, min/max ranges from real KAMIS government data |
| 🤖 **KAMIS Scraper** | Automated daily scraper downloads Excel files per commodity from kamis.kilimo.go.ke |
| 🔔 **Price Alerts** | Follow commodities, set thresholds, get notified on price drops/spikes/new listings |
| 📧 **Multi-channel Notifications** | In-app bell, email (Mailpit/SMTP), SMS via Africa's Talking |
| 📅 **Weekly Summaries** | Automated Monday morning digest of price movements |
| 👨‍💼 **Admin Dashboard** | User management, listing moderation, commodity management, scraper monitoring |
| ⚙️ **Background Jobs** | Queue worker processes scraping jobs in the background with live status polling |
| 🚀 **CI Pipeline** | Jenkins runs full test suite on every push via GitHub webhook |

---

## Tech Stack

### Backend
| Technology | Version | Purpose |
|---|---|---|
| **PHP** | 8.5 | Core language |
| **Laravel** | 11 | Application framework |
| **MySQL** | 8.4 | Primary database |
| **Redis** | Alpine | Cache, queue signals |
| **Laravel Queues** | — | Background job processing |
| **Laravel Notifications** | — | Multi-channel notification system |
| **Laravel Sail** | — | Docker development environment |

### Key Packages
| Package | Purpose |
|---|---|
| `spatie/laravel-permission` | Role and permission management (user/admin roles) |
| `spatie/laravel-medialibrary` | File uploads with image conversions (listing photos) |
| `spatie/laravel-data` | Typed DTOs for clean data transfer between layers |
| `phpoffice/phpspreadsheet` | Parse `.xls` Excel files from KAMIS export endpoint |
| `predis/predis` | Pure PHP Redis client (no extension required) |
| `africastalking/africastalking` | SMS delivery via Africa's Talking for Kenyan numbers |

### Frontend
| Technology | Purpose |
|---|---|
| **Blade** | Server-side templating |
| **Tailwind CSS** | Utility-first styling |
| **Alpine.js** | Lightweight reactivity (dropdowns, toggles) |
| **Vite** | Asset bundling |
| **Chart.js** | Price trend charts |

### Infrastructure & DevOps
| Technology | Purpose |
|---|---|
| **Docker** | Containerised development via Laravel Sail |
| **Jenkins** | CI server — runs on every push to `develop` and `main` |
| **GitHub** | Source control, GitHub Flow branching strategy |
| **ngrok** | Tunnels GitHub webhooks to local Jenkins |
| **Mailpit** | Local email testing — catches all outgoing mail |

---

## Architecture

### Design Principles

This project was built following **SOLID principles** throughout:

- **Single Responsibility** — Each class does one thing. Controllers are thin,
  business logic lives in Action classes (`CreateListingAction`, `GetCommodityInsightsAction`)
- **Open/Closed** — The pricing system is open for extension via `PricingSourceInterface`.
  Adding a new data source requires only a new class — zero changes to existing code
- **Liskov Substitution** — `ListingsPricingSource` and `KamisPricingSource` are
  interchangeable implementations of `PricingSourceInterface`
- **Interface Segregation** — Notification channels are separated; each notification
  class declares which channels it needs via `via()`
- **Dependency Inversion** — Controllers and actions depend on interfaces, not
  concrete implementations. `AppServiceProvider` decides which implementation to inject

### Project Structure

```
app/
├── Actions/              # Single-purpose business logic classes
│   ├── Commodities/
│   ├── Insights/
│   └── Listings/
├── Channels/             # Custom notification channels (AfricasTalking)
├── Console/Commands/     # Artisan commands (kamis:scrape, notifications:weekly-summary)
├── Contracts/            # Interfaces (PricingSourceInterface)
├── DTOs/                 # Typed data transfer objects via spatie/laravel-data
├── Enums/                # PHP 8.1 enums (ListingStatus, CommodityUnit, KenyaCounty)
├── Http/
│   ├── Controllers/
│   │   ├── Admin/        # Admin-only controllers (separate namespace)
│   │   ├── Insights/
│   │   ├── Listings/
│   │   └── Notifications/
│   ├── Middleware/       # EnsureUserIsAdmin
│   └── Requests/         # Form request validation classes
├── Jobs/                 # Queueable jobs (ScrapeKamisJob)
├── Models/               # Eloquent models
├── Notifications/        # Notification classes (PriceDropAlert, etc.)
├── Policies/             # Authorization policies
├── Providers/            # AppServiceProvider — dependency injection bindings
└── Services/
    ├── Notifications/    # PriceAlertService, NewListingNotificationService
    ├── Pricing/          # ListingsPricingSource, KamisPricingSource, mapper
    └── Scraping/         # KamisScraperService
```

### The KAMIS Scraper

The most technically interesting part of the project. KAMIS doesn't have a
public API, so cFarm scrapes it:

```
Daily 6:00 AM
     │
     ▼
ScrapeKamisJob (queued)
     │
     ▼
KamisScraperService
     │
     ├── Fetch kamis.kilimo.go.ke/site/market HTML
     │   └── Extract <option value="ID"> tags via regex
     │       → discovers all 170+ product IDs dynamically
     │
     └── For each product ID:
         └── GET /site/market?product={id}&per-page=3000&export=excel
             └── Parse .xls with PhpSpreadsheet
                 └── updateOrCreate in market_prices table
                     (keyed on commodity + market + date)

Result: 30,000+ price records covering all 47 counties
```

### The Pricing Source Interface

```
PricingSourceInterface
├── getAveragePricesByCounty(Commodity)
├── getPriceRange(Commodity)
├── getWeeklyTrend(Commodity)
└── getMostListedCommodities()

Implementations:
├── ListingsPricingSource   ← uses listings table (always available)
└── KamisPricingSource      ← uses market_prices table (populated by scraper)

AppServiceProvider binds: KamisPricingSource if data exists, else fallback
```

### CI Pipeline

```
git push origin develop
        │
        ▼
GitHub fires webhook → ngrok → Jenkins
        │
        ▼
Jenkinsfile pipeline:
  1. cleanWs()              ← prevent workspace corruption
  2. checkout scm
  3. composer install
  4. npm ci
  5. npm run build          ← Vite manifest required by @vite() in views
  6. cp .env.testing.example .env.testing
     php artisan key:generate --env=testing
  7. php artisan test --env=testing
        │
        ├── SQLite :memory: (no MySQL needed in CI)
        └── All tests pass → ✅ Green
```

---

## Getting Started

### Prerequisites

- Docker & Docker Compose
- Git

### Installation

```bash
# Clone the repository
git clone https://github.com/AlvinMwatati/cFarm.git
cd cFarm

# Install PHP dependencies
docker run --rm -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php85-composer:latest \
    composer install --ignore-platform-reqs

# Copy environment file
cp .env.example .env

# Start the environment
./vendor/bin/sail up -d

# Generate app key
sail artisan key:generate

# Run migrations and seed data
sail artisan migrate --seed

# Build frontend assets
sail npm install
sail npm run build
```

### Running the Queue Worker

In a second terminal:

```bash
sail artisan queue:work --sleep=3 --tries=1 --timeout=900
```

### Seeding KAMIS Data

```bash
# Pull real price data from KAMIS (takes 5–10 minutes)
sail artisan kamis:scrape
```

### Running Tests

```bash
sail artisan test
```

### Environment Variables

Key variables to configure in `.env`:

```env
# Mail (local testing via Mailpit at localhost:8025)
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025

# Africa's Talking SMS (sandbox for testing)
AT_USERNAME=sandbox
AT_API_KEY=your_sandbox_key

# Queue
QUEUE_CONNECTION=database
REDIS_CLIENT=predis
```

---

## Testing

The project has feature tests covering all major functionality:

```
tests/Feature/
├── AuthTest.php              # Registration, login, ban check
├── CommodityTest.php         # Commodity CRUD and policies
├── ListingTest.php           # Listing creation, status toggle, policies
├── MarketplaceTest.php       # Filtering, search, pagination
├── InsightsTest.php          # Price range, county averages, weekly trend
├── KamisScraperTest.php      # Scraper with HTTP::fake(), duplicate prevention
├── NotificationTest.php      # Follow/unfollow, price alerts, new listing alerts
└── AdminTest.php             # Admin access control, ban, listing moderation
```

All tests use:
- `RefreshDatabase` with SQLite `:memory:` — fast, isolated, no MySQL needed
- `Http::fake()` for external HTTP calls — tests never hit real KAMIS
- `Notification::fake()` for notification assertions
- `#[Test]` PHPUnit 12 attributes (not deprecated docblock `@test`)

```bash
# Run all tests
sail artisan test

# Run a specific file
sail artisan test tests/Feature/InsightsTest.php

# Run with coverage
sail artisan test --coverage
```

---

## What I Learned Building This

This project was built over 8 days as a deliberate exercise in production-quality
Laravel development. Here's what the journey taught me:

### Technical Skills

**Laravel Architecture**
I went from writing logic directly in controllers to understanding why that's a
problem at scale. The Actions pattern (`CreateListingAction`, `GetCommodityInsightsAction`)
keeps controllers as thin HTTP adapters and business logic in testable, reusable
classes. Every feature now has a clear home.

**SOLID Principles in Practice**
The pricing insights feature was where SOLID clicked. I needed market price data
from two sources — internal listings and an external government website. Writing
`PricingSourceInterface` first meant the controller never changed when the data
source changed. Dependency inversion stopped being a theoretical concept and
became a practical tool I'll use forever.

**Web Scraping Real-World Messiness**
KAMIS taught me that real-world data is never clean. The government site has no
API, the Excel files have inconsistent formats, some product IDs return empty
responses, dates come as Excel serial numbers, prices come as strings like
`"55.00/Kg"`. I learned to write defensive parsers, use `updateOrCreate` for
idempotency, and design scrapers that fail gracefully with detailed logging.

**Docker & Development Environments**
Laravel Sail made Docker approachable but I had to go deeper — building a custom
`cfarm-php:8.4` image for Jenkins with specific PHP extensions (GD with JPEG
support, pdo_sqlite for test isolation), understanding the difference between
images and containers, Docker networking (why `DB_HOST=mysql` works inside Docker),
and volume mounts for persistence.

**CI/CD with Jenkins**
Setting up Jenkins via Docker with a GitHub webhook taught me how CI actually
works end-to-end — not just "run tests" but the full pipeline: workspace
management, dependency installation, asset compilation (Vite manifest errors
taught me that CI environments have no dev server), environment setup, and
test execution. The `cleanWs()` lesson — workspace corruption from a missing
`git clean` — is one I won't forget.

**Background Jobs & Queues**
The KAMIS scraper takes 5–10 minutes. Running it synchronously in a web request
would time out. Building `ScrapeKamisJob`, setting up the database queue driver,
implementing cache-based status polling, and debugging queue worker container
issues (user permission errors, missing PHP extensions, Redis client mismatches)
gave me a deep understanding of how Laravel queues actually work.

**Testing Strategy**
Writing tests that don't hit real external services (`Http::fake()`, 
`Notification::fake()`) taught me that good tests are isolated tests.
SQLite `:memory:` for speed. The `YEARWEEK()` vs `strftime()` incompatibility
between MySQL and SQLite taught me that tests should mirror production as
closely as possible — which led to writing database-agnostic queries.

**Database Design**
Designing the `commodity_follows` table — with per-commodity notification
preferences, per-channel toggles, and per-user thresholds — taught me to
think about data models from the user's perspective first, not the developer's.
The `market_prices` table with proper indexes on `(commodity_name, county, price_date)`
taught me that index design matters as soon as you have 30,000+ rows.

### Soft Skills & Process

**Git Flow in a Real Project**
Working with `feature/*` → `develop` → `main` and writing meaningful commit
messages (`feat:`, `fix:`, `ci:`) made the project history readable and the
CI pipeline meaningful. Every merge to `develop` is a verified, tested feature.

**Debugging Production-Like Issues**
Many problems only showed up in the Docker/Jenkins environment — Vite manifest
missing, GD extension without JPEG support, queue worker user permission errors,
Redis client not installed. Debugging these taught me to read stack traces
carefully, isolate the root cause from the symptom, and verify fixes systematically.

**Building in Public**
Committing to building this publicly — with a real CI pipeline, real government
data, real SMS integration — forced a level of quality that "private project"
work doesn't. Every feature had to actually work, not just look like it worked.

---

## Project Status

| Area | Status |
|---|---|
| Core marketplace | ✅ Complete |
| KAMIS scraper | ✅ Complete |
| Price insights | ✅ Complete |
| Notifications | ✅ Complete |
| Admin dashboard | ✅ Complete |
| CI pipeline | ✅ Green |
| UI/Design polish | 🔄 In Progress |
| Production deployment | 📋 Planned |
| CD pipeline | 📋 Planned |

---

## Author

**Alvin Mwatati**  
Full-Stack Developer — Nairobi, Kenya

Built this project to demonstrate production-quality Laravel development
with real DevOps practices. If you're hiring and you've read this far,
let's talk.

- GitHub: [@AlvinMwatati](https://github.com/AlvinMwatati)

---

## Acknowledgements

- [KAMIS](https://kamis.kilimo.go.ke) — Kenya Agricultural Market Information System
  for making market price data publicly accessible
- [Laravel](https://laravel.com) — for the best developer experience in PHP
- [Spatie](https://spatie.be) — for the packages that made this cleaner

---

*"Stop guessing. Start selling at the right price."*
