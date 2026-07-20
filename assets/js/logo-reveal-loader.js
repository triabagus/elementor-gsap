(function () {
	'use strict';

	var pluginsRegistered = false;
	var customEaseRegistered = false;

	function isEditorPreview() {
		return !!(window.elementorFrontend
			&& typeof window.elementorFrontend.isEditMode === 'function'
			&& window.elementorFrontend.isEditMode());
	}

	function ensurePlugins() {
		if (pluginsRegistered) return true;
		if (typeof window.gsap === 'undefined') return false;
		try {
			var plugins = [];
			if (window.CustomEase) plugins.push(CustomEase);
			if (window.SplitText)  plugins.push(SplitText);
			if (plugins.length) gsap.registerPlugin.apply(gsap, plugins);
			if (window.CustomEase && !customEaseRegistered) {
				if (typeof CustomEase.get !== 'function' || !CustomEase.get('loader')) {
					CustomEase.create('loader', '0.65, 0.01, 0.05, 0.99');
				}
				customEaseRegistered = true;
			}
			pluginsRegistered = true;
			return true;
		} catch (_) {
			return false;
		}
	}

	function initLogoRevealLoader(wrap) {
		if (!wrap) return;
		if (wrap.dataset.egsapLrlInit === '1') return;
		wrap.dataset.egsapLrlInit = '1';

		if (isEditorPreview()) {
			// Extension render_loader() sudah skip di editor. Guard extra
			// kalau ternyata masuk juga.
			wrap.style.display = 'none';
			return;
		}

		if (typeof window.gsap === 'undefined') {
			console.warn('GSAP belum dimuat untuk Logo Reveal Loader.');
			return;
		}
		ensurePlugins();
		var gsap = window.gsap;

		var container    = wrap.querySelector('[data-load-container]');
		var bg           = wrap.querySelector('[data-load-bg]');
		var progressBar  = wrap.querySelector('[data-load-progress]');
		var logo         = wrap.querySelector('[data-load-logo]');
		var textElements = Array.prototype.slice.call(wrap.querySelectorAll('[data-load-text]'));
		var resetTargets = Array.prototype.slice.call(wrap.querySelectorAll('[data-load-reset]:not([data-load-text])'));

		var mainDuration = parseFloat(wrap.dataset.egsapLrlMainDuration);
		if (isNaN(mainDuration) || mainDuration <= 0) mainDuration = 3;
		var exitDuration = parseFloat(wrap.dataset.egsapLrlExitDuration);
		if (isNaN(exitDuration) || exitDuration <= 0) exitDuration = 1;

		document.body.setAttribute('data-egsap-lrl-status', 'loading');

		var loadTimeline = gsap
			.timeline({
				defaults: {
					ease:     'loader',
					duration: mainDuration,
				},
				onComplete: function () {
					document.body.setAttribute('data-egsap-lrl-status', 'done');
					wrap.style.pointerEvents = 'none';
				},
			})
			.set(wrap, { display: 'block' })
			.to(progressBar, { scaleX: 1 })
			.to(logo, { clipPath: 'inset(0% 0% 0% 0%)' }, '<')
			.to(container, { autoAlpha: 0, duration: 0.5 })
			.to(progressBar, { scaleX: 0, transformOrigin: 'right center', duration: 0.5 }, '<')
			.add('hideContent', '<')
			.to(bg, { yPercent: -101, duration: exitDuration }, 'hideContent')
			.set(wrap, { display: 'none' });

		if (resetTargets.length) {
			loadTimeline.set(resetTargets, { autoAlpha: 1 }, 0);
		}

		if (textElements.length >= 2 && typeof window.SplitText !== 'undefined') {
			var firstWord  = new SplitText(textElements[0], { type: 'lines,chars', mask: 'lines' });
			var secondWord = new SplitText(textElements[1], { type: 'lines,chars', mask: 'lines' });

			gsap.set([ firstWord.chars, secondWord.chars ], { autoAlpha: 0, yPercent: 125 });
			gsap.set(textElements, { autoAlpha: 1 });

			// first text in
			loadTimeline.to(
				firstWord.chars,
				{
					autoAlpha: 1,
					yPercent:  0,
					duration:  0.6,
					stagger:   { each: 0.02 },
				},
				0
			);

			// first text out while second text in
			loadTimeline.to(
				firstWord.chars,
				{
					autoAlpha: 0,
					yPercent:  -125,
					duration:  0.4,
					stagger:   { each: 0.02 },
				},
				'>+=0.4'
			);

			loadTimeline.to(
				secondWord.chars,
				{
					autoAlpha: 1,
					yPercent:  0,
					duration:  0.6,
					stagger:   { each: 0.02 },
				},
				'<'
			);

			// second text out
			loadTimeline.to(
				secondWord.chars,
				{
					autoAlpha: 0,
					yPercent:  -125,
					duration:  0.4,
					stagger:   { each: 0.02 },
				},
				'hideContent-=0.5'
			);
		}
	}

	function initAll(scope) {
		(scope || document).querySelectorAll('[data-load-wrap][data-egsap-lrl]').forEach(initLogoRevealLoader);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () { initAll(); });
	} else {
		initAll();
	}

	if (window.elementorFrontend && window.elementorFrontend.hooks) {
		window.elementorFrontend.hooks.addAction('frontend/init', function () { initAll(document); });
	}
})();
