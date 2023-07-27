<?php
add_action('widgets_init', 'kopa_widgets_init');

function kopa_widgets_init() {
    register_widget('Kopa_Widget_Text');
    register_widget('Kopa_Widget_Tagline');
    register_widget('Kopa_Widget_Posts_List');
    register_widget('Kopa_Widget_Posts_Carousel');
    register_widget('Kopa_Widget_Socials');
    register_widget('Kopa_Widget_Subscribe');
    register_widget('Kopa_Widget_Flickr');
    register_widget('Kopa_Widget_Contact_Form');
    register_widget('Kopa_Widget_Categories');
    register_widget('Kopa_Widget_About');
    register_widget('Kopa_Widget_Sequence_Slider');

    if ( function_exists( 'kopa_nictitate_toolkit_init' ) ) {
        register_widget('Kopa_Widget_Services_Intro');
        register_widget('Kopa_Widget_Services_Tabs');
        register_widget('Kopa_Widget_Testimonials');
        register_widget('Kopa_Widget_Skill');
        register_widget('Kopa_Widget_Services');
        register_widget('Kopa_Widget_Clients');
        register_widget('Kopa_Widget_Portfolios');
        register_widget('Kopa_Widget_Staffs');
    }
}

add_action('admin_enqueue_scripts', 'kopa_widget_admin_enqueue_scripts');

function kopa_widget_admin_enqueue_scripts($hook) {
    if ('widgets.php' === $hook) {
        $dir = get_template_directory_uri() . '/library';
        wp_enqueue_style('kopa_widget_admin', "{$dir}/css/widget.css");
        wp_enqueue_script('kopa_widget_admin', "{$dir}/js/widget.js", array('jquery'));
    }
}

function kopa_widget_article_build_query($query_args = array()) {
    $args = array(
        'post_type' => array('post'),
        'posts_per_page' => $query_args['number_of_article']
    );

    $tax_query = array();

    if ($query_args['categories']) {
        $tax_query[] = array(
            'taxonomy' => 'category',
            'field' => 'id',
            'terms' => $query_args['categories']
        );
    }
    if ($query_args['tags']) {
        $tax_query[] = array(
            'taxonomy' => 'post_tag',
            'field' => 'id',
            'terms' => $query_args['tags']
        );
    }
    if ($query_args['relation'] && count($tax_query) == 2)
        $tax_query['relation'] = $query_args['relation'];

    if ($tax_query) {
        $args['tax_query'] = $tax_query;
    }

    switch ($query_args['orderby']) {
        case 'popular':
            $args['meta_key'] = 'kopa_' . kopa_get_domain() . '_total_view';
            $args['orderby'] = 'meta_value_num';
            break;
        case 'most_comment':
            $args['orderby'] = 'comment_count';
            break;
        case 'random':
            $args['orderby'] = 'rand';
            break;
        default:
            $args['orderby'] = 'date';
            break;
    }
    if (isset($query_args['post__not_in']) && $query_args['post__not_in']) {
        $args['post__not_in'] = $query_args['post__not_in'];
    }
    return new WP_Query($args);
}

function kopa_widget_posttype_build_query( $query_args = array() ) {
    $default_query_args = array(
        'post_type'      => 'post',
        'posts_per_page' => -1,
        'post__not_in'   => array(),
        'ignore_sticky_posts' => 1,
        'categories'     => array(),
        'tags'           => array(),
        'relation'       => 'OR',
        'orderby'        => 'lastest',
        'cat_name'       => 'category',
        'tag_name'       => 'post_tag'
    );

    $query_args = wp_parse_args( $query_args, $default_query_args );

    $args = array(
        'post_type'           => $query_args['post_type'],
        'posts_per_page'      => $query_args['posts_per_page'],
        'post__not_in'        => $query_args['post__not_in'],
        'ignore_sticky_posts' => $query_args['ignore_sticky_posts']
    );

    $tax_query = array();

    if ( $query_args['categories'] ) {
        $tax_query[] = array(
            'taxonomy' => $query_args['cat_name'],
            'field'    => 'id',
            'terms'    => $query_args['categories']
        );
    }
    if ( $query_args['tags'] ) {
        $tax_query[] = array(
            'taxonomy' => $query_args['tag_name'],
            'field'    => 'id',
            'terms'    => $query_args['tags']
        );
    }
    if ( $query_args['relation'] && count( $tax_query ) == 2 )
        $tax_query['relation'] = $query_args['relation'];

    if ( $tax_query ) {
        $args['tax_query'] = $tax_query;
    }

    switch ( $query_args['orderby'] ) {
    case 'popular':
        $args['meta_key'] = 'kopa_' . kopa_get_domain() . '_total_view';
        $args['orderby'] = 'meta_value_num';
        break;
    case 'most_comment':
        $args['orderby'] = 'comment_count';
        break;
    case 'random':
        $args['orderby'] = 'rand';
        break;
    default:
        $args['orderby'] = 'date';
        break;
    }

    return new WP_Query( $args );
}

/**
 * @since Nictitate 1.0
 */
class Kopa_Widget_Text extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'kopa_widget_text', 'description' => __('Arbitrary text, HTML or shortcodes', kopa_get_domain()));
        $control_ops = array('width' => 600, 'height' => 400);
        parent::__construct('kopa_widget_text', __('Kopa Text', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $text = apply_filters('widget_text', empty($instance['text']) ? '' : $instance['text'], $instance);

        echo $before_widget;
        if (!empty($title)) {
            echo $before_title . '<span data-icon="&#xf040;"></span>' . $title . $after_title;
        }
        ?>
        <?php echo!empty($instance['filter']) ? wpautop($text) : $text; ?>
        <?php
        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        if (current_user_can('unfiltered_html'))
            $instance['text'] = $new_instance['text'];
        else
            $instance['text'] = stripslashes(wp_filter_post_kses(addslashes($new_instance['text'])));
        $instance['filter'] = isset($new_instance['filter']);
        return $instance;
    }

    function form($instance) {
        $instance = wp_parse_args((array) $instance, array('title' => '', 'text' => ''));
        $title = strip_tags($instance['title']);
        $text = esc_textarea($instance['text']);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>        
        <ul class="kopa_shortcode_icons">
            <?php
            $shortcodes = array(
                'one_half' => 'One Half Column',
                'one_third' => 'One Thirtd Column',
                'two_third' => 'Two Third Column',
                'one_fourth' => 'One Fourth Column',
                'three_fourth' => 'Three Fourth Column',
                'dropcaps' => 'Add Dropcaps Text',
                'button' => 'Add A Button',
                'alert' => 'Add A Alert Box',
                'tabs' => 'Add A Tabs Content',
                'accordions' => 'Add A Accordions Content',
                'toggle' => 'Add A Toggle Content',
                'contact_form' => 'Add A Contact Form',
                // 'posts_lastest' => 'Add A List Lastest Post',
                // 'posts_popular' => 'Add A List Popular Post',
                // 'posts_most_comment' => 'Add A List Most Comment Post',
                // 'posts_random' => 'Add A List Random Post',
                'youtube' => 'Add A Yoube Video Box',
                'vimeo' => 'Add A Vimeo Video Box'
            );
            foreach ($shortcodes as $rel => $title):
                ?>
                <li>
                    <a onclick="return kopa_shortcode_icon_click('<?php echo $rel; ?>', jQuery('#<?php echo $this->get_field_id('text'); ?>'));" href="#" class="<?php echo "kopa-icon-{$rel}"; ?>" rel="<?php echo $rel; ?>" title="<?php echo $title; ?>"></a>
                </li>
            <?php endforeach; ?>
        </ul>        
        <textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
        <p>
            <input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs', kopa_get_domain()); ?></label>
        </p>
        <?php
    }

}

/**
 * @since Nictitate 1.0
 */
class Kopa_Widget_Services_Intro extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kopa-intro-widget', 'description' => __('Display a services intro widget', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_services_intro', __('Kopa Services Intro', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

        $query_args['post_type'] = 'services';
        $query_args['cat_name'] = 'service_category';
        $query_args['tag_name'] = 'service_tag';
        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = esc_attr($instance['relation']);
        $query_args['tags'] = $instance['tags'];
        $query_args['posts_per_page'] = (int) $instance['posts_per_page'];
        $query_args['orderby'] = $instance['orderby'];

        $services = kopa_widget_posttype_build_query($query_args);

        if ( $services->post_count == 0 )
            return;

        echo $before_widget;

        if ( ! empty ( $title ) )
            echo $before_title . '<span data-icon="&#xf013;"></span>' . $title . $after_title;
            
        $service_index = 1;
        $kopa_icon = unserialize(KOPA_ICON); ?>
        
        <ul class="clearfix">

        <?php while ($services->have_posts()):
            $services->the_post();
            // initialize & reset for each loop
            $icon_class = '';

            $icon_class = get_post_meta(get_the_ID(), 'icon_class', true);

            // for font awesome icon class
            if ( $icon_class ) {
                $icon_class = 'fa fa-' . $icon_class;
            }

            // get service url
            $service_external_page = get_post_meta(get_the_ID(), 'service_external_page', true);
            $service_static_page = get_post_meta(get_the_ID(), 'service_static_page', true);

            if (!empty($service_external_page)) {
                $service_url = esc_url($service_external_page);
            } elseif (!empty($service_static_page)) {
                $service_url = get_page_link($service_static_page);
            } else {
                $service_url = get_permalink();
            }
        ?>
            <li>
                <article class="entry-item">
                    <h2 class="entry-title clearfix"><span class="<?php echo $icon_class; ?>"></span><a href="<?php echo $service_url; ?>"><?php the_title(); ?></a></h2>
                    <?php the_excerpt(); ?>
                    <a href="<?php echo $service_url; ?>" class="more-link clearfix"><?php _e( 'Read more', kopa_get_domain() ); ?> <span class="fa fa-forward"></span></a>
                </article>
            </li>
        <?php 
        if ( $service_index % 3 == 0 && $service_index != $services->post_count )
            echo '</ul><ul class="clearfix mt-20">';
        
        $service_index++;

        endwhile; ?>

        </ul>

        <?php wp_reset_postdata();
        echo $after_widget;
    }

    function form($instance) {
        $default = array(
            'title' => '',
            'categories' => array(),
            'relation' => 'OR',
            'tags' => array(),
            'posts_per_page' => 3,
            'orderby' => 'lastest',
        );
        $instance = wp_parse_args((array) $instance, $default);
        $title = strip_tags($instance['title']);

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['posts_per_page'] = (int) $instance['posts_per_page'];
        $form['orderby'] = $instance['orderby'];
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_terms('service_category');
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, (isset($form['categories']) ? $form['categories'] : array()))) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                <?php
                $relation = array(
                    'AND' => __('And', kopa_get_domain()),
                    'OR' => __('Or', kopa_get_domain())
                );
                foreach ($relation as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $tags = get_terms('service_tag');
                foreach ($tags as $tag) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, (isset($form['tags']) ? $form['tags'] : array()))) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('posts_per_page'); ?>"><?php _e('Number of items:', kopa_get_domain()); ?></label>                
            <input class="widefat" id="<?php echo $this->get_field_id('posts_per_page'); ?>" name="<?php echo $this->get_field_name('posts_per_page'); ?>" value="<?php echo $form['posts_per_page']; ?>" type="number" min="1">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'lastest' => __('Lastest', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain()),
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>

        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['posts_per_page'] = (int) $new_instance['posts_per_page'];
        $instance['orderby'] = $new_instance['orderby'];

        return $instance;
    }
}

