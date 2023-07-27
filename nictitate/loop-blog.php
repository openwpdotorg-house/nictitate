<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    <?php
    /**
     * Switch content in here 
     * content        : if standard post format
     * content-gallery: if gallery post format
     * content-audio  : if audio post format
     * content-video  : if video post format
     */ 
    get_template_part( 'content', get_post_format() ); 
    ?>

<?php endwhile; else : ?>

    <?php get_template_part( 'content', 'notfound' ); ?>

<?php endif; ?>