<?php
add_action('after_setup_theme', 'kopa_front_after_setup_theme');

function kopa_front_after_setup_theme() {
    add_theme_support('post-formats', array('gallery', 'audio', 'video'));
    add_theme_support('post-thumbnails');
    add_theme_support('loop-pagination');
    add_theme_support('automatic-feed-links');

    $cbg_defaults = array(
        'default-color' => '',
        'default-image' => '',
        'wp-head-callback' => 'kopa_custom_background_cb',
        'admin-head-callback' => '',
        'admin-preview-callback' => ''
    );
    add_theme_support('custom-background', $cbg_defaults);

    global $content_width;
    if (!isset($content_width))
        $content_width = 806;

    register_nav_menus(array(
        'main-nav' => __('Main Menu', kopa_get_domain()),
        'bottom-nav' => __('Bottom Menu', kopa_get_domain())
    ));

    if (!is_admin()) {
        add_action('wp_enqueue_scripts', 'kopa_front_enqueue_scripts');
        add_action('wp_footer', 'kopa_footer');
        add_action('wp_head', 'kopa_head');
        add_action('wp_head', 'kopa_ie_js_header');
        add_filter('widget_text', 'do_shortcode');
        add_filter('the_category', 'kopa_the_category');
        add_filter('get_the_excerpt', 'kopa_get_the_excerpt');
        add_filter('post_class', 'kopa_post_class');
        add_filter('body_class', 'kopa_body_class');
        add_filter('comment_reply_link', 'kopa_comment_reply_link');
        add_filter('edit_comment_link', 'kopa_edit_comment_link');
        add_filter('excerpt_more', 'kopa_new_excerpt_more');
        add_filter('wp_title', 'kopa_wp_title', 10, 2);
    } else {
        add_filter('image_size_names_choose', 'kopa_image_size_names_choose');
    }

    /* Add theme's image sizes */
    $sizes = apply_filters('kopa_get_image_sizes', array(
        'kopa-image-size-0' => array(806, 393, true, __('Single Post Thumbnail (Kopatheme)', kopa_get_domain())),
        'kopa-image-size-1' => array(251, 199, true, __('Thumbnail pm posts list widget (Kopatheme)', kopa_get_domain())),
        'kopa-image-size-2' => array(80, 80, true, __('Testimonial avatar (Kopatheme)', kopa_get_domain())),
        'kopa-image-size-3' => array(252, 201, true, __('Post Carousel Thumbnail (Kopatheme)', kopa_get_domain())),
        'kopa-image-size-4' => array(150, 38, true, __('Client Logo (Kopatheme)', kopa_get_domain())),
        'kopa-image-size-5' => array(118, 118, true, __('Portfolio Thumbnail 1 (Kopatheme)', kopa_get_domain())),
        'kopa-image-size-6' => array(118, 239, true, __('Portfolio Thumbnail 2 (Kopatheme)', kopa_get_domain())),
        'kopa-image-size-7' => array(239, 118, true, __('Portfolio Thumbnail 3 (Kopatheme)', kopa_get_domain())),
        'kopa-image-size-8' => array(239, 239, true, __('Portfolio Thumbnail 4 (Kopatheme)', kopa_get_domain())),
        'kopa-image-size-9' => array(1086, 529, true, __('Single Post Fullwidth Thumbnail (Kopatheme)', kopa_get_domain())),
        'kopa-image-size-10' => array(104, 84, true, __('Products Widget Thumbnail (Kopatheme)', kopa_get_domain())),
        'kopa-image-size-11' => array(531, 326, true, __('About Widget Slider Image (Kopatheme)', kopa_get_domain())),
    ));
    foreach ($sizes as $slug => $details) {
        add_image_size($slug, $details[0], $details[1], $details[2]);
    }
}

function kopa_comment_reply_link($link) {
    return str_replace('comment-reply-link', 'comment-reply-link small-button green-button', $link);
}

function kopa_edit_comment_link($link) {
    return str_replace('comment-edit-link', 'comment-edit-link small-button green-button', $link);
}

function kopa_post_class($classes) {
    if (is_single()) {
        $classes[] = 'entry-box';
        $classes[] = 'clearfix';
    }
    return $classes;
}

function kopa_body_class($classes) {
    $template_setting = kopa_get_template_setting();

    $classes[] = get_option('kopa_theme_options_footer_style', 'dark-footer');

    if (is_front_page()) {
        $classes[] = 'home-page';
    } else {
        $classes[] = 'sub-page';
    }

    switch ($template_setting['layout_id']) {
        case 'home-page-1':
            $classes[] = 'kopa-home-2';
            break;
        case 'home-page-2':
            $classes[] = 'kopa-home-3';
            break;
        case 'home-page-3':
            $classes[] = 'kopa-home-4';
            break;
        case 'blog-right-sidebar':
            $classes[] = 'kopa-blog-1';
            break;
        case 'blog-2-right-sidebar':
            $classes[] = 'kopa-blog-2';
            break;
        case 'single-right-sidebar':
            $classes[] = 'kopa-single-standard-1';
            break;
        case 'single-2-right-sidebar':
            $classes[] = 'kopa-single-standard-2';
            break;
        case 'portfolio':
            $classes[] = 'kopa-portfolio-page';
            break;
        case 'page-fullwidth-widgets':
            $classes[] = 'kopa-about-page';
            break;
    }

    return $classes;
}

function kopa_footer() {
    wp_nonce_field('kopa_set_view_count', 'kopa_set_view_count_wpnonce', false);
}

