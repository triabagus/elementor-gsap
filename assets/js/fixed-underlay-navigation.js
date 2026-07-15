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
			if (typeof CustomEase.get !== 'function' || !CustomEase.get('fun-energy')) {
				CustomEase.create('fun-energy', 'M0,0 C0.32,0.72 0,1 1,1');
			}
			customEaseRegistered = true;
			return true;
		} catch (_) {
			return false;
		}
	}

	// Find the outermost wrapper to detach. Preferred: the widget's
	// .elementor-element ancestor — keeping it intact preserves {{WRAPPER}}
	// CSS selectors after the move.
	function findDetachTarget(root) {
		if (typeof root.closest === 'function') {
			var el = root.closest('.elementor-element');
			if (el) return el;
		}
		return root;
	}

	// Elementor men-scope post-CSS dengan prefix .elementor-{POST_ID}
	// (class itu ada di <div class="elementor elementor-{POST_ID}" data-elementor-id="…">
	// yang membungkus konten halaman). Saat widget detach keluar dari wrapper itu,
	// selector .elementor-{POST_ID} .elementor-element-{WIDGET_ID} …
	// tidak match lagi → style dari Elementor controls hilang di frontend.
	// Copy class scope ke <body> supaya descendant combinator tetap match.
	function preserveElementorScope(detachTarget) {
		if (typeof detachTarget.closest !== 'function') return;
		var scopeEl = detachTarget.closest('.elementor[data-elementor-id]');
		if (!scopeEl) return;
		var pageId = scopeEl.dataset.elementorId;
		if (pageId) document.body.classList.add('elementor-' + pageId);
		// Beberapa selector Elementor juga mengandalkan class generic `.elementor`
		// di ancestor; tambahkan untuk amannya (idempotent).
		document.body.classList.add('elementor');
	}

	function detachToBody(detachTarget) {
		if (detachTarget.parentNode !== document.body) {
			preserveElementorScope(detachTarget);
			document.body.appendChild(detachTarget);
		}
	}

	function resolveMain(root, detachTarget) {
		var selector = root.dataset.funMainSelector || '';

		if (selector) {
			var el = document.querySelector(selector);
			if (el) {
				el.setAttribute('data-fun-main', '');
				return el;
			}
		}

		// Look for an explicit attribute marker already in the DOM.
		var marker = document.querySelector('[data-fun-main]');
		if (marker) return marker;

		// Auto-wrap: collect body's direct children (other than the detached
		// widget wrapper and non-renderable nodes) into a single wrapper. This
		// lets the widget work out of the box when the user hasn't set up a
		// dedicated main wrapper.
		var body = document.body;
		if (!body) return null;
		var wrap = document.createElement('div');
		wrap.setAttribute('data-fun-main', '');
		wrap.setAttribute('data-fun-auto-wrap', '');

		var toMove = [];
		for (var i = 0; i < body.children.length; i++) {
			var child = body.children[i];
			if (child === detachTarget) continue;
			if (child === wrap) continue;
			if (child.tagName === 'SCRIPT' || child.tagName === 'NOSCRIPT' || child.tagName === 'STYLE') continue;
			if (child.id === 'wpadminbar' || child.id === 'query-monitor-main') continue;
			if (child.hasAttribute && child.hasAttribute('data-fun-skip-wrap')) continue;
			toMove.push(child);
		}
		if (!toMove.length) return null;
		body.insertBefore(wrap, toMove[0]);
		toMove.forEach(function (n) { wrap.appendChild(n); });

		// Prevent horizontal scrollbar when the wrapper translates off-screen.
		var html = document.documentElement;
		if (html && getComputedStyle(html).overflowX !== 'hidden') {
			html.style.overflowX = 'hidden';
		}

		return wrap;
	}

	function destroyInstance(root) {
		var inst = instances.get(root);
		if (!inst) return;
		if (inst.timeline) { try { inst.timeline.kill(); } catch (_) {} }
		if (inst.onToggle && inst.toggleBtn) inst.toggleBtn.removeEventListener('click', inst.onToggle);
		if (inst.onOverlayClick && inst.overlayEl) inst.overlayEl.removeEventListener('click', inst.onOverlayClick);
		if (inst.onKeydown) document.removeEventListener('keydown', inst.onKeydown);
		if (inst.onResize) window.removeEventListener('resize', inst.onResize);
		root.classList.remove('fun-underlay-nav--editor');
		instances.delete(root);
		delete root.dataset.funInit;
	}

	function cleanupStale() {
		instances.forEach(function (_inst, root) {
			if (!root.isConnected) destroyInstance(root);
		});
	}

	function initOne(root) {
		if (root.dataset.funInit === '1') destroyInstance(root);
		root.dataset.funInit = '1';

		var toggleBtn      = root.querySelector('[data-underlay-nav-toggle]');
		var toggleLabels   = root.querySelectorAll('.underlay-nav__toggle-label');
		var toggleBars     = root.querySelectorAll('.underlay-nav__toggle-bar');
		var menuEl         = root.querySelector('[data-underlay-nav-menu]');
		var largeItems     = root.querySelectorAll('[data-reveal-l]');
		var smallItems     = root.querySelectorAll('[data-reveal-s]');
		var menuBorder     = root.querySelector('.underlay-nav__bottom-border');
		var overlayEl      = root.querySelector('[data-underlay-nav-overlay]');
		var darkEl         = root.querySelector('.underlay-nav__dark');
		var corners        = root.querySelectorAll('.underlay-nav__corner');
		var overlayBorders = root.querySelectorAll('.underlay-nav__border-row');

		if (!toggleBtn || !menuEl || !overlayEl) return;

		var inst = {
			root: root,
			toggleBtn: toggleBtn,
			overlayEl: overlayEl,
			timeline: null,
			isOpen: false,
			enterEndTime: 0,
			onToggle: null,
			onOverlayClick: null,
			onKeydown: null,
			onResize: null,
		};

		var inEditor = isEditorPreview();
		var mainEl = null;

		if (inEditor) {
			// Editor preview: JANGAN detach/wrap — bisa mengganggu DOM
			// management Elementor editor (selection handles, drag/drop, dll.).
			// Timeline tetap dibangun supaya user bisa preview open/close,
			// tapi animasi menggeser MENU sendiri (xPercent) alih-alih
			// shift page content via [data-fun-main]. State awal di-seek ke
			// "open" supaya user langsung bisa styling menu yang terbuka.
			root.classList.add('fun-underlay-nav--editor');
		} else {
			// Frontend: detach widget & wrap konten halaman supaya {{WRAPPER}}
			// CSS tetap match & trik z-index aktif.
			var detachTarget = findDetachTarget(root);
			detachToBody(detachTarget);

			mainEl = resolveMain(root, detachTarget);
			if (!mainEl) {
				console.warn('Fixed Underlay Navigation: no main wrapper found.');
				instances.set(root, inst);
				return;
			}
			inst.mainEl = mainEl;
		}

		if (!ensureGsap()) {
			console.warn('GSAP belum dimuat untuk Fixed Underlay Navigation.');
			instances.set(root, inst);
			return;
		}

		var hasCustomEase = ensureCustomEase();
		var primaryEase = hasCustomEase ? 'fun-energy' : 'power3.out';
		var reverseEase = 'power2.inOut';

		var duration = parseFloat(root.dataset.funDuration);
		if (isNaN(duration) || duration <= 0) duration = 0.7;

		// Baca dari CSS custom property di root widget supaya kontrol
		// "Color (Closed)" & "Color (Open)" di Elementor benar-benar mengontrol
		// warna toggle. Fallback ke computed color kalau variabel kosong
		// (mis. user hapus default).
		var rootStyle = getComputedStyle(root);
		var openColor = rootStyle.getPropertyValue('--fun-toggle-color-open').trim()
			|| getComputedStyle(menuEl).color;
		var closedColor = rootStyle.getPropertyValue('--fun-toggle-color-closed').trim()
			|| getComputedStyle(toggleBtn).color;

		function getMenuOffset() {
			return -menuEl.offsetWidth;
		}

		// Initial state (closed). Menu di-CSS `visibility: hidden` untuk
		// mencegah FOUC; flip ke visible setelah struktur siap.
		// Frontend: trik z-index ([data-fun-main] menutupi menu).
		// Editor: menu di-shift offscreen kanan (xPercent: 100) karena tidak
		// ada [data-fun-main]; nanti di-seek ke "open" untuk styling preview.
		gsap.set(menuEl, { visibility: 'visible' });
		gsap.set(overlayEl, { visibility: 'hidden', pointerEvents: 'none' });
		gsap.set(darkEl, { autoAlpha: 0 });
		if (mainEl) {
			gsap.set(mainEl, { x: 0 });
		} else {
			gsap.set(menuEl, { xPercent: 100 });
		}
		gsap.set(toggleLabels, { yPercent: 0 });
		gsap.set(toggleBars, { y: 0, rotation: 0 });
		if (menuBorder) gsap.set(menuBorder, { scaleX: 0 });
		if (overlayBorders[0]) gsap.set(overlayBorders[0], { yPercent: -100 });
		if (overlayBorders[1]) gsap.set(overlayBorders[1], { yPercent: 100 });
		if (corners.length) gsap.set(corners, { scale: 0 });

		function buildTimeline() {
			inst.timeline = gsap.timeline({
				paused: true,
				defaults: {
					ease: primaryEase,
					easeReverse: reverseEase,
					duration: duration,
				},
			});

			var tl = inst.timeline;

			tl.set(overlayEl, { visibility: 'visible', pointerEvents: 'auto' }, 0);

			// OPEN shift. Frontend: page content + overlay geser kiri.
			// Editor: menu sendiri yang slide in dari kanan (xPercent 100 → 0);
			// overlay tetap shift kiri supaya bingkai/corners frame menu.
			if (mainEl) {
				tl.to([mainEl, overlayEl], {
					x: getMenuOffset,
					duration: duration,
				}, 0);
			} else {
				tl.to(menuEl, {
					xPercent: 0,
					duration: duration,
				}, 0);
				tl.to(overlayEl, {
					x: getMenuOffset,
					duration: duration,
				}, 0);
			}

			tl.to(darkEl, {
				autoAlpha: 1,
				duration: duration * 0.71,
			}, 0)

			.to(corners, {
				scale: 1,
				duration: duration * 0.71,
			}, 0)

			.to(overlayBorders, {
				yPercent: 0,
				duration: duration * 0.71,
			}, 0)

			.to(toggleLabels, {
				yPercent: -100,
				duration: duration * 0.57,
			}, 0)

			.to(toggleBtn, {
				color: openColor,
				duration: duration * 0.57,
			}, 0)

			.to(toggleBars[0], {
				y: '0.25em',
				rotation: 45,
				duration: duration * 0.5,
				ease: 'back.out(1.4)',
				easeReverse: 'power3.out',
			}, 0.05)

			.to(toggleBars[1], {
				y: '-0.25em',
				rotation: -45,
				duration: duration * 0.5,
				ease: 'back.out(1.4)',
				easeReverse: 'power3.out',
			}, 0.05)

			.fromTo(largeItems,
				{ autoAlpha: 0, xPercent: 25 },
				{ autoAlpha: 1, xPercent: 0, duration: duration, stagger: 0.05 },
				0
			)

			.fromTo(smallItems,
				{ autoAlpha: 0, yPercent: 100 },
				{ autoAlpha: 1, yPercent: 0, duration: duration * 0.71, stagger: 0.03, ease: 'power3.out' },
				0.3
			);

			if (menuBorder) {
				tl.to(menuBorder, { scaleX: 1, duration: duration * 0.71 }, '<');
			}

			inst.enterEndTime = tl.duration();

			tl.addPause();

			tl.to([largeItems, smallItems], {
				autoAlpha: 0,
				duration: duration * 0.43,
			}, '<');

			// CLOSE shift (kebalikan dari OPEN).
			if (mainEl) {
				tl.to([mainEl, overlayEl], {
					x: 0,
					duration: duration * 0.86,
				}, '<');
			} else {
				tl.to(menuEl, {
					xPercent: 100,
					duration: duration * 0.86,
				}, '<');
				tl.to(overlayEl, {
					x: 0,
					duration: duration * 0.86,
				}, '<');
			}

			tl.to(darkEl, {
				autoAlpha: 0,
				duration: duration * 0.5,
				ease: 'power2.inOut',
			}, '<')

			.to(corners, {
				scale: 0,
				duration: duration * 0.71,
			}, '<');

			if (menuBorder) {
				tl.to(menuBorder, { scaleX: 0, duration: duration * 0.43 }, '<');
			}

			if (overlayBorders[0]) {
				tl.to(overlayBorders[0], { yPercent: -100, duration: duration * 0.71 }, '<');
			}
			if (overlayBorders[1]) {
				tl.to(overlayBorders[1], { yPercent: 100, duration: duration * 0.71 }, '<');
			}

			tl.to(toggleBtn, {
				color: closedColor,
				duration: duration * 0.36,
			}, '<+=0.1')

			.to(toggleLabels, {
				yPercent: 0,
				duration: duration * 0.36,
				ease: 'power3.in',
			}, '<')

			.to(toggleBars, {
				y: 0,
				rotation: 0,
				duration: duration * 0.36,
				ease: 'power3.in',
			}, '<')

			.set(overlayEl, {
				visibility: 'hidden',
				pointerEvents: 'none',
			});
		}

		function toggle() {
			inst.isOpen = !inst.isOpen;
			toggleBtn.setAttribute('aria-expanded', String(inst.isOpen));
			toggleBtn.setAttribute('aria-label', inst.isOpen ? 'close menu' : 'open menu');
			document.body.setAttribute('data-fun-menu-status', inst.isOpen ? 'open' : '');

			var tl = inst.timeline;
			if (inst.isOpen) {
				tl.invalidate();
				if (tl.time() >= inst.enterEndTime) tl.timeScale(1).restart();
				else tl.timeScale(1).play();
			} else {
				if (tl.time() < inst.enterEndTime) tl.timeScale(1).reverse();
				else tl.timeScale(1).play();
			}
		}

		buildTimeline();

		if (inEditor) {
			// Tampilkan menu dalam state "open" untuk styling convenience.
			// User bisa klik toggle untuk preview close → open animation.
			// Seek (bukan play) supaya state langsung di-apply tanpa animasi
			// muncul saat halaman pertama kali render.
			inst.timeline.time(inst.enterEndTime);
			inst.isOpen = true;
			toggleBtn.setAttribute('aria-expanded', 'true');
			toggleBtn.setAttribute('aria-label', 'close menu');
		}

		inst.onToggle = toggle;
		toggleBtn.addEventListener('click', inst.onToggle);

		inst.onOverlayClick = function () {
			if (inst.isOpen) toggle();
		};
		overlayEl.addEventListener('click', inst.onOverlayClick);

		inst.onKeydown = function (e) {
			if (e.key === 'Escape' && inst.isOpen) {
				toggle();
				toggleBtn.focus();
			}
		};
		document.addEventListener('keydown', inst.onKeydown);

		var resizeTimer;
		inst.onResize = function () {
			clearTimeout(resizeTimer);
			resizeTimer = setTimeout(function () {
				if (inst.isOpen) {
					if (mainEl) {
						gsap.set([mainEl, overlayEl], { x: getMenuOffset() });
					} else {
						gsap.set(overlayEl, { x: getMenuOffset() });
					}
				} else {
					inst.timeline.invalidate();
				}
			}, 150);
		};
		window.addEventListener('resize', inst.onResize);

		instances.set(root, inst);
	}

	function initAll(scope) {
		cleanupStale();
		var root = scope || document;
		if (typeof root.querySelectorAll !== 'function') return;
		root.querySelectorAll('[data-fun-root]').forEach(initOne);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () { initAll(); });
	} else {
		initAll();
	}

	if (window.elementorFrontend && window.elementorFrontend.hooks) {
		window.elementorFrontend.hooks.addAction(
			'frontend/element_ready/fixed_underlay_navigation.default',
			function ($scope) {
				var node = $scope && $scope[0];
				if (!node) return;
				initAll(node);
			}
		);
	}
})();
