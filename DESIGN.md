# cFarm — UI Design & Completion Guide

> Hand this file to an AI assistant to complete the entire cFarm frontend.
> It contains the aesthetic direction, design system, component patterns,
> and page-by-page specifications for every route in the application.

---

## Project Context

**cFarm** is Kenya's farm produce marketplace — connecting farmers directly
with buyers across all 47 counties. It pulls real government price data from
KAMIS (Kenya Agricultural Market Information System) updated daily, giving
users market intelligence that was previously only available to large traders.

**Core value propositions:**
1. Real KAMIS government price data — not guesswork
2. Direct farmer-to-buyer connections — no middlemen
3. Price alerts — know before the market moves
4. County-level granularity — 47 counties, hyperlocal data

**Users:** Small-scale farmers, produce traders, market analysts, agribusiness buyers.

---

## Aesthetic Direction

### Theme: **"Soil & Signal"**

Earthy, grounded, and data-rich. Think the intersection of a well-worn
Kenyan market stall and a Bloomberg terminal. Warm terracotta and deep
forest green as anchors. Clean data tables. Dense with information but
never cluttered. This is a tool people will use every day — it must feel
trustworthy, fast, and alive.

**NOT:** Generic SaaS purple. Not sterile white. Not generic "farm green."
**YES:** The warmth of red soil. The depth of Kenyan tea. The precision of
a commodity trading floor.

### Color Palette

```css
:root {
  /* Primary — Terracotta / Red Soil */
  --color-primary:        #C1440E;
  --color-primary-dark:   #8B3008;
  --color-primary-light:  #E8693A;
  --color-primary-muted:  #F4DDD3;

  /* Secondary — Forest / Tea */
  --color-secondary:      #2D5016;
  --color-secondary-dark: #1A2F0D;
  --color-secondary-light:#4A7A28;
  --color-secondary-muted:#D6E8C4;

  /* Accent — Golden Harvest */
  --color-accent:         #D4A017;
  --color-accent-light:   #F5E6A3;

  /* Neutrals */
  --color-soil:           #3D2B1F;   /* deep brown — headings */
  --color-bark:           #6B4C35;   /* medium brown — body text */
  --color-sand:           #F5EFE6;   /* warm off-white — backgrounds */
  --color-parchment:      #FBF7F2;   /* card backgrounds */
  --color-stone:          #C4B5A5;   /* borders, dividers */
  --color-mist:           #EAE4DC;   /* subtle backgrounds */

  /* Semantic */
  --color-up:             #2D8A4E;   /* price increase — deep green */
  --color-down:           #C1440E;   /* price decrease — terracotta */
  --color-neutral:        #8A7968;   /* no change */

  /* Dark mode overrides */
  --color-bg-dark:        #1C1410;
  --color-surface-dark:   #2A1E17;
  --color-border-dark:    #3D2B1F;
}
```

### Typography

```css
/* Import in <head> */
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap');

:root {
  --font-display: 'Playfair Display', Georgia, serif;  /* Logo, hero headlines */
  --font-body:    'DM Sans', sans-serif;               /* All UI text */
  --font-mono:    'DM Mono', monospace;                /* Prices, data, numbers */
}
```

**Typography rules:**
- All prices and numerical data use `--font-mono` — this is non-negotiable
- Hero headlines use `--font-display` at large sizes
- Everything else uses `--font-body`
- Prices always show 2 decimal places: `KES 45.00`
- Large numbers use commas: `32,415`

### Logo

```html
<!-- cFarm wordmark — use in nav and footer -->
<a href="/" class="cfarm-logo">
  <span class="logo-c">c</span><span class="logo-farm">Farm</span>
</a>

<style>
.cfarm-logo {
  font-family: 'Playfair Display', serif;
  font-weight: 900;
  font-size: 1.5rem;
  text-decoration: none;
  letter-spacing: -0.02em;
}
.logo-c {
  color: var(--color-primary);
  font-style: italic;
}
.logo-farm {
  color: var(--color-soil);
}
/* Dark backgrounds */
.logo-inverted .logo-farm { color: #FBF7F2; }
</style>
```

### Spacing & Radius

