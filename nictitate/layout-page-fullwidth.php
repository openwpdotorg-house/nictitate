<?php get_header(); ?>

<div id="main-content">
                        
    <?php get_template_part('content', 'page-title'); ?>
    
    <div class="wrapper">
        <div class="row-fluid">
            <div class="span12">

                <?php get_template_part( 'contents' ) ?>
            
            </div><!--span12-->
        </div><!--row-fluid-->
    </div><!--wrapper-->
    
</div><!--main-content-->


<?php get_footer(); ?>