/**
 * @since Nictitate 1.0
 */
class Kopa_Widget_Services_Tabs extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => '', 'description' => __('Display a services tabs widget', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_services_tabs', __('Kopa Services Tabs', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

        $query_args['post_type'] = 'services';
        $query_args['cat_name'] = 'service_category';
        $query_args['tag_name'] = 'service_tag';
        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = esc_attr($instance['relation']);
        $query_args['tags'] = $instance['tags'];
        $query_args['posts_per_page'] = (int) $instance['posts_per_page'];
        $query_args['orderby'] = $instance['orderby'];

        $services = kopa_widget_posttype_build_query($query_args);

        if ( $services->post_count == 0 )
            return;

        echo $before_widget;

        if ( ! empty ( $title ) )
            echo $before_title . '<span data-icon="&#xf013;"></span>' . $title . $after_title;
            
        $kopa_icon = unserialize( KOPA_ICON ); ?>

        <div class="list-container-2">
            <ul class="tabs-2 clearfix">

        <?php while ( $services->have_posts() ) :
            $services->the_post();
            // initialize & reset for each loop
            $icon_class = '';

            $icon_class = get_post_meta(get_the_ID(), 'icon_class', true);

            if ( $icon_class ) {
                $icon_class = 'fa fa-' . $icon_class;
            }
        ?>
            <li><a href="#<?php echo $this->get_field_id( 'tab' ) . get_the_ID(); ?>" class="clearfix"><span class="<?php echo $icon_class; ?>"></span><?php the_title(); ?></a></li>
        <?php endwhile; ?>
            
            </ul><!--tabs-2-->
        </div>

        <div class="tab-container-2">
            <?php while ($services->have_posts()) : $services->the_post(); ?>
                <div class="tab-content-2" 
                    id="<?php echo $this->get_field_id( 'tab' ) . get_the_ID(); ?>">                        
                    <p><?php echo strip_tags( get_the_content() ); ?></p>
                </div><!--tab-content-2-->       
            <?php endwhile; ?>    
        </div>

        <?php wp_reset_postdata();
        echo $after_widget;
    }

    function form($instance) {
        $default = array(
            'title' => '',
            'categories' => array(),
            'relation' => 'OR',
            'tags' => array(),
            'posts_per_page' => 3,
            'orderby' => 'lastest',
        );
        $instance = wp_parse_args((array) $instance, $default);
        $title = strip_tags($instance['title']);

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['posts_per_page'] = (int) $instance['posts_per_page'];
        $form['orderby'] = $instance['orderby'];
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_terms('service_category');
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, (isset($form['categories']) ? $form['categories'] : array()))) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                <?php
                $relation = array(
                    'AND' => __('And', kopa_get_domain()),
                    'OR' => __('Or', kopa_get_domain())
                );
                foreach ($relation as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $tags = get_terms('service_tag');
                foreach ($tags as $tag) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, (isset($form['tags']) ? $form['tags'] : array()))) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('posts_per_page'); ?>"><?php _e('Number of items:', kopa_get_domain()); ?></label>                
            <input class="widefat" id="<?php echo $this->get_field_id('posts_per_page'); ?>" name="<?php echo $this->get_field_name('posts_per_page'); ?>" value="<?php echo $form['posts_per_page']; ?>" type="number" min="1">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'lastest' => __('Lastest', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain()),
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>

        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['posts_per_page'] = (int) $new_instance['posts_per_page'];
        $instance['orderby'] = $new_instance['orderby'];

        return $instance;
    }
}

/**
 * Dislay a services widget with excerpt description
 * @since Nictitate 1.0
 */
class Kopa_Widget_Services extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kopa-service-widget', 'description' => __('Display a services widget', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_services', __('Kopa Services', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

        $query_args['post_type'] = 'services';
        $query_args['cat_name'] = 'service_category';
        $query_args['tag_name'] = 'service_tag';
        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = esc_attr($instance['relation']);
        $query_args['tags'] = $instance['tags'];
        $query_args['posts_per_page'] = (int) $instance['posts_per_page'];
        $query_args['orderby'] = $instance['orderby'];

        $services = kopa_widget_posttype_build_query($query_args);


        echo $before_widget;

        if ( ! empty ( $title ) )
            echo $before_title . '<span data-icon="&#xf013;"></span>' . $title . $after_title;
        
        if ( $services->post_count == 0 ) {
            echo $after_widget;
            return;
        }
            
        $kopa_icon = unserialize( KOPA_ICON ); ?>

        <ul class="clearfix">

        <?php 
        $service_index = 1;
        while ( $services->have_posts() ) : $services->the_post();

            // initialize & reset for each loop
            $icon_class = '';
            $icon_class = get_post_meta(get_the_ID(), 'icon_class', true); 

            // get service url
            $service_external_page = get_post_meta(get_the_ID(), 'service_external_page', true);
            $service_static_page = get_post_meta(get_the_ID(), 'service_static_page', true);

            if (!empty($service_external_page)) {
                $service_url = esc_url($service_external_page);
            } elseif (!empty($service_static_page)) {
                $service_url = get_page_link($service_static_page);
            } else {
                $service_url = get_permalink();
            }            
            ?>

            <li>
                <h6 class="service-title"><span data-icon="<?php echo $kopa_icon[$icon_class]; ?>"></span><a href="<?php echo $service_url; ?>"><?php the_title(); ?></a></h6>
                <?php the_excerpt(); ?>
            </li>

        <?php 
        if ( $service_index % 4 == 0 && $service_index != $services->post_count )
            echo '</ul><ul class="clearfix">';
        $service_index++;

        endwhile; ?>

        </ul> <!-- .clearfix -->

        <?php wp_reset_postdata();
        echo $after_widget;
    }

    function form($instance) {
        $default = array(
            'title' => __( 'Services', kopa_get_domain() ),
            'categories' => array(),
            'relation' => 'OR',
            'tags' => array(),
            'posts_per_page' => 8,
            'orderby' => 'lastest',
        );
        $instance = wp_parse_args((array) $instance, $default);
        $title = strip_tags($instance['title']);

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['posts_per_page'] = (int) $instance['posts_per_page'];
        $form['orderby'] = $instance['orderby'];
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_terms('service_category');
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, (isset($form['categories']) ? $form['categories'] : array()))) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                <?php
                $relation = array(
                    'AND' => __('And', kopa_get_domain()),
                    'OR' => __('Or', kopa_get_domain())
                );
                foreach ($relation as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $tags = get_terms('service_tag');
                foreach ($tags as $tag) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, (isset($form['tags']) ? $form['tags'] : array()))) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('posts_per_page'); ?>"><?php _e('Number of items:', kopa_get_domain()); ?></label>                
            <input class="widefat" id="<?php echo $this->get_field_id('posts_per_page'); ?>" name="<?php echo $this->get_field_name('posts_per_page'); ?>" value="<?php echo $form['posts_per_page']; ?>" type="number" min="1">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'lastest' => __('Lastest', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain()),
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>

        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['posts_per_page'] = (int) $new_instance['posts_per_page'];
        $instance['orderby'] = $new_instance['orderby'];

        return $instance;
    }
}

/**
 * @since Nictitate 1.0
 */
