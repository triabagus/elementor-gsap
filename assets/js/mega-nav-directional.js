(function () {
	'use strict';

	function isEditorPreview() {
		return !!(window.elementorFrontend
			&& typeof window.elementorFrontend.isEditMode === 'function'
			&& window.elementorFrontend.isEditMode());
	}

	function initMegaNav(root) {
		if (!root) return;
		if (root.dataset.egsapMndInit === '1') return;
		root.dataset.egsapMndInit = '1';

		if (isEditorPreview()) return; // Skip animasi di editor iframe; render statis saja.
		if (typeof window.gsap === 'undefined') {
			console.warn('GSAP belum dimuat untuk Mega Navigation.');
			return;
		}
		var gsap = window.gsap;

		var DUR = {
			bgMorph:     0.4,
			contentIn:   0.3,
			contentOut:  0.2,
			stagger:     0.25,
			backdropIn:  0.3,
			backdropOut: 0.2,
			openScale:   0.35,
			closeScale:  0.25,
		};
		var HOVER_ENTER = 120;
		var HOVER_LEAVE = 150;

		// Scoped selectors — semua query di dalam root supaya multi-instance aman.
		var q  = function (sel) { return root.querySelector(sel); };
		var qa = function (sel) { return root.querySelectorAll(sel); };

		var menuWrap      = root; // root = [data-egsap-mnd-menu-open]
		var navList       = q('[data-egsap-mnd-nav-list]');
		var dropWrapper   = q('[data-egsap-mnd-dropdown-wrapper]');
		var dropContainer = q('[data-egsap-mnd-dropdown-container]');
		var backdrop      = q('[data-egsap-mnd-backdrop]');
		var toggles       = [].slice.call(qa('[data-egsap-mnd-dropdown-toggle]'));
		var panels        = [].slice.call(qa('[data-egsap-mnd-nav-content]'));
		var burger        = q('[data-egsap-mnd-burger-toggle]');
		var backBtn       = q('[data-egsap-mnd-mobile-back]');
		var logo          = q('[data-egsap-mnd-logo]');
		var lineTop = q('[data-egsap-mnd-burger-line="top"]');
		var lineMid = q('[data-egsap-mnd-burger-line="mid"]');
		var lineBot = q('[data-egsap-mnd-burger-line="bot"]');

		if (!dropWrapper || !dropContainer || !backdrop || !burger) return;

		var state = {
			isOpen: false,
			activePanel: null,
			activePanelIndex: -1,
			isMobile: window.innerWidth <= 991,
			mobileMenuOpen: false,
			mobilePanelActive: null,
			hoverTimer: null,
			leaveTimer: null,
			tl: null,
			mobileTl: null,
			mobilePanelTl: null,
		};

		var getPanel    = function (name) { return root.querySelector('[data-egsap-mnd-nav-content="' + name + '"]'); };
		var getToggle   = function (name) { return root.querySelector('[data-egsap-mnd-dropdown-toggle="' + name + '"]'); };
		var getFade     = function (el)   { return el.querySelectorAll('[data-egsap-mnd-fade]'); };
		var getNavItems = function ()     { return navList ? navList.querySelectorAll('[data-egsap-mnd-nav-list-item]') : []; };
		var getIndex    = function (name) { return toggles.indexOf(getToggle(name)); };
		var stagger     = function (n)    { return n <= 1 ? 0 : { amount: DUR.stagger }; };

		function clearTimers() {
			clearTimeout(state.hoverTimer);
			clearTimeout(state.leaveTimer);
			state.hoverTimer = state.leaveTimer = null;
		}

		function killTl(key) {
			if (state[key]) { state[key].kill(); state[key] = null; }
		}

		function killDropdown() {
			killTl('tl');
			gsap.killTweensOf(dropContainer);
			gsap.killTweensOf(backdrop);
			panels.forEach(function (p) { gsap.killTweensOf(p); gsap.killTweensOf(getFade(p)); });
		}

		function killMobile() {
			killTl('mobileTl');
			gsap.killTweensOf([ navList, lineTop, lineMid, lineBot ]);
		}

		function killMobilePanel() {
			killTl('mobilePanelTl');
			gsap.killTweensOf(getNavItems());
			gsap.killTweensOf([ backBtn, logo ]);
			panels.forEach(function (p) { gsap.killTweensOf(p); gsap.killTweensOf(getFade(p)); });
		}

		function resetToggles() {
			toggles.forEach(function (t) { t.setAttribute('aria-expanded', 'false'); });
		}

		function resetDesktop() {
			panels.forEach(function (p) {
				gsap.set(p, { visibility: 'hidden', opacity: 0, pointerEvents: 'none', x: 0, y: 0, xPercent: 0 });
				gsap.set(getFade(p), { autoAlpha: 0, x: 0, y: 0, xPercent: 0 });
			});
			gsap.set(dropContainer, { height: 0, clearProps: 'transform' });
			gsap.set(backdrop, { autoAlpha: 0 });
			menuWrap.setAttribute('data-egsap-mnd-menu-open', 'false');
			resetToggles();
		}

		function setupMobile() {
			panels.forEach(function (p) {
				gsap.set(p, { autoAlpha: 0, xPercent: 0, visibility: 'visible', pointerEvents: 'none' });
				gsap.set(getFade(p), { xPercent: 20, autoAlpha: 0 });
			});
			gsap.set(getNavItems(), { xPercent: 0, y: 0, autoAlpha: 1 });
			if (navList) gsap.set(navList, { autoAlpha: 0, x: 0 });
			if (backBtn) gsap.set(backBtn, { autoAlpha: 0 });
			if (logo)    gsap.set(logo, { autoAlpha: 1 });
			gsap.set(dropContainer, { clearProps: 'height' });
			gsap.set(backdrop, { autoAlpha: 0 });
		}

		function measurePanel(name) {
			var el = getPanel(name);
			if (!el) return 0;
			var s = el.style;
			var prev = [ s.visibility, s.opacity, s.pointerEvents ];
			s.visibility = 'visible';
			s.opacity = '0';
			s.pointerEvents = 'none';
			var h = el.getBoundingClientRect().height;
			s.visibility = prev[0];
			s.opacity = prev[1];
			s.pointerEvents = prev[2];
			return h;
		}

		function openDropdown(panelName) {
			if (state.isOpen && state.activePanel === panelName) return;
			if (state.isOpen) return switchPanel(state.activePanel, panelName);

			var height = measurePanel(panelName);
			if (!height) return;

			killDropdown();
			resetDesktop();

			var el = getPanel(panelName);
			var fade = getFade(el);
			var toggle = getToggle(panelName);

			state.isOpen = true;
			state.activePanel = panelName;
			state.activePanelIndex = getIndex(panelName);
			menuWrap.setAttribute('data-egsap-mnd-menu-open', 'true');
			if (toggle) toggle.setAttribute('aria-expanded', 'true');

			gsap.set(dropContainer, { height: 0 });

			var tl = gsap.timeline();
			state.tl = tl;
			tl.to(backdrop, { autoAlpha: 1, duration: DUR.backdropIn, ease: 'power2.out' }, 0);
			tl.to(dropContainer, { height: height, duration: DUR.openScale, ease: 'power3.out' }, 0);
			tl.set(el, { visibility: 'visible', opacity: 1, pointerEvents: 'auto' }, 0.05);
			if (fade.length) {
				tl.fromTo(fade,
					{ autoAlpha: 0, y: 8 },
					{ autoAlpha: 1, y: 0, duration: DUR.contentIn, stagger: stagger(fade.length), ease: 'power3.out' },
					0.1
				);
			}
		}

		function closeDropdown() {
			if (!state.isOpen) return;
			var el = getPanel(state.activePanel);
			var fade = el ? getFade(el) : [];

			killDropdown();

			var tl = gsap.timeline({
				onComplete: function () {
					state.isOpen = false;
					state.activePanel = null;
					state.activePanelIndex = -1;
					state.tl = null;
					resetDesktop();
				},
			});
			state.tl = tl;
			if (fade.length) tl.to(fade, { autoAlpha: 0, y: -4, duration: DUR.contentOut * 0.7, ease: 'power2.in' }, 0);
			tl.to(dropContainer, { height: 0, duration: DUR.closeScale, ease: 'power2.in' }, 0.05);
			tl.to(backdrop, { autoAlpha: 0, duration: DUR.backdropOut, ease: 'power2.out' }, 0);
			if (el) tl.set(el, { visibility: 'hidden', opacity: 0, pointerEvents: 'none' });
		}

		function switchPanel(fromName, toName) {
			var dir = getIndex(toName) > getIndex(fromName) ? 1 : -1;
			var fromEl = getPanel(fromName), toEl = getPanel(toName);
			if (!fromEl || !toEl) return;

			var fromFade = getFade(fromEl), toFade = getFade(toEl);
			var toHeight = measurePanel(toName);
			if (!toHeight) return;

			killDropdown();

			panels.forEach(function (p) {
				gsap.set(p, { visibility: 'hidden', opacity: 0, pointerEvents: 'none', xPercent: 0 });
				gsap.set(getFade(p), { autoAlpha: 0, x: 0, y: 0 });
			});
			gsap.set(fromEl, { visibility: 'visible', opacity: 1, pointerEvents: 'auto', x: 0 });
			if (fromFade.length) gsap.set(fromFade, { autoAlpha: 1, x: 0, y: 0 });
			gsap.set(backdrop, { autoAlpha: 1 });

			var toToggle = getToggle(toName);
			state.activePanel = toName;
			state.activePanelIndex = getIndex(toName);
			resetToggles();
			if (toToggle) toToggle.setAttribute('aria-expanded', 'true');

			var xOut = dir * -30, xIn = dir * 30;
			var tl = gsap.timeline();
			state.tl = tl;

			if (fromFade.length) tl.to(fromFade, { autoAlpha: 0, x: xOut, duration: DUR.contentOut, ease: 'power2.in' }, 0);
			tl.set(fromEl, { visibility: 'hidden', opacity: 0, pointerEvents: 'none', xPercent: 0 }, DUR.contentOut);
			if (fromFade.length) tl.set(fromFade, { x: 0 }, DUR.contentOut);
			tl.to(dropContainer, { height: toHeight, duration: DUR.bgMorph, ease: 'power3.out' }, 0.05);
			tl.set(toEl, { visibility: 'visible', opacity: 1, pointerEvents: 'auto', xPercent: 0 }, DUR.contentOut * 0.5);
			if (toFade.length) {
				tl.fromTo(toFade,
					{ autoAlpha: 0, x: xIn },
					{ autoAlpha: 1, x: 0, duration: DUR.contentIn, stagger: stagger(toFade.length), ease: 'power3.out' },
					DUR.contentOut * 0.6
				);
			}
		}

		function handleToggleEnter(e) {
			if (state.isMobile) return;
			var name = e.currentTarget.getAttribute('data-egsap-mnd-dropdown-toggle');
			if (!name) return;
			clearTimeout(state.leaveTimer); state.leaveTimer = null;
			clearTimeout(state.hoverTimer);
			state.hoverTimer = setTimeout(function () { openDropdown(name); }, state.isOpen ? 0 : HOVER_ENTER);
		}

		function handleToggleLeave() {
			if (state.isMobile) return;
			clearTimeout(state.hoverTimer); state.hoverTimer = null;
			state.leaveTimer = setTimeout(closeDropdown, HOVER_LEAVE);
		}

		function handleWrapperEnter() {
			if (state.isMobile) return;
			clearTimeout(state.leaveTimer); state.leaveTimer = null;
		}

		function handleWrapperLeave() {
			if (state.isMobile) return;
			state.leaveTimer = setTimeout(closeDropdown, HOVER_LEAVE);
		}

		function handleEscape(e) {
			if (e.key !== 'Escape') return;
			if (state.isMobile) {
				if (state.mobilePanelActive) closeMobilePanel();
				else if (state.mobileMenuOpen) closeMobileMenu();
				return;
			}
			if (state.isOpen) {
				var t = getToggle(state.activePanel);
				closeDropdown();
				if (t) t.focus();
			}
		}

		function handleDocClick(e) {
			if (state.isMobile || !state.isOpen) return;
			if (!root.contains(e.target)) closeDropdown();
		}

		function focusFirstLink(panelName) {
			setTimeout(function () {
				var el = getPanel(panelName);
				if (!el) return;
				var link = el.querySelector('a');
				if (!link) return;
				gsap.set(link, { visibility: 'visible' });
				link.focus();
			}, 80);
		}

		function handleKeydownOnToggle(e) {
			if (state.isMobile) return;
			var name = e.currentTarget.getAttribute('data-egsap-mnd-dropdown-toggle');
			if (e.key === 'Enter' || e.key === ' ') {
				e.preventDefault();
				if (state.isOpen && state.activePanel === name) closeDropdown();
				else { openDropdown(name); focusFirstLink(name); }
				return;
			}
			if (e.key === 'ArrowDown') {
				e.preventDefault();
				if (!state.isOpen || state.activePanel !== name) openDropdown(name);
				focusFirstLink(name);
			}
			if (e.key === 'Tab' && !e.shiftKey && state.isOpen && state.activePanel === name) {
				e.preventDefault();
				var panelEl = getPanel(name);
				var link = panelEl ? panelEl.querySelector('a') : null;
				if (link) link.focus();
			}
		}

		function handleKeydownInPanel(e) {
			if (state.isMobile || !state.isOpen) return;
			var el = getPanel(state.activePanel);
			if (!el) return;
			var links = [].slice.call(el.querySelectorAll('a'));
			var idx = links.indexOf(document.activeElement);
			if (e.key === 'ArrowDown') {
				e.preventDefault();
				links[(idx + 1) % links.length].focus();
			}
			if (e.key === 'ArrowUp') {
				e.preventDefault();
				if (idx <= 0) { var t = getToggle(state.activePanel); if (t) t.focus(); }
				else links[idx - 1].focus();
			}
			if (e.key === 'Tab' && !e.shiftKey && idx === links.length - 1) {
				e.preventDefault();
				var curIdx = toggles.indexOf(getToggle(state.activePanel));
				var next = curIdx < toggles.length - 1 ? toggles[curIdx + 1] : null;
				closeDropdown();
				if (next) next.focus();
			}
			if (e.key === 'Tab' && e.shiftKey && idx === 0) {
				e.preventDefault();
				var t2 = getToggle(state.activePanel);
				if (t2) t2.focus();
			}
		}

		function animateBurger(toX) {
			var tl = gsap.timeline({ defaults: { ease: 'power2.inOut' } });
			if (toX) {
				tl.to(lineTop, { y: '0.3125em', duration: 0.15 }, 0);
				tl.to(lineBot, { y: '-0.3125em', duration: 0.15 }, 0);
				tl.to(lineMid, { autoAlpha: 0, duration: 0.1 }, 0.1);
				tl.to(lineTop, { rotation: 45, duration: 0.2 }, 0.15);
				tl.to(lineBot, { rotation: -45, duration: 0.2 }, 0.15);
			} else {
				tl.to(lineTop, { rotation: 0, duration: 0.2 }, 0);
				tl.to(lineBot, { rotation: 0, duration: 0.2 }, 0);
				tl.to(lineTop, { y: 0, duration: 0.15 }, 0.15);
				tl.to(lineBot, { y: 0, duration: 0.15 }, 0.15);
				tl.to(lineMid, { autoAlpha: 1, duration: 0.1 }, 0.15);
			}
			return tl;
		}

		function openMobileMenu() {
			killMobile();
			state.mobileMenuOpen = true;
			menuWrap.setAttribute('data-egsap-mnd-menu-open', 'true');
			burger.setAttribute('aria-expanded', 'true');
			document.body.style.overflow = 'hidden';

			var items = getNavItems();
			var tl = gsap.timeline();
			state.mobileTl = tl;
			tl.add(animateBurger(true), 0);
			tl.to(navList, { autoAlpha: 1, duration: 0.3, ease: 'power2.out' }, 0);
			if (items.length) {
				tl.fromTo(items,
					{ autoAlpha: 0, y: 12 },
					{ autoAlpha: 1, y: 0, duration: 0.3, stagger: 0.04, ease: 'power3.out' },
					0.15
				);
			}
		}

		function closeMobileMenu() {
			var hadPanel = state.mobilePanelActive;
			var panelEl = hadPanel ? getPanel(hadPanel) : null;

			killMobile();
			killMobilePanel();

			menuWrap.setAttribute('data-egsap-mnd-menu-open', 'false');
			state.mobileMenuOpen = false;
			state.mobilePanelActive = null;
			burger.setAttribute('aria-expanded', 'false');

			var tl = gsap.timeline({
				onComplete: function () {
					document.body.style.overflow = '';
					state.mobileTl = null;
					setupMobile();
				},
			});
			state.mobileTl = tl;
			tl.add(animateBurger(false), 0);
			if (hadPanel && panelEl) {
				tl.to(panelEl, { autoAlpha: 0, duration: 0.3, ease: 'power2.inOut' }, 0.05);
				if (backBtn) tl.to(backBtn, { autoAlpha: 0, duration: 0.2, ease: 'power2.in' }, 0.05);
			}
			if (navList) tl.to(navList, { autoAlpha: 0, duration: 0.3, ease: 'power2.inOut' }, 0.05);
		}

		function openMobilePanel(panelName) {
			var el = getPanel(panelName);
			if (!el) return;
			killMobilePanel();
			state.mobilePanelActive = panelName;

			var navItems = getNavItems();
			var panelFade = getFade(el);

			var tl = gsap.timeline();
			state.mobilePanelTl = tl;

			if (navItems.length) {
				tl.to(navItems, {
					xPercent: -10, autoAlpha: 0,
					duration: 0.35, stagger: 0.03, ease: 'power2.in',
				}, 0);
			}
			if (logo)    tl.to(logo, { autoAlpha: 0, duration: 0.2, ease: 'power2.in' }, 0);
			if (backBtn) tl.to(backBtn, { autoAlpha: 1, duration: 0.25, ease: 'power2.inOut' }, 0.15);

			tl.set(el, { autoAlpha: 1, xPercent: 0, pointerEvents: 'auto' }, 0.2);
			if (panelFade.length) {
				tl.fromTo(panelFade,
					{ xPercent: 8, autoAlpha: 0 },
					{ xPercent: 0, autoAlpha: 1, duration: 0.3, stagger: stagger(panelFade.length), ease: 'power3.out' },
					0.25
				);
			}
		}

		function closeMobilePanel() {
			if (!state.mobilePanelActive) return;
			var el = getPanel(state.mobilePanelActive);
			if (!el) return;
			killMobilePanel();

			var navItems = getNavItems();

			var tl = gsap.timeline({
				onComplete: function () { state.mobilePanelActive = null; state.mobilePanelTl = null; },
			});
			state.mobilePanelTl = tl;

			tl.to(el, { xPercent: 20, autoAlpha: 0, duration: 0.3, ease: 'power2.in' }, 0);
			tl.set(el, { autoAlpha: 0, pointerEvents: 'none' }, 0.25);

			if (backBtn) tl.to(backBtn, { autoAlpha: 0, duration: 0.2, ease: 'power2.in' }, 0);
			if (logo)    tl.to(logo, { autoAlpha: 1, duration: 0.25, ease: 'power2.out' }, 0.15);

			if (navItems.length) {
				tl.fromTo(navItems,
					{ xPercent: -20, autoAlpha: 0 },
					{ xPercent: 0, autoAlpha: 1, duration: 0.35, stagger: 0.03, ease: 'power3.out' },
					0.25
				);
			}
		}

		function handleToggleClick(e) {
			if (!state.isMobile || !state.mobileMenuOpen) return;
			var name = e.currentTarget.getAttribute('data-egsap-mnd-dropdown-toggle');
			if (name) { e.preventDefault(); openMobilePanel(name); }
		}

		var resizeTimer = null;
		var lastWidth = window.innerWidth;
		function handleResize() {
			var w = window.innerWidth;
			if (w === lastWidth) return;
			lastWidth = w;
			clearTimeout(resizeTimer);
			resizeTimer = setTimeout(function () {
				var was = state.isMobile;
				state.isMobile = window.innerWidth <= 991;

				if (was && !state.isMobile) {
					killMobile(); killMobilePanel();
					if (navList) gsap.set(navList, { clearProps: 'all' });
					gsap.set(getNavItems(), { clearProps: 'all' });
					if (backBtn) gsap.set(backBtn, { autoAlpha: 0 });
					if (logo)    gsap.set(logo, { clearProps: 'all' });
					gsap.set([ lineTop, lineMid, lineBot ], { rotation: 0, y: 0, autoAlpha: 1 });
					panels.forEach(function (p) {
						gsap.set(p, { clearProps: 'all' });
						gsap.set(getFade(p), { clearProps: 'all' });
					});
					burger.setAttribute('aria-expanded', 'false');
					state.mobileMenuOpen = false;
					state.mobilePanelActive = null;
					document.body.style.overflow = '';
					resetDesktop();
				}
				if (!was && state.isMobile) {
					killDropdown();
					state.isOpen = false; state.activePanel = null; state.activePanelIndex = -1;
					clearTimers();
					menuWrap.setAttribute('data-egsap-mnd-menu-open', 'false');
					resetToggles();
					setupMobile();
				}
			}, 150);
		}

		toggles.forEach(function (btn) {
			btn.addEventListener('mouseenter', handleToggleEnter);
			btn.addEventListener('mouseleave', handleToggleLeave);
			btn.addEventListener('keydown', handleKeydownOnToggle);
			btn.addEventListener('click', handleToggleClick);
		});

		dropWrapper.addEventListener('mouseenter', handleWrapperEnter);
		dropWrapper.addEventListener('mouseleave', handleWrapperLeave);
		panels.forEach(function (p) { p.addEventListener('keydown', handleKeydownInPanel); });
		backdrop.addEventListener('click', closeDropdown);
		document.addEventListener('keydown', handleEscape);
		document.addEventListener('click', handleDocClick);
		burger.addEventListener('click', function () {
			if (state.mobileMenuOpen) closeMobileMenu(); else openMobileMenu();
		});
		if (backBtn) backBtn.addEventListener('click', closeMobilePanel);
		window.addEventListener('resize', handleResize);

		if (state.isMobile) setupMobile(); else resetDesktop();
	}

	function initAll(scope) {
		(scope || document).querySelectorAll('[data-egsap-mnd]').forEach(initMegaNav);
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
