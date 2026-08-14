<?php

	defined('ABSPATH') or die("Jog on!");

	/**
	 * Register the "Snippet Shortcode" block
	 */
	function sh_cd_gutenberg_register_block() {

		wp_register_script(
			'sh-cd-gutenberg-block',
			plugins_url( '../assets/js/gutenberg-block.js', __FILE__ ),
			[ 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-server-side-render', 'wp-i18n' ],
			SH_CD_PLUGIN_VERSION,
			true
		);

		register_block_type( 'sh-cd/shortcode', [
			'title'           => __( 'Snippet Shortcode', SH_CD_SLUG ),
			'description'     => __( 'Insert one of your own shortcodes or a premade shortcode.', SH_CD_SLUG ),
			'category'        => 'widgets',
			'icon'            => 'shortcode',
			'attributes'      => [
				'slug' => [
					'type'    => 'string',
					'default' => '',
				],
			],
			'editor_script'   => 'sh-cd-gutenberg-block',
			'render_callback' => 'sh_cd_gutenberg_render_block',
		] );
	}
	add_action( 'init', 'sh_cd_gutenberg_register_block' );

	/**
	 * Render the block on the front-end (and for the editor's ServerSideRender preview)
	 *
	 * @param $attributes
	 *
	 * @return string
	 */
	function sh_cd_gutenberg_render_block( $attributes ) {

		if ( true === empty( $attributes[ 'slug' ] ) ) {
			return '';
		}

		return sh_cd_shortcode_render( [ 'slug' => sanitize_text_field( $attributes[ 'slug' ] ) ] );
	}

	/**
	 * Supply the block editor with the list of shortcodes to pick from
	 */
	function sh_cd_gutenberg_enqueue_editor_assets() {

		$config = [
			'yourShortcodes'    => sh_cd_gutenberg_options_your_shortcodes(),
			'premadeShortcodes' => sh_cd_gutenberg_options_premade_shortcodes(),
			'manageUrl'         => admin_url( 'admin.php?page=sh-cd-shortcode-variables-your-shortcodes' ),
			'i18n'              => [
				'placeholder-title'      => __( 'Snippet Shortcode', SH_CD_SLUG ),
				'placeholder-text'       => __( 'Select one of your own shortcodes, or a premade shortcode.', SH_CD_SLUG ),
				'your-shortcodes-label'  => __( 'Your shortcodes', SH_CD_SLUG ),
				'premade-shortcodes-label' => __( 'Premade shortcodes', SH_CD_SLUG ),
				'no-shortcodes'          => __( 'You haven\'t created any shortcodes yet.', SH_CD_SLUG ),
				'manage-shortcodes'      => __( 'Manage your shortcodes', SH_CD_SLUG ),
				'change-shortcode'       => __( 'Change shortcode', SH_CD_SLUG ),
				'select-option'          => __( 'Select a shortcode…', SH_CD_SLUG ),
				'premium-label'          => __( 'Premium', SH_CD_SLUG ),
			],
		];

		wp_localize_script( 'sh-cd-gutenberg-block', 'shCdGutenberg', $config );
	}
	add_action( 'enqueue_block_editor_assets', 'sh_cd_gutenberg_enqueue_editor_assets' );

	/**
	 * Build the "your shortcodes" option list for the block editor
	 *
	 * @return array
	 */
	function sh_cd_gutenberg_options_your_shortcodes() {

		$shortcodes = sh_cd_db_shortcodes_all_enabled();
		$slugs      = wp_list_pluck( $shortcodes, 'slug' );

		$options = [];

		foreach ( $slugs as $slug ) {
			$options[] = [ 'value' => $slug, 'label' => $slug, 'premium' => false ];
		}

		return $options;
	}

	/**
	 * Build the "premade shortcodes" option list for the block editor
	 *
	 * @return array
	 */
	function sh_cd_gutenberg_options_premade_shortcodes() {

		$presets = sh_cd_presets_both_lists();

		$options = [];

		foreach ( $presets as $slug => $data ) {
			$options[] = [ 'value' => $slug, 'label' => $slug, 'premium' => false === empty( $data[ 'premium' ] ) ];
		}

		return $options;
	}