function kopa_front_enqueue_scripts() {
    if (!is_admin()) {
        global $wp_styles, $is_IE;

        $dir = get_template_directory_uri();

        /* GOOGLE FONTS */
        wp_enqueue_script('kopa-google-api', 'http://ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js', array(), NULL);
        wp_enqueue_script('kopa-google-fonts', $dir . '/js/google-fonts.js', array('kopa-google-api'), NULL);

        $google_fonts = kopa_get_google_font_array();
        $current_heading_font = get_option('kopa_theme_options_heading_font_family');
        $current_content_font = get_option('kopa_theme_options_content_font_family');
        $current_main_nav_font = get_option('kopa_theme_options_main_nav_font_family');
        $current_dropdown_font = get_option('kopa_theme_options_sub_nav_font_family');
        $current_wdg_sidebar_font = get_option('kopa_theme_options_wdg_sidebar_font_family');
        $current_wdg_main_font = get_option('kopa_theme_options_wdg_main_font_family');
        $current_wdg_footer_font = get_option('kopa_theme_options_wdg_footer_font_family');
        $current_slider_font = get_option('kopa_theme_options_slider_font_family');
        $load_font_array = array();

        // heading font family
        if ($current_heading_font && !in_array($current_heading_font, $load_font_array)) {
            array_push($load_font_array, $current_heading_font);
        }

        // content font family
        if ($current_content_font && !in_array($current_content_font, $load_font_array)) {
            array_push($load_font_array, $current_content_font);
        }

        // main menu font family
        if ($current_main_nav_font && !in_array($current_main_nav_font, $load_font_array)) {
            array_push($load_font_array, $current_main_nav_font);
        }

        // sub menu font family
        if ($current_dropdown_font && !in_array($current_dropdown_font, $load_font_array)) {
            array_push($load_font_array, $current_dropdown_font);
        }

        // right sidebar: widget title font family  
        if ($current_wdg_sidebar_font && !in_array($current_wdg_sidebar_font, $load_font_array)) {
            array_push($load_font_array, $current_wdg_sidebar_font);
        }

        // main content sidebar: widget title font family  
        if ($current_wdg_main_font && !in_array($current_wdg_main_font, $load_font_array)) {
            array_push($load_font_array, $current_wdg_main_font);
        }

        // footer sidebar: widget title font family
        if ($current_wdg_footer_font && !in_array($current_wdg_footer_font, $load_font_array)) {
            array_push($load_font_array, $current_wdg_footer_font);
        }

        foreach ($load_font_array as $current_font) {
            if ($current_font != '') {
                $google_font_family = $google_fonts[$current_font]['family'];
                $temp_font_name = str_replace(' ', '+', $google_font_family);
                $font_url = 'http://fonts.googleapis.com/css?family=' . $temp_font_name . ':300,300italic,400,400italic,700,700italic&subset=latin';
                wp_enqueue_style('Google-Font-' . $temp_font_name, $font_url);
            }
        }

        /* STYLESHEETs */

        wp_enqueue_style('kopa-bootstrap', $dir . '/css/bootstrap.css', array(), NULL, 'screen');
        wp_enqueue_style('kopa-fontawesome', $dir . '/css/font-awesome.css', array(), NULL);
        wp_enqueue_style('kopa-superfish', $dir . '/css/superfish.css', array(), NULL, 'screen');
        wp_enqueue_style('kopa-prettyPhoto', $dir . '/css/prettyPhoto.css', array(), NULL, 'screen');
        wp_enqueue_style('kopa-flexlisder', $dir . '/css/flexslider.css', array(), NULL, 'screen');
        wp_enqueue_style('kopa-sequence-style', $dir . '/css/sequencejs-theme.modern-slide-in.css', array(), NULL, 'screen');
        wp_enqueue_style('kopa-style', get_stylesheet_uri(), array(), NULL);
        wp_enqueue_style('kopa-bootstrap-responsive', $dir . '/css/bootstrap-responsive.css', array(), NULL);
        wp_enqueue_style('kopa-extra-style', $dir . '/css/extra.css', array(), NULL);
        wp_enqueue_style('kopa-responsive', $dir . '/css/responsive.css', array(), NULL);

        if ($is_IE) {
            wp_register_style('kopa-ie', $dir . '/css/ie.css', array(), NULL);
            $wp_styles->add_data('kopa-ie', 'conditional', 'lt IE 9');
            wp_enqueue_style('kopa-ie');
        }

        /* JAVASCRIPTs */

        wp_enqueue_script('kopa-modernizr', $dir . '/js/modernizr.custom.js');
        wp_enqueue_script('jquery');
        wp_localize_script('jquery', 'kopa_front_variable', kopa_front_localize_script());

        /**
         * Fix: Superfish conflicts with WP admin bar
         * @author joeldbirch
         * @link https://github.com/joeldbirch/superfish/issues/14
         * @filesource https://github.com/briancherne/jquery-hoverIntent 
         */
        wp_deregister_script('hoverIntent');
        wp_register_script('hoverIntent', ( '/js/jquery.hoverIntent.js'), array('jquery'), 'r7');

        wp_enqueue_script('kopa-superfish-js', $dir . '/js/superfish.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-retina', $dir . '/js/retina.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-bootstrap-js', $dir . '/js/bootstrap.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-hoverdir', $dir . '/js/jquery.hoverdir.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-carouFredSel', $dir . '/js/jquery.carouFredSel-6.0.4-packed.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-flexlisder-js', $dir . '/js/jquery.flexslider-min.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-prettyPhoto-js', $dir . '/js/jquery.prettyPhoto.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-jflickrfeed', $dir . '/js/jflickrfeed.min.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-jquery-validate', $dir . '/js/jquery.validate.min.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-jquery-form', $dir . '/js/jquery.form.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-jquery-sequence', $dir . '/js/sequence.jquery-min.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-classie', $dir . '/js/classie.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-cbpAnimatedHeader', $dir . '/js/cbpAnimatedHeader.min.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-set-view-count', $dir . '/js/set-view-count.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-custom', $dir . '/js/custom.js', array('jquery'), NULL, TRUE);
        // send localization to frontend
        wp_localize_script('kopa-custom', 'kopa_custom_front_localization', kopa_custom_front_localization());

        if (is_single() || is_page()) {
            wp_enqueue_script('comment-reply');
        }
    }
}

function kopa_front_localize_script() {
    $kopa_variable = array(
        'ajax' => array(
            'url' => admin_url('admin-ajax.php')
        ),
        'template' => array(
            'post_id' => (is_singular()) ? get_queried_object_id() : 0
        )
    );
    return $kopa_variable;
}

/**
 * Send the translated texts to frontend
 * @package Circle
 * @since Circle 1.12
 */
