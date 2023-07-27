<div id="tab-social-links" class="kopa-content-box tab-content tab-content-1">    

    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('Social Links', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->

    <div class="kopa-box-body">

        <!-- RSS -->
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('RSS URL', kopa_get_domain()); ?></span>
            <p class="kopa-desc"><?php _e('Display the RSS feed button with the default RSS feed or enter a custom feed below. <br><code>Enter <b>"HIDE"</b> if you want to hide it</code>', kopa_get_domain()); ?></p>    
            <input type="text" value="<?php echo get_option('kopa_theme_options_social_links_rss_url'); ?>" id="kopa_theme_options_social_links_rss_url" name="kopa_theme_options_social_links_rss_url">                                                     
        </div><!--kopa-element-box-->

        <!-- FACEBOOK -->
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Facebook_URL', kopa_get_domain()); ?></span>
            <input type="url" value="<?php echo get_option('kopa_theme_options_social_links_facebook_url'); ?>" id="kopa_theme_options_social_links_facebook_url" name="kopa_theme_options_social_links_facebook_url">
        </div>

        <!-- TWITTER -->
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Twitter URL', kopa_get_domain()); ?></span>
            <input type="url" value="<?php echo get_option('kopa_theme_options_social_links_twitter_url'); ?>" id="kopa_theme_options_social_links_twitter_url" name="kopa_theme_options_social_links_twitter_url">
        </div>

        <!-- PINTEREST -->
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Pinterest URL', kopa_get_domain()); ?></span>
            <input type="url" value="<?php echo get_option('kopa_theme_options_social_links_pinterest_url'); ?>" id="kopa_theme_options_social_links_pinterest_url" name="kopa_theme_options_social_links_pinterest_url">
        </div>
        <!-- Dribble -->
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Dribbble URL', kopa_get_domain()); ?></span>
            <input type="url" value="<?php echo get_option('kopa_theme_options_social_links_dribbble_url'); ?>" id="kopa_theme_options_social_links_dribbble_url" name="kopa_theme_options_social_links_dribbble_url">
        </div>
        <!-- Youtube -->
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Youtube URL', kopa_get_domain()); ?></span>
            <input type="url" value="<?php echo get_option('kopa_theme_options_social_links_youtube_url'); ?>" id="kopa_theme_options_social_links_youtube_url" name="kopa_theme_options_social_links_youtube_url">
        </div>
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Flickr URL', kopa_get_domain()); ?></span>
            <input type="url" value="<?php echo get_option('kopa_theme_options_social_links_flickr_url'); ?>" id="kopa_theme_options_social_links_flickr_url" name="kopa_theme_options_social_links_flickr_url">
        </div>
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Vimeo URL', kopa_get_domain()); ?></span>
            <input type="url" value="<?php echo get_option('kopa_theme_options_social_links_vimeo_url'); ?>" id="kopa_theme_options_social_links_vimeo_url" name="kopa_theme_options_social_links_vimeo_url">
        </div>
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Instagram URL', kopa_get_domain()); ?></span>
            <input type="url" value="<?php echo get_option('kopa_theme_options_social_links_instagram_url'); ?>" id="kopa_theme_options_social_links_instagram_url" name="kopa_theme_options_social_links_instagram_url">
        </div>       
    </div>
</div>
