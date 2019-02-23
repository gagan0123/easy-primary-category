<?php
/**
 * Class to handle the admin side interactions of the plugin.
 *
 * @package Easy_Primary_Category
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
if ( ! class_exists( 'Easy_Primary_Category_Admin' ) ) {

	/**
	 * Handles the admin side interactions of the plugin.
	 *
	 * @since 0.1
	 */
	class Easy_Primary_Category_Admin {

		/**
		 * The instance of the class Easy_Primary_Category_Admin.
		 *
		 * @since 0.1
		 * @access protected
		 * @static
		 *
		 * @var Easy_Primary_Category_Admin
		 */
		protected static $instance = null;

		/**
		 * Calls the function to register the hooks.
		 *
		 * @since 0.1
		 * @access public
		 */
		public function __construct() {
			$this->register_hooks();
		}

		/**
		 * Returns the current instance of the class.
		 *
		 * @since 0.1
		 * @access public
		 *
		 * @return Easy_Primary_Category_Admin Returns the current instance of the class.
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Registers the actions and filters for the Admin UI.
		 *
		 * @since 0.1
		 * @access public
		 *
		 * @return void
		 */
		public function register_hooks() {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'save_post', array( $this, 'save_primary_terms' ) );
			add_action( 'admin_footer', array( $this, 'admin_footer' ), 10 );
		}

		/**
		 * Enqueues all the assets needed for the primary term interface.
		 *
		 * @since 0.1
		 * @access public
		 *
		 * @return void
		 */
		public function enqueue_scripts() {

			// Return if the its not post edit or add screen.
			if ( ! $this->is_post_edit() ) {
				return;
			}

			// Enqueue only if there are taxonomies that need a primary term.
			$taxonomies = $this->get_primary_term_taxonomies();
			if ( empty( $taxonomies ) ) {
				return;
			}

			// Registering our admin styles and scripts.
			wp_register_style( 'epc-taxonomy-metabox', EPC_URL . 'admin/css/epc-taxonomy-metabox.min.css', array(), EPC_ASSETS_VERSION );
			wp_register_script( 'epc-taxonomy-metabox', EPC_URL . 'admin/js/epc-taxonomy-metabox.min.js', array( 'jquery' ), EPC_ASSETS_VERSION, true );

			// Enqueueing our admin styles and scripts.
			wp_enqueue_style( 'epc-taxonomy-metabox' );
			wp_enqueue_script( 'epc-taxonomy-metabox' );

			// Formatting taxonomies for JS.
			$taxonomies = array_map( array( $this, 'map_taxonomies_for_js' ), $taxonomies );
			$data       = array(
				'taxonomies' => $taxonomies,
			);
			wp_localize_script( 'epc-taxonomy-metabox', 'easyPrimaryCategory', $data );
		}

		/**
		 * Add primary term JS templates in footer.
		 *
		 * @since 0.1
		 * @access public
		 *
		 * @return void
		 */
		public function admin_footer() {

			// Return if the its not post edit or add screen.
			if ( ! $this->is_post_edit() ) {
				return;
			}

			$taxonomies = $this->get_primary_term_taxonomies();

			// Enqueue only if there are taxonomies that need a primary term.
			if ( ! empty( $taxonomies ) ) {
				$this->include_js_templates();
			}
		}

		/**
		 * Include Underscore.js style template for buttons and input fields
		 * on post edit and post add screens.
		 *
		 * @since 0.1
		 * @access public
		 *
		 * @return void
		 */
		protected function include_js_templates() {
			include_once EPC_PATH . 'admin/templates/js-templates-primary-term.php';
		}

		/**
		 * Save the primary term for a specific taxonomy.
		 *
		 * @since 0.1
		 * @access public
		 *
		 * @param int     $post_id  Post ID to save primary term for.
		 * @param WP_Term $taxonomy Taxonomy to save primary term for.
		 *
		 * @return void
		 */
		protected function save_primary_term( $post_id, $taxonomy ) {
			$primary_term = filter_input( INPUT_POST, 'epc_primary_' . $taxonomy->name . '_term', FILTER_SANITIZE_NUMBER_INT );

			// We accept an empty string here because we need to save that if no terms are selected.
			if ( null !== $primary_term && check_admin_referer( 'save-primary-term', 'epc_primary_' . $taxonomy->name . '_nonce' ) ) {
				$primary_term_object = new Easy_Primary_Term( $taxonomy->name, $post_id );
				$primary_term_object->set_primary_term( $primary_term );
			}
		}

		/**
		 * Saves all selected primary terms.
		 *
		 * @since 0.1
		 * @access public
		 *
		 * @param int $post_id Post ID to save primary terms for.
		 *
		 * @return void
		 */
		public function save_primary_terms( $post_id ) {
			// Bail if this is a multisite installation and the site has been switched.
			if ( is_multisite() && ms_is_switched() ) {
				return;
			}

			$taxonomies = $this->get_primary_term_taxonomies( $post_id );

			foreach ( $taxonomies as $taxonomy ) {
				$this->save_primary_term( $post_id, $taxonomy );
			}
		}

		/**
		 * /**
		 * Get the id of the primary term.
		 *
		 * @since 0.1
		 * @access protected
		 *
		 * @param string $taxonomy_name Taxonomy name for the term.
		 *
		 * @return int|bool Primary Term ID or false if no Primary Term.
		 */
		protected function get_primary_term( $taxonomy_name ) {
			$primary_term = new Easy_Primary_Term( $taxonomy_name, $this->get_current_id() );

			return $primary_term->get_primary_term();
		}

		/**
		 * Returns all the taxonomies for which the primary term selection is enabled.
		 *
		 * @since 0.1
		 *
		 * @param int $post_id Default current post ID.
		 *
		 * @return WP_Taxonomy[] Returns an array of taxonomies for which the primary
		 *                       term selection is enabled.
		 */
		protected function get_primary_term_taxonomies( $post_id = null ) {

			if ( null === $post_id ) {
				$post_id = $this->get_current_id();
			}

			/**
			 * Array of taxonomies.
			 *
			 * @var $taxonomies WP_Taxonomy[]
			 */
			$taxonomies = wp_cache_get( 'primary_term_taxonomies_' . $post_id, 'epc' );

			if ( false !== $taxonomies ) {
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
		 * @return WP_Taxonomy[] Returns an array of taxonomies for which the primary
		 *                       term selection is enabled.
		 */
		protected function generate_primary_term_taxonomies( $post_id ) {
			$post_type      = get_post_type( $post_id );
			$all_taxonomies = get_object_taxonomies( $post_type, 'objects' );
			$all_taxonomies = array_filter( $all_taxonomies, array( $this, 'filter_hierarchical_taxonomies' ) );

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
		 * Checks if the current screen is post edit or new post screen.
		 *
		 * @since 0.1
		 *
		 * @return bool True if its post edit or new post screen,
		 *              False if anything else.
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

		/**
		 * Returns an array suitable for use in the javascript
		 *
		 * @param stdClass $taxonomy The taxonomy to map.
		 *
		 * @return array
		 */
		private function map_taxonomies_for_js( $taxonomy ) {
			$primary_term = $this->get_primary_term( $taxonomy->name );

			if ( empty( $primary_term ) ) {
				$primary_term = '';
			}

			return array(
				'title'   => $taxonomy->labels->singular_name,
				'name'    => $taxonomy->name,
				'primary' => $primary_term,
				'terms'   => array_map( array( $this, 'map_terms_for_js' ), get_terms( $taxonomy->name ) ),
			);
		}

		/**
		 * Returns an array suitable for use in the javascript
		 *
		 * @since 0.1
		 *
		 * @param stdClass $term The term to map.
		 *
		 * @return array
		 */
		private function map_terms_for_js( $term ) {
			return array(
				'id'   => $term->term_id,
				'name' => $term->name,
			);
		}

	}

	Easy_Primary_Category_Admin::get_instance();
}
