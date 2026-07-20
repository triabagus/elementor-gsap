(function () {
	'use strict';

	function isEditorPreview() {
		return !!(window.elementorFrontend
			&& typeof window.elementorFrontend.isEditMode === 'function'
			&& window.elementorFrontend.isEditMode());
	}

	function initBoldFullscreenNav(root) {
		if (!root) return;
		if (root.dataset.egsapBfnInit === '1') return;
		root.dataset.egsapBfnInit = '1';

		if (isEditorPreview()) return; // Skip di editor — tile clip-path tetap closed, JS nggak dijalankan.

		var setStatus = function (status) {
			root.setAttribute('data-egsap-bfn-status', status);
			if (status === 'active') {
				document.body.style.overflow = 'hidden';
			} else {
				document.body.style.overflow = '';
			}
		};
		var isActive = function () {
			return root.getAttribute('data-egsap-bfn-status') === 'active';
		};
		var openNav  = function () { setStatus('active'); };
		var closeNav = function () { setStatus('not-active'); };
		var toggleNav = function () { if (isActive()) closeNav(); else openNav(); };

		root.querySelectorAll('[data-egsap-bfn-toggle="toggle"]').forEach(function (btn) {
			btn.addEventListener('click', toggleNav);
		});
		root.querySelectorAll('[data-egsap-bfn-toggle="close"]').forEach(function (btn) {
			btn.addEventListener('click', closeNav);
		});
		root.querySelectorAll('.egsap-bfn__link').forEach(function (link) {
			link.addEventListener('click', function () {
				if (isActive()) closeNav();
			});
		});

		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && isActive()) closeNav();
		});
	}

	function initAll(scope) {
		(scope || document).querySelectorAll('[data-egsap-bfn]').forEach(initBoldFullscreenNav);
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
