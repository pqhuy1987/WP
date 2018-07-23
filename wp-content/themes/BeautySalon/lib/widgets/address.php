<?php

class widget_address extends WP_Widget { 
	
	// Widget Settings
	function __construct() {
		$widget_ops  = array('description' => __('Display your address to any widget position beautifully.', 'warp'));
		$control_ops = array('width' => 300, 'height' => 350, 'id_base' => 'address');
		parent::__construct('address', __('BdThemes Address', 'warp'), $widget_ops, $control_ops );
	}
	
	// Widget Output
	function widget($args, $instance) {
		extract($args);
		$title         = apply_filters('widget_title', esc_html($instance['title']));
		$address       = $instance['address'];
		$phone         = $instance['phone'];
		$email         = $instance['email'];
		$website_title = $instance['website_title'];
		$website       = $instance['website'];
		$googlemap     = $instance['googlemap'];

		echo $before_widget;
		echo $before_title . esc_html($title) . $after_title;

		echo '<ul class="contact-address">';
			if ($address) {
				echo '<li><i class="uk-icon-justify uk-icon-home"></i> <span class="contact_content">'.$address.'</span></li>';
			}
			if ($phone) {
				echo '<li><i class="uk-icon-justify uk-icon-phone"></i> <span class="contact_content">'.$phone.'</span></li>';
			}
			if ($email) {
				echo '<li><i class="uk-icon-justify uk-icon-envelope"></i> <span class="contact_content">'.$email.'</span></li>';
			}
			if ($website) {
				echo '<li><i class="uk-icon-justify uk-icon-link"></i> <span class="contact_content"><a href="'.$website.'">'.$website_title.'</a></span></li>';
			}
			if ($googlemap) {
				echo '<li><i class="uk-icon-justify uk-icon-map-marker"></i> <span class="contact_content"><a href="'.$googlemap.'" target="_blank">'.__("Find on Google Map", "warp").'</a></span></li>';
			}
		echo '</ul>';


		echo $after_widget;
	}
	
	// Update
	function update($new_instance, $old_instance) {  
		$instance                  = $old_instance; 
		$instance['title']         = strip_tags( $new_instance['title'] );
		$instance['address']       = strip_tags( $new_instance['address'] );
		$instance['phone']         = strip_tags( $new_instance['phone'] );
		$instance['email']         = strip_tags( $new_instance['email'] );
		$instance['website_title'] = strip_tags($new_instance['website_title']);
		$instance['website']       = strip_tags($new_instance['website']);
		$instance['googlemap']     = strip_tags($new_instance['googlemap']);

		return $instance;
	}
	
	// Backend Form
	function form($instance) {
		
		$defaults = array('title' => 'Address Widget', 'phone' => '', 'email' => '', 'address' => '', 'website_title' => '', 'website' => '', 'googlemap' => ''); // Default Values
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		?>
        
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>">Title:</label>
			<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>
        <p>
			<label for="<?php echo esc_attr($this->get_field_id('address')); ?>">Your Address:</label>
			<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('address')); ?>" name="<?php echo esc_attr($this->get_field_name('address')); ?>" value="<?php echo esc_attr($instance['address']); ?>" placeholder="BdThemes Ltd, Lathifpur, Bogra, Bangladesh" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('phone')); ?>">Phone Number:</label>
			<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('phone')); ?>" name="<?php echo esc_attr($this->get_field_name('phone')); ?>" value="<?php echo esc_attr($instance['phone']); ?>" placeholder="+880-1718-542596" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('email')); ?>">Email Address:</label>
			<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('email')); ?>" name="<?php echo esc_attr($this->get_field_name('email')); ?>" value="<?php echo esc_attr($instance['email']); ?>" placeholder="info@bdthemes.com" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('website_title')); ?>">Website Title:</label>
			<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('website_title')); ?>" name="<?php echo esc_attr($this->get_field_name('website_title')); ?>" value="<?php echo esc_attr($instance['website_title']); ?>" placeholder="BdThemes Limited" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('website')); ?>">Website Link:</label>
			<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('website')); ?>" name="<?php echo esc_attr($this->get_field_name('website')); ?>" value="<?php echo esc_attr($instance['website']); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('googlemap')); ?>">Google Map Link:</label>
			<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('googlemap')); ?>" name="<?php echo esc_attr($this->get_field_name('googlemap')); ?>" value="<?php echo esc_attr($instance['googlemap']); ?>" /><br>Use google map direct link here.
		</p>
		
    <?php }
}

// Add Widget
function widget_address_init() {
	register_widget('widget_address');
}
add_action('widgets_init', 'widget_address_init');

?>