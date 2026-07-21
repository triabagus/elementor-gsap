(function () {
	'use strict';

	function isEditorPreview() {
		return !!(window.elementorFrontend
			&& typeof window.elementorFrontend.isEditMode === 'function'
			&& window.elementorFrontend.isEditMode());
	}

	function toggleSibling(items, index, offset, className, add) {
		var sibling = items[index + offset];
		if (sibling) sibling.classList.toggle(className, add);
	}

	function initAppleDockNav(root) {
		if (!root) return;
		if (root.dataset.egsapAdnInit === '1') return;
		root.dataset.egsapAdnInit = '1';

		if (isEditorPreview()) return; // Skip hover magnify di editor iframe.

		var items = Array.prototype.slice.call(root.querySelectorAll('.egsap-adn__item'));
		if (!items.length) return;

		items.forEach(function (item, index) {
			item.addEventListener('mouseenter', function () {
				item.classList.add('is--hover');
				toggleSibling(items, index, -1, 'is--sibling-close', true);
				toggleSibling(items, index,  1, 'is--sibling-close', true);
				toggleSibling(items, index, -2, 'is--sibling-far', true);
				toggleSibling(items, index,  2, 'is--sibling-far', true);
			});

			item.addEventListener('mouseleave', function () {
				item.classList.remove('is--hover');
				toggleSibling(items, index, -1, 'is--sibling-close', false);
				toggleSibling(items, index,  1, 'is--sibling-close', false);
				toggleSibling(items, index, -2, 'is--sibling-far', false);
				toggleSibling(items, index,  2, 'is--sibling-far', false);
			});
		});
	}

	function initAll(scope) {
		(scope || document).querySelectorAll('[data-egsap-adn]').forEach(initAppleDockNav);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () { initAll(); });
	} else {
		initAll();
	}

	if (window.elementorFrontend && window.elementorFrontend.hooks) {
		window.elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope) {
			if ($scope && $scope[0]) initAll($scope[0]);
		});
	}
})();
