<?php

defined('ABSPATH') or die('Jog on!');

function sh_cd_help_page() {

	?>

    <div class="wrap ws-ls-admin-page">

	<div id="icon-options-general" class="icon32"></div>

	<div id="poststuff">

		<div id="post-body" class="metabox-holder columns-3">

			<!-- main content -->
			<div id="post-body-content">

				<div class="meta-box-sortables ui-sortable">

                    <div class="postbox">
						<h3 class="postbox-header">
							<span>
								<?php echo __( 'Custom modifications / web development', SH_CD_SLUG ); ?>
							</span>
                        </h3>
                        <div style="padding: 0px 15px 0px 15px">
	                        <?php sh_cd_custom_notification_html(); ?>
                        </div>
                    </div>
					<div class="postbox">
						<h3 class="postbox-header">
							<span>
								<?php echo __( 'Documenation and Release notes', SH_CD_SLUG ); ?>
							</span>
                        </h3>
						<div style="padding: 0px 15px 0px 15px">
							<p><?php echo __( 'You can find detailed documentation for this plugin at our site:', SH_CD_SLUG ); ?></p>
							<p><a href="https://snippetshortcodes.yeken.uk/" rel="noopener noreferrer"  class="button"  target="_blank"><?php echo __( 'Main website', SH_CD_SLUG ); ?></a>
								<a href="https://yeken.gitbook.io/snippet-shortcodes" rel="noopener noreferrer"  class="button"  target="_blank"><?php echo __( 'View Documentation', SH_CD_SLUG ); ?></a>
								<a href="https://wordpress.org/plugins/shortcode-variables/#developers"  class="button"  rel="noopener noreferrer" target="_blank"><?php echo __( 'Release Notes', SH_CD_SLUG ); ?></a>
							</p>
						</div>
					</div>
                    <div class="postbox">
						<h3 class="postbox-header">
							<span>
								<?php echo __( 'Contact', SH_CD_SLUG ); ?>
							</span>
                        </h3>
                        <div style="padding: 0px 15px 0px 15px">
                            <p>If you have any questions or bugs to report, then please contact us at <a href="mailto:email@yeken.uk">email@yeken.uk</a>.</p>
                        </div>
                    </div>
					<div class="postbox">
						<h3 class="postbox-header">
							<span>
								<?php echo __( 'Support Actions', SH_CD_SLUG ); ?>
							</span>
                        </h3>
						<div style="padding: 0px 15px 0px 15px">
							<p><?php echo __( 'Below are tools that may help you troubleshoot issues with your install:', SH_CD_SLUG ); ?></p>
							<?php
								$url = admin_url('admin.php?page=sh-cd-help&db-check=y' );

								if ( false === empty( $_GET[ 'db-check' ] ) ) {
									sh_cd_create_database_table();
									sh_cd_create_database_table_multisite();
									echo __( '<p class="sh-cd-highlight-colour">Database tables have been checked and fixed if required.</p>', SH_CD_SLUG );
								}
							?>
							<p>
								<a href="<?php echo esc_url($url); ?>" rel="noopener noreferrer"  class="button" ><?php echo __( 'Check and fix database tables', SH_CD_SLUG ); ?></a>
							</p>
						</div>
					</div>

				</div>
				<!-- .meta-box-sortables .ui-sortable -->

			</div>
			<!-- post-body-content -->

		</div>
		<!-- #post-body .metabox-holder .columns-2 -->

		<br class="clear">
	</div>
	<!-- #poststuff -->

</div> <!-- .wrap -->
<?php

}

/**
 * HTML for mention of custom work
 */
function sh_cd_custom_notification_html() {
	?>

	<p><img src="<?php echo plugins_url( 'assets/images/yeken-logo.png', __FILE__ ); ?>" width="100" height="100" style="margin-right:20px" align="left" /><?php echo __( 'If you need changes to Snippet Shortcodes, a custom plugin built, or help from a developer for your website, feel free to get in touch!', SH_CD_SLUG ); ?></p>
	<p><strong><?php echo __( 'We provide fixed priced quotes.', SH_CD_SLUG ); ?></strong></p>
	<p><a href="https://www.yeken.uk" rel="noopener noreferrer" target="_blank">YeKen.uk</a> /
		<a href="https://profiles.wordpress.org/aliakro" rel="noopener noreferrer" target="_blank">WordPress Profile</a> /
		<a href="mailto:email@yeken.uk" >email@yeken.uk</a></p>
	<br clear="both"/>
	<?php
}

