<?php 
$kopa_setting = kopa_get_template_setting();
$sidebars = $kopa_setting['sidebars'];

get_header(); ?>

<div id="main-content">
                
    <div class="wrapper">
        <div class="row-fluid">
            <div class="span12 clearfix">
                <?php if ( is_active_sidebar( $sidebars[0] ) )
                    dynamic_sidebar( $sidebars[0] );
                ?>
            </div><!--span12-->
        </div><!--row-fluid-->
    </div><!--wrapper-->
    
    <div class="widget kopa-portfolio-widget">
        <div class="wrapper">
            <ul id="container" class="clearfix da-thumbs">
            
                <?php get_template_part( 'contents' ); ?>

            </ul> <!-- #container -->
        </div><!--wrapper-->    
    </div><!--widget-->
    
</div><!--main-content-->

<?php get_footer(); ?>