<?php
global $wpdb;

// Register ts_ratings to $wpdb global variable
$wpdb->ts_ratings = $wpdb->prefix.'ts_ratings';

// Create ratings table
$sql = "
CREATE TABLE IF NOT EXISTS `{$wpdb->ts_ratings}` (
	`ID` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`post_id` BIGINT(20) UNSIGNED NOT NULL,
	`rating` TINYINT UNSIGNED NOT NULL,
	`IP` TEXT NOT NULL,
	PRIMARY KEY (`ID`)
);
";

require_once(ABSPATH.'wp-admin/includes/upgrade.php');
dbDelta($sql);

// Register rating
add_action('wp_ajax_themesector_rating', 'ts_add_rating');
add_action('wp_ajax_nopriv_themesector_rating', 'ts_add_rating');

function ts_add_rating() {
	global $wpdb;

	$find = $wpdb->query("SELECT * FROM {$wpdb->ts_ratings} WHERE post_id = {$_POST['post_id']} AND IP = '{$_POST['ip']}'");

	if(!$find) {
		$wpdb->insert(
			$wpdb->ts_ratings,
			array('post_id' => $_POST['post_id'], 'rating' => $_POST['rating'], 'IP' => $_POST['ip']),
			array('%d', '%d', '%s')
		);

		echo json_encode(array('success' => true));
	} else {
		echo json_encode(array('success' => false));
	}

	die();
}

function ts_get_post_score($post_id) {
	global $wpdb;

	$find = $wpdb->get_results("SELECT * FROM {$wpdb->ts_ratings} WHERE post_id = " . $post_id);

	if($find) {
		$find_count = count($find);

		foreach($find as $rating) {
			$numbers[] = $rating->rating;
		}

		$number = array_sum($numbers);

		$real_rating = $number / $find_count;

		$real_rating = round($real_rating * 2) / 2;
	} else {
		$real_rating = 0;
	}

	return $real_rating;
}