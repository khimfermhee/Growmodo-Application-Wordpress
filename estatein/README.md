# Estatein – WordPress theme

Custom classic theme built from the *Estatein* dark real-estate UI kit (Figma) for the Growmodo WordPress Developer assessment.

## Requirements

- WordPress 6.3+ (tested up to 6.8), PHP 7.4+
- [Secure Custom Fields](https://wordpress.org/plugins/secure-custom-fields/) (free, by WordPress.org) **or** ACF Pro – for editing page sections. Without it the theme still renders the full design using its built-in default content.

## Install

1. Upload `estatein.zip` under **Appearance → Themes → Add New → Upload** and activate.
2. Install + activate **Secure Custom Fields**.
3. Go to **Appearance → Estatein Setup → Import demo content**.

The importer creates all pages (with templates), header + footer menus, 6 properties, testimonials, FAQs, team, clients, services, offices, 3 blog posts and their images, and sets the static front page and permalinks. It can be re-run safely (items are matched by slug).

## Where content lives

| What | Where in the dashboard |
| --- | --- |
| Announcement bar, header button, contact details, socials, stats, quick-link cards, CTA band, footer text, form recipient | **Theme Settings** |
| Home / About / Properties / Services / Contact copy | Edit the page → section fields (tabs) |
| Listings (price, location, beds, baths, area, year, gallery, features, pricing breakdown, "featured") | **Properties** (+ Property Types, Locations) |
| Testimonials, FAQs, Team, Clients, Offices | Their own menus (drag order via *Order*) |
| Service cards / service sections (intro, layout, CTA) | **Services** → All Services / Service Sections |
| Form submissions | **Inquiries** (read-only) – also emailed |
| Menus | Appearance → Menus (Footer: top-level items = column headings) |

## File structure

```
estatein/
├── functions.php            # Bootstrap – includes /inc files only
├── style.css                # Theme header
├── header.php / footer.php  # Global header (announcement + nav) and footer (CTA + links)
├── front-page.php           # Home
├── single-property.php      # Property details
├── page-templates/          # About, Properties, Services, Contact templates
├── archive-faq.php          # FAQ accordion  ·  archive-testimonial.php
├── index/archive/search/single/page/404/sidebar/comments.php   # Blog + WP Loop
├── template-parts/
│   ├── components/          # Reusable: section heading, carousel, cards, forms, CTA, stats…
│   ├── header/  home/  properties/  property/  services/  contact/  blog/
├── inc/
│   ├── defaults.php         # Design copy = fallback content + importer source (single source of truth)
│   ├── helpers.php          # Field getters with fallbacks, image/button/link helpers
│   ├── icons.php            # Inline SVG icon set
│   ├── setup.php            # Supports, menus, image sizes, sidebar
│   ├── enqueue.php          # Assets + performance (defer, preload, no emoji/block CSS)
│   ├── post-types.php       # CPTs + taxonomies
│   ├── acf-fields.php       # Field groups + options page (PHP, version controlled)
│   ├── queries.php          # Data mappers, property search query
│   ├── forms.php            # Form schema, validation, storage, email
│   ├── seo.php              # Meta description, Open Graph, JSON-LD
│   ├── admin.php            # Admin columns, inquiry viewer, notices
│   └── demo-import.php      # Appearance → Estatein Setup
├── assets/
│   ├── css/main.css (+ .min) · js/main.js (+ .min) · fonts/ (Urbanist, self-hosted) · images/
└── demo/images/             # Demo photos used by the importer
```

## Development

```bash
npm install
npm run build   # minifies assets/css/main.css and assets/js/main.js
npm run zip     # builds and creates ../estatein.zip
```

Set `define( 'SCRIPT_DEBUG', true );` to load the unminified files.

Coding conventions: WordPress Coding Standards (PHP), escaping on output, prefixed functions (`estatein_`), BEM-style CSS with design tokens as custom properties, mobile-first media queries matching the Figma frames (390 / 768 / 1440 / 1920).
