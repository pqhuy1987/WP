<?php
// BdThemes Counter Shortcode

if (!function_exists('bdthemes_counter_shortcode')) {
    function bdthemes_counter_shortcode($atts, $content=null) {
        $atts = shortcode_atts(array(
            'id'                       => uniqid('suc'),
            'align'                    => 'top',
            'count_start'              => 1,
            'count_end'                => 5000,
            'counter_refresh_interval' => 50,
            'counter_speed'            => 5,
            'separator'                => 'no',
            'decimal'                  => 'no',
            'prefix'                   => '',
            'suffix'                   => '',
            'count_color'              => '',
            'count_size'               => '32px',
            'text_color'               => '',
            'text_size'                => '14px',
            'icon'                     => '',
            'icon_color'               => '',
            'icon_size'                => '24',
            'border'                   => '',
            'padding'                  => '15px',
            'background'               => '',
            'class'                    => ''
        ), $atts);       

        $id          = $atts['id'];
        $border      = ($atts['border']) ? 'border:'.$atts['border'].';' : '';
        $background  = ($atts['background']) ? 'background-color:'.$atts['background'].';' : '';
        $padding     = ($atts['padding']) ? 'padding:'.$atts['padding'].';' : '';
        
        $count_color = ($atts['count_color']) ? 'color:' . $atts['count_color'] . ';' : '';
        $text_color  = ($atts['text_color']) ? 'color:' . $atts['text_color'] . ';' : '';
        $icon_color  = ($atts['icon_color']) ? 'color:' . $atts['icon_color'] . ';' : '';
        $icon_size   = ($atts['icon_size']) ? 'font-size: '.intval($atts['icon_size']).'px;' : 'font-size: '.$atts['count_size'].';';

        // Font-Awesome icon
        if (strpos($atts['icon'], 'uk-icon-') !== false) {
            $atts['icon'] = '<i class="'.$atts['icon'].'" style="' . $icon_color .$icon_size .'"></i>';
        }
        elseif (strpos($atts['icon'], 'fa fa-') !== false) {
            $atts['icon'] = '<i class="uk-icon-' . trim(str_replace('fa fa-', '', $atts['icon'])) . '" style="' . $icon_color .$icon_size .'"></i>';
        }

        $icon        = ($atts['icon']) ? '<div class="bdt-counter-icon">'. $atts['icon'] .'</div>' : '';

        wp_enqueue_script('jquery-appear');
        wp_enqueue_script('jquery-countup');


        $output = '<div id="'. $id .'" class="bdt-counter-wrapper clearfix bdt-counter-'.$atts['align'].'" data-id="'.$id.'" data-from="'.$atts['count_start'].'" data-to="'.$atts['count_end'].'" data-speed="'.$atts['counter_speed'].'" data-separator="'.$atts['separator'].'" data-prefix="'.$atts['prefix'].'" data-suffix="'.$atts['suffix'].'" style="' .$background.$border.$padding.'">';
        $output .= $icon;
        $output .= '<div class="bdt-counter-desc">
                <div id="'. $id .'_count" class="bdt-counter-number" style="font-size: '.$atts['count_size'].'; '. $count_color .'">
                </div>
                <div class="bdt-counter-text" style="'. $text_color .' font-size: '.$atts['text_size'].';">'. do_shortcode($content) .'</div>
            </div>
        </div>';

        return $output;
    }
    // end of counter shortcode

    add_shortcode('bdt_counter', 'bdthemes_counter_shortcode');
}