```css
:root {
  --radius-sm:  4px;
  --radius-md:  8px;
  --radius-lg:  12px;
  --radius-xl:  20px;
  --radius-full: 9999px;

  --shadow-sm: 0 1px 3px rgba(61,43,31,0.08), 0 1px 2px rgba(61,43,31,0.04);
  --shadow-md: 0 4px 12px rgba(61,43,31,0.10), 0 2px 4px rgba(61,43,31,0.06);
  --shadow-lg: 0 8px 24px rgba(61,43,31,0.12), 0 4px 8px rgba(61,43,31,0.08);
}
```

### Texture & Atmosphere

Add a subtle grain texture to the main background using a CSS pseudo-element
or SVG filter. This gives the app warmth and avoids the sterile "white SaaS" look.

```css
body::before {
  content: '';
  position: fixed;
  inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
  pointer-events: none;
  z-index: 0;
  opacity: 0.4;
}
```

---

## Shared Components

### Navigation (`resources/views/layouts/navigation.blade.php`)

Full-width sticky nav. Left: cFarm logo. Center: main nav links.
Right: notification bell + user avatar dropdown.

```
┌─────────────────────────────────────────────────────────────────┐
│  cFarm    Browse    Market Prices    Sell Produce    My Alerts  🔔 👤 │
└─────────────────────────────────────────────────────────────────┘
```

- Background: `--color-parchment` with 1px bottom border `--color-stone`
- Sticky, `backdrop-filter: blur(8px)` with slight transparency on scroll
- Active link: `--color-primary` with subtle underline
- Notification bell: shows red badge with count when unread > 0
- User dropdown: avatar initials circle in `--color-primary-muted`
- Mobile: hamburger menu, slides in from left
- Admin link visible only to admin users — styled in terracotta red

### Page Layout

```
┌──────────────────────────────────────────┐
│              NAV (sticky)                 │
├──────────────────────────────────────────┤
│   Page Header (breadcrumb + title + CTA)  │
├──────────────────────────────────────────┤
│                                           │
│              Main Content                 │
│                                           │
└──────────────────────────────────────────┘
```

Tailwind class pattern for main content area:
```html
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
```

### Cards

```html
<!-- Standard content card -->
<div class="bg-[--color-parchment] rounded-[--radius-lg]
            border border-[--color-stone]
            shadow-[--shadow-sm] p-6">
```

### Stat Cards (for dashboards and insights)

```html
<div class="stat-card">
  <p class="stat-label">Average Price</p>
  <p class="stat-value">KES 45.50</p>
  <p class="stat-change up">▲ 12.3% this week</p>
</div>

<style>
.stat-card { background: var(--color-parchment); border-radius: var(--radius-lg);
             border: 1px solid var(--color-stone); padding: 1.25rem; }
.stat-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;
              color: var(--color-bark); font-family: var(--font-body); }
.stat-value { font-family: var(--font-mono); font-size: 1.75rem; font-weight: 500;
              color: var(--color-soil); margin: 0.25rem 0; }
.stat-change { font-size: 0.8rem; font-family: var(--font-mono); }
.stat-change.up   { color: var(--color-up); }
.stat-change.down { color: var(--color-down); }
</style>
```

### Price Badge

```html
<!-- Used everywhere a price appears inline -->
<span class="price-badge">KES 45.00<span class="unit">/kg</span></span>

<style>
.price-badge { font-family: var(--font-mono); font-weight: 500;
               color: var(--color-soil); }
.price-badge .unit { font-size: 0.8em; color: var(--color-bark);
                     margin-left: 2px; }
</style>
```

### Buttons

```html
<!-- Primary -->
<button class="btn-primary">Post Listing</button>

<!-- Secondary -->
<button class="btn-secondary">View Details</button>

<!-- Danger -->
<button class="btn-danger">Delete</button>

<style>
.btn-primary {
  background: var(--color-primary); color: white;
  font-family: var(--font-body); font-weight: 600; font-size: 0.875rem;
  padding: 0.625rem 1.25rem; border-radius: var(--radius-md);
  border: none; cursor: pointer; transition: all 0.15s ease;
}
.btn-primary:hover { background: var(--color-primary-dark); transform: translateY(-1px);
                     box-shadow: var(--shadow-md); }
.btn-secondary {
  background: transparent; color: var(--color-soil);
  border: 1.5px solid var(--color-stone); font-family: var(--font-body);
  font-weight: 500; font-size: 0.875rem; padding: 0.625rem 1.25rem;
  border-radius: var(--radius-md); cursor: pointer; transition: all 0.15s ease;
}
.btn-secondary:hover { border-color: var(--color-primary);
                        color: var(--color-primary); }
</style>
```

