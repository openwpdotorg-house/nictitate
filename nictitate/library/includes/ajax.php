<?php
if (!function_exists('save_general_setting')) {

    function save_general_setting() {
        if (!wp_verify_nonce($_POST['wpnonce_save_theme_options'], 'save_general_setting'))
            exit();
        $data = $_POST;
        foreach ($data as $key => $value) {
            if (strpos($key, 'kopa_theme_options_') === 0) {
                update_option($key, $value);
            }
        }
        exit();
    }

    add_action('wp_ajax_save_general_setting', 'save_general_setting');
}
/* ==============================================================================
 * Remove Sidebar
  =============================================================================== */
if (!function_exists('kopa_remove_sidebar')) {

    function kopa_remove_sidebar() {
        if (!wp_verify_nonce($_POST['wpnonce'], 'save_sidebar_setting'))
            exit();

        if (!empty($_POST['removed_sidebar_id'])) {
            $removed_sidebar_id = ($_POST['removed_sidebar_id']);
            if ($removed_sidebar_id === 'sidebar_hide') {
                echo json_encode(array("is_exist" => true, "error_message" => "You can not remove this sidebar!"));
            } else {
                $kopa_sidebar = get_option("kopa_sidebar", array());
                $found_sidebar = false;
                foreach ($kopa_sidebar as $e_sidebar_id => $e_sidebar_name) {
                    if ($removed_sidebar_id === $e_sidebar_id) {
                        $found_sidebar = true;
                    }
                }
                if ($found_sidebar) {
                    $kopa_setting = get_option('kopa_setting', array());
                    $found_setting = false;
                    foreach ($kopa_setting as $kopa_setting_key => $kopa_setting_value) {
                        foreach ($kopa_setting_value['sidebars'] as $key => $value) {
                            if ($removed_sidebar_id === $value) {
                                $found_setting = true;
                                $layout_id = $kopa_setting_key;
                            }
                        }
                    }
                    if ($found_setting) {
                        $kopa_template_hierarchy = unserialize(KOPA_TEMPLATE_HIERARCHY);
                        echo json_encode(array("is_exist" => true, "error_message" => "You can not remove this sidebar. It is in used for " . $kopa_template_hierarchy[$layout_id]['title'] . ' page'));
                    } else {
                        unset($kopa_sidebar[$removed_sidebar_id]);
                        update_option("kopa_sidebar", $kopa_sidebar);
                        echo json_encode(array("is_exist" => false, "error_message" => "successfull"));
                    }
                }
            }
        }
        exit();
    }

    add_action('wp_ajax_kopa_remove_sidebar', 'kopa_remove_sidebar');
}
////////////////////////////////////////////////////////
if (!function_exists('kopa_add_sidebar')) {

    function kopa_add_sidebar() {
        if (!wp_verify_nonce($_POST['wpnonce'], 'save_sidebar_setting'))
            exit();
        if (!empty($_POST['new_sidebar_name'])) {
            $kopa_sidebar_name = ($_POST['new_sidebar_name']);
            $kopa_sidebar = get_option("kopa_sidebar", array());
            $sidebar_id = strtolower(trim(str_replace(" ", "_", $kopa_sidebar_name)));
            $found_sidebar = false;
            foreach ($kopa_sidebar as $e_sidebar_id => $e_sidebar_name) {
                if ($sidebar_id === $e_sidebar_id) {
                    $found_sidebar = true;
                }
            }
            if ($found_sidebar) {
                $error_message = 'The sidebar name "' . $kopa_sidebar_name . '" already exist!';
                echo json_encode(array("is_exist" => true, "error_message" => $error_message, "sidebar_id" => $sidebar_id));
            } else {
                echo json_encode(array("is_exist" => false, "error_message" => "", "sidebar_id" => $sidebar_id));
                $kopa_sidebar[$sidebar_id] = $kopa_sidebar_name;
                update_option("kopa_sidebar", $kopa_sidebar);
            }
        }
        exit();
    }

    add_action('wp_ajax_kopa_add_sidebar', 'kopa_add_sidebar');
}
////////////////////////////////////////////////////////
if (!function_exists('save_sidebar_setting')) {

    function save_sidebar_setting() {
        if (!wp_verify_nonce($_POST['wpnonce'], 'save_sidebar_setting'))
            exit();
        if (!empty($_POST[kopa_sidebar])) {
            $kopa_sidebar_name_arr = ($_POST[kopa_sidebar]);
            $kopa_sidebar_existing = get_option("kopa_sidebar", array());

            foreach ($kopa_sidebar_name_arr as $key => $value) {
                $sidebar_id = trim(str_replace(" ", "_", $value)) . $key;
                if (in_array($sidebar_id, $kopa_sidebar_existing)) {
                    $sidebar_id = $sidebar_id . 'kopa';
                }
                $kopa_sidebar[$sidebar_id] = $value;
            }
            update_option("kopa_sidebar", $kopa_sidebar);
        }
        exit();
    }

    add_action('wp_ajax_save_sidebar_setting', 'save_sidebar_setting');
}
////////////////////////////////////////////////////////
if (!function_exists('save_layout')) {

    function save_layout() {
        $kopa_setting = get_option('kopa_setting');
        if (!wp_verify_nonce($_POST['wpnonce'], 'save_layout_setting'))
            exit();
        if (!empty($_POST)) {
            $new_kopa_setting = $_POST['kopa_setting'];
            $template_id = $_POST['template_id'];

            $kopa_setting[$template_id] = $new_kopa_setting[0];
            update_option("kopa_setting", $kopa_setting);
        }
        exit();
    }

    add_action('wp_ajax_save_layout', 'save_layout');
}

