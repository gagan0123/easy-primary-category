<?php
/**
 * Some subcommands for WP CLI.
 *
 * @package Easy_Primary_Category
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( 'Easy_Primary_CLI' ) ) {

	/**
	 * Easy Primary CLI Class
	 *
	 * Contains methods extending wp-cli interface, linked with
	 * frontend functions
	 *
	 * @since 0.1
	 */
	class Easy_Primary_CLI extends WP_CLI_Command {

		/**
		 * Displays posts belonging to a particular term ID set as primary term
		 *
		 * ## Example
		 * # Displaying posts with term ID 3 as primary term
		 * $ wp primary-category get-primary-posts 3
		 *
		 * @since 0.1
		 *
		 * @subcommand get-primary-posts
		 *
		 * @param array $args Arguments array, containing the Term ID to be fetched.
		 */
		function get_primary_posts( $args ) {
			if ( isset( $args[0] ) && is_numeric( $args[0] ) ) {
				$term_id = intval( $args[0] );
			} else {
				WP_CLI::error( __( 'Term ID not provided', 'easy-primary-category' ) );
			}

			$posts = epc_get_primary_term_posts( $term_id );
			if ( ! is_wp_error( $posts ) ) {
				// Convert to array to be passed to format_items method.
				$posts = (array) $posts;
				WP_CLI\Utils\format_items( 'table', $posts, array( 'ID', 'post_title', 'post_name', 'post_date', 'post_status' ) );
			} else {
				WP_CLI::error( $posts->get_error_message() );
			}
		}

	}

}
