<?php
// BdThemes Gallery Shortcode

if (!function_exists('bdt_gallery_function')) {
    remove_shortcode('gallery', 'gallery_shortcode'); // removes the original shortcode
    function bdt_gallery_function( $atts ) {
        global $post;
        $pid = $post->ID;
        $gallery = "";

        if (empty($pid)) {$pid = $post['ID'];}

        if (!empty( $atts['ids'] ) ) {
            $atts['orderby'] = 'post__in';
            $atts['include'] = $atts['ids'];
        }

        extract(shortcode_atts(array(
            'orderby'    => 'menu_order ASC, ID ASC',
            'include'    => '',
            'id'         => $pid,
            'itemtag'    => 'dl',
            'icontag'    => 'dt',
            'captiontag' => 'dd',
            'columns'    => 3,
            'size'       => 'large',
            'link'       => 'file',
            'gutter'     => 10
        ), $atts, 'gallery'));
            
        $args = array('post_type' => 'attachment', 'post_status' => 'inherit', 'post_mime_type' => 'image', 'orderby' => $orderby);
        $gutter = ($gutter) ? 'gutter: '.esc_attr($gutter) : '';

        if (!empty($include)) {$args['include'] = $include;}
        else {
            $args['post_parent'] = $id;
            $args['numberposts'] = -1;
        }

        if ($args['include'] == "") { $args['orderby'] = 'date'; $args['order'] = 'asc';}

        $images = get_posts($args);
        

        $gallery .= '<div class="gallery'.$columns.' bdt-photo-gallery" data-uk-grid="{'.$gutter.'}">';

        foreach ( $images as $image ) {
            //print_r($image); /*see available fields*/
            $thumbnail = wp_get_attachment_image_src($image->ID, 'large');
            $thumbnail = $thumbnail[0];
            $excerpt = $image->post_excerpt;
            $title = $image->post_title;

            $gallery .= '<div class="uk-width-small-1-2 uk-width-medium-1-'.$columns.'">';
                $gallery .= '<div class="bdt-pg-item">';
                    if ($link == 'file') {
                        $gallery .= '<div class="bdt-photo-gallery-links">';
                            $gallery .= '<a href="'.$thumbnail.'" class="gallery-item" data-uk-lightbox="{group:\'group1\'}">';
                                $gallery .= '<i class="uk-icon-search"></i>';
                            $gallery .= "</a>";
                        $gallery .= "</div>";
                    }
                    $gallery .= "<img src='".$thumbnail."'>";
                $gallery .= "</div>";
            $gallery .= "</div>";
        }

        $gallery .= '</div>';
        
        return $gallery;
    }
    add_shortcode('gallery', 'bdt_gallery_function');
}