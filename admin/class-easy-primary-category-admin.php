<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
	die;
}
if ( !class_exists( 'Easy_Primary_Category_Admin' ) ) {

	class Easy_Primary_Category_Admin {

		protected static $instance = null;

		function __construct() {
			
		}

		/**
		 * @return Easy_Primary_Category_Admin Returns the current instance of the class
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

	}

}