class Kopa_Widget_Tagline extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kopa-tagline-widget clearfix', 'description' => __('Display a tagline widget', kopa_get_domain()));
        $control_ops = array('width' => '400', 'height' => 'auto');
        parent::__construct('kopa_widget_tagline', __('Kopa Tagline', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $description = $instance['description'];
        $button_text = $instance['button_text'];
        $button_link = $instance['button_link'];

        echo $before_widget;
        ?>

        <div class="kopa-tagline-description">
            <h4><?php echo $title; ?></h4>
            <p><?php echo $description; ?></p>
        </div>
        <a href="<?php echo $button_link; ?>" class="kopa-button blue-button"><?php echo $button_text; ?></a>

        <?php 
        echo $after_widget;
    }

    function form($instance) {
        $default = array(
            'title'       => '',
            'description' => '',
            'button_text' => '',
            'button_link' => ''
        );
        $instance = wp_parse_args((array) $instance, $default);
        $title = strip_tags($instance['title']);
        $form['description'] = $instance['description'];
        $form['button_text'] = $instance['button_text'];
        $form['button_link'] = $instance['button_link'];
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Description:', kopa_get_domain()); ?></label>
            <textarea class="widefat" name="<?php echo $this->get_field_name( 'description' ); ?>" id="<?php echo $this->get_field_id( 'description' ); ?>" rows="5"><?php echo esc_textarea( $form['description'] ); ?></textarea>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('button_text'); ?>"><?php _e('Button Text:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('button_text'); ?>" name="<?php echo $this->get_field_name('button_text'); ?>" type="text" value="<?php echo esc_attr($form['button_text']); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('button_link'); ?>"><?php _e('Button Link:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('button_link'); ?>" name="<?php echo $this->get_field_name('button_link'); ?>" type="text" value="<?php echo esc_attr($form['button_link']); ?>" />
        </p>

        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['description'] = strip_tags( $new_instance['description'] );
        $instance['button_text'] = strip_tags( $new_instance['button_text'] );
        $instance['button_link'] = strip_tags( $new_instance['button_link'] );

        return $instance;
    }
}

/**
 * @since Nictitate 1.0
 */
class Kopa_Widget_Posts_List extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kopa-latest-post-widget', 'description' => __('Display a posts list widget', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_posts_list', __('Kopa Posts List', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

        $query_args['post_type'] = 'post';
        $query_args['cat_name'] = 'category';
        $query_args['tag_name'] = 'post_tag';
        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = esc_attr($instance['relation']);
        $query_args['tags'] = $instance['tags'];
        $query_args['posts_per_page'] = (int) $instance['posts_per_page'];
        $query_args['orderby'] = $instance['orderby'];

        $posts = kopa_widget_posttype_build_query($query_args);

        if ( $posts->post_count == 0 )
            return;

        echo $before_widget;

        if ( ! empty ( $title ) )
            echo $before_title . '<span data-icon="&#xf040;"></span>' . $title . $after_title; ?>
        
        <ul class="clearfix">

        <?php 
        $post_index = 1;
        
        while ( $posts->have_posts() ) : $posts->the_post();
            $thumbnail_id = get_post_thumbnail_id();
            $large_thumbnail = wp_get_attachment_image_src( $thumbnail_id, 'large' );
            $thumbnail = wp_get_attachment_image_src( $thumbnail_id, 'kopa-image-size-1' );
        ?>
            <li>
                <article class="entry-item clearfix">
                    <?php if ( get_post_format() == 'gallery' ) : 
                        $gallery = kopa_content_get_gallery( get_the_content() );
                        $slug = $this->get_field_id('gallery').'-'.get_the_ID();

                        if ( ! empty ($gallery) ) {
                            $gallery = $gallery[0];
                            $shortcode = $gallery['shortcode'];

                            // get gallery string ids
                            preg_match_all('/ids=\"(?:\d+,*)+\"/', $shortcode, $gallery_string_ids);
                            $gallery_string_ids = $gallery_string_ids[0][0];

                            // get array of image id
                            preg_match_all('/\d+/', $gallery_string_ids, $gallery_ids);
                            $gallery_ids = $gallery_ids[0];

                            $first_image_id = array_shift($gallery_ids);
                            $first_image_src = wp_get_attachment_image_src( $first_image_id, 'kopa-image-size-1' );
                            $first_full_image_src = wp_get_attachment_image_src( $first_image_id, 'full' );

                        }
                    ?>
                        <div class="entry-thumb hover-effect">
                            <div class="mask">
                                <a class="link-detail" href="<?php the_permalink(); ?>" data-icon="&#xf0c1;"></a>
                                
                                <?php if ( ! isset($first_image_src[0]) && has_post_thumbnail() ) : ?>
                                    <a class="link-gallery" href="<?php echo $large_thumbnail[0]; ?>" data-icon="&#xf002;" rel="prettyPhoto"></a>
                                <?php elseif ( isset($first_image_src[0]) ) : ?>
                                    <a class="link-gallery" href="<?php echo $first_full_image_src[0]; ?>" data-icon="&#xf03e;" rel="prettyPhoto[<?php echo $slug; ?>]"></a>
                                <?php endif; ?>

                                <?php if (isset($gallery_ids) && ! empty($gallery_ids)) {
                                    foreach( $gallery_ids as $gallery_id ) {
                                        $gallery_image_src = wp_get_attachment_image_src($gallery_id, 'full');
                                        echo '<a style="display: none;" href="'.$gallery_image_src[0].'" rel="prettyPhoto['.$slug.']"></a>';
                                    }
                                }; ?>
                            </div>
                            <?php if ( has_post_thumbnail() ) : ?>
                                <img src="<?php echo $thumbnail[0]; ?>" alt="<?php the_title(); ?>">
                            <?php elseif ( isset( $first_image_src[0] ) ) : ?>
                                <img src="<?php echo $first_image_src[0]; ?>" alt="<?php the_title(); ?>">
                            <?php endif; ?>
                        </div>

                    <?php elseif ( get_post_format() == 'video' ) : 
                        $video = kopa_content_get_video( get_the_content() );

                        if ( ! empty( $video ) ) {
                            $video = $video[0];

                            if ( isset($video['type']) && isset($video['url']) ) {
                                $video_thumbnail = kopa_get_video_thumbnails_url( $video['type'], $video['url'] );
                            }
                        }

                        $enableLightbox = get_option('kopa_theme_options_play_video_in_lightbox', 'enable');
                    ?>
                        <div class="entry-thumb hover-effect">
                            <div class="mask">
                                <a class="link-detail" href="<?php the_permalink(); ?>" data-icon="&#xf0c1;"></a>
                                <?php if ( isset( $video['url'] ) ) : ?>
                                    <a class="link-gallery" href="<?php echo $video['url']; ?>" data-icon="&#xf04b;" rel="<?php echo $enableLightbox == 'enable' ? 'prettyPhoto' : ''; ?>"></a>
                                <?php elseif ( has_post_thumbnail() ) : ?>
                                    <a class="link-gallery" href="<?php echo $large_thumbnail[0]; ?>" data-icon="&#xf002;" rel="prettyPhoto"></a>
                                <?php endif; ?>
                            </div>
                            <?php if ( has_post_thumbnail() ) : ?>
                                <img src="<?php echo $thumbnail[0]; ?>" alt="<?php the_title(); ?>">
                            <?php elseif ( isset($video_thumbnail) ) : ?>
                                <img width="251" src="<?php echo $video_thumbnail; ?>" alt="<?php the_title(); ?>">
                            <?php endif; ?>
                        </div>

                    <?php else : ?>
                        <div class="entry-thumb hover-effect">
                            <div class="mask">
                                <a class="link-detail" href="<?php the_permalink(); ?>" data-icon="&#xf0c1;"></a>
                                <a class="link-gallery" href="<?php echo $large_thumbnail[0]; ?>" data-icon="&#xf002;" rel="prettyPhoto"></a>
                            </div>
                            <?php if ( has_post_thumbnail() ) : ?>
                                <img src="<?php echo $thumbnail[0]; ?>" alt="<?php the_title(); ?>">
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="entry-content">
                        <h6 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><span></span></h6>
                        <span class="entry-date clearfix"><span class="fa fa-clock-o"></span><?php the_time( get_option( 'date_format') ); ?></span>
                        <?php the_excerpt(); ?>
                    </div>
                </article>
            </li>
        <?php 
        if ( $post_index % 2 == 0  && $post_index != $posts->post_count )
            echo '</ul><ul class="mt-20 clearfix">';
        $post_index++;

        endwhile; 

        $blogID = get_option( 'page_for_posts' );
        $blogLink = get_page_link( $blogID );

        if ( ! empty( $blogLink ) ) :
        ?>
            <a href="<?php echo $blogLink; ?>" class="view-all"><?php _e( 'View All', kopa_get_domain() ); ?> &raquo;</a>
        <?php 
        endif; 
        ?>

        </ul>
        <?php wp_reset_postdata();
        echo $after_widget;
    }

    function form($instance) {
        $default = array(
            'title' => __( 'Lastest Posts', kopa_get_domain() ),
            'categories' => array(),
            'relation' => 'OR',
            'tags' => array(),
            'posts_per_page' => 2,
            'orderby' => 'lastest',
        );
        $instance = wp_parse_args((array) $instance, $default);
        $title = strip_tags($instance['title']);

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['posts_per_page'] = (int) $instance['posts_per_page'];
        $form['orderby'] = $instance['orderby'];
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'categories' ); ?>"><?php _e( 'Categories', kopa_get_domain() ); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'categories' ); ?>" name="<?php echo $this->get_field_name( 'categories' ) ?>[]" multiple="multiple" size="5">
                <option value=""><?php _e('--Select--', kopa_get_domain()); ?></option>
                <?php 
                $categories = get_categories();
                foreach ($categories as $category) :
                ?>
                <option value="<?php echo $category->term_id; ?>" <?php echo in_array($category->term_id, $form['categories']) ? 'selected="selected"' : ''; ?>>
                    <?php echo $category->name.' ('.$category->count.')'; ?></option>
                <?php 
                endforeach; 
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation', kopa_get_domain()); ?>:</label>
            <select class="widefat" name="<?php echo $this->get_field_name('relation'); ?>" id="<?php echo $this->get_field_id('relation'); ?>">
                <option value="OR" <?php selected('OR', $form['relation']); ?>><?php _e('OR', kopa_get_domain()); ?></option>
                <option value="AND" <?php selected('AND', $form['relation']); ?>><?php _e('AND', kopa_get_domain()); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tags' ); ?>"><?php _e( 'Tags', kopa_get_domain() ); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'tags' ); ?>" name="<?php echo $this->get_field_name( 'tags' ) ?>[]" multiple="multiple" size="5">
                <option value=""><?php _e('--Select--', kopa_get_domain()); ?></option>
                <?php 
                $tags = get_tags();
                foreach ($tags as $category) :
                ?>
                <option value="<?php echo $category->term_id; ?>" <?php echo in_array($category->term_id, $form['tags']) ? 'selected="selected"' : ''; ?>>
                    <?php echo $category->name.' ('.$category->count.')'; ?></option>
                <?php 
                endforeach; 
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _e( 'Orderby', kopa_get_domain() ); ?></label>
            <select name="<?php echo $this->get_field_name( 'orderby' ); ?>" id="<?php echo $this->get_field_id('orderby' ); ?>">
                <?php $orderby = array(
                    'lastest'      => __('Lastest', kopa_get_domain()),
                    'popular'      => __('Popular by view count', kopa_get_domain()),
                    'most_comment' => __('Popular by comment count', kopa_get_domain()),
                    'random'       => __('Random', kopa_get_domain())
                );
                
                foreach ($orderby as $value => $label) :
                ?>
                    <option value="<?php echo $value; ?>" <?php selected($value, $form['orderby']); ?>><?php echo $label; ?></option>              
                <?php
                endforeach;
                ?>    
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('posts_per_page'); ?>"><?php _e('Number of items:', kopa_get_domain()); ?></label>                
            <input class="widefat" id="<?php echo $this->get_field_id('posts_per_page'); ?>" name="<?php echo $this->get_field_name('posts_per_page'); ?>" value="<?php echo $form['posts_per_page']; ?>" type="number" min="1">
        </p>

        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['posts_per_page'] = (int) $new_instance['posts_per_page'];
        $instance['orderby'] = $new_instance['orderby'];

        return $instance;
    }
}

/**
 * @since Nictitate 1.0
 */
