=== Snippet Shortcodes ===
Contributors: aliakro
Tags: custom,shortcode,snippet,variable,library
Requires at least: 6.0
Tested up to: 6.8
Stable tag: 4.2.4
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate link: https://www.paypal.me/yeken

Create a library of custom shortcodes and reusable content, and seamlessly insert them into your posts and pages. 

== Description ==

> Create a library of custom shortcodes and reusable content, and seamlessly insert them into your posts and pages for streamlined, consistent site updates. Plus, get a head start with a built-in selection of ready-made shortcodes to use out of the box.

Looking to reuse the same snippet of text or HTML across your website while only updating it in one spot? [Snippet Shortcodes](https://snippet-shortcodes.yeken.uk/) could be exactly what you need. With this tool, you can generate a library of custom shortcodes and embed them into your WordPress content. Using the familiar WordPress editor, you can insert anything from text and HTML to JavaScript, images, or any other elements supported by your theme or plugins. The biggest benefit? You only need to create the shortcode once, and you can then deploy it site-wide, saving time and ensuring consistency.

*Quick example*

By default, you tend to create content once and then copy and paste it across your site as needed. Later, when updates are required, you have to track down every instance, edit each one individually, and save every page or post - making consistent updates a tedious process. Instead, put a snippet like this into one of our [custom shortcodes](https://snippet-shortcodes.yeken.uk/shortcodes-own.html) and get a shortcode you can paste into your content. To update, all you need to do is edit the [custom shortcode](https://snippet-shortcodes.yeken.uk/shortcodes-own.html) and your entire site will update consistently.

``<a href="https://www.facebook.com/yekenuk" target="_blank" rel="noopener">Our Facebook</a>

to

``[sv slug="facebook-link"]

**Basic Features**

* **[Custom shortcodes](https://snippet-shortcodes.yeken.uk/shortcodes-own.html)** – Create up to 10 custom shortcodes that can be embedded throughout your website.
* **[Parameters](https://snippet-shortcodes.yeken.uk/shortcodes-own.html)** – Enhance your shortcodes by passing parameters into them e.g. [ sv slug="logo" color="blue" ]
* **[Ready made shortcodes](https://snippet-shortcodes.yeken.uk/shortcodes-free.html)**  – A collection of out-of-the-box shortcodes for displaying common WordPress fields such as site title, username, admin email, etc.

**Premium Features**

* **No limits** – create unlimited [custom shortcodes](https://snippet-shortcodes.yeken.uk/shortcodes-own.html).
* **Inline editor** – Ability to edit [custom shortcodes](https://snippet-shortcodes.yeken.uk/shortcodes-own.html) quickly using the inline editor.
* **Duplicator** – Ability to duplicate [custom shortcodes](https://snippet-shortcodes.yeken.uk/shortcodes-own.html) with one button click.
* **Enable /Disable** – Ability to enable or disable [custom shortcodes](https://snippet-shortcodes.yeken.uk/shortcodes-own.html).
* **Multi-site** – use [custom shortcodes](https://snippet-shortcodes.yeken.uk/shortcodes-own.html) throughout your entire multi-site, not just limited to the one child site. 
* **[CSV import](https://snippet-shortcodes.yeken.uk/csv-import.html)** – Bulk import your custom shortcodes.
* **[WooCommerce fields](https://snippet-shortcodes.yeken.uk/shortcodes/sc-woocommerce.html)** –  A ready-made shortcode to display WooCommerce fields.
* **[Fetch values from database](https://snippet-shortcodes.yeken.uk/shortcodes/sc-db-value-by-id.html)** – A ready-made shortcode to fetch a value from any database table.
* An enhanced [collection of out-of-the-box shortcodes](https://snippet-shortcodes.yeken.uk/shortcodes-premium.html) for displaying additional WordPress fields, as well as wrapping around PHP functionality like GET/POST values, number of users, etc.

==Pricing Plans==

We're very transparent on our pricing and usually offer two plans: *yearly* and *lifetime*. For further information, please visit [our upgrade page](https://shop.yeken.uk/product/shortcode-variables/).

== Getting support ==

If you have a question or an issue, please ask on the plugin's [WordPress support page](https://wordpress.org/support/plugin/shortcode-variables/), and we'll be more than happy to help.

== Useful links ==

* [Technical Documentation](https://snippet-shortcodes.yeken.uk/) – Installation and detailed documentation on how to use the plugin.
* [Trial license](https://shop.yeken.uk/get-a-trial-license/) – Get a trial license to try out all the features.
* [Upgrade](https://shop.yeken.uk/product/shortcode-variables/) – purchase a license to receive all of the features.

== Screenshots ==

1. [Custom shortcodes](https://snippet-shortcodes.yeken.uk/shortcodes-own.html) list, with inline editor, duplicate, and delete options. 
2. Add a new [custom shortcode](https://snippet-shortcodes.yeken.uk/shortcodes-own.html).
3. Edit an existing [custom shortcode](https://snippet-shortcodes.yeken.uk/shortcodes-own.html).
4. Embeded the [custom shortcode](https://snippet-shortcodes.yeken.uk/shortcodes-own.html) into a page.
5. A [custom shortcode](https://snippet-shortcodes.yeken.uk/shortcodes-own.html) rendered on a page.
6. Admin page listing the [collection of out-of-the-box shortcodes](https://snippet-shortcodes.yeken.uk/shortcodes-premium.html) available.
7. [CSV import](https://snippet-shortcodes.yeken.uk/csv-import.html) screen.

== Upgrade Notice ==

4.1 -[sv slug="sc-db-value-by-id"], new Premium shortcode for fetching a value from a MySQL table.

== Changelog ==

= 4.2.4 =

* Change: Updated Readme.txt.

= 4.2.3 =

* Improvement: All shortcodes are now editable even if there is no premium license. Previously only the top 10 shortcodes could be edited.
* Improvement: Display update messages from Yeken.uk in Admin notices.
* Change: Slugs can no longer be changed if not in Premium.
* Bug fix: Fixed issues where save notifications etc appeared incorrectly within title boxes.

= 4.2.2 =

* Version bump for WP 6.8 compatibility.

= 4.2 =

* New feature: Added Premium shortcode for displaying WooComerce user fields. Read more: https://snippet-shortcodes.yeken.uk/shortcodes/sc-woocommerce
* New feature: Added Premium shortcode for displaying WordPress meta fields. Read more: https://snippet-shortcodes.yeken.uk/shortcodes/sc-user-meta

= 4.1.7 =

* Improvement: Added additional security checks to Ajax handlers.

= 4.1.6 =

* New Feature: Extended [sv slug="sc-db-value-by-id"] shortcode to include the new argument "key-query-string". This allows a key to be read from the matching querystring value. Read more: https://snippet-shortcodes.yeken.uk/shortcodes/sc-db-value-by-id.html

= 4.1.5 =

* Maintenance: Added a nonce to the main admin Add/Edit UI screen. Although the form was on an admin screen and not exposed to the public, it doesn't hurt to add a nonce as well. Thanks Benedictus Jovan (aillesiM).

= 4.1.4 =

* Maintenance: Updated tested with WP 6.5 note.

= 4.1.3 =

* Bug fix: Fixed incorrect reference to ws_ls_to_bool() with sh_cd_to_bool()

= 4.1.2 =

* Improvement: By default, the shortcode [sv slug="sc-db-value-by-id"] shall be disabled unless explicitly enabled in WP Admin.
* Improvement: Added the setting ''"sc-db-value-by-id" shortcode enabled?' to enable the shortcode [sv slug="sc-db-value-by-id"].
* Improvement: Added filter "disable-ss-sc-db-value-by-id" to hard disable the shortcode [sv slug="sc-db-value-by-id"].

= 4.1.1 =

* Improvement: Dropped the database table prefix from the shortcode [sv slug="sc-db-value-by-id"] e.g. instead of specifying "users" table, it would now be "wp_users" (i.e. if a WP table, specify the prefix)

= 4.1 =

* New Feature: New Premium shortcode for fetching a value from a MySQL table, [sv slug="sc-db-value-by-id"]. Read more: https://snippet-shortcodes.yeken.uk/shortcodes/sc-db-value-by-id.html

= 4.0.4 =

* Bug fix: Added extra error handling around no multisite tags.

= 4.0.3 =

* Updated "Tested upto" statement.

= 4.0.2 =

* Tested up to version 6.0.

= 4.0.1 =

* Updated "Tested upto" WP version.

= 4.0 =

* New feature: Bulk import of shortcodes via CSV.
* New feature: Quick Add shortcodes without having to open the editor and wait for page refreshes.
* Improvement: Shortcodes can be deleted via Ajax on the list page. This saves waiting for a page refresh.
* Improvement: Added "loading" animations on relevant UI elements.
* Improvement: General code refactoring.
* Bug fix: Allowed text in JS files to be correctly localised.

= 3.5.4 =

* Updated version WP compatibility statement.

= 3.5.3 =

* Updated version WP compatibility statement.

= 3.5.2 =

* Version bump.

= 3.5.1 =

* Bug fix: Corrected documentation and GitHub issue links.
* Added a little text about emailing in suggestions.

= 3.5 =

* New Feature: New settings page.
* New Feature: Allow authors and editors to view and edit your shortcodes.

= 3.4.1 =

* Bug fix: Fixed issue with saving.

= 3.4 =

* Improvement: Cosmetic tweaks to shortcode list table.
* Bug fix: Fixed an issue where license price wasn't being displayed correctly.
* Change: Updated links to new documentation website: https://snippet-shortcodes.yeken.uk/
* Change: Free users are now limited to 15 shortcodes.
* Change: Tweaked Upgrade URL and default price.

= 3.3.3 =

* Updated "Tested upto" statement within readme.txt.

= 3.3.2 =

* Renamed plugin to Snippet Shortcodes!

= 3.3.1 =

* Updated tested upto version for 5.6

= 3.3 =

* Improvement: Removed "Your shortcode has been saved" confirmation page.

= 3.2.1 =

* Readme tweaks.

= 3.2 =

* New Shortcode: "sc-user-profile-photo" – display the current user's profile photo.

= 3.1.1 =

* Updated compatibility version number.

= 3.1 =

* New Shortcode: "sc-site-current-url" – get the current URL.
* New Shortcode: "sc-site-register-url" – get the URL for the WordPress registration page.
* Improvement: Added localised strings so plugin can now be translated.
* Improvement: Licenses are now checked daily and on each upgrade to ensure they are still valid.
* Bug fix: Missing array element throwing error on shortcode listing page.
* Bug fix: PHP warning being thrown on license page when one hasn't been added.
* Bug fix: Always create multisite database table regardless.

= 3.0.4 =

* Improvement: Fetch license price from YeKen API

= 3.0.3 =

* Bug fix: Removed debug data altogether as causing unintended behaviour.

= 3.0.2 =

* Bug fix: Only display HTML caching comment on pages / posts. Currently rendering on things like AJAX responses and causing unintended behaviour.

= 3.0.1 =

* Bug fix: Fix error being thrown when not a multi site and DB table missing.

= 3.0 =

* Improvement: Support for multi-site variables.
* Improvement: Replace shortcodes within menu titles.

= 2.4.1 =

* Bug fix: fixed save_result undeclared variable error.

= 2.4 =

* New Feature: Added a button to the WordPress text editor (classic mode) to allow users to easily insert shortcodes.
* New Fetaure: Added a new shortcode sc-date. Allows you to do render today's date and add or subtract days, months and years.

= 2.3 =

* Improvement: sc-user-counts – Display a count of all WordPress users or the number of WordPress users for a given role e.g. [sv slug="sc-user-counts" role="subscriber"] or [sv slug="sc-user-counts"].

= 2.2.1 =

* Bug fix: Fixed issue with clone.

= 2.2 =

* Improvement: Slugs can now be edited.

= 2.1 =

* New Feature: Added the ability to clone your own shortcodes.

= 2.0.1 =

* Bug fix: Fixed broken menu

= 2.0 =

* New Feature: Added additional premium shortcodes.
* New Feature: Inline editing of shortcodes from the main list screen.
* New Feature: Able to disable / enable shortcodes from shortcode list.
* Improvement: Refactoring and optimisation of the entire plugin code.
* Improvement: Added Fooicons.
* Improvement: Added simple form validation when adding a record.

= 1.8 =

* Improvement: Added escaping for premade shortcodes.
* Improvement: Added new shortcode "sc-privacy-url" for rendering Privacy URL link.

= 1.7.4 =

– Version and readme.txt updated to reflect 4.8 compatibility.

= 1.7.3 =

– BUG FIX: On the very first load of a variable it would return nothing. This was due to a bug in the code. The first load would display nothing to the user, however it would cache the shortcode correctly. Upon the next visit, the shortcode would render correctly!

= 1.7.2 =

– When creating a new shortcode, "Disabled" is set by default to "No".
– Additional upgrade check added. This compares the previously stored version number against the new version number. If there is a difference, it will run the DB table check again.

= 1.7.1 =

– BUG FIX: Tweak made to "on activate" so the code required to change the relevant database tables is called correctly.

= 1.7 =

– Disable a variable. You can now disable a variable via the admin panel – if a shortcode is disabled nothing will be rendered in it's place (will remove the shortcode though).

= 1.6.1 =

– BUG FIX: Array declaration caused 500 error on non PHP 7

= 1.6 =

– Now supports custom parameters. You can now add parameters when inserting a shortcode and specify where in the shortcode those parameters should appear.
– BUG FIX: Removed a stray var_dump()

= 1.5.1 =

* BUG FIX: "Add new" link for message "You haven't created any shortcodes yet." wasn't working correctly
* BUG FIX: Typo – "Shotcodes" instead of "Shortcodes" on "Your Shortcodes" page

= 1.5 =

* Added a shorter shortcode slug. So, instead of [shortcode-variables slug="your-slug-name"] you can also use [s-var slug="your-slug-name"]
* BUG FIX: Some pre-made shortcodes weren't being rendered in the correct place. Fixed.

= 1.4 =
* Added the new pre-made shortcodes:
 * sc-login-page – Wordpress login page. Add the parameter "redirect" to specify where the user is taken after a successful login e.g. redirect="http://www.google.co.uk".
 * sc-username – Display the logged in username.
 * sc-user-id – Display the current user's ID
 * sc-user-ip – Display the current user's IP address.
 * sc-user-email – Display the current user's email address.
 * sc-username – Display the current user's username.
 * sc-first-name – Display the current user's first name.
 * sc-last-name – Display the current user's last name.
 * sc-display-name – Display the current user's display name.
 * sc-user-agent – Display the current user's user agent
* BUG FIX: Deleting a shortcode from cache when deleted from Admin panel. This stops it getting rendered when removed from the plugin.

= 1.3.1 =

* Added some messages to encourage people to suggest premade tags.
* Added version numbers. These are stored in DB to aid future upgrades.

= 1.3 =

This was a dummy release to fix an SVN issue with the 1.2 release!

= 1.2 =

* Added Premade shortcodes and framework to add additional ones
* Added Top Level menu item to support two sub pages. One for user defined shortcodes and another for premade shortcodes.

= 1.1 =
* Added caching to SQL queries. Therefore making shortcode rendering faster and reduce load on mySQL.
* TinyMCE editor for editing shortcode content.
* You can now specify other shortcodes within your Snippet Shortcodes.
* Readme.txt fixes

= 1.0 =
* Initial Release
