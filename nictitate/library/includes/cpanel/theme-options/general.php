<div class="kopa-content-box tab-content tab-content-1" id="tab-general">

    <!--tab-logo-favicon-icon-->
    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('Logo, Favicon, Apple Icon', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->

    <div class="kopa-box-body">
        <div class="kopa-element-box kopa-theme-options">
            <p class="kopa-desc"><?php printf( __('Use <a href="%s">custom header</a> to upload logo image and change site title color.', kopa_get_domain()), admin_url('themes.php?page=custom-header') ); ?></p>                         
            <p class="kopa-desc"><?php _e('Logo margin', kopa_get_domain()); ?></p>
            <label class="kopa-label"><?php _e('Top margin:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_logo_margin_top'); ?>" id="kopa_theme_options_logo_margin_top" name="kopa_theme_options_logo_margin_top" class=" kopa-short-input">
            <label class="kopa-label"><?php _e('px', kopa_get_domain()); ?></label>

            <span class="kopa-spacer"></span>

            <label class="kopa-label"><?php _e('Left margin:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_logo_margin_left'); ?>" id="kopa_theme_options_logo_margin_left" name="kopa_theme_options_logo_margin_left" class=" kopa-short-input">
            <label class="kopa-label"><?php _e('px', kopa_get_domain()); ?></label>

            <span class="kopa-spacer"></span>

            <label class="kopa-label"><?php _e('Right margin:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_logo_margin_right'); ?>" id="kopa_theme_options_logo_margin_right" name="kopa_theme_options_logo_margin_right" class=" kopa-short-input">
            <label class="kopa-label"><?php _e('px', kopa_get_domain()); ?></label>

            <span class="kopa-spacer"></span>

            <label class="kopa-label"><?php _e('Bottom margin:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_logo_margin_bottom'); ?>" id="kopa_theme_options_logo_margin_bottom" name="kopa_theme_options_logo_margin_bottom" class=" kopa-short-input">
            <label class="kopa-label"><?php _e('px', kopa_get_domain()); ?></label>
        </div><!--kopa-element-box-->


        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Favicon', kopa_get_domain()); ?></span>

            <p class="kopa-desc"><?php _e('Upload your own favicon.', kopa_get_domain()); ?></p>    
            <div class="clearfix">
                <input class="left" type="text" value="<?php echo get_option('kopa_theme_options_favicon_url'); ?>" id="kopa_theme_options_favicon_url" name="kopa_theme_options_favicon_url">
                <button class="left btn btn-success upload_image_button" alt="kopa_theme_options_favicon_url"><i class="icon-circle-arrow-up"></i><?php _e('Upload', kopa_get_domain()); ?></button>
            </div>
        </div><!--kopa-element-box-->


        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Apple Icons', kopa_get_domain()); ?></span>

            <p class="kopa-desc"><?php _e('Iphone (57px - 57px)', kopa_get_domain()); ?></p>   
            <div class="clearfix">
                <input class="left" type="text" value="<?php echo get_option('kopa_theme_options_apple_iphone_icon_url'); ?>" id="kopa_theme_options_apple_iphone_icon_url" name="kopa_theme_options_apple_iphone_icon_url">
                <button class="left btn btn-success upload_image_button" alt="kopa_theme_options_apple_iphone_icon_url"><i class="icon-circle-arrow-up"></i><?php _e('Upload', kopa_get_domain()); ?></button>
            </div>
            <p class="kopa-desc"><?php _e('Iphone Retina (114px - 114px)', kopa_get_domain()); ?></p>    
            <div class="clearfix">
                <input class="left" type="text" value="<?php echo get_option('kopa_theme_options_apple_iphone_retina_icon_url'); ?>" id="kopa_theme_options_apple_iphone_retina_icon_url" name="kopa_theme_options_apple_iphone_retina_icon_url">
                <button class="left btn btn-success upload_image_button" alt="kopa_theme_options_apple_iphone_retina_icon_url"><i class="icon-circle-arrow-up"></i><?php _e('Upload', kopa_get_domain()); ?></button>
            </div>

            <p class="kopa-desc"><?php _e('Ipad (72px - 72px)', kopa_get_domain()); ?></p>    
            <div class="clearfix">
                <input class="left" type="text" value="<?php echo get_option('kopa_theme_options_apple_ipad_icon_url'); ?>" id="kopa_theme_options_apple_ipad_icon_url" name="kopa_theme_options_apple_ipad_icon_url">
                <button class="left btn btn-success upload_image_button" alt="kopa_theme_options_apple_ipad_icon_url"><i class="icon-circle-arrow-up"></i><?php _e('Upload', kopa_get_domain()); ?></button>
            </div>

            <p class="kopa-desc"><?php _e('Ipad Retina (144px - 144px)', kopa_get_domain()); ?></p>    
            <div class="clearfix">
                <input class="" type="text" value="<?php echo get_option('kopa_theme_options_apple_ipad_retina_icon_url'); ?>" id="kopa_theme_options_apple_ipad_retina_icon_url" name="kopa_theme_options_apple_ipad_retina_icon_url">
                <button class="btn btn-success upload_image_button" alt="kopa_theme_options_apple_ipad_retina_icon_url"><i class="icon-circle-arrow-up"></i><?php _e('Upload', kopa_get_domain()); ?></button>
            </div>
        </div><!--kopa-element-box-->


    </div><!--tab-logo-favicon-icon-->

    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('Header top links', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->

    <div class="kopa-box-body">
   
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Show / Hide top links (Login, register links)', kopa_get_domain()); ?></span>
            <?php
            $top_links_status = array(
                'show' => __('Show', kopa_get_domain()),
                'hide' => __('Hide', kopa_get_domain())
            );
            $top_links_status_name = "kopa_theme_options_header_top_links_status";
            foreach ($top_links_status as $value => $label):
                $top_links_status_id = $top_links_status_name . "_{$value}";
                ?>
                <label  for="<?php echo $top_links_status_id; ?>" class="kopa-label-for-radio-button"><input type="radio" value="<?php echo $value; ?>" id="<?php echo $top_links_status_id; ?>" name="<?php echo $top_links_status_name; ?>" <?php echo ($value == get_option($top_links_status_name, 'show')) ? 'checked="checked"' : ''; ?>><?php echo $label; ?></label>
                <?php
            endforeach;
            ?>
        </div>
        
    </div>

    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('Sticky Main Menu', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->

    <div class="kopa-box-body">
   
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Enable / Disable sticky main menu', kopa_get_domain()); ?></span>
            <?php
            $sticky_status = array(
                'enable'  => __('Enable', kopa_get_domain()),
                'disable' => __('Disable', kopa_get_domain())
            );
            $sticky_status_name = "kopa_theme_options_sticky_menu_status";
            foreach ($sticky_status as $value => $label):
                $sticky_status_id = $sticky_status_name . "_{$value}";
                ?>
                <label  for="<?php echo $sticky_status_id; ?>" class="kopa-label-for-radio-button"><input type="radio" value="<?php echo $value; ?>" id="<?php echo $sticky_status_id; ?>" name="<?php echo $sticky_status_name; ?>" <?php echo ($value == get_option($sticky_status_name, 'enable')) ? 'checked="checked"' : ''; ?>><?php echo $label; ?></label>
                <?php
            endforeach;
            ?>
        </div>
    </div>

    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('Site Layout', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->

    <div class="kopa-box-body">
   
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Choose a layout', kopa_get_domain()); ?></span>
            <?php
            $layouts = array(
                'wide' => __('Wide', kopa_get_domain()),
                'box' => __('Box', kopa_get_domain())
            );
            $layout_option_name = "kopa_theme_options_layout";
            foreach ($layouts as $value => $label):
                $layout_type_id = $layout_option_name . "_{$value}";
                ?>
                <label  for="<?php echo $layout_type_id; ?>" class="kopa-label-for-radio-button"><input type="radio" value="<?php echo $value; ?>" id="<?php echo $layout_type_id; ?>" name="<?php echo $layout_option_name; ?>" <?php echo ($value == get_option($layout_option_name, 'wide')) ? 'checked="checked"' : ''; ?>><?php echo $label; ?></label>
                <?php
            endforeach;
            ?>
            <p class="kopa-desc"><?php printf( __( 'Select Box layout to use <a href="%1$s">custom background</a>.', kopa_get_domain() ), admin_url('themes.php?page=custom-background') ); ?></p> 
        </div>
    </div>

    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('Main Content', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->

    <div class="kopa-box-body">
   
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Play Video in lightbox', kopa_get_domain()); ?></span>
            <?php
            $play_video_types = array(
                'enable' => __('Enable', kopa_get_domain()),
                'disable' => __('Disable', kopa_get_domain())
            );
            $play_video_type_name = "kopa_theme_options_play_video_in_lightbox";
            foreach ($play_video_types as $value => $label):
                $play_video_type_id = $play_video_type_name . "_{$value}";
                ?>
                <label  for="<?php echo $play_video_type_id; ?>" class="kopa-label-for-radio-button"><input type="radio" value="<?php echo $value; ?>" id="<?php echo $play_video_type_id; ?>" name="<?php echo $play_video_type_name; ?>" <?php echo ($value == get_option($play_video_type_name, 'enable')) ? 'checked="checked"' : ''; ?>><?php echo $label; ?></label>
                <?php
            endforeach
            ?>
        </div>
    </div>

    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('Contact Information', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->

    <div class="kopa-box-body">
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Email:', kopa_get_domain()); ?></span>
            <input type="text" value="<?php echo get_option('kopa_theme_options_email_address'); ?>" id="kopa_theme_options_email_address" name="kopa_theme_options_email_address">
        </div>
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Phone number:', kopa_get_domain()); ?></span>
            <input type="text" value="<?php echo get_option('kopa_theme_options_phone_number'); ?>" id="kopa_theme_options_phone_number" name="kopa_theme_options_phone_number">
        </div>
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Address:', kopa_get_domain()); ?></span>
            <input type="text" value="<?php echo get_option('kopa_theme_options_address'); ?>" id="kopa_theme_options_address" name="kopa_theme_options_address">
        </div>
    </div>
    
    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('Header Information', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->

    <div class="kopa-box-body">
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Left header description:', kopa_get_domain()); ?></span>
            <input type="text" value="<?php echo htmlspecialchars(stripslashes(get_option('kopa_theme_options_left_header_descriptio', 'Call toll free: 0307 472 1942'))); ?>" id="kopa_theme_options_left_header_description" name="kopa_theme_options_left_header_description">
        </div>
    </div>

    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('Portfolio', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->

    <div class="kopa-box-body">
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Show related portfolio by', kopa_get_domain()); ?></span>                        
            <select class="" id="kopa_theme_options_portfolio_related_get_by" name="kopa_theme_options_portfolio_related_get_by">
                <?php
                $portfolio_related_get_by = array(
                    'hide' => __('-- Hide --', kopa_get_domain()),
                    'portfolio_project' => __('Project', kopa_get_domain()),
                    'portfolio_tag' => __('Tag', kopa_get_domain())
                );
                foreach ($portfolio_related_get_by as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value == get_option('kopa_theme_options_portfolio_related_get_by', 'portfolio_tag')) ? 'selected="selected"' : '');
                }
                ?>
            </select>                        
        </div>
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Limit', kopa_get_domain()); ?></span>
            <input type="number" value="<?php echo get_option('kopa_theme_options_portfolio_related_limit', 3); ?>" id="kopa_theme_options_portfolio_related_limit" name="kopa_theme_options_portfolio_related_limit">
        </div>
    </div>

    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('Footer', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->

    <div class="kopa-box-body">

        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Custom Footer', kopa_get_domain()); ?></span>
            <p class="kopa-desc"><?php _e('Enter the content you want to display in your footer (e.g. copyright text).', kopa_get_domain()); ?></p>    
            <textarea class="" rows="6" id="kopa_setting_copyrights" name="kopa_theme_options_copyright"><?php echo htmlspecialchars_decode(stripslashes(get_option('kopa_theme_options_copyright', __('Copyrights. &copy; 2014', kopa_get_domain())))); ?></textarea>
        </div><!--kopa-element-box-->

    </div>

</div><!--kopa-content-box-->

