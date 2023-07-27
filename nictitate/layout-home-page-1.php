<?php 
$kopa_setting = kopa_get_template_setting(); 
$sidebars = $kopa_setting['sidebars'];

get_header(); ?>

<div id="main-content"> 
    <div class="widget">
        <?php if ( is_active_sidebar( $sidebars[0] ) ) 
            dynamic_sidebar( $sidebars[0] );
        ?>
    </div>

    <div class="wrapper">

        <div class="row-fluid">
        
            <div class="span12 clearfix">

                <?php // print content of front page 
                if ( have_posts() ) {
                    while ( have_posts() ) {
                        the_post();

                        if ( get_the_content() ) {
                            get_template_part( 'content', 'page' );
                        }
                    }
                } ?>
            
                <?php if ( is_active_sidebar( $sidebars[1] ) ) 
                    dynamic_sidebar( $sidebars[1] );
                ?>
            
            </div><!--span12-->
            
        </div><!--row-fluid-->

    </div><!--wrapper-->

    <div class="wrapper">
        <div class="row-fluid">
            <div class="widget-area-1 span6">

                <?php if ( is_active_sidebar( $sidebars[2] ) ) 
                    dynamic_sidebar( $sidebars[2] );
                ?>
                
            </div><!--widget-area-1-->
            
            <div class="widget-area-2 span6">

                <?php if ( is_active_sidebar( $sidebars[3] ) ) 
                    dynamic_sidebar( $sidebars[3] );
                ?>
                
            </div><!--widget-area-2-->
        </div><!--row-fluid-->
    </div><!--wrapper-->

    <div class="wrapper">

        <div class="row-fluid">
        
            <div class="span12 clearfix">
            
                <?php if ( is_active_sidebar( $sidebars[4] ) ) 
                    dynamic_sidebar( $sidebars[4] );
                ?>
            
            </div><!--span12-->
            
        </div><!--row-fluid-->

    </div><!--wrapper-->

</div> <!-- #main-menu -->


<?php if ( is_active_sidebar( $sidebars[5] ) || is_active_sidebar( $sidebars[6] ) || is_active_sidebar( $sidebars[7] ) || is_active_sidebar( $sidebars[8] ) ) { ?>

    <div id="page-bottom">
        <div class="wrapper">
            <div class="row-fluid">
                
                <div class="span3">

                    <?php if ( is_active_sidebar( $sidebars[5] ) ) 
                        dynamic_sidebar( $sidebars[5] );
                    ?>
                    
                </div><!--span3-->
                
                <div class="span3">

                    <?php if ( is_active_sidebar( $sidebars[6] ) ) 
                        dynamic_sidebar( $sidebars[6] );
                    ?>
                    
                </div><!--span3-->
                
                <div class="span3">

                    <?php if ( is_active_sidebar( $sidebars[7] ) ) 
                        dynamic_sidebar( $sidebars[7] );
                    ?>
                    
                </div><!--span3-->
                
                <div class="span3">

                    <?php if ( is_active_sidebar( $sidebars[8] ) ) 
                        dynamic_sidebar( $sidebars[8] );
                    ?>
                    
                </div><!--span3-->
                
            </div><!--row-fluid-->
        </div><!--wrapper-->
    </div><!--page-bottom-->

<?php } ?>
    
<?php get_footer(); ?>
