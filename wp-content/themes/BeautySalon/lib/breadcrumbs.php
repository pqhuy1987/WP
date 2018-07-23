<?php
/*
 *  Breadcrumbs Function
 *  Source: http://dimox.net/wordpress-breadcrumbs-without-a-plugin/
 */

function bdthemes_breadcrumbs() {

    $showOnHome  = 1; // 1 - show breadcrumbs on the homepage, 0 - don't show
    $delimiter   = '/'; // delimiter between crumbs
    $home        = (get_theme_mod('bdt_home_title')) ? get_theme_mod('bdt_home_title') : get_bloginfo('name'); // text for the 'Home' link
    $blog        = get_theme_mod('bdt_blog_title', 'Blog');
    $shop        = get_theme_mod('bdt_woocommerce_title', 'Shop');;
    $forums      = get_theme_mod('bdt_bbpress_title', 'Forum');;
    $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
    $before      = '<span class="current">'; // tag before the current crumb
    $after       = '</span>'; // tag after the current crumb
    $output      = array();
 
    global $post;
    $homeLink = home_url();

    global $woocommerce;
    if($woocommerce) {
        $shopLink = get_permalink( woocommerce_get_page_id('shop') );
    }

    $forumLink = get_post_type_archive_link('forum');

    if (is_home() || is_front_page()) {
        if ($showOnHome == 1) {
            $output[] = '<div id="tm-breadcrumb" class="tm-breadcrumb"><a href="' . esc_url($homeLink) . '">' . esc_html($home) . '</a> ' . esc_html($delimiter) . ' ' . esc_html($blog) . '</a></div>';
        }
    } else {
        $output[] = '<div id="tm-breadcrumb" class="tm-breadcrumb"><a href="' . esc_url($homeLink) . '">' . esc_html($home) . '</a> ' . esc_html($delimiter) . ' ';

        if (is_category()) {
            $thisCat = get_category(get_query_var('cat'), false);
            if ($thisCat->parent != 0) {
                $output[] = get_category_parents($thisCat->parent, TRUE, ' ' . esc_html($delimiter) . ' ') . '';
            }
            $output[] = $before . __('Category', 'warp') . ': ' . esc_html(single_cat_title('', false)) . '' . $after;

        } elseif ( is_search() ) {
            $output[] = $before . __('Search', 'warp') . $after;
        } elseif ( is_day() ) {
            $output[] = '<a href="' . esc_url(get_year_link(get_the_time('Y'))) . '">' . esc_html(get_the_time('Y')) . '</a> ' . esc_html($delimiter) . ' ';
            $output[] = '<a href="' . esc_url(get_month_link(get_the_time('Y'),get_the_time('m'))) . '">' . esc_html(get_the_time('F')) . '</a> ' . esc_html($delimiter) . ' ';
            $output[] = $before . get_the_time('d') . $after;

        } elseif ( is_month() ) {
            $output[] = '<a href="' . esc_url(get_year_link(get_the_time('Y'))) . '">' . esc_html(get_the_time('Y')) . '</a> ' . esc_html($delimiter) . ' ';
            $output[] = $before . esc_html(get_the_time('F')) . $after;

        } elseif ( is_year() ) {
            $output[] = $before . esc_html(get_the_time('Y')) . $after;

        } elseif( class_exists('Woocommerce') && is_shop() ) {
            $output[] = $before . '<a href="' . esc_url($shopLink) . '">' . esc_html($shop) . '</a>' . $after;

        } elseif( class_exists('Woocommerce') && is_product() ) {
            $output[] = '<a href="' . esc_url($shopLink) . '">' . esc_html($shop) . '</a> ' . esc_html($delimiter) . ' ' . $before . esc_html(get_the_title()) . $after;

        } elseif( class_exists('bbPress') && is_bbpress() ) {
            $output[] = '<a href="' . esc_url($forumLink) . '">' . esc_html($forums) . '</a> ' . esc_html($delimiter) . ' ' . $before . esc_html(get_the_title()) . $after . '</a>';

        } elseif ( is_single() && !is_attachment() ) {
            if ( get_post_type() != 'post' ) {
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                if ($showCurrent == 1) {
                    $output[] = ' ' . $before . esc_html(get_the_title()) . $after;
                }
            } else {
                $cat = get_the_category(); $cat = $cat[0];
                $cats = get_category_parents($cat, TRUE, ' ' . esc_html($delimiter) . ' ');
            if ($showCurrent == 0) $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
                $output[] = $cats; // No need to escape here
            if ($showCurrent == 1) $output[] = $before . esc_html(get_the_title()) . $after;
            }

        } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
            $post_type = get_post_type_object(get_post_type());
            $output[] = $before . esc_html($post_type->labels->singular_name) . $after;

        } elseif ( is_attachment() ) {
            if ($showCurrent == 1) $output[] = $before . esc_html(get_the_title()) . $after;

        } elseif ( is_page() && !$post->post_parent ) {
            if ($showCurrent == 1) $output[] = $before . esc_html(get_the_title()) . $after;

        } elseif ( is_page() && $post->post_parent ) {
            $parent_id  = $post->post_parent;
            $breadcrumbs = array();
            while ($parent_id) {
                $page = get_page($parent_id);
                $breadcrumbs[] = '<a href="' . esc_url(get_permalink($page->ID)) . '">' . esc_html(get_the_title($page->ID)) . '</a>';
                $parent_id  = $page->post_parent;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
            for ($i = 0; $i < count($breadcrumbs); $i++) {
                $output[] = $breadcrumbs[$i]; // No need to escape here
            if ($i != count($breadcrumbs)-1) $output[] = ' ' . esc_html($delimiter) . ' ';
            }
            if ($showCurrent == 1) $output[] = ' ' . esc_html($delimiter) . ' ' . $before . esc_html(get_the_title()) . $after;

        } elseif ( is_tag() ) {
            $output[] = $before . __('Tag', 'warp') . ': ' . esc_html(single_tag_title('', false)) . $after;
        } elseif ( is_author() ) {
            global $author;
            $userdata = get_userdata($author);
            $output[] = $before . __('Articles by', 'warp') . ' ' . esc_html($userdata->display_name) . $after;
        } elseif ( is_404() ) {
            $output[] = $before . __('Error 404', 'warp') . $after;
        }
        if ( get_query_var('paged') ) {
            $output[] = ' (' . __('Page', 'warp') . ' ' . esc_html(get_query_var('paged')) . ')';
        }
        $output[] = '</div>';
    }

    return implode("\n", $output);

}

?>