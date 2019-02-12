<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if(!class_exists('FPD_Pricing_Admin_Builder')) {

	class FPD_Pricing_Admin_Builder {

		public function __construct() {

			//scripts and styles for admin
			add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_styles_scripts' ), 20 );
			//add sub menu to fpd admin
			add_action( 'admin_menu', array( &$this, 'add_menu_pages' ) );

			//AJAX
			add_action( 'wp_ajax_fpd_pr_saverules', array( &$this, 'save_rules' ) );
			add_action( 'wp_ajax_fpd_pr_loadrules', array( &$this, 'load_rules' ) );

		}

		public function enqueue_styles_scripts( $hook ) {

			global $post;

		    if( $hook == 'fancy-product-designer_page_fpd_pricing_builder' ) {

				wp_enqueue_style( 'fpd-pricing-builder' );
				wp_enqueue_script( 'fpd-pricing-admin', plugins_url('/js/admin-pricing.js', __FILE__), array(
					'fpd-pricing-builder'
				), Fancy_Product_Designer_Pricing::VERSION );

				wp_localize_script( 'fpd-pricing-admin', 'fpd_admin_pr_opts', array(
						'groupTitlePrompt' => __('Enter an unique title for the pricing group!', 'radykal'),
						'groupExists' => __('The title for a group already exists, please choose another!', 'radykal'),
						'ruleInputValuePlaceholder' => __('Numeric Value', 'radykal'),
						'ruleInputHeightPlaceholder' => __('Height', 'radykal'),
						'ruleInputWidthPlaceholder' => __('Width', 'radykal'),
						'rulesRemoveConfirm' => __('All rules will be removed when changing the property. Are you sure?', 'radykal'),
					)
				);

		    }

		}

		public function add_menu_pages() {

			add_submenu_page(
				'fancy_product_designer',
				 __('Pricing Rules', 'radykal'),
				 __('Pricing Rules', 'radykal'),
				 Fancy_Product_Designer::CAPABILITY,
				 'fpd_pricing_builder',
				 array( $this, 'builder_page' )
			);

		}

		public function builder_page() {

			self::require_group_template();

			?>
			<div class="wrap" id="fpd-pricing-builder">
				<h2 class="fpd-clearfix">
					<?php _e('Pricing Rules', 'radykal'); ?>
					<a class="add-new-h2" href="#" id="fpd-add-pricing-group"><?php _e('Add Pricing Group', 'radykal'); ?></a>
					<div class="fpd-header-right">
						<button id="fpd-collapse-toggle" class="button button-secondary"><?php _e('Collapse Toggle', 'radykal'); ?></button>
					</div>
				</h2>
				<div id="fpd-pricing-rules-wrapper">

				</div>
				<button id="fpd-save-pricing-groups" class="button button-primary"><?php _e('Save Pricing Groups', 'radykal'); ?></button>
				<div class="fpd-ui-blocker"></div>
			</div>
			<?php

		}

		//save rules via ajax
		public function save_rules() {

			if ( !isset($_POST['groups']) )
			    die;

			check_ajax_referer( 'fpd_ajax_nonce', '_ajax_nonce' );

			update_option( 'fpd_pr_groups', $_POST['groups'] );

			echo json_encode(array(
				'message' => __('Pricing Groups Saved.', 'radykal'),
			));

			die;

		}

		//load groups via ajax
		public function load_rules() {

			check_ajax_referer( 'fpd_ajax_nonce', '_ajax_nonce' );

			echo json_encode(array(
				'groups' => fpd_strip_multi_slahes(get_option( 'fpd_pr_groups' )),
			));

			die;

		}

		public static function require_group_template() {
			require_once('pricing_group_template.php');
		}

	}

}

new FPD_Pricing_Admin_Builder();

?>