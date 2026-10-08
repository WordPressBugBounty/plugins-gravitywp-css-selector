/* global jQuery */
(function ($) {
	'use strict';
	$(function () {
		var $library = $('#gwp-class-library');
		if (!$library.length) {
			return;
		}
		var nextIndex = $library.find('tbody tr').length;
		$library.on('click', '.gwp-library-add', function () {
			var template = document.getElementById('gwp-library-row-template');
			var $row = $(template.innerHTML.replace(/__index__/g, nextIndex++));
			$library.find('tbody').append($row);
			$row.find('input').first().trigger('focus');
		});
		$library.on('click', '.gwp-library-remove', function () {
			var $row = $(this).closest('tr');
			var $next = $row.next().find('input').first();
			$row.remove();
			($next.length ? $next : $library.find('.gwp-library-add')).trigger('focus');
		});
	});
})(jQuery);
