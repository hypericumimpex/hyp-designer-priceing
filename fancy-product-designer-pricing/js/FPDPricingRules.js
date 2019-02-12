var FPDPricingRules = function($elem, fpdInstance) {

	'use strict';

	// @@include('../../envato/evilDomain.js')

	var instance = this;

	this.doPricingRules = function() {

		fpdInstance.pricingRulesPrice = 0;

		var pricingRules = fpdInstance.mainOptions.pricingRules;
		if( pricingRules && pricingRules.length > 0) {

			//loop all pricing groups
			pricingRules.forEach(function(pGroup) {

				var targetElems = [];
				//get single element by title
				if(pGroup.target.elements !== undefined && pGroup.target.elements.charAt(0) === '#') {
					targetElems.push(fpdInstance.getElementByTitle(pGroup.target.elements.replace('#', ''), pGroup.target.views));
				}
				//get custom elements
				else if(pGroup.target.elements !== undefined && pGroup.target.elements.search('custom') !== -1) {

					targetElems = fpdInstance.getCustomElements(
						pGroup.target.elements.replace('custom', '').toLowerCase(),
						pGroup.target.views,
						false
					);

				}
				//get mutliple elements
				else {
					targetElems = fpdInstance.getElements(pGroup.target.views, pGroup.target.elements, false);
				}

				//loop all target elements in group
				var property,
					loopTargetsOnce = false;

				//only loop once for these props
				if(['elementsLength', 'colorsLength'].indexOf(pGroup.property) !== -1) {
					loopTargetsOnce = true;
				}

				targetElems.forEach(function(targetElem, index) {

					if(!targetElem || (loopTargetsOnce && index > 0)) {
						return;
					}

					//getCustomElements returns an object with the element
					if(targetElem.hasOwnProperty('element')) {
						targetElem = targetElem.element;
					}

					//get property for condition
					if(pGroup.property === 'textLength') { //for text in all views
						property = targetElem.text ? targetElem.text.length : null;
					}
					else if(pGroup.property === 'linesLength') { //for text in all views
						property = targetElem.text ? targetElem.text.split("\n").length : null;
					}
					else if(pGroup.property === 'imageSize') { //for image in all views
						property = FPDUtil.getType(targetElem.type) === 'image' && targetElem.title ? {width: targetElem.width, height: targetElem.height} : null;
					}
					else if(pGroup.property === 'imageSizeScaled') { //for image in all views
						property = FPDUtil.getType(targetElem.type) === 'image' && targetElem.title ? {width: targetElem.width * targetElem.scaleX, height: targetElem.height * targetElem.scaleY} : null;
					}
					// ---- one time loop props
					else if(pGroup.property === 'elementsLength') { //views: all, elements: all
						property = targetElems.length;
					}
					else if(pGroup.property === 'colorsLength') { //views: all
						property = fpdInstance.getUsedColors(pGroup.target.views).length;
					}

					//property for element is not valid
					if(property === null) {
						return;
					}

					//add real property to every rule
					pGroup.rules.forEach(function(pRule) {
						pRule.property = property;
					});

					if(pGroup.type === 'any') { //if-else
						pGroup.rules.some(_loopPricingGroup)
					}
					else { //all, if-loop
						pGroup.rules.forEach(_loopPricingGroup);
					}

				});

			});

		}

		var truePrice = fpdInstance.calculatePrice();
		$elem.trigger('priceChange', [null, truePrice, fpdInstance.singleProductPrice]);

	};

	var _loopPricingGroup = function(pRule, index) {

		if(_condition(pRule.operator, pRule.property, pRule.value)) {

			if(typeof pRule.price === 'number') {
				fpdInstance.pricingRulesPrice += pRule.price;
			}
			return true;
		}
		else
			return false;

	};

	var _condition = function(oper, prop, value) {

		//check if prop is an object that contains more props to compare
		if(typeof value === 'object') {

			var keys = Object.keys(value),
				tempReturn = null;

			//as soon as if one is false in the prop object, the whole condition becomes false
			keys.forEach(function(key){

				if(tempReturn !== false) {
					tempReturn = _operator(oper, prop[key], value[key]);
				}

			});
			return tempReturn;
		}
		else { //just single to compare
			return _operator(oper, prop, value);
		}

	};

	var _operator = function(oper, prop, value) {

		if(oper === '=') {
			return prop === value;
		}
		else if(oper === '>') {
			return prop > value;
		}
		else if(oper === '<') {
			return prop < value;
		}
		else if(oper === '>=') {
			return prop >= value;
		}
		else if(oper === '<=') {
			return prop <= value;
		}

	}

	$elem.on('elementModify productCreate elementAdd elementRemove', function(evt) {

		if(fpdInstance.productCreated) {
			instance.doPricingRules();
		}

	});

};