class Kopa_Widget_Posts_Carousel extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kopa-latest-work-widget', 'description' => __('Display a posts carousel widget', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_posts_carousel', __('Kopa Posts Carousel', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $max_items = $instance['max_items'];
        $scroll_items = $instance['scroll_items'];

        $query_args['post_type'] = 'post';
        $query_args['cat_name'] = 'category';
        $query_args['tag_name'] = 'post_tag';
        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = esc_attr($instance['relation']);
        $query_args['tags'] = $instance['tags'];
        $query_args['posts_per_page'] = (int) $instance['posts_per_page'];
        $query_args['orderby'] = $instance['orderby'];
        
        $posts = kopa_widget_posttype_build_query($query_args);

        if ( $posts->post_count == 0 )
            return;

        echo $before_widget;

        if ( ! empty ( $title ) )
            echo $before_title . '<span data-icon="&#xf040;"></span>' . $title . $after_title; ?>
        
        <div class="list-carousel responsive" >
            <ul class="kopa-latest-work-carousel" data-max-items="<?php echo $max_items; ?>" data-scroll-items="<?php echo $scroll_items; ?>" data-prev-id="#<?php echo $this->get_field_id('prev-1'); ?>" data-next-id="#<?php echo $this->get_field_id('next-1'); ?>">

        <?php while ( $posts->have_posts() ) : $posts->the_post();
            $thumbnail_id = get_post_thumbnail_id();
            $large_thumbnail = wp_get_attachment_image_src( $thumbnail_id, 'large' );
            $thumbnail = wp_get_attachment_image_src( $thumbnail_id, 'kopa-image-size-3' );
        ?>
            <li style="width: 252px;">
                <article class="entry-item clearfix">
                    <?php if ( get_post_format() == 'gallery' ) : 
                        $gallery = kopa_content_get_gallery( get_the_content() );
                        $slug = $this->get_field_id('gallery').'-'.get_the_ID();

                        if ( ! empty ($gallery) ) {
                            $gallery = $gallery[0];
                            $shortcode = $gallery['shortcode'];

                            // get gallery string ids
                            preg_match_all('/ids=\"(?:\d+,*)+\"/', $shortcode, $gallery_string_ids);
                            $gallery_string_ids = $gallery_string_ids[0][0];

                            // get array of image id
                            preg_match_all('/\d+/', $gallery_string_ids, $gallery_ids);
                            $gallery_ids = $gallery_ids[0];

                            $first_image_id = array_shift($gallery_ids);
                            $first_image_src = wp_get_attachment_image_src( $first_image_id, 'kopa-image-size-1' );
                            $first_full_image_src = wp_get_attachment_image_src( $first_image_id, 'full' );

                        }
                    ?>
                        <div class="entry-thumb hover-effect">
                            <div class="mask">
                                <a class="link-detail" href="<?php the_permalink(); ?>" data-icon="&#xf0c1;"></a>
                                
                                <?php if ( ! isset($first_image_src[0]) && has_post_thumbnail() ) : ?>
                                    <a class="link-gallery" href="<?php echo $large_thumbnail[0]; ?>" data-icon="&#xf002;" rel="prettyPhoto"></a>
                                <?php elseif ( isset($first_image_src[0]) ) : ?>
                                    <a class="link-gallery" href="<?php echo $first_full_image_src[0]; ?>" data-icon="&#xf03e;" rel="prettyPhoto[<?php echo $slug; ?>]"></a>
                                <?php endif; ?>

                                <?php if (isset($gallery_ids) && ! empty($gallery_ids)) {
                                    foreach( $gallery_ids as $gallery_id ) {
                                        $gallery_image_src = wp_get_attachment_image_src($gallery_id, 'full');
                                        echo '<a style="display: none;" href="'.$gallery_image_src[0].'" rel="prettyPhoto['.$slug.']"></a>';
                                    }
                                }; ?>
                            </div>
                            <?php if ( has_post_thumbnail() ) : ?>
                                <img src="<?php echo $thumbnail[0]; ?>" alt="<?php the_title(); ?>">
                            <?php elseif ( isset( $first_image_src[0] ) ) : ?>
                                <img src="<?php echo $first_image_src[0]; ?>" alt="<?php the_title(); ?>">
                            <?php endif; ?>
                        </div>

                    <?php elseif ( get_post_format() == 'video' ) : 
                        $video = kopa_content_get_video( get_the_content() );

                        if ( ! empty( $video ) ) {
                            $video = $video[0];

                            if ( isset($video['type']) && isset($video['url']) ) {
                                $video_thumbnail = kopa_get_video_thumbnails_url( $video['type'], $video['url'] );
                            }
                        }

                        $enableLightbox = get_option('kopa_theme_options_play_video_in_lightbox', 'enable');
                    ?>
                        <div class="entry-thumb hover-effect">
                            <div class="mask">
                                <a class="link-detail" href="<?php the_permalink(); ?>" data-icon="&#xf0c1;"></a>
                                <?php if ( isset( $video['url'] ) ) : ?>
                                    <a class="link-gallery" href="<?php echo $video['url']; ?>" data-icon="&#xf04b;" rel="<?php echo $enableLightbox == 'enable' ? 'prettyPhoto' : ''; ?>"></a>
                                <?php elseif ( has_post_thumbnail() ) : ?>
                                    <a class="link-gallery" href="<?php echo $large_thumbnail[0]; ?>" data-icon="&#xf002;" rel="<?php echo $enableLightbox == 'enable' ? 'prettyPhoto' : ''; ?>"></a>
                                <?php endif; ?>
                            </div>
                            <?php if ( has_post_thumbnail() ) : ?>
                                <img src="<?php echo $thumbnail[0]; ?>" alt="<?php the_title(); ?>">
                            <?php elseif ( isset($video_thumbnail) ) : ?>
                                <img width="252" src="<?php echo $video_thumbnail; ?>" alt="<?php the_title(); ?>">
                            <?php endif; ?>
                        </div>

                    <?php else : ?>
                        <div class="entry-thumb hover-effect">
                            <div class="mask">
                                <a class="link-detail" href="<?php the_permalink(); ?>" data-icon="&#xf0c1;"></a>
                                <a class="link-gallery" href="<?php echo $large_thumbnail[0]; ?>" data-icon="&#xf002;" rel="prettyPhoto"></a>
                            </div>
                            <?php if ( has_post_thumbnail() ) : ?>
                                <img src="<?php echo $thumbnail[0]; ?>" alt="<?php the_title(); ?>">
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="entry-content">
                        <h6 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
                        <span class="entry-categories"><?php the_category(',&nbsp;'); ?></span>
                    </div><!--entry-content-->
                </article><!--entry-item-->
            </li>
        <?php endwhile; ?>

            </ul>
            <div class="clearfix"></div>
            <div class="carousel-nav clearfix">
                <a id="<?php echo $this->get_field_id('prev-1'); ?>" class="carousel-prev" href="#">&lt;</a>
                <a id="<?php echo $this->get_field_id('next-1'); ?>" class="carousel-next" href="#">&gt;</a>
            </div>
        </div>
        <?php wp_reset_postdata();
        echo $after_widget;
    }

    function form($instance) {
        $default = array(
            'title' => __( 'Lastest Posts', kopa_get_domain() ),
            'categories' => array(),
            'relation' => 'OR',
            'tags' => array(),
            'posts_per_page' => 8,
            'orderby' => 'lastest',
            'max_items' => 4,
            'scroll_items' => 1
        );
        $instance = wp_parse_args((array) $instance, $default);
        $title = strip_tags($instance['title']);

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['posts_per_page'] = (int) $instance['posts_per_page'];
        $form['orderby'] = $instance['orderby'];
        $form['max_items'] = (int) $instance['max_items'];
        $form['scroll_items'] = (int) $instance['scroll_items'];
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'categories' ); ?>"><?php _e( 'Categories', kopa_get_domain() ); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'categories' ); ?>" name="<?php echo $this->get_field_name( 'categories' ) ?>[]" multiple="multiple" size="5">
                <option value=""><?php _e('--Select--', kopa_get_domain()); ?></option>
                <?php 
                $categories = get_categories();
                foreach ($categories as $category) :
                ?>
                <option value="<?php echo $category->term_id; ?>" <?php echo in_array($category->term_id, $form['categories']) ? 'selected="selected"' : ''; ?>>
                    <?php echo $category->name.' ('.$category->count.')'; ?></option>
                <?php 
                endforeach; 
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation', kopa_get_domain()); ?>:</label>
            <select class="widefat" name="<?php echo $this->get_field_name('relation'); ?>" id="<?php echo $this->get_field_id('relation'); ?>">
                <option value="OR" <?php selected('OR', $form['relation']); ?>><?php _e('OR', kopa_get_domain()); ?></option>
                <option value="AND" <?php selected('AND', $form['relation']); ?>><?php _e('AND', kopa_get_domain()); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tags' ); ?>"><?php _e( 'Tags', kopa_get_domain() ); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'tags' ); ?>" name="<?php echo $this->get_field_name( 'tags' ) ?>[]" multiple="multiple" size="5">
                <option value=""><?php _e('--Select--', kopa_get_domain()); ?></option>
                <?php 
                $tags = get_tags();
                foreach ($tags as $category) :
                ?>
                <option value="<?php echo $category->term_id; ?>" <?php echo in_array($category->term_id, $form['tags']) ? 'selected="selected"' : ''; ?>>
                    <?php echo $category->name.' ('.$category->count.')'; ?></option>
                <?php 
                endforeach; 
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _e( 'Orderby', kopa_get_domain() ); ?></label>
            <select name="<?php echo $this->get_field_name( 'orderby' ); ?>" id="<?php echo $this->get_field_id('orderby' ); ?>">
                <?php $orderby = array(
                    'lastest'      => __('Lastest', kopa_get_domain()),
                    'popular'      => __('Popular by view count', kopa_get_domain()),
                    'most_comment' => __('Popular by comment count', kopa_get_domain()),
                    'random'       => __('Random', kopa_get_domain())
                );
                
                foreach ($orderby as $value => $label) :
                ?>
                    <option value="<?php echo $value; ?>" <?php selected($value, $form['orderby']); ?>><?php echo $label; ?></option>              
                <?php
                endforeach;
                ?>    
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('posts_per_page'); ?>"><?php _e('Number of items:', kopa_get_domain()); ?></label>                
            <input class="widefat" id="<?php echo $this->get_field_id('posts_per_page'); ?>" name="<?php echo $this->get_field_name('posts_per_page'); ?>" value="<?php echo $form['posts_per_page']; ?>" type="number" min="1">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('max_items'); ?>"><?php _e('Max items carousel range:', kopa_get_domain()); ?></label>                
            <select name="<?php echo $this->get_field_name( 'max_items' ); ?>" id="<?php $this->get_field_id( 'max_items' ); ?>">
                <?php $max_items = array( 4, 5 );
                foreach ($max_items as $value) :
                ?>
                    <option value="<?php echo $value; ?>" <?php selected( $value, $form['max_items'] ); ?>><?php echo $value ?></option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('scroll_items'); ?>"><?php _e('Scroll Items:', kopa_get_domain()); ?></label>                
            <input class="widefat" id="<?php echo $this->get_field_id('scroll_items'); ?>" name="<?php echo $this->get_field_name('scroll_items'); ?>" value="<?php echo $form['scroll_items']; ?>" type="number" min="1">
        </p>

        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['posts_per_page'] = (int) $new_instance['posts_per_page'];
        $instance['orderby'] = $new_instance['orderby'];
        $instance['max_items'] = (int) $new_instance['max_items'];
        $instance['scroll_items'] = (int) $new_instance['scroll_items'] ? (int) $new_instance['scroll_items'] : 1;

        return $instance;
    }
}

/**
 * @since Nictitate 1.0
 */
