<?php

defined('ABSPATH') or die('Jog on!');

/**
 * If we think we have a valid license, but the Premium plugin hasn't been installed then prompt the user to.
 */
function sh_cd_prompt_to_install_premium() {

	if ( true === sh_cd_is_premium_plugin_activated() ) {
		return;
	}

	if ( false === (bool) get_option( 'sh-cd-license-valid', false ) ) {
		return;
	}
	
	if ( false === empty( $_GET['dismiss-premium'] ) ) {
		sh_cd_marketing_prompt_been_dismissed( '_yeken_shortcode_variables_install_premium_has_been_dismissed', 1 );
		return;
	}

	if ( false === empty( sh_cd_marketing_prompt_been_dismissed( '_yeken_shortcode_variables_install_premium_has_been_dismissed' ) ) ) {
		return;
	}
	
	$dismiss_url = add_query_arg( [ 'dismiss-premium' => 1 ], sh_cd_get_current_url() );

	printf('<div class="notice notice-warning">
				<p><strong>%1$s</strong>: %2$s</p>
				<p>
					%3$s 
					<a href="%4$s" class="button">%5$s</a>
				</p>				
			</div>',
			esc_html( SH_CD_PLUGIN_NAME ),
			__( 'Shortcode Snippets now consists of two plugins: the one you currently have installed, and an additional plugin called <strong>"Snippet Shortcodes – Premium."</strong> You\'re seeing this message because it appears you may already have a Premium license. If that\'s the case, be sure to download and install the Premium plugin to continue accessing all Premium features.', SH_CD_SLUG ),
			sh_cd_premium_shortcode_download( true ),
			$dismiss_url,
			__( 'Dismiss', SH_CD_SLUG ),
    );
}
add_action( 'admin_notices', 'sh_cd_prompt_to_install_premium' );

/**
 * If we think the premium plugin is installed, but not the latest version then prompt
 */
function sh_cd_prompt_to_upgrade_premium() {

	if ( false === sh_cd_is_premium_plugin_activated() ) {
		return;
	}

	$latest_version = sh_cd_get_latest_premium_version();

	if ( true === empty( $latest_version ) ) {
		return;
	}
	
	if ( $latest_version === YK_SS_PLUGIN_VERSION ) {
		return;
	}

	if ( false === empty( $_GET[ 'dismiss-premium-upgrade' ] ) ) {
		sh_cd_marketing_prompt_been_dismissed( '_yeken_shortcode_variables_upgrade_premium_has_been_dismissed', $_GET[ 'dismiss-premium-upgrade' ] );
		return;
	}

	$version_dismissed = sh_cd_marketing_prompt_been_dismissed( '_yeken_shortcode_variables_upgrade_premium_has_been_dismissed' );

	if ( false !== empty( $version_dimissed ) && $version_dismissed === $latest_version ) {
		return;
	}

	$dismiss_url = add_query_arg( [ 'dismiss-premium-upgrade' => $latest_version ], sh_cd_get_current_url() );

	printf('<div class="notice notice-warning">
				<p><strong>%1$s</strong>: %2$s</p>
				<p>
					<a href="%3$s" class="button">%6$s</a>
					%7$s
					<a href="%4$s" class="button">%5$s</a>
				</p>	
			</div>',
			esc_html( SH_CD_PLUGIN_NAME ),
			__( 'A newer version of <strong>"Snippet Shortcodes – Premium"</strong> is available. Please update it from the Plugins screen to access the latest features and bug fixes.', SH_CD_SLUG ),
			esc_url( admin_url('plugins.php') ),
			$dismiss_url,
			__( 'Dismiss', SH_CD_SLUG ),
			__( 'Update both plugins via plugin screen', SH_CD_SLUG ),
			sh_cd_premium_shortcode_download( true ),
    );
}
add_action( 'admin_notices', 'sh_cd_prompt_to_upgrade_premium' );

/**
 * Save the fact the user has dismissed the prompt to install the premium plugin
 */
