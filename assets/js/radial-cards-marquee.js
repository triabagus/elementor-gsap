(function () {
	'use strict';

	function isEditorPreview() {
		return !!(window.elementorFrontend
			&& typeof window.elementorFrontend.isEditMode === 'function'
			&& window.elementorFrontend.isEditMode());
	}

	function initRadialCardsMarquee(root) {
		if (!root) return;
		if (root.dataset.egsapRcmInit === '1') return;
		root.dataset.egsapRcmInit = '1';

		if (isEditorPreview()) return; // Skip di editor — animasi di-freeze via CSS.

		var list = root.querySelector('[data-egsap-rcm-list]');
		if (!list) return;

		// Baca --total dari computed style (base 10 — fix bug reference yang pakai base 12).
		var total = parseInt(getComputedStyle(list).getPropertyValue('--total'), 10);
		if (!total || total < 1) return;

		var originals = Array.prototype.slice.call(list.children);
		if (!originals.length) return;

		// Clone / remove supaya jumlah children = --total.
		while (list.children.length > total) list.lastElementChild.remove();

		for (var i = list.children.length; i < total; i++) {
			var clone = originals[i % originals.length].cloneNode(true);
			clone.setAttribute('aria-hidden', 'true');
			list.appendChild(clone);
		}

		// Set inline --card supaya nggak dependent ke CSS :nth-child rules.
		Array.prototype.slice.call(list.children).forEach(function (item, idx) {
			item.style.setProperty('--card', idx + 1);
		});

		// Pause animasi kalau semua card di luar viewport — hemat CPU.
		var visibilityCheck = function () {
			var visible = Array.prototype.slice.call(list.children).some(function (item) {
				var r = item.getBoundingClientRect();
				return r.bottom > 0 && r.right > 0 && r.top < window.innerHeight && r.left < window.innerWidth;
			});
			list.style.animationPlayState = visible ? 'running' : 'paused';
		};

		window.addEventListener('scroll', visibilityCheck, { passive: true });
		window.addEventListener('resize', visibilityCheck);
		visibilityCheck();
	}

	function initAll(scope) {
		(scope || document).querySelectorAll('[data-egsap-rcm]').forEach(initRadialCardsMarquee);
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
