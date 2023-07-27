<?php 
$kopa_setting = kopa_get_template_setting();
$layout_id = $kopa_setting['layout_id'];
?>
<li id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <article class="entry-item standard-post <?php echo $layout_id == 'blog-2-right-sidebar' ? 'clearfix' : ''; ?>">
        <?php if ( has_post_thumbnail() ) : 
        $thumbnail_id = get_post_thumbnail_id();
        $thumbnail = wp_get_attachment_image_src( $thumbnail_id, 'kopa-image-size-0' );
        ?>
        <div class="entry-thumb hover-effect">
            <div class="mask">
                <a class="link-detail" href="<?php the_permalink(); ?>" data-icon="&#xf0c1;"></a>
            </div>
            <img src="<?php echo $thumbnail[0]; ?>" alt="<?php the_title(); ?>">
        </div>
        <?php endif; // endif has_post_thumbnail ?>
        <div class="entry-content">
            <header>
                <h6 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><span></span></h6>
                <span class="entry-date"><span class="fa fa-clock-o"></span><?php the_time( get_option( 'date_format' ) ); ?></span>
                <span class="entry-comments"><span class="fa fa-comment"></span><?php comments_popup_link(); ?></span>
            </header>
            <?php the_excerpt(); ?>
            <a class="more-link clearfix" href="<?php the_permalink(); ?>"><?php _e( 'Read more', kopa_get_domain() ); ?> <span class="fa fa-forward"></span></a>
        </div>
    </article><!--entry-item-->
</li>