<?php

add_action('after_setup_theme', 'kopa_after_setup_theme');

function kopa_after_setup_theme() {
    kopa_i18n();

    add_action('admin_menu', 'kopa_admin_menu');
    add_action('init', 'kopa_initial_database');
    add_action('init', 'kopa_add_exceprt_page');
    require trailingslashit(get_template_directory()) . '/library/includes/widgets.php';
    
    add_filter('get_avatar', 'kopa_get_avatar');
}
function kopa_add_exceprt_page() {
	add_post_type_support( 'page', 'excerpt' );
}
function kopa_admin_menu() {
    
    //General Setting Page
    $page_kopa_cpanel_theme_options = add_theme_page(
            __('Theme Options', kopa_get_domain()), __('Theme Options', kopa_get_domain()), 'edit_themes', 'kopa_cpanel_theme_options', 'kopa_cpanel_theme_options'
    );
    add_action('admin_print_scripts-' . $page_kopa_cpanel_theme_options, 'kopa_admin_print_scripts');
    add_action('admin_print_styles-' . $page_kopa_cpanel_theme_options, 'kopa_admin_print_styles');

}

function kopa_cpanel_theme_options() {
    include trailingslashit(get_template_directory()) . '/library/includes/cpanel/theme-options.php';
}

function kopa_admin_print_scripts() {
    $dir = get_template_directory_uri() . '/library/js';

    if (!wp_script_is('jquery'))
        wp_enqueue_script('jquery');

    wp_localize_script('jquery', 'kopa_variable', kopa_localize_script());

    if (!wp_script_is('wp-color-picker'))
        wp_enqueue_script('wp-color-picker');
    if (!wp_script_is('kopa-uploader'))
        wp_enqueue_script('kopa-colorpicker', "{$dir}/colorpicker.js", array('jquery'), NULL, TRUE);

    if (!wp_script_is('kopa-admin-utils'))
        wp_enqueue_script('kopa-admin-utils', "{$dir}/utils.js", array('jquery'), NULL, TRUE);

    if (!wp_script_is('kopa-admin-jquery-form'))
        wp_enqueue_script('kopa-admin-jquery-form', "{$dir}/jquery.form.js", array('jquery'), NULL, TRUE);

    if (!wp_script_is('kopa-admin-script'))
        wp_enqueue_script('kopa-admin-script', "{$dir}/script.js", array('jquery'), NULL, TRUE);

    if (!wp_script_is('kopa-admin-bootstrap'))
        wp_enqueue_script('kopa-admin-bootstrap', "{$dir}/bootstrap.min.js", array('jquery'), NULL, TRUE);

    if (!wp_script_is('thickbox'))
        wp_enqueue_script('thickbox', null, array('jquery'), NULL, TRUE);

    if (!wp_script_is('kopa-uploader'))
        wp_enqueue_script('kopa-uploader', "{$dir}/uploader.js", array('jquery'), NULL, TRUE);
}

function kopa_localize_script() {
    return array(
        'AjaxUrl' => admin_url('admin-ajax.php'),
        'google_fonts' => kopa_get_google_font_array(),
        'kopa_icon_font' => unserialize(KOPA_ICON)
    );
}

