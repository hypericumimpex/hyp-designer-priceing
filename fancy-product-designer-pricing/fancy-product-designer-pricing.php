<?php
/*
Plugin Name: Fancy Product Designer Pricing
Plugin URI: https://fancyproductdesigner.com/
Description: An Add-On for Fancy Product Designer that helps to create pricing rules.
Version: 1.0.6
Author: fancyproductdesigner.com
Author URI: https://fancyproductdesigner.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if( !class_exists('Fancy_Product_Designer_Pricing') ) {

	class Fancy_Product_Designer_Pricing {

		const VERSION = '1.0.6';
		const LOCAL = false;

		public function __construct() {

			add_action( 'plugins_loaded', array( &$this,'plugins_loaded' ) );

		}

		public function plugins_loaded() {

			if(class_exists('Fancy_Product_Designer') && version_compare(Fancy_Product_Designer::VERSION, '3.5.0', '>=')) { //FPD is enabled

				add_action( 'init', array( &$this, 'init') );

				require_once(__DIR__.'/inc/class-scripts-styles.php');
				require_once(__DIR__.'/inc/class-frontend.php');
				require_once(__DIR__.'/admin/class-admin.php');

			}
			else {
				add_action( 'admin_notices',  array( &$this, 'display_admin_notices' ) );
			}

		}

		public function display_admin_notices() {

			?>
			<div class="error">
		        <p><?php _e( 'Fancy Product Designer Pricing requires <a href="https://codecanyon.net/item/fancy-product-designer-woocommerce-plugin/6318393?ref=radykal" target="_blank">Fancy Product Designer Plugin for WordPress/WooCommerce V3.5.0</a>. Please update to this version, otherwise you can not use the Pricing Add-On.', 'radykal' ); ?></p>
		    </div>
		    <?php

		}

		public function init() {


		}

	}
}

new Fancy_Product_Designer_Pricing();

?>