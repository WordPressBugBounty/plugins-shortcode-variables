<?php

defined('ABSPATH') or die('Jog on!');

/**
 * Is the Premium plugin enabled and do we have a valid license?
 *
 * @return bool
 */
function sh_cd_is_premium() {

	if ( false === sh_cd_is_premium_plugin_activated() ) {
		return false;
	}

	return apply_filters( 'sh-cd-license-is-premium', false );
}

/**
 * Is Premium plugin enabled?
 */
function sh_cd_is_premium_plugin_activated() {
	return defined( 'YK_SS_PLUGIN_NAME' );
}

/**
 *	Generate a site hash to identify this site.
	**/
function sh_cd_generate_site_hash() {

	$site_hash = get_option( 'sh-cd-hash' );

	// Generate a basic site key from URL and plugin slug
	if( false == $site_hash ) {

		$site_hash = md5( 'yeken-sh-cd-' . site_url() );
		$site_hash = substr( $site_hash, 0, 6 );

		update_option( 'sh-cd-hash', $site_hash );

	}
	return $site_hash;
}

/**
 * Save / Insert a shortcode
 *
 * @return bool
 */
function sh_cd_shortcodes_save_post() {

	$fields = apply_filters( 'sh-cd-post-field-keys', [ 'id', 'slug', 'previous_slug', 'data', 'disabled', 'multisite', 'editor' ] );
 
	// Capture the raw $_POST fields, the save functions will process and validate the data
	$shortcode = sh_cd_get_values_from_post( $fields );

	// If we are not premium, then the user is not allowed to change the site slug (otherwise they could just re-use variables and by pass the limit)
	if ( ! sh_cd_is_premium() && false === empty( $shortcode[ 'previous_slug' ] ) ) {
		$shortcode[ 'slug' ] = $shortcode[ 'previous_slug' ];
	}
	
	return sh_cd_db_shortcodes_save( $shortcode );
}

/**
 * Replace user parameters within a shortcode e.g. look for %%parameter%% and replace
 *
 * @param $shortcode
 * @param $user_defined_parameters
 *
 * @return mixed
 */
function sh_cd_apply_user_defined_parameters( $shortcode, $user_defined_parameters ){

    // Ensure we have something to do!
    if ( true === empty( $user_defined_parameters ) || false === is_array( $user_defined_parameters ) ) {
        return $shortcode;
    }

    foreach ( $user_defined_parameters as $key => $value ) {
        $shortcode = str_replace( '%%' . $key . '%%', $value, $shortcode );
    }

	return $shortcode;
}

/**
 * Generate a unique slug
 *
 * @param $slug
 *
 * @return string
 */
function sh_cd_slug_generate( $slug, $exising_id = NULL ) {

    if ( true === empty( $slug ) ) {
        return NULL;
    }

	$slug = sanitize_key( $slug );

    $original_slug = $slug;

    $try = 1;

    // Ensure the slug is unique
    while ( false === sh_cd_slug_is_unique( $slug, $exising_id ) ) {

	    $slug = sprintf( '%s_%d', $original_slug, $try );

        $try++;
    }

    return $slug;
}

/**
 * Clone an existing shortcode!
 *
 * @param $id
 *
 * @return bool
 */
function sh_cd_clone( $id ) {

	if( false === sh_cd_is_premium() ) {
		return true;
	}

	if ( false === is_numeric( $id ) ) {
		return false;
	}

	$to_be_cloned = sh_cd_db_shortcodes_by_id( $id );

	if ( true === empty( $to_be_cloned ) ) {
		return false;
	}

	unset( $to_be_cloned['id'] );

	return sh_cd_db_shortcodes_save( $to_be_cloned );
}

/**
 * Display message in admin UI
 *
 * @param $text
 * @param bool $error
 */
function sh_cd_message_display( $text, $error = false ) {

    if ( true === empty( $text ) ) {
        return;
    }

    printf( '<div class="%s"><p>%s</p></div>',
            true === $error ? 'error' : 'updated',
            esc_html( $text )
    );

    //TODO: Hook this to use admin_notices
}

/**
 * Fetch cache item
 *
 * @param $key
 *
 * @return mixed
 */
