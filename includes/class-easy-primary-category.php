<?php
/**
 * Main class of the plugin interacting with the WordPress.
 *
 * @package Easy_Primary_Category
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( 'Easy_Primary_Category' ) ) {

	/**
	 * Main class of the plugin interacting with the WordPress.
	 *
	 * @since 0.1
	 */
	class Easy_Primary_Category {

		/**
		 * The instance of the class Easy_Primary_Category.
		 *
		 * @since 0.1
		 * @access protected
		 * @static
		 *
		 * @var Easy_Primary_Category
		 */
		protected static $instance = null;

		/**
		 * Calls the register_hooks function and require_files function.
		 *
		 * @since 0.1
		 * @access public
		 *
		 * @return void
		 */
		public function __construct() {
			$this->register_hooks();
			$this->require_files();
		}

		/**
		 * Returns the current instance of the class.
		 *
		 * @since 0.1
		 * @access public
		 * @static
		 *
		 * @return Easy_Primary_Category Returns the current instance of the class.
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Requires the necessary files for the plugin.
		 *
		 * @since 0.1
		 * @access public
		 *
		 * @return void
		 */
		public function require_files() {
			require_once EPC_PATH . 'includes/class-easy-primary-term.php';
			require_once EPC_PATH . 'includes/class-easy-primary-frontend.php';
			require_once EPC_PATH . 'includes/frontend-wrappers.php';

			// Lets be nice with WP_CLI.
			if ( defined( 'WP_CLI' ) && WP_CLI ) {
				require_once EPC_PATH . 'includes/class-easy-primary-cli.php';
				WP_CLI::add_command( 'primary-category', 'Easy_Primary_CLI' );
			}
		}

		/**
		 * Registers the actions and filters for the plugin.
		 *
		 * @since 0.1
		 * @access public
		 *
		 * @return void
		 */
		public function register_hooks() {
			add_filter( 'post_link_category', array( $this, 'post_link_category' ), 10, 3 );
		}

		/**
		 * Filters post_link_category to change the category to the chosen category by the user.
		 *
		 * @since 0.1
		 * @access public
		 *
		 * @param stdClass $category The category that is now used for the post link.
		 * @param array    $categories This parameter is not used.
		 * @param WP_Post  $post The post in question.
		 *
		 * @return object|array|WP_Error|null The category we want to use for the post link.
		 */
		public function post_link_category( $category, $categories = null, $post = null ) {
			$post                = get_post( $post );
			$primary_category    = $this->get_primary_category( $post );

			if ( false !== $primary_category && $primary_category !== $category->cat_ID ) {
				$category = get_category( $primary_category );
			}

			return $category;
		}

		/**
		 * Get the id of the primary category.
		 *
		 * @since 0.1
		 * @access protected
		 *
		 * @param WP_Post $post The post in question.
		 *
		 * @return int primary category id
		 */
		protected function get_primary_category( $post = null ) {
			$post = get_post( $post );

			if ( null === $post ) {
				return false;
			}

			$primary_term = new Easy_Primary_Term( 'category', $post->ID );

			return $primary_term->get_primary_term();
		}

	}

	Easy_Primary_Category::get_instance();
}