class Kopa_Widget_Testimonials extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kopa-testimonial-widget', 'description' => __('Display a testimonials widget', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_testimonials', __('Kopa Testimonials', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

        $query_args['post_type'] = 'testimonials';
        $query_args['cat_name'] = 'testimonial_category';
        $query_args['tag_name'] = 'testimonial_tag';
        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = esc_attr($instance['relation']);
        $query_args['tags'] = $instance['tags'];
        $query_args['posts_per_page'] = (int) $instance['posts_per_page'];
        $query_args['orderby'] = $instance['orderby'];
        $style = $instance['style']; // fix later

        $testimonials = kopa_widget_posttype_build_query($query_args);

        if ( $testimonials->post_count == 0 )
            return;

        echo $before_widget;

        if ( ! empty ( $title ) )
            echo $before_title . '<span data-icon="&#xf0c0;"></span>' . $title . $after_title;
            
        if ( $style == 'two_columns' ) :
        ?>
        
            <div class="list-carousel responsive" >
                <ul class="kopa-testimonial-carousel" data-prev-id="#<?php echo $this->get_field_id('prev-2'); ?>" data-next-id="#<?php echo $this->get_field_id('next-2'); ?>">

            <?php while ($testimonials->have_posts()):
                $testimonials->the_post();
                $thumbnail_id = get_post_thumbnail_id();
                $thumbnail = wp_get_attachment_image_src( $thumbnail_id, 'kopa-image-size-2' );
            ?>
                <li style="width: 530px;">
                    <article class="testimonial-detail clearfix">
                        <div class="avatar">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <img src="<?php echo $thumbnail[0]; ?>" alt="<?php the_title(); ?> <?php _e( 'avatar', kopa_get_domain() ); ?>">
                            <?php endif; ?>  
                        </div>
                        <div class="testimonial-content">
                            <div class="testimonial-content-inside">
                                <?php the_content(); ?>
                            </div>
                        </div><!--testimonial-content-->
                    </article><!--testimonial-detail-->
                </li>
            <?php 
            endwhile; ?>

                </ul><!--kopa-latest-work-carousel-->
                <div class="clearfix"></div>
                <div class="carousel-nav clearfix">
                    <a id="<?php echo $this->get_field_id('prev-2'); ?>" class="carousel-prev" href="#">&lt;</a>
                    <a id="<?php echo $this->get_field_id('next-2'); ?>" class="carousel-next" href="#">&gt;</a>
                </div>
            </div><!--list-carousel-->

        <?php else : ?>

            <div class="flexslider kopa-testimonial-slider">
                <ul class="slides">
                    <?php while ($testimonials->have_posts()):
                        $testimonials->the_post();
                        $thumbnail_id = get_post_thumbnail_id();
                        $thumbnail = wp_get_attachment_image_src( $thumbnail_id, 'kopa-image-size-2' );
                    ?>
                        <li class="clearfix">
                            <div class="avatar">
                                <?php if ( has_post_thumbnail() ) : ?>
                                <img src="<?php echo $thumbnail[0]; ?>" alt="<?php the_title(); ?>">
                                <?php endif; ?>
                            </div>
                            <div class="testimonial-content">
                                <div class="testimonial-content-inside">
                                    <?php the_content(); ?>
                                </div>
                                <span><?php the_title(); ?></span>
                            </div>
                        </li>
                    <?php endwhile; ?>
                </ul><!--slides-->
            </div><!--kopa-testimonial-slider-->

        <?php endif; // endif $style == 'two_columns' ?>

        <?php wp_reset_postdata();
        echo $after_widget;
    }

    function form($instance) {
        $default = array(
            'title' => '',
            'categories' => array(),
            'relation' => 'OR',
            'tags' => array(),
            'posts_per_page' => 3,
            'orderby' => 'lastest',
            'style'   => 'two_columns'
        );
        $instance = wp_parse_args((array) $instance, $default);
        $title = strip_tags($instance['title']);

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['posts_per_page'] = (int) $instance['posts_per_page'];
        $form['orderby'] = $instance['orderby'];
        $form['style'] = $instance['style'];
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_terms('testimonial_category');
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, (isset($form['categories']) ? $form['categories'] : array()))) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                <?php
                $relation = array(
                    'AND' => __('And', kopa_get_domain()),
                    'OR' => __('Or', kopa_get_domain())
                );
                foreach ($relation as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $tags = get_terms('testimonial_tag');
                foreach ($tags as $tag) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, (isset($form['tags']) ? $form['tags'] : array()))) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('posts_per_page'); ?>"><?php _e('Number of items:', kopa_get_domain()); ?></label>                
            <input class="widefat" id="<?php echo $this->get_field_id('posts_per_page'); ?>" name="<?php echo $this->get_field_name('posts_per_page'); ?>" value="<?php echo $form['posts_per_page']; ?>" type="number" min="1">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'lastest' => __('Lastest', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain()),
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('style'); ?>"><?php _e('Style:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>" autocomplete="off">
                <?php
                $style = array(
                    'one_column' => __('Slider', kopa_get_domain()),
                    'two_columns' => __('Carousel', kopa_get_domain())
                );
                foreach ($style as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['style']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>

        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['posts_per_page'] = (int) $new_instance['posts_per_page'];
        $instance['orderby'] = $new_instance['orderby'];
        $instance['style'] = $new_instance['style'];

        return $instance;
    }
}

/**
 * @since Nictitate 1.0
 */
class Kopa_Widget_Socials extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'kopa-social-widget', 'description' => __('Socials Widget', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_socials', __('Kopa Socials', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $facebook = get_option( 'kopa_theme_options_social_links_facebook_url' );
        $twitter = get_option( 'kopa_theme_options_social_links_twitter_url' );
        $rss = get_option( 'kopa_theme_options_social_links_rss_url' );
        $flickr = get_option( 'kopa_theme_options_social_links_flickr_url' );
        $pinterest = get_option( 'kopa_theme_options_social_links_pinterest_url' );
        $dribbble = get_option( 'kopa_theme_options_social_links_dribbble_url' );
        $vimeo = get_option( 'kopa_theme_options_social_links_vimeo_url' );
        $youtube = get_option( 'kopa_theme_options_social_links_youtube_url' );
        $instagram = get_option( 'kopa_theme_options_social_links_instagram_url' );

        echo $before_widget;

        if ( ! empty( $title ) )
            echo $before_title . $title . $after_title;

        if ( empty( $facebook ) &&
             empty( $twitter ) && 
             $rss == 'HIDE' &&
             empty( $flickr ) &&
             empty( $pinterest ) &&
             empty( $dribbble ) &&
             empty( $vimeo ) &&
             empty( $youtube ) && 
             empty( $instagram ) ) {
            
            echo $after_widget;
            return;
        }
        ?>

        <ul class="clearfix">
            <?php if ( ! empty( $twitter ) ) : ?>
                <li><a href="<?php echo esc_url( $twitter ); ?>" data-icon="&#xf099;"></a></li>
            <?php endif; ?>

            <?php if ( ! empty( $facebook ) ) : ?>
                <li><a href="<?php echo esc_url( $facebook ); ?>" data-icon="&#xf09a;"></a></li>
            <?php endif; ?>

            <?php if ( $rss != 'HIDE' && $rss == '' ) : ?>
                <li><a href="<?php bloginfo( 'rss2_url' ); ?>" data-icon="&#xf09e;"></a></li>
            <?php elseif ( $rss != 'HIDE' ) : ?>
                <li><a href="<?php echo esc_url( $rss ); ?>" data-icon="&#xf09e;"></a></li>
            <?php endif; ?>

            <?php if ( ! empty( $flickr ) ) : ?>
                <li><a href="<?php echo esc_url( $flickr ); ?>" data-icon="&#xf16e;"></a></li>
            <?php endif; ?>

            <?php if ( ! empty( $pinterest ) ) : ?>
                <li><a href="<?php echo esc_url( $pinterest ); ?>" data-icon="&#xf0d2;"></a></li>
            <?php endif; ?>
            
            <?php if ( ! empty( $dribbble ) ) : ?>
                <li><a href="<?php echo esc_url( $dribbble ); ?>" data-icon="&#xf17d;"></a></li>
            <?php endif; ?>
            
            <?php if ( ! empty( $vimeo ) ) : ?>
                <li><a href="<?php echo esc_url( $vimeo ); ?>" data-icon="&#xf194;"></a></li>
            <?php endif; ?>

            <?php if ( ! empty( $youtube ) ) : ?>
                <li><a href="<?php echo esc_url( $youtube ); ?>" data-icon="&#xf167;"></a></li>
            <?php endif; ?>

            <?php if ( ! empty( $instagram ) ) : ?>
                <li><a href="<?php echo esc_url( $instagram ); ?>" data-icon="&#xf16d;"></a></li>
            <?php endif; ?>
        </ul>

        <?php 
        echo $after_widget;
    }

    function form($instance) {
        $default = array(
            'title'       => ''
        );
        $instance = wp_parse_args((array) $instance, $default);
        $title = strip_tags($instance['title']);
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );

        return $instance;
    }

}

/**
 * @since Nictitate 1.0
 */
class Kopa_Widget_Subscribe extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kopa-newsletter-widget', 'description' => __('Feedburner Email Subscriptions Widget', kopa_get_domain()));
        $control_ops = array('width' => '400', 'height' => 'auto');
        parent::__construct('kopa_widget_subscribe', __('Kopa Subsribe', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $feed_id = $instance['feed_id'];

        echo $before_widget;

        if ( ! empty( $title ) )
            echo $before_title . $title . $after_title;

        if ( empty( $feed_id ) ) {
            echo $after_widget;
            return;
        }
        ?>

        <form class="newsletter-form clearfix" action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $feed_id; ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
            
            <input type="hidden" value="<?php echo $feed_id; ?>" name="uri"/>
            
            <p class="input-email clearfix">
                <input type="text" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;" name="email" value="<?php _e('Subscribe to newsletter', kopa_get_domain()); ?>" class="email" size="40">
                <input type="submit" value="Subscribe" class="submit">
            </p>

        </form>

        <?php 
        echo $after_widget;
    }

    function form($instance) {
        $default = array(
            'title'   => __( 'Newsletter', kopa_get_domain() ),
            'feed_id' => ''
        );
        $instance = wp_parse_args((array) $instance, $default);
        $title = strip_tags( $instance['title'] );
        $form['feed_id'] = strip_tags( $instance['feed_id'] );
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('feed_id'); ?>"><?php _e('Feedburner id (http://feeds.feedburner.com/<b>wordpress/kopatheme</b>)', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('feed_id'); ?>" name="<?php echo $this->get_field_name('feed_id'); ?>" type="text" value="<?php echo esc_attr($form['feed_id']); ?>" />
            
        </p>
        
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['feed_id'] = strip_tags( $new_instance['feed_id'] );

        return $instance;
    }
}

/**
 * @since Nictitate 1.0
 */
class Kopa_Widget_Flickr extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => ' kopa-widget-flickr', 'description' => __('Flickr Widget', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_flickr', __('Kopa Flickr', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $flickr_id = $instance['flickr_id'];
        $limit = $instance['limit'];

        echo $before_widget;

        if ( ! empty( $title ) )
            echo $before_title . $title . $after_title;

        if ( empty( $flickr_id ) ) {
            echo $after_widget;
            return;
        }
        ?>

        <div class="flickr-wrap clearfix" id="<?php echo $this->get_field_id( 'flickr-feed' ); ?>" data-flickr-id="<?php echo $flickr_id; ?>" data-limit="<?php echo $limit; ?>">                    
            <ul class="kopa-flickr-widget clearfix"></ul>
        </div><!--flickr-wrap-->

        <?php 
        echo $after_widget;
    }

    function form($instance) {
        $default = array(
            'title'     => __( 'Photo Flickr', kopa_get_domain() ),
            'flickr_id' => '',
            'limit'     => 9
        );
        $instance = wp_parse_args((array) $instance, $default);
        $title = strip_tags( $instance['title'] );
        $form['flickr_id'] = strip_tags( $instance['flickr_id'] );
        $form['limit'] = $instance['limit'];
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('flickr_id'); ?>"><?php _e('Flickr ID', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('flickr_id'); ?>" name="<?php echo $this->get_field_name('flickr_id'); ?>" type="text" value="<?php echo esc_attr($form['flickr_id']); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Limit the number of items', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="number" min="1" value="<?php echo esc_attr($form['limit']); ?>" />
        </p>
        
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['flickr_id'] = strip_tags( $new_instance['flickr_id'] );
        $instance['limit'] = (int) $new_instance['limit'] ? (int) $new_instance['limit'] : 9;

        return $instance;
    }

}

