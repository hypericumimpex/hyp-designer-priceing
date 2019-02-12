jQuery(document).ready(function($) {

	$ = jQuery;

	var $wrapper = $('#fpd-pricing-builder'),
		$container = $('#fpd-pricing-rules-wrapper'),
		prBuilder = new FPDPricingRulesBuilder($container, $('#fpd-pr-group-template'), fpd_admin_pr_opts);

	fpdBlockPanel($wrapper);

	$.ajax({
		url: fpd_admin_opts.adminAjaxUrl,
		data: {
			action: 'fpd_pr_loadrules',
			_ajax_nonce: fpd_admin_opts.ajaxNonce,
		},
		type: 'post',
		dataType: 'json',
		success: function(data) {

			if(data && data.groups) {
				prBuilder.loadGroups(JSON.parse(data.groups));
			}

			fpdUnblockPanel($wrapper);

		}
	});

	$('#fpd-add-pricing-group').click(function(evt) {

		evt.preventDefault();

		radykalPrompt({placeholder: fpd_admin_pr_opts.groupTitlePrompt}, function(title) {

			if(title !== null && title !== '') {

				if($container.find('.fpd-panel-header:contains('+title+')').length > 0) {
					fpdMessage(fpd_admin_pr_opts.groupExists, 'error');
					return;
				}

				prBuilder.addGroup(title);
			}

		});

	});

	$('#fpd-save-pricing-groups').click(function(evt) {

		evt.preventDefault();

		fpdBlockPanel($wrapper);

		$.ajax({
			url: fpd_admin_opts.adminAjaxUrl,
			data: {
				action: 'fpd_pr_saverules',
				_ajax_nonce: fpd_admin_opts.ajaxNonce,
				groups: JSON.stringify(prBuilder.getAll())
			},
			type: 'post',
			dataType: 'json',
			success: function(data) {

				if(data && data.message) {
					fpdMessage(data.message, 'success');
				}

				fpdUnblockPanel($wrapper);

			}
		});

	});

	$('#fpd-collapse-toggle').click(function(evt) {

		evt.preventDefault();

		var $this = $(this);

		$this.toggleClass('fpd-collapsed');
		$container.find('.fpd-pr-group').toggleClass('fpd-closed', $this.hasClass('fpd-collapsed'));

	});

});