<?php

defined('ABSPATH') or die('Jog on!');

/**
 * Build database table
 */
function sh_cd_create_database_table() {

	global $wpdb;

	$table_name = $wpdb->prefix . SH_CD_TABLE;

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  slug varchar(100) NOT NULL,
	  previous_slug varchar(100) NOT NULL,
	  editor varchar(10) NULL,
	  data text,
	  disabled bit default 0,
	  multisite bit default 0,
	  header bit default 0,
	  footer bit default 0,
	  device_type varchar(50) NULL DEFAULT '[\"desktop\",\"mobile\",\"tablet\"]',
	  UNIQUE KEY id (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	dbDelta( $sql );
}

/**
 * Build database table for multisite
 */
function sh_cd_create_database_table_multisite() {

	global $wpdb;

	$table_name = $wpdb->base_prefix . SH_CD_TABLE_MULTISITE;

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  slug varchar(100) NOT NULL,
	  data text,
	  site_id int default 0,
	  header bit default 0,
	  footer bit default 0,
	  device_type varchar(50) NULL DEFAULT '[\"desktop\",\"mobile\",\"tablet\"]',
	  UNIQUE KEY id (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	dbDelta( $sql );
}

/**
 * Fetch all Shortcodes
 *
 * @return bool
 */
function sh_cd_db_shortcodes_all() {

	global $wpdb;

	return $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . SH_CD_TABLE . ' order by slug asc', ARRAY_A );
}

/**
 * Fetch all enabled Shortcodes
 *
 * @return bool
 */
function sh_cd_db_shortcodes_all_enabled() {

	global $wpdb;

	return $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . SH_CD_TABLE . ' where disabled = 0 order by slug asc', ARRAY_A );
}

/**
 * Fetch a count of all shortcodes
 *
 * @return bool
 */
function sh_cd_db_shortcodes_count() {

	global $wpdb;

	return $wpdb->get_var( 'SELECT count(id) FROM ' . $wpdb->prefix . SH_CD_TABLE );
}

/**
 * Fetch a shortcode by ID (mainly used for quick lookups in admin)
 *
 * @param $id
 *
 * @return mixed
 */
function sh_cd_db_shortcodes_by_id( $id ) {

	global $wpdb;

	$sql 		= $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . SH_CD_TABLE . ' where id = %d', $id);
	$shortcode 	= $wpdb->get_row( $sql, ARRAY_A );

	if ( false === empty( $shortcode ) ) {
		$shortcode = sh_cd_db_filter_loaded_shortcode( $shortcode );
	}
	
	return $shortcode;
}

/**
 * Fetch the content of the shortcode by slug
 *
 * @param $slug
 *
 * @return null|string
 */
function sh_cd_db_shortcodes_by_slug( $slug ) {

	global $wpdb;

	$sqls = [];

	// If multi site functionality is enabled, look there first for the shortcode!
	if ( true === sh_cd_is_multisite_enabled() ) {
		
		$sql = $wpdb->prepare('SELECT * FROM ' . $wpdb->base_prefix . SH_CD_TABLE_MULTISITE . ' where slug = %s', $slug);

		$shortcode = $wpdb->get_row( $sql, ARRAY_A );

		if ( false === empty( $shortcode[ 'data' ] ) ) {
			$shortcode[ 'data' ]	= stripslashes( $shortcode[ 'data' ] );
			$shortcode[ 'source' ] 	= 'multisite';
			return sh_cd_db_filter_loaded_shortcode( $shortcode );
		}	
	}

	// No multisite slug by this name, so check the main table
	$sql 		= $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . SH_CD_TABLE . ' where slug = %s and disabled <> 1', $slug);
	$shortcode 	= $wpdb->get_row( $sql, ARRAY_A );

	if ( false === empty( $shortcode[ 'data' ] ) ) {
		$shortcode[ 'data' ]	= stripslashes( $shortcode[ 'data' ] );
		$shortcode[ 'source' ]	= 'local';
		return sh_cd_db_filter_loaded_shortcode( $shortcode );
	}
	
	return NULL;
}

/**
 * Allow processing of loaded shortcode data
 */
function sh_cd_db_filter_loaded_shortcode( $shortcode ) {
	return apply_filters( 'sh-cd-db-loaded-shortcode', $shortcode );
}

/**
 * Get a Shortcode's slug from the slug ID
 *
 * @param $id
 *
 * @return bool
 */
function sh_cd_db_shortcodes_get_slug_by_id( $id ) {

	global $wpdb;

	$sql = $wpdb->prepare('SELECT slug FROM ' . $wpdb->prefix . SH_CD_TABLE . ' where id = %d', $id);

	return $wpdb->get_var( $sql );
}

/**
 * Save / Insert a shortcode
 *
 * @param $shortcode
 *
 * @return bool
 */
