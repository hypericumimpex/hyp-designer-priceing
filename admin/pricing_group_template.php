<?php

	$properties = array(
		'textLength' => __('Text Length', 'radykal'),
		'linesLength' => __('Lines Length', 'radykal'),
		'imageSize' => __('Image Size (Origin Width & Height)', 'radykal'),
		'imageSizeScaled' => __('Image Size Scaled', 'radykal'),
		'elementsLength' => __('Amount of elements', 'radykal'),
		'colorsLength' => __('Amount of used colors', 'radykal')
	)

?>

<div id="fpd-pr-group-template" class="fpd-pr-group fpd-panel fpd-hidden">

	<div class="fpd-panel-header fpd-clearfix">
		<span class="fpd-pr-group-title"></span>
		<div class="fpd-pr-group-actions">
			<span class="fpd-pr-group-collapse">
				<span class="fpd-pr-builder-icon-arrow-left"></span>
				<span class="fpd-pr-builder-icon-arrow-down"></span>
			</span>
			<span class="fpd-pr-group-remove">
				<span class="fpd-pr-builder-icon-close"></span>
			</span>
		</div>
	</div>

	<div class="fpd-panel-body fpd-pr-group-body">

		<div class="fpd-pr-property">
			<label>
				<span><?php _e('Property', 'radykal'); ?>
				<i class="fpd-admin-icon-info-outline fpd-pr-tooltip" title="<?php _e('Select a property that will be used for pricing.', 'radykal'); ?>"></i>
				</span>

			</label>
			<select name="property">
				<?php
				foreach($properties as $key => $value) {
					echo "<option value='$key'>$value</option>";
				}
				?>
			</select>
		</div>

		<div class="fpd-pr-targets">

			<label>
				<span><?php _e('Target', 'radykal'); ?>
				<i class="fpd-admin-icon-info-outline fpd-pr-tooltip" title="<?php _e('The view(s) and the elements(s) in the view(s) you want to use for the pricing rules.', 'radykal'); ?>"></i>
				</span>
			</label>
			<div>
				<?php _e('View(s)', 'radykal'); ?><input type="number" placeholder="<?php _e('All', 'radykal'); ?>" name="views" step="1" min="0" class="fpd-pr-tooltip" title="<?php _e('Set a numeric index to target specific views.0=first view, 1=second view...', 'radykal'); ?>" />
			</div>
			<div>
				<?php _e('Element(s)', 'radykal'); ?>
				<select name="elements"  class="fpd-pr-tooltip" title="<?php _e('The element(s) in the view(s) to target.', 'radykal'); ?>">
					<option value="all"><?php _e('All', 'radykal'); ?></option>
					<option value="image"><?php _e('All Images', 'radykal'); ?></option>
					<option value="text"><?php _e('All texts', 'radykal'); ?></option>
					<option value="customImage"><?php _e('All custom images', 'radykal'); ?></option>
					<option value="customText"><?php _e('All custom texts', 'radykal'); ?></option>
					<option value="single"><?php _e('Single Element', 'radykal'); ?></option>
				</select>
				<input type="text" name="elementTitle" placeholder="<?php _e('Enter title of an element', 'radykal'); ?>" class="fpd-hidden" />
			</div>

		</div>

		<div class="fpd-pr-match">
			<label>
				<span><?php _e('Match', 'radykal'); ?>
				<i class="fpd-admin-icon-info-outline fpd-pr-tooltip" title="<?php _e('Define the match type.', 'radykal'); ?>"></i>
				</span>
			</label>
			<span class="fpd-pr-tooltip" title="<?php _e('The first matching rule will be executed and stops.', 'radykal'); ?>">
				<input type="radio" class="fpd-pr-type" checked="checked" value="any" />
				<?php _e('<strong>ANY</strong> of the rules', 'radykal'); ?>
			</span>
			<span class="fpd-pr-tooltip" title="<?php _e('All matching rules will be executed.', 'radykal'); ?>">
				<input type="radio" class="fpd-pr-type" value="all" />
				<?php _e('<strong>ALL</strong> of the rules', 'radykal'); ?>
			</span>
		</div>

		<div>

			<label>
				<span><?php _e('Rules', 'radykal'); ?>
				<i class="fpd-admin-icon-info-outline fpd-pr-tooltip" title="<?php _e('The order is important when using the ANY match.', 'radykal'); ?>"></i>
				</span>
			</label>
			<div class="fpd-pr-rules">

				<div class="fpd-pr-rule">
					<select name="operator">
						<option value="="><?php _e('Equal', 'radykal'); ?></option>
						<option value=">"><?php _e('Greater than', 'radykal'); ?></option>
						<option value="<"><?php _e('Less than', 'radykal'); ?></option>
						<option value=">="><?php _e('Greater than or equal', 'radykal'); ?></option>
						<option value="<="><?php _e('Less than or equal', 'radykal'); ?></option>
					</select>
					<div class="fpd-pr-rule-value"></div>
					<input type="number" name="price" placeholder="<?php _e('Price', 'radykal'); ?>" />
					<div class="fpd-pr-rule-actions">
						<span class="fpd-pr-rule-drag">
							<span class="fpd-pr-builder-icon-drag-handle"></span>
						</span>
						<span class="fpd-pr-rule-remove">
							<span class="fpd-pr-builder-icon-close"></span>
						</span>
					</div>
				</div>

			</div>

			<span class="fpd-pr-add-rule"><?php _e('Add Rule', 'radykal'); ?></span>

		</div>

	</div>

</div>