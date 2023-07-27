<?php
//add extra fields to category edit form hook
add_action('edit_category_form_fields', 'kopa_edit_extra_category_fields');

function kopa_edit_extra_category_fields($tag) { //check for existing featured ID
    $t_id = $tag->term_id;
    $kopa_category_setting_key = "kopa_category_setting_" . $t_id;
    $kopa_setting = get_option('kopa_setting');
    $kopa_category_setting = get_option($kopa_category_setting_key,array());
    $kopa_disable = '';
    if (empty($kopa_category_setting)) {
        $kopa_disable = ' disabled';
        $kopa_checked = '';
        $kopa_checked_value = "No";
        $kopa_category_setting = $kopa_setting["taxonomy"];
    } else {
        $kopa_checked = 'checked ="checked"';
        $kopa_disable = '';
        $kopa_checked_value = "Yes";
    }
    $kopa_template_hierarchy = unserialize(KOPA_TEMPLATE_HIERARCHY);
    $kopa_layout = unserialize(KOPA_LAYOUT);
    $kopa_sidebar_position = unserialize(KOPA_SIDEBAR_POSITION);
    $kopa_sidebar = get_option('kopa_sidebar');
    wp_nonce_field("save_layout_setting", "nonce_id_save");
    ?>
    <tr class="form-field">
        <th scope="row" valign="top">
        </th>
        <td>
            <div class="kopa-content-box tab-content kopa-content-main-box" id="kopa-category-edit" class="kopa-category-edit">
                <div class="kopa-box-head clearfix">  
                    <h4><i class="icon-cog"></i>Custom Setting Layout and Sidebar?</h4>
                    <input onchange="show_on_checked(jQuery(this));" autocomplete="off" type="checkbox" name="kopa_custom_layout_setting" id="kopa_custom_layout_setting" class="kopa_custom_layout_setting" value="<?php echo $kopa_checked_value; ?>" <?php echo $kopa_checked; ?> >
                    <label class="kopa-label">Check if you would like to use custom setting</label>

                </div><!--kopa-box-head-->
                <div class="kopa-box-body clearfix"> 
                    <div class="kopa-layout-box pull-left">
                        <div class="kopa-select-layout-box kopa-element-box">

                            <span class="kopa-component-title">Select the layout</span>
                            <select name ="kopa_select_layout" class="kopa-layout-select"  onchange="show_onchange(jQuery(this));" autocomplete="off" <?php echo $kopa_disable; ?>>
                                <?php
                                foreach ($kopa_template_hierarchy['taxonomy']['layout'] as $keys => $value) {
                                    echo '<option value="' . $value . '"';
                                    if ($value === $kopa_category_setting['layout_id']) {
                                        echo 'selected="selected"';
                                    }
                                    echo '>' . $kopa_layout[$value]['title'] . '</option>';
                                }
                                ?>
                            </select>                           
                        </div><!--kopa-select-layout-box-->
                        <?php
                        foreach ($kopa_template_hierarchy['taxonomy']['layout'] as $keys => $value) {
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
                                                echo '<select class="kopa-sidebar-select"  autocomplete="off" ' . $kopa_disable . '>';
                                                foreach ($kopa_sidebar as $sidebar_list_key => $sidebar_list_value) {
                                                    $__selected_sidebar = '';
                                                    if ($layout_key === $kopa_category_setting['layout_id']) {
                                                        if ($sidebar_list_key === $kopa_category_setting['sidebars'][$postion_key]) {
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
                        foreach ($kopa_template_hierarchy['taxonomy']['layout'] as $thumbnails_key => $thumbnails_value) {
                            ?>
                            <image class="responsive-img <?php echo ' kopa-cpanel-thumbnails kopa-cpanel-thumbnails-' . $thumbnails_value; ?>" src="<?php echo KOPA_CPANEL_IMAGE_DIR . $kopa_layout[$thumbnails_value]['thumbnails']; ?>" class="img-polaroid" alt="">
                            <?php
                        }
                        ?>
                    </div><!--kopa-thumbnails-box-->
                </div><!--kopa-box-body-->           
            </div><!--kopa-content-box-->       
        </td>
    </tr>
    <?php
}

// save extra category extra fields hook
add_action('edited_category', 'kopa_save_extra_category_fileds');

// save extra category extra fields callback function
function kopa_save_extra_category_fileds($term_id) {  
    $kopa_category_setting_key = "kopa_category_setting_" . $term_id;
    if (empty($_POST['kopa_custom_layout_setting'])) {
        delete_option($kopa_category_setting_key);
    }
    if (!empty($_POST['kopa_custom_layout_setting']) && !empty($_POST['kopa_select_layout']) && !empty($_POST['sidebar'])) {
        if ($_POST['kopa_custom_layout_setting'] == 'Yes') {  
            $kopa_new_setting = array();
            $kopa_new_setting['layout_id'] = $_POST['kopa_select_layout'];
            $new_sidebars = ($_POST['sidebar']);
            $kopa_new_setting['sidebars'] = array();
            foreach ($new_sidebars as $__k => $__v) {
                $kopa_new_setting['sidebars'][$__k] = $__v;
            }
            update_option($kopa_category_setting_key, $kopa_new_setting);
       }
    }
}