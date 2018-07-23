<?php
// BdThemes testimonial Shortcode

if (!function_exists('bdthemes_testimonial_shortcode')) {
    function bdthemes_testimonial_shortcode($atts = null, $content = null) {
        $atts = shortcode_atts(array(
            'style'        => '1',
            'name'         => 'Jhon Doe',
            'title'        => '',
            'photo'        => '',
            'company'      => '',
            'url'          => '',
            'target'       => 'blank',
            'italic'       => 'no',
            'radius'       => '',
            'css'          => '',
            'el_class'     => ''
        ), $atts, 'bdt_testimonial');

        $id = uniqid('sutm');
        $cite = '';
        $title = '';
        $name = '';
        $company = '';
        $photo = '';

        // Get Photo form the wordpress with checking
        if ($atts['photo'] != '') {
            if (strpos($atts['photo'], 'http://') !== false || strpos($atts['photo'], 'https://') !== false) {

                $multi_photo = array();
                $multi_photo = explode(',',$atts['photo'], 2);
                $photo = '<img src="' . ($multi_photo[0]) . '" alt="" />';

                if(isset($multi_photo[1]) )
                    $photo .= '<img src="' . ($multi_photo[1]) . '" alt="" />';

            } else {
                $post = get_post( $atts['photo'] );
                $caption = $post->post_excerpt;

                $post_image = wp_get_attachment_image_src( $atts['photo'], 'large' );
                $post_image = $post_image[0];

                if($post_image == '') {
                    $post_image = BDT_SC_JS.'../images/member.svg';
                }
                $photo .= '<img src="' .$post_image.'" alt="'. $caption . '" />';
            }            

        } else {
            $photo = '<img src="' .BDT_SC_JS.'../images/member.svg" alt="" />';
        }


        if (!$atts['title'] && !$atts['name'] && !$atts['photo'] && !$atts['company']) {
          $atts['el_class'] .= ' bdt-testimonial-no-cite';
        }
        else {
            if ($atts['photo']) {
                $atts['el_class'] .= ' bdt-testimonial-has-photo';
                $photo = '<div class="bdt-testimonial-photo">'.$photo.'</div>';
            }

            if ($atts['title']) {
                $title = '<span class="bdt-testimonial-title">' . $atts['title'] . '</span>';
            }
            if ($atts['name']) {
                $name = '<span class="bdt-testimonial-name">' . $atts['name'] . '</span>';
            }
            if ($atts['company']) {
                $company = ( $atts['url'] ) ? '<a href="' . $atts['url'] . '" class="bdt-testimonial-company" target="_' . $atts['target'] . '">' . $atts['company'] . '</a>' : '<span class="bdt-testimonial-company">' . $atts['company'] . '</span>';
                if ($atts['title'])
                    $company = ' - ' . $company;
            } 
            
            $cite = "<div class='bdt-testimonial-cite'>{$name}{$title}{$company}</div>";
        }

        $italic     = ($atts['italic'] == 'yes') ? 'bdt-testimonial-italic' : '';
        $radius     = ($atts['radius']) ? 'border-radius:' .$atts['radius']. ';' : '';
        
        // add class of custom css for visual compser
        $classes[] = vc_shortcode_custom_css_class( $atts['css'], ' ' ) . $atts['el_class'];

        $output = '<div id="'.$id.'" class="bdt-testimonial ' . $atts['el_class'] . ' bdt-testimonial-style-'.$atts['style']. ' '.$italic.'">';
            $output .= '<div class="bdt-testimonial-text bdt-content-wrap" style="'.$radius.'">';
                if ($atts['style'] == 4) {
                    $output .= $photo;
                }
                $output .= '<span class="quote"></span>' . do_shortcode($content);
            $output .= '</div>';
                if ($atts['style'] != 4) {
                    $output .= $photo;
                }
            $output .= $cite;
        $output .= '</div>';
                
        return $output;
    }
    // end of testimonial shortcode

    add_shortcode('bdt_testimonial', 'bdthemes_testimonial_shortcode');
}