function kopa_custom_front_localization() {
    $front_localization = array(
        'validate' => array(
            'form' => array(
                'submit' => __('Submit', kopa_get_domain()),
                'sending' => __('Sending...', kopa_get_domain())
            ),
            'name' => array(
                'required' => __('Please enter your name.', kopa_get_domain()),
                'minlength' => __('At least {0} characters required.', kopa_get_domain())
            ),
            'email' => array(
                'required' => __('Please enter your email.', kopa_get_domain()),
                'email' => __('Please enter a valid email.', kopa_get_domain())
            ),
            'url' => array(
                'required' => __('Please enter your url.', kopa_get_domain()),
                'url' => __('Please enter a valid url.', kopa_get_domain())
            ),
            'message' => array(
                'required' => __('Please enter a message.', kopa_get_domain()),
                'minlength' => __('At least {0} characters required.', kopa_get_domain())
            )
        )
    );

    return $front_localization;
}

function kopa_the_category($thelist) {
    return $thelist;
}

/* FUNCTION */

function kopa_image_size_names_choose($sizes) {
    $kopa_sizes = apply_filters('kopa_get_image_sizes', array(
        'kopa-image-size-0' => array(806, 393, TRUE, __('Single Post Thumbnail (Kopatheme)', kopa_get_domain())),
        'kopa-image-size-1' => array(251, 199, TRUE, __('Thumbnail pm posts list widget (Kopatheme)', kopa_get_domain())),
        'kopa-image-size-2' => array(80, 80, TRUE, __('Testimonial avatar (Kopatheme)', kopa_get_domain())),
        'kopa-image-size-3' => array(252, 201, TRUE, __('Post Carousel Thumbnail (Kopatheme)', kopa_get_domain())),
        'kopa-image-size-4' => array(150, 38, TRUE, __('Client Logo (Kopatheme)', kopa_get_domain())),
        'kopa-image-size-5' => array(118, 118, TRUE, __('Portfolio Thumbnail 1 (Kopatheme)', kopa_get_domain())),
        'kopa-image-size-6' => array(118, 239, TRUE, __('Portfolio Thumbnail 2 (Kopatheme)', kopa_get_domain())),
        'kopa-image-size-7' => array(239, 118, TRUE, __('Portfolio Thumbnail 3 (Kopatheme)', kopa_get_domain())),
        'kopa-image-size-8' => array(239, 239, TRUE, __('Portfolio Thumbnail 4 (Kopatheme)', kopa_get_domain())),
        'kopa-image-size-9' => array(1086, 529, TRUE, __('Single Post Fullwidth Thumbnail (Kopatheme)', kopa_get_domain())),
        'kopa-image-size-10' => array(104, 84, TRUE, __('Products Widget Thumbnail (Kopatheme)', kopa_get_domain())),
        'kopa-image-size-11' => array(531, 326, TRUE, __('About Widget Slider Image (Kopatheme)', kopa_get_domain()))
    ));
    foreach ($kopa_sizes as $size => $image) {
        $width = ($image[0]) ? $image[0] : __('auto', kopa_get_domain());
        $height = ($image[1]) ? $image[1] : __('auto', kopa_get_domain());
        $sizes[$size] = $image[3] . " ({$width} x {$height})";
    }
    return $sizes;
}

function kopa_set_view_count($post_id) {
    $new_view_count = 0;
    $meta_key = 'kopa_' . kopa_get_domain() . '_total_view';

    $current_views = (int) get_post_meta($post_id, $meta_key, true);

    if ($current_views) {
        $new_view_count = $current_views + 1;
        update_post_meta($post_id, $meta_key, $new_view_count);
    } else {
        $new_view_count = 1;
        add_post_meta($post_id, $meta_key, $new_view_count);
    }
    return $new_view_count;
}

function kopa_get_view_count($post_id) {
    $key = 'kopa_' . kopa_get_domain() . '_total_view';
    return kopa_get_post_meta($post_id, $key, true, 'Int');
}

