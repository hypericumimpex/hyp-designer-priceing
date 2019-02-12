var FPDPricingRulesBuilder = function($container, $template, options) {

	$ = jQuery;

	if(options === undefined) {
		options = {};
		options.ruleInputValuePlaceholder = 'Numeric Value';
		options.ruleInputWidthPlaceholder = 'Height';
		options.ruleInputHeightPlaceholder = 'Width';
		options.rulesRemoveConfirm = 'All rules will be removed when changing the property. Are you sure?';
	}

	$container.addClass('fpd-pr-wrapper fpd-clearfix');
	$template = $template.clone().removeAttr('id').removeClass('fpd-hidden');

	var $ruleTemplate = $template.find('.fpd-pr-rules > div:first').remove();

	this.addGroup = function(title, data) {

		data = data === undefined ? {} : data;

		var $clone = $template.clone();
		$clone.find('.fpd-pr-group-title').text(title);

		_updateForm($clone.find('.fpd-pr-group-body:first'));
		$clone.find('.fpd-pr-type').attr('name', title+'_type')
		.first().prop('checked', true);

		_fillGroup($clone, data);

		$clone.find('.fpd-pr-rules').sortable({
			axis: 'y',
			handle: '.fpd-pr-rule-drag',
			placeholder: 'ui-sortable-placeholder'
		});

		$clone.find('.fpd-pr-tooltip').tooltipster({
			offsetY: 0,
			theme: '.fpd-admin-tooltip-theme'
		});

		$container.append($clone);

	};

	this.getAll = function() {

		var groups = [];

		$container.children('.fpd-pr-group').each(function(i, group) {

			var $group = $(group),
				viewValue = $group.find('input[name="views"]').val(),
				elementsValue = $group.find('select[name="elements"]').val(),
				groupObj = {
					property : $group.find('select[name="property"]').val(),
					target: {
						views: viewValue === '' | isNaN(viewValue) ? -1 : parseInt(viewValue),
						elements: elementsValue === 'single' ? '#'+$group.find('input[name="elementTitle"]').val() : elementsValue
					},
					type: $group.find('.fpd-pr-type:checked').val()
				};

			var rules = [];
			$group.find('.fpd-pr-rules > div').each(function(i, rule) {

				var $rule = $(rule),
					$valueWrapper = $rule.children('.fpd-pr-rule-value'),
					ruleObj = {
						operator: $rule.children('[name="operator"]').val(),
						price: $rule.children('[name="price"]').val().length > 0 ? Number($rule.children('[name="price"]').val()) : ''
					};

				var $values = $valueWrapper.children('input'),
					valueEntry;

				if($values.length > 1) {

					valueEntry = {};
					$values.each(function(j, valueField) {

						if(valueField.value.length > 0) {
							valueEntry[valueField.name] = Number(valueField.value);
						}

					});

				}
				else {
					valueEntry = $values.first().val().length > 0 ? Number($values.first().val()) : '';
				}

				ruleObj.value = valueEntry;

				rules.push(ruleObj);

			});

			groupObj.rules = rules;

			var groupData = {
				name: $group.find('.fpd-pr-group-title').text(),
				data: groupObj
			};

			groups.push(groupData);

		});

		return groups;

	};

	this.getAllData = function() {

		var groupData = [];
		this.getAll().forEach(function(group) {
			groupData.push(group.data);
		});

		return groupData;

	};

	this.loadGroups = function(groups) {

		var _this = this;

		groups.forEach(function(groupObj) {

			_this.addGroup(groupObj.name, groupObj.data);

		});

	};

	var _updateForm = function($groupBody) {

		var selectedProp = $groupBody.find('select[name="property"]').val(),
			$selectElements = $groupBody.find('select[name="elements"]');

		$selectElements.removeClass('fpd-pr-disabled').children('option').removeClass('fpd-hidden');

		if(['linesLength', 'textLength'].indexOf(selectedProp) !== -1) {
			$selectElements.children('[value="all"], [value="image"], [value="customImage"]').addClass('fpd-hidden');
		}
		else if(['imageSize'].indexOf(selectedProp) !== -1 || ['imageSizeScaled'].indexOf(selectedProp) !== -1) {
			$selectElements.children('[value="all"], [value="text"], [value="customText"]').addClass('fpd-hidden')
		}
		else if(['colorsLength'].indexOf(selectedProp) !== -1) {
			$selectElements.addClass('fpd-pr-disabled');
		}

		//select first visible option
		$selectElements.children('option:not(.fpd-hidden):first').prop('selected', true);
		$groupBody.find('select[name="elements"]').change();

	};

	var _fillGroup = function($group, data) {

		if(data && typeof data === 'object') {

			if(data.hasOwnProperty('property')) {
				$group.find('select[name="property"]').val(data.property);
				_updateForm($group.find('.fpd-pr-group-body:first'));
			}

			if(data.hasOwnProperty('target')) {
				var targetObj = data.target,
					targetViews = targetObj.view !== undefined ? targetObj.view : targetObj.views; //in V1.0 view property was used

				$group.find('input[name="views"]').val(targetViews === -1 ? '' : targetViews);
				if(targetObj.hasOwnProperty('elements')) {
					if(targetObj.elements.charAt(0) === '#') {
						$group.find('select[name="elements"]').val('single')
						.next('input').removeClass('fpd-hidden').val(targetObj.elements.replace('#', ''));
					}
					else {
						$group.find('select[name="elements"]').val(targetObj.elements);
					}

				}
			}

			if(data.hasOwnProperty('type')) {
				$group.find('.fpd-pr-type[value="'+data.type+'"]').prop('checked', true);
			}

			if(data.hasOwnProperty('rules')) {

				data.rules.forEach(function(ruleObj) {
					var $rule = _addRule($group.find('.fpd-pr-rules:first'), data.property);

					$rule.find('[name="operator"]').val(ruleObj.operator);

					if(typeof ruleObj.value === 'object') {
						Object.keys(ruleObj.value).forEach(function(name) {
							$rule.find('[name="'+name+'"]').val(ruleObj.value[name]);
						});
					}
					else {
						$rule.find('[name="value"]').val(ruleObj.value);
					}

					$rule.find('[name="price"]').val(ruleObj.price);

				});

			}

		}

	};

	var _addRule = function($rulesWrapper, property) {


		var $rule = $ruleTemplate.clone(),
			$value = '<input type="number" name="value" value="" placeholder="'+options.ruleInputValuePlaceholder+'" />';

		if(property === 'imageSize' || property === 'imageSizeScaled') {
			$value = '<input type="number" name="width" placeholder="'+options.ruleInputWidthPlaceholder+'" /><input type="number" name="height" placeholder="'+options.ruleInputHeightPlaceholder+'" />';
			$rule.find('.fpd-pr-rule-value').addClass('fpd-two');
		}

		$rule.find('.fpd-pr-rule-value').html($value);
		$rulesWrapper.append($rule);

		return $rule;

	};

	//select:property changed
	var prevProperty;
	$container.on('focus',  'select[name="property"]', function() {
		prevProperty = this.value;
	})
	.on('change', 'select[name="property"]', function() {

		var select = this;
			$groupBody = $(this).parents('.fpd-pr-group-body:first');

		if($groupBody.find('.fpd-pr-rules').children().length > 0) {

			radykalConfirm({msg: options.rulesRemoveConfirm}, function(confirmResult) {

				if(!confirmResult) {
					select.value = prevProperty;
					return;
				}
				else {
					$groupBody.find('.fpd-pr-rules').empty();
					_updateForm($groupBody);

					$container.trigger('rulesChange');
				}

			});

		}
		else {
			$groupBody.find('.fpd-pr-rules').empty();
			_updateForm($groupBody);
		}

	})
	//select:elements changed
	.on('change', 'select[name="elements"]', function() {

		$(this).next('input').val('').toggleClass('fpd-hidden', this.value !== 'single');

		$container.trigger('rulesChange');

	})
	//add rule
	.on('click', '.fpd-pr-add-rule', function() {

		var $this = $(this),
			$groupBody = $this.parents('.fpd-pr-group-body:first');

		_addRule($groupBody.find('.fpd-pr-rules:first'), $groupBody.find('select[name="property"]').val());

	})
	//remove rule
	.on('click', '.fpd-pr-rule-remove', function() {

		$(this).parents('.fpd-pr-rule:first').remove();

		$container.trigger('rulesChange');

	})
	//remove group
	.on('click', '.fpd-pr-group-remove', function() {

		$(this).parents('.fpd-pr-group:first').remove();

		$container.trigger('rulesChange');

	})
	//collapse group
	.on('click', '.fpd-pr-group-collapse', function() {

		$(this).parents('.fpd-pr-group:first').toggleClass('fpd-closed');

	})
	.on('change', '.fpd-pr-rule input, .fpd-pr-rule select, input[name="views"], .fpd-pr-type', function() {
		$container.trigger('rulesChange');
	});

};