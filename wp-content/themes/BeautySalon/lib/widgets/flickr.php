<?php

class widget_flickr extends WP_Widget { 
	
	// Widget Settings
	function __construct() {
		$widget_ops = array('description' => __('Display your latest flickr photos', 'warp') );
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'flickr' );
		parent::__construct( 'flickr', __('BdThemes Flickr', 'warp'), $widget_ops, $control_ops );
	}
	
	// Widget Output
	function widget($args, $instance) {
		extract($args);
		$title    = apply_filters('widget_title', esc_html($instance['title']));
		$username = $instance['username'];
		$pics     = $instance['pics'];
		$lightbox = $instance['lightbox'];
		
		// ------
		echo $before_widget;
		echo $before_title . esc_html($title) . $after_title;


		$unique_id = uniqid("flickr_");

		$image = ($lightbox) ? '<a class="su-lightbox" data-uk-lightbox="{group: \''.$unique_id.'\'}" href="{{image_b}}" title="{{title}}"> ' : '';
		$image .= '<img src="{{image_s}}" alt="{{title}}" />';
		$image .= ($lightbox) ? '</a> ' : '';		

		echo "<ul id='".$unique_id."' class='flickrfeed'></ul> <div class='clear'></div>";

		echo "<script type='text/javascript'> 
			      jQuery(document).ready(function() {
					jQuery('#".$unique_id."').jflickrfeed({ 
						limit: " . esc_attr($pics) . ", qstrings: { 
						id: '" . esc_attr($username) . "'}, 
						itemTemplate: '<li>" . addslashes($image) . "</li>' 
					});
		          });
		      </script> ";

		echo $after_widget;
	}
	
	// Update
	function update($new_instance, $old_instance) {  
		$instance = $old_instance; 
		
		$instance['title']    = strip_tags( $new_instance['title'] );
		$instance['username'] = strip_tags( $new_instance['username'] );
		$instance['pics']     = strip_tags( $new_instance['pics'] );
		$instance['lightbox'] = strip_tags($new_instance['lightbox']);

		return $instance;
	}
	
	// Backend Form
	function form($instance) {
		
		$defaults = array( 'title' => 'Flickr Widget', 'pics' => '8', 'username' => '95572727@N00', 'lightbox' => '1' ); // Default Values
		$instance = wp_parse_args( (array) $instance, $defaults ); 
?>
        
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>">Widget Title:</label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>
        <p>
			<label for="<?php echo esc_attr($this->get_field_id( 'username' )); ?>">Flickr ID:</label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'username' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'username' )); ?>" value="<?php echo esc_attr($instance['username']); ?>" /><br />Don't know how to get id? Look here: <a href="<?php echo esc_url( __('http://idgettr.com/','warp')); ?>" target="_blank"><?php _e('Flickr idGettr', 'warp'); ?></a>
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'pics' )); ?>">Number of Photos:</label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'pics' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'pics' )); ?>" value="<?php echo esc_attr($instance['pics']); ?>" />
		</p>

		<p>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'lightbox' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'lightbox' )); ?>" type="checkbox" value="1" <?php checked('1', $instance['lightbox'] ); ?> />
			<label for="<?php echo esc_attr($this->get_field_id( 'lightbox' )); ?>">Show in Lightbox?</label>
		</p>
		
    <?php }
}

// Add Widget
function widget_flickr_init() {
	register_widget('widget_flickr');
}
add_action('widgets_init', 'widget_flickr_init');

?>