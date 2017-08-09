<?php

/**
 * Class SampleTest
 *
 * @package Barebone
 */

/**
 * Sample test case.
 */
class Easy_Primary_Category_Tests extends WP_UnitTestCase {

	private $easy_primary_category;
	private $easy_primary_category_admin;

	/**
	 * Setting up the test set
	 */
	function setUp() {
		$this->easy_primary_category		 = Easy_Primary_Category::get_instance();
		$this->easy_primary_category_admin	 = Easy_Primary_Category_Admin::get_instance();
	}

	/**
	 * Check if our class is initialized
	 */
	function test_initialization() {
		$this->assertInstanceOf( 'Easy_Primary_Category', $this->easy_primary_category );
		$this->assertInstanceOf( 'Easy_Primary_Category_Admin', $this->easy_primary_category_admin );
	}

}