function kopa_admin_print_styles() {
    $dir = get_template_directory_uri() . '/library/css';
    wp_enqueue_style('kopa-admin-style', "{$dir}/style.css", array(), NULL);
    wp_enqueue_style('thickbox.css', '/' . WPINC . '/js/thickbox/thickbox.css', array(), NULL);
    wp_enqueue_style('open-sans-font', 'http://fonts.googleapis.com/css?family=Open+Sans:400,700,600', array(), NULL);
    if (!wp_style_is('wp-color-picker'))
        wp_enqueue_style('wp-color-picker');


    $google_fonts = kopa_get_google_font_array();
    $current_heading_font = get_option('kopa_theme_options_heading_font_family', array(), NULL);
    $current_content_font = get_option('kopa_theme_options_content_font_family');
    $current_main_nav_font = get_option('kopa_theme_options_main_nav_font_family');
    $current_wdg_sidebar_font = get_option('kopa_theme_options_wdg_sidebar_font_family');
    $current_wdg_main_font = get_option('kopa_theme_options_wdg_main_font_family');
    $current_wdg_footer_font = get_option('kopa_theme_options_wdg_footer_font_family');
    $current_slider_font = get_option('kopa_theme_options_slider_font_family');

    $load_font_array = array();
    if ($current_heading_font) {
        array_push($load_font_array, $current_heading_font);
    }
    if ($current_content_font && !in_array($current_content_font, $load_font_array)) {
        array_push($load_font_array, $current_content_font);
    }
    if ($current_main_nav_font && !in_array($current_main_nav_font, $load_font_array)) {
        array_push($load_font_array, $current_main_nav_font);
    }
    if ($current_wdg_sidebar_font && !in_array($current_wdg_sidebar_font, $load_font_array)) {
        array_push($load_font_array, $current_wdg_sidebar_font);
    }


    if ($current_wdg_main_font && !in_array($current_wdg_main_font, $load_font_array)) {
        array_push($load_font_array, $current_wdg_main_font);
    }

    if ($current_wdg_footer_font && !in_array($current_wdg_footer_font, $load_font_array)) {
        array_push($load_font_array, $current_wdg_footer_font);
    }
    if ($current_slider_font && !in_array($current_slider_font, $load_font_array)) {
        array_push($load_font_array, $current_slider_font);
    }

    foreach ($load_font_array as $current_font) {

        if ($current_font != '') {
            $google_font_family = $google_fonts[$current_font]['family'];
            $temp_font_name = str_replace(' ', '+', $google_font_family);
            $font_url = 'http://fonts.googleapis.com/css?family=' . $temp_font_name . ':300,300italic,400,400italic,700,700italic&subset=latin';
            wp_enqueue_style('Google-Font-' . $temp_font_name, $font_url, array(), NULL);
        }
    }
}



function kopa_get_domain() {
    return constant('KOPA_DOMAIN');
}

function kopa_i18n() {
    load_theme_textdomain(kopa_get_domain(), get_template_directory() . '/languages');
}

/* =====================================================================================
 * Add Style and script for categories and post edit page
  ==================================================================================== */
add_action('admin_enqueue_scripts', 'kopa_category_scripts', 10, 1);

function kopa_category_scripts($hook) {
    if ($hook == 'edit-tags.php' or $hook == 'post-new.php' or $hook == 'post.php' or $hook == 'widgets.php') {
        wp_enqueue_script('jquery');
        wp_localize_script('jquery', 'kopa_variable', kopa_localize_script());
        wp_enqueue_script('kopa-admin-script', get_template_directory_uri() . '/library/js/script.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-admin-bootstrap', get_template_directory_uri() . '/library/js/bootstrap.min.js', array('jquery'), NULL, TRUE);
        wp_enqueue_style('kopa-admin-style', get_template_directory_uri() . '/library/css/style.css', array(), NULL);
        wp_enqueue_style('kopa-icon-style', get_template_directory_uri() . '/css/font-awesome.css', array(), NULL);
    }
}

/* =====================================================================================
 * Add Style and script for Widget page
  ==================================================================================== */
add_action('admin_enqueue_scripts', 'kopa_widget_page_scripts', 10, 1);

function kopa_widget_page_scripts($hook) {
    if ($hook == 'widgets.php') {
        wp_enqueue_script('jquery');
        if (!wp_script_is('thickbox'))
            wp_enqueue_script('thickbox', null, array('jquery'), NULL, TRUE);

        if (!wp_script_is('kopa-uploader'))
            wp_enqueue_script('kopa-uploader', get_template_directory_uri() ."/library/js/uploader.js", array('jquery'), NULL, TRUE);
        wp_enqueue_style('thickbox.css', '/' . WPINC . '/js/thickbox/thickbox.css', array(), NULL);
        wp_enqueue_style('kopa-admin-style', get_template_directory_uri() . '/library/css/widget.css', array(), NULL);
    }
}

function kopa_log($message) {
    if (WP_DEBUG === true) {
        if (is_array($message) || is_object($message)) {
            error_log(print_r($message, true));
        } else {
            error_log($message);
        }
    }
}
function kopa_get_avatar($avatar) {
    $avatar = str_replace('"', "'", $avatar);
    return str_replace("class='", "class='author-avatar ", $avatar);
}
