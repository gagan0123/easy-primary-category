<?php

/**
 * Class Easy_Primary_Category_Tests
 *
 * @package Easy_Primary_Category
 */
class Easy_Primary_Category_Tests extends WP_UnitTestCase {

	private $easy_primary_category;

	/**
	 * Setting up the test set
	 */
	function setUp() {
		$this->easy_primary_category = Easy_Primary_Category::get_instance();
	}

	/**
	 * Check if our class is initialized
	 */
	function test_initialization() {
		$this->assertInstanceOf( 'Easy_Primary_Category', $this->easy_primary_category );
	}

}
