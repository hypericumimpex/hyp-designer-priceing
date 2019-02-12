<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if(!class_exists('FPD_Pricing_Scripts_Styles')) {

	class FPD_Pricing_Scripts_Styles {

		public function __construct() {

			add_action( 'init', array( &$this, 'register'), 10 );
			add_action( 'wp_footer', array(&$this, 'footer_handler') );

		}

		public function register() {

			$js_url = Fancy_Product_Designer_Pricing::LOCAL ? 'http://radykal.dep/fpd/dist/addons/pricing_rules/FPDPricingRules.js' : plugins_url( '/js/FPDPricingRules.min.js', dirname(__FILE__) );

			wp_register_style( 'fpd-pricing-builder', plugins_url('/admin/css/pricing-rules-builder.css', dirname(__FILE__)), array(
				'radykal-admin',
				'fpd-admin',
				'fpd-admin-icon-font'
			), Fancy_Product_Designer_Pricing::VERSION );

			wp_register_script( 'fpd-pricing-builder', plugins_url('/admin/js/pricing-rules-builder.js', dirname(__FILE__)), array(
				'jquery-ui-sortable',
				'radykal-admin',
				'fpd-admin'
			), Fancy_Product_Designer_Pricing::VERSION );

			wp_register_script( 'fpd-pricing', $js_url, false, Fancy_Product_Designer_Pricing::VERSION );

		}

		public function footer_handler() {

			if(FPD_Scripts_Styles::$add_script)
				wp_enqueue_script( 'fpd-pricing' );

		}

	}

}

new FPD_Pricing_Scripts_Styles();

?>