function sh_cd_cache_get( $key ) {

    $key = sh_cd_cache_generate_key( $key );

    return get_transient( $key );
}

/**
 * Set cache item
 *
 * @param $key
 * @param $data
 */
function sh_cd_cache_set( $key, $data, $expire = NULL ) {


	$expire = ( false === empty( $expire ) ) ? (int) $expire : 1 * HOUR_IN_SECONDS;

    $key = sh_cd_cache_generate_key( $key );

    set_transient( $key, $data, $expire );

	do_action( 'sh-cd-global-cache-delete' );
}

/**
 * Delete cache for given shortcode slug / ID
 *
 * @param $slug_or_key
 */
function sh_cd_cache_delete_by_slug_or_key( $slug_or_key ) {

    if ( true === is_numeric( $slug_or_key ) ) {

	    $slug_or_key = sh_cd_db_shortcodes_get_slug_by_id( $slug_or_key );

        sh_cd_cache_delete( $slug_or_key );

    } else {
	    sh_cd_cache_delete( $slug_or_key );
    }

    // Delete site option
	$slug_or_key = SH_CD_PREFIX . $slug_or_key;

	delete_site_option( $slug_or_key );

}

/**
 * Delete cache item
 *
 * @param $key
 *
 * @return mixed
 */
function sh_cd_cache_delete( $key, $trigger_global_hook = true ) {

    $key = sh_cd_cache_generate_key( $key );

	if ( true === $trigger_global_hook ) {
		do_action( 'sh-cd-global-cache-delete' );
	}

    return delete_transient( $key );
}


/**
 * Generate cache key
 *
 * @param $key
 *
 * @return string
 */
function sh_cd_cache_generate_key( $key ) {
    return SH_CD_SHORTCODE . SH_CD_PLUGIN_VERSION . $key;
}

/**
 * Return link to list own shortcodes
 *
 * @return mixed
 */
function sh_cd_link_your_shortcodes() {

	$link = admin_url('admin.php?page=sh-cd-shortcode-variables-your-shortcodes');

	return esc_url( $link );
}

/**
 * Return link to add own shortcode
 *
 * @return mixed
 */
function sh_cd_link_your_shortcodes_add() {

    $link = admin_url('admin.php?page=sh-cd-shortcode-variables-your-shortcodes&action=add');

    return esc_url( $link );
}

/**
 * Return link to edit own shortcode
 *
 * @return mixed
 */
function sh_cd_link_your_shortcodes_edit( $id ) {

	$link = admin_url('admin.php?page=sh-cd-shortcode-variables-your-shortcodes&action=edit&id=' . (int) $id );

	return esc_url( $link );
}

/**
 * Return link to delete own shortcode
 *
 * @param $id
 * @return mixed
 */
function sh_cd_link_your_shortcodes_delete( $id ) {

	$link = admin_url('admin.php?page=sh-cd-shortcode-variables-your-shortcodes&action=delete&id=' . (int) $id );

	return esc_url( $link );
}

/**
 * Either fetch data from the $_POST object or from the array passed in!
 *
 * @param $object
 * @param $key
 * @return string
 */
function sh_cd_get_value_from_post_or_obj( $object, $key ) {

	if ( true === isset( $_POST[ $key ] ) ) {
		return $_POST[ $key ];
	}

	if ( true === isset( $object[ $key ] ) ) {
		return $object[ $key ];
	}

	return '';
}

/**
 * Either fetch data from the $_POST object for the given object keys
 *
 * @param $keys
 * @return array
 */
function sh_cd_get_values_from_post( $keys ) {

	$data = [];

	foreach ( $keys as $key ) {

		if ( true === isset( $_POST[ $key ] ) ) {
			$data[ $key ] = $_POST[ $key ];
		} else {
			$data[ $key ] = '';
		}

	}

	return $data;
}

/**
 * Toggle the status of a shortcode
 *
 * @param $id
 */
function sh_cd_toggle_status( $id ) {

	$slug = sh_cd_db_shortcodes_by_id( (int) $id );

	if ( false === empty( $slug ) ) {

	    $status = ( 1 === (int) $slug['disabled'] ) ? 0 : 1 ;

		sh_cd_db_shortcodes_update_status( $id, $status );

	    return $status;
    }

	return NULL;
}