function sh_cd_marketing_prompt_been_dismissed( $key, $value = NULL ) {
	
	if ( NULL !== $value ) {
		update_option( $key, $value );
	}
	
	return get_option( $key ) ;
}

/**
* Return a link to the upgrade page
*
* @return string
*/
function sh_cd_license_upgrade_link() {

	$link = admin_url('admin.php?page=sh-cd-shortcode-variables-upgrade');

	return esc_url( $link );
}

/**
 * Return a list of all premium features
 * @return array
 */
function sh_cd_premium_features_list() {

	return [
			[ 'title' => 'Insert into header or footer', 'description' => 'Automaically insert your custom shortcode’s content into either your site’s header and/or footer.', 'read-more-url' => '' ],
			[ 'title' => 'Limit to certain device types', 'description' => 'Specify whether your custom shortcode should only be visible on Mobile, Tablet, Desktop or all three.', 'read-more-url' => '' ],
			[ 'title' => 'No limits', 'description' => 'Create unlimited custom shortcodes.', 'read-more-url' => '' ],
			[ 'title' => 'Inline editor', 'description' => 'Ability to edit custom shortcodes without having to use a full editor.', 'read-more-url' => '' ],
			[ 'title' => 'Duplicator', 'description' => 'Ability to duplicate custom shortcodes.', 'read-more-url' => '' ],
			[ 'title' => 'Enable/Disable', 'description' => 'Ability to enable or disable custom shortcodes.', 'read-more-url' => '' ],
			[ 'title' => 'Multi-site', 'description' => 'Use custom shortcodes throughout your entire multi-site, not just limited to the one child site', 'read-more-url' => '' ],
			[ 'title' => 'CSV import', 'description' => 'Bulk import your custom shortcodes.', 'read-more-url' => 'https://snippet-shortcodes.yeken.uk/csv-import.html' ],
			[ 'title' => 'WooCommerce fields', 'description' => 'A ready-made shortcode to display WooCommerce fields.', 'read-more-url' => 'https://snippet-shortcodes.yeken.uk/shortcodes/sc-woocommerce.html' ],
			[ 'title' => 'Fetch values from database', 'description' => 'A ready-made shortcode to fetch a value from any database table', 'read-more-url' => 'https://snippet-shortcodes.yeken.uk/shortcodes/sc-db-value-by-id.html' ],
			[ 'title' => 'More out-of-the-box shortcodes', 'description' => 'Display additional WordPress fields, as well as wrapping around PHP functionality like GET/POST values, number of users, etc', 'read-more-url' => 'https://snippet-shortcodes.yeken.uk/shortcodes-premium.html' ]

			// [ 'title' => '', 'description' => '', 'read-more-url' => '' ],
	];

}
/**
 * Return a list of slugs / titles for free presets
 * @return array
 */
