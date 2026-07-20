(function () {
	'use strict';

	var instances = new Map();
	var customEaseRegistered = false;

	function isEditorPreview() {
		return !!(window.elementorFrontend
			&& typeof window.elementorFrontend.isEditMode === 'function'
			&& window.elementorFrontend.isEditMode());
	}

	function ensureCustomEase() {
		if (customEaseRegistered) return true;
		if (typeof window.gsap === 'undefined' || typeof window.CustomEase === 'undefined') return false;
		try {
			gsap.registerPlugin(CustomEase);
			if (typeof CustomEase.get !== 'function' || !CustomEase.get('osmo')) {
				CustomEase.create('osmo', 'M0,0 C0.625,0.05 0,1 1,1');
			}
			customEaseRegistered = true;
			return true;
		} catch (_) {
			return false;
		}
	}

	function destroyInstance(nav) {
		var inst = instances.get(nav);
		if (!inst) return;
		if (inst.timeline)  { try { inst.timeline.kill(); } catch (_) {} }
		if (inst.onClick && inst.toggle) inst.toggle.removeEventListener('click', inst.onClick, true);
		if (inst.onKeydown) document.removeEventListener('keydown', inst.onKeydown);
		if (inst.onResize)  window.removeEventListener('resize', inst.onResize);
		instances.delete(nav);
		delete nav.dataset.egsapEbnInit;
	}

	function cleanupStale() {
		instances.forEach(function (_inst, nav) {
			if (!nav.isConnected) destroyInstance(nav);
		});
	}

	function initExpandingBottomNav(nav) {
		if (!nav) return;
		if (nav.dataset.egsapEbnInit === '1') return;
		nav.dataset.egsapEbnInit = '1';

		if (typeof window.gsap === 'undefined') {
			console.warn('GSAP belum dimuat untuk Expanding Bottom Navigation.');
			return;
		}
		var gsap    = window.gsap;
		var inEditor = isEditorPreview();
		ensureCustomEase();

		var inner   = nav.querySelector('[data-bottom-nav-inner]');
		var bar     = nav.querySelector('[data-bottom-nav-bar]');
		var panel   = nav.querySelector('[data-bottom-nav-panel]');
		var toggle  = nav.querySelector('[data-bottom-nav-toggle]');
		if (!inner || !bar || !panel || !toggle) return;

		var reveals = panel.querySelectorAll('[data-bottom-nav-reveal]');
		var barTop  = toggle.querySelector('.bottom-nav__toggle-bar.is--top');
		var barBot  = toggle.querySelector('.bottom-nav__toggle-bar.is--btm');
		var divider = nav.querySelector('[data-bottom-nav-divider]');

		var openDuration = parseFloat(nav.dataset.bottomNavOpenDuration);
		if (isNaN(openDuration) || openDuration <= 0) openDuration = 0.65;

		var revealStagger = parseFloat(nav.dataset.bottomNavStagger);
		if (isNaN(revealStagger) || revealStagger < 0) revealStagger = 0.03;

		var barDuration = parseFloat(nav.dataset.bottomNavBarDuration);
		if (isNaN(barDuration) || barDuration <= 0) barDuration = 0.4;

		var isOpen     = false;
		var enterEnd   = 0;
		var dimensions = { closedW: 0, closedH: 0, openW: 0, openH: 0 };
		var tl;
		var resizeTimer;

		function measure() {
			var w = inner.style.width;
			var h = inner.style.height;
			inner.style.width  = 'var(--ebn-open-width)';
			inner.style.height = 'auto';
			var openW = inner.offsetWidth;
			var openH = inner.offsetHeight;
			inner.style.width  = 'var(--ebn-closed-width)';
			var closedW = inner.offsetWidth;
			inner.style.width  = w;
			inner.style.height = h;
			return { closedW: closedW, closedH: bar.offsetHeight, openW: openW, openH: openH };
		}

		function applyClosed() {
			gsap.set(inner, { width: dimensions.closedW, height: dimensions.closedH });
		}

		function buildTimeline() {
			tl = gsap.timeline({
				paused:   true,
				defaults: { ease: 'osmo', easeReverse: 'power2.inOut' },
			});

			tl.to(inner, {
				width:    function () { return dimensions.openW; },
				height:   function () { return dimensions.openH; },
				duration: openDuration,
			}, 0);

			if (barTop) {
				tl.to(barTop, {
					y:           '0.175em',
					rotation:    45,
					duration:    barDuration,
					ease:        'back.out(2)',
					easeReverse: 'power3.out',
				}, 0.05);
			}

			if (barBot) {
				tl.to(barBot, {
					y:           '-0.175em',
					rotation:    -45,
					duration:    barDuration,
					ease:        'back.out(2)',
					easeReverse: 'power3.out',
				}, 0.05);
			}

			tl.set(panel, { autoAlpha: 1 }, 0.1);

			if (reveals.length) {
				tl.fromTo(reveals,
					{ autoAlpha: 0, yPercent: 100 },
					{ autoAlpha: 1, yPercent: 0, duration: 0.6, stagger: revealStagger },
					0.1
				);
			}

			if (divider) {
				tl.fromTo(divider,
					{ scaleX: 0, autoAlpha: 0 },
					{ scaleX: 1, autoAlpha: 1, duration: 1.1 },
					0
				);
			}

			enterEnd = tl.duration();
			tl.addPause();

			// Close half
			if (reveals.length) {
				tl.to(reveals, {
					autoAlpha: 0,
					yPercent:  10,
					duration:  0.25,
					stagger:   { each: 0.01, from: 'end' },
				});
			}

			tl.to(inner, {
				width:    function () { return dimensions.closedW; },
				height:   function () { return dimensions.closedH; },
				duration: 0.45,
				ease:     'power3.inOut',
			}, '<');

			if (barTop && barBot) {
				tl.to([ barTop, barBot ], {
					y:        0,
					rotation: 0,
					duration: 0.3,
					ease:     'power3.in',
				}, '<');
			}

			tl.set(panel, { autoAlpha: 0 });
		}

		function setState(open) {
			isOpen = open;
			nav.setAttribute('data-bottom-nav-open', String(open));
			toggle.setAttribute('aria-expanded', String(open));
			toggle.setAttribute('aria-label', open ? 'close menu' : 'open menu');
			panel.setAttribute('aria-hidden', String(!open));
		}

		function toggleNav() {
			setState(!isOpen);
			if (isOpen) {
				tl.invalidate();
				if (tl.time() >= enterEnd) tl.timeScale(1).restart();
				else tl.timeScale(1).play();
			} else if (tl.time() < enterEnd) {
				tl.timeScale(1).reverse();
			} else {
				tl.timeScale(1).play();
			}
		}

		function onClick(e) {
			// Di editor Elementor, click di dalam widget preview biasanya
			// ditangkap oleh Elementor untuk widget selection. stopPropagation
			// mencegah click bubble ke document-level handler Elementor —
			// user bisa klik toggle untuk preview animasi tanpa memicu
			// select-widget yang mengganggu.
			if (inEditor && e && typeof e.stopPropagation === 'function') {
				e.stopPropagation();
			}
			if (e && typeof e.preventDefault === 'function') {
				e.preventDefault();
			}
			toggleNav();
		}

		function onKeydown(e) {
			if (e.key === 'Escape' && isOpen) {
				toggleNav();
				toggle.focus();
			}
		}

		function onResize() {
			clearTimeout(resizeTimer);
			resizeTimer = setTimeout(function () {
				dimensions = measure();
				if (isOpen) gsap.set(inner, { width: dimensions.openW, height: dimensions.openH });
				else {
					if (tl) tl.invalidate();
					applyClosed();
				}
			}, 150);
		}

		dimensions = measure();
		applyClosed();
		buildTimeline();

		// Editor mode: seek timeline ke end (state open) supaya user langsung
		// bisa lihat + styling isi panel tanpa perlu klik toggle dulu. Toggle
		// tetap bisa diklik untuk preview close → open animation.
		if (inEditor) {
			tl.time(enterEnd);
			setState(true);
		}

		// Capture phase supaya handler kita fire duluan sebelum listener
		// document-level Elementor (yang di editor akan intercept click
		// untuk widget selection).
		toggle.addEventListener('click', onClick, true);
		document.addEventListener('keydown', onKeydown);
		window.addEventListener('resize', onResize);

		instances.set(nav, {
			timeline:  tl,
			toggle:    toggle,
			onClick:   onClick,
			onKeydown: onKeydown,
			onResize:  onResize,
		});
	}

	function initAll(root) {
		cleanupStale();
		var scope = root || document;
		scope.querySelectorAll('[data-bottom-nav-init]').forEach(initExpandingBottomNav);
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