### Empty States

Every list/table that can be empty needs a warm, on-brand empty state:

```html
<div class="empty-state">
  <span class="empty-icon">🌱</span>
  <p class="empty-title">No listings yet</p>
  <p class="empty-desc">Be the first to post produce in this category.</p>
  <a href="/listings/create" class="btn-primary">Post a Listing</a>
</div>
```

Use contextual icons: 🌱 for listings, 📊 for prices, 🔔 for notifications,
🌾 for commodities.

---

## Page Specifications

---

### 1. Landing Page (`/`) — `resources/views/home.blade.php`

**Goal:** Convert visitors into registered users. Lead with the KAMIS price
data feature — this is the most unique thing cFarm offers. Make it feel like
they're getting insider market intelligence.

**Sections (in order):**

#### Hero Section
Full-viewport hero. Split layout on desktop:
- Left (60%): Headline, subheadline, CTAs
- Right (40%): Animated price ticker showing live commodity prices

```
┌────────────────────────────────────────────────────┐
│                                                    │
│   Know the Market.          ┌──────────────────┐  │
│   Sell at the Right Price.  │ 🌽 Maize         │  │
│                             │ KES 45.50/kg ▲2% │  │
│   Real KAMIS government     │ 🍅 Tomatoes      │  │
│   prices. Updated daily.    │ KES 82.00/kg ▼5% │  │
│   47 counties.              │ 🥔 Potatoes      │  │
│                             │ KES 35.00/bag    │  │
│   [Browse Market Prices]    │ 🌿 Kale          │  │
│   [Register Free →]         │ KES 12.00/bundle │  │
│                             └──────────────────┘  │
│                                                    │
└────────────────────────────────────────────────────┘
```

Background: Deep terracotta-to-soil gradient with subtle grain texture.
Text: White and `--color-parchment`. The price ticker animates — rows
scroll up every 3 seconds revealing more commodities.

Headline font: `--font-display`, very large (clamp 2.5rem to 4.5rem).
Headline copy suggestion:
> "Kenya's Farm Prices,  
> In Your Hands."

Sub-copy:
> Real government market data from KAMIS. Updated every morning.
> Know what maize sells for in Meru before you drive to market.

CTAs:
- Primary: "See Today's Prices" → `/insights`
- Secondary: "Sell Your Produce" → `/register`

#### Social Proof Bar
Full-width muted background strip immediately below hero:

```
32,415 Price Records  •  170 Commodities  •  47 Counties  •  Updated Daily 6AM
```

Font mono for numbers. Subtle left-right scroll animation on mobile.

#### "Why Farmers Trust cFarm" Section
3-column feature grid. Icons are large (48px emoji or SVG), not tiny.

| 📊 Real Government Prices | 🔔 Price Alerts | 🤝 No Middlemen |
|---|---|---|
| We pull directly from KAMIS — the official Kenya Agricultural Market Information System — every morning at 6AM. | Follow any commodity and get notified when prices spike or drop beyond your threshold. | Post your produce and connect directly with buyers. No commission. No gatekeepers. |

#### Featured Market Insights
Show 3 commodity cards with real price data and trend arrows.
Each card links to `/insights/{commodity}`.

```
┌──────────────┐  ┌──────────────┐  ┌──────────────┐
│   🌽 Maize   │  │  🍅 Tomatoes │  │  🥔 Potatoes │
│  KES 45.50   │  │  KES 82.00   │  │  KES 35.00   │
│  per kg      │  │  per kg      │  │  per bag     │
│  ▲ 12% week  │  │  ▼ 5% week   │  │  — steady    │
│  View Prices →│  │  View Prices →│  │  View Prices →│
└──────────────┘  └──────────────┘  └──────────────┘
```

#### Latest Listings Grid
6 most recent active listings. Standard listing card format (see Listings section).
"View All Listings →" link at the end.

