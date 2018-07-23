<?php
// BdThemes inline_icon Shortcode

    if (!function_exists('bdthemes_inline_icon_shortcode')) {
        function bdthemes_inline_icon_shortcode($atts = null, $content = null) {
            $atts = shortcode_atts(array(
                'icon'          => 'fa fa-heart',
                'background'    => 'transparent',
                'color'         => '#333333',
                'size'          => '16',
                'radius'        => '0px',
                'square_size'   => 'yes',
                'border'        => '0px solid #cccccc',
                'margin'        => '0px',
                'padding'       => '15px',
                'url'           => '',
                'target'        => 'blank',
                'inline_text'   => '',
                'class'         => ''
            ), $atts, 'bdt_inline_icon');

            $id            = uniqid('suico_');
            $css           = '';
            $atts['size']  = intval($atts['size']);
            $square_size   = ($atts['square_size'] === 'no') ? '' : ' square-size';
            $background    = ($atts['background']) ? 'background-color:' . $atts['background'] .';' : '';
            $color         = ($atts['color']) ? 'color:' . $atts['color'] .';' : '';
            $border        = ($atts['border']) ? 'border:' . $atts['border'] .';' : '';
            $border_radius = ($atts['radius']) ? '-webkit-border-radius:' . $atts['radius'] . ';border-radius:' . $atts['radius'] .';' : '';
            $padding       = ($atts['padding']) ? 'padding:' . $atts['padding'] .';' : '';

            if ($atts['margin']) {
                $css .= 'margin:' . $atts['margin'] .';';
            }

            if (strpos($atts['icon'], 'uk-icon-') !== false or strpos($atts['icon'], 'fa fa-') !== false) {
                $css .= 'font-size:' . intval($atts['size']) . 'px;line-height:' . intval($atts['size']) . 'px;'.$background.$color.$border.$border_radius.$padding;
            }
            
            if ($css) {
                $css = 'style="'.$css.'"';
            }

            
            // Font-Awesome icon
            if (strpos($atts['icon'], 'uk-icon-') !== false) {
                $atts['icon'] = '<i class="'.$atts['icon'].'" '.$css.'></i>';
            }
            elseif (strpos($atts['icon'], 'fa fa-') !== false) {
                $atts['icon'] = '<i class="uk-icon-' . trim(str_replace('fa fa-', '', $atts['icon'])) . '" '.$css.'></i>';
            }

            // Prepare text
            if ($content) {
                $content = '<span class="bdt-icon-text">' . $content . '</span>';
            } elseif ($atts['inline_text']) {
                $content = '<span class="bdt-icon-text">' .$atts['inline_text'] .'</span>';
            }

            if (!$atts['url']) {
                $icon = '<span id="'.$id.'" class="bdt-icon' .$square_size. bdt_ecssc($atts) . '">' . $atts['icon'] . do_shortcode($content) . '</span>';
            } else {
                $icon = '<a id="'.$id.'" href="' . $atts['url'] . '" class="bdt-icon' .$square_size. bdt_ecssc($atts) . '" target="_' . $atts['target'] . '">' . $atts['icon'] .do_shortcode($content) . '</a>';
            }         

            return $icon;
        }
        // end of inline_icon shortcode

        add_shortcode('bdt_inline_icon', 'bdthemes_inline_icon_shortcode');
    }
