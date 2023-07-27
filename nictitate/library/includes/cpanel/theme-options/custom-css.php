<div id="tab-custom-css" class="kopa-content-box tab-content tab-content-1">    

    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('Custom CSS', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->
    <div class="kopa-box-body">

        <div class="kopa-element-box kopa-theme-options">        
            <p class="kopa-desc"><?php _e('Enter the your custom CSS code', kopa_get_domain()); ?></p>
            <textarea class="" rows="10" id="kopa_custom_css" name="kopa_theme_options_custom_css"><?php echo htmlspecialchars_decode(stripslashes(get_option('kopa_theme_options_custom_css'))); ?></textarea>
        </div><!--kopa-element-box-->

    </div><!--tab-theme-skin-->
</div><!--tab-container-->