/**
 * @since Nictitate 1.0
 */
class Kopa_Widget_Skill extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kopa-skill-widget', 'description' => __('Display a services expertise widget', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_skill', __('Kopa Services Expertise', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

        $query_args['post_type'] = 'services';
        $query_args['cat_name'] = 'service_category';
        $query_args['tag_name'] = 'service_tag';
        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = esc_attr($instance['relation']);
        $query_args['tags'] = $instance['tags'];
        $query_args['posts_per_page'] = (int) $instance['posts_per_page'];
        $query_args['orderby'] = $instance['orderby'];

        $services = kopa_widget_posttype_build_query($query_args);

        if ( $services->post_count == 0 )
            return;

        echo $before_widget;

        if ( ! empty ( $title ) )
            echo $before_title . '<span data-icon="&#xf080;"></span>' . $title . $after_title;
            
        $service_index = 1;
        $kopa_icon = unserialize(KOPA_ICON); ?>

        <div class="kopa-skill-wrapper clearfix">
        
        <?php while ($services->have_posts()):
            $services->the_post();
            // initialize & reset for each loop
            $icon_class = '';

            $icon_class = get_post_meta(get_the_ID(), 'icon_class', true);
            $service_expertise = get_post_meta( get_the_ID(), 'service_percentage', true );
        ?>
            <div class="kopa-skill clearfix"><p class="kopa-skill-title"><?php the_title(); ?></p>
                <div class="progress-bar green animate">
                    <span class="progress-<?php echo $service_expertise; ?>" style="width: <?php echo $service_expertise; ?>%">
                        <span></span>
                    </span>  
                </div>
            </div><!--kopa-skill-->
        <?php 
        endwhile; ?>

        </div> <!-- .kopa-skill-wrapper -->

        <?php wp_reset_postdata();
        echo $after_widget;
    }

    function form($instance) {
        $default = array(
            'title' => '',
            'categories' => array(),
            'relation' => 'OR',
            'tags' => array(),
            'posts_per_page' => 3,
            'orderby' => 'lastest',
        );
        $instance = wp_parse_args((array) $instance, $default);
        $title = strip_tags($instance['title']);

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['posts_per_page'] = (int) $instance['posts_per_page'];
        $form['orderby'] = $instance['orderby'];
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_terms('service_category');
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, (isset($form['categories']) ? $form['categories'] : array()))) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                <?php
                $relation = array(
                    'AND' => __('And', kopa_get_domain()),
                    'OR' => __('Or', kopa_get_domain())
                );
                foreach ($relation as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $tags = get_terms('service_tag');
                foreach ($tags as $tag) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, (isset($form['tags']) ? $form['tags'] : array()))) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('posts_per_page'); ?>"><?php _e('Number of items:', kopa_get_domain()); ?></label>                
            <input class="widefat" id="<?php echo $this->get_field_id('posts_per_page'); ?>" name="<?php echo $this->get_field_name('posts_per_page'); ?>" value="<?php echo $form['posts_per_page']; ?>" type="number" min="1">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'lastest' => __('Lastest', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain()),
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>

        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['posts_per_page'] = (int) $new_instance['posts_per_page'];
        $instance['orderby'] = $new_instance['orderby'];

        return $instance;
    }
}

/**
 * Dislay a clients widget with url
 * @since Nictitate 1.0
 */
class Kopa_Widget_Clients extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kopa-client-widget', 'description' => __('Display a clients widget', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_clients', __('Kopa Clients', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

        $query_args['post_type'] = 'clients';
        $query_args['cat_name'] = 'client_category';
        $query_args['tag_name'] = 'client_tag';
        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = esc_attr($instance['relation']);
        $query_args['tags'] = $instance['tags'];
        $query_args['posts_per_page'] = (int) $instance['posts_per_page'];
        $query_args['orderby'] = $instance['orderby'];

        $clients = kopa_widget_posttype_build_query($query_args);


        echo $before_widget;

        if ( ! empty ( $title ) )
            echo $before_title . '<span data-icon="&#xf0ac;"></span>' . $title . $after_title;
        
        if ( $clients->post_count == 0 ) {
            echo $after_widget;
            return;
        }
            
        $kopa_icon = unserialize( KOPA_ICON ); ?>

        <ul class="clearfix">

        <?php 
        $client_index = 1;
        while ( $clients->have_posts() ) : $clients->the_post(); 
            $client_url = get_post_meta( get_the_ID(), 'client_url', true );
            $thumbnail_id = get_post_thumbnail_id();
            $thumbnail = wp_get_attachment_image_src( $thumbnail_id, 'kopa-image-size-4' );
        ?>

            <li>
                <div class="auto-margin">
                    <a href="<?php echo $client_url; ?>"><img src="<?php echo $thumbnail[0]; ?>" alt=""></a>
                </div>
            </li>

        <?php 
        if ( $client_index % 5 == 0 && $client_index != $clients->post_count )
            echo '</ul><ul class="clearfix" style="margin-top: 40px">';
        $client_index++;

        endwhile; ?>

        </ul> <!-- .clearfix -->

        <?php wp_reset_postdata();
        echo $after_widget;
    }

    function form($instance) {
        $default = array(
            'title' => __( 'Clients', kopa_get_domain() ),
            'categories' => array(),
            'relation' => 'OR',
            'tags' => array(),
            'posts_per_page' => 5,
            'orderby' => 'lastest',
        );
        $instance = wp_parse_args((array) $instance, $default);
        $title = strip_tags($instance['title']);

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['posts_per_page'] = (int) $instance['posts_per_page'];
        $form['orderby'] = $instance['orderby'];
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_terms('client_category');
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, (isset($form['categories']) ? $form['categories'] : array()))) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                <?php
                $relation = array(
                    'AND' => __('And', kopa_get_domain()),
                    'OR' => __('Or', kopa_get_domain())
                );
                foreach ($relation as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $tags = get_terms('client_tag');
                foreach ($tags as $tag) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, (isset($form['tags']) ? $form['tags'] : array()))) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('posts_per_page'); ?>"><?php _e('Number of items:', kopa_get_domain()); ?></label>                
            <input class="widefat" id="<?php echo $this->get_field_id('posts_per_page'); ?>" name="<?php echo $this->get_field_name('posts_per_page'); ?>" value="<?php echo $form['posts_per_page']; ?>" type="number" min="1">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'lastest' => __('Lastest', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain()),
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>

        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['posts_per_page'] = (int) $new_instance['posts_per_page'];
        $instance['orderby'] = $new_instance['orderby'];

        return $instance;
    }
}

/**
 * Display portfolio widget
 * @since Nictitate 1.0
 */
class Kopa_Widget_Portfolios extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'kopa-portfolio-widget', 'description' => __('Display a portfolios widget', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_portfolios', __('Kopa Portfolios', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

        $query_args['post_type'] = 'portfolio';
        $query_args['cat_name'] = 'portfolio_project';
        $query_args['tag_name'] = 'portfolio_tag';
        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = esc_attr($instance['relation']);
        $query_args['tags'] = $instance['tags'];
        $query_args['posts_per_page'] = (int) $instance['posts_per_page'];
        $query_args['orderby'] = $instance['orderby'];

        $portfolios = kopa_widget_posttype_build_query($query_args);

        echo $before_widget;

        if ( $portfolios->post_count == 0 ) {
            echo $after_widget;
            return;
        }

        $title_position = $instance['title_position'];

        ?>
        <div class="wrapper">
            <ul id="container" class="clearfix da-thumbs">

        <?php 
        $portfolio_index = 1;
        while ( $portfolios->have_posts() ) : $portfolios->the_post(); 
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

            if ($portfolio_index == $title_position) {
                echo '<li class="element width2 isotope-item">
                        <h2>'.$title.'</h2>
                    </li>';
            }

            if ( has_post_thumbnail() ) :
        ?>
            <li class="element <?php echo $item_class; ?>">
              <div class="da-thumbs-hover">
                <img src="<?php echo $thumbnail[0]; ?>" alt="<?php the_title(); ?>">
                <p>
                    <a class="link-gallery" href="<?php echo $full_thumbnail[0]; ?>" rel="prettyPhoto[<?php echo $this->get_field_id( 'gallery' ); ?>]"><?php the_title(); ?></a>
                    <a class="link-detail" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </p>
              </div>
            </li>
        <?php 
            endif;

            $portfolio_index++;
        endwhile; 

        // title in the last position of portfolio list items
        if ( $title_position > $portfolios->post_count ) {
            echo '<li class="element width2 isotope-item">
                    <h2>'.$title.'</h2>
                </li>';
        }
        ?>

            </ul> <!-- #container -->
        </div><!--wrapper-->

        <?php wp_reset_postdata();
        echo $after_widget;
    }

    function form($instance) {
        $default = array(
            'title' => __( 'Our Portfolio', kopa_get_domain() ),
            'categories' => array(),
            'relation' => 'OR',
            'tags' => array(),
            'posts_per_page' => 16,
            'orderby' => 'lastest',
            'title_position' => 1
        );
        $instance = wp_parse_args((array) $instance, $default);
        $title = strip_tags($instance['title']);

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['posts_per_page'] = (int) $instance['posts_per_page'];
        $form['orderby'] = $instance['orderby'];
        $form['title_position'] = $instance['title_position'];
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_terms('portfolio_project');
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, (isset($form['categories']) ? $form['categories'] : array()))) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                <?php
                $relation = array(
                    'AND' => __('And', kopa_get_domain()),
                    'OR' => __('Or', kopa_get_domain())
                );
                foreach ($relation as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $tags = get_terms('portfolio_tag');
                foreach ($tags as $tag) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, (isset($form['tags']) ? $form['tags'] : array()))) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('posts_per_page'); ?>"><?php _e('Number of items:', kopa_get_domain()); ?></label>                
            <input class="widefat" id="<?php echo $this->get_field_id('posts_per_page'); ?>" name="<?php echo $this->get_field_name('posts_per_page'); ?>" value="<?php echo $form['posts_per_page']; ?>" type="number" min="1">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'lastest' => __('Lastest', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain()),
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('title_position'); ?>"><?php _e('Title position', kopa_get_domain()); ?></label>
            <input class="widefat" type="number" min="1" name="<?php echo $this->get_field_name('title_position'); ?>" id="<?php echo $this->get_field_id('title_position'); ?>" value="<?php echo esc_attr($form['title_position']); ?>">
            <small><?php _e('Ex: Enter 5 if you want title will be displayed as the 5th item in the portfolio list items', kopa_get_domain()); ?></small>
        </p>

        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['posts_per_page'] = (int) $new_instance['posts_per_page'] ? (int) $new_instance['posts_per_page'] : 16;
        $instance['orderby'] = $new_instance['orderby'];

        $instance['title_position'] = (int) $new_instance['title_position'] ? (int) $new_instance['title_position'] : 1;
        $instance['title_position'] = $instance['title_position'] > $instance['posts_per_page'] ?
            $instance['posts_per_page'] + 1 : 
            $instance['title_position']; 

        return $instance;
    }

}

/**
 * Display testimonials widget
 * @since Nictitate 1.0
 */