/**
 * Toggle the multisite of a shortcode
 *
 * @param $id
 * @return int|null
 */
function sh_cd_toggle_multisite( $id ) {

	$slug = sh_cd_db_shortcodes_by_id( (int) $id );

	if ( false === empty( $slug ) ) {

		$multisite = ( 1 === (int) $slug['multisite'] ) ? 0 : 1 ;

		sh_cd_db_shortcodes_update_multisite( $id, $multisite );

		return $multisite;
	}

	return NULL;
}

/**
 * Display an upgrade button
 *
 * @param string $css_class
 * @param null $link
 */
function sh_cd_upgrade_button( $css_class = '', $link = NULL ) {

    $link = ( false === empty( $link ) ) ? $link : SH_CD_UPGRADE_LINK . '?hash=' . sh_cd_generate_site_hash() ;

	$price = sh_cd_license_price();
	$price = ( false === empty( $price ) ) ? sprintf( '- £%s %s', $price, __( 'a year ', SH_CD_SLUG ) ) : '';

	echo sprintf('<a href="%s" class="button-primary sh-cd-upgrade-button sh-cd-button %s"><i class="far fa-star"></i> %s %s</a>',
		esc_url( $link ),
		esc_attr( ' ' . $css_class ),
        __( 'Purchase a license ', SH_CD_SLUG ),
        $price
	);
}

/**
 * Display an upgrade button
 *
 * @param string $css_class
 * @param null $link
 */
function sh_cd_premium_shortcode_download( $return = false ) {

    $link = SH_CD_GET_PREMIUM_LINK . '?hash=' . sh_cd_generate_site_hash();

	$html = sprintf('<a href="%s" class="button-primary sh-cd-button sh-cd-upgrade-button"><i class="fas fa-download"></i> %s</a>',
		esc_url( $link ),
        __( 'Download Premium Plugin', SH_CD_SLUG )
	);

	if ( true === $return ) {
		return $html;
	}

	echo $html;
}

/**
 * Is multsite functionality active for this install?
 *
 * @return bool
 */
function sh_cd_is_multisite_enabled() {

	if ( true === defined( 'YK_TEST_IS_MULTISITE' ) && true === YK_TEST_IS_MULTISITE ) {
		return true;
	}

	if ( false === is_multisite() ) {
		return false;
	}

	if ( false === sh_cd_is_premium() ) {
		return false;
	}

	return true;
}

/**
 * Fetch all multisite slugs
 *
 * @return array|null
 */
function sh_cd_multisite_slugs() {

	if ( false === is_multisite() ) {
		return [];
	}

	$cache = sh_cd_cache_get( 'sh-cd-multisite-slugs' );

	if ( false !== $cache ) {
		return $cache;
	}

	$slugs = sh_cd_db_shortcodes_multisite_slugs();

	$slugs = ( false === empty( $slugs ) ) ? wp_list_pluck( $slugs, 'slug' ) : [];

	// Cache this for a short time
	sh_cd_cache_set( 'sh-cd-multisite-slugs', $slugs, 30 );

	return ( true === is_array( $slugs ) ) ? $slugs : [];
}

/**
 * Have we reached the limit of free shortcodes?
 * @return bool
 */
function sh_cd_reached_free_limit() {

	if ( true === sh_cd_is_premium() ) {
		return false;
	}

	$existing_shortcodes = sh_cd_db_shortcodes_count();

	if ( true === empty( $existing_shortcodes ) ) {
		return false;
	}

	return ( (int) $existing_shortcodes >= sh_cd_get_free_limit() );
}

/**
 * Return free limit for shortcodes
 */
function sh_cd_get_free_limit() {
	return 10;
}

/**
 * Get the minimum user role allowed for viewing data pages in admin
 * @return mixed|void
 */
function sh_cd_permission_role() {

	// If not premium, then admin only
	if ( false === sh_cd_is_premium() ) {
		return 'manage_options';
	}

	$permission_role = get_option( 'sh-cd-edit-permissions', 'manage_options' );

	return ( false === empty( $permission_role ) ) ? $permission_role : 'manage_options';
}

