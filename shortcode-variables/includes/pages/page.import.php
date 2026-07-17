<?php

defined('ABSPATH') or die('Naw ya dinnie!');

/**
 * Stream the CSV export before any admin page HTML is output. Hooked on admin_init
 * rather than handled in the page callback itself, since by the time WordPress calls
 * the page callback it has already sent the admin header/menu chrome, making it too
 * late to send Content-Disposition/Content-Type headers for a file download.
 */
function sh_cd_admin_page_import_maybe_export() {

	if ( false === isset( $_GET[ 'page' ] ) || 'sh-cd-import' !== $_GET[ 'page' ] || 'export' !== ( $_GET[ 'mode' ] ?? '' ) ) {
		return;
	}

	sh_cd_permission_check();

	if ( false === sh_cd_is_premium() ) {
		return;
	}

	check_admin_referer( 'sh_cd_export_csv' );

	$filename = 'snippet-shortcodes-export-' . gmdate( 'Y-m-d' ) . '.csv';

	nocache_headers();
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=' . $filename );

	echo sh_cd_export_csv();
	exit;
}
add_action( 'admin_init', 'sh_cd_admin_page_import_maybe_export' );

function sh_cd_admin_page_import() {

	sh_cd_permission_check();

	wp_enqueue_media();

	$importing 	= false;
	$output		= '';

	if ( true === sh_cd_is_premium() &&
			false === empty( $_POST[ 'attachment-id' ] ) ){

		$importing 	= true;
		$dry_run	= ( false === empty( $_POST[ 'dry-run' ] ) );
		$output 	= sh_cd_import_csv( $_POST[ 'attachment-id' ], $dry_run );
	}

    ?>
    <div class="wrap sh-cd-csv-import ws-ls-admin-page">
    <div id="poststuff">
        <div id="post-body" class="metabox-holder">
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <?php
						if ( false === sh_cd_is_premium() ) {
							sh_cd_display_pro_upgrade_notice();
						}
                    ?>
                   <div class="postbox">
				  		<h3 class="postbox-header">
							<span>
								<?php echo __( 'Import CSV', SH_CD_SLUG ); ?>
							</span>
                        </h3>
					    <div class="inside">
                        	<?php if ( false === $importing ): ?>
								<div class="sh-cd-form-row">
									<p>
										<?php echo __( 'Please select a CSV file to import one or more shortcodes into your collection.', SH_CD_SLUG ); ?>
										<a href="https://yeken.gitbook.io/snippet-shortcodes/features/csv-import" rel="noopener noreferrer" target="_blank"><?php echo __( 'Read more about CSV imports and the required format', SH_CD_SLUG ); ?>.</a>
									</p>
									<input id="select_csv" type="button" class="button sh-cd-button" value="<?php echo __( 'Select CSV file', SH_CD_SLUG ); ?>" />
									<br />
								</div>
								<div class="sh-cd-hide sh-cd-import-selected" id="selected-form" >
									<form action="<?php echo admin_url( 'admin.php?page=sh-cd-import&mode=import'); ?>" method="post">
										<div class="sh-cd-form-row">
											<label for="attachment-path"><?php echo __( 'Selected file:', SH_CD_SLUG ); ?></label>
											<input type='text' name='attachment-path' id='attachment-path' value='' class="widefat" disabled="disabled" />
											<input type='hidden' name='attachment-id' id='attachment-id' value='' />
										</div>
										<div class="sh-cd-form-row">
											<input type='checkbox' name='dry-run' id='dry-run' value='yes' />
											<label for="dry-run"><?php echo __( 'Dry run mode. This will do basic tests on the file without performing an import.', SH_CD_SLUG ); ?></label>
										</div>
										<div class="sh-cd-form-row">
											<input type="submit" class="button button-primary sh-cd-button" value="<?php echo __( 'Import CSV', SH_CD_SLUG ); ?>" <?php if ( false === sh_cd_is_premium() ) { echo 'disabled="disabled"'; } ?> />
										</div>
									</form>
								</div>
							<?php else: ?>
								<p><strong><?php echo __( 'Output:', SH_CD_SLUG ); ?></strong></p>
								<textarea class="widefat" rows="20" cols="100"><?php echo esc_html( $output ); ?></textarea>
							<?php endif; ?>
                        </div>
                    </div>
                   <div class="postbox">
					  		<h3 class="postbox-header">
								<span>
									<?php echo __( 'Export to CSV', SH_CD_SLUG ); ?>
								</span>
	                        </h3>
						    <div class="inside">
	                        	<div class="sh-cd-form-row">
									<p><?php echo __( 'Download all of your shortcodes as a CSV file, in the same format used for import.', SH_CD_SLUG ); ?></p>
									<a class="button button-primary sh-cd-button" <?php if ( false === sh_cd_is_premium() ) { echo 'disabled="disabled"'; } ?> href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=sh-cd-import&mode=export' ), 'sh_cd_export_csv' ) ); ?>"><?php echo __( 'Export CSV', SH_CD_SLUG ); ?></a>
								</div>
	                        </div>
	                    </div>
                </div>
            </div>
        </div>
        <br class="clear">
    </div>
	<script>
		jQuery( document ).ready(function ($) {

			// CSV import for
			let file_frame;

			$( '#select_csv').on('click', function( event ){

				event.preventDefault();

				<?php if ( false === sh_cd_is_premium() ) : ?>
					alert( '<?php echo __( "Please upgrade to bulk import shortcodes via CSV.", SH_CD_SLUG ); ?>' );
					return;
				<?php else: ?>

					// If the media frame already exists, reopen it.
					if ( file_frame ) {

						// Open frame
						file_frame.open();
						return;
					}

					// Create the media frame.
					file_frame = wp.media.frames.file_frame = wp.media({
						title: '<?php echo __( "Select a CSV", SH_CD_SLUG ); ?>',
						button: {
							text: '<?php echo __( "Use this file", SH_CD_SLUG ); ?>',
						},
						library : {
							type : ['application/csv', 'text/csv'],
						},
						multiple: false
					});

					// When an image is selected, run a callback.
					file_frame.on( 'select', function() {

						attachment = file_frame.state().get('selection').first().toJSON();

						$( '#attachment-id' ).val( attachment.id );
						$( '#attachment-path' ).val( attachment.url );
						$( '#selected-form' ).removeClass( 'sh-cd-hide' );

					});

					file_frame.open();

				<?php endif; ?>
			});
		});
	</script>
    <?php
}