class Kopa_Widget_Staffs extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'kopa-our-team-widget', 'description' => __('Display a staffs widget', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_staffs', __('Kopa Staffs Widget', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

        $query_args['post_type'] = 'staffs';
        $query_args['cat_name'] = 'staff_category';
        $query_args['tag_name'] = 'staff_tag';
        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = esc_attr($instance['relation']);
        $query_args['tags'] = $instance['tags'];
        $query_args['posts_per_page'] = (int) $instance['posts_per_page'];
        $query_args['orderby'] = $instance['orderby'];

        $staffs = kopa_widget_posttype_build_query($query_args);

        echo $before_widget;

        if ( $staffs->post_count == 0 ) {
            echo $after_widget;
            return;
        }

        if ( ! empty( $title ) )
            echo $before_title . '<span data-icon="&#xf0c0;"></span>' . $title . $after_title;
        ?>

        <ul class="clearfix">
            <?php 
            $staff_index = 1;

            while ( $staffs->have_posts() ) : $staffs->the_post(); 
                $thumbnail_id = get_post_thumbnail_id();
                $thumbnail = wp_get_attachment_image_src( $thumbnail_id, 'kopa-image-size-3' );
                $staff_position = get_post_meta( get_the_ID(), 'position', true );
                $staff_facebook = get_post_meta( get_the_ID(), 'facebook', true );
                $staff_twitter = get_post_meta( get_the_ID(), 'twitter', true );
                $staff_gplus = get_post_meta( get_the_ID(), 'gplus', true );
            ?>
            <li>
                <article class="entry-item clearfix">
                    <div class="entry-thumb">
                        <a href="<?php the_permalink(); ?>"><img src="<?php echo $thumbnail[0]; ?>" alt="<?php the_title(); ?>"></a>
                    </div>
                    <div class="entry-content">
                        <header>
                            <h6 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><span></span></h6>
                            <span><?php echo $staff_position; ?></span>
                        </header>
                        <?php the_excerpt(); ?>
                        <ul class="our-team-social-link clearfix">
                            <?php if ( ! empty( $staff_facebook ) ) : ?>
                            <li><a href="<?php echo $staff_facebook; ?>" data-icon="&#xf09a;"></a></li>
                            <?php endif; ?>

                            <?php if ( ! empty( $staff_twitter ) ) : ?>
                            <li><a href="<?php echo $staff_twitter; ?>" data-icon="&#xf099;"></a></li>
                            <?php endif; ?>

                            <?php if ( ! empty( $staff_gplus ) ) : ?>
                            <li><a href="<?php echo $staff_gplus; ?>" data-icon="&#xf0d5;"></a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </article>
            </li>
            <?php 
            if ( $staff_index % 4 == 0 && $staff_index != $staffs->post_count )
                echo '</ul><ul class="clearfix mt-20">';
            
            $staff_index++;

            endwhile; ?>
        </ul>

        <?php wp_reset_postdata();
        echo $after_widget;
    }

    function form($instance) {
        $default = array(
            'title' => __( 'Our Team', kopa_get_domain() ),
            'categories' => array(),
            'relation' => 'OR',
            'tags' => array(),
            'posts_per_page' => 4,
            'orderby' => 'lastest'
        );
        $instance = wp_parse_args((array) $instance, $default);
        $title = strip_tags($instance['title']);

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['posts_per_page'] = (int) $instance['posts_per_page'];
        $form['orderby'] = $instance['orderby'];
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_terms('staff_category');
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, (isset($form['categories']) ? $form['categories'] : array()))) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                <?php
                $relation = array(
                    'AND' => __('And', kopa_get_domain()),
                    'OR' => __('Or', kopa_get_domain())
                );
                foreach ($relation as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $tags = get_terms('staff_tag');
                foreach ($tags as $tag) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, (isset($form['tags']) ? $form['tags'] : array()))) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('posts_per_page'); ?>"><?php _e('Number of items:', kopa_get_domain()); ?></label>                
            <input class="widefat" id="<?php echo $this->get_field_id('posts_per_page'); ?>" name="<?php echo $this->get_field_name('posts_per_page'); ?>" value="<?php echo $form['posts_per_page']; ?>" type="number" min="1">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'lastest' => __('Lastest', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain()),
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>

        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['posts_per_page'] = (int) $new_instance['posts_per_page'] ? (int) $new_instance['posts_per_page'] : 16;
        $instance['orderby'] = $new_instance['orderby'];

        $instance['title_position'] = (int) $new_instance['title_position'] ? (int) $new_instance['title_position'] : 1;
        $instance['title_position'] = $instance['title_position'] > $instance['posts_per_page'] ?
            $instance['posts_per_page'] + 1 : 
            $instance['title_position']; 

        return $instance;
    }

}

/**
 * Contact Form Widget
 * @since Nictitate 1.0
 */
class Kopa_Widget_Contact_Form extends WP_Widget {

    function __construct() {
        $widget_ops = array( 'classname' => 'kopa-contact-widget', 'description' => __( 'Contact Form Widget', kopa_get_domain() ) );
        $control_ops = array( 'width' => '400', 'height' => 'auto' );
        parent::__construct( 'kopa_widget_contact_form', __( 'Kopa Contact Form', kopa_get_domain() ), $widget_ops, $control_ops );
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? get_bloginfo('name') : $instance['title'] );
        $description = $instance['description'];
        $email = get_option( 'kopa_theme_options_email_address' );
        $phone_number = get_option( 'kopa_theme_options_phone_number' );
        $address = get_option( 'kopa_theme_options_address' );
        $facebook = get_option( 'kopa_theme_options_social_links_facebook_url' );
        $twitter = get_option( 'kopa_theme_options_social_links_twitter_url' );
        $rss = get_option( 'kopa_theme_options_social_links_rss_url' );
        $flickr = get_option( 'kopa_theme_options_social_links_flickr_url' );
        $pinterest = get_option( 'kopa_theme_options_social_links_pinterest_url' );
        $dribbble = get_option( 'kopa_theme_options_social_links_dribbble_url' );
        $vimeo = get_option( 'kopa_theme_options_social_links_vimeo_url' );
        $youtube = get_option( 'kopa_theme_options_social_links_youtube_url' );
        $instagram = get_option( 'kopa_theme_options_social_links_instagram_url' );

        echo $before_widget;
        ?>
        <div class="wrapper">
            <div class="row-fluid">
                <div class="span6">                             
                    <div id="contact-box">
                        <form id="contact-form" class="clearfix" action="<?php echo admin_url('admin-ajax.php') ?>" method="post">
                            <p class="input-block clearfix">
                                <label class="required" for="contact_name"><?php _e('Name', kopa_get_domain()); ?> <span><?php _e('(required)', kopa_get_domain()); ?></span>:</label>
                                <input class="valid" type="text" name="name" id="contact_name" value="">
                            </p>
                            <p class="input-block clearfix">
                                <label class="required" for="contact_email"><?php _e('Email', kopa_get_domain()); ?> <span><?php _e('(required)', kopa_get_domain()); ?></span>:</label>
                                <input type="email" class="valid" name="email" id="contact_email" value="">
                            </p>
                            <p class="input-block clearfix">
                                <label class="required" for="contact_subject"><?php _e('Subject:', kopa_get_domain()); ?></label>
                                <input type="text" class="valid" name="subject" id="contact_subject" value="">
                            </p>
                            <p class="textarea-block clearfix">                        
                                <label class="required" for="contact_message"><?php _e('Message', kopa_get_domain()); ?> <span><?php _e('(required)', kopa_get_domain()); ?></span>:</label>
                                <textarea rows="6" cols="80" id="contact_message" name="message"></textarea>
                            </p>                            
                            <p class="contact-button clearfix">                    
                                <input type="submit" id="submit-contact" value="<?php _e( 'Submit', kopa_get_domain() ); ?>">
                            </p>
                            <input type="hidden" name="action" value="kopa_send_contact">
                            <?php wp_nonce_field('kopa_send_contact_nicole_kidman', 'kopa_send_contact_nonce'); ?>
                            <div class="clear"></div>                        
                        </form>
                        <div id="response"></div>
                    </div><!--contact-box-->
                </div><!--span6-->
                
                <div class="span6">
                    <div id="contact-info">
                        <h2 class="contact-title"><?php echo $title; ?></h2>
                        <p><?php echo $description; ?></p>
                        <ul class="contact-social-link clearfix">
                            <?php if ( ! empty( $facebook ) ) : ?> 
                            <li><a href="<?php echo $facebook; ?>" data-icon="&#xf09a;"></a></li>
                            <?php endif; ?>

                            <?php if ( ! empty( $twitter ) ) : ?>
                            <li><a href="<?php echo esc_url( $twitter ); ?>" data-icon="&#xf099;"></a></li>
                            <?php endif; ?>

                            <?php if ( $rss != 'HIDE' && $rss == '' ) : ?>
                            <li><a href="<?php bloginfo( 'rss2_url' ); ?>" data-icon="&#xf09e;"></a></li>
                            <?php elseif ( $rss != 'HIDE' ) : ?> 
                            <li><a href="<?php echo esc_url( $rss ); ?>" data-icon="&#xf09e;"></a></li>
                            <?php endif; ?>

                            <?php if ( ! empty( $flickr ) ) : ?>
                            <li><a href="<?php echo esc_url( $flickr ); ?>" data-icon="&#xf16e;"></a></li>
                            <?php endif; ?>

                            <?php if ( ! empty( $pinterest ) ) : ?>
                            <li><a href="<?php echo esc_url( $pinterest ); ?>" data-icon="&#xf0d2;"></a></li>
                            <?php endif; ?>

                            <?php if ( ! empty( $dribbble ) ) : ?>
                            <li><a href="<?php echo esc_url( $dribbble ); ?>" data-icon="&#xf17d;"></a></li>
                            <?php endif; ?>

                            <?php if ( ! empty( $vimeo ) ) : ?>
                            <li><a href="<?php echo esc_url( $vimeo ); ?>" data-icon="&#xf194;"></a></li>
                            <?php endif; ?>

                            <?php if ( ! empty( $youtube ) ) : ?>
                            <li><a href="<?php echo esc_url( $youtube ); ?>" data-icon="&#xf167;"></a></li>
                            <?php endif; ?>

                            <?php if ( ! empty( $instagram ) ) : ?>
                            <li><a href="<?php echo esc_url( $instagram ); ?>" data-icon="&#xf16d;"></a></li>
                            <?php endif; ?>
                        </ul><!--contact-social-link-->
                        <address>
                            <p><i class="fa fa-map-marker"></i><span><?php echo $address; ?></span></p>
                            <p><i class="fa fa-phone"></i><span><?php echo $phone_number; ?></span></p>
                            <p><i class="fa fa-envelope"></i><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></p>
                        </address>
                    </div><!--contact-info-->
                </div><!--span6-->
            </div><!--row-fluid-->
        </div><!--wrapper-->

