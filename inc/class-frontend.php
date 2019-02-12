<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if(!class_exists('FPD_Pricing_Frontend')) {

	class FPD_Pricing_Frontend {

		public function __construct() {

			add_action( 'fpd_before_js_fpd_init', array( &$this, 'before_js_fpd_init' ) );

		}

		public function before_js_fpd_init( $product_settings ) {

			$pricing_rules = $product_settings->get_option('pricing_rules[]') ? $product_settings->get_option('pricing_rules[]') : get_option('fpd_pricing_rules');

			if($pricing_rules) {

				$pricing_rules = is_string($pricing_rules) ? explode(' ', $pricing_rules) : $pricing_rules;

				$pr_groups = get_option( 'fpd_pr_groups', array() );
				if( !is_array($pr_groups) )
					$pr_groups = json_decode(fpd_strip_multi_slahes($pr_groups), true);


				$used_pr_groups = [];
				foreach($pr_groups as $pr_group) {
					if( in_array(sanitize_key($pr_group['name']), $pricing_rules)) {
						array_push($used_pr_groups, $pr_group['data']);
					}
				}

				//output pricing rules options
				?>pluginOptions.pricingRules = <?php echo json_encode($used_pr_groups, JSON_PRETTY_PRINT); ?>;<?php

			}

		}

	}

}

new FPD_Pricing_Frontend();

?>