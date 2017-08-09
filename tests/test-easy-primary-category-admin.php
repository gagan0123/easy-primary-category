<?php

/**
 * Class Easy_Primary_Category_Admin_Tests
 *
 * @package Easy_Primary_Category
 */
class Easy_Primary_Category_Admin_Tests extends WP_UnitTestCase {

	private $easy_primary_category_admin;
	private $user_id;

	/**
	 * Setting up the test set
	 */
	function setUp() {
		parent::setUp();

		$this->user_id						 = $this->factory->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $this->user_id );
		set_current_screen( 'edit-post' );
		$this->easy_primary_category_admin	 = Easy_Primary_Category_Admin::get_instance();
	}

	/**
	 * Check if our class is initialized
	 */
	function test_initialization() {
		$this->assertInstanceOf( 'Easy_Primary_Category_Admin', $this->easy_primary_category_admin );
	}

}
