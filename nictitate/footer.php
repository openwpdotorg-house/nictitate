<?php
$kopa_setting = kopa_get_template_setting();
$sidebars = $kopa_setting['sidebars'];
$total = count($sidebars);

$footer_sidebar[0] = ($kopa_setting) ? $sidebars[$total - 3] : 'sidebar_3';
$footer_sidebar[1] = ($kopa_setting) ? $sidebars[$total - 2] : 'sidebar_4';
$footer_sidebar[2] = ($kopa_setting) ? $sidebars[$total - 1] : 'sidebar_5';
?>
<div id="bottom-sidebar">
    <div class="wrapper">
        <div class="row-fluid">

            <div class="span4 widget-area-3">
                <?php
                if (is_active_sidebar($footer_sidebar[0]))
                    dynamic_sidebar($footer_sidebar[0]);
                ?>
            </div><!--span4-->

            <div class="span4 widget-area-4">
                <?php
                if (is_active_sidebar($footer_sidebar[1]))
                    dynamic_sidebar($footer_sidebar[1]);
                ?>
            </div><!--span4-->

            <div class="span4 widget-area-5">
                <?php
                if (is_active_sidebar($footer_sidebar[2]))
                    dynamic_sidebar($footer_sidebar[2]);
                ?>
            </div><!--span4-->

        </div><!--row-fluid-->
    </div><!--wrapper-->
</div><!--bottom-sidebar-->

<footer id="page-footer">
    <div class="wrapper">
        <div class="row-fluid">
            <div class="span12">
                <p id="copyright"><?php echo stripslashes(get_option('kopa_theme_options_copyright', 'Copyrights. &copy; 2014')); ?></p>
                <?php
                if (has_nav_menu('bottom-nav')) {
                    wp_nav_menu(array(
                        'theme_location' => 'bottom-nav',
                        'container' => '',
                        'items_wrap' => '<ul id="footer-menu" class="clearfix">%3$s</ul>',
                        'depth' => -1
                    ));
                }
                ?>
            </div><!--span12-->
        </div><!--row-fluid-->
    </div><!--wrapper-->
</footer><!--page-footer-->

<p id="back-top" style="display: block;">
    <a href="#top"><?php _e('Back to Top', kopa_get_domain()); ?></a>
</p>

</div><!--kopa-wrapper-->

<?php wp_footer(); ?>
</body>
</html>