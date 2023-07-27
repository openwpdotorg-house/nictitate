<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    
    <?php get_template_part( 'content-single', get_post_format() ); ?>

    <?php if (get_option('kopa_theme_options_post_about_author', 'show') == 'show') : ?>
        <div class="about-author clearfix">
            <a class="avatar-thumb" href="<?php echo get_author_posts_url( get_the_author_meta('ID') ); ?>"><?php echo get_avatar( get_the_author_meta('ID'), 87 ); ?></a>                                
            <div class="author-content">
                <header class="clearfix">
                    <h4><?php _e('About the author:', kopa_get_domain()); ?></h4>
                    <?php the_author_posts_link(); ?>                                       
                </header>
                <p><?php echo get_the_author_meta('description'); ?></p>                                
            </div><!--author-content-->
        </div><!--about-author-->
    <?php endif; // endif show about author ?>
    
    <?php $tags = get_the_terms( get_the_ID(), 'post_tag' );
    
    if ( ! empty( $tags ) ) : ?>
    
        <div class="tag-box">
            <span><?php _e('Tags', kopa_get_domain()); ?>:</span>
            <?php the_tags('', ' '); ?>
        </div><!--tag-box-->
    
    <?php endif; ?>
  
    <?php if ( is_singular('post') )
        kopa_get_related_articles(); 
    elseif ( is_singular('portfolio') ) 
        kopa_get_related_portfolio(); ?>
    
    <?php comments_template(); ?>

<?php endwhile; else : ?>

    <?php get_template_part( 'content', 'notfound' ); ?>

<?php endif; ?>