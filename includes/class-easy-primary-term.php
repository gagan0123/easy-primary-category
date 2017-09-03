<?php
/**
 * Class to manipulate post's primary term.
 *
 * @package Easy_Primary_Category
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Manipulate a post's primary term
 */
class Easy_Primary_Term {

	/**
	 * The taxonomy to which this term belongs.
	 *
	 * @since 0.1
	 *
	 * @var string
	 */
	protected $taxonomy_name;

	/**
	 * The post ID to which this term belongs.
	 *
	 * @since 0.1
	 *
	 * @var int
	 */
	protected $post_id;

	/**
	 * Setting up the taxonomy and post ID for the term.
	 *
	 * @since 0.1
	 *
	 * @param string $taxonomy_name The taxonomy to which this term belongs.
	 * @param int    $post_id       The post ID to which this term belongs.
	 */
	public function __construct( $taxonomy_name, $post_id ) {
		$this->taxonomy_name = $taxonomy_name;
		$this->post_id       = $post_id;
	}

	/**
	 * Returns the primary term ID.
	 *
	 * @since 0.1
	 *
	 * @return int|bool Primary Term ID or false if no Primary Term.
	 */
	public function get_primary_term() {
		$primary_term = (int) get_post_meta( $this->post_id, 'epc_primary_' . $this->taxonomy_name, true );

		$terms = $this->get_terms();

		if ( ! in_array( $primary_term, wp_list_pluck( $terms, 'term_id' ), true ) ) {
			$primary_term = false;
		}

		return ( $primary_term ) ? ( $primary_term ) : false;
	}

	/**
	 * Sets the new primary term ID.
	 *
	 * @since 0.1
	 *
	 * @param int $new_primary_term New primary term ID.
	 *
	 * @return void
	 */
	public function set_primary_term( $new_primary_term ) {
		update_post_meta( $this->post_id, 'epc_primary_' . $this->taxonomy_name, $new_primary_term );
	}

	/**
	 * Get the terms for the current post ID.
	 * When $terms is not an array, set $terms to an array.
	 *
	 * @since 0.1
	 *
	 * @return array Return the terms for the current post ID.
	 */
	public function get_terms() {
		$terms = get_the_terms( $this->post_id, $this->taxonomy_name );

		if ( ! is_array( $terms ) ) {
			$terms = array();
		}

		return $terms;
	}

}
