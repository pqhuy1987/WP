<?php
// BdThemes icon_list_item Shortcode

if (!function_exists('bdthemes_icon_list_item_shortcode')) {
    function bdthemes_icon_list_item_shortcode($atts = null, $content = null) {
        $atts = shortcode_atts(array(
            'title'       => 'Unique Design',
            'title_color' => '#444444',
            'title_size'  => '18px',
            'color'       => '#444444',
            'icon'        => 'uk-icon-eye',
            'icon_color'  => '#666666',
            'icon_size'   => 32,
            'link_text'   => 'Read More...',
            'icon_align'  => 'top',
            'url'         => '#',
            'target'      => 'self',
            'class'       => ''
        ), $atts, 'bdt_icon_list_item');

        $id = uniqid('suil');
        $icon = '';

        $title_color    = ($atts['title_color']) ? 'color:' . $atts['title_color'] . ';' : '';
        $title_size     = ($atts['title_size']) ? 'font-size: '.$atts['title_size'].';' : '';
        $icon_color     = ($atts['icon_color']) ? 'color:' . $atts['icon_color'] . ';' : '';
        $icon_size      = ($atts['icon_size']) ? 'font-size: '.intval($atts['icon_size']).'px;' : '';
        $link_text      = ($atts['link_text'] && $atts['url']) ? '<a href="'.$atts['url'].'" target="'.$atts['target'].'">'.$atts['link_text'].'</a>' : '';

        $classes        = array('bdt-icon-list', 'bdt-icon-align-'. $atts['icon_align'], bdt_ecssc($atts));


        // Font-Awesome icon
        if (strpos($atts['icon'], 'uk-icon-') !== false) {
            $icon = '<i class="list-img-icon '.$atts['icon'].'" style="'.$icon_color.$icon_size.'"></i>';
        }
        elseif (strpos($atts['icon'], 'fa fa-') !== false) {
            $icon = '<i class="list-img-icon uk-icon-' . trim(str_replace('fa fa-', '', $atts['icon'])) . '" style="'.$icon_color.$icon_size.'"></i>';
        }

        $return = '
            <div id="'.$id.'" class="'.bdt_acssc($classes).'">
                <div class="icon_list_item">
                    <div class="icon_list_wrapper">
                        <div class="icon_list_icon" font-size:' . intval($atts['icon_size']) . 'px; max-width:' . intval($atts['icon_size']) . 'px; height:' . intval($atts['icon_size']) . 'px;">'
                            . $icon . '
                        </div>
                    </div>
                    <div class="icon_description">
                        <h3 style="' .$title_color.$title_size.'">'.$atts['title'].'</h3>
                        <div class="icon_description_text" style="color:' . $atts['color'] . ';">'
                         . do_shortcode($content) .
                        '</div>'.$link_text.'
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>';

        return $return;
    }
    // end of icon_list_item shortcode
    add_shortcode('bdt_icon_list_item', 'bdthemes_icon_list_item_shortcode');
}