function sh_cd_shortcode_presets_premium_list() {

	return [
		'db-value-by-id' => [ 'class' => 'SC_DB_VALUE_BY_ID', 'description' => __( 'Return a column value from a MySQL database for the given ID of search criteria e.g. <a href="https://snippet-shortcodes.yeken.uk/shortcodes/sc-db-value-by-id.html" rel="noopener" target="_blank">[sv slug="db-value-by-id" table="users" column="user_login" column-to-search="id" key="3" key-format="%d"]</a>', SH_CD_SLUG ), 'premium' => true,  'args' => [ '_sh_cd_func' => 'by-id' ] ],
		'date' => [ 'class' => 'SC_DATE', 'description' => __( 'A shortcode that displays today\'s date with the ability to add or subtract days, months and years. To specify an interval to add or subtract onto the date use the parameter "interval" e.g. [sv slug="date" interval="-1 year"], [sv slug="date" interval="+5 days"], [sv slug="date" interval="+3 months"]. Intervals are based upon PHP intervals and are outlined here <a href="https://www.php.net/manual/en/dateinterval.createfromdatestring.php" target="_blank">https://www.php.net/manual/en/dateinterval.createfromdatestring.php</a>. Default is UK format (DD/MM/YYYY). Format can be changed by adding the parameter format="m/d/Y" onto the shortcode. Format syntax is based upon PHP date: <a href="http://php.net/manual/en/function.date.php" target="_blank">http://php.net/manual/en/function.date.php</a>', SH_CD_SLUG ), 'premium' => true ],
		'site-language' => [ 'class' => 'SC_BLOG_INFO', 'description' => __( 'Language code for the current site', SH_CD_SLUG ), 'args' => [ '_sh_cd_func' => 'language' ], 'premium' => true ],
		'site-description' => [ 'class' => 'SC_BLOG_INFO', 'description' => __( 'Site tagline (set in Settings > General)', SH_CD_SLUG ), 'args' => [ '_sh_cd_func' => 'description' ], 'premium' => true ],
		'site-wp-url' => [ 'class' => 'SC_BLOG_INFO', 'description' => __( 'The WordPress address (URL) (set in Settings > General)', SH_CD_SLUG ), 'args' => [ '_sh_cd_func' => 'wpurl' ], 'premium' => true ],
		'site-charset' => [ 'class' => 'SC_BLOG_INFO', 'description' => __( 'The "Encoding for pages and feeds"  (set in Settings > Reading)', SH_CD_SLUG ), 'args' => [ '_sh_cd_func' => 'charset' ], 'premium' => true ],
		'site-wp-version' => [ 'class' => 'SC_BLOG_INFO', 'description' => __( 'The current WordPress version', SH_CD_SLUG ), 'args' => [ '_sh_cd_func' => 'version' ], 'premium' => true ],
		'site-html-type' => [ 'class' => 'SC_BLOG_INFO', 'description' => __( 'The content-type (default: "text/html"). Themes and plugins', SH_CD_SLUG ), 'args' => [ '_sh_cd_func' => 'html_type' ], 'premium' => true ],
		'site-stylesheet-url' => [ 'class' => 'SC_BLOG_INFO', 'description' => __( 'URL to the stylesheet for the active theme.', SH_CD_SLUG ), 'args' => [ '_sh_cd_func' => 'stylesheet_url' ], 'premium' => true ],
		'site-stylesheet_directory' => [ 'class' => 'SC_BLOG_INFO', 'description' => __( 'Directory path for the active theme.', SH_CD_SLUG ), 'args' => [ '_sh_cd_func' => 'stylesheet_directory' ], 'premium' => true ],
		'site-current-url' => [ 'class' => 'SC_CURRENT_URL', 'description' => __( 'Get the current URL.', SH_CD_SLUG ), 'args' => [ '_sh_cd_func' => 'current_url' ], 'premium' => true],
		'site-register-url' => [ 'class' => 'SC_REGISTER_URL', 'description' => __( 'Get the URL to the WordPress registration page.', SH_CD_SLUG ), 'args' => [ '_sh_cd_func' => 'registration_url' ], 'premium' => true],
		'site-template-url' => [ 'class' => 'SC_BLOG_INFO', 'description' => __( 'The URL of the active theme\'s directory.', SH_CD_SLUG ), 'args' => [ '_sh_cd_func' => 'template_url' ], 'premium' => true],
		'site-pingback-url' => [ 'class' => 'SC_BLOG_INFO', 'description' => __( 'The pingback XML-RPC file URL (xmlrpc.php)', SH_CD_SLUG ), 'args' => [ '_sh_cd_func' => 'pingback_url' ], 'premium' => true ],
		'site-atom-feed' => [ 'class' => 'SC_BLOG_INFO', 'description' => __( 'The Atom feed URL (/feed/atom)', SH_CD_SLUG ), 'args' => [ '_sh_cd_func' => 'atom_url' ], 'premium' => true ],
		'site-rdf-url' => [ 'class' => 'SC_BLOG_INFO', 'description' => __( 'The RDF/RSS 1.0 feed URL (/feed/rfd)', SH_CD_SLUG ), 'args' => [ '_sh_cd_func' => 'rdf_url' ], 'premium' => true ],
		'site-rss-url' => [ 'class' => 'SC_BLOG_INFO', 'description' => __( 'The RSS 0.92 feed URL (/feed/rss)', SH_CD_SLUG ), 'args' => [ '_sh_cd_func' => 'rss_url' ], 'premium' => true ],
		'site-rss2-url' => [ 'class' => 'SC_BLOG_INFO', 'description' => __( 'The RSS 2.0 feed URL (/feed)', SH_CD_SLUG ), 'args' => [ '_sh_cd_func' => 'rss2_url' ], 'premium' => true ],
		'site-comments-atom-url' => [ 'class' => 'SC_BLOG_INFO', 'description' => __( 'The comments Atom feed URL (/comments/feed)', SH_CD_SLUG ), 'args' => [ '_sh_cd_func' => 'comments_atom_url' ], 'premium' => true ],
		'site-comments-rss2-url' => [ 'class' => 'SC_BLOG_INFO', 'description' => __( 'The comments RSS 2.0 feed URL (/comments/feed)', SH_CD_SLUG ), 'args' => [ '_sh_cd_func' => 'comments_rss2_url' ], 'premium' => true ],
		'php-server-info' => [ 'class' => 'SC_SERVER_INFO', 'description' => __( 'Display data from the PHP $_SERVER global e.g. [sv slug="server-info" field="SERVER_SOFTWARE"]. <a href="http://php.net/manual/en/reserved.variables.server.php" rel="noopener" target="_blank">Allowed values for field attribute</a>.', SH_CD_SLUG ), 'premium' => true ],
		'php-unique-id' => [ 'class' => 'SC_UNIQUE_ID', 'description' => __( 'Generate a unique ID. Based upon <a href="http://php.net/manual/en/function.uniqid.php" rel="noopener" target="_blank">uniqid()</a>. If you wish the unique ID to be prefixed, add a the prefix attribute e.g. [sv slug="php-unique-id" prefix="yeken"]', SH_CD_SLUG ), 'premium' => true ],
		'php-timestamp' => [ 'class' => 'SC_TIMESTAMP', 'description' => __( 'Display the current unix timestamp. Based upon <a href="http://php.net/manual/en/function.time.php" rel="noopener" target="_blank">time()</a>.', SH_CD_SLUG ), 'premium' => true ],
		'php-random-number' => [ 'class' => 'SC_RAND_NUMBER', 'description' => __( 'Display a random number. Based upon <a href="http://php.net/manual/en/function.rand.php" rel="noopener" target="_blank">rand()</a>. It also supports the optional arguments of min and max e.g. [sv slug="php-random-number" min="5" max="20" ]', SH_CD_SLUG ), 'premium' => true ],
		'php-random-string' => [ 'class' => 'SC_RAND_STRING', 'description' => __( 'Display a random string of characters. It also supports the optional argument of "length". This specifies the number of characters you wish to display (default is 10) [sv slug="php-random-string" length="15"]', SH_CD_SLUG ), 'premium' => true ],
		'php-post-value' => [ 'class' => 'SC_POST_VALUE', 'description' => __( 'Display a value from the $_POST array. The "key" arguments specifies which array value to render. It also supports the optional arguments of "default". If there is no value in the array for the given "key" then the "default" will be displayed. [sv slug="php-post-value" key="username" default="Not Found"]', SH_CD_SLUG ), 'premium' => true ],
		'php-get-value' => [ 'class' => 'SC_GET_VALUE', 'description' => __( 'Display a value from the $_GET array. The "key" arguments specifies which array value to render. It also supports the optional arguments of "default". If there is no value in the array for the given "key" then the "default" will be displayed. [sv slug="php-get-value" key="username" default="Not Found"]', SH_CD_SLUG ), 'premium' => true ],
		'php-info' => [ 'class' => 'SC_PHP_INFO', 'description' => __( 'Display PHP Info', SH_CD_SLUG ), 'premium' => true ],
		'post-id' => [ 'class' => 'SC_POST_ID', 'description' => __( 'Display ID for the current post.', SH_CD_SLUG ), 'premium' => true ],
		'post-author' => [ 'class' => 'SC_POST_AUTHOR', 'description' => __( 'Display the author\'s display name or ID. The optional argument "field" allows you to specify whether you wish to display the author\'s "display-name" or "id". [sv slug="post-author" field="id" ]', SH_CD_SLUG ), 'premium' => true ],
		'post-counts' => [ 'class' => 'SC_POST_COUNTS', 'description' => __( 'Display a count of posts for certain statuses. Using the argument status, specify whether to return a count for all posts that have a status of "publish" (default), "future", "draft", "pending" or "private". [sv slug="post-counts" status="draft"]', SH_CD_SLUG ), 'premium' => true ],
        'user-counts' => [ 'class' => 'SC_USER_COUNTS', 'description' => __( 'Display a count of all WordPress users or the number of WordPress users for a given role e.g. [sv slug="user-counts" role="subscriber"] or [sv slug="user-counts"]', SH_CD_SLUG ), 'premium' => true ],
		'user-profile-photo' => [ 'class' => 'SC_AVATAR', 'description' => __( 'Display the WordPress profile photo for the logged in user e.g. [sv slug="user-profile-photo" width="150"] or [sv slug="user-profile-photo"]. Please note, width defaults to 96px.', SH_CD_SLUG ), 'premium' => true ],
		'user-meta' => [ 'class' => 'SC_USER_META', 'description' => __( 'Display a WordPress user meta field (wraps get_user_meta) field e.g. last_name. Read more: <a href="https://snippet-shortcodes.yeken.uk/shortcodes/sc-user-meta" rel="noopener" target="_blank">[sv slug="user-meta" field="last_name"]</a>', SH_CD_SLUG ), 'premium' => true ],
		'woocommerce' => [ 'class' => 'SC_USER_META', 'description' => __( 'Display a Woocommerce user profile field e.g. billing_phone. Read more: <a href="https://snippet-shortcodes.yeken.uk/shortcodes/sc-woocommerce" rel="noopener" target="_blank">[sv slug="woocommerce" field="billing_phone"]</a>', SH_CD_SLUG ), 'premium' => true ]

		// '' => [ 'class' => '', 'description' => '', 'premium' => true ]
	];
}

