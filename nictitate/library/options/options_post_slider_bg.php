<?php
add_action('add_meta_boxes', 'kopa_slider_background_image_meta_box_add');

function kopa_slider_background_image_meta_box_add() {
    add_meta_box('kopa-slider-background-image-edit', __( 'Sequence Slider Background Image', kopa_get_domain() ), 'kopa_meta_box_slider_background_image_cb', 'post', 'normal', 'high');
}

function kopa_meta_box_slider_background_image_cb($post) {
    $dir = get_template_directory_uri() . '/library/js';
    wp_enqueue_script('jquery');
    wp_enqueue_script('kopa-uploader', "{$dir}/uploader.js", array('jquery'), NULL, TRUE);

    $slider_background_image = get_post_meta($post->ID, 'slider_background_image', true);

    wp_nonce_field('post_slider_background_image_meta_box_nonce', 'post_slider_background_image_meta_box_nonce');
    
    ?>
    <div class="kopa-content-box">
        <div class="kopa-box-head">
            <i class="icon-hand-right"></i>
            <span class="kopa-section-title">Upload Background Image</span>
        </div>
        <div class="kopa-box-body">
            <div class="kopa-element-box kopa-theme-options">
                <span class="kopa-component-title"><?php _e('Slider Background Image', kopa_get_domain()); ?></span>
                <p class="kopa-desc"><?php _e('Upload your own slider background image.', kopa_get_domain()); ?></p>                         
                <div class="clearfix">
                    <input class="left" type="text" value="<?php echo esc_attr( $slider_background_image ); ?>" id="slider_background_image" name="slider_background_image">
                    <button class="left btn btn-success upload_image_button" alt="slider_background_image"><i class="icon-circle-arrow-up"></i><?php _e('Upload', kopa_get_domain()); ?></button>
                </div>
            </div><!--kopa-element-box--> 
        </div><!-- kopa-box-body -->
    </div> <!-- kopa-content-box -->

    <?php
}

add_action('save_post', 'kopa_save_slider_background_image');

function kopa_save_slider_background_image($post_id) {

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!isset($_POST['post_slider_background_image_meta_box_nonce']) || !wp_verify_nonce($_POST['post_slider_background_image_meta_box_nonce'], 'post_slider_background_image_meta_box_nonce')) {
        return;
    }

    if (isset($_POST['slider_background_image'])) {
        update_post_meta($post_id, 'slider_background_image', $_POST['slider_background_image']);
    }
}