#### How It Works
3-step horizontal flow with connecting line between steps:

```
  [1]─────────────────[2]─────────────────[3]
Register & Post    Buyers Find You    Connect Directly
Your Produce       by County &        No Commission
                   Commodity          No Middleman
```

Step circles: `--color-primary` fill, white number.
Connecting line: dashed `--color-stone`.

#### Call to Action Banner
Full-width terracotta section before footer:

> "Stop guessing. Start selling at the right price."
> [Create Free Account] [View Market Prices]

#### Footer
Dark background (`--color-soil`). Logo (inverted), nav links, social links.
Add: "Price data sourced from KAMIS — Kenya Agricultural Market Information System"

---

### 2. Dashboard (`/dashboard`) — `resources/views/dashboard.blade.php`

**Who sees this:** Logged-in users. Their personal hub.

**Layout:** 2-column grid on desktop (sidebar-style stats left, content right).

**Sections:**

#### Welcome Header
```
Good morning, Alvin 👋
Here's what's happening in your followed markets today.
```
Personalized greeting based on time of day.

#### Quick Stats Row (4 cards)
```
My Listings    Followed        Unread           Price
    3          Commodities    Notifications     Alerts
              8              2                 Today: 5
```

#### My Price Alerts Feed
List of recent notifications (last 10) inline on dashboard.
Each notification shows:
- Icon (📉📈🌾📊)
- Message
- Time ago
- "View" link

If no alerts: "You're not following any commodities yet. [Browse Commodities →]"

#### My Active Listings (last 3)
Compact listing cards with status badge. "View all my listings →" link.

#### Market Overview
Mini price table showing current prices for their followed commodities.
If following none: "Follow commodities to see prices here. [Browse →]"

#### Recently Active Commodities
Small grid of commodity chips showing the 8 most-listed commodities.
Clicking goes to `/listings?commodity_id=X`.

---

### 3. Market Insights Index (`/insights`) — `resources/views/insights/index.blade.php`

**This is the hero feature page. Make it feel like a financial data terminal
crossed with a Kenyan market stall.**

**Header:**
Large hero section with headline and subtext. Not a gray box — make it pop.
Background: subtle diagonal stripe pattern in `--color-secondary-muted`.

> "Kenya Market Prices"  
> Sourced from KAMIS Government Data — Updated Daily at 6:00 AM

Show last updated timestamp prominently.

**Layout: Two-panel**

Left sidebar (25%): Commodity browser — list of all commodities grouped by
category (Grains, Vegetables, Fruits, Dairy, Legumes). Clicking selects the
commodity and loads its data inline OR navigates to `/insights/{commodity}`.
Use Tailwind sticky positioning.

Right main (75%): Content area.

**Default state (no commodity selected):**

#### Platform Highlights
Row of 3 stat cards:
- Most Active Commodity (highest listing count)
- Biggest Price Mover This Week (highest % change)
- Latest Data Date

#### Most Listed Commodities Chart
Horizontal bar chart (CSS-only, no JS library needed).
Each bar: commodity name left, bar fills proportionally, count right.
Bars use `--color-primary` with 10% opacity fill and solid left border.

```
Maize     ████████████████████ 142 listings
Tomatoes  ███████████████      98 listings
Potatoes  ██████████           67 listings
Kale      ████████             51 listings
```

#### County Price Map Table
Table showing average prices per county for the top commodity.

**Commodity selected state:**
Navigate to `/insights/{commodity}` (see next section).

---

### 4. Market Insights Show (`/insights/{commodity}`) — `resources/views/insights/show.blade.php`

**The single most data-rich page. Think Bloomberg for maize.**

**Header:**
Commodity name large. Breadcrumb: Insights > Maize
Follow button on right: "🔔 Follow for Alerts" (or "✓ Following" if already following).

**Stats Row (4 cards):**
```
Min Price      Max Price      Avg Price      Data Points
KES 42.00      KES 65.00      KES 52.40      1,247 records
```

**Price Trend Chart:**
8-week weekly trend. Implement as a CSS/SVG sparkline or use Chart.js
(available in Sail). Show data points, trend line, week labels on X axis,
price on Y axis.

Color: rising segments in `--color-up`, falling in `--color-down`.