        <?php
        echo $after_widget;
    } 

    function form( $instance ) {
        $default = array(
            'title'       => get_bloginfo('name'),
            'description' => get_bloginfo('description')
        );

        $instance = wp_parse_args( (array) $instance, $default );
        $title = $instance['title'];
        $form['description'] = $instance['description'];

        ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', kopa_get_domain() ); ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Description', kopa_get_domain() ); ?></label>
            <textarea class="widefat" name="<?php echo $this->get_field_name( 'description' ); ?>" id="<?php echo $this->get_field_id( 'description' ); ?>" rows="10"><?php echo esc_textarea( $form['description'] ); ?></textarea>
        </p>

        <?php
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['description'] = strip_tags( $new_instance['description'] );

        return $instance;
    }
}

/**
 * Categories Widget
 * @since Nictitate 1.0
 */
class Kopa_Widget_Categories extends WP_Widget {


    function __construct() {
        $widget_ops = array( 'classname' => 'kopa-categories-widget', 'description' => __( 'Categories widget', kopa_get_domain() ) );
        $control_ops = array( 'width' => 'auto', 'height' => 'auto' );
        parent::__construct( 'kopa_widget_categories', __( 'Kopa Categories Widget', kopa_get_domain() ), $widget_ops, $control_ops );
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? get_bloginfo('name') : $instance['title'] );
        echo $before_widget; 

        $categories = get_terms('category');
        $parent_categories = array();

        foreach ( $categories as $category ) {
            if ( $category->parent != 0 && 
                 ! in_array($category->parent, $parent_categories)) {
                array_push($parent_categories, $category->parent);
            }
        }
        ?>

        <?php if ( ! empty( $title ) ) 
            echo $before_title . $title . '<span></span>' . $after_title;
        ?>

        <div class="acc-wrapper">
            <div class="accordion-title">
                <h3><a href="#"><?php _e('All Categories', kopa_get_domain()); ?></a></h3>
                <span>+</span>
            </div>
            <div class="accordion-container">
                <ul>
                    <?php foreach ($categories as $category) : ?>
                            
                        <li><a href="<?php echo get_category_link($category->term_id); ?>">
                            <?php echo "{$category->name} ({$category->count})"; ?>
                        </a></li>

                    <?php endforeach; ?>
                </ul>
            </div>
            <?php foreach ($parent_categories as $parent_category) : 
                $parent_category_object = get_category( $parent_category );
                $parent_category_name = $parent_category_object->name;
            ?>
                <div class="accordion-title">
                  <h3><a href="#"><?php echo $parent_category_name; ?></a></h3>
                  <span>+</span>
                </div>
                <div class="accordion-container">
                    <ul>

                        <?php foreach ($categories as $category) :
                            if ($category->parent == $parent_category) : ?>
                                
                                <li><a href="<?php echo get_category_link($category->term_id); ?>">
                                    <?php echo "{$category->name} ({$category->count})"; ?>
                                </a></li>

                        <?php endif; 
                        endforeach; ?>
                        
                    </ul>
                </div>
            <?php endforeach; ?>
        </div><!--acc-wrapper-->
        <?php
        echo $after_widget;
    } 

    function form( $instance ) {
        $default = array(
            'title'       => __( 'Categories', kopa_get_domain() )
        );

        $instance = wp_parse_args( (array) $instance, $default );
        $title = $instance['title'];

        ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', kopa_get_domain() ); ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>">
        </p>

        <?php
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );

        return $instance;
    }

}

/**
 * Display Introduction widget
 * @since Nictitate 1.0
 */
class Kopa_Widget_About extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'kopa-about-widget clearfix', 'description' => __('Display a gallery and description', kopa_get_domain()));
        $control_ops = array('width' => '400', 'height' => 'auto');
        parent::__construct('kopa_widget_about', __('Kopa About Widget', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $image_ids = $instance['image_ids'];
        $image_ids = str_replace( ' ', '', $image_ids );
        $image_ids = explode( ',', $image_ids );

        $description = $instance['description'];

        echo $before_widget;

        if ( ! empty( $title ) )
            echo $before_title . '<span data-icon="&#xf040;"></span>' . $title . $after_title;
        ?>

            <?php if ( ! empty( $image_ids ) ) : ?>
                <div class="entry-thumb">
                    <div class="flexslider about-slider">
                        <ul class="slides">
                            <?php foreach ( $image_ids as $id ) {
                                if ( wp_attachment_is_image( $id ) ) {
                                    echo '<li>' . wp_get_attachment_image( $id, 'kopa-image-size-11' ) . '</li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
            <div class="entry-content">                             
                <p><?php echo $description; ?></p>
            </div>

        <?php wp_reset_postdata();
        echo $after_widget;
    }

    function form($instance) {
        $default = array(
            'title'       => '',
            'image_ids'   => '',
            'description' => ''
        );
        $instance = wp_parse_args((array) $instance, $default);
        $title = strip_tags($instance['title']);
        $image_ids = $instance['image_ids'];
        $description = $instance['description'];
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('image_ids'); ?>"><?php _e('Image IDs:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('image_ids'); ?>" name="<?php echo $this->get_field_name('image_ids'); ?>" type="text" value="<?php echo esc_attr($image_ids); ?>" />
            <small><?php _e( 'Image Attachment IDs, separated by commas.', kopa_get_domain() ); ?></small>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Description:', kopa_get_domain()); ?></label>
            <textarea class="widefat" name="<?php echo $this->get_field_name('description'); ?>" id="<?php echo $this->get_field_id('description'); ?>" rows="10"><?php echo esc_textarea( $description ); ?></textarea>
        </p>

        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['image_ids'] = $new_instance['image_ids'];
        $instance['description'] = $new_instance['description'];
        
        return $instance;
    }

}


class Kopa_Widget_Sequence_Slider extends WP_Widget {

    private $inline_styles_array = array();

    function __construct() {
        $widget_ops = array('classname' => 'sequence-wrapper', 'description' => __('Display a posts slider', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_sequence_slider', __('Kopa Sequence Slider', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

        $query_args['post_type'] = 'post';
        $query_args['cat_name'] = 'category';
        $query_args['tag_name'] = 'post_tag';
        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = esc_attr($instance['relation']);
        $query_args['tags'] = $instance['tags'];
        $query_args['posts_per_page'] = (int) $instance['posts_per_page'];
        $query_args['orderby'] = $instance['orderby'];

        $posts = kopa_widget_posttype_build_query($query_args);

        if ( $posts->post_count == 0 )
            return;

        echo $before_widget;

        if ( ! empty ( $title ) ) {
            echo $before_title . '<span data-icon="&#xf03e;"></span>' . $title . $after_title; 
        }
        ?>

            <a class="prev" href="#"></a>
            <a class="next" href="#"></a>
            
            <div class="sequence-slider">
            
                <div id="sequence" class="kopa-sequence-slider">
                    <ul>
                    <?php if ( $posts->have_posts() ) : while ( $posts->have_posts() ) : $posts->the_post();

                        $slider_background_image = '';

                        if ( get_post_meta( get_the_ID(), 'slider_background_image', true ) ) {
                            $slider_background_image = get_post_meta( get_the_ID(), 'slider_background_image', true );
                        } 
                    ?>
                        <li id="<?php echo $this->get_field_id( 'sequence_slider-item' ) . '-' . get_the_ID(); ?>" style="background: url(<?php echo $slider_background_image; ?>);">
                            <div class="title-2"><h2><?php the_title(); ?></h2></div>
                            <div class="subtitle-2 animate-in">
                                <?php the_excerpt(); ?>
                            </div>
                            <div class="model-2-1">
                                <div class="video-wrapper">
                                    <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'kopa-image-size-11' ); ?></a>
                                </div>
                            </div>
                        </li>
                    <?php endwhile; endif; ?>
                    </ul>
                </div><!--sequence-->
                                
            </div><!--sequence-slider-->
        
        <?php wp_reset_postdata();
        echo $after_widget;
    }

    function form($instance) {
        $default = array(
            'title' => '',
            'categories' => array(),
            'relation' => 'OR',
            'tags' => array(),
            'posts_per_page' => 4,
            'orderby' => 'lastest',
        );
        $instance = wp_parse_args((array) $instance, $default);
        $title = strip_tags($instance['title']);

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['posts_per_page'] = (int) $instance['posts_per_page'];
        $form['orderby'] = $instance['orderby'];
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'categories' ); ?>"><?php _e( 'Categories', kopa_get_domain() ); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'categories' ); ?>" name="<?php echo $this->get_field_name( 'categories' ) ?>[]" multiple="multiple" size="5">
                <option value=""><?php _e('--Select--', kopa_get_domain()); ?></option>
                <?php 
                $categories = get_categories();
                foreach ($categories as $category) :
                ?>
                <option value="<?php echo $category->term_id; ?>" <?php echo in_array($category->term_id, $form['categories']) ? 'selected="selected"' : ''; ?>>
                    <?php echo $category->name.' ('.$category->count.')'; ?></option>
                <?php 
                endforeach; 
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation', kopa_get_domain()); ?>:</label>
            <select class="widefat" name="<?php echo $this->get_field_name('relation'); ?>" id="<?php echo $this->get_field_id('relation'); ?>">
                <option value="OR" <?php selected('OR', $form['relation']); ?>><?php _e('OR', kopa_get_domain()); ?></option>
                <option value="AND" <?php selected('AND', $form['relation']); ?>><?php _e('AND', kopa_get_domain()); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tags' ); ?>"><?php _e( 'Tags', kopa_get_domain() ); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'tags' ); ?>" name="<?php echo $this->get_field_name( 'tags' ) ?>[]" multiple="multiple" size="5">
                <option value=""><?php _e('--Select--', kopa_get_domain()); ?></option>
                <?php 
                $tags = get_tags();
                foreach ($tags as $category) :
                ?>
                <option value="<?php echo $category->term_id; ?>" <?php echo in_array($category->term_id, $form['tags']) ? 'selected="selected"' : ''; ?>>
                    <?php echo $category->name.' ('.$category->count.')'; ?></option>
                <?php 
                endforeach; 
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _e( 'Orderby', kopa_get_domain() ); ?></label>
            <select name="<?php echo $this->get_field_name( 'orderby' ); ?>" id="<?php echo $this->get_field_id('orderby' ); ?>">
                <?php $orderby = array(
                    'lastest'      => __('Lastest', kopa_get_domain()),
                    'popular'      => __('Popular by view count', kopa_get_domain()),
                    'most_comment' => __('Popular by comment count', kopa_get_domain()),
                    'random'       => __('Random', kopa_get_domain())
                );
                
                foreach ($orderby as $value => $label) :
                ?>
                    <option value="<?php echo $value; ?>" <?php selected($value, $form['orderby']); ?>><?php echo $label; ?></option>              
                <?php
                endforeach;
                ?>    
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('posts_per_page'); ?>"><?php _e('Number of items:', kopa_get_domain()); ?></label>                
            <input class="widefat" id="<?php echo $this->get_field_id('posts_per_page'); ?>" name="<?php echo $this->get_field_name('posts_per_page'); ?>" value="<?php echo $form['posts_per_page']; ?>" type="number" min="1">
        </p>

        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['posts_per_page'] = (int) $new_instance['posts_per_page'];
        $instance['orderby'] = $new_instance['orderby'];

        return $instance;
    }
}