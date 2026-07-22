(function () {
	'use strict';

	function isEditorPreview() {
		return !!(window.elementorFrontend
			&& typeof window.elementorFrontend.isEditMode === 'function'
			&& window.elementorFrontend.isEditMode());
	}

	function initParallaxSlider(root) {
		if (!root) return;
		if (root.dataset.egsapPisInit === '1') return;
		root.dataset.egsapPisInit = '1';

		if (isEditorPreview()) return;

		if (typeof window.gsap === 'undefined') {
			console.warn('GSAP belum dimuat untuk Parallax Image Slider.');
			return;
		}
		if (typeof window.Smooothy === 'undefined') {
			console.warn('Smooothy belum dimuat untuk Parallax Image Slider.');
			return;
		}
		var gsap = window.gsap;
		var Smooothy = window.Smooothy;

		var wrapper = root.querySelector('[data-egsap-pis-slider]');
		if (!wrapper) return;

		/* Ambil parallax layers per slide (data-egsap-pis-inner). */
		var parallaxItems = Array.prototype.slice.call(wrapper.children).map(function (slide) {
			return slide.querySelector('[data-egsap-pis-inner]');
		});

		/* Baca config dari data-attribute di wrapper. */
		var amountAttr = wrapper.getAttribute('data-egsap-pis-amount');
		var amount = amountAttr !== null ? parseFloat(amountAttr) : 10;

		var snap = wrapper.getAttribute('data-egsap-pis-snap') !== 'false';
		var infinite = wrapper.getAttribute('data-egsap-pis-infinite') !== 'false';

		var lerpAttr = wrapper.getAttribute('data-egsap-pis-lerp');
		var lerp = lerpAttr !== null ? parseFloat(lerpAttr) : 0.3;

		var maxOffset = 25;

		var slider = new Smooothy(wrapper, {
			infinite: infinite,
			snap: snap,
			lerpFactor: lerp,
			onUpdate: function (data) {
				var pvals = data && data.parallaxValues;
				if (!pvals) return;
				parallaxItems.forEach(function (item, i) {
					if (!item) return;
					var offset = gsap.utils.clamp(-maxOffset, maxOffset, pvals[i] * amount);
					item.style.transform = 'translateX(' + offset + '%)';
				});
			},
		});

		gsap.ticker.add(function () {
			if (!root.isConnected) return;
			slider.update();
		});
	}

	function initAll(scope) {
		(scope || document).querySelectorAll('[data-egsap-pis-init]').forEach(initParallaxSlider);
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
