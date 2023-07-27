<?php
$kopa_action ='';
if (!is_admin() || !current_user_can("edit_themes"))
    die('Not allowed');

if (isset($_GET['action'])) {
    $kopa_action = $_GET['action'];
}
?>

<div id="kopa-top-bar">
    <a class="top-button <?php if($kopa_action == 'kopa_cpanel_theme_options' || $kopa_action =='') echo 'active';?>" href="<?php echo admin_url("/themes.php?page=kopa_cpanel_theme_options&action=kopa_cpanel_theme_options"); ?>">Theme Options</a>
    <a class="top-button <?php if($kopa_action == 'kopa_cpanel_sidebar_manager') echo 'active';?>" href="<?php echo admin_url("/themes.php?page=kopa_cpanel_theme_options&action=kopa_cpanel_sidebar_manager"); ?>">Sidebar Manager</a>
    <a class="top-button <?php if($kopa_action == 'kopa_cpanel_layout_manager') echo 'active';?>" href="<?php echo admin_url("/themes.php?page=kopa_cpanel_theme_options&action=kopa_cpanel_layout_manager"); ?>">Layout Manager</a>

</div>
<?php
switch ($kopa_action):

    case 'kopa_cpanel_layout_manager':
        include_once trailingslashit(get_template_directory()) . '/library/includes/cpanel/layout-manager.php';
        break;
    case 'kopa_cpanel_sidebar_manager':
        include_once trailingslashit(get_template_directory()) . '/library/includes/cpanel/sidebar-manager.php';
        break;
    default :
        include_once trailingslashit(get_template_directory()) . '/library/includes/cpanel/theme-options-page.php';
        break;

endswitch;
