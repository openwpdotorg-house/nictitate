<?php
$kopa_layout = unserialize(KOPA_LAYOUT);
$kopa_template_hierarchy = unserialize(KOPA_TEMPLATE_HIERARCHY);
$kopa_sidebar_position = unserialize(KOPA_SIDEBAR_POSITION);
$kopa_setting = get_option('kopa_setting');
$kopa_sidebar = get_option('kopa_sidebar');
wp_nonce_field("load_layout_setting", "nonce_id");
wp_nonce_field("save_layout_setting", "nonce_id_save");

?>
<div id="kopa-admin-wrapper" class="clearfix">
    <div id="kopa-loading-gif"></div>
    <input type="hidden" id="kopa_template_id" value="home">
    <?php
    if ($kopa_template_hierarchy) {
        echo '<div class="kopa-nav list-container">
                <ul class="tabs clearfix">';
        foreach ($kopa_template_hierarchy as $kopa_template_key => $kopa_template_value) {
            if ($kopa_template_key === 'home')
                $_active = "class='active'";
            else {
                $_active = '';
            }
            echo '<li ' . $_active . '><span title="' . $kopa_template_key . '" onclick="load_layout_setting(jQuery(this))">' . $kopa_template_value['title'] . '</span></li>';
        }
        echo '</ul><!--tabs--->
             </div><!--kopa-nav-->';
    }
    ?>
    <div class="kopa-content">
        <div class="kopa-page-header clearfix">
            <div class="pull-left">
                <h4><i class="icon-cog"></i>Layout And Sidebar Manager</h4>
            </div>
            <div class="pull-right">
                <div class="kopa-copyrights">
                    <span>Visit author URL: </span><a href="http://kopatheme.com">http://kopatheme.com</a>
                </div><!--="kopa-copyrights-->
            </div>
        </div><!--kopa-page-header-->
        <div class="tab-container">
            <div class="kopa-content-box tab-content kopa-content-main-box" id="home">
                <div class="kopa-actions clearfix">
                    <div class="kopa-button">
                        <span class="btn btn-primary" onclick="save_layout_setting(jQuery(this))"><i class="icon-ok-circle"></i>Save</span>
                    </div>
                </div><!--kopa-actions-->
                <div class="kopa-box-head">
                    <i class="icon-hand-right"></i>
                    <span class="kopa-section-title">Home</span>
                </div><!--kopa-box-head-->
                <div class="kopa-box-body clearfix"> 
                    <div class="kopa-layout-box pull-left">
                        <div class="kopa-select-layout-box kopa-element-box">
                            <span class="kopa-component-title">Select the layout</span>
                            <select class="kopa-layout-select"  onchange="show_onchange(jQuery(this));" autocomplete="off">
                                <?php
                                foreach ($kopa_template_hierarchy['home']['layout'] as $keys => $value) {
                                    echo '<option value="' . $value . '"';
                                    if($value === $kopa_setting['home']['layout_id']){
                                            echo 'selected="selected"';
                                        }
                                    echo '>' . $kopa_layout[$value]['title'] . '</option>';
                                }
                                ?>
                            </select>                          
                        </div><!--kopa-select-layout-box-->
                        <?php
                        foreach ($kopa_template_hierarchy['home']['layout'] as $keys => $value) {
                            foreach ($kopa_layout as $layout_key => $layout_value) {
                                if ($layout_key == $value) {
                                    ?>
                                    <div class="<?php echo 'kopa-sidebar-box-wrapper sidebar-position-' . $layout_key; ?>">
                                        <?php
                                        foreach ($layout_value['positions'] as $postion_key => $postion_id) {
                                            ?>
                                            <div class="kopa-sidebar-box kopa-element-box">
                                                <span class="kopa-component-title"><?php echo $kopa_sidebar_position[$postion_id]['title']; ?></span>                                               
                                                <label class="kopa-label">Select sidebars</label>
                                                <?php
                                                echo '<select class="kopa-sidebar-select" autocomplete="off">';
                                                foreach ($kopa_sidebar as $sidebar_list_key => $sidebar_list_value) {
                                                    $__selected_sidebar = '';
                                                    if($layout_key === $kopa_setting['home']['layout_id']){
                                                        if($sidebar_list_key === $kopa_setting['home']['sidebars'][$postion_key]){
                                                             $__selected_sidebar = 'selected="selected"';
                                                        }
                                                    }
                                                    echo '<option value="'.$sidebar_list_key.'" ' . $__selected_sidebar . '>' . $sidebar_list_value . '</option>';
                                                    $__selected_sidebar = '';
                                                }
                                                echo '</select>';
                                                ?>
                                            </div><!--kopa-sidebar-box-->
                                        <?php } ?>
                                    </div><!--kopa-sidebar-box-wrapper-->
                                    <?php
                                }
                            }
                        }
                        ?>
                    </div><!--kopa-layout-box-->
                    <div class="kopa-thumbnails-box pull-right">
                        <?php
                        foreach ($kopa_template_hierarchy['home']['layout'] as $thumbnails_key => $thumbnails_value) {
                            ?>
                            <image class="responsive-img <?php echo ' kopa-cpanel-thumbnails kopa-cpanel-thumbnails-' . $thumbnails_value; ?>" src="<?php echo KOPA_CPANEL_IMAGE_DIR . $kopa_layout[$thumbnails_value]['thumbnails']; ?>" class="img-polaroid" alt="">
                            <?php
                        }
                        ?>
                    </div><!--kopa-thumbnails-box-->
                </div><!--kopa-box-body-->
                <div class="kopa-actions kopa-bottom-action-bar clearfix">
                    <div class="kopa-button">
                        <span class="btn btn-primary" onclick="save_layout_setting(jQuery(this))"><i class="icon-ok-circle"></i>Save</span>
                    </div>
                </div>

            </div><!--kopa-content-box-->
        </div><!--tab-container-->
    </div><!--kopa-content-->
</div><!--kopa-admin-wrapper-->