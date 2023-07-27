<?php 
$kopa_setting = kopa_get_template_setting();
$sidebars = $kopa_setting['sidebars'];
get_header(); ?>

<div id="main-content">
                        
    <?php get_template_part('content', 'page-title'); ?>

    <div class="wrapper">
        <div class="row-fluid">
            <div class="span12">
                <?php if ( is_active_sidebar( $sidebars[0] ) )
                    dynamic_sidebar( $sidebars[0] );
                ?>
            </div><!--span12-->
        </div><!--row-fluid-->
    </div><!--wrapper-->

    <div class="wrapper full-width">
        
        <?php if ( is_active_sidebar( $sidebars[1] ) )
            dynamic_sidebar( $sidebars[1] );
        ?>
        
    </div><!--wrapper-->

</div><!--main-content-->

<?php get_footer(); ?>