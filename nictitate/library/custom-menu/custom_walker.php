<?php

/**
 * Walker Nav Menu to customize menu with icon
 */
class kopa_main_menu extends Walker_Nav_Menu {

    function start_el(&$output, $item, $depth = 0, $args = array(), $current_object_id = 0) {
        global $wp_query;
        $kopa_icons = unserialize(KOPA_ICON);

        $indent = ( $depth ) ? str_repeat("\t", $depth) : '';

        $class_names = $value = '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;

        $icon = $item->icon;

        if (!empty($icon)) {
            if (array_key_exists($icon, $kopa_icons)) {
                $icon = '<i data-icon="' . $kopa_icons[$icon] . '"></i>';
            } else {
                $icon = '<i data-icon="' . $kopa_icons['home'] . '"></i>';
            }
        }
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item));
        $class_names = ' class="' . esc_attr($class_names) . '"';

        $output .= $indent . '<li id="menu-item-' . $item->ID . '"' . $value . $class_names . '>';

        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .=!empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .=!empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .=!empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

        $item_output = $args->before;

        if ($depth == 0) {

            $item_output .= '<a' . $attributes . ' data-description="' . $item->description . '">';
            $item_output .= $icon . $args->link_before . '<span>' . apply_filters('the_title', $item->title, $item->ID) . '</span>' . $args->link_after;
            $item_output .= '</a>';
        } else {

            $item_output .= '<a' . $attributes . ' data-description="' . $item->description . '">';
            $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
            $item_output .= '</a>';
        }

        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

}
