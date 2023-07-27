<?php

define('KOPA_THEME_NAME', 'Nictitate');
define('KOPA_DOMAIN', 'nictitate');
define('KOPA_CPANEL_IMAGE_DIR', get_template_directory_uri() . '/library/images/layout/');

/*
 * Initialize admin page, register widgets
 */
require trailingslashit(get_template_directory()) . '/library/kopa.php';

/*
 * Initialize layout settings and dynamic sidebar settings
 */
require trailingslashit(get_template_directory()) . '/library/ini.php';

/*
 * Get google fonts array
 */
require trailingslashit(get_template_directory()) . '/library/includes/google-fonts.php';

/*
 * Contain all ajax functions that use in this theme 
 */
require trailingslashit(get_template_directory()) . '/library/includes/ajax.php';

/*
 * Dynamic layout options for post, page and category
 */
require trailingslashit(get_template_directory()) . '/library/includes/metabox/post.php';
require trailingslashit(get_template_directory()) . '/library/includes/metabox/category.php';
require trailingslashit(get_template_directory()) . '/library/includes/metabox/page.php';

/*
 * Set up theme defaults and registers support for various WordPress features.
 * Contain many utility functions for frontend
 */
require trailingslashit(get_template_directory()) . '/library/front.php';

/*
 * Upload field in post (image will be used in sequence slider widget) 
 */
require trailingslashit(get_template_directory()) . '/library/options/options_post_slider_bg.php';

/*
 * Icon selection field for main menu item
 */
require trailingslashit(get_template_directory()) . '/library/custom-menu/kopa_custom_menu.php';

/*
 * Implement Custom Header features.
 */
require get_template_directory() . '/library/options/custom-header.php';


/**
 * Include the TGM_Plugin_Activation class.
 */
require_once get_template_directory() . '/class-tgm-plugin-activation.php';

add_action('tgmpa_register', 'kopa_register_required_plugins');

function kopa_register_required_plugins() {
    $plugins = array(
        array(
            'name' => 'Kopa Nictitate Toolkit',
            'slug' => 'kopa-nictitate-toolkit',
            'source' => 'http://downloads.wordpress.org/plugin/kopa-nictitate-toolkit.zip',
            'required' => true,
            'version' => '1.0.0',
            'force_activation' => false,
            'force_deactivation' => true,
        )
    );

    $config = array(
        'has_notices' => true,
        'is_automatic' => false
    );

    tgmpa($plugins, $config);
}
