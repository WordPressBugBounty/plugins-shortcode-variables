<?php

defined('ABSPATH') or die('Jog on!');

function sh_cd_pages_your_shortcodes_edit( $action = 'add', $save_result = NULL ) {

    if ( false === in_array( $action, [ 'add', 'edit', 'save' ] ) ) {
	    return;
	}

	sh_cd_permission_check();

	// Saving / Inserting a shortcode?
	if ( false === $save_result ) {
		sh_cd_message_display(  __( 'There was an error saving your shortcode!' , SH_CD_SLUG ), ! $save_result );
	}
	global $wpdb;

	// Load
	$shortcode = ( false === empty( $_GET['id'] ) ) ?
		            sh_cd_db_shortcodes_by_id( (int) $_GET['id'] ) :
		                sh_cd_get_values_from_post( [ 'id', 'slug', 'previous_slug', 'data', 'disabled', 'multisite', 'editor' ] );

	$shortcode['data']  = stripslashes( $shortcode['data'] );

    $current_editor = ( false === empty( $_GET[ 'editor' ] ) ) ? $_GET[ 'editor' ] : $shortcode[ 'editor' ];
    
    if ( true === empty( $current_editor ) ) {
        $current_editor = sh_cd_default_editor_get();
    }
?>
<form method="post" action="<?php echo sh_cd_link_your_shortcodes() . '&action=save'; ?>" class="sh-cd-form">
                                
	<div class="wrap">
		<div id="icon-options-general" class="icon32"></div>
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-3">
				<div id="post-body-content">
					<div class="meta-box-sortables ui-sortable">
                        <?php if ( false === sh_cd_is_premium() ) : ?>
                                <p>
                                    <i class="fa-regular fa-star"></i>
                                    <a href="<?php echo sh_cd_license_upgrade_link(); ?>">
                                        <?php echo __( 'Multi site support and editing slugs are only available for Premium users. Upgrade now.', SH_CD_SLUG ); ?>
                                    </a>
                                </p>
                            <?php endif; ?>
                        <div class="postbox sh-cd-postbox-edit-slug">
							<h3 class="postbox-header">
                                <span>
                                    <?php echo __( 'Slug', SH_CD_SLUG ); ?>
                                </span>
                            </h3>
                            <div style="padding: 0px 15px 0px 15px">
                            <?php

                                if ( true === $save_result ) :

                                    printf('<p>%1$s.<a href="%2$s">%3$s</a>.</p>',
                                        			__( 'Your shortcode has been saved successfully', SH_CD_SLUG ),
                                        			sh_cd_link_your_shortcodes(),
													__( 'Return to all shortcodes', SH_CD_SLUG )
									);

                                else:
                                
                                ?>

                                <input type="hidden" id="id" name="id" value="<?php echo esc_attr( $shortcode['id'] ); ?>" />
                                <?php wp_nonce_field( 'save-shortcode' ); ?>
                                
                                <div class="sh-cd-row">
                                    <input type="text" required class="regular-text sh-cd-slug-validation" size="100" id="slug" name="slug"
                                            placeholder="Enter a name for your shortcode" title="Only letters, numbers, dashes and underscores are allowed."
                                                value="<?php echo esc_attr( $shortcode['slug'] )?>"  <?php if ( false === sh_cd_is_premium() && 'edit' === $action ) { echo 'disabled="disabled"'; } ?> />
                            
                                    <?php $previous_slug = ( false === empty( $shortcode['previous_slug'] ) ) ? $shortcode['previous_slug'] : $shortcode['slug']; ?>
                                    <input type="hidden" id="previous_slug" name="previous_slug" value="<?php echo esc_attr( $previous_slug )?>" />

                                    <p class="sh-cd-shortcode-slug">
                                        <?php
                                            $default_slug = ( 'add' === $action ) ? 'enter-a-name-for-your-shortcode' : $previous_slug;
                                        ?>
                                        <?php echo __( 'Shortcode:', SH_CD_SLUG ); ?><span id="sh-cd-test-shortcode-slug">[<?php echo SH_CD_SHORTCODE; ?> slug="<span id="sh-cd-shortcode-slug-preview"><?php echo esc_html( $default_slug ); ?></span>"]</span>
                                        <i class="far fa-copy sh-cd-copy-trigger <?php if ( 'add' === $action ): ?>sh-cd-hide<?php endif; ?>" data-clipboard-text="[<?php echo SH_CD_SHORTCODE; ?> slug=&quot;<?php echo esc_html( $default_slug ); ?>&quot;]"></i>
                                    </p>            
                                </div>
                            </div>
                        </div>
                        <div class="postbox sh-cd-postbox-edit-content">
                            <h3 class="postbox-header">
                                <span>
                                    <?php echo __( 'Content', SH_CD_SLUG ); ?>
                                </span>
                                <?php sh_cd_editor_change_button( $current_editor ); ?>
                            </h3>
                            <div class="sh-cd-postbox-content">
                                <?php 
                                    if ( 'code' !== $current_editor ) {
                                        wp_editor( $shortcode['data'], 'data', [ 'textarea_name' => 'data' ] );
                                    } else {
                                        printf( '   <textarea id="sh-cd-code-editor" name="data">%s</textarea>
                                                    <script>
                                                        jQuery( document ).ready(function ($) {
                                                            wp.codeEditor.initialize($("#sh-cd-code-editor"), cm_settings);
                                                        });
                                                    </script>', $shortcode['data'] );
                                    }

                                    $premium_icon = sh_cd_display_premium_star();
                                    
                                ?>    
                                <input type="hidden" id="editor" name="editor" value="<?php echo esc_attr( $current_editor ); ?>" />
                                <table class="sh-cd-shortcode-options" width="100%">
                                    <tr>
                                        <th width="100">
                                            <?php echo __( 'Disable?', SH_CD_SLUG ); ?>
                                            <?php echo sh_cd_display_info_tooltip( 'If disabled, no content will appear at the location of the shortcode in the public facing site.'); ?>
                                            <?php echo $premium_icon; ?> 
                                        </th>
                                        <td width="*">
                                            <select id="disabled" name="disabled" <?php if ( false === sh_cd_is_premium() ) { echo 'disabled="disabled"'; } ?>>
                                                <option value="0" <?php selected( $shortcode['disabled'], 0 ); ?>><?php echo __( 'No', SH_CD_SLUG ); ?></option>
                                                <option value="1" <?php selected( $shortcode['disabled'], 1 ); ?>><?php echo __( 'Yes', SH_CD_SLUG ); ?></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><?php echo __( 'Global?', SH_CD_SLUG ); ?> 
                                            <?php echo sh_cd_display_info_tooltip( "Enable this to make the shortcode available across all sites in your multisite network. Note: Global shortcodes override local ones with the same slug, and conflicts may arise if slugs aren't unique. Updates may take up to 30 seconds to apply."); ?> 
                                            <?php echo $premium_icon; ?>    
                                        </th>
                                        <td>
                                            <select id="multisite" name="multisite" <?php if ( false === sh_cd_is_premium() ) { echo 'disabled="disabled"'; } ?>>
                                                <option value="0" <?php selected( $shortcode['multisite'], 0 ); ?>><?php echo __( 'No', SH_CD_SLUG ); ?></option>
                                                <option value="1" <?php selected( $shortcode['multisite'], 1 ); ?>><?php echo __( 'Yes', SH_CD_SLUG ); ?></option>
                                            </select>
                                        </td>
                                    </tr>
                                </table>  
                                <div class="sh-cd-button-row sh-cd-border-top">
                                    <a class="comment-submit button" href="<?php echo sh_cd_link_your_shortcodes(); ?>"><?php echo __( 'Cancel', SH_CD_SLUG ); ?></a>
                                    <input name="submit_button" type="submit" value="Save Shortcode" class="comment-submit button button-primary sh-cd-button">
                                </div>
                            
                        <?php endif; ?>
                    </div>
                </div>
                </div>
			    </div>
			    </div>
			
		</div>
	</div>
    </form>
	<?php
}

 /**
  * Button for toggling editor
  *
  * @return void
  */
 function sh_cd_editor_change_button( $current_editor = null ) {

    if ( true === empty( $current_editor ) ) {
        $current_editor = sh_cd_default_editor_get();
    }

    $editor = ( 'tinymce' === $current_editor ) ? 'code' : 'tinymce';
    $text   = ( 'tinymce' === $current_editor ) ? __( 'Change to HTML Editor', SH_CD_SLUG ) : __( 'Change to WordPress Editor', SH_CD_SLUG );
    $icon   = ( 'tinymce' === $current_editor ) ? 'fa-solid fa-code' : 'fa-brands fa-wordpress';

    $url = add_query_arg( [ 'editor' => $editor ], sh_cd_get_current_url() );
   
    echo sprintf( '<a type="button" class="button sh-cd-button sh-cd-button-editor-select sh-cd-button-%2$s" href="%1$s" data-new-editor="%2$s"><i class="%3$s"></i> %4$s</a>', 
                    esc_url( $url ), 
                    $editor,
                    esc_html( $icon ),
                    esc_html( $text ));
 }

/**
 * If using code editor, load the code editor
 */
add_action( 'admin_enqueue_scripts', function() {

    if ( false === sh_cd_is_snippet_shortcodes_admin_page() ) {
        return;
    }  

    // Types found here: https://docs.classicpress.net/reference/functions/wp_enqueue_code_editor/
    $cm_settings['codeEditor'] = wp_enqueue_code_editor( [ 'type' => 'text/html' ] );
    wp_localize_script( 'jquery', 'cm_settings', $cm_settings) ;
 
    wp_enqueue_script( 'wp-theme-plugin-editor' );
    wp_enqueue_style( 'wp-codemirror' );
});