<?php $kopa_setting = kopa_get_template_setting();
$layout_id = $kopa_setting['layout_id'];
?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php if ( has_post_thumbnail() && get_post_format() == '' ) : 
        $thumbnail_id = get_post_thumbnail_id();
        $thumbnail = wp_get_attachment_image_src( $thumbnail_id, 'kopa-image-size-0' );
    ?>
        <div class="entry-thumb">
            <img src="<?php echo $thumbnail[0]; ?>" alt="<?php the_title(); ?>">
        </div>
    <?php endif; ?>
    <header class="<?php echo $layout_id == 'single-2-right-sidebar' ? 'clearfix' : ''; ?>">                                    
        <h1 class="entry-title"><?php the_title(); ?><span></span></h1>
        <span class="entry-date"><span class="fa fa-clock-o"></span><?php the_time( get_option( 'date_format' ) ); ?></span>
        <span class="entry-comments"><span class="fa fa-comment"></span><?php comments_popup_link(); ?></span>
    </header>

    <div class="elements-box">
        <?php the_content(); ?>
    
        <div class="page-pagination">

            <?php wp_link_pages(); ?>

        </div>
    </div>

    <div class="clear"></div>
    
    <footer class="clearfix">
        <?php get_template_part('post', 'navigation'); ?>
    </footer>
</div><!--entry-box-->