function kopa_breadcrumb() {
    if (is_main_query()) {
        global $post, $wp_query;

        $prefix = '';
        $current_class = 'current-page';
        $description = '';
        $breadcrumb_before = '<div id="breadcrumb-wrapper"><div class="wrapper"><div class="row-fluid"><div class="span12"><div class="breadcrumb">';
        $breadcrumb_after = '</div></div></div></div></div>';
        $breadcrumb_home = '<a href="' . home_url() . '">' . __('Home', kopa_get_domain()) . '</a>';
        $breadcrumb = '';
        ?>

        <?php
        if (is_home()) {
            $breadcrumb.= $breadcrumb_home;
            $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, __('Blog', kopa_get_domain()));
        } else if (is_post_type_archive('product') && jigoshop_get_page_id('shop')) {
            $breadcrumb.= $breadcrumb_home;
            $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, get_the_title(jigoshop_get_page_id('shop')));
        } else if (is_tag()) {
            $breadcrumb.= $breadcrumb_home;

            $term = get_term(get_queried_object_id(), 'post_tag');
            $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, $term->name);
        } else if (is_category()) {
            $breadcrumb.= $breadcrumb_home;

            $category_id = get_queried_object_id();
            $terms_link = explode(',', substr(get_category_parents(get_queried_object_id(), TRUE, ','), 0, (strlen(',') * -1)));
            $n = count($terms_link);
            if ($n > 1) {
                for ($i = 0; $i < ($n - 1); $i++) {
                    $breadcrumb.= $prefix . $terms_link[$i];
                }
            }
            $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, get_the_category_by_ID(get_queried_object_id()));
        } else if (is_tax('product_cat')) {
            $breadcrumb.= $breadcrumb_home;
            $breadcrumb.= '<a href="' . get_page_link(jigoshop_get_page_id('shop')) . '">' . get_the_title(jigoshop_get_page_id('shop')) . '</a>';
            $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));

            $parents = array();
            $parent = $term->parent;
            while ($parent):
                $parents[] = $parent;
                $new_parent = get_term_by('id', $parent, get_query_var('taxonomy'));
                $parent = $new_parent->parent;
            endwhile;
            if (!empty($parents)):
                $parents = array_reverse($parents);
                foreach ($parents as $parent):
                    $item = get_term_by('id', $parent, get_query_var('taxonomy'));
                    $breadcrumb .= '<a href="' . get_term_link($item->slug, 'product_cat') . '">' . $item->name . '</a>';
                endforeach;
            endif;

            $queried_object = get_queried_object();
            $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, $queried_object->name);
        } else if (is_tax('product_tag')) {
            $breadcrumb.= $breadcrumb_home;
            $breadcrumb.= '<a href="' . get_page_link(jigoshop_get_page_id('shop')) . '">' . get_the_title(jigoshop_get_page_id('shop')) . '</a>';
            $queried_object = get_queried_object();
            $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, $queried_object->name);
        } else if (is_single()) {
            $breadcrumb.= $breadcrumb_home;

            if (get_post_type() === 'product') :

                $breadcrumb .= '<a href="' . get_page_link(jigoshop_get_page_id('shop')) . '">' . get_the_title(jigoshop_get_page_id('shop')) . '</a>';

                if ($terms = get_the_terms($post->ID, 'product_cat')) :
                    $term = apply_filters('jigoshop_product_cat_breadcrumb_terms', current($terms), $terms);
                    $parents = array();
                    $parent = $term->parent;
                    while ($parent):
                        $parents[] = $parent;
                        $new_parent = get_term_by('id', $parent, 'product_cat');
                        $parent = $new_parent->parent;
                    endwhile;
                    if (!empty($parents)):
                        $parents = array_reverse($parents);
                        foreach ($parents as $parent):
                            $item = get_term_by('id', $parent, 'product_cat');
                            $breadcrumb .= '<a href="' . get_term_link($item->slug, 'product_cat') . '">' . $item->name . '</a>';
                        endforeach;
                    endif;
                    $breadcrumb .= '<a href="' . get_term_link($term->slug, 'product_cat') . '">' . $term->name . '</a>';
                endif;

                $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, get_the_title());

            else :

                $categories = get_the_category(get_queried_object_id());
                if ($categories) {
                    foreach ($categories as $category) {
                        $breadcrumb.= $prefix . sprintf('<a href="%1$s">%2$s</a>', get_category_link($category->term_id), $category->name);
                    }
                }

                $post_id = get_queried_object_id();
                $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, get_the_title($post_id));

            endif;
        } else if (is_page()) {
            if (!is_front_page()) {
                $post_id = get_queried_object_id();
                $breadcrumb.= $breadcrumb_home;
                $post_ancestors = get_post_ancestors($post);
                if ($post_ancestors) {
                    $post_ancestors = array_reverse($post_ancestors);
                    foreach ($post_ancestors as $crumb)
                        $breadcrumb.= $prefix . sprintf('<a href="%1$s">%2$s</a>', get_permalink($crumb), get_the_title($crumb));
                }
                $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, get_the_title(get_queried_object_id()));
            }
        } else if (is_year() || is_month() || is_day()) {
            $breadcrumb.= $breadcrumb_home;

            $date = array('y' => NULL, 'm' => NULL, 'd' => NULL);

            $date['y'] = get_the_time('Y');
            $date['m'] = get_the_time('m');
            $date['d'] = get_the_time('j');

            if (is_year()) {
                $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, $date['y']);
            }

            if (is_month()) {
                $breadcrumb.= $prefix . sprintf('<a href="%1$s">%2$s</a>', get_year_link($date['y']), $date['y']);
                $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, date('F', mktime(0, 0, 0, $date['m'])));
            }

            if (is_day()) {
                $breadcrumb.= $prefix . sprintf('<a href="%1$s">%2$s</a>', get_year_link($date['y']), $date['y']);
                $breadcrumb.= $prefix . sprintf('<a href="%1$s">%2$s</a>', get_month_link($date['y'], $date['m']), date('F', mktime(0, 0, 0, $date['m'])));
                $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, $date['d']);
            }
        } else if (is_search()) {
            $breadcrumb.= $breadcrumb_home;

            $s = get_search_query();
            $c = $wp_query->found_posts;

            $description = sprintf(__('<span class="%1$s">Your search for "%2$s"', kopa_get_domain()), $current_class, $s);
            $breadcrumb .= $prefix . $description;
        } else if (is_author()) {
            $breadcrumb.= $breadcrumb_home;
            $author_id = get_queried_object_id();
            $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</a>', $current_class, sprintf(__('Posts created by %1$s', kopa_get_domain()), get_the_author_meta('display_name', $author_id)));
        } else if (is_404()) {
            $breadcrumb.= $breadcrumb_home;
            $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, __('Page not found', kopa_get_domain()));
        }

        if ($breadcrumb)
            echo apply_filters('kopa_breadcrumb', $breadcrumb_before . $breadcrumb . $breadcrumb_after);
    }
}

