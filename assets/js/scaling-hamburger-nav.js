(function () {
	'use strict';

	function isEditorPreview() {
		return !!(window.elementorFrontend
			&& typeof window.elementorFrontend.isEditMode === 'function'
			&& window.elementorFrontend.isEditMode());
	}

	function initScalingHamburgerNav(root) {
		if (!root) return;
		if (root.dataset.egsapShnInit === '1') return;
		root.dataset.egsapShnInit = '1';

		if (isEditorPreview()) return; // Skip di editor iframe.

		var setStatus = function (status) {
			root.setAttribute('data-egsap-shn-status', status);
		};
		var isActive = function () {
			return root.getAttribute('data-egsap-shn-status') === 'active';
		};
		var open  = function () { setStatus('active'); };
		var close = function () { setStatus('not-active'); };
		var toggle = function () { if (isActive()) close(); else open(); };

		root.querySelectorAll('[data-egsap-shn-toggle="toggle"]').forEach(function (btn) {
			btn.addEventListener('click', toggle);
			// <div role="button"> — kasih Enter/Space handling manual biar aksesibel
			btn.addEventListener('keydown', function (e) {
				if (e.key === 'Enter' || e.key === ' ') {
					e.preventDefault();
					toggle();
				}
			});
		});
		root.querySelectorAll('[data-egsap-shn-toggle="close"]').forEach(function (btn) {
			btn.addEventListener('click', close);
		});

		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && isActive()) close();
		});
	}

	function initAll(scope) {
		(scope || document).querySelectorAll('[data-egsap-shn]').forEach(initScalingHamburgerNav);
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