if (!function_exists('load_layout')) {

    function load_layout() {
        if (!wp_verify_nonce($_POST['wpnonce'], 'load_layout_setting'))
            exit();
        if (!empty($_POST)) {
            echo kopa_layout_page($_POST['kopa_template_id']);
        }
        exit();
    }

    add_action('wp_ajax_load_layout', 'load_layout');
}

function kopa_layout_page($_kopa_template_id) {
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
        <input type="hidden" id="kopa_template_id" value="<?php echo $_kopa_template_id; ?>">
        <?php
        if ($kopa_template_hierarchy) {
            echo '<div class="kopa-nav list-container">
                <ul class="tabs clearfix">';
            foreach ($kopa_template_hierarchy as $kopa_template_key => $kopa_template_value) {
                if ($kopa_template_key === $_kopa_template_id)
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
                <div class="kopa-content-box tab-content kopa-content-main-box" id="<?php echo $_kopa_template_id; ?>">
                    <div class="kopa-actions clearfix">
                        <div class="kopa-button">
                            <span class="btn btn-primary" onclick="save_layout_setting(jQuery(this))"><i class="icon-ok-circle"></i>Save</span>
                        </div>
                    </div><!--kopa-actions-->
                    <div class="kopa-box-head">
                        <i class="icon-hand-right"></i>
                        <span class="kopa-section-title"><?php echo $kopa_template_hierarchy[$_kopa_template_id]['title'] ?></span>
                    </div><!--kopa-box-head-->
                    <div class="kopa-box-body clearfix"> 
                        <div class="kopa-layout-box pull-left">
                            <div class="kopa-select-layout-box kopa-element-box">
                                <span class="kopa-component-title">Select the layout</span>
                                <select class="kopa-layout-select"  onchange="show_onchange(jQuery(this));" autocomplete="off">
                                    <?php
                                    foreach ($kopa_template_hierarchy[$_kopa_template_id]['layout'] as $keys => $value) {
                                        echo '<option value="' . $value . '"';
                                        /* foreach ($kopa_setting as $kopa_setting_key => $kopa_setting_value) {
                                          if ($kopa_setting_key == $_kopa_template_id && $kopa_setting_value[layout_id] == $value) {
                                          echo 'selected="selected"';
                                          }
                                          } */
                                        if ($value === $kopa_setting[$_kopa_template_id]['layout_id']) {
                                            echo 'selected="selected"';
                                        }
                                        echo '>' . $kopa_layout[$value]['title'] . '</option>';
                                    }
                                    ?>
                                </select>                          
                            </div><!--kopa-select-layout-box-->
                            <?php
                            foreach ($kopa_template_hierarchy[$_kopa_template_id]['layout'] as $keys => $value) {
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
                                                        if ($layout_key === $kopa_setting[$_kopa_template_id]['layout_id']) {
                                                            if ($sidebar_list_key === $kopa_setting[$_kopa_template_id]['sidebars'][$postion_key]) {
                                                                $__selected_sidebar = 'selected="selected"';
                                                            }
                                                        }
                                                        echo '<option value="' . $sidebar_list_key . '" ' . $__selected_sidebar . '>' . $sidebar_list_value . '</option>';
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
                            foreach ($kopa_template_hierarchy[$_kopa_template_id]['layout'] as $thumbnails_key => $thumbnails_value) {
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
    <?php
}

if (!function_exists('kopa_ajax_send_contact')) {

    function kopa_ajax_send_contact() {
        check_ajax_referer('kopa_send_contact_nicole_kidman', 'kopa_send_contact_nonce');

        foreach ($_POST as $key => $value) {
            if (ini_get('magic_quotes_gpc')) {
                $_POST[$key] = stripslashes($_POST[$key]);
            }
            $_POST[$key] = htmlspecialchars(strip_tags($_POST[$key]));
        }

        $name = $_POST["name"];
        $email = $_POST["email"];
        $message = $_POST["message"];

        $message_body = "Name: {$name}" . PHP_EOL . "Message: {$message}";

        $to = get_bloginfo('admin_email');
        if ( isset( $_POST["subject"] ) && $_POST["subject"] != '' )
            $subject = "Contact Form: $name - {$_POST['subject']}";
        else
            $subject = "Contact Form: $name";

        if ( isset( $_POST['url'] ) && $_POST['url'] != '' )
            $message_body .= PHP_EOL . __('Website:', kopa_get_domain()) . $_POST['url'];

        $headers[] = 'From: ' . $name . ' <' . $email . '>';
        $headers[] = 'Cc: ' . $name . ' <' . $email . '>';

        $result = '<span class="failure">' . __('Oops! errors occured.', kopa_get_domain()) . '</span>';
        if (wp_mail($to, $subject, $message_body, $headers)) {
            $result = '<span class="success">' . __('Success! Your email has been sent.', kopa_get_domain()) . '</span>';
        }

        die($result);
    }

    add_action('wp_ajax_kopa_send_contact', 'kopa_ajax_send_contact');
    add_action('wp_ajax_nopriv_kopa_send_contact', 'kopa_ajax_send_contact');
}

if (!function_exists('kopa_ajax_set_view_count')) {

    function kopa_ajax_set_view_count() {
        check_ajax_referer('kopa_set_view_count', 'wpnonce');
        if (!empty($_POST['post_id'])) {
            $post_id = (int) $_POST['post_id'];
            $data['count'] = kopa_set_view_count($post_id);
            echo json_encode($data);
        }
        die();
    }

    add_action('wp_ajax_kopa_set_view_count', 'kopa_ajax_set_view_count');
    add_action('wp_ajax_nopriv_kopa_set_view_count', 'kopa_ajax_set_view_count');
}

if (!function_exists('kopa_sharing_button')) {

    function kopa_sharing_button() {
        if (!wp_verify_nonce($_POST['wpnonce'], 'kopa_sharing_button'))
            exit();
        if (!empty($_POST['pid'])) {
            $sharing_buttons = array(
                'facebook' => get_option('kopa_theme_options_post_sharing_button_facebook', 'show'),
                'twitter' => get_option('kopa_theme_options_post_sharing_button_twitter', 'show'),
                'google' => get_option('kopa_theme_options_post_sharing_button_google', 'show'),
                'linkedin' => get_option('kopa_theme_options_post_sharing_button_linkedin', 'show'),
                'pinterest' => get_option('kopa_theme_options_post_sharing_button_pinterest', 'show'),
                'email' => get_option('kopa_theme_options_post_sharing_button_email', 'show')
            );

            $id = (int) $_POST['pid'];
            $url = get_permalink($id);
            $title = get_the_title($id);
            $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($id), 'full');

            $turn_on = false;
            foreach ($sharing_buttons as $item) {
                if ('show' === $item) {
                    $turn_on = true;
                    break;
                }
            }
            if ($turn_on):
                ?>                
                <?php if ('show' === $sharing_buttons['twitter']): ?>
                    <a href="https://twitter.com/share" class="twitter-share-button" data-lang="en"></a>     
                    <script>!function(d, s, id) {
                                    var js, fjs = d.getElementsByTagName(s)[0];
                                    if (!d.getElementById(id)) {
                                        js = d.createElement(s);
                                        js.id = id;
                                        js.src = "//platform.twitter.com/widgets.js";
                                        fjs.parentNode.insertBefore(js, fjs);
                                    }
                                }(document, "script", "twitter-wjs");</script>                        
                <?php endif; ?>

                <?php if ('show' === $sharing_buttons['google']): ?>                
                    <div class="g-plusone" data-size="medium"></div>
                    <script type="text/javascript">
                        (function() {
                            var po = document.createElement('script');
                            po.type = 'text/javascript';
                            po.async = true;
                            po.src = 'https://apis.google.com/js/plusone.js';
                            var s = document.getElementsByTagName('script')[0];
                            s.parentNode.insertBefore(po, s);
                        })();
                    </script> 
                <?php endif; ?>

                <?php if ('show' === $sharing_buttons['facebook']): ?>
                    <div class="fb-like" data-send="false" data-layout="button_count" data-width="200" data-show-faces="true"></div>
                    <div id="fb-root"></div>
                    <script>(function(d, s, id) {
                            var js, fjs = d.getElementsByTagName(s)[0];
                            if (d.getElementById(id))
                                return;
                            js = d.createElement(s);
                            js.id = id;
                            js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
                            fjs.parentNode.insertBefore(js, fjs);
                        }(document, 'script', 'facebook-jssdk'));</script>
                <?php endif; ?>

                <?php if ('show' === $sharing_buttons['linkedin']): ?>
                    <script type="IN/Share" data-counter="right"></script>       
                    <script src="//platform.linkedin.com/in.js" type="text/javascript"></script>
                <?php endif; ?>

                <?php if ('show' === $sharing_buttons['pinterest']): ?>
                    <span class="pin-it"><a href="http://pinterest.com/pin/create/button/?url=<?php echo $url; ?>&media=<?php echo $thumbnail[0]; ?>&description=<?php echo $title; ?>" class="pin-it-button" count-layout="horizontal"><?php _e('Pin It', kopa_get_domain()); ?></a></span>
                    <script type="text/javascript" src="http://assets.pinterest.com/js/pinit.js"></script>
                <?php endif; ?>

                <?php if ('show' === $sharing_buttons['email']): ?>
                    <a class="share-by-email" href="mailto:?subject=<?php _e('I wanted you to see this site', kopa_get_domain()); ?>&amp;body=<?php echo $url; ?>" title="<?php _e('Share by Email', kopa_get_domain()); ?>"><?php _e('Mail To', kopa_get_domain()); ?></a>
                <?php endif; ?>

                <div class="clear"></div>
                <?php
            endif;
        }
        exit();
    }

    add_action('wp_ajax_kopa_sharing_button', 'kopa_sharing_button');
    add_action('wp_ajax_nopriv_kopa_sharing_button', 'kopa_sharing_button');
}


if (!function_exists('kopa_change_like_status')) {
    add_action('wp_ajax_kopa_change_like_status', 'kopa_change_like_status');
    add_action('wp_ajax_nopriv_kopa_change_like_status', 'kopa_change_like_status');

    function kopa_change_like_status() {
        check_ajax_referer('kopa_change_like_status', 'wpnonce');
        if (!empty($_POST['pid'])) {
            $pid = (int) $_POST['pid'];
            $status = $_POST['status'];

            $public_key = 'kopa_' . kopa_get_domain() . '_total_like';
            $single_key = 'kopa_' . kopa_get_domain() . '_like_by_' . kopa_get_client_IP();

            $total = kopa_get_post_meta($pid, $public_key, true, 'Int');
            $is_voted = kopa_get_post_meta($pid, $single_key, true, 'Int');

            $result = array();

            if (('enable' == $status) && (0 == $is_voted)) {
                $total++;
                update_post_meta($pid, $single_key, 1);
                update_post_meta($pid, $public_key, abs($total));
                $result['status'] = 'disable';
            } else {
                $total--;
                delete_post_meta($pid, $single_key);
                update_post_meta($pid, $public_key, abs($total));
                $result['status'] = 'enable';
            }
            $result['total'] = sprintf(__('%1$s Likes', kopa_get_domain()), $total);
            echo json_encode($result);
        }
        die();
    }

}
if (!function_exists('load_more_articles')) {

    function load_more_articles() {
        if (!wp_verify_nonce($_POST['wpnonce'], 'load_more_articles'))
            exit();
        if (!empty($_POST)) {

            if (isset($_POST['kopa_categories_arg'])) {
                $query_args['categories'] = $_POST['kopa_categories_arg'];
            }

            $query_args['relation'] = $_POST['kopa_relation_arg'];

            if (isset($_POST['kopa_tags_arg'])) {
                $query_args['tags'] = $_POST['kopa_tags_arg'];
            }
            $query_args['number_of_article'] = (int) $_POST['kopa_number_of_article_arg'];
            $query_args['orderby'] = $_POST['kopa_orderbye_arg'];
            $query_args['post__not_in'] = explode(',', $_POST['kopa_post_id_string']);
            echo kopa_get_articles($query_args, $_POST['stored_month_year']);
        }

        die();
    }

    add_action('wp_ajax_load_more_articles', 'load_more_articles');
    add_action('wp_ajax_nopriv_load_more_articles', 'load_more_articles');
}

function kopa_get_articles($query_args, $stored_month_year) {
    global $post;

    $posts = kopa_widget_article_build_query($query_args);
    if ($posts->post_count > 0):
        $previous_date = '';
        $month_year = json_decode(stripslashes($stored_month_year), true);
        $post_id_array = array();
        foreach ($query_args['post__not_in'] as $kopa_post_key => $kopa_post_id) {
            array_push($post_id_array, $kopa_post_id);
        }
        while ($posts->have_posts()):
            $posts->the_post();
            $post_id = get_the_ID();
            array_push($post_id_array, $post_id);
            $post_url = get_permalink();
            $post_title = get_the_title();
            $current_date = get_the_date('M,Y');
            $_element = array(
                'month' => get_the_date('m'),
                'year' => get_the_date('Y'),
                'month-year' => get_the_date('M') . '-' . get_the_date('Y'),
                'month-text' => get_the_date('F')
            );
            array_push($month_year, $_element);
            $last_stored_month_year = json_decode(stripslashes($stored_month_year), true);
            $last_array_element = array_pop($last_stored_month_year);
            if ($_element['month-year'] !== $last_array_element['month-year']) {
                if ($current_date != $previous_date):
                    $previous_date = $current_date;
                    $total_post_no = kopa_total_post_count_by_month(get_the_date('m'), get_the_date('Y'));
                    ?>
                    <div class="time-to-filter clearfix" id="<?php echo get_the_date('M') . '-' . get_the_date('Y'); ?>">
                        <p class="timeline-filter"><span><?php echo $current_date; ?></span></p>
                        <span class="post-quantity"><?php
                            echo $total_post_no;
                            if ($total_post_no <= 1): _e(' Article', kopa_get_domain());
                            else: _e(' Articles', kopa_get_domain());
                            endif;
                            ?>
                        </span>
                        <span class="top-ring"></span>
                        <span class="bottom-ring"></span>
                    </div><!--time-to-filter-->
                    <?php
                endif;
            }
            switch (get_post_format()) {
                case 'quote':
                    ?>
                    <article class="timeline-item quote-post clearfix">                                                    
                        <div class="timeline-icon">
                            <div><span class="post-type" data-icon="&#xe075;"></span></div>
                            <span class="dotted-horizon"></span>
                            <span class="vertical-line"></span>
                            <span class="circle-outer"></span>
                            <span class="circle-inner"></span>
                        </div>
                        <div class="entry-body clearfix">                                                    
                            <p><?php the_excerpt(); ?></p>
                            <center><span class="quote-name"><?php the_author(); ?></span></center>
                            <header>
                                <span class="entry-date"><span class="icon-clock-4 entry-icon" aria-hidden="true"></span><span><?php echo get_the_date(); ?></span></span></span>
                                <span class="entry-comment"><span class="icon-bubbles-4 entry-icon" aria-hidden="true"></span><?php comments_popup_link(__('No Comment', kopa_get_domain()), __('1 Comment', kopa_get_domain()), __('% Comments', kopa_get_domain()), '', __('Comments Off', kopa_get_domain())); ?></span>
                            </header>
                        </div>
                    </article><!--timeline-item-->
                    <?php
                    break;
                case 'video':
                    $video = kopa_content_get_video($post->post_content);
                    ?>
                    <article class="timeline-item video-post clearfix">                                                    
                        <div class="timeline-icon">
                            <div><span class="post-type" data-icon="&#xe023;"></span></div>
                            <span class="dotted-horizon"></span>
                            <span class="vertical-line"></span>
                            <span class="circle-outer"></span>
                            <span class="circle-inner"></span>
                        </div>
                        <div class="entry-body clearfix">
                            <div class="kp-thumb hover-effect">
                                <div class="mask">
                                    <a href="<?php echo $video[0]['url'] ?>" rel="prettyPhoto" class="link-detail" data-icon="&#xe022;"><span></span></a>
                                </div>
                                <?php
                                if (has_post_thumbnail()):
                                    the_post_thumbnail('kopa-image-size-1');
                                else:
                                    printf('<img src="%1$s" alt="">', kopa_get_video_thumbnails_url($video[0]['type'], $video[0]['url']));
                                endif;
                                ?>
                            </div>
                            <header>
                                <h2 class="entry-title"><a href="<?php echo $post_url; ?>"><?php the_title(); ?></a></h2>
                                <span class="entry-date"><span class="icon-clock-4 entry-icon" aria-hidden="true"></span><span><?php echo get_the_date(); ?></span></span>
                                <span class="entry-comment"><span class="icon-bubbles-4 entry-icon" aria-hidden="true"></span><?php comments_popup_link(__('No Comment', kopa_get_domain()), __('1 Comment', kopa_get_domain()), __('% Comments', kopa_get_domain()), '', __('Comments Off', kopa_get_domain())); ?></span>
                            </header>
                            <p><?php the_excerpt(); ?></p>
                            <a href="<?php echo $post_url; ?>" class="more-link"><?php _e('Continue Reading &raquo;', kopa_get_domain()); ?></a>
                        </div>

                    </article><!--timeline-item-->
                    <?php
                    break;
                case 'gallery':
                    ?>
                    <article class="timeline-item gallery-post clearfix">                                                    
                        <div class="timeline-icon">
                            <div><span class="post-type" data-icon="&#xe01d;"></p></div>
                            <span class="dotted-horizon"></span>
                            <span class="vertical-line"></span>
                            <span class="circle-outer"></span>
                            <span class="circle-inner"></span>
                        </div>
                        <div class="entry-body clearfix"> 
                            <?php
                            $gallery = kopa_content_get_gallery($post->post_content);
                            if ($gallery) {
                                $shortcode = substr_replace($gallery[0]['shortcode'], ' display_type = 1]', strlen($gallery[0]['shortcode']) - 1, strlen($gallery[0]['shortcode']));
                                echo do_shortcode($shortcode);
                            }
                            ?>
                            <header>
                                <h2 class="entry-title"><a href="<?php echo $post_url; ?>"><?php the_title(); ?></a></h2>
                                <span class="entry-date"><span class="icon-clock-4 entry-icon" aria-hidden="true"></span><span><?php echo get_the_date(); ?></span></span>
                                <span class="entry-comment"><span class="icon-bubbles-4 entry-icon" aria-hidden="true"></span><?php comments_popup_link(__('No Comment', kopa_get_domain()), __('1 Comment', kopa_get_domain()), __('% Comments', kopa_get_domain()), '', __('Comments Off', kopa_get_domain())); ?></span>
                            </header>
                            <span class="load-more-gallery" onclick="more_gallery(jQuery(this));"><span></span></span>
                        </div>                                                    

                    </article><!--timeline-item-->
                    <?php
                    break;
                case 'audio':
                    ?>
                    <article class="timeline-item audio-post clearfix">                                                    
                        <div class="timeline-icon">
                            <div><span class="post-type" data-icon="&#xe020;"></span></div>
                            <span class="dotted-horizon"></span>
                            <span class="vertical-line"></span>
                            <span class="circle-outer"></span>
                            <span class="circle-inner"></span>
                        </div>
                        <div class="entry-body clearfix">
                            <header>
                                <h2 class="entry-title"><a href="<?php echo $post_url; ?>"><?php the_title(); ?></a></h2>
                                <span class="entry-date"><span class="icon-clock-4 entry-icon" aria-hidden="true"></span><span><?php echo get_the_date(); ?></span></span>
                                <span class="entry-comment"><span class="icon-bubbles-4 entry-icon" aria-hidden="true"></span><?php comments_popup_link(__('No Comment', kopa_get_domain()), __('1 Comment', kopa_get_domain()), __('% Comments', kopa_get_domain()), '', __('Comments Off', kopa_get_domain())); ?></span>
                            </header>
                            <?php
                            $audio = kopa_content_get_audio($post->post_content);
                            if ($audio) {
                                echo do_shortcode($audio[0]['shortcode']);
                            }
                            ?>
                            <p><?php the_excerpt(); ?></p>
                            <a href="<?php echo $post_url; ?>" class="more-link"><?php _e('Continue Reading &raquo;', kopa_get_domain()); ?></a>
                        </div>

                    </article><!--timeline-item-->
                    <?php
                    break;
                default:
                    ?>
                    <article class="timeline-item <?php
                    if (has_post_thumbnail())
                        echo ' standard-post ';
                    else
                        echo 'link-post'
                        ?> clearfix">
                        <div class="timeline-icon">
                            <div><span class="post-type" data-icon="&#xe034;"></span></div>
                            <span class="dotted-horizon"></span>
                            <span class="vertical-line"></span>
                            <span class="circle-outer"></span>
                            <span class="circle-inner"></span>
                        </div>
                        <div class="entry-body clearfix">
                    <?php if (has_post_thumbnail()): ?>
                                <div class="kp-thumb hover-effect">
                                    <div class="mask">
                                        <a class="link-detail" href="<?php echo $post_url; ?>" data-icon="&#xe0c2;"></a>
                                    </div>
                                <?php the_post_thumbnail('kopa-image-size-1'); ?>
                                </div>
                    <?php endif; ?>
                            <header>
                                <h2 class="entry-title"><a href="<?php echo $post_url; ?>"><?php the_title(); ?></a></h2>
                                <span class="entry-date"><span class="icon-clock-4 entry-icon" aria-hidden="true"></span><span><?php echo get_the_date(); ?></span></span>
                                <span class="entry-comment"><span class="icon-bubbles-4 entry-icon" aria-hidden="true"></span><?php comments_popup_link(__('No Comment', kopa_get_domain()), __('1 Comment', kopa_get_domain()), __('% Comments', kopa_get_domain()), '', __('Comments Off', kopa_get_domain())); ?></span>
                            </header>
                            <p><?php the_excerpt(); ?></p>
                            <a href="<?php echo $post_url; ?>" class="more-link"><?php _e('Continue Reading &raquo;', kopa_get_domain()); ?></a>
                        </div>
                    </article><!--timeline-item-->
                    <?php
                    break;
            }
            ?>
        <?php endwhile; ?>  

        <?php
        $post_id_string = implode(",", $post_id_array);
        ?>
        <input type="hidden" id="post_id_array" value="<?php echo $post_id_string; ?>">
        <div class="kp-filter clearfix">
            <div onclick="kp_filter_click(jQuery(this))">
                <span>View by:</span><em>All</em>
                <a></a>                                    
                <ul id="ss-links" class="ss-links">
                    <?php
                    $current_month = '';
                    $current_year = '';
                    foreach ($month_year as $k => $v) {
                        if ($v['year'] !== $current_year) {
                            $current_year = $v['year'];
                            echo '<li class="year"><span>' . $current_year . '</span></li>';
                        }
                        if ($v['month'] !== $current_month) {
                            $current_month = $v['month'];
                            echo '<li><a href="#' . $v['month-year'] . '" onclick="kp_filter_li_click(jQuery(this))">' . $v['month-text'] . '</a></li>';
                        }
                    }
                    ?>
                </ul>
                <input type="hidden" id="stored_month_year" value='<?php echo json_encode($month_year); ?>'>
                <input type="hidden" id="no_post_found" value="0">
            </div>
        </div><!--kp-filter-->
        <?php
    else:
        ?>
        <input type="hidden" id="no_post_found" value="1">
    <?php
    endif;
    wp_reset_postdata();
}