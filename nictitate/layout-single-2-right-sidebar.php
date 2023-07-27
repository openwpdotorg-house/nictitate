<?php 
$kopa_setting = kopa_get_template_setting();
$sidebars = $kopa_setting['sidebars'];
get_header(); ?>

<div id="main-content">
    
    <?php get_template_part('content', 'page-title'); ?>

    <div class="wrapper">
        <div class="row-fluid">
            <div class="span12">
                
                <div id="main-col">
                    
                    <?php get_template_part( 'contents' ); ?>
                    
                </div><!--main-col-->
                
                <div class="sidebar">
                    
                    <?php if ( is_active_sidebar($sidebars[0] ) )
                        dynamic_sidebar( $sidebars[0] );
                    ?>

                </div><!--sidebar-->
                
                <div class="clear"></div>
                
            </div><!--span12-->
        </div><!--row-fluid-->                
    </div><!--wrapper-->

</div>

<?php get_footer(); ?>