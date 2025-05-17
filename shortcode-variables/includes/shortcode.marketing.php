<?php

/**
 * Render text file for promo
 */
function sh_cd_shortcode_render_text() {

	$output = '**Premium Shortcodes**' . PHP_EOL;

	$shortcodes = sh_cd_shortcode_presets_premium_list();

	foreach ( $shortcodes as $key => $data ) {
		$output .= sprintf('- %s - %s' . PHP_EOL , $key, $data['description'] );
	}

	$output .= '**Free Shortcodes**' . PHP_EOL;

	$shortcodes = sh_cd_shortcode_presets_free_list();

	foreach ( $shortcodes as $key => $data ) {
		$output .= sprintf('- %s - %s' . PHP_EOL , $key, $data['description'] );
	}

	return $output;

}
add_shortcode( 'sv-promo', 'sh_cd_shortcode_render_text' );

/**
 * Shortcode to render free shortcodes (more for promo purposes)
 *
 * @return string
 */
function sh_cd_shortcode_render_table_free() {

	return sh_cd_display_premade_shortcodes( 'free' );

}
add_shortcode( 'sv-promo-free', 'sh_cd_shortcode_render_table_free');

/**
 * Shortcode to render premium shortcodes (more for promo purposes)
 *
 * @return string
 */
function sh_cd_shortcode_render_table_premium() {

	return sh_cd_display_premade_shortcodes( 'premium' );

}
add_shortcode( 'sv-promo-premium', 'sh_cd_shortcode_render_table_premium');

/**
 * Shortcode to render premium shortcodes (more for promo purposes)
 *
 * @return string
 */
function sh_cd_shortcode_version() {

	return SH_CD_PLUGIN_VERSION;

}
add_shortcode( 'sv-version', 'sh_cd_shortcode_version');

/**
 * Shortcode to render premium features
 *
 * @return string
 */
function sh_cd_shortcode_render_table_premium_features() {

	return sh_cd_display_premium( true );

}
add_shortcode( 'sv-promo-premium-features', 'sh_cd_shortcode_render_table_premium_features');

/**
 * Return Premode shortcodes as a list
 *
 * @return string
 */
function sh_cd_display_premade_premium_shortcodes_as_ul() {

	$shortcodes	= sh_cd_shortcode_presets_premium_list();
	$html		= '<ul class="sh-cd-promo-list">';

	foreach ( $shortcodes as $key => $data ) {
		$html .= sprintf( '<li><span>%s</span> - %s</li>', $shortcode = '[' . SH_CD_SHORTCODE. ' slug="' . $key . '"]', wp_kses_post( $data['description'] ) );
	}

	$html .= '</ul>';

	return $html;
}
add_shortcode( 'sv-promo-premium-shortcodes-as-list', 'sh_cd_display_premade_premium_shortcodes_as_ul');

/**
 * Shortcode to render all shortcodes (more for promo purposes)
 *
 * @return string
 */
function sh_cd_shortcode_render_table_all() {

	return sh_cd_display_premade_shortcodes();

}
add_shortcode( 'sv-promo-all', 'sh_cd_shortcode_render_table_all');

 /**
 * Fetch license price
 *
 * @return float|null
 */
function sh_cd_license_price() {

	$price = yeken_license_price( 'sv-premium' );

	return ( false === empty( $price ) ) ? $price : '(Error fetching price)';
}

if ( false === function_exists( 'yeken_license_api_fetch_licenses' ) ) {

	/**
	 * Call out to YeKen API for license prices
	 */
	function yeken_license_api_fetch_licenses() {

		if ( $cache = get_transient( 'yeken_api_prices' ) ) {
			return $cache;
		}

		$response = wp_remote_get( 'https://shop.yeken.uk/wp-json/yeken/v1/license-prices/' );

		// All ok?
		if ( 200 === wp_remote_retrieve_response_code( $response ) ) {

			$body = wp_remote_retrieve_body( $response );

			if ( false === empty( $body ) ) {

				$body = json_decode( $body, true );
				set_transient( 'yeken_api_prices', $body, 216000 ); // Cache for 6 hours

				return $body;
			}
		}

		return NULL;
	}

	/**
	 * Fetch a certain product price
	 * @param $sku
	 * @param string $type
	 */
	function yeken_license_price( $sku, $type = 'yearly' ) {

		$licenses = yeken_license_api_fetch_licenses();

		return ( false === empty( $licenses[ $sku ][ $type ] ) ) ? $licenses[ $sku ][ $type ] : NULL;
	}

	/**
	 * Render out license prices
	 *
	 * @param $args
	 * @return mixed|string
	 */
	function yeken_license_shortcode( $args ) {

		$args = wp_parse_args( $args, [ 'sku' => 'sv-premium', 'type' => 'yearly', 'prefix' => '&pound;' ] );

		$price = yeken_license_price( $args[ 'sku' ], $args[ 'type' ] );

		if ( false === empty( $price ) ) {
			return sprintf( '%s%d', esc_html(  $args[ 'prefix' ] ), $price );
		}

		return '';
	}
	add_shortcode( 'yeken-license-price', 'yeken_license_shortcode' );

}