```
KES
 65 │           ●───●
 60 │         ●/
 55 │       ●/
 50 │   ●───●
 45 │ ●/
    └─────────────────
    Wk1  2   3   4   5
```

**Price by County — Bar Chart:**
Horizontal bars. County name left, bar proportional to price, price right.
Color-code by region (Coast, Central, Rift Valley, Nyanza, etc.) using
subtle bar tint variations.

**Data Source Notice:**
Small muted note at bottom:
> "Prices sourced from KAMIS (Kenya Agricultural Market Information System).
> Retail prices shown. Data updated daily at 6:00 AM."

**Related Listings CTA:**
Full-width terracotta strip:
> "Ready to buy or sell {{ $commodity->name }}?"
> [View Active Listings] [Post a Listing]

---

### 5. Commodities Index (`/commodities`) — `resources/views/commodities/index.blade.php`

**Grid of commodity cards with category filter tabs.**

**Filter Tabs (horizontal scroll on mobile):**
All | Grains | Vegetables | Fruits | Dairy | Legumes

**Grid: 3 columns desktop, 2 tablet, 1 mobile**

Each commodity card:
```
┌────────────────────┐
│   🌽               │
│   Maize            │
│   Grains • per Bag │
│                    │
│   142 listings     │
│   KES 45.50 avg    │
│                    │
│  [Browse] [Prices] │
└────────────────────┘
```

- Icon: large emoji or category-based SVG (can use emoji)
- Name: `--font-display` italic
- Metadata: small muted text
- Listing count: only show if > 0, otherwise show "Be first to list"
- Average price: from KAMIS if available, from listings otherwise
- Buttons: "Browse Listings" and "View Prices"
- Active/inactive badge if inactive

**Search bar** at top for filtering by name.

---

### 6. Commodity Show (`/commodities/{commodity}`) — `resources/views/commodities/show.blade.php`

**Full detail view for a commodity.**

**Hero:** Large commodity name with category badge. Description if available.

**Two columns:**

Left (40%): Metadata card
- Category, Unit, Active status
- Total listings count
- Average price (from KAMIS or listings)
- Follow button with preferences form (inline expand on click)
- Quick links: Browse Listings, View Price Insights

Right (60%): Mini price chart (last 4 weeks) pulled from insights.
If no data: "No price data available yet" empty state with link to insights.

**Recent Listings:**
Latest 6 listings for this commodity. Standard listing card grid.
"View all listings →" link.

---

### 7. Listings Index (`/listings`) — `resources/views/listings/index.blade.php`

**The marketplace. Dense, functional, filterable.**

**Filter Sidebar (desktop left, mobile drawer):**

```
FILTERS
──────────────────
🔍 Search
[________________]

Commodity
[Dropdown ▼]

Category
○ All
○ Grains
○ Vegetables
○ Fruits
○ Dairy

County
[Dropdown — 47 counties ▼]

Price Range
Min [______] Max [______]

Sort By
● Newest
○ Price: Low to High
○ Price: High to Low

[Apply Filters]
[Clear All]
```

**Main Content:**
Header shows result count: "142 listings found"

**Listing Cards Grid (2 columns desktop, 1 mobile):**

```
┌─────────────────────────────────────────┐
│ [Image]    🌽 Maize                      │
│            90kg bags of dry maize        │
│                                          │
│            KES 45.50 / bag               │
│                                          │
│            📍 Kawangware, Nairobi        │
│            👤 John Kamau                 │
│            📅 2 days ago                 │
│                                          │
│            500 bags available            │
│            [View Listing]                │
└─────────────────────────────────────────┘
```

Image: full width top, 200px height, object-cover. If no image, show
commodity emoji centered on `--color-primary-muted` background.

Price: `--font-mono`, large, `--color-soil`.
Location: small with pin emoji.
Available quantity: pill badge in `--color-secondary-muted`.

**Pagination:** Bottom, styled with `--color-primary` active page.

---

### 8. Listing Show (`/listings/{listing}`) — `resources/views/listings/show.blade.php`

**The single listing detail page. Primary goal: get buyer to contact seller.**

**Layout: 2 column**

Left (65%): Listing details
Right (35%): Sticky contact card

**Left column:**

