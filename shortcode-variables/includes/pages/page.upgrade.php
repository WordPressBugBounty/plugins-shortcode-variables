<?php

    defined('ABSPATH') or die('Jog on!');

    function sh_cd_page_upgrade() {
        ?>
        <div class="wrap ws-ls-admin-page">
            <div id="icon-options-general" class="icon32"></div>
            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-2">
                    <div id="post-body-content">

                        <div class="meta-box-sortables ui-sortable">
                            <div class="postbox">
                                <h3 class="postbox-header">
                                    <span>
                                        <i class="fa-regular fa-star"></i> <?php echo __( 'Upgrade to Premium and get much more!', SH_CD_SLUG ); ?>
                                    </span>
                                </h3>
                                <div class="inside">
                                    <?php sh_cd_marketing_upgrade_page_text(); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="postbox-container-1" class="postbox-container">
                        <div class="meta-box-sortables">

                            <div class="postbox">
								<h3 class="postbox-header">
                                    <span>
                                        <?php echo __( 'How to upgrade', SH_CD_SLUG ); ?>
                                    </span>
                                </h3>
                                <div class="inside">
                                <p><?php echo __( 'To upgrade, you must perform the following steps:', SH_CD_SLUG ); ?><p>
                                </p><?php echo __( '1. Download, install and activate the Premium plugin.', SH_CD_SLUG ); ?> </p>
                                <?php sh_cd_premium_shortcode_download() ?>
                                <p><?php echo __( '2. Purchase a license for the plugin and apply it. In case you need, your <strong>Site Hash</strong> is', SH_CD_SLUG ); ?> <?php echo esc_html( sh_cd_generate_site_hash() ); ?>.</p>
                                <?php sh_cd_upgrade_button(); ?>
                                </div>
                            </div>
                         </div>
                    </div>
                </div>
            <?php
        }
?>
