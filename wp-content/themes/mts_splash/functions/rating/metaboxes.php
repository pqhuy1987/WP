<?php

class PyreThemeFrameworkMetaboxes {
	
	public function __construct()
	{
		add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
		add_action('save_post', array($this, 'save_meta_boxes'));
	}
	
	public function add_meta_boxes()
	{
		//$this->add_meta_box('post_options', 'Post Options', 'post');
		$this->add_meta_box('review_info', 'Review Info', 'reviews', 'normal', 'high');
	}
	
	public function add_meta_box($id, $label, $post_type)
	{
		add_meta_box( 
			'mts_' . $id,
			$label,
			array($this, $id),
			$post_type
		);
	}
	
	public function save_meta_boxes($post_id)
	{
		if(defined( 'DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}
		
		foreach($_POST as $key => $value) {
			if(strstr($key, 'mts_')) {
				update_post_meta($post_id, $key, $value);
			}
		}
	}
	
	public function review_info()
	{	
		include 'style.php';
		include 'review_info.php';
	}
	
	public function post_options()
	{	
		include 'style.php';
		include 'post_options.php';
	}
	
	public function text($id, $label, $desc = '')
	{
		global $post;
		
		$html .= '<div class="mts_metabox_field">';
			$html .= '<label for="mts_' . $id . '">';
			$html .= $label;
			$html .= '</label>';
			$html .= '<div class="field">';
				$html .= '<input type="text" id="mts_' . $id . '" name="mts_' . $id . '" value="' . get_post_meta($post->ID, 'mts_' . $id, true) . '" />';
				if($desc) {
					$html .= '<p>' . $desc . '</p>';
				}
			$html .= '</div>';
		$html .= '</div>';
		
		echo $html;
	}

	public function textarea($id, $label, $desc = '')
	{
		global $post;
		$html = '';
		$html .= '<div class="mts_metabox_field">';
			$html .= '<label for="mts_' . $id . '">';
			$html .= $label;
			$html .= '</label>';
			$html .= '<div class="field">';
				$html .= '<textarea id="mts_' . $id . '" name="mts_' . $id . '" style="margin: 1px; height: 77px; width: 617px; "/>' . get_post_meta($post->ID, 'mts_' . $id, true) . '</textarea>';
				if($desc) {
					$html .= '<p>' . $desc . '</p>';
				}
			$html .= '</div>';
		$html .= '</div>';
		
		echo $html;
	}
	
	public function select($id, $label, $options, $desc = '')
	{
		global $post;
		
		$html .= '<div class="mts_metabox_field">';
			$html .= '<label for="mts_' . $id . '">';
			$html .= $label;
			$html .= '</label>';
			$html .= '<div class="field">';
				$html .= '<select id="mts_' . $id . '" name="mts_' . $id . '">';
				foreach($options as $key => $option) {
					if(get_post_meta($post->ID, 'mts_' . $id, true) == $key) {
						$selected = 'selected="selected"';
					} else {
						$selected = '';
					}
					
					$html .= '<option ' . $selected . 'value="' . $key . '">' . $option . '</option>';
				}
				$html .= '</select>';
				if($desc) {
					$html .= '<p>' . $desc . '</p>';
				}
			$html .= '</div>';
		$html .= '</div>';
		
		echo $html;
	}
	
}

$metaboxes = new PyreThemeFrameworkMetaboxes;