Image gallery (if multiple images — carousel). Large main image, thumbnails below.
If no images: large commodity emoji on warm background.

Listing title as `--font-display` h1.
Status badge (Active/Inactive).
Posted date and "by [seller name]".

**Specs table:**
```
Commodity    Maize
Category     Grains
Quantity     500 bags
Unit         90kg bag
Price        KES 45.50 / bag
Total Value  KES 22,750
County       Nairobi
Location     Kawangware Market
```

Description block (if provided).

KAMIS price comparison:
```
ℹ️  KAMIS Market Reference
Average market price for Maize in Nairobi: KES 47.20/bag
This listing is 3.6% below market average.
```
Green if below market, red if above, neutral if within 5%.

**Right column (sticky):**

```
┌─────────────────────────┐
│   Contact Seller         │
│                          │
│   👤 John Kamau          │
│   📍 Nairobi County      │
│   Member since Jan 2026  │
│                          │
│   📞 0712 345 678        │
│   (tap to call on mobile)│
│                          │
│   KES 45.50 / bag        │
│   500 bags available     │
│                          │
│   [📞 Call Seller]       │
│   [💬 WhatsApp]          │
└─────────────────────────┘
```

Phone number shown only to logged-in users.
If not logged in: "Login to see contact details" CTA.
WhatsApp link: `https://wa.me/254XXXXXXXXX`

Related listings (same commodity, different seller) at bottom.

---

### 9. Create Listing (`/listings/create`) — `resources/views/listings/create.blade.php`

**Clean, step-by-step form with warm design.**

Single page form (not multi-step for simplicity).

**Form sections with visual separators:**

**1. What are you selling?**
- Commodity dropdown (searchable, shows emoji + name)
- Title text input
- Description textarea (optional)

**2. Pricing & Quantity**
- Price per unit (with unit label auto-populated from commodity)
- Quantity available
- Show KAMIS reference price inline:
  > "Current market average for Maize: KES 45.50/bag"
  > This helps you price competitively.

**3. Location**
- County dropdown (47 counties)
- Specific location/market (text input, optional)

**4. Photos** (optional but encouraged)
- Drag and drop zone with preview
- "Listings with photos get 3× more views" encouragement text
- Max 5 photos indicator

**Preview panel** (right column on desktop):
Live preview of how the listing card will look as user fills in the form.
Updates in real time using Alpine.js (available in Sail).

Submit button: large, full-width on mobile. "Post Listing →"

---

### 10. My Listings (`/my-listings`) — `resources/views/listings/my-listings.blade.php`

**Personal listing management.**

**Stats row:**
```
Active: 3    Inactive: 1    Total Views: 142    Total Listings: 4
```

**Listing management table:**
```
┌──────────────────┬──────────┬──────────┬────────────┬──────────────────┐
│ Listing          │ Price    │ Qty      │ Status     │ Actions          │
├──────────────────┼──────────┼──────────┼────────────┼──────────────────┤
│ Dry Maize 90kg   │ KES 45   │ 500 bags │ ● Active   │ [Edit] [Toggle]  │
│ Fresh Tomatoes   │ KES 80   │ 200 kg   │ ○ Inactive │ [Edit] [Toggle]  │
└──────────────────┴──────────┴──────────┴────────────┴──────────────────┘
```

Status toggle: inline AJAX, no page reload. Show spinner on toggle.
"Post New Listing +" button top right.
Empty state: "You haven't posted any listings yet. [Post Your First Listing →]"

---

### 11. Notifications (`/notifications`) — `resources/views/notifications/index.blade.php`

**Notification feed with type filtering.**

**Filter tabs:**
All | Price Drops 📉 | Price Spikes 📈 | New Listings 🌾 | Weekly Reports 📊

**Notification items:**

Each notification is a card with:
- Left colored border by type (terracotta=price drop, green=price spike, gold=new listing, indigo=weekly)
- Icon + message + time ago
- Unread notifications have `--color-parchment` background with slight left border
- Read notifications are muted

```
┌─ 📉 ──────────────────────────────────────────── 2h ago ─┐
│  Maize price dropped 15% in Nairobi                       │
│  Was KES 52.00 → Now KES 44.20 per bag                   │
│  [View Listings]                                    [✕]   │
└───────────────────────────────────────────────────────────┘
```

