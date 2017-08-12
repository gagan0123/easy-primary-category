<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
	die;
}
if ( !class_exists( 'Easy_Primary_Category_Admin' ) ) {

	class Easy_Primary_Category_Admin {

		/**
		 * The instance of the class Easy_Primary_Category_Admin
		 * 
		 * @since 0.1
		 * 
		 * @var Easy_Primary_Category_Admin
		 */
		protected static $instance = null;

		function __construct() {
			$this->register_hooks();
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
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'save_post', array( $this, 'save_primary_terms' ) );
			add_action( 'admin_footer', array( $this, 'admin_footer' ), 10 );
		}

		/**
		 * Enqueues all the assets needed for the primary term interface
		 * 
		 * @since 0.1
		 *
		 * @return void
		 */
		public function enqueue_scripts() {
			
			//Return if the its not post edit or add screen
			if ( !$this->is_post_edit() ) {
				return;
			}

			// Enqueue only if there are taxonomies that need a primary term.
			$taxonomies = $this->get_primary_term_taxonomies();
			if ( empty( $taxonomies ) ) {
				return;
			}

			//Registering our admin styles and scripts
			wp_register_style( 'epc-taxonomy-metabox', EPC_URL . 'admin/css/epc-taxonomy-metabox.min.css', array(), EPC_VERSION );
			wp_register_script( 'epc-taxonomy-metabox', EPC_URL . 'admin/js/epc-taxonomy-metabox.min.js', array( 'jquery' ), EPC_VERSION, true );

			//Enqueueing our admin styles and scripts
			wp_enqueue_style( 'epc-taxonomy-metabox' );
			wp_enqueue_script( 'epc-taxonomy-metabox' );
		}

		/**
		 * Add primary term JS templates in footer 
		 * 
		 * @since 0.1
		 * 
		 * @return void
		 */
		public function admin_footer() {

			//Return if the its not post edit or add screen
			if ( !$this->is_post_edit() ) {
				return;
			}

			$taxonomies = $this->get_primary_term_taxonomies();

			// Enqueue only if there are taxonomies that need a primary term.
			if ( !empty( $taxonomies ) ) {
				$this->include_js_templates();
			}
		}

		/**
		 * Include Underscore.js style template for buttons and input fields
		 * on post edit and post add screens.
		 * 
		 * @since 0.1
		 * 
		 * @return void
		 */
		protected function include_js_templates() {
			include_once EPC_PATH . 'admin/templates/js-templates-primary-term.php';
		}

		/**
		 * Save the primary term for a specific taxonomy
		 * 
		 * @since 0.1
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
		 * 
		 * @return void
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
			$primary_term = new Easy_Primary_Term( $taxonomy_name, $this->get_current_id() );

			return $primary_term->get_primary_term();
		}

		/**
		 * Returns all the taxonomies for which the primary term selection is enabled
		 * 
		 * @since 0.1
		 *
		 * @param int $post_id Default current post ID.
		 * 
		 * @return array
		 */
		protected function get_primary_term_taxonomies( $post_id = null ) {

			if ( null === $post_id ) {
				$post_id = $this->get_current_id();
			}

			if ( false !== ( $taxonomies = wp_cache_get( 'primary_term_taxonomies_' . $post_id, 'epc' ) ) ) {
				return $taxonomies;
			}

			$taxonomies = $this->generate_primary_term_taxonomies( $post_id );

			wp_cache_set( 'primary_term_taxonomies_' . $post_id, $taxonomies, 'epc' );

			return $taxonomies;
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
			$post_type		 = get_post_type( $post_id );
			$all_taxonomies	 = get_object_taxonomies( $post_type, 'objects' );
			$all_taxonomies	 = array_filter( $all_taxonomies, array( $this, 'filter_hierarchical_taxonomies' ) );

			/**
			 * Filters which taxonomies for which the user can choose the primary term.
			 *
			 * @api array    $taxonomies An array of taxonomy objects that are primary_term enabled.
			 *
			 * @param string $post_type      The post type for which to filter the taxonomies.
			 * @param array  $all_taxonomies All taxonomies for this post types, even ones that don't have primary term
			 *                               enabled.
			 */
			$taxonomies = (array) apply_filters( 'easy_primary_term_taxonomies', $all_taxonomies, $post_type, $all_taxonomies );

			return $taxonomies;
		}

		/**
		 * Checks if the current screen is post edit or new post screen
		 * 
		 * @since 0.1
		 * 
		 * @return bool True if its post edit or new post screen,
		 * 				False if anything else
		 */
		public function is_post_edit() {
			global $pagenow;
			return 'post.php' === $pagenow || 'post-new.php' === $pagenow;
		}

		/**
		 * Get the current post ID.
		 * 
		 * @since 0.1
		 *
		 * @return integer The post ID.
		 */
		protected function get_current_id() {
			return filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );
		}

		/**
		 * Returns whether or not a taxonomy is hierarchical
		 * 
		 * @since 0.1
		 *
		 * @param stdClass $taxonomy Taxonomy object.
		 *
		 * @return bool Whether or not a taxonomy is hierarchical
		 */
		private function filter_hierarchical_taxonomies( $taxonomy ) {
			return (bool) $taxonomy->hierarchical;
		}

	}

	Easy_Primary_Category_Admin::get_instance();
}