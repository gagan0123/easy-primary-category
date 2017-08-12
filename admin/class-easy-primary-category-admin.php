<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
	die;
}
if ( !class_exists( 'Easy_Primary_Category_Admin' ) ) {

	class Easy_Primary_Category_Admin {

		/**
		 * @var Easy_Primary_Category_Admin The instance of the class Easy_Primary_Category_Admin
		 */
		protected static $instance = null;

		function __construct() {
			
		}

		/**
		 * Returns the current instance of the class
		 * 
		 * @since 0.1
		 * 
		 * @return Easy_Primary_Category_Admin Returns the current instance of the class
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		 * Registers the actions and filters for the Admin UI
		 * 
		 * @since 0.1
		 * 
		 * @return void
		 */
		public function register_hooks() {
			
		}

		/**
		 * Enqueues all the assets needed for the primary term interface
		 *
		 * @return void
		 */
		public function enqueue_scripts() {
			
		}

		/**
		 * Save the primary term for a specific taxonomy
		 *
		 * @param int     $post_id  Post ID to save primary term for.
		 * @param WP_Term $taxonomy Taxonomy to save primary term for.
		 * 
		 * @return void
		 */
		protected function save_primary_term( $post_id, $taxonomy ) {
			
		}

		/**
		 * Saves all selected primary terms
		 * 
		 * @since 0.1
		 *
		 * @param int $post_id Post ID to save primary terms for.
		 */
		public function save_primary_terms( $post_id ) {
			
		}

		/**
		 * /**
		 * Get the id of the primary term
		 * 
		 * @since 0.1
		 *
		 * @param string $taxonomy_name Taxonomy name for the term.
		 *
		 * @return int primary term id
		 */
		protected function get_primary_term( $taxonomy_name ) {
			
		}

		/**
		 * Returns all the taxonomies for which the primary term selection is enabled
		 * 
		 * @since 0.1
		 *
		 * @param int $post_id Default current post ID.
		 * @return array
		 */
		protected function get_primary_term_taxonomies( $post_id = null ) {
			
		}

		/**
		 * Generate the primary term taxonomies.
		 * 
		 * @since 0.1
		 *
		 * @param int $post_id ID of the post.
		 *
		 * @return array
		 */
		protected function generate_primary_term_taxonomies( $post_id ) {
			
		}

	}

}