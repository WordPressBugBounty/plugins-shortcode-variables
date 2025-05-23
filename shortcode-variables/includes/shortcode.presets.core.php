<?php

defined('ABSPATH') or die("Jog on!");

/**
 * Determine if the slug belongs to a preset
 * @param $slug
 */
function sh_cd_is_preset( $slug ) {

	$slug = sh_cd_prep_slug( $slug );

	// Free preset?
	if ( true === array_key_exists( $slug, sh_cd_shortcode_presets_free_list() ) ) {
		return 'free';
	}

	// Premium preset?
	if ( true === array_key_exists( $slug, sh_cd_shortcode_presets_premium_list() ) ) {
		return 'premium';
	}

	return false;
}

/**
 * Remove old references to the "sc-" prefix
 */
function sh_cd_prep_slug( $slug ) {

	$slug = str_replace( 'sc-', '', $slug );

	return $slug;
}

/**
 * Return all free and premium premade shortcodes
 *
 * @return array
 */
function sh_cd_presets_both_lists() {

	$both = array_merge( sh_cd_shortcode_presets_free_list(), sh_cd_shortcode_presets_premium_list() );

	ksort( $both );

	return $both;
}

/**
 * For a given preset, look up the class information, if valid, initiate the class and render
 *
 * @param $args
 *
 * @return string
 */
function sh_cd_shortcode_presets_render( $args ) {

	$preset = sh_cd_shortcode_presets_fetch( $args[ 'slug' ] );

	// Not a preset?
	if ( false === $preset ) {
		return '';
	}

	// Is this a premium shortcode but no license?
	if ( 'premium' === $preset['sh-cd-type'] && false === sh_cd_is_premium() ) {
		return sprintf( '<p><strong>%s</strong> %s. <a href="%s">%s</a>.<p>',
						__( 'Ooops!', SH_CD_SLUG ),
						__('Unfortunately this is a Premium shortcode. You need to upgrade "Snippet Shortcodes" to use it', SH_CD_SLUG ),
						sh_cd_license_upgrade_link(),
						__('Upgrade now', SH_CD_SLUG )
		);
	}

	$class_name = 'SV_' . $preset[ 'class' ];

	if ( true === class_exists( $class_name ) ) {

		$shortcode = new $class_name();

		// Any plugin arguments?
		if ( false === empty( $preset['args'] ) ) {
			$args = array_merge( $args, $preset['args'] );
		}

		$shortcode->set_arguments( $args );

		$shortcode->init();

		return $shortcode->sanitised();
	}

	return '';
}

/**
 * For a given slug, fetch the preset information regarding it.
 *
 * @param $slug
 *
 * @return bool
 */
function sh_cd_shortcode_presets_fetch( $slug ) {

	$slug = sh_cd_prep_slug( $slug );

	$free_presets = sh_cd_shortcode_presets_free_list();

	// Free preset?
	if ( true === array_key_exists( $slug, $free_presets ) ) {

		$preset = $free_presets[ $slug ];
		$preset['sh-cd-type'] = 'free';

		return $preset;
	}

	$premium_presents = sh_cd_shortcode_presets_premium_list();

	// Premium preset?
	if ( true === array_key_exists( $slug, $premium_presents ) ) {

		$preset = $premium_presents[ $slug ];
		$preset['sh-cd-type'] = 'premium';

		return $preset;
	}

	return false;

}