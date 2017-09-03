<?php
/**
 * Provides wrapper functions for frontend queries.
 *
 * @package Easy_Primary_Category
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Returns array of WP_Post which have the given $term_id set as Primary.
 *
 * @since 0.1
 *
 * @param int|WP_Term $term WP_Term object or term_id for which posts need to be queried.
 * @param array       $args Arguments to be passed along to the WP_Query, except for meta_query parameter.
 *
 * @return array|false|WP_Error Returns array of WP_Posts if posts are found, false if no posts are found
 *                              WP_Error if term isn't a term ID or a WP_Term object.
 */
function epc_get_primary_term_posts( $term, $args = array() ) {
	$epc_frontend = Easy_Primary_Frontend::get_instance();
	return $epc_frontend->get_primary_term_posts( $term, $args );
}