"Mark all as read" and "Clear all" actions top right.
Empty state: 🔔 "No notifications yet. Follow commodities to get alerts."

---

### 12. My Follows (`/my-follows`) — `resources/views/notifications/follows.blade.php`

**Manage notification subscriptions per commodity.**

Header: "Your Market Alerts"
Subtext: "You'll be notified when prices change or new listings are posted."

**Follows list as cards (not a table — cards are warmer):**

Each card:
```
┌──────────────────────────────────────────────────────┐
│  🌽 Maize                          [Edit] [Unfollow] │
│                                                       │
│  Notifications: 📉 Price Drops  📈 Spikes  🌾 Listings│
│  Channels: 📱 In-app  📧 Email                        │
│  Threshold: Changes > 10%                             │
└──────────────────────────────────────────────────────┘
```

"Follow More Commodities →" button at bottom links to `/commodities`.
Empty state: "🌱 Not following any commodities. [Browse Commodities →]"

---

### 13. Auth Pages (`/login`, `/register`, `/forgot-password`)

**Warm, welcoming — not sterile corporate.**

**Layout:** Centered card on a background with a subtle KAMIS price data
collage effect (blurred/faded price data text pattern, or a photo of a
Kenyan market with a warm overlay).

**Login card:**
```
┌──────────────────────────────┐
│   cFarm                       │
│                               │
│   Welcome back                │
│   Sign in to your account     │
│                               │
│   Email                       │
│   [___________________]       │
│                               │
│   Password           [Forgot?]│
│   [___________________]       │
│                               │
│   [Sign In →]                 │
│                               │
│   Don't have an account?      │
│   [Register Free]             │
└──────────────────────────────┘
```

**Register card:**
Same warm style. Fields: Name, Email, Phone (Kenyan format +254),
County (dropdown), Password, Confirm Password.
Show county as grouped by region for easier selection.

---

### 14. Profile (`/profile`) — `resources/views/profile/edit.blade.php`

3-section tabbed layout:
1. **Profile Information** — Name, Email, Phone, County
2. **Password** — Current, New, Confirm
3. **Danger Zone** — Delete account (red section at bottom with confirmation)

---

### 15. Admin Layout — `resources/views/layouts/admin.blade.php`

Dark sidebar layout. The admin area should feel like a control room —
not the same warm earthy vibe of the main app.

```
┌──────────┬──────────────────────────────────────────┐
│          │  Header: page title + Admin Mode badge    │
│  cFarm   ├──────────────────────────────────────────┤
│  Admin   │                                           │
│  ──────  │           Main Content                    │
│ 📊 Dash  │                                           │
│ 👥 Users │                                           │
│ 📋 Lists │                                           │
│ 🌽 Comms │                                           │
│ 🤖 KAMIS │                                           │
│ 🔔 Alerts│                                           │
│          │                                           │
│ ← App    │                                           │
└──────────┴──────────────────────────────────────────┘
```

Sidebar: `#111827` (near black). Active item: `--color-primary` background.
Content area: `#F9FAFB`. Header: white with subtle shadow.

---

## Implementation Notes

### Tailwind Configuration

Add these to `tailwind.config.js`:

```js
theme: {
  extend: {
    fontFamily: {
      display: ['Playfair Display', 'Georgia', 'serif'],
      body:    ['DM Sans', 'sans-serif'],
      mono:    ['DM Mono', 'monospace'],
    },
    colors: {
      primary:   { DEFAULT: '#C1440E', dark: '#8B3008', light: '#E8693A', muted: '#F4DDD3' },
      secondary: { DEFAULT: '#2D5016', dark: '#1A2F0D', light: '#4A7A28', muted: '#D6E8C4' },
      accent:    { DEFAULT: '#D4A017', light: '#F5E6A3' },
      soil:      '#3D2B1F',
      bark:      '#6B4C35',
      sand:      '#F5EFE6',
      parchment: '#FBF7F2',
      stone:     '#C4B5A5',
      mist:      '#EAE4DC',
    },
  },
},
```

### Google Fonts

Add to `resources/views/layouts/app.blade.php` in `<head>`:

```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
```

### Alpine.js Usage

