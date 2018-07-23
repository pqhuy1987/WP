<?php

function beautysalon_customize_register( $wp_customize ) {
    // alert custom control
    class beautysalon_Customize_Alert_Control extends WP_Customize_Control {
        public $type = 'alert';
        public $text = '';
        public $alert_type = '';
        public function render_content() {
        ?>
        <label>
            <span class="beautysalon-alert <?php echo esc_html( $this->alert_type ); ?>"><?php echo esc_html( $this->text ); ?></span>
        </label>
        <?php
        }
    } 

    // Textarea custom control
    class beautysalon_Customize_Textarea_Control extends WP_Customize_Control {
        public $type = 'textarea';
 
        public function render_content() {
            ?>
        <label>
            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
            <textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
        </label>
        <?php
        }
    }

    // Select custom control with default option
    class beautysalon_Customize_Select_Control extends WP_Customize_Control {
        public $type = 'select';

        public function render_content() {
            ?>
                <label>
                  <span><?php echo esc_html( $this->label ); ?></span>
                  <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
                  <select>
                    <option value="0" <?php if(!$this->value): ?>selected="selected"<?php endif; ?>><?php esc_attr_e('Default', 'beautysalon'); ?></option>
                  </select>
                </label>
            <?php
        }
    }

    // Layout custom control for select sidebar
    class beautysalon_Customize_Layout_Control extends WP_Customize_Control {

        public $type = 'layout';
        public function render_content() { ?>

            <div class="beautysalon-layout-select">

            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>

            <ul>
            <?php 
                $name = '_customize-radio-' . $this->id; 

                foreach ( $this->choices as $value => $label ) : ?>
                    <li>
                        <label for="<?php echo esc_attr($this->id); ?>[<?php echo esc_attr( $value ); ?>]" title="<?php echo esc_attr( $label ); ?>">
                            <input type="radio" name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($this->id); ?>[<?php echo esc_attr( $value ); ?>]" value="<?php echo esc_attr( $value ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?> />
                            <img src="<?php echo get_template_directory_uri() . '/admin/images/'.esc_attr( $value ).'.png';  ?>" alt="Left Sidebar" />
                        </label>
                    </li>
               
                    <?php endforeach; ?>

                </ul>
            </div>
            <?php
        }
    }




    class beautysalon_Alpha_Color_Control extends WP_Customize_Control {
        /**
         * Official control name.
         */
        public $type = 'alpha-color';
        /**
         * Add support for palettes to be passed in.
         *
         * Supported palette values are true, false, or an array of RGBa and Hex colors.
         */
        public $palette;
        /**
         * Add support for showing the opacity value on the slider handle.
         */
        public $show_opacity;
        /**
         * Enqueue scripts and styles.
         *
         * Ideally these would get registered and given proper paths before this control object
         * gets initialized, then we could simply enqueue them here, but for completeness as a
         * stand alone class we'll register and enqueue them here.
         */
        public function enqueue() {
            wp_enqueue_script(
                'alpha-color-picker',
                get_template_directory_uri() . '/admin/js/alpha-color-picker.js',
                array( 'jquery', 'wp-color-picker' ),
                '1.0.0',
                true
            );
            wp_enqueue_style(
                'alpha-color-picker',
                get_template_directory_uri() . '/admin/css/alpha-color-picker.css',
                array( 'wp-color-picker' ),
                '1.0.0'
            );
        }
        /**
         * Render the control.
         */
        public function render_content() {
            // Process the palette
            if ( is_array( $this->palette ) ) {
                $palette = implode( '|', $this->palette );
            } else {
                // Default to true.
                $palette = ( false === $this->palette || 'false' === $this->palette ) ? 'false' : 'true';
            }
            // Support passing show_opacity as string or boolean. Default to true.
            $show_opacity = ( false === $this->show_opacity || 'false' === $this->show_opacity ) ? 'false' : 'true';
            // Begin the output. ?>
            <label>
                <?php // Output the label and description if they were passed in.
                if ( isset( $this->label ) && '' !== $this->label ) {
                    echo '<span class="customize-control-title">' . sanitize_text_field( $this->label ) . '</span>';
                }
                if ( isset( $this->description ) && '' !== $this->description ) {
                    echo '<span class="description customize-control-description">' . sanitize_text_field( $this->description ) . '</span>';
                } ?>
                <input class="alpha-color-control" type="text" data-show-opacity="<?php echo esc_attr($show_opacity); ?>" data-palette="<?php echo esc_attr( $palette ); ?>" data-default-color="<?php echo esc_attr( $this->settings['default']->default ); ?>" <?php $this->link(); ?>  />
            </label>
            <?php
        }
    }

    /**
     * Google Fonts Control
     */

    class beautysalon_Google_Fonts_Control extends WP_Customize_Control {
        public function render_content() {
            $this_val = $this->value() ? $this->value() : 'Open Sans';  ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                    <select <?php $this->link(); ?> style="width:100%;">
                    <option value="Open Sans" <?php if ( ! $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Default', 'beautysalon' ); ?></option>
                    <?php
                    // Add custom fonts from child themes
                    if ( function_exists( 'beautysalon_add_custom_fonts' ) ) {
                        $fonts = beautysalon_add_custom_fonts();
                        if ( $fonts && is_array( $fonts ) ) { ?>
                            <optgroup label="<?php esc_html_e( 'Custom Fonts', 'beautysalon' ); ?>">
                                <?php foreach ( $fonts as $font ) { ?>
                                    <option value="<?php echo $font; ?>" <?php if ( $font == $this_val ) echo 'selected="selected"'; ?>><?php echo $font; ?></option>
                                <?php } ?>
                            </optgroup>
                        <?php }
                    } ?>
                    <?php
                    // Get Standard font options
                    if ( $std_fonts = beautysalon_standard_fonts() ) { ?>
                        <optgroup label="<?php esc_html_e( 'Standard Fonts', 'beautysalon' ); ?>">
                            <?php
                            // Loop through font options and add to select
                            foreach ( $std_fonts as $font ) { ?>
                                <option value="<?php echo $font; ?>" <?php selected( $font, $this_val ); ?>><?php echo $font; ?></option>
                            <?php } ?>
                        </optgroup>
                    <?php } ?>
                    <?php
                    // Google font options
                    if ( $google_fonts = beautysalon_google_fonts_array( $google_fonts ) ) { ?>
                        <optgroup label="<?php esc_html_e( 'Google Fonts', 'beautysalon' ); ?>">
                            <?php
                            // Loop through font options and add to select
                            foreach ( $google_fonts as $font ) { ?>
                                <option value="<?php echo $font; ?>" <?php selected( $font, $this_val ); ?>><?php echo $font; ?></option>
                            <?php } ?>
                        </optgroup>
                    <?php } ?>
                </select>
            </label>
            <?php 
        }
    }
}
add_action( 'customize_register', 'beautysalon_customize_register' );


/* custom sanitization */
function beautysalon_sanitize_textarea($string) {
    return htmlspecialchars_decode(esc_textarea( $string));
}


// custom sanitize
function beautysalon_sanitize_choices( $input, $setting ) {
    global $wp_customize;
 
    $control = $wp_customize->get_control( $setting->id );
 
    if ( array_key_exists( $input, $control->choices ) ) {
        return $input;
    } else {
        return $setting->default;
    }
}

function beautysalon_sanitize_checkbox( $input ) {
    if ( $input == 1 ) {
        return 1;
    } else {
        return 0;
    }
}

function beautysalon_sanitize_rgba_color( $color ) {
    if ( '' === $color ) {
        return '';
    }

    // 3 or 6 hex digits, or the empty string.
    if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
        return $color;
    } elseif (preg_match('/\A^rgba\(([0]*[0-9]{1,2}|[1][0-9]{2}|[2][0-4][0-9]|[2][5][0-5])\s*,\s*([0]*[0-9]{1,2}|[1][0-9]{2}|[2][0-4][0-9]|[2][5][0-5])\s*,\s*([0]*[0-9]{1,2}|[1][0-9]{2}|[2][0-4][0-9]|[2][5][0-5])\s*,\s*([0-9]*\.?[0-9]+)\)$\z/im', $color)) {
        return $color;
    }
}

// drawer widget check for customizer option visible 
function beautysalon_drawer_widget_check($control) {

    if ( is_active_sidebar( 'drawer' ) ) {
        return true;
    } else {
        return false;
    }
}

// footer widget check for customizer option visible 
function beautysalon_footer_widget_check($control) {

    if ( get_theme_mod('beautysalon_footer_widgets') and (is_active_sidebar( 'footer-widgets' ) or is_active_sidebar( 'footer-widgets-2' ) or is_active_sidebar( 'footer-widgets-3' ) or is_active_sidebar( 'footer-widgets-4' )) ) {
        return true;
    } else {
        return false;
    }
}

// toolbar activate check for customizer option visible 
function beautysalon_toolbar_check() {

    if ( get_theme_mod('beautysalon_toolbar') ) {
        return true;
    } else {
        return false;
    }
}

// toolbar activate check for customizer option visible 
function beautysalon_toolbar_left_custom_check() {

    if ( get_theme_mod('beautysalon_toolbar') == 1 and get_theme_mod('beautysalon_toolbar_left') == 'custom-left') {
        return true;
    } else {
        return false;
    }
}

function beautysalon_toolbar_right_custom_check() {

    if ( get_theme_mod('beautysalon_toolbar') == 1 and get_theme_mod('beautysalon_toolbar_right') == 'custom-right' ) {
        return true;
    } else {
        return false;
    }
}

function beautysalon_header_type_check() {

    if ( get_theme_mod('beautysalon_header_style') != 'style2' and get_theme_mod('beautysalon_header_type') == 'fixed' ) {
        return true;
    } else {
        return false;
    }
}