function kopa_get_related_articles() {
    if (is_single()) {
        $get_by = get_option('kopa_theme_options_post_related_get_by', 'hide');
        if ('hide' != $get_by) {
            $limit = (int) get_option('kopa_theme_options_post_related_limit', 5);
            if ($limit > 0) {
                global $post;
                $taxs = array();
                if ('category' == $get_by) {
                    $cats = get_the_category(($post->ID));
                    if ($cats) {
                        $ids = array();
                        foreach ($cats as $cat) {
                            $ids[] = $cat->term_id;
                        }
                        $taxs [] = array(
                            'taxonomy' => 'category',
                            'field' => 'id',
                            'terms' => $ids
                        );
                    }
                } else {
                    $tags = get_the_tags($post->ID);
                    if ($tags) {
                        $ids = array();
                        foreach ($tags as $tag) {
                            $ids[] = $tag->term_id;
                        }
                        $taxs [] = array(
                            'taxonomy' => 'post_tag',
                            'field' => 'id',
                            'terms' => $ids
                        );
                    }
                }

                if ($taxs) {
                    $related_args = array(
                        'tax_query' => $taxs,
                        'post__not_in' => array($post->ID),
                        'posts_per_page' => $limit
                    );
                    $related_posts = new WP_Query($related_args);
                    $carousel_id = ($related_posts->post_count > 3) ? 'related-widget' : 'related-widget-no-carousel';
                    if ($related_posts->have_posts()):
                        ?>
                        <div class="kopa-related-post">
                            <h3><span data-icon="&#xf040;"></span><?php _e('Related Posts', kopa_get_domain()); ?></h3>
                            <div class="list-carousel responsive">
                                <ul class="kopa-related-post-carousel" id="<?php echo $carousel_id; ?>">
                                    <?php
                                    while ($related_posts->have_posts()):
                                        $related_posts->the_post();
                                        $post_url = get_permalink();
                                        $post_title = get_the_title();
                                        ?>       
                                        <li style="width: 390px;">
                                            <article class="entry-item clearfix">
                                                <div class="entry-thumb hover-effect">
                                                    <?php
                                                    switch (get_post_format()) :

                                                        // video post format
                                                        case 'video':
                                                            $video = kopa_content_get_video(get_the_content());

                                                            if (!empty($video)) :
                                                                $video = $video[0];
                                                                ?>
                                                                <div class="mask">
                                                                    <a class="link-detail" rel="prettyPhoto" data-icon="&#xf04b;" href="<?php echo $video['url'] ?>"></a>
                                                                </div>
                                                                <?php
                                                                if (has_post_thumbnail())
                                                                    the_post_thumbnail('kopa-image-size-1');
                                                                else
                                                                    echo '<img src="' . kopa_get_video_thumbnails_url($video['type'], $video['url']) . '">';

                                                            endif; // endif ! empty( $video )

                                                            break;

                                                        // gallery post format
                                                        case 'gallery':
                                                            $gallery = kopa_content_get_gallery(get_the_content());

                                                            if (!empty($gallery)) :

                                                                $shortcode = $gallery[0]['shortcode'];

                                                                // get gallery string ids
                                                                preg_match_all('/ids=\"(?:\d+,*)+\"/', $shortcode, $gallery_string_ids);
                                                                $gallery_string_ids = $gallery_string_ids[0][0];

                                                                // get array of image id
                                                                preg_match_all('/\d+/', $gallery_string_ids, $gallery_ids);
                                                                $gallery_ids = $gallery_ids[0];

                                                                $first_image_id = array_shift($gallery_ids);
                                                                $first_image_src = wp_get_attachment_image_src($first_image_id, 'kopa-image-size-1');
                                                                $first_full_image_src = wp_get_attachment_image_src($first_image_id, 'full');

                                                                $slug = 'gallery-' . get_the_ID();
                                                                ?>
                                                                <div class="mask">
                                                                    <a class="link-detail" rel="prettyPhoto[<?php echo $slug; ?>]" data-icon="&#xf03e;" href="<?php echo $first_full_image_src[0]; ?>"></a>
                                                                </div>
                                                                <?php
                                                                foreach ($gallery_ids as $gallery_id) :
                                                                    $image_src = wp_get_attachment_image_src($gallery_id, 'full');
                                                                    ?>
                                                                    <a style="display: none" href="<?php echo $image_src[0]; ?>" rel="prettyPhoto[<?php echo $slug; ?>]"></a>
                                                                    <?php
                                                                endforeach;

                                                                if (has_post_thumbnail())
                                                                    the_post_thumbnail('kopa-image-size-1');
                                                                else
                                                                    echo '<img src="' . $first_image_src[0] . '">';

                                                            endif; // endif ! empty ( $gallery )

                                                            break;

                                                        // default post format
                                                        default:
                                                            if (get_post_format() == 'quote')
                                                                $data_icon = '&#xf10d;';
                                                            elseif (get_post_format() == 'audio')
                                                                $data_icon = '&#xf001;';
                                                            else
                                                                $data_icon = '&#xf0c1;';
                                                            ?>
                                                            <div class="mask">
                                                                <a class="link-detail" data-icon="<?php echo $data_icon; ?>" href="<?php the_permalink(); ?>"></a>
                                                            </div>
                                                            <?php
                                                            if (has_post_thumbnail())
                                                                the_post_thumbnail('kopa-image-size-1');
                                                            break;
                                                    endswitch;
                                                    ?>
                                                </div>
                                                <div class="entry-content">
                                                    <h6 class="entry-title"><a href="<?php echo $post_url; ?>"><?php echo $post_title; ?></a><span></span></h6>
                                                    <span class="entry-date clearfix"><span class="fa fa-clock-o"></span><span><?php echo get_the_date(); ?></span></span>
                                                    <?php the_excerpt(); ?>
                                                </div><!--entry-content-->
                                            </article><!--entry-item-->
                                        </li>
                                        <?php
                                    endwhile;
                                    ?>
                                </ul>
                                <div class="clearfix"></div>
                                <?php if ($related_posts->post_count > 3): ?>
                                    <div class="carousel-nav clearfix">
                                        <a id="prev-4" class="carousel-prev" href="#">&lt;</a>
                                        <a id="next-4" class="carousel-next" href="#">&gt;</a>
                                    </div><!--end:carousel-nav-->
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                    endif;
                    wp_reset_postdata();
                }
            }
        }
    }
}

function kopa_get_related_portfolio() {
    if (is_singular('portfolio')) {
        $get_by = get_option('kopa_theme_options_portfolio_related_get_by', 'hide');
        if ('hide' != $get_by) {
            $limit = (int) get_option('kopa_theme_options_portfolio_related_limit', 3);
            if ($limit > 0) {
                global $post;
                $taxs = array();

                $terms = wp_get_post_terms($post->ID, $get_by);
                if ($terms) {
                    $ids = array();
                    foreach ($terms as $term) {
                        $ids[] = $term->term_id;
                    }
                    $taxs [] = array(
                        'taxonomy' => $get_by,
                        'field' => 'id',
                        'terms' => $ids
                    );
                }

                if ($taxs) {
                    $related_args = array(
                        'post_type' => 'portfolio',
                        'tax_query' => $taxs,
                        'post__not_in' => array($post->ID),
                        'posts_per_page' => $limit
                    );
                    $related_portfolios = new WP_Query($related_args);
                    $carousel_id = ($related_portfolios->post_count > 3) ? 'related-widget' : 'related-widget-no-carousel';
                    if ($related_portfolios->have_posts()):
                        $index = 1;
                        ?>
                        <div class="kopa-related-post">
                            <h3><span data-icon="&#xf040;"></span><?php _e('Related Portfolios', kopa_get_domain()); ?></h3>
                            <div class="list-carousel responsive">
                                <ul class="kopa-related-post-carousel" id="<?php echo $carousel_id; ?>">
                                    <?php
                                    while ($related_portfolios->have_posts()):
                                        $related_portfolios->the_post();
                                        $post_url = get_permalink();
                                        $post_title = get_the_title();
                                        ?>       
                                        <li style="width: 390px;">
                                            <article class="entry-item clearfix">
                                                <div class="entry-thumb hover-effect">
                                                    <div class="mask">
                                                        <a class="link-detail" data-icon="&#xf0c1;" href="<?php the_permalink(); ?>"></a>
                                                    </div>
                                                    <?php
                                                    if (has_post_thumbnail())
                                                        the_post_thumbnail('kopa-image-size-1');
                                                    ?>
                                                </div>
                                                <div class="entry-content">
                                                    <h6 class="entry-title"><a href="<?php echo $post_url; ?>"><?php echo $post_title; ?></a><span></span></h6>
                                                    <span class="entry-date clearfix"><span class="fa fa-clock-o"></span><span><?php echo get_the_date(); ?></span></span>
                                                    <?php the_excerpt(); ?>
                                                </div><!--entry-content-->
                                            </article><!--entry-item-->
                                        </li>
                                        <?php
                                    endwhile;
                                    ?>
                                </ul>
                                <div class="clearfix"></div>
                                <?php if ($related_portfolios->post_count > 3): ?>
                                    <div class="carousel-nav clearfix">
                                        <a id="prev-4" class="carousel-prev" href="#">&lt;</a>
                                        <a id="next-4" class="carousel-next" href="#">&gt;</a>
                                    </div><!--end:carousel-nav-->
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                    endif;
                    wp_reset_postdata();
                }
            }
        }
    }
}