/**
 * Return a list of slugs / titles for free presets
 * @return array
 */
function sh_cd_shortcode_presets_free_list() {

	return [
		'todays-date' => [ 'class' => 'SC_TODAYS_DATE', 'description' => __( 'Displays today\'s date. Default is UK format (DD/MM/YYYY). Format can be changed by adding the parameter format="m/d/Y" onto the shortcode. Format syntax is based upon PHP date: <a href="http://php.net/manual/en/function.date.php" target="_blank">http://php.net/manual/en/function.date.php</a>', SH_CD_SLUG ) ],
		'user-ip' => [ 'class' => 'SC_USER_IP', 'description' => __( 'Display the current user\'s IP address.', SH_CD_SLUG )],
		'user-agent' => [ 'class' => 'SC_USER_AGENT', 'description' => __( 'Display the current user\'s User Agent', SH_CD_SLUG ) ],
		'site-url' => [ 'class' => 'SC_BLOG_INFO', 'description' => __( 'The Site address (URL) (set in Settings > General)', SH_CD_SLUG ), 'args' => [ '_sh_cd_func' => 'url' ] ],
		'site-title' => [ 'class' => 'SC_BLOG_INFO', 'description' => __( 'Displays the site title.', SH_CD_SLUG ) ],
		'admin-email' => [ 'class' => 'SC_BLOG_INFO', 'description' => __( 'Admin email (set in Settings > General)', SH_CD_SLUG ), 'args' => [ '_sh_cd_func' => 'admin_email' ] ],
		'page-title' => [ 'class' => 'SC_PAGE_TITLE', 'description' => __( 'Displays the page title.', SH_CD_SLUG ) ],
		'login-page' => [ 'class' => 'SC_LOGIN_PAGE', 'description' => __( 'Wordpress login page. Add the parameter "redirect" to specify where the user is taken after a successful login e.g. redirect="http://www.google.co.uk".', SH_CD_SLUG ) ],
		'privacy-url' => [ 'class' => 'SC_POLICY_URL', 'description' => __( 'Displays the privacy page URL.', SH_CD_SLUG ) ],
		'username' => [ 'class' => 'SC_USER_INFO', 'description' => __( 'Display the logged in username.', SH_CD_SLUG ), 'args' => [ '_sh_cd_func' => 'user_login' ] ],
		'user-id' => [ 'class' => 'SC_USER_INFO', 'description' => __( 'Display the current user\'s ID.', SH_CD_SLUG ), 'args' => [ '_sh_cd_func' => 'ID' ] ],
		'user-email' => [ 'class' => 'SC_USER_INFO', 'description' => __( 'Display the current user\'s email address.', SH_CD_SLUG ), 'args' => [ '_sh_cd_func' => 'user_email' ] ],
		'first-name' => [ 'class' => 'SC_USER_INFO', 'description' => __( 'Display the current user\'s username.', SH_CD_SLUG ), 'args' => [ '_sh_cd_func' => 'user_firstname' ] ],
		'last-name' => [ 'class' => 'SC_USER_INFO', 'description' => __( 'Display the current user\'s last name.', SH_CD_SLUG ), 'args' => [ '_sh_cd_func' => 'user_lastname' ] ],
		'display-name' => [ 'class' => 'SC_USER_INFO', 'description' => __( 'Display the current user\'s display name.', SH_CD_SLUG ), 'args' => [ '_sh_cd_func' => 'display_name' ] ]
	];

	// '' => [ 'class' => '', 'description' => '', 'args' => [ '_sh_cd_func' => 'admin_email' ] ]
}

