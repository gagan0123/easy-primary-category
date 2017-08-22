<?php

/*
  Plugin Name: Easy Primary Category
  Plugin URI:  https://gagan0123.com/
  Description: Allows you to choose primary category for posts and custom post types
  Version:     0.1
  Author:      Gagan Deep Singh
  Author URI:  https://gagan0123.com
  License:     GPLv2
  License URI: https://www.gnu.org/licenses/gpl-2.0.html
  Text Domain: easy-primary-category
  Domain Path: /languages
 */

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
	die;
}

if ( !defined( 'EPC_PATH' ) ) {
	define( 'EPC_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
}

if ( !defined( 'EPC_URL' ) ) {
	define( 'EPC_URL', trailingslashit( plugins_url( '', __FILE__ ) ) );
}

if ( !defined( 'EPC_VERSION' ) ) {
	define( 'EPC_VERSION', '0.1' );
}

/**
 * The core plugin class
 */
require_once EPC_PATH . 'includes/class-easy-primary-category.php';

/**
 * Load the admin class if its the admin dashboard
 */
if ( is_admin() ) {
	require_once EPC_PATH . 'admin/class-easy-primary-category-admin.php';
}