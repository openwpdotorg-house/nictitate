<?php 
$args['post_type'] = 'portfolio';
$args['posts_per_page'] = -1;

if ( is_tax('portfolio_project') ) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'portfolio_project',
            'field'    => 'id',
            'terms'    => array( get_queried_object_id() )
        )
    );
} elseif ( is_tax('portfolio_tag') ) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'portfolio_tag',
            'field'    => 'id',
            'terms'    => array( get_queried_object_id() )
        )
    );
}

query_posts($args);

if ( have_posts() ) : while ( have_posts() ) : the_post(); 
    $portfolio_thumbnail_size = get_post_meta( get_the_ID(), 'portfolio_thumbnail_size', true );
    $item_image_size = '';
    $item_class = '';

    if ($portfolio_thumbnail_size == '118x118') {
        $item_image_size = 'kopa-image-size-5';
    }
    elseif ($portfolio_thumbnail_size == '118x239') {
        $item_image_size = 'kopa-image-size-6';
        $item_class = 'height2';
    }
    elseif ($portfolio_thumbnail_size == '239x118') {
        $item_image_size = 'kopa-image-size-7';
        $item_class = 'width2';
    }
    else {
        $item_image_size = 'kopa-image-size-8';
        $item_class = 'width2 height2';
    }

    $thumbnail_id = get_post_thumbnail_id();
    $thumbnail = wp_get_attachment_image_src( $thumbnail_id, $item_image_size );
    $full_thumbnail = wp_get_attachment_image_src( $thumbnail_id, 'full' );
?>

    <li class="element <?php echo $item_class; ?>">
      <div class="da-thumbs-hover">
        <?php the_post_thumbnail( $item_image_size ); ?>
        <p>
            <a class="link-gallery" href="<?php echo $full_thumbnail[0]; ?>" rel="prettyPhoto[gallery]"><?php the_title(); ?></a>
            <a class="link-detail" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </p>
      </div>
    </li>

<?php endwhile; else : ?>

    <?php get_template_part('content', 'notfound'); ?>

<?php endif; 

wp_reset_query();

?>