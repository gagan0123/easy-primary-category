<?php
/**
 * Frontend based functions for other plugins or themes to use.
 *
 * @package Easy_Primary_Category
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( 'Easy_Primary_Frontend' ) ) {

	/**
	 * Easy Primary Frontend Class
	 *
	 * Contains methods needed to frontend queries
	 *
	 * @since 0.1
	 */
	class Easy_Primary_Frontend {

		/**
		 * Stores the instance of the class
		 *
		 * @since 0.1
		 *
		 * @var Easy_Primary_Frontend
		 */
		protected static $instance = null;

		/**
		 * Returns the current instance of the class
		 *
		 * @since 0.1
		 *
		 * @return Easy_Primary_Frontend Returns the current instance of the class
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Returns array of WP_Post which have the given $term_id set as Primary
		 *
		 * @since 0.1
		 *
		 * @param int|string $term The term to check. Accepts term ID, slug, or name.
		 * @param array      $args Arguments to be passed along to the WP_Query,
		 *                         except for meta_query parameter.
		 *
		 * @return array|false|WP_Error Returns array of WP_Posts if posts are found,
		 *                              false if no posts are found, WP_Error if $term
		 *                              isn't a term ID or a WP_Term object.
		 */
		public function get_primary_term_posts( $term, $args = array() ) {

			$term_id = $this->term_exists( $term );

			if ( null === $term_id || 0 === $term_id ) {
				return new WP_Error( 'noterm', __( 'Term does not exist', 'easy-primary-category' ) );
			}

			// Lets set up the WP_Query arguments.
			$defaults = array(
				'post_status'    => 'publish',
				'posts_per_page' => 10,
				'post_type'      => 'post',
			);

			$meta_query = array(
				'key'    => 'epc_primary_category',
				'value'  => $term_id,
			);

			$args               = wp_parse_args( $args, $defaults );
			$args['meta_query'] = array( $meta_query );

			$query = new WP_Query( $args );
			if ( $query->post_count > 0 ) {
				return $query->posts;
			} else {
				return false;
			}
		}

		/**
		 * For caching the term exists status if queried with same term multiple
		 * number of times
		 *
		 * @since 0.1
		 *
		 * @param int|string $term     The term to check. Accepts term ID, slug, or name.
		 * @param string     $taxonomy The taxonomy name to use.
		 *
		 * @return mixed Returns null if the term does not exist. Returns the term ID
		 *               if no taxonomy is specified and the term ID exists. Returns
		 *               an array of the term ID and the term taxonomy ID the taxonomy
		 *               is specified and the pairing exists.
		 */
		private function term_exists( $term, $taxonomy = '' ) {

			$cache_key   = 'primary_term_exists_' . $term . '_' . $taxonomy;
			$return      = wp_cache_get( $cache_key, 'epc' );

			if ( false === $return ) {
				$return = term_exists( $term, $taxonomy );
				wp_cache_set( $cache_key, $return, 'epc' );
			}

			return $return;
		}

	}

}



