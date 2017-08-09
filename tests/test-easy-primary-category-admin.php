<?php

/**
 * Class Easy_Primary_Category_Admin_Tests
 *
 * @package Easy_Primary_Category
 */
class Easy_Primary_Category_Admin_Tests extends WP_UnitTestCase {

	private $easy_primary_category_admin;

	/**
	 * Setting up the test set
	 */
	function setUp() {
		parent::setUp();
		
		require_once EPC_PATH . 'admin/class-easy-primary-category-admin.php';
		$this->easy_primary_category_admin	 = Easy_Primary_Category_Admin::get_instance();
	}

	/**
	 * Check if our class is initialized
	 */
	function test_initialization() {
		$this->assertInstanceOf( 'Easy_Primary_Category_Admin', $this->easy_primary_category_admin );
	}

}
