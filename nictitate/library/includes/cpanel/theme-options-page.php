<div id="kopa-admin-wrapper" class="theme-option clearfix">
    <form name="kopa-theme-options" id="kopa-theme-options" autocomplete="off" method="post" action="#">
        <?php
        wp_nonce_field("save_general_setting", "wpnonce_save_theme_options");
        ?>
        <input type="hidden" name="action" value="save_general_setting">
        <div id="kopa-loading-gif"></div>
        <div class="kopa-nav list-container">
            <ul class="tabs-1 clearfix">
                <li><span lang="#tab-general"><?php _e('General Setting', kopa_get_domain()); ?></span></li>                
                <li><span lang="#tab-single-post"><?php _e('Single Post', kopa_get_domain()); ?></span></li>
                <li><span lang="#tab-social-links"><?php _e('Social Links', kopa_get_domain()); ?></span></li>                
                <li><span lang="#tab-custom-css"><?php _e('Custom CSS', kopa_get_domain()); ?></span></li>
            </ul><!--tabs--->
        </div><!--kopa-nav-->
        <div class="kopa-content">

            <div class="kopa-page-header clearfix">
                <div class="pull-left">
                    <h4><?php _e('Theme Options', kopa_get_domain()); ?></h4>
                </div>
                <div class="pull-right">
                    <div class="kopa-copyrights">
                        <span><?php _e('Visit author URL:', kopa_get_domain()); ?> </span><a href="http://kopatheme.com">http://kopatheme.com</a>
                    </div><!--="kopa-copyrights-->
                </div>
            </div><!--kopa-page-header-->

            <div class="kopa-actions clearfix">
                <div class="kopa-button">
                    <span class="btn btn-primary" onclick="save_general_setting(jQuery(this));"><i class="icon-ok-circle"></i><?php _e('Save', kopa_get_domain()); ?></span>
                </div>
                <div class="progress progress-striped active">
                    <div class="bar" style="width: 100%;"></div>
                </div>
            </div>


            <div class="tab-container">                      

                <?php include_once trailingslashit(get_template_directory()) . '/library/includes/cpanel/theme-options/general.php'; ?>
                <?php include_once trailingslashit(get_template_directory()) . '/library/includes/cpanel/theme-options/post.php'; ?>
                <?php include_once trailingslashit(get_template_directory()) . '/library/includes/cpanel/theme-options/social-links.php'; ?>
                <?php include_once trailingslashit(get_template_directory()) . '/library/includes/cpanel/theme-options/custom-css.php'; ?>

                <div class="kopa-actions kopa-bottom-action-bar clearfix">
                    <div class="kopa-button">
                        <span class="btn btn-primary" onclick="save_general_setting(jQuery(this));"><i class="icon-ok-circle"></i><?php _e('Save', kopa_get_domain()); ?></span>
                    </div>
                    <div class="progress progress-striped active">
                        <div class="bar" style="width: 100%;"></div>
                    </div>
                </div>
            </div><!--tab-container-->
        </div><!--kopa-content-->

        <div class="clear"></div>
    </form>    
</div><!--kopa-admin-wrapper-->