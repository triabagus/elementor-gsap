(function () {
	'use strict';

	function isEditorPreview() {
		return !!(window.elementorFrontend
			&& typeof window.elementorFrontend.isEditMode === 'function'
			&& window.elementorFrontend.isEditMode());
	}

	function debounce(fn, delay) {
		var t;
		return function () { clearTimeout(t); t = setTimeout(fn, delay); };
	}

	function closeAllDropdowns(root) {
		root.querySelectorAll('[data-egsap-mln-dropdown-toggle]').forEach(function (t) {
			t.dataset.egsapMlnDropdownToggle = 'closed';
			t.setAttribute('aria-expanded', 'false');
			var dd = t.nextElementSibling;
			if (dd && dd.classList.contains('egsap-mln__dropdown')) {
				dd.setAttribute('aria-hidden', 'true');
			}
		});
	}

	function initMobileMenu(root, uid) {
		var btn = root.querySelector('[data-egsap-mln-menu-btn]');
		if (!btn) return;
		btn.setAttribute('aria-expanded', 'false');
		btn.setAttribute('aria-controls', 'egsap-mln-nav-' + uid);
		root.setAttribute('id', 'egsap-mln-nav-' + uid);
		root.setAttribute('role', 'navigation');
		root.setAttribute('aria-label', 'Main navigation');

		if (!btn._egsapMlnMobileClick) {
			btn._egsapMlnMobileClick = true;
			btn.addEventListener('click', function () {
				var open = root.dataset.egsapMlnStatus === 'open';
				root.dataset.egsapMlnStatus = open ? 'closed' : 'open';
				btn.setAttribute('aria-expanded', String(!open));
				document.body.style.overflow = open ? '' : 'hidden';
				if (open) closeAllDropdowns(root);
			});
		}

		var toggles = Array.from(root.querySelectorAll('[data-egsap-mln-dropdown-toggle]'));
		toggles.forEach(function (toggle, i) {
			var dd = toggle.nextElementSibling;
			if (!dd || !dd.classList.contains('egsap-mln__dropdown')) return;
			if (toggle._egsapMlnMobileInit) return;
			toggle._egsapMlnMobileInit = true;

			toggle.setAttribute('aria-expanded', 'false');
			toggle.setAttribute('aria-haspopup', 'true');
			toggle.setAttribute('aria-controls', 'egsap-mln-dd-' + uid + '-' + i);
			dd.setAttribute('id', 'egsap-mln-dd-' + uid + '-' + i);
			dd.setAttribute('role', 'menu');
			dd.querySelectorAll('.egsap-mln__dropdown-link').forEach(function (l) {
				l.setAttribute('role', 'menuitem');
			});

			toggle.addEventListener('click', function () {
				var open = toggle.dataset.egsapMlnDropdownToggle === 'open';
				toggles.forEach(function (other) {
					if (other !== toggle) {
						other.dataset.egsapMlnDropdownToggle = 'closed';
						other.setAttribute('aria-expanded', 'false');
					}
				});
				toggle.dataset.egsapMlnDropdownToggle = open ? 'closed' : 'open';
				toggle.setAttribute('aria-expanded', String(!open));
			});
		});
	}

	function initDesktopDropdowns(root, uid) {
		var toggles = Array.from(root.querySelectorAll('[data-egsap-mln-dropdown-toggle]'));
		var plainLinks = Array.from(root.querySelectorAll('.egsap-mln__link')).filter(function (l) {
			return !l.hasAttribute('data-egsap-mln-dropdown-toggle');
		});

		toggles.forEach(function (toggle, i) {
			var dd = toggle.nextElementSibling;
			if (!dd || !dd.classList.contains('egsap-mln__dropdown')) return;
			if (toggle._egsapMlnDesktopInit) return;
			toggle._egsapMlnDesktopInit = true;

			toggle.setAttribute('aria-expanded', 'false');
			toggle.setAttribute('aria-haspopup', 'true');
			toggle.setAttribute('aria-controls', 'egsap-mln-desktop-dd-' + uid + '-' + i);
			dd.setAttribute('id', 'egsap-mln-desktop-dd-' + uid + '-' + i);
			dd.setAttribute('role', 'menu');
			dd.setAttribute('aria-hidden', 'true');
			dd.querySelectorAll('.egsap-mln__dropdown-link').forEach(function (l) {
				l.setAttribute('role', 'menuitem');
			});

			toggle.addEventListener('click', function (e) {
				e.preventDefault();
				toggles.forEach(function (other) {
					if (other !== toggle) {
						other.dataset.egsapMlnDropdownToggle = 'closed';
						other.setAttribute('aria-expanded', 'false');
						var otherDd = other.nextElementSibling;
						if (otherDd) otherDd.setAttribute('aria-hidden', 'true');
					}
				});
				var wasOpen = toggle.dataset.egsapMlnDropdownToggle === 'open';
				toggle.dataset.egsapMlnDropdownToggle = wasOpen ? 'closed' : 'open';
				toggle.setAttribute('aria-expanded', String(!wasOpen));
				dd.setAttribute('aria-hidden', String(wasOpen));
				if (!wasOpen) {
					var first = dd.querySelector('.egsap-mln__dropdown-link');
					if (first) first.focus();
				}
			});

			toggle.addEventListener('mouseenter', function () {
				toggles.forEach(function (other) {
					if (other !== toggle) {
						other.dataset.egsapMlnDropdownToggle = 'closed';
						other.setAttribute('aria-expanded', 'false');
						var otherDd = other.nextElementSibling;
						if (otherDd) otherDd.setAttribute('aria-hidden', 'true');
					}
				});
				toggle.dataset.egsapMlnDropdownToggle = 'open';
				toggle.setAttribute('aria-expanded', 'true');
				dd.setAttribute('aria-hidden', 'false');
			});

			dd.addEventListener('mouseleave', function () {
				toggle.dataset.egsapMlnDropdownToggle = 'closed';
				toggle.setAttribute('aria-expanded', 'false');
				dd.setAttribute('aria-hidden', 'true');
			});

			toggle.addEventListener('keydown', function (e) {
				if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggle.click(); }
				else if (e.key === 'Escape') {
					toggle.dataset.egsapMlnDropdownToggle = 'closed';
					toggle.setAttribute('aria-expanded', 'false');
					dd.setAttribute('aria-hidden', 'true');
					toggle.focus();
				}
			});

			dd.addEventListener('keydown', function (e) {
				var items = Array.from(dd.querySelectorAll('.egsap-mln__dropdown-link'));
				var idx = items.indexOf(document.activeElement);
				if (e.key === 'ArrowDown') { e.preventDefault(); items[(idx + 1) % items.length].focus(); }
				else if (e.key === 'ArrowUp') { e.preventDefault(); items[(idx - 1 + items.length) % items.length].focus(); }
				else if (e.key === 'Escape') {
					e.preventDefault();
					toggle.dataset.egsapMlnDropdownToggle = 'closed';
					toggle.setAttribute('aria-expanded', 'false');
					dd.setAttribute('aria-hidden', 'true');
					toggle.focus();
				}
			});
		});

		plainLinks.forEach(function (link) {
			link.addEventListener('mouseenter', function () { closeAllDropdowns(root); });
		});

		document.addEventListener('click', function (e) {
			var inside = toggles.some(function (toggle) {
				var dd = toggle.nextElementSibling;
				return toggle.contains(e.target) || (dd && dd.contains(e.target));
			});
			if (!inside) closeAllDropdowns(root);
		});
	}

	var UID_SEED = 0;
	function initMultilevelNav(root) {
		if (!root) return;
		if (root.dataset.egsapMlnInit === '1') return;
		root.dataset.egsapMlnInit = '1';
		if (isEditorPreview()) return;

		var uid = String(++UID_SEED);
		var state = { mode: null };

		function setup() {
			var isMobile = window.innerWidth < 768;
			if (isMobile && state.mode !== 'mobile') {
				initMobileMenu(root, uid);
				state.mode = 'mobile';
			} else if (!isMobile && state.mode !== 'desktop') {
				initDesktopDropdowns(root, uid);
				state.mode = 'desktop';
				// Kalau resize dari mobile → desktop, reset drawer + scroll lock.
				root.dataset.egsapMlnStatus = 'closed';
				document.body.style.overflow = '';
				closeAllDropdowns(root);
			}
		}

		setup();
		window.addEventListener('resize', debounce(setup, 200));

		document.addEventListener('keydown', function (e) {
			if (e.key !== 'Escape') return;
			if (root.dataset.egsapMlnStatus === 'open') {
				root.dataset.egsapMlnStatus = 'closed';
				var btn = root.querySelector('[data-egsap-mln-menu-btn]');
				if (btn) btn.setAttribute('aria-expanded', 'false');
				document.body.style.overflow = '';
				closeAllDropdowns(root);
			}
		});
	}

	function initAll(scope) {
		(scope || document).querySelectorAll('[data-egsap-mln]').forEach(initMultilevelNav);
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