/**
 * Display a table of premium features
 *
 * @return string
 */
function sh_cd_display_premium( $list = false ) {

	$premium_user = sh_cd_is_premium();
	$upgrade_link = sprintf( '<a class="button sh-cd-upgrade-button" href="%1$s"><i class="fas fa-check"></i> %2$s</a>', sh_cd_license_upgrade_link(), __('Upgrade now', SH_CD_SLUG ) );

	$html = ( $list ) ? '<ul class="sh-cd-promo-list">' :
					 sprintf('<table class="widefat sh-cd-table" width="100%%">
                		<tr class="row-title">
							<th class="row-title" width="30%%">%s</th>
							<th width="*">%s</th>
						</tr>
						', __('Premium Feature', SH_CD_SLUG ),
						__('Description', SH_CD_SLUG )
					);

	$class = '';

	foreach ( sh_cd_premium_features_list() as $feature ) {

		$class = ($class == 'alternate') ? '' : 'alternate';

		$title = ( empty( $feature[ 'read-more-url' ] ) ) ? esc_html( $feature[ 'title' ] ) : sprintf( '<a href="%s" target="_blank" rel="noopener">%s</a>', esc_url( $feature[ 'read-more-url' ] ), esc_html( $feature[ 'title' ] ) );

		$template = ( $list ) ? '<li class="%s"><span>%s</span> - %s</li>' : 
									'<tr class="%s">
											<td class="sh-cd-bold">%s</td>
											<td>%s</td>
										</tr>';

		$html .= sprintf( $template, 
							$class, 
							$title,
							esc_html( $feature[ 'description' ] )
						);
	}

    $html .= ( $list ) ? '</ul>' : '</table>';

	return $html;
}

