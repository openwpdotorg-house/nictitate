<div class="page-title-wrapper">
    <div class="page-title">
        <div class="wrapper">
            <div class="row-fluid">
                <div class="span12">
                    <h3><?php 
                    if ( is_search() ) {
                        _e( 'Search', kopa_get_domain() ); 
                    } elseif( is_tax( 'product_cat' ) || is_tax( 'product_tag' ) ) {
                        $queried_object = get_queried_object();
                        echo $queried_object->name;
                    } elseif( is_post_type_archive('product') && jigoshop_get_page_id('shop') ) {   
                        echo get_the_title( jigoshop_get_page_id('shop') );
                    } else {
                        single_post_title();
                    }
                    ?></h3>

                    <?php kopa_breadcrumb(); ?>

                </div><!--span12-->
            </div><!--row-fluid-->
        </div><!--wrapper-->
    </div><!--page-title-->
</div><!--page-title-wrapper-->