<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */
//die('test');
/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define('WP_USE_THEMES', true);

/** Loads the WordPress Environment and Template */
// require(__DIR__ . '/../../../appscedcommerce_wp/wp-blog-header.php');

require_once( $_SERVER['DOCUMENT_ROOT'].'/appscedcommerce_wp/wp-blog-header.php' ); 