function kopa_get_about_author() {
    if ('show' == get_option('kopa_theme_options_post_about_author', 'hide')) {
        global $post;
        $user_id = $post->post_author;
        $description = get_the_author_meta('description', $user_id);
        $email = get_the_author_meta('user_email', $user_id);
        $name = get_the_author_meta('display_name', $user_id);
        $link = trim(get_the_author_meta('user_url', $user_id));
        ?>

        <div class="about-author clearfix">
            <a class="avatar-thumb" href="<?php echo $link; ?>"><?php echo get_avatar($email, 90); ?></a>                                            
            <div class="author-content">
                <header class="clearfix">
                    <h4><?php _e('Posted by:', kopa_get_domain()); ?></h4>                    
                    <a class="author-name" href="<?php echo $link; ?>"><?php echo $name; ?></a>
                    <?php
                    $social_links['facebook'] = get_user_meta($user_id, 'facebook', true);
                    $social_links['twitter'] = get_user_meta($user_id, 'twitter', true);
                    $social_links['google-plus'] = get_user_meta($user_id, 'google-plus', true);

                    if ($social_links['facebook'] || $social_links['twitter'] || $social_links['google-plus']):
                        ?>                  
                        <ul class="clearfix social-link">                      
                            <li><?php _e('Follow:', kopa_get_domain()); ?></li>

                            <?php if ($social_links['facebook']): ?>
                                <li class="facebook-icon"><a target="_blank" data-icon="&#xf09a;" title="<?php _e('Facebook', kopa_get_domain()); ?>" href="<?php echo $social_links['facebook']; ?>"></a></li>
                            <?php endif; ?>

                            <?php if ($social_links['twitter']): ?>
                                <li class="twitter-icon"><a target="_blank" data-icon="&#xf099;" title="<?php _e('Twitter', kopa_get_domain()); ?>" class="twitter" href="<?php echo $social_links['twitter']; ?>"></a></li>
                            <?php endif; ?>

                            <?php if ($social_links['google-plus']): ?>
                                <li class="gplus-icon"><a target="_blank" data-icon="&#xf0d5;" title="<?php _e('Google+', kopa_get_domain()); ?>" class="twitter" href="<?php echo $social_links['google-plus']; ?>"></a></li>
                            <?php endif; ?>                            

                        </ul><!--social-link-->
                    <?php endif; ?>
                </header>
                <div><?php echo $description; ?></div>
            </div><!--author-content-->
        </div><!--about-author-->
        <?php
    }
}

function kopa_edit_user_profile($user) {
    ?>   
    <table class="form-table">
        <tr>
            <th><label for="facebook"><?php _e('Facebook', kopa_get_domain()); ?></label></th>
            <td>
                <input type="url" name="facebook" id="facebook" value="<?php echo esc_attr(get_the_author_meta('facebook', $user->ID)); ?>" class="regular-text" /><br />
                <span class="description"><?php _e('Please enter your Facebook URL', kopa_get_domain()); ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="twitter"><?php _e('Twitter', kopa_get_domain()); ?></label></th>
            <td>
                <input type="url" name="twitter" id="twitter" value="<?php echo esc_attr(get_the_author_meta('twitter', $user->ID)); ?>" class="regular-text" /><br />
                <span class="description"><?php _e('Please enter your Twitter URL', kopa_get_domain()); ?></span>
            </td>
        </tr>       
        <tr>
            <th><label for="google-plus"><?php _e('Google Plus', kopa_get_domain()); ?></label></th>
            <td>
                <input type="url" name="google-plus" id="google-plus" value="<?php echo esc_attr(get_the_author_meta('google-plus', $user->ID)); ?>" class="regular-text" /><br />
                <span class="description"><?php _e('Please enter your Google Plus URL', kopa_get_domain()); ?></span>
            </td>
        </tr>
    </table>
    <?php
}

function kopa_edit_user_profile_update($user_id) {
    if (!current_user_can('edit_user', $user_id))
        return false;

    update_user_meta($user_id, 'facebook', $_POST['facebook']);
    update_user_meta($user_id, 'twitter', $_POST['twitter']);
    update_user_meta($user_id, 'google-plus', $_POST['google-plus']);
}

function kopa_get_the_excerpt($excerpt) {
    if (is_main_query()) {
        if (is_category() || is_tag()) {
            $limit = get_option('gs_excerpt_max_length', 100);
            if (strlen($excerpt) > $limit) {
                $break_pos = strpos($excerpt, ' ', $limit);
                $visible = substr($excerpt, 0, $break_pos);
                return balanceTags($visible);
            } else {
                return $excerpt;
            }
        } else if (is_search()) {
            $keys = implode('|', explode(' ', get_search_query()));
            return preg_replace('/(' . $keys . ')/iu', '<span class="kopa-search-keyword">\0</span>', $excerpt);
        } else {
            return $excerpt;
        }
    }
}

