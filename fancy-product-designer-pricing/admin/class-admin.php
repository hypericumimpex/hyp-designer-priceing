<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if( !class_exists('FPD_Pricing_Admin') ) {

	class FPD_Pricing_Admin {

		public function __construct() {

			require_once( __DIR__.'/class-settings.php' );
			require_once( __DIR__.'/class-admin-pricing-builder.php' );

		}
	}

}

new FPD_Pricing_Admin();

?>