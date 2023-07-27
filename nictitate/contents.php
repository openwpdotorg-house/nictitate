<?php
if ( is_home() ) {
    get_template_part( 'loop', 'blog' );
} elseif ( is_singular( 'product' ) ) {
    get_template_part( 'loop', 'content-single-product' );
} elseif ( is_single() ) {
    get_template_part( 'loop', 'single' );
} elseif ( is_page() ) {
    get_template_part( 'loop', 'page' );
} elseif ( is_post_type_archive('portfolio') || is_tax('portfolio_project') || is_tax('portfolio_tag') ) {
    get_template_part( 'loop', 'portfolio' );
} elseif ( is_post_type_archive( 'product' ) ) {
    get_template_part( 'loop', 'content-shop' );
} elseif ( is_tax( 'product_cat' ) || is_tax( 'product_tag' ) ) {
    get_template_part( 'loop', 'content-product-taxonomy' );
} else {
    get_template_part( 'loop', 'blog' );
}