function kopa_get_template_setting() {
    $kopa_setting = get_option('kopa_setting');
    $setting = array();

    if (is_home()) {
        $setting = $kopa_setting['home'];
    } else if (is_post_type_archive('portfolio')) {
        $setting = $kopa_setting['portfolio'];
    } else if (is_archive()) {
        if (is_category() || is_tag()) {
            $setting = get_option("kopa_category_setting_" . get_queried_object_id(), $kopa_setting['taxonomy']);
        } else if (is_tax('portfolio_project') || is_tax('portfolio_tag')) {
            $setting = $kopa_setting['portfolio'];
        } else {
            $setting = get_option("kopa_category_setting_" . get_queried_object_id(), $kopa_setting['archive']);
        }
    } else if (is_singular()) {
        if (is_singular('post')) {
            $setting = get_option("kopa_post_setting_" . get_queried_object_id(), $kopa_setting['post']);
        } else if (is_page()) {

            $setting = get_option("kopa_page_setting_" . get_queried_object_id());
            if (!$setting) {
                if (is_front_page()) {
                    $setting = $kopa_setting['front-page'];
                } else {
                    $setting = $kopa_setting['page'];
                }
            }
        } else {
            $setting = $kopa_setting['post'];
        }
    } else if (is_404()) {
        $setting = $kopa_setting['_404'];
    } else if (is_search()) {
        $setting = $kopa_setting['search'];
    }

    return $setting;
}

function kopa_content_get_gallery($content, $enable_multi = false) {
    return kopa_content_get_media($content, $enable_multi, array('gallery'));
}

function kopa_content_get_video($content, $enable_multi = false) {
    return kopa_content_get_media($content, $enable_multi, array('vimeo', 'youtube'));
}

function kopa_content_get_audio($content, $enable_multi = false) {
    return kopa_content_get_media($content, $enable_multi, array('audio', 'soundcloud'));
}

function kopa_content_get_media($content, $enable_multi = false, $media_types = array()) {
    $media = array();
    $regex_matches = '';
    $regex_pattern = get_shortcode_regex();
    preg_match_all('/' . $regex_pattern . '/s', $content, $regex_matches);
    foreach ($regex_matches[0] as $shortcode) {
        $regex_matches_new = '';
        preg_match('/' . $regex_pattern . '/s', $shortcode, $regex_matches_new);

        if (in_array($regex_matches_new[2], $media_types)) :
            $media[] = array(
                'shortcode' => $regex_matches_new[0],
                'type' => $regex_matches_new[2],
                'url' => $regex_matches_new[5]
            );
            if (false == $enable_multi) {
                break;
            }
        endif;
    }

    return $media;
}

function kopa_get_video_thumbnails_url($type, $url) {
    $thubnails = '';
    $matches = array();
    if ('youtube' === $type) {
        preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $url, $matches);
        $file_url = "http://gdata.youtube.com/feeds/api/videos/" . $matches[0] . "?v=2&alt=jsonc";
        $results = wp_remote_get($file_url);

        if (!is_wp_error($results)) {
            $json = json_decode($results['body']);
            $thubnails = $json->data->thumbnail->hqDefault;
        }
    } else if ('vimeo' === $type) {
        preg_match_all('#(http://vimeo.com)/([0-9]+)#i', $url, $matches);
        $imgid = $matches[2][0];

        $results = wp_remote_get("http://vimeo.com/api/v2/video/$imgid.php");

        if (!is_wp_error($results)) {
            $hash = unserialize($results['body']);
            $thubnails = $hash[0]['thumbnail_large'];
        }
    }
    return $thubnails;
}

function kopa_get_client_IP() {
    $IP = NULL;

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        //check if IP is from shared Internet
        $IP = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //check if IP is passed from proxy
        $ip_array = explode(",", $_SERVER['HTTP_X_FORWARDED_FOR']);
        $IP = trim($ip_array[count($ip_array) - 1]);
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        //standard IP check
        $IP = $_SERVER['REMOTE_ADDR'];
    }
    return $IP;
}

function kopa_get_post_meta($pid, $key = '', $single = false, $type = 'String', $default = '') {
    $data = get_post_meta($pid, $key, $single);
    switch ($type) {
        case 'Int':
            $data = (int) $data;
            return ($data >= 0) ? $data : $default;
            break;
        default:
            return ($data) ? $data : $default;
            break;
    }
}

function kopa_get_like_permission($pid) {
    $permission = 'disable';

    $key = 'kopa_' . kopa_get_domain() . '_like_by_' . kopa_get_client_IP();
    $is_voted = kopa_get_post_meta($pid, $key, true, 'Int');

    if (!$is_voted)
        $permission = 'enable';

    return $permission;
}

function kopa_get_like_count($pid) {
    $key = 'kopa_' . kopa_get_domain() . '_total_like';
    return kopa_get_post_meta($pid, $key, true, 'Int');
}

function kopa_total_post_count_by_month($month, $year) {
    $args = array(
        'monthnum' => (int) $month,
        'year' => (int) $year,
    );
    $the_query = new WP_Query($args);
    return $the_query->post_count;
    ;
}

