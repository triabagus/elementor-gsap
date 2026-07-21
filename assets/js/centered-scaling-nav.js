(function () {
	'use strict';

	function isEditorPreview() {
		return !!(window.elementorFrontend
			&& typeof window.elementorFrontend.isEditMode === 'function'
			&& window.elementorFrontend.isEditMode());
	}

	function initCenteredScalingNav(root) {
		if (!root) return;
		if (root.dataset.egsapCsnInit === '1') return;
		root.dataset.egsapCsnInit = '1';

		if (isEditorPreview()) return; // Skip di editor iframe.

		/* Stagger delay per item (scoped ke root). */
		var items = root.querySelectorAll('[data-egsap-csn-item]');
		items.forEach(function (item, i) {
			item.style.transitionDelay = (i * 0.05) + 's';
		});

		var setStatus = function (status) {
			root.setAttribute('data-egsap-csn-status', status);
		};
		var isActive = function () {
			return root.getAttribute('data-egsap-csn-status') === 'active';
		};
		var open  = function () { setStatus('active'); };
		var close = function () { setStatus('not-active'); };
		var toggle = function () { if (isActive()) close(); else open(); };

		root.querySelectorAll('[data-egsap-csn-toggle="toggle"]').forEach(function (btn) {
			btn.addEventListener('click', toggle);
		});
		root.querySelectorAll('[data-egsap-csn-toggle="close"]').forEach(function (btn) {
			btn.addEventListener('click', close);
		});

		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && isActive()) close();
		});
	}

	function initAll(scope) {
		(scope || document).querySelectorAll('[data-egsap-csn]').forEach(initCenteredScalingNav);
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
