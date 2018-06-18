<?php
// make sure to not include translations
$args['presets']['default'] = array(
	'title' => 'Default',
	'demo' => 'http://demo.mythemeshop.com/splash/',
	'thumbnail' => get_template_directory_uri().'/options/demo-importer/demo-files/default/thumb.jpg',
	'menus' => array( 'primary-menu' => 'Primary Menu', 'secondary-menu' => 'Secondary Menu', 'mobile' => ''), // menu location slug => Demo menu name
	'options' => array( 'show_on_front' => 'posts', 'posts_per_page' => 5 ),
);

$args['presets']['shop'] = array(
	'title' => 'Shop',
	'demo' => 'http://demo.mythemeshop.com/splash/shop/',
	'thumbnail' => get_template_directory_uri().'/options/demo-importer/demo-files/shop/thumb.jpg',
	'menus' => array( 'primary-menu' => 'Primary Menu', 'secondary-menu' => 'Secondary Menu', 'mobile' => ''), // menu location slug => Demo menu name
	'options' => array( 'show_on_front' => 'page', 'page_on_front' => '127' ),
);

$args['presets']['food'] = array(
	'title' => 'Food',
	'demo' => 'http://demo.mythemeshop.com/splash-food/',
	'thumbnail' => get_template_directory_uri().'/options/demo-importer/demo-files/food/thumb.jpg',
	'menus' => array( 'primary-menu' => 'Primary Menu', 'secondary-menu' => '', 'mobile' => ''), // menu location slug => Demo menu name
	'options' => array( 'show_on_front' => 'posts', 'posts_per_page' => 4 ),
);

$args['presets']['movie'] = array(
	'title' => 'Movie',
	'demo' => 'http://demo.mythemeshop.com/splash-movie/',
	'thumbnail' => get_template_directory_uri().'/options/demo-importer/demo-files/movie/thumb.jpg',
	'menus' => array( 'primary-menu' => '', 'secondary-menu' => 'Secondary Menu', 'mobile' => ''), // menu location slug => Demo menu name
	'options' => array( 'show_on_front' => 'posts', 'posts_per_page' => 6 ),
);

$args['presets']['sports'] = array(
	'title' => 'Sports',
	'demo' => 'http://demo.mythemeshop.com/splash-sports/',
	'thumbnail' => get_template_directory_uri().'/options/demo-importer/demo-files/sports/thumb.jpg',
	'menus' => array( 'primary-menu' => 'Primary Menu', 'secondary-menu' => 'Secondary Menu', 'mobile' => ''), // menu location slug => Demo menu name
	'options' => array( 'show_on_front' => 'posts', 'posts_per_page' => 5 ),
);

$args['presets']['gaming'] = array(
	'title' => 'Gaming',
	'demo' => 'http://demo.mythemeshop.com/splash-gaming/',
	'thumbnail' => get_template_directory_uri().'/options/demo-importer/demo-files/gaming/thumb.jpg',
	'menus' => array( 'primary-menu' => '', 'secondary-menu' => 'Secondary Menu', 'mobile' => ''), // menu location slug => Demo menu name
	'options' => array( 'show_on_front' => 'posts', 'posts_per_page' => 10 ),
);

$args['presets']['relationship'] = array(
	'title' => 'Relationship',
	'demo' => 'http://demo.mythemeshop.com/splash-relationship/',
	'thumbnail' => get_template_directory_uri().'/options/demo-importer/demo-files/relationship/thumb.jpg',
	'menus' => array( 'primary-menu' => 'Primary Menu', 'secondary-menu' => 'Secondary Menu', 'mobile' => ''), // menu location slug => Demo menu name
	'options' => array( 'show_on_front' => 'posts', 'posts_per_page' => 10 ),
);

$args['presets']['pets'] = array(
	'title' => 'Pets',
	'demo' => 'http://demo.mythemeshop.com/splash-pets/',
	'thumbnail' => get_template_directory_uri().'/options/demo-importer/demo-files/pets/thumb.jpg',
	'menus' => array( 'primary-menu' => '', 'secondary-menu' => 'Secondary Menu', 'mobile' => ''), // menu location slug => Demo menu name
	'options' => array( 'show_on_front' => 'posts', 'posts_per_page' => 10 ),
);

global $mts_presets;
$mts_presets = $args['presets'];
