(function () {
	'use strict';

	var instances = new Map();

	function isEditorPreview() {
		return !!(window.elementorFrontend
			&& typeof window.elementorFrontend.isEditMode === 'function'
			&& window.elementorFrontend.isEditMode());
	}

	function destroyInstance(container) {
		var inst = instances.get(container);
		if (!inst) return;
		if (inst.triggers) {
			inst.triggers.forEach(function (t) {
				try { t.kill(); } catch (_) {}
			});
		}
		instances.delete(container);
		delete container.dataset.egsapStickyInit;
	}

	function cleanupStale() {
		instances.forEach(function (_inst, container) {
			if (!container.isConnected) destroyInstance(container);
		});
	}

	function setActiveStep(items, activeIndex) {
		items.forEach(function (item, index) {
			var status = 'active';
			if (index < activeIndex) status = 'before';
			if (index > activeIndex) status = 'after';
			if (item.getAttribute('data-sticky-steps-item-status') !== status) {
				item.setAttribute('data-sticky-steps-item-status', status);
			}
		});
	}

	function initStickyStepsBasic(container) {
		if (!container) return;
		if (container.dataset.egsapStickyInit === '1') return;
		container.dataset.egsapStickyInit = '1';

		// Editor preview: skip ScrollTrigger — di iframe editor sering rusak
		// karena scroll-parent detection ambigu. Item tetap terlihat semua
		// (via CSS editor override).
		if (isEditorPreview()) {
			instances.set(container, { editor: true });
			return;
		}

		if (typeof window.gsap === 'undefined' || typeof window.ScrollTrigger === 'undefined') {
			console.warn('GSAP + ScrollTrigger belum dimuat untuk Sticky Steps.');
			return;
		}
		var gsap          = window.gsap;
		var ScrollTrigger = window.ScrollTrigger;

		try { gsap.registerPlugin(ScrollTrigger); } catch (_) {}

		var items = Array.prototype.slice.call(container.querySelectorAll('[data-sticky-steps-item]'));
		if (!items.length) return;

		var triggers = [];

		items.forEach(function (item, index) {
			var anchor = item.querySelector('[data-sticky-steps-anchor]');
			if (!anchor) return;

			var st = ScrollTrigger.create({
				trigger:      anchor,
				start:        'center center',
				onEnter:      function () { setActiveStep(items, index); },
				onEnterBack:  function () { setActiveStep(items, index); },
			});
			triggers.push(st);
		});

		// Initial state: item pertama active supaya UI konsisten sebelum
		// ScrollTrigger fire pertama kali (mis. saat load di posisi paling atas).
		setActiveStep(items, 0);

		instances.set(container, { triggers: triggers });
	}

	function initAll(root) {
		cleanupStale();
		var scope = root || document;
		scope.querySelectorAll('[data-sticky-steps-init]').forEach(initStickyStepsBasic);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () { initAll(); });
	} else {
		initAll();
	}

	if (window.elementorFrontend && window.elementorFrontend.hooks) {
		window.elementorFrontend.hooks.addAction('frontend/init', function () {
			initAll(document);
		});
	}
})();
