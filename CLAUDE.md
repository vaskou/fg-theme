# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Build Commands

```bash
npm start          # Full build + watch with BrowserSync (default dev workflow)
npm run build      # Full production build (clean, styles, scripts)
npm run styles     # Compile SCSS only
npm run scripts    # Bundle JS only
npm run watch      # Watch with BrowserSync (proxy: https://dev.fremeditiguitars.com/)
```

BrowserSync is configured in `gulp.config.js` to proxy `https://dev.fremeditiguitars.com/`.

**PHP linting** (WordPress Coding Standards via PHPCS):
```bash
vendor/bin/phpcs
```
Config is in `phpcs.xml.dist`. No JS linting configured. No test suite.

## Asset Pipeline

- **SCSS** source: `src/sass/style.scss` → compiled to `style.css` and `style.min.css` at theme root
- **JS** source: `src/js/scripts.js` → bundled (UIKit + js-cookie + custom) to `assets/js/scripts.js` and `scripts.min.js`
- Build tool: **Gulp 5** (`gulpfile.js`, `gulp.config.js`)
- CSS framework: **UIKit 3.19.4** (included via npm, bundled into output JS/CSS)

## Architecture

This is a custom WordPress theme (v1.3.1) based on Underscores (_s). All theme logic lives in `inc/` as 16 singleton PHP classes, loaded via `functions.php`.

### PHP Class Structure (`inc/`)

All classes follow the singleton pattern (`::instance()`), instantiated from `Fremediti_Guitars_Theme`.

Key classes:
- `Fremediti_Guitars_Theme` — main bootstrap class, wires everything together
- `Fremediti_Guitars_FG_Guitars` — bridges theme to the external `FG_Guitars_*` plugin (checked via `class_exists()`); renders guitar slideshows, specs, features, sounds, pricing, reviews
- `Fremediti_Guitars_FG_Pickups` — renders pickup post type with lightbox thumbnails and specs
- `Fremediti_Guitars_Woocommerce` — WooCommerce customizations: removes sidebar/upsell/related hooks, parses product specs from editor HTML
- `Fremediti_Guitars_Settings` — admin settings page (Google Tag Manager, language redirect, contact form IDs, role-based feature flags); extends `WordpressCustomSettings\SettingsSetup` (Composer: `vaskou/wordpress-custom-settings`)
- `Fremediti_Guitars_Helpers` — role-based feature flag checks (`show_new_layout()`, `show_new_images()`, `show_new_some_versions_section()`)
- `Fremediti_Guitars_Multilanguage` — WPML integration; redirects Greek IPs to Greek language using a cookie to prevent repeat redirects
- `Fremediti_Guitars_Metaboxes` — per-page options (hide title, full width, sidebar toggle) via CMB2

### Custom Post Types & Taxonomies

Registered in dedicated class files:
- `fg_available_guitars` — non-public; availability/pricing data with CMB2 metaboxes
- `fg_gallery` — non-public; image galleries with shortcode
- `fg_videos` — custom video posts
- `fg_guitars_cat` — taxonomy for guitar categorization (has template: `taxonomy-fg_guitars_cat.php`)

### WooCommerce Specs Parsing

Product specifications are **not stored in custom fields**. They are written in the post content editor as `<h4>Section Name</h4>` followed by `<ul>` with `<li>Body|Mahogany</li>` pipe-separated values. `Fremediti_Guitars_Woocommerce::show_description()` parses this HTML via `DOMDocument` and renders it as a UIKit 3-column grid with class `fg-specifications`.

### Feature Flags

The Settings page (Themes menu in WP Admin) stores role-based flags that control which visitors see new layouts vs. old. `Fremediti_Guitars_Helpers` methods gate rendering of new single-page layouts, new image presentations, and the "Some Versions" section.

### Theme Hooks

Custom hooks added by this theme:
- `fremediti_guitars_has_sidebar` — filter
- `fremediti_guitars_page_before/after`, `fremediti_guitars_single_before/after`, `fremediti_guitars_archive_before/after` — actions
- `fremediti_guitars_custom_post_type_thumbnail` — filter
- `fremediti_guitars_custom_post_type_after_content/after_content_row` — actions

### Dependencies

- **Composer**: `vaskou/wordpress-custom-settings ^2.0` (admin settings framework)
- **npm**: Gulp 5, UIKit 3.19.4, js-cookie, Dart Sass, BrowserSync
- **External plugin**: `FG_Guitars_*` classes (optional; theme degrades gracefully when absent)
- **WPML**: assumed present for multilanguage features