/**
 * Display a table of premade shortcodes
 *
 * @param string $display
 * @return string
 */
function sh_cd_display_premade_shortcodes( $display = 'all' ) {

	$premium_user = sh_cd_is_premium();
	$upgrade_link = sprintf( '<a class="button sh-cd-upgrade-button" href="%1$s"><i class="fa-regular fa-star"></i> %2$s</a>', sh_cd_license_upgrade_link(), __('Upgrade now', SH_CD_SLUG ) );

	switch ( $display ) {
		case 'free':
			$shortcodes = sh_cd_shortcode_presets_free_list();
			$show_premium_col = false;
			break;
		case 'premium':
			$shortcodes = sh_cd_shortcode_presets_premium_list();
			$show_premium_col = false;
			break;
		default:
			$shortcodes = sh_cd_presets_both_lists();
			$show_premium_col = true;
	}

	$html = sprintf('<table class="widefat sh-cd-table" width="100%%">
                <tr class="row-title">
                    <th class="row-title" width="30%%">%s</th>', __('Shortcode', SH_CD_SLUG ) );

                     if ( true === $show_premium_col) {
	                     $html .= sprintf( '<th class="row-title">%s</th>', __('Premium', SH_CD_SLUG ) );
                     }

					$html .= sprintf( '<th width="*">%s</th>
											</tr>', __('Description', SH_CD_SLUG ) );

	$class = '';

	foreach ( $shortcodes as $key => $data ) {

		$class = ($class == 'alternate') ? '' : 'alternate';

		$shortcode = '[' . SH_CD_SHORTCODE. ' slug=&quot;' . $key . '&quot;]';

		$premium_shortcode = ( true === isset( $data['premium'] ) && true === $data['premium'] );

		$html .= sprintf( '<tr class="%s"><td>%s <i class="far fa-copy sh-cd-copy-trigger sh-cd-tooltip" data-clipboard-text="%2$s" title="%3$s"></i></td>', 
							$class, 
							esc_html( $shortcode ), 
							__( 'Copy to clipboard', SH_CD_SLUG )
				);


		if ( true === $show_premium_col) {

			$html .= sprintf( '<td align="middle">%s%s</td>',
				( true === $premium_shortcode && true === $premium_user ) ? '<i class="fas fa-star"></i>' : '',
				( true == $premium_shortcode && false === $premium_user ) ? $upgrade_link : ''
			);
		}

		$html .= sprintf( '<td>%s</td></tr>', wp_kses_post( $data['description'] ) );

	}

    $html .= '</table>';

	return $html;
}

/**
 * Fetch the latest version of the premium plugin
 */
function sh_cd_get_latest_premium_version() {
	
	if ( $cache = get_transient( '_yeken_shortcode_variables_latest_premium_version' ) ) {
		return $cache;
	}

	$response 	= wp_remote_get( SH_CD_YEKEN_PREMIUM_RELEASE_MANIFEST );
	$version 	= NULL;

	// All ok?
	if ( 200 === wp_remote_retrieve_response_code( $response ) ) {

		$body = wp_remote_retrieve_body( $response );

		if ( false === empty( $body ) ) {

			$body = json_decode( $body, true );

			$version = ( false === empty( $body[ 'version' ] ) ) ? $body[ 'version' ] : NULL;
			
			set_transient( '_yeken_shortcode_variables_latest_premium_version', $version, DAY_IN_SECONDS );
		}
	}

	return $version;
}

/**
 * Display admin notice for notification from yeken.uk
 */
function sh_cd_get_marketing_message() {
	
	if ( $cache = get_transient( '_yeken_shortcode_variables_update' ) ) {
		return $cache;
	}

	$response = wp_remote_get( SH_CD_YEKEN_UPDATES_URL );

	// All ok?
	if ( 200 === wp_remote_retrieve_response_code( $response ) ) {

		$body = wp_remote_retrieve_body( $response );

		if ( false === empty( $body ) ) {
			$body = json_decode( $body, true );
			
			set_transient( '_yeken_shortcode_variables_update', $body, DAY_IN_SECONDS );

			return $body;
		}
	}

	return NULL;
}

/**
 * Get/Set key of notice last dismissed.
 */
function sh_cd_marketing_update_key_last_dismissed( $key = NULL ) {
	
	if ( NULL !== $key ) {
		update_option( '_yeken_shortcode_variables_update_key_last_dismissed', $key );
	}
	
	return (int) get_option( '_yeken_shortcode_variables_update_key_last_dismissed' ) ;

}

/**
 * Display HTML for admin notice
 */
function sh_cd_updates_display_notice( $json ) {

	if ( false === is_array( $json ) ) {
		return;
	}

	$button = '';

	if ( !empty( $json[ 'url'] ) && !empty( $json[ 'url-title' ] ) ) {
		$button = sprintf( '<p>
								<a href="%1$s" class="button button-primary" target="_blank" rel="noopener">%2$s</a>
							</p>',
							esc_url( $json[ 'url' ] ),
							sh_cd_wp_kses( $json[ 'url-title' ] )
		);
	}
				

    printf('<div class="updated notice is-dismissible sh-cd-update-notice" data-update-key="%4$s" data-nonce="%5$s">
                        <p><strong>%1$s</strong>: %2$s</p>
                       	%3$s
                    </div>',
                    esc_html( SH_CD_PLUGIN_NAME ),
                    !empty( $json[ 'message' ] ) ? esc_html( $json[ 'message' ] ) : '',
                    $button,
					esc_html( $json[ '_update_key' ] ),
					esc_attr( wp_create_nonce( 'sh-cd-nonce' ) )
    );
}

 /**
  * display and admin notice if one exists and hasn't been dismissed already.
  */
function sh_cd_updates_admin_notice() {
   
	$json = sh_cd_get_marketing_message();

	if ( true === empty( $json ) ) {
		return false;
	}

	if ( $json[ '_update_key' ] <> sh_cd_marketing_update_key_last_dismissed() ) {
		sh_cd_updates_display_notice( $json );
	}
}
add_action( 'admin_notices', 'sh_cd_updates_admin_notice' );

 /**
  * Ajax handler to dismiss setup wizard
  */
 function sh_cd_updates_ajax_dismiss() {

	check_ajax_referer( 'sh-cd-nonce', 'security' );
	
	if ( true === empty( $_POST[ 'update_key' ] ) ) {
		return;
	}
	
	$update_key = (int) $_POST[ 'update_key' ];
	
	if ( false === empty( $update_key ) ) {
		sh_cd_marketing_update_key_last_dismissed( $update_key );
	}
 }
 add_action( 'wp_ajax_sh_cd_dismiss_notice', 'sh_cd_updates_ajax_dismiss' );

 /**
  * Text to display on upgrade and license page
  */
function sh_cd_marketing_upgrade_page_text() {
?>
	<h4 class="sh-cd-promo"><i class="fa-regular fa-star"></i> <?php echo __( 'Unlock these extra features when you upgrade:', SH_CD_SLUG ); ?></h4>
	<?php echo sh_cd_display_premium(); ?>
	<h4 class="sh-cd-promo"><i class="fa-regular fa-star"></i> <?php echo __( 'Extra Premium shortcodes', SH_CD_SLUG ); ?>:</h4>
	<p><?php echo __( 'Upgrade to the Premium version of Snippet Shortcodes to unlock these additional shortcodes.', SH_CD_SLUG ); ?>:</p>
	<?php echo sh_cd_display_premade_shortcodes( 'premium' );  ?>
<?php
}

/**
 * Marketing prompt to upgrade on Edit page to get additional shortcode options
 *
 * @return void
 */
function sh_cd_marketing_page_edit_additional_options() {

	$title =  __( 'Unlock these additional settings by upgrading to Premium', SH_CD_SLUG ) ;

	$content = sprintf('<ul><li>%s</li><li>%s</li><li>%s</li><li>%s</li><li>%s</li></ul>',
		__( '<span>Insert into Header / Footer</span> - specify whether to automatically insert the shortcode content into the WP Header or Footer.', SH_CD_SLUG ),
		__( '<span>Limit to mobile or desktop</span> - specify whether a shortcode should only appear on mobile or desktop devices.', SH_CD_SLUG ),
		__( '<span>Global shortcodes</span> - specify whether a shortcode should be available across all sites in your multisite network.', SH_CD_SLUG ),
		__( '<span>Disable shortcodes</span> - have the ability to disable shortcodes so content will not appear at the location of the shortcode in the public facing site.', SH_CD_SLUG ),
		__( '<span>Edit Slug</span> - have the ability to change the slug of an existing shortcode.', SH_CD_SLUG )
	);

	sh_cd_display_pro_upgrade_notice( $title, $content, 'sh-cd-page-edit-marketing-prompt' );
}

