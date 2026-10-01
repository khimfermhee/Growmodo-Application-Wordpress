# Estatein WordPress Theme – Development Documentation

**Candidate:** Khim Fermhee Ronquillo · **Position:** WordPress Developer (Growmodo)
**Design:** "Real Estate Business Website UI Template – Dark Theme" (Figma Community) · 6 pages × 3 breakpoints
**Repository:** https://github.com/khimfermhee/Growmodo-Application-Wordpress

## 1. Development process

1. **Design analysis.** Exported every frame (1920, 1440 and 390 px) and inventoried sections, repeated components, typography (Urbanist), colours (#141414 / #1A1A1A / #262626 / #999 / #703BF7) and spacing. Repeated UI became reusable components: section heading, card carousel, property / testimonial / FAQ / client cards, stats, quick-link cards, inquiry form and the CTA band.
2. **Content model first.** Decided what the client edits and where *before* writing markup (see §3), so every visible string comes from the dashboard.
3. **Build.** Custom classic theme: PHP templates + template parts, one hand-written mobile-first stylesheet using design tokens, one dependency-free script.
4. **Verify.** Ran WordPress locally, imported the demo content and compared full-page screenshots of every page side by side with the Figma exports at 1440 px and 390 px, iterating until layouts matched. Automated checks: PHP lint, zero PHP notices with `WP_DEBUG` on, axe-core accessibility audit (0 violations on all pages), browser tests for forms (AJAX and no-JS), carousels, search filters, gallery, office filter, mobile menu and FAQ deep links, and a horizontal-overflow check at 390 px.

## 2. Theme architecture & key decisions

- **Custom classic theme, no page builder.** Full control over markup, performance and accessibility; templates are small and readable (`functions.php` only includes the `/inc` modules).
- **Single source of truth for design copy** (`inc/defaults.php`). Templates fall back to it when a field is empty, so the site never looks broken – even before the field plugin is installed – and the demo importer writes the same data into real posts and fields.
- **Data layer separated from markup** (`inc/queries.php`). Posts are mapped to plain arrays that the card components consume; the same card renders a real listing or a default.
- **Reusable components.** One `carousel-section` part powers Featured Properties, Testimonials, FAQs and Clients; one `inquiry-form` part renders three different forms from a schema shared with the server-side validator, so the two can never drift apart.
- **Carousels use native CSS scroll-snap**; JS only adds paging buttons and the "01 of 10" counter. Swipe works natively, and off-screen slides are made `inert` so keyboard users don't tab into hidden cards.
- **Breakpoints mirror the Figma frames:** base (390), 768, 1200 (1440 design) and 1800 (1920 design); the 1920 layout scales type and spacing via custom properties.

## 3. Content management (CPTs + custom fields)

| Content | Implementation |
| --- | --- |
| Properties | CPT `property` + taxonomies *Property Type* and *Location*; fields: price, location label, tagline, beds, baths, area, build year, featured, gallery, features (repeater), pricing breakdown (nested repeater) |
| Testimonials, FAQs, Team, Clients, Offices | One CPT each with small field groups and drag-and-drop order |
| Services | CPT `service` + taxonomy *Service Sections* whose term fields hold the intro, layout (grid / split) and CTA |
| Page copy | Field groups per page template (tabs per section) |
| Global settings | *Theme Settings* options page: announcement bar, contact details, socials, statistics, quick-link cards, CTA band, footer, form recipient |
| Submissions | Private CPT *Inquiries* with a read-only detail view |

Field groups are registered in PHP (`acf_add_local_field_group`), so they are version controlled and appear automatically – no JSON import step.

## 4. Plugins & tools

- **Secure Custom Fields** (free, maintained by WordPress.org, ACF-compatible API) – chosen over free ACF because it includes repeaters, galleries and options pages at no cost; the theme works the same with ACF Pro. It is the only plugin required.
- **No form plugin.** Forms are handled by the theme: nonce + honeypot + minimum fill time against spam, server-side validation, AJAX with a classic-POST fallback, email notification **and** storage under *Inquiries* (free hosts often block outgoing mail, so no lead is lost).
- **No SEO plugin required.** Built-in meta descriptions, Open Graph/Twitter tags and JSON-LD (RealEstateAgent, RealEstateListing, FAQPage); it switches off automatically if Yoast, Rank Math or SEOPress is installed.
- **Build tooling:** esbuild (CSS/JS minification). **AI:** Claude was used to speed up scaffolding and boilerplate; all code was reviewed, tested in a real WordPress install and refined against the design.

## 5. Performance, accessibility & SEO

- One CSS file (≈10 KB gzipped) and one deferred JS file (≈3 KB gzipped), no jQuery; core block CSS and emoji scripts removed on templated pages.
- Self-hosted variable Urbanist font (one 28 KB woff2, preloaded, `font-display: swap`); hero image preloaded with `fetchpriority="high"`.
- WebP UI assets, responsive `srcset`/`sizes` via custom image sizes, native lazy loading below the fold.
- Accessibility: semantic landmarks and heading order, skip link, visible focus styles, labelled forms with inline, announced errors, ARIA-labelled carousels and icon buttons, keyboard-operable menu (Esc closes), `prefers-reduced-motion` respected, contrast-checked placeholders; axe-core reports no violations.
- SEO: title tag support, unique meta descriptions per page, alt text on all images, structured data, clean permalinks.

## 6. Small enhancements beyond the design

Working property search with five filters (shareable URLs) · rotating "Discover Your Dream Property" badge · sticky translucent header · dismissible announcement bar that is remembered · office filter (All / Regional / International) · FAQ page with an accessible accordion that opens the answer linked from a card · thumbnail-synced property gallery · subtle scroll-reveal animations · branded login screen · one-click demo importer · admin columns (thumbnail, price, featured) for listings.