function kopa_head() {
    $logo_margin_top = get_option('kopa_theme_options_logo_margin_top', 0);
    $logo_margin_left = get_option('kopa_theme_options_logo_margin_left', 0);
    $logo_margin_right = get_option('kopa_theme_options_logo_margin_right', 0);
    $logo_margin_bottom = get_option('kopa_theme_options_logo_margin_bottom', 0);
    $kopa_theme_options_color_code = get_option('kopa_theme_options_color_code', '#33bee5');

    echo "<style>
        #logo-image{
            margin-top:{$logo_margin_top}px;
            margin-left:{$logo_margin_left}px;
            margin-right:{$logo_margin_right}px;
            margin-bottom:{$logo_margin_bottom}px;
        } 
    </style>";

    /* ==================================================================================================
     * Custom CSS
     * ================================================================================================= */
    $kopa_theme_options_custom_css = htmlspecialchars_decode(stripslashes(get_option('kopa_theme_options_custom_css')));
    if ($kopa_theme_options_custom_css)
        echo "<style>{$kopa_theme_options_custom_css}</style>";

    /* ==================================================================================================
     * IE8 Fix CSS3
     * ================================================================================================= */
    echo "<style>
        .kopa-button,
        .sequence-wrapper .next,
        .sequence-wrapper .prev,
        .kopa-intro-widget ul li .entry-title span,
        #main-content .widget .widget-title span,
        #main-content .widget .widget-title,
        .kopa-featured-product-widget .entry-item .entry-thumb .add-to-cart,
        .hover-effect .mask a.link-gallery,
        .hover-effect .mask a.link-detail,
        .kopa-testimonial-widget .testimonial-detail .avatar,
        .kopa-testimonial-widget .testimonial-detail .avatar img,
        .list-container-2 ul li span,
        .kopa-testimonial-slider .avatar,
        .kopa-testimonial-slider .avatar img,
        .about-author .avatar-thumb,
        .about-author .avatar-thumb img,
        #comments h3, .kopa-related-post h3, #respond h3,
        #comments h3 span, .kopa-related-post h3 span, #respond h3 span,
        #comments .comment-avatar,
        #comments .comment-avatar img,
        .kopa-our-team-widget ul li .our-team-social-link li a,
        .kp-dropcap.color {
            behavior: url(" . get_template_directory_uri() . "/js/PIE.htc);
        }
    </style>";

    $favicon = get_option('kopa_theme_options_favicon_url');
    if ($favicon) {
        printf('<link rel="shortcut icon" type="image/x-icon"  href="%s">', $favicon);
    }

    $iphone_icon = get_option('kopa_theme_options_apple_iphone_icon_url');
    if ($iphone_icon) {
        printf('<link rel="apple-touch-icon" sizes="57x57" href="%s">', $iphone_icon);
    }

    $ipad_icon = get_option('kopa_theme_options_apple_ipad_icon_url');
    if ($ipad_icon) {
        printf('<link rel="apple-touch-icon" sizes="72x72" href="%s">', $ipad_icon);
    }

    $iphone_retina_icon = get_option('kopa_theme_options_apple_iphone_retina_icon_url');
    if ($iphone_retina_icon) {
        printf('<link rel="apple-touch-icon" sizes="114x114" href="%s">', $iphone_retina_icon);
    }

    $ipad_retina_icon = get_option('kopa_theme_options_apple_ipad_retina_icon_url');
    if ($ipad_retina_icon) {
        printf('<link rel="apple-touch-icon" sizes="144x144" href="%s">', $ipad_retina_icon);
    }
}

/* IE js header */

function kopa_ie_js_header() {
    echo '<!--[if lt IE 9]>' . "\n";
    echo '<script src="' . esc_url(get_template_directory_uri() . '/js/html5.js') . '"></script>' . "\n";
    echo '<script src="' . esc_url(get_template_directory_uri() . '/js/css3-mediaqueries.js') . '"></script>' . "\n";
    echo '<![endif]-->' . "\n";
}

/* ==============================================================================
 * Mobile Menu
  ============================================================================= */

class kopa_mobile_menu extends Walker_Nav_Menu {

    function start_el(&$output, $item, $depth = 0, $args = array(), $current_object_id = 0) {
        global $wp_query;
        $indent = ( $depth ) ? str_repeat("\t", $depth) : '';

        $class_names = $value = '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));

        if ($depth == 0)
            $class_names = $class_names ? ' class="' . esc_attr($class_names) . ' clearfix"' : 'class="clearfix"';
        else
            $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : 'class=""';

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $value . $class_names . '>';

        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .=!empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .=!empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .=!empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
        if ($depth == 0) {
            $item_output = $args->before;
            $item_output .= '<h3><a' . $attributes . '>';
            $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
            $item_output .= '</a></h3>';
            $item_output .= $args->after;
        } else {
            $item_output = $args->before;
            $item_output .= '<a' . $attributes . '>';
            $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
            $item_output .= '</a>';
            $item_output .= $args->after;
        }
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    function start_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        if ($depth == 0) {
            $output .= "\n$indent<span>+</span><div class='clear'></div><div class='menu-panel clearfix'><ul>";
        } else {
            $output .= '<ul>'; // indent for level 2, 3 ...
        }
    }

    function end_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        if ($depth == 0) {
            $output .= "$indent</ul></div>\n";
        } else {
            $output .= '</ul>';
        }
    }

}

// end mobile menu walker class

function kopa_new_excerpt_more($more) {
    return '...';
}

/**
 * Convert Hex Color to RGB using PHP
 * @link http://bavotasan.com/2011/convert-hex-color-to-rgb-using-php/
 */
function kopa_hex2rgba($hex, $alpha = false) {
    $hex = str_replace("#", "", $hex);

    if (strlen($hex) == 3) {
        $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
        $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
        $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }
    if ($alpha)
        return array($r, $g, $b, $alpha);

    return array($r, $g, $b);
}

/**
 * Custom background callback funtion for core custom background feature
 */
function kopa_custom_background_cb() {
    // $background is the saved custom image, or the default image.
    $background = set_url_scheme(get_background_image());

    // $color is the saved custom color.
    // A default has to be specified in style.css. It will not be printed here.
    $color = get_theme_mod('background_color');

    if (!$background && !$color)
        return;

    $style = $color ? "background-color: #$color;" : '';

    if ($background) {
        $image = " background-image: url('$background');";

        $repeat = get_theme_mod('background_repeat', get_theme_support('custom-background', 'default-repeat'));
        if (!in_array($repeat, array('no-repeat', 'repeat-x', 'repeat-y', 'repeat')))
            $repeat = 'repeat';
        $repeat = " background-repeat: $repeat;";

        $position = get_theme_mod('background_position_x', get_theme_support('custom-background', 'default-position-x'));
        if (!in_array($position, array('center', 'right', 'left')))
            $position = 'left';
        $position = " background-position: top $position;";

        $attachment = get_theme_mod('background_attachment', get_theme_support('custom-background', 'default-attachment'));
        if (!in_array($attachment, array('fixed', 'scroll')))
            $attachment = 'scroll';
        $attachment = " background-attachment: $attachment;";

        $style .= $image . $repeat . $position . $attachment;
    }
    ?>
    <style type="text/css" id="custom-background-css">
        body.kopa-boxed { <?php echo trim($style); ?> }
    </style>
    <?php
}

/**
 * Create a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @package Nictitate
 * 
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function kopa_wp_title($title, $sep) {
    global $paged, $page;

    if (is_feed()) {
        return $title;
    }

    // Add the site name.
    $title .= get_bloginfo('name');

    // Add the site description for the home/front page.
    $site_description = get_bloginfo('description', 'display');
    if ($site_description && ( is_home() || is_front_page() )) {
        $title = "$title $sep $site_description";
    }

    // Add a page number if necessary.
    if ($paged >= 2 || $page >= 2) {
        $title = "$title $sep " . sprintf(__('Page %s', kopa_get_domain()), max($paged, $page));
    }

    return $title;
}