Sail includes Alpine.js. Use it for:
- Filter sidebar open/close on mobile
- Listing form live preview
- Notification dropdown
- Follow preferences inline expand
- Status toggle on my-listings (with `fetch()` to PATCH endpoint)

### Chart.js Usage

For the price trend chart on `/insights/{commodity}`:

```html
<canvas id="priceChart"></canvas>
<script>
const ctx = document.getElementById('priceChart');
new Chart(ctx, {
  type: 'line',
  data: {
    labels: @json($weekly_trend->pluck('week')),
    datasets: [{
      data: @json($weekly_trend->pluck('average_price')),
      borderColor: '#C1440E',
      backgroundColor: 'rgba(193,68,14,0.08)',
      tension: 0.3,
      fill: true,
    }]
  },
  options: {
    plugins: { legend: { display: false } },
    scales: {
      y: { ticks: { callback: v => 'KES ' + v } }
    }
  }
});
</script>
```

Add Chart.js via CDN in the view or include via npm.

### Responsive Breakpoints

- Mobile: < 640px — single column, mobile-first
- Tablet: 640px–1024px — 2 columns where applicable
- Desktop: > 1024px — full layout as specified

### Loading States

Every form submission and AJAX call needs a loading state:
- Buttons: disable + show spinner (CSS border-spinning div)
- Tables/lists: skeleton loading rows in `--color-mist`
- Charts: placeholder gray box while data loads

---

## File Checklist

Pages to create/update:

```
resources/views/
├── home.blade.php                          ← Landing page
├── dashboard.blade.php                     ← User dashboard
├── layouts/
│   ├── app.blade.php                       ← Main layout (add fonts)
│   ├── navigation.blade.php                ← Nav (add links + bell)
│   └── admin.blade.php                     ← Admin dark layout
├── commodities/
│   ├── index.blade.php                     ← Commodity grid
│   └── show.blade.php                      ← Commodity detail
├── listings/
│   ├── index.blade.php                     ← Marketplace
│   ├── show.blade.php                      ← Listing detail
│   ├── create.blade.php                    ← Post listing form
│   └── my-listings.blade.php              ← My listings management
├── insights/
│   ├── index.blade.php                     ← Market insights hub
│   └── show.blade.php                      ← Commodity price detail
├── notifications/
│   ├── index.blade.php                     ← Notification feed
│   └── follows.blade.php                  ← Manage follows
├── profile/
│   └── edit.blade.php                      ← Profile settings
└── admin/
    ├── dashboard.blade.php                 ← Admin overview
    ├── scraper.blade.php                   ← KAMIS scraper control
    ├── notifications.blade.php             ← Alerts log
    ├── users/
    │   ├── index.blade.php                 ← Users list
    │   └── show.blade.php                  ← User detail
    ├── listings/
    │   └── index.blade.php                 ← Listings moderation
    └── commodities/
        ├── index.blade.php                 ← Commodities management
        └── create.blade.php               ← Add commodity form
```

---

## Routes Reference

```
GET  /                          home
GET  /dashboard                 dashboard (auth)
GET  /commodities               commodities.index
GET  /commodities/{commodity}   commodities.show
GET  /listings                  listings.index
GET  /listings/create           listings.create
POST /listings                  listings.store
GET  /listings/{listing}        listings.show
GET  /my-listings               listings.mine
GET  /insights                  insights.index
GET  /insights/{commodity}      insights.show
GET  /notifications             notifications.index
GET  /my-follows                follows.index
GET  /admin                     admin.dashboard
GET  /admin/users               admin.users
GET  /admin/scraper             admin.scraper
GET  /admin/notifications       admin.notifications
```

---

## Final Notes for AI Implementation

- All Blade views use the `<x-app-layout>` component wrapper
- Admin views use `<x-admin-layout>` with `header="Page Title"`
- All forms need `@csrf` tokens
- All prices use `number_format($price, 2)` — never raw floats
- All dates use `$date->diffForHumans()` for relative, `format('M d, Y')` for absolute
- Empty states are mandatory on every list/grid — never show a blank page
- Every page must work on mobile — test at 375px width
- Dark mode support is optional but preferred where easy (Tailwind `dark:` classes)
- The grain texture background effect should be on `<body>` globally
- Chart.js is available via CDN: `https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js`
