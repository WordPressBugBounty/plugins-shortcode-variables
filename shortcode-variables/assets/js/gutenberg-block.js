( function( wp ) {

	var el = wp.element.createElement;
	var Fragment = wp.element.Fragment;
	var registerBlockType = wp.blocks.registerBlockType;
	var useBlockProps = wp.blockEditor.useBlockProps;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var BlockControls = wp.blockEditor.BlockControls;
	var Placeholder = wp.components.Placeholder;
	var SelectControl = wp.components.SelectControl;
	var PanelBody = wp.components.PanelBody;
	var ToolbarGroup = wp.components.ToolbarGroup;
	var ToolbarButton = wp.components.ToolbarButton;
	var ExternalLink = wp.components.ExternalLink;
	var ServerSideRender = wp.serverSideRender.default || wp.serverSideRender;

	/**
	 * Build the grouped options for the "select a shortcode" control
	 */
	function sh_cd_gutenberg_build_options( config ) {

		var options = [ { value: '', label: config.i18n[ 'select-option' ] } ];

		config.yourShortcodes.forEach( function( option ) {
			options.push( { value: option.value, label: option.label } );
		} );

		config.premadeShortcodes.forEach( function( option ) {
			options.push( { value: option.value, label: option.premium ? option.label + ' (' + config.i18n[ 'premium-label' ] + ')' : option.label } );
		} );

		return options;
	}

	/**
	 * Picker shown when no shortcode has been selected yet
	 */
	function sh_cd_gutenberg_picker( config, slug, onChange ) {

		var body = [];

		if ( 0 === config.yourShortcodes.length ) {

			body.push( el( 'p', { key: 'empty' },
				config.i18n[ 'no-shortcodes' ] + ' ',
				el( ExternalLink, { key: 'link', href: config.manageUrl }, config.i18n[ 'manage-shortcodes' ] )
			) );
		}

		body.push( el( SelectControl, {
			key: 'picker',
			label: config.i18n[ 'select-option' ],
			value: slug,
			options: sh_cd_gutenberg_build_options( config ),
			onChange: onChange,
		} ) );

		return el( Placeholder, {
			icon: 'shortcode',
			label: config.i18n[ 'placeholder-title' ],
			instructions: config.i18n[ 'placeholder-text' ],
		}, body );
	}

	registerBlockType( 'sh-cd/shortcode', {

		title: window.shCdGutenberg.i18n[ 'placeholder-title' ],
		description: window.shCdGutenberg.i18n[ 'placeholder-text' ],
		category: 'widgets',
		icon: 'shortcode',
		attributes: {
			slug: { type: 'string', default: '' },
		},

		edit: function( props ) {

			var config = window.shCdGutenberg;
			var slug = props.attributes.slug;
			var setSlug = function( newSlug ) {
				props.setAttributes( { slug: newSlug } );
			};
			var blockProps = useBlockProps();

			if ( '' === slug ) {
				return el( 'div', blockProps, sh_cd_gutenberg_picker( config, slug, setSlug ) );
			}

			return el( Fragment, {},
				el( BlockControls, {},
					el( ToolbarGroup, {},
						el( ToolbarButton, { onClick: function() { setSlug( '' ); } }, config.i18n[ 'change-shortcode' ] )
					)
				),
				el( InspectorControls, {},
					el( PanelBody, { title: config.i18n[ 'placeholder-title' ] },
						el( SelectControl, {
							label: config.i18n[ 'select-option' ],
							value: slug,
							options: sh_cd_gutenberg_build_options( config ),
							onChange: setSlug,
						} )
					)
				),
				el( 'div', blockProps,
					el( ServerSideRender, { block: 'sh-cd/shortcode', attributes: props.attributes } )
				)
			);
		},

		save: function() {
			return null;
		},

	} );

} )( window.wp );
