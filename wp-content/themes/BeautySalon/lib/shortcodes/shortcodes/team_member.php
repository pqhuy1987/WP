<?php
// BdThemes team_member Shortcode

if (!function_exists('bdthemes_team_member_shortcode')) {
    function bdthemes_team_member_shortcode($atts=NULL, $content=NULL) {
        $atts = shortcode_atts(array(
            'style'          => '1',
            'background'     => '#ffffff',
            'shadow'         => '',
            'color'          => '#333333',
            'border'         => '0px solid #cccccc',
            'radius'         => '0',
            'text_align'     => 'center',
            'photo'          => '',
            'name'           => __('John Due', 'warp'),
            'role'           => __('Role', 'warp'),
            'facebook_url'   => '',
            'twitter_url'    => '',
            'googleplus_url' => '',
            'pinterest_url'  => '',
            'github_url'     => '',
            'linkedin_url'   => '',
            'url'            => '',
            'el_class'       => '',
            'css'            => ''
        ), $atts, 'bdt_team_member');
        
        $id = uniqid('sum');
        $icons = array();
        $show_icons = '';
        $member_photo ='';
        $classes= array('bdt-member', 'bdt-member-style-'. $atts['style'], 'bdt-member-align-'.$atts['text_align']);

        $box_shadow = ($atts['shadow']) ? 'box-shadow:' . $atts['shadow'] . '; -webkit-box-shadow:' . $atts['shadow'] . ';' : '';
        $radius = ($atts['radius']) ? 'border-radius:' . $atts['radius'] . ';' : '';

        
        if ($atts['facebook_url']) {
            $icon = '<i class="uk-icon-facebook"></i>';
             $icons[] = '<a href="' . $atts['facebook_url'] . '" title="Facebook" class="bdt-memeber-icon bdt-m-facebook" target="_blank">' . $icon . '</a>';
        }
        if ($atts['twitter_url']) {
            $icon = '<i class="uk-icon-twitter"></i>';
             $icons[] = '<a href="' . $atts['twitter_url'] . '" title="Twitter" class="bdt-memeber-icon bdt-m-twitter" target="_blank">' . $icon . '</a>';
        }
        if ($atts['googleplus_url']) {
            $icon = '<i class="uk-icon-google-plus"></i>';
             $icons[] = '<a href="' . $atts['googleplus_url'] . '" title="Google-Plus" class="bdt-memeber-icon bdt-m-google-plus" target="_blank">' . $icon . '</a>';
        }
        if ($atts['pinterest_url']) {
            $icon = '<i class="uk-icon-pinterest"></i>';
             $icons[] = '<a href="' . $atts['pinterest_url'] . '" title="Pinterest" class="bdt-memeber-icon bdt-m-pinterest" target="_blank">' . $icon . '</a>';
        }
        if ($atts['github_url']) {
            $icon = '<i class="uk-icon-github"></i>';
             $icons[] = '<a href="' . $atts['github_url'] . '" title="Github" class="bdt-memeber-icon bdt-m-github" target="_blank">' . $icon . '</a>';
        }
        if ($atts['linkedin_url']) {
            $icon = '<i class="uk-icon-linkedin"></i>';
             $icons[] = '<a href="' . $atts['linkedin_url'] . '" title="Linkedin" class="bdt-memeber-icon bdt-m-linkedin" target="_blank">' . $icon . '</a>';
        }
        
       

        if (count($icons)) {
            $show_icons = '<div class="bdt-member-icons"><div class="bdt-member-ic">' . implode('', $icons) . '</div>';
            $show_icons .= '</div>';
        }
        
        if ($atts['photo'] != '') {
            if (strpos($atts['photo'], 'http://') !== false || strpos($atts['photo'], 'https://') !== false) {

                $multi_photo = array();
                $multi_photo = explode(',',$atts['photo'], 2);
                $member_photo = '<img src="' . ($multi_photo[0]) . '" alt="" />';

                if(isset($multi_photo[1]) )
                    $member_photo .= '<img src="' . ($multi_photo[1]) . '" alt="" />';

            } else {
                $post = get_post( $atts['photo'] );
                $caption = $post->post_excerpt;

                $post_image = wp_get_attachment_image_src( $atts['photo'], 'large' );
                $post_image = $post_image[0];

                if($post_image == '') {
                    $post_image = BDT_SC_JS.'../images/member.svg';
                }
                $member_photo .= '<img src="' .$post_image.'" alt="'. $caption . '" />';
            }            

        } else {
            $default_image = BDT_SC_JS.'../images/member.svg';
            $member_photo = '<img src="' .$default_image.'" alt="" />';
        }

        $title = '<span class="bdt-member-name">' . $atts['name'] . '</span><span class="bdt-member-role">' . $atts['role'] . '</span>';

        $click_able = $atts['url'] ? 'data-url="' . $atts['url'] . '"' : '';
        $click_able_class = $atts['url'] ? 'bdt-member-clickable' : '';

        $classes[] = $click_able_class;

        wp_enqueue_script('team-member');

        // HTML Layout
        $output = '<div id="'.$id.'" class="'.bdt_acssc($classes).'" '.$click_able.' style="background-color:' . $atts['background'] . '; color:' . $atts['color'] . '; border:' . $atts['border'] .';'. $radius . $box_shadow .'">';
            $output .= '<div class="bdt-member-photo">';
                $output .= $member_photo;
                if ($atts['style'] == '2' or $atts['style'] == '4') { $output .= $show_icons; }
            $output .= '</div>';

            $output .= '<div class="bdt-member-info">';
                $output .= $title;
                $output .= ($content) ? '<div class="bdt-member-desc bdt-content-wrap">' . do_shortcode($content) . '</div>' : '';
            $output .= '</div>';

            if ($atts['style'] != '2' and $atts['style'] != '4') { $output .= $show_icons; }

        $output .= '</div>';
        
        return $output;
    }
    // end of team_member shortcode

    add_shortcode('bdt_team_member', 'bdthemes_team_member_shortcode');
}
