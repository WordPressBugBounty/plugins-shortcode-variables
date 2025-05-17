# Premium helper shortcodes

> The following shortcodes are only available in the [Premium version](https://snippetshortcodes.yeken.uk/) version of the plugin. Please [download and activate this seperate plugin](https://snippetshortcodes.yeken.uk/) on your WordPress site.

Besides the ability to create [your own shortcodes]({{ site.baseurl }}/shortcodes-own.html), Snippet Shortcode has a collection of out the box helper shortcodes. Shortcodes functionality are implemented using [WordPress shortcodes](https://codex.wordpress.org/Shortcode_API).

For additional shortcodes, check out the [Free helper shortcodes]({{ site.baseurl }}/shortcodes-free.html).

|--|--|
|[sv slug="date"] |A shortcode that displays today's date with the ability to add or subtract days, months and years. To specify an interval to add or subtract onto the date use the parameter "interval" e.g. [sv slug="date" interval="-1 year"], [sv slug="date" interval="+5 days"], [sv slug="date" interval="+3 months"]. Intervals are based upon PHP intervals and are outlined here https://www.php.net/manual/en/dateinterval.createfromdatestring.php. Default is UK format (DD/MM/YYYY). Format can be changed by adding the parameter format="m/d/Y" onto the shortcode. Format syntax is based upon PHP date: http://php.net/manual/en/function.date.php
|[[sv slug="db-value-by-id"]]({{ site.baseurl }}/shortcodes/sc-db-value-by-id.html) |Fetch a value from the given MySQL table. Specify which column the value should be fetched from as well as specify which column should be matched against for the given key.
|[sv slug="site-language"]|	Language code for the current site
|[sv slug="site-description"]|	Site tagline (set in Settings > General)
|[sv slug="site-wp-url"]	|The WordPress address (URL) (set in Settings > General)
|[sv slug="site-charset"]|	The "Encoding for pages and feeds" (set in Settings > Reading)
|[sv slug="site-wp-version"]	|The current WordPress version
|[sv slug="site-html-type"]|	The content-type (default: "text/html"). Themes and plugins
|[sv slug="site-stylesheet-url"]|	URL to the stylesheet for the active theme.
|[sv slug="site-stylesheet_directory"]|	Directory path for the active theme.
|[sv slug="site-current-url"]	|Get the current URL.
|[sv slug="site-register-url"]|	Get the URL to the WordPress registration page.
|[sv slug="site-template-url"]	|The URL of the active theme's directory.
|[sv slug="site-pingback-url"]|	The pingback XML-RPC file URL (xmlrpc.php)
|[sv slug="site-atom-feed"]	|The Atom feed URL (/feed/atom)
|[sv slug="site-rdf-url"]	|The RDF/RSS 1.0 feed URL (/feed/rfd)
|[sv slug="site-rss-url"]	|The RSS 0.92 feed URL (/feed/rss)
|[sv slug="site-rss2-url"]|	The RSS 2.0 feed URL (/feed)
|[sv slug="site-comments-atom-url"]	|The comments Atom feed URL (/comments/feed)
|[sv slug="site-comments-rss2-url"]	|The comments RSS 2.0 feed URL (/comments/feed)
|[sv slug="php-server-info"]|	Display data from the PHP $_SERVER global e.g. [sv slug="server-info" field="SERVER_SOFTWARE"]. Allowed values for field attribute.
|[sv slug="php-unique-id"]	|Generate a unique ID. Based upon uniqid(). If you wish the unique ID to be prefixed, add a the prefix attribute e.g. [sv slug="php-unique-id" prefix="yeken"]
|[sv slug="php-timestamp"]	|Display the current unix timestamp. Based upon time().
|[sv slug="php-random-number"]|	Display a random number. Based upon rand(). It also supports the optional arguments of min and max e.g. [sv slug="php-random-number" min="5" max="20" ]
|[sv slug="php-random-string"]	|Display a random string of characters. It also supports the optional argument of "length". This specifies the number of characters you wish to display (default is 10) [sv slug="php-random-string" length="15"]
|[sv slug="php-post-value"]	|Display a value from the $_POST array. The "key" arguments specifies which array value to render. It also supports the optional arguments of "default". If there is no value in the array for the given "key" then the "default" will be displayed. [sv slug="php-post-value" key="username" default="Not Found"]
|[sv slug="php-get-value"]|	Display a value from the $_GET array. The "key" arguments specifies which array value to render. It also supports the optional arguments of "default". If there is no value in the array for the given "key" then the "default" will be displayed. [sv slug="php-get-value" key="username" default="Not Found"]
|[sv slug="php-info"]	|Display PHP Info
|[sv slug="post-id"]|	Display ID for the current post.
|[sv slug="post-author"]|	Display the author's display name or ID. The optional argument "field" allows you to specify whether you wish to display the author's "display-name" or "id". [sv slug="post-author" field="id" ]
|[sv slug="post-counts"]|	Display a count of posts for certain statuses. Using the argument status, specify whether to return a count for all posts that have a status of "publish" (default), "future", "draft", "pending" or "private". [sv slug="post-counts" status="draft"]
|[sv slug="user-counts"]	|Display a count of all WordPress users or the number of WordPress users for a given role e.g. [sv slug="user-counts" role="subscriber"] or [sv slug="user-counts"]
|[[sv slug="user-meta"]]({{ site.baseurl }}/shortcodes/sc-user-meta.html) |Display a WordPress user meta field (wraps get_user_meta) field e.g. last_name. 
|[sv slug="user-profile-photo"]	|Display the WordPress profile photo for the logged in user e.g. [sv slug="user-profile-photo" width="150"] or [sv slug="user-profile-photo"]. Please note, width defaults to 96px.
|[[sv slug="woocommerce"]]({{ site.baseurl }}/shortcodes/sc-woocommerce.html) |Display a WooCommerce user meta field field e.g. billing_phone. 