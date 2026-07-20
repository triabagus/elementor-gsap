(function () {
	'use strict';

	function isEditorPreview() {
		return !!(window.elementorFrontend
			&& typeof window.elementorFrontend.isEditMode === 'function'
			&& window.elementorFrontend.isEditMode());
	}

	function initTwostepNav(root) {
		if (!root) return;
		if (root.dataset.egsapTsnInit === '1') return;
		root.dataset.egsapTsnInit = '1';

		if (isEditorPreview()) return; // Skip di editor iframe — biar bar tidak overlay UI Elementor.

		var setStatus = function (status) {
			root.setAttribute('data-egsap-tsn-status', status);
		};
		var isActive = function () {
			return root.getAttribute('data-egsap-tsn-status') === 'active';
		};
		var openNav  = function () { setStatus('active'); };
		var closeNav = function () { setStatus('not-active'); };
		var toggleNav = function () { if (isActive()) closeNav(); else openNav(); };

		root.querySelectorAll('[data-egsap-tsn-toggle="toggle"]').forEach(function (btn) {
			btn.addEventListener('click', toggleNav);
		});
		root.querySelectorAll('[data-egsap-tsn-toggle="close"]').forEach(function (btn) {
			btn.addEventListener('click', closeNav);
		});

		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && isActive()) closeNav();
		});
	}

	function initAll(scope) {
		(scope || document).querySelectorAll('[data-egsap-tsn]').forEach(initTwostepNav);
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
