<?php

defined('ABSPATH') or die('Jog on!');

/**
 * Determine which page to show
 */
function sh_cd_pages_your_shortcodes() {

    $action = ( false === empty( $_GET['action'] ) ) ? $_GET['action'] : NULL;

	$action = ( 'save' === $action && true === empty( $_POST ) ) ? '' : $action;

	$save_result = NULL;

	// Do we have a save event?
	if ( 'save' === $action ) {

		check_admin_referer( 'save-shortcode' );

		$save_result = false;

		if ( false === empty( $_POST[ 'id'] ) ||
			  	false === sh_cd_reached_free_limit() ) {
			$save_result = sh_cd_shortcodes_save_post();

			// Success?
			if ( true === $save_result ) {
				$action = 'list';
			}
		}
	}

    switch ( $action ) {

        case 'add':
        case 'edit':
		case 'save':
	        sh_cd_pages_your_shortcodes_edit( $action, $save_result );
            break;
        default:
	        sh_cd_pages_your_shortcodes_list( $action, $save_result );
    }

}

/**
 * Display all shortcodes
 * @param null $action
 * @param null $save_result
 */
function sh_cd_pages_your_shortcodes_list($action = NULL, $save_result = NULL) {

	sh_cd_permission_check();

	// Cloning a shortcode?
    if ( 'clone' === $action && false === empty( $_GET['id'] ) ) {
	    sh_cd_clone( (int) $_GET['id'] );
    }

	if ( true == $save_result ) {
		sh_cd_message_display( __( 'Your shortcode has been saved!', SH_CD_SLUG ) );
	}

	?>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"></div>
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-3">
				<div id="post-body-content">
                    <div class="meta-box-sortables ui-sortable">
                        <div class="postbox">
							<h3 class="postbox-header">
								<span>
									<?php echo __( 'Your Snippet Shortcodes', SH_CD_SLUG ); ?>
								</span>
                        	</h3>
                            <div style="padding: 0px 15px 0px 15px">
								<table width="100%" style="margin-top: 10px">
									<tr>
										<td class="yk-ss-shortcode-stats">
											<?php

												if ( false === sh_cd_is_premium() ) {
													printf( '%s %d %s %d %s. <i class="fa-regular fa-star"></i> <a href="%s">%s</a>',
														__( 'Used', SH_CD_SLUG ),
														sh_cd_db_shortcodes_count(),
														__( 'of', SH_CD_SLUG ),
														sh_cd_get_free_limit(),
														__( 'shortcodes', SH_CD_SLUG ),
														sh_cd_license_upgrade_link(),
														__( 'Go unlimited!', SH_CD_SLUG )
													);
												}

											?>
										</td>
										<td align="right">
											<?php
												if ( false === sh_cd_is_premium() ) {
													sh_cd_upgrade_button( 'sh-cd-hide', sh_cd_license_upgrade_link() );
												}

												sc_cd_display_add_button();
											?>
										</td>
									</tr>
								</table>
                                <p style="text-align: right">

                                </p>
								
                                <table class="widefat sh-cd-table" width="100%">
                                    <tr class="row-title">
                                        <th class="row-title" width="20%"><?php echo __( 'Shortcode', SH_CD_SLUG ); ?></th>
                                        <th width="*"><?php echo __( 'Content', SH_CD_SLUG ); ?></th>
                                        <th width="60px" align="middle"><?php echo __( 'Global', SH_CD_SLUG ); ?></th>
                                        <th width="60px" align="middle"><?php echo __( 'Enabled', SH_CD_SLUG ); ?></th>
                                        <th width="70px" align="middle"><?php echo __( 'Options', SH_CD_SLUG ); ?></th>
                                    </tr>
                                    <?php

									printf(	'<tr class="sh-cd-hide" id="sh-cd-add-inline">
												<td>
													<input type="text" maxlength="100" placeholder="%1$s" class="sh-cd-slug-validation" id="sh-cd-add-inline-slug" title="Only letters, numbers, dashes and underscores are allowed." />
													 <p class="sh-cd-shortcode-slug sh-cd-hide">
														<span id="sh-cd-test-shortcode-slug">[%6$s slug="<span id="sh-cd-shortcode-slug-preview"></span>"]</span>
														<i class="far fa-copy sh-cd-copy-trigger" data-clipboard-text="[%6$s: slug=&quot;&quot;]"></i>
													</p>  	
												</td>
												<td align="right">
													<textarea class="large-text inline-text-shortcode" id="sh-cd-add-inline-text"></textarea>
													<label for="sh-cd-add-inline-clear" >%3$s </label><input type="checkbox" id="sh-cd-add-inline-clear" value="true" checked="checked" />
												</td>
												<td align="middle"><input type="checkbox" id="sh-cd-add-inline-global" value="true" /></td>
												<td align="middle"><input type="checkbox" id="sh-cd-add-inline-enabled" value="true" checked="checked" /></td>
												<td width="100">
													<a class="button button-small sh-cd-inline-add-button" id="sh-cd-add-button" %5$s><i class="fas fa-save"></i> %2$s</a>
												</td>
											</tr>
											<tr class="sh-cd-hide" id="sh-cd-add-inline-results">
												<td></td>
												<td cospan="4">
													<p>%4$s</p>
												</td>
											</tr>

											',
											__( 'Slug', SH_CD_SLUG ),
											__( 'Add', SH_CD_SLUG ),
											__( 'Clear form after save', SH_CD_SLUG ),
											__( 'Your Shortcode has been added. Please refresh the page to edit it.', SH_CD_SLUG ),
											( false === sh_cd_is_premium() ) ? ' disabled="disabled"' : '',
											SH_CD_SHORTCODE
									);

                                    $current_shortcodes = sh_cd_db_shortcodes_all();

                                    if ( false === empty( $current_shortcodes ) ) {

                                        $class 			= '';
                                        $link 			= sh_cd_link_your_shortcodes();
                                        $i 				= 0;
                                        $limit_reached 	= sh_cd_reached_free_limit();

                                        foreach ( $current_shortcodes as $shortcode ) {

                                            $class = ($class == 'alternate') ? '' : 'alternate';

                                            $id = (int) $shortcode['id'];

                                            printf(	'<tr class="%1$s yk-ss-row-%3$s sh-cd-shortcode-row" id="sh-cd-row-%8$s">
														<td><a href="%2$s" class="slug-link">[%4$s slug="%3$s"]</a> <i class="far fa-copy sh-cd-copy-trigger sh-cd-tooltip" data-clipboard-text="[%4$s slug=&quot;%3$s&quot;]" title="%20$s"></i></td>
														<td align="right">
															<textarea class="large-text inline-text-shortcode sh-cd-toggle-%13$s" id="sh-cd-text-area-%8$d" data-id="%8$d" %13$s>%5$s</textarea>
															<a class="button button-small sh-cd-inline-save-button sh-cd-toggle-%13$s" id="sh-cd-save-button-%8$d" data-id="%8$d" %13$s><i class="fas fa-save"></i> %11$s</a>
														</td>
														<td align="middle"><a class="button button-small toggle-multisite sh-cd-toggle-%13$s sh-cd-tooltip" id="sc-cd-multisite-%8$s" data-id="%8$s" %13$s title="%19$s"><i class="fa-solid %10$s"></i></a></td>
														<td align="middle"><a class="button button-small toggle-disable sh-cd-toggle-%13$s sh-cd-tooltip" id="sc-cd-toggle-%8$s" data-id="%8$s" %13$s title="%18$s"><i class="fa-solid %6$s"></i></a></td>
														<td width="100">
															<a class="button button-small sh-cd-toggle-%13$s sh-cd-tooltip" %13$s href="%9$s" title="%17$s"><i class="far fa-clone"></i></a>
															<a class="button button-small edit-shortcode sh-cd-tooltip" href="%2$s" title="%15$s"><i class="far fa-edit"></i></a>
															<a id="delete-%8$s" class="button button-small delete-shortcode sh-cd-tooltip" data-id="%8$s" title="%16$s"><i class="fas fa-trash-alt"></i></a>
														</td>
													</tr>',
													$class,
													$link . '&action=edit&id=' . $id,
													esc_html( $shortcode['slug'] ),
													SH_CD_SHORTCODE,
													( true === sh_cd_is_premium() ) ? esc_html( stripslashes( $shortcode['data'] ) ) : __( 'Upgrade for inline editing and toggles.', SH_CD_SLUG ),
													( 1 === (int) $shortcode['disabled'] ) ? 'fa-times' : 'fa-check',
													$link . '&action=delete&id=' . $id,
													$id,
													( true === sh_cd_is_premium() ) ? $link . '&action=clone&id=' . $id : sh_cd_license_upgrade_link(),
													( 1 === (int) $shortcode['multisite'] ) ? 'fa-check' : 'fa-times',
													__( 'Save', SH_CD_SLUG ),
													__( 'Are you sure you want to delete this shortcode?', SH_CD_SLUG ),
													( false === sh_cd_is_premium() ) ? 'disabled' : '',
													( true === $limit_reached && $i > sh_cd_get_free_limit() ) ? 'disabled' : '',
													__( 'Use the full Visual or Code editor to edit this shortcode.', SH_CD_SLUG ),
													__( 'Permanently delete and remove this shortcode.', SH_CD_SLUG ),
													__( 'Clone this shortcode to create an identical copy for editing', SH_CD_SLUG ),
													__( 'Enable or disable a shortcode. When enabled, the shortcode will not be rendered on the website\'s public facing side.', SH_CD_SLUG ),
													__( 'Enable this shortcode for use across all sites within your WordPress multisite network.', SH_CD_SLUG ),
													__( 'Copy to clipboard', SH_CD_SLUG )
                                            );

                                            $i++;
                                        }
                                    }
                                    else {
                                        printf( '<tr><td colspan="5" align="center">%1$s. <a href="%2$s">%3$s</a></td></tr>',
												__( 'You haven\'t created any shortcodes yet', SH_CD_SLUG ),
												sh_cd_link_your_shortcodes_add(),
												__( 'Add one now!', SH_CD_SLUG )
										);
                                    }
                                    ?>
                                </table>
								<br clear="all" />
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Render button for adding a shortcode
 *
 * @param bool $show_quick_add
 */
function sc_cd_display_add_button( $show_quick_add = true ) {

	$limit_reached = sh_cd_reached_free_limit();

	if ( true === $show_quick_add ) {
		printf( '&nbsp;<a class="button-primary sh-cd-button button-add-inline yk-ss-button-add-quick-editor"><i class="fa-solid fa-plus"></i> %1$s</a>', __( 'Quick Add', SH_CD_SLUG ) );
	}

	printf( '&nbsp;<a class="button-primary sh-cd-button yk-ss-button-add-full-editor sh-cd-tooltip" title="%3$s" href="%1$s"><i class="fa-solid fa-file-code"></i> %2$s</a>',
		( false === $limit_reached ) ? sh_cd_link_your_shortcodes_add() : sh_cd_license_upgrade_link(),
		( false === $limit_reached ) ? __( 'Add via full editor', SH_CD_SLUG ) : __( 'You must upgrade to add more shortcodes', SH_CD_SLUG ),
		__( 'Use the full Visual or Code editor to add a new shortcode.', SH_CD_SLUG )
	);
}
