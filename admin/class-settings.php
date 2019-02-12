<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if( !class_exists('FPD_Pricing_Settings') ) {

	class FPD_Pricing_Settings {

		public function __construct() {

			//Global Settings
			add_filter( 'fpd_general_settings', array( &$this,'register_settings' ) );
			add_filter( 'fpd_settings_blocks', array( &$this,'register_settings_block' ) );
			add_action( 'fpd_block_options_end', array(&$this, 'add_block_options') );

			//IPS
			add_action( 'fpd_ips_general_tbody_end', array( &$this,'ips_general_pricing_settings' ) );

		}

		//add options to general settings
		public function register_settings( $options ) {

			$options['pricing-rules'] = array(

				array(
					'title' => __( 'Pricing Groups', 'radykal' ),
					'description' => __('Select pricing groups that will be used for all product designers.', 'radykal'),
					'id' 		=> 'fpd_pricing_rules',
					'css' 		=> 'width: 100%;',
					'default'	=> '',
					'type' 		=> 'multiselect',
					'class'		=> 'radykal-select2',
					'options'   => self::get_pricing_group_names()
				)
			);

			return $options;

		}

		//register settings block
		public function register_settings_block( $settings ) {

			$settings['general']['pricing-rules'] = __('Pricing Rules', 'radykal');

			return $settings;

		}

		//display settings block
		public function add_block_options() {

			$options = FPD_Settings_General::get_options();
			FPD_Settings::$radykal_settings->add_block_options( 'pricing-rules', $options['pricing-rules']);

		}

		public static function get_pricing_group_names() {

			$names = array();
			$pr_groups = get_option( 'fpd_pr_groups', array() );

			if( !is_array($pr_groups) )
				$pr_groups = json_decode(fpd_strip_multi_slahes($pr_groups), true);

			foreach($pr_groups as $pr_group) {
				$names[sanitize_key($pr_group['name'])] = $pr_group['name'];
			}

			return $names;

		}

		public function ips_general_pricing_settings() {

			?>
			<tr valign="top">
				<th scope="row"><label><?php _e('Pricing Rules', 'radykal'); ?></label></th>
				<td>
					<select class="radykal-select2" name="pricing_rules[]" multiple data-placeholder="<?php _e('Global Pricing Rules', 'radykal'); ?>" style="width: 100%;">
						<?php
							//get all created categories
							$pr_groups = self::get_pricing_group_names();
							foreach($pr_groups as $key => $pr_group) {
								echo '<option value="'.$key.'">'.$pr_group.'</option>';
							}
						?>
					</select>
				</td>
			</tr>
			<?php
		}

	}
}

new FPD_Pricing_Settings();

?>