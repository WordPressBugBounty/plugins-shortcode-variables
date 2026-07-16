# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

"Snippet Shortcodes" (internal prefix `sh_cd` / constant prefix `SH_CD_`) — the free/core WordPress plugin. Lets users define custom `[sv slug="..."]` shortcodes storing reusable HTML/text/JS, plus a library of "premade" (preset) shortcodes for common WP fields.

It has a paid sibling plugin, **Snippet Shortcodes - Premium** (`../snippet-shortcodes-premium`), which adds header/footer auto-insertion, device targeting, multisite, CSV import execution, extra presets, and licensing. This plugin never references the Premium plugin's code directly — the relationship is entirely one-directional via WordPress hooks (see "Premium extension points" below), so it must keep working standalone.

## Tooling

Plain PHP + vanilla JS — no composer.json, no package.json, no build step, no unit test suite in this repo. There is no local test runner to invoke here.

End-to-end tests covering both plugins together live in the Premium plugin's Playwright suite (`../snippet-shortcodes-premium/playwright`) — see that repo's CLAUDE.md.

CI (`.github/workflows/deploy.yml`) only fires on tag push and deploys `readme.txt`/plugin files to the WordPress.org SVN repo. There's no CI lint/test gate on PRs.

### Version bumps

The plugin version is duplicated in three places and must be kept in sync when releasing: `shortcode-variables.php` (header comment `Version:` and `SH_CD_PLUGIN_VERSION` constant) and `readme.txt` (`Stable tag:`). The cache key (`sh_cd_cache_generate_key`) embeds this version, so bumping it implicitly busts all cached shortcode output.

## Architecture

- **Bootstrap** (`shortcode-variables.php`): defines `SH_CD_*` constants, then on `plugins_loaded` includes everything under `includes/`. `SH_CD_GET_PREMIUM_LINK` is explicitly commented as load-bearing — the Premium plugin checks for its existence to detect the core plugin is active, so don't rename/remove it.
- **Data layer** (`includes/db.php`): raw `$wpdb` queries against two tables — the per-site `SH_CD_TABLE` (`{prefix}SH_CD_SHORTCODES`) and, for multisite, the network-wide `SH_CD_TABLE_MULTISITE` (`{base_prefix}SH_CD_SHORTCODES_MULTISITE`, a denormalized copy kept in sync on save/delete). All CRUD funcs are `sh_cd_db_*`.
- **Caching**: `sh_cd_cache_*` in `includes/functions.php` wraps WP transients, keyed by `SH_CD_SHORTCODE . SH_CD_PLUGIN_VERSION . $key`. Shortcode lookups are cache-first (`sh_cd_shortcode_render` in `includes/shortcode.user.php`); saves/deletes explicitly invalidate by slug and by id.
- **Shortcode rendering** (`includes/shortcode.user.php`): the `[sv ...]` tag (plus legacy aliases `shortcode-variables`, `s-var`) resolves in order: preset? → cached DB row → DB row (then cached) → `do_shortcode()` on the stored content → `%%param%%` substitution from shortcode attributes.
- **Presets / "premade shortcodes"**: `includes/class.presets.php` defines the abstract `SV_Preset` base (subclasses implement `unsanitised()`; escaping method is auto-selected from the `_sh_cd_func` arg — URL-ish funcs get `esc_url_raw`, everything else `esc_html`). `includes/shortcode.presets.core.php` does slug→class lookup/dispatch. `includes/shortcode.presets.free.php` holds the free preset classes (`SV_SC_*`).
  - Note: `sh_cd_shortcode_presets_premium_list()` (in `includes/marketing.php`) declares metadata/descriptions for *premium* presets too, but the actual `SV_SC_*` classes for those slugs are only defined in the Premium plugin. This list exists in core purely so upgrade/marketing screens can describe Premium presets without Premium installed — rendering a premium slug without Premium active shows an upgrade prompt instead (`sh_cd_shortcode_presets_render`).
- **Admin UI**: `includes/hooks.php` registers the admin menu, enqueues `assets/js/sh-cd.js` + CSS, and handles `wp_ajax_*` endpoints (`toggle_status`, `delete_shortcode`, `update_shortcode`, `add_shortcode`) — all gated by `check_ajax_referer('sh-cd-security', ...)` and `sh_cd_permission_check()`. `includes/pages/*.php` render each admin screen (list/edit/premade/import/settings/help/upgrade).
- **Marketing/upsell** (`includes/marketing.php`, `includes/shortcode.marketing.php`): admin notices, premium feature comparison tables, license pricing lookups against the Yeken license API.
- **TinyMCE integration** (`includes/tinymce.php` + `assets/js/tinymce.js`): adds a classic-editor button to insert own/premade shortcodes.

## Premium extension points

Everything the Premium plugin needs is exposed via filters/actions rather than a direct dependency — when changing these, check `../snippet-shortcodes-premium` for consumers:

- `sh_cd_is_premium_plugin_activated()` gates purely on `defined('YK_SS_PLUGIN_NAME')`; `sh_cd_is_premium()` additionally requires the `sh-cd-license-is-premium` filter (supplied by Premium) to return true.
- Filters Premium hooks into: `sh-cd-license-is-premium`, `sh-cd-db-default-values`, `sh-cd-post-field-keys`, `sh-cd-db-default-shortcode-before-save`, `sh-cd-db-loaded-shortcode`, `sh-cd-admin-pages`, `sh-cd-filter-hide-shortcode`, `disable-ss-sc-db-value-by-id`.
- Actions Premium hooks into: `sh-cd-admin-menu-upgrade`, `sh-cd-upgrade`, `sh-cd-shortcode-added`, `sh-cd-shortcode-updated`, `sh_cd_multisite_changed`, `sh-cd-global-cache-delete`.
- The `header`, `footer`, `device_type` DB columns and the multisite table exist in core's schema but are only populated/enforced when Premium is active — `sh_cd_is_multisite_enabled()` requires both `is_multisite()` and `sh_cd_is_premium()`.

## Conventions

- Yoda-style boolean comparisons throughout — `true === empty( $x )`, `false === is_admin()` rather than `!empty($x)`/`!is_admin()`. Match this in new code.
- Function names and hook names use the `sh_cd_` / `sh-cd-` prefix consistently, mirroring the `SH_CD_` constant prefix.
- Any function that mutates data checks `is_admin()` and, on the AJAX path, both `check_ajax_referer('sh-cd-security', ...)` and `sh_cd_permission_check()` before touching the database.