/**
 * Does the user have the correct permissions to view this page?
 */
function sh_cd_permission_check() {

	$allowed_viewer = sh_cd_permission_role();

	if ( false === current_user_can( $allowed_viewer ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.', SH_CD_SLUG ) );
	}
}

/**
 * Is the shortcode [sv slug="sc-db-value-by-id"] enabled
 * @return bool (default false)
 */
function sh_cd_is_shortcode_db_value_by_id_enabled() {

    if ( false === sh_cd_is_premium() ) {
        return false;
    }

    // Disabling by filter overrides the setting in WP admin
    if ( true === apply_filters( 'disable-ss-sc-db-value-by-id', __return_false() ) ) {
        return false;
    }

    $value = get_option( 'sh-cd-shortcode-db-value-by-id-enabled', false );

    return sh_cd_to_bool( $value );
}

/**
 * Display upgrade notice
 *
 * @param bool $pro_plus
 */
function sh_cd_display_pro_upgrade_notice( $title = NULL, $content = '', $class = '' ) {
	
	$title = ( true === empty( $title ) ) ? __( 'Upgrade Snippet Shortcodes and get more features!', SH_CD_SLUG ) : $title;

	?>
	<div class="postbox sh-cd-advertise-premium <?php echo esc_attr( $class ) ?>">
		<h3 class="hndle"><i class="fa-regular fa-star"></i> <?php echo esc_html( $title ) ?></h3>
		<div style="padding: 0px 15px 0px 15px">
			<p><?php echo wp_kses( $content, [ 'ul' => [ 'class' ], 'li' => [], 'strong' => [], 'span' => [], 'div' => [] ] ); ?></p>
			<p><a href="<?php echo esc_url( admin_url('admin.php?page=sh-cd-shortcode-variables-upgrade') ); ?>" class="button-primary sh-cd-upgrade-button"><i class="fa-regular fa-star"></i> <?php echo __( 'Get Premium', SH_CD_SLUG ); ?></a></p>
		</div>
	</div>
<?php
}

/**
 * Display a star to prompt for a Premioum upgrade
 *
 * @param bool $pro_plus
 */
function sh_cd_display_premium_star() {

	if ( true === sh_cd_is_premium() ) {
		return '';	// We don't want to show the star if the user has already upgraded
	}

	return sprintf ('<a href="%s"><i class="fa-regular fa-star"></i></a>', 
						esc_url( admin_url('admin.php?page=sh-cd-shortcode-variables-upgrade') )
					);
}
   
/**
 * Display info symbol with tooltip
 */
function sh_cd_display_info_tooltip( $text ) {

	return sprintf ('<i class="fa-regular fa-circle-question sh-cd-tooltip" title="%s">', 
						esc_html( $text )
					);
}

/**
 * Process a CSV attachment and import into database
 *
 * @param $attachment_id
 *
 * @param bool $dry_run
 *
 * @return string
 */
function sh_cd_import_csv( $attachment_id, $dry_run = true ) {

	if ( false === sh_cd_permission_check() ) {
		return 'You do not have the correct admin permissions';
	}

	if ( false === sh_cd_is_premium() ) {
		return 'This is a premium feature';
	}

	$csv_path = get_attached_file( $attachment_id );
	$admin_id = get_current_user_id();

	if ( true === empty( $csv_path ) || false === file_exists( $csv_path )) {
		return 'Error: Error loading CSV from disk.';
	}

	$csv = array_map('str_getcsv', file( $csv_path ) );

	if ( true === empty( $csv ) ) {
		return 'Error: The CSV appears to be empty.';
	}

	array_walk($csv, function(&$a) use ($csv) {
		$a = array_combine($csv[0], $a);
	});

	$validate_header_result = sh_cd_import_csv_validate_header( $csv[0] );

	if ( true !== $validate_header_result ) {
		return $validate_header_result;
	}

	array_shift($csv );

	if ( true === empty( $csv ) ) {
		return 'Error: The CSV appears to be empty (when header hs been removed).';
	}

	$errors = 0;

	$output = sprintf( '%d rows to process...' . PHP_EOL, count( $csv ) );

	if ( true === $dry_run ) {
		$output .= 'DRY RUN MODE! No data will be imported.' . PHP_EOL;
	}

	foreach ( $csv as $row ) {

		if ( $errors >= 50 ) {
			$output .= 'Aborted! More than 50 errors have been detected in this file.' . PHP_EOL;
			break;
		}

		$row = array_change_key_case( $row ); // Force CSV headers to lowercase

		$validation_result = sh_cd_import_csv_validate_row( $row );

		// Validate a row before proceeding
		if ( true !== $validation_result ) {
			$output .= $validation_result . PHP_EOL;
			$errors++;
			continue;
		}

		if ( false === $dry_run ) {

			$shortcode = [	'slug' 			=> $row[ 'slug' ],
							'previous_slug' => '',
							'data' 			=> $row[ 'content' ],
							'disabled' 		=> ! sh_cd_to_bool( $row[ 'enabled' ] ),
							'multisite' 	=> sh_cd_to_bool( $row[ 'global' ] )
			];

			$result = sh_cd_db_shortcodes_save( $shortcode );

			if ( false === $result ) {
				$output .= 'Skipped: Error inserting into database (most likely a field contains too many characters or in the wrong format): ' .  implode( ',', $row ) . PHP_EOL;
			}
		}

	}

	if ( $errors > 0 ) {
		$output .= sprintf( '%d errors were detected and the rows skipped.' . PHP_EOL, $errors );
	}

	$output .= 'Completed.';

	return $output;

}

/**
 * Verify header row
 * @param $header_row
 *
 * @return bool|string
 */
function sh_cd_import_csv_validate_header( $header_row ) {

	$expected_headers = [ 'slug', 'content', 'global', 'enabled' ];

	foreach ( $expected_headers as $column ) {

		if ( false === isset( $header_row[ $column ] ) ) {
			return 'Missing column: ' . $column . '. Expecting: ' . implode( ',', $expected_headers ) . PHP_EOL;
		}
	}

	return true;
}

/**
 * Validate CSV row
 * @param $csv_row
 *
 * @return bool|string
 */
function sh_cd_import_csv_validate_row( $csv_row ) {

	if ( true === empty( $csv_row[ 'slug' ] ) ) {
		return 'Skipped: Missing slug: ' . implode( ',', $csv_row );
	}

	if ( false === empty( $isset[ 'content' ] ) ) {
		return 'Skipped: Content: ' . implode( ',', $csv_row );
	}

	$allowed_bools = [ 'yes', 'no', 'true', 'false', '1', '0' ];

	if ( true === empty( $csv_row[ 'global' ] ) ||
		 false === in_array( $csv_row[ 'global' ], $allowed_bools ) ) {
		return 'Skipped: Invalid "global" value. Must be "yes" or "no": ' . implode( ',', $csv_row );
	}

	if ( true === empty( $csv_row[ 'enabled' ] ) ||
		 false === in_array( $csv_row[ 'enabled' ], $allowed_bools ) ) {
		return 'Skipped: Invalid "enabled" value. Must be "yes" or "no": ' . implode( ',', $csv_row );
	}

	return true;
}

/**
 * Convert string to bool
 * @param $string
 * @return mixed
 */
function sh_cd_to_bool( $string ) {
	return filter_var( $string, FILTER_VALIDATE_BOOLEAN );
}

/**
 * Our version of kses and the HTML we are happy with
 */
function sh_cd_wp_kses( $value ) {

	$basic_tags = wp_kses_allowed_html( 'html' );

	$basic_tags[ 'a' ] 		= [ 'id' => true, 'class' => true, 'href' => true, 'title' => true, 'target' => true];
	$basic_tags[ 'canvas' ] = [ 'id' => true, 'class' => true ];
	$basic_tags[ 'div' ]	= [ 'id' => true, 'class' => true, 'style' => true ];	
	$basic_tags[ 'i' ]		= [ 'id' => true, 'class' => true ];	
	$basic_tags[ 'p' ]		= [ 'id' => true, 'class' => true ];		
	$basic_tags[ 'span' ]	= [ 'id' => true, 'class' => true ];			
	$basic_tags[ 'table' ]	= [ 'id' => true, 'class' => true ];	
	$basic_tags[ 'tr' ]		= [ 'id' => true, 'class' => true ];	
	$basic_tags[ 'td' ]		= [ 'id' => true, 'class' => true ];	
	$basic_tags[ 'li' ]		= [ 'class' => true ];	

	return wp_kses( $value, $basic_tags );
}

/**
 * Return the current url
 */
function sh_cd_get_current_url() {
	$protocol = (
		( isset($_SERVER['HTTPS'] ) && 'on' == $_SERVER['HTTPS'] ) ||
		( isset($_SERVER['SERVER_PORT'] ) && 443 == $_SERVER['SERVER_PORT'] )
	) ? 'https://' : 'http://';

	return $protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
}

/**
 * Get selected default editor
 */
function sh_cd_default_editor_get() {
	return get_option( 'sh-cd-option-default-editor', 'tinymce' );
}

/**
 * Is editor valid
 */
function sh_cd_editors_is_valid( $editor ) {
	$editors = sh_cd_editors_options();

	return ! empty( $editors[ $editor ] );
}

/**
 * Return valid editors
 */
function sh_cd_editors_options( $keys_only = true ) {
	return [ 'tinymce' => __( 'WordPress Editor', SH_CD_SLUG ), 'code' => __( 'HTML Editor', SH_CD_SLUG ) ];
}

/**
 * Are tooltips enabled?
 */
function sh_cd_tooltips_is_enabled() {
	return ( 'yes' === get_option( 'sh-cd-option-tool-tips-enabled', 'yes' ) );
}

/**
 * Fetch icons for given shortcode
 *
 * @param [type] $shortcode
 * @param boolean $return_array
 * @return void
 */
function sh_cd_icons_for_shortcode( $shortcode, $return_array = false ) {

	if ( true === empty( $shortcode ) ) {
		return [];
	}

	$icons = [];

	if ( false === empty( $shortcode[ 'header' ] ) ) {
		$icons[] = sprintf( '<i class="fa-solid fa-heading sh-cd-option-icon sh-cd-tooltip" title="%s"></i>', esc_html( __( 'Insert into WP Header', SH_CD_SLUG ) ) );
	}

	if ( false === empty( $shortcode[ 'footer' ] ) ) {
		$icons[] = sprintf( '<i class="fa-solid fa-shoe-prints sh-cd-option-icon sh-cd-tooltip" title="%s"></i>', esc_html( __( 'Insert into WP Footer', SH_CD_SLUG ) ) );
	}

	if ( false === empty( $shortcode[ 'device_type' ] ) ) {
		$shortcode[ 'device_type' ] = json_decode( $shortcode[ 'device_type' ] );
	}

	if ( true === is_array( $shortcode[ 'device_type' ] ) ) {

		if ( true === in_array( 'desktop', $shortcode[ 'device_type' ] ) ) {
			$icons[] = sprintf( '<i class="fa-solid fa-desktop sh-cd-option-icon sh-cd-tooltip" title="%s"></i>', esc_html( __( 'Display only on desktop devices', SH_CD_SLUG ) ) );
		} 
		
		if ( true === in_array( 'mobile', $shortcode[ 'device_type' ] ) ) {
			$icons[] = sprintf( '<i class="fa-solid fa-mobile-screen sh-cd-option-icon sh-cd-tooltip" title="%s"></i>', esc_html( __( 'Display only on mobile devices', SH_CD_SLUG ) ) );
		}

		if ( true === in_array( 'tablet', $shortcode[ 'device_type' ] ) ) {
			$icons[] = sprintf( '<i class="fa-solid fa-tablet-screen-button sh-cd-option-icon sh-cd-tooltip" title="%s"></i>', esc_html( __( 'Display only on tablet devices', SH_CD_SLUG ) ) );
		}
	}

	if ( true === $return_array ) {
		return $icons;
	}

	return ( false === empty( $icons ) ) ? implode( PHP_EOL, $icons ) : '';
}