function sh_cd_db_shortcodes_save( $shortcode, $return_shortcode = false ) {

	if ( false === is_admin() ) {
		return false;
	}

	$multi_site_enabled = sh_cd_is_multisite_enabled();

	$default_values = apply_filters( 'sh-cd-db-default-values', [	'id'            => NULL,
																	'slug'          => NULL,
																	'previous_slug' => NULL,
																	'editor' 		=> NULL,
																	'data'          => NULL,
																	'disabled'      => 0,
																	'multisite'     => 0
	]);

	$shortcode = wp_parse_args( $shortcode, $default_values );

	// We need either a slug or an ID
	if ( true === empty( $shortcode['slug'] ) && true === empty( $shortcode['id'] ) ) {
		return false;
	}

	$shortcode['disabled'] 	= (int) $shortcode['disabled'];
	$shortcode['multisite'] = ( true === $multi_site_enabled ) ? (int) $shortcode['multisite'] : 0;

	global $wpdb;

	$result = false;

	// Updating an existing shortcode?
	if ( false === empty( $shortcode['id'] ) && true === is_numeric( $shortcode['id'] ) ){

		$shortcode['slug'] = sh_cd_slug_generate( $shortcode['slug'], $shortcode['id'] );

		$shortcode = apply_filters( 'sh-cd-db-default-shortcode-before-save', $shortcode );

		$formats = sh_cd_db_get_formats( $shortcode );

		$result = $wpdb->update(
			$wpdb->prefix . SH_CD_TABLE,
			$shortcode,
			[ 'id' => $shortcode['id'] ],
			$formats,
			[ '%d' ]
		);

		if ( false !== $result ) {
			do_action( 'sh-cd-shortcode-updated', $shortcode );
		}

		sh_cd_cache_delete_by_slug_or_key( $shortcode['id'] );
		sh_cd_cache_delete_by_slug_or_key( $shortcode['previous_slug'] );

		// Adding a new shortcode
	} else {

		unset( $shortcode['id'] );

		// Ensure slug is santised and unique
		$shortcode['slug'] = sh_cd_slug_generate( $shortcode['slug'] );

		$shortcode = apply_filters( 'sh-cd-db-default-shortcode-before-save', $shortcode );

		$formats = sh_cd_db_get_formats( $shortcode );

		$result = $wpdb->insert(
			$wpdb->prefix . SH_CD_TABLE,
			$shortcode,
			$formats
		);

		if ( false !== $result ) {
			do_action( 'sh-cd-shortcode-added', $shortcode );
		}

		// It's an insert, so there should be no cache... however, just a wee sanity check in case
		// a shortcode with the same slug previously exists.
		sh_cd_cache_delete_by_slug_or_key( $shortcode['slug'] );
	}

	if ( false !== $result ) {

		if ( 1 === $shortcode['multisite'] ) {
			sh_cd_db_shortcodes_multisite_insert( $shortcode );
		} else {
			sh_cd_db_shortcodes_multisite_delete( $shortcode['slug'] );
		}

		sh_cd_cache_delete( $shortcode['slug'] );

		if ( true === $return_shortcode ) {

			$shortcode['id'] = $result;
			return $shortcode;
		}

		return true;
	}

	return false;
}


/**
 * Insert a multisite shortcode
 *
 * @param $shortcode
 *
 * @return bool
 */
function sh_cd_db_shortcodes_multisite_insert( $shortcode ) {

	if ( false === is_admin() ) {
		return false;
	}

	sh_cd_db_shortcodes_multisite_delete( $shortcode['slug'] );

	global $wpdb;

	$shortcode_multisite = [	'slug' 			=> $shortcode['slug'],
								'data' 			=> $shortcode['data'],
								'header' 		=> $shortcode['header'],
								'footer' 		=> $shortcode['footer'],
								'device_type' 	=> $shortcode['device_type'],
								'site_id' 		=> get_current_blog_id()
	];

	$formats = sh_cd_db_get_formats( $shortcode_multisite );

	$result = $wpdb->insert(
		$wpdb->base_prefix . SH_CD_TABLE_MULTISITE,
		$shortcode_multisite,
		$formats
	);

	sh_cd_cache_delete( $shortcode['slug'] );

	do_action( 'sh_cd_multisite_changed', $shortcode );

	return ( false !== $result );
}

/**
 * Delete a multisite shortcode
 *
 * @param $slug
 *
 * @return bool
 */
function sh_cd_db_shortcodes_multisite_delete( $slug ) {

	global $wpdb;

	$result = $wpdb->delete( $wpdb->base_prefix . SH_CD_TABLE_MULTISITE, [ 'slug' => $slug ], [ '%s' ] );

	sh_cd_cache_delete( $slug );

	do_action( 'sh_cd_multisite_changed', $slug );

	return ( false !== $result );
}

/**
 * Fetch all multisite slugs

 * @return null|array
 */
function sh_cd_db_shortcodes_multisite_slugs() {

	global $wpdb;

	return $wpdb->get_results( 'SELECT slug FROM ' . $wpdb->base_prefix . SH_CD_TABLE_MULTISITE, ARRAY_A );
}


/**
 * Update a shortcode's status
 *
 * @param $shortcode
 *
 * @return bool
 */
function sh_cd_db_shortcodes_update_status( $id, $status ) {

	if ( false === is_admin() ) {
		return false;
	}

	global $wpdb;

	$result = $wpdb->update(
		$wpdb->prefix . SH_CD_TABLE,
		[ 'disabled' => $status ],
		[ 'id' => $id ],
		[ '%d' ],
		[ '%d' ]
	);

	sh_cd_cache_delete_by_slug_or_key( $id );

	return ( false !== $result );
}

/**
 * Update a shortcode's multisite
 *
 * @param $shortcode
 *
 * @return bool
 */
function sh_cd_db_shortcodes_update_multisite( $id, $multisite ) {

	if ( false === is_admin() ) {
		return false;
	}

	global $wpdb;

	$result = $wpdb->update(
		$wpdb->prefix . SH_CD_TABLE,
		[ 'multisite' => $multisite ],
		[ 'id' => $id ],
		[ '%d' ],
		[ '%d' ]
	);

	$shortcode = sh_cd_db_shortcodes_by_id( $id );

	if ( false === empty( $shortcode ) ) {

		if ( 1 === (int) $multisite ) {
			sh_cd_db_shortcodes_multisite_insert( $shortcode );
		} else {
			sh_cd_db_shortcodes_multisite_delete( $shortcode['slug'] );
		}

	}

	sh_cd_cache_delete_by_slug_or_key( $id );

	return ( false !== $result );
}

/**
 * Update a shortcode's content
 *
 * @param $shortcode
 *
 * @return bool
 */
function sh_cd_db_shortcodes_update_content( $id, $data ) {

	if ( false === is_admin() ) {
		return false;
	}

	global $wpdb;

	$result = $wpdb->update(
		$wpdb->prefix . SH_CD_TABLE,
		[ 'data' => $data ],
		[ 'id' => $id ],
		[ '%s' ],
		[ '%d' ]
	);

	$shortcode = sh_cd_db_shortcodes_by_id( $id );

	if ( false === empty( $shortcode ) ) {

		if ( 1 === (int) $shortcode[ 'multisite' ] ) {
			sh_cd_db_shortcodes_multisite_insert( $shortcode );
		} else {
			sh_cd_db_shortcodes_multisite_delete( $shortcode['slug'] );
		}

	}
	sh_cd_cache_delete_by_slug_or_key( $id );

	return ( false !== $result );
}


/**
 * Delete a shortcode
 *
 * @param $id
 *
 * @return bool
 */
function sh_cd_db_shortcodes_delete( $id ) {

	if ( false === is_admin() || false === is_numeric( $id ) ) {
		return false;
	}

	global $wpdb;

	// Clear multi site
	$shortcode_before_delete = sh_cd_db_shortcodes_by_id( $id );
	sh_cd_db_shortcodes_multisite_delete( $shortcode_before_delete['slug'] );

	// Clear cached version
	sh_cd_cache_delete_by_slug_or_key( $id );

	$slug 	= sh_cd_db_shortcodes_get_slug_by_id( $id );
	$result = $wpdb->delete( $wpdb->prefix . SH_CD_TABLE, [ 'id' => $id ], [ '%d' ] );

	if ( false !== $result ) {
		do_action( 'sh-cd-shortcode-updated', $id, $slug );
	}
	
	return ( false !== $result );
}

/**
 * For a given key value array, look up and return the expected MySQL data formats.
 *
 * @param $data
 *
 * @return array
 */
function sh_cd_db_get_formats( $data ) {

	$lookup = [
		'id' 			=> '%d',
		'slug' 			=> '%s',
		'previous_slug' => '%s',
		'data' 			=> '%s',
		'disabled' 		=> '%d',
		'multisite' 	=> '%d',
		'site_id' 		=> '%d',
		'editor'		=> '%s',
		'header' 		=> '%d',
		'footer' 		=> '%d',
		'device_type' 	=> '%s'
	];

	$formats = [];

	foreach ( $data as $key => $value ) {

		if ( false === empty( $lookup[ $key ] ) ) {
			$formats[] = $lookup[ $key ];
		}
	}

	return $formats;
}

/**
 * Check if the slug already exists
 *
 * @param $slug
 * @param $existing_id
 *
 * @return bool
 */
function sh_cd_slug_is_unique( $slug, $existing_id = NULL ) {

	if ( true === empty( $slug ) ) {
		return false;
	}

	global $wpdb;

	$sql = $wpdb->prepare( 'SELECT count(slug) FROM ' . $wpdb->prefix . SH_CD_TABLE . ' where slug = %s', $slug );

	if ( false === empty( $existing_id ) ) {
		$sql .= $wpdb->prepare( ' and id <> %d', $existing_id );
	}

	$row = $wpdb->get_var( $sql );

	return ( empty( $row ) );
}
