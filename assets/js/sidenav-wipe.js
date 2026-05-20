(function () {
	'use strict';

	var instances = new Map();
	var customEaseRegistered = false;

	function isEditorPreview() {
		return !!(window.elementorFrontend
			&& typeof window.elementorFrontend.isEditMode === 'function'
			&& window.elementorFrontend.isEditMode());
	}

	function ensureGsap() {
		return typeof window.gsap !== 'undefined';
	}

	function ensureCustomEase() {
		if (!ensureGsap()) return false;
		if (typeof window.CustomEase === 'undefined') return false;
		if (customEaseRegistered) return true;
		try {
			gsap.registerPlugin(CustomEase);
			if (typeof CustomEase.get !== 'function' || !CustomEase.get('sidenav-main')) {
				CustomEase.create('sidenav-main', '0.65, 0.01, 0.05, 0.99');
			}
			customEaseRegistered = true;
			return true;
		} catch (_) {
			return false;
		}
	}

	function destroyInstance(root) {
		var inst = instances.get(root);
		if (!inst) return;
		if (inst.timeline) { try { inst.timeline.kill(); } catch (_) {} }
		if (inst.onToggle && inst.toggles) {
			inst.toggles.forEach(function (t) { t.removeEventListener('click', inst.onToggle); });
		}
		if (inst.onKeydown) document.removeEventListener('keydown', inst.onKeydown);
		root.classList.remove('sidenav--editor');
		instances.delete(root);
		delete root.dataset.snInit;
	}

	function cleanupStale() {
		instances.forEach(function (_inst, root) {
			if (!root.isConnected) destroyInstance(root);
		});
	}

	function initOne(root) {
		if (root.dataset.snInit === '1') destroyInstance(root);
		root.dataset.snInit = '1';

		var navWrap = root.querySelector('[data-sidenav-wrap]');
		if (!navWrap) return;
		var overlay         = navWrap.querySelector('[data-sidenav-overlay]');
		var menu            = navWrap.querySelector('[data-sidenav-menu]');
		var bgPanels        = navWrap.querySelectorAll('[data-sidenav-panel]');
		var menuToggles     = root.querySelectorAll('[data-sidenav-toggle]');
		var menuLinks       = navWrap.querySelectorAll('[data-sidenav-link]');
		var fadeTargets     = navWrap.querySelectorAll('[data-sidenav-fade]');
		var menuButton      = root.querySelector('[data-sidenav-button]');
		if (!menuButton) return;
		var menuButtonTexts = menuButton.querySelectorAll('[data-sidenav-label]');
		var menuButtonIcon  = menuButton.querySelector('[data-sidenav-icon]');

		var inst = {
			root: root,
			navWrap: navWrap,
			toggles: Array.from(menuToggles),
			timeline: null,
			onToggle: null,
			onKeydown: null,
		};

		// Editor preview — render inline, no animation, no toggle.
		if (isEditorPreview()) {
			root.classList.add('sidenav--editor');
			navWrap.setAttribute('data-nav-state', 'open');
			instances.set(root, inst);
			return;
		}

		if (!ensureGsap()) {
			console.warn('GSAP belum dimuat untuk Side Navigation Wipe.');
			instances.set(root, inst);
			return;
		}

		var hasCustomEase = ensureCustomEase();
		var ease = hasCustomEase ? 'sidenav-main' : 'cubic-bezier(0.65, 0.01, 0.05, 0.99)';

		var duration = parseFloat(root.dataset.snDuration);
		if (isNaN(duration) || duration <= 0) duration = 0.7;

		inst.timeline = gsap.timeline({ defaults: { ease: ease, duration: duration } });

		function openNav() {
			navWrap.setAttribute('data-nav-state', 'open');
			menuButton.setAttribute('aria-expanded', 'true');
			inst.timeline.clear()
				.set(navWrap, { display: 'block' })
				.set(menu, { xPercent: 0 }, '<')
				.fromTo(menuButtonTexts, { yPercent: 0 }, { yPercent: -100, stagger: 0.2 })
				.fromTo(menuButtonIcon, { rotate: 0 }, { rotate: 315 }, '<')
				.fromTo(overlay, { autoAlpha: 0 }, { autoAlpha: 1 }, '<')
				.fromTo(bgPanels, { xPercent: 101 }, { xPercent: 0, stagger: 0.12, duration: 0.575 }, '<')
				// clearProps: 'transform' so the CSS :hover translateX kicks in after the open completes.
				.fromTo(menuLinks, { yPercent: 140, rotate: 10 }, { yPercent: 0, rotate: 0, stagger: 0.05, clearProps: 'transform' }, '<+=0.35')
				// Same for socials — their hover state needs a clean transform to apply translateY.
				.fromTo(fadeTargets, { autoAlpha: 0, yPercent: 50 }, { autoAlpha: 1, yPercent: 0, stagger: 0.04, clearProps: 'transform' }, '<+=0.2');
		}

		function closeNav() {
			navWrap.setAttribute('data-nav-state', 'closed');
			menuButton.setAttribute('aria-expanded', 'false');
			inst.timeline.clear()
				.to(overlay, { autoAlpha: 0 })
				.to(menu, { xPercent: 120 }, '<')
				.to(menuButtonTexts, { yPercent: 0 }, '<')
				.to(menuButtonIcon, { rotate: 0 }, '<')
				.set(navWrap, { display: 'none' });
		}

		inst.onToggle = function () {
			var state = navWrap.getAttribute('data-nav-state');
			if (state === 'open') closeNav();
			else openNav();
		};
		inst.toggles.forEach(function (t) { t.addEventListener('click', inst.onToggle); });

		inst.onKeydown = function (e) {
			if (e.key === 'Escape' && navWrap.getAttribute('data-nav-state') === 'open') {
				closeNav();
			}
		};
		document.addEventListener('keydown', inst.onKeydown);

		menuButton.setAttribute('aria-expanded', 'false');

		instances.set(root, inst);
	}

	function initAll(scope) {
		cleanupStale();
		var root = scope || document;
		if (typeof root.querySelectorAll !== 'function') return;
		root.querySelectorAll('[data-sidenav-root]').forEach(initOne);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () { initAll(); });
	} else {
		initAll();
	}

	if (window.elementorFrontend && window.elementorFrontend.hooks) {
		window.elementorFrontend.hooks.addAction(
			'frontend/element_ready/sidenav_wipe.default',
			function ($scope) {
				var node = $scope && $scope[0];
				if (!node) return;
				initAll(node);
			}
		);
	}
})();
