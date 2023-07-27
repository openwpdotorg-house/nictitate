<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    
    <?php get_template_part( 'content', 'page' ); ?>
  
    <?php comments_template(); ?>

<?php endwhile; else : ?>

    <?php get_template_part( 'content', 'notfound' ); ?>

<?php endif; ?>