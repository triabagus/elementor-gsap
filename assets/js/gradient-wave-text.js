(function () {
	'use strict';

	var pluginsRegistered = false;
	var instances = new Map();

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
			if (window.ScrollTrigger) plugins.push(ScrollTrigger);
			if (window.SplitText)     plugins.push(SplitText);
			if (plugins.length) gsap.registerPlugin.apply(gsap, plugins);
			pluginsRegistered = true;
			return true;
		} catch (_) {
			return false;
		}
	}

	function destroyInstance(el) {
		var inst = instances.get(el);
		if (!inst) return;
		if (inst.split && typeof inst.split.revert === 'function') {
			try { inst.split.revert(); } catch (_) {}
		}
		if (inst.ctx && typeof inst.ctx.revert === 'function') {
			try { inst.ctx.revert(); } catch (_) {}
		}
		instances.delete(el);
		delete el.dataset.egsapGwtInit;
	}

	function cleanupStale() {
		instances.forEach(function (_inst, el) {
			if (!el.isConnected) destroyInstance(el);
		});
	}

	function initOne(heading) {
		if (!heading) return;
		if (heading.dataset.egsapGwtInit === '1') return;
		heading.dataset.egsapGwtInit = '1';

		if (isEditorPreview()) {
			instances.set(heading, { editor: true });
			return;
		}

		if (typeof window.gsap === 'undefined' || typeof window.SplitText === 'undefined' || typeof window.ScrollTrigger === 'undefined') {
			console.warn('GSAP + SplitText + ScrollTrigger belum dimuat untuk Gradient Wave Text.');
			return;
		}
		ensurePlugins();
		var gsap = window.gsap;

		var scrollStart  = heading.getAttribute('data-gradient-wave-scroll-start') || 'top 90%';
		var scrollEnd    = heading.getAttribute('data-gradient-wave-scroll-end')   || 'center 40%';
		var startColor   = heading.getAttribute('data-gradient-wave-color-start')  || 'rgba(255, 255, 255, 0.2)';
		var waveColor    = heading.getAttribute('data-gradient-wave-color-wave')   || '#F84131';
		var waveDuration = parseFloat(heading.getAttribute('data-gradient-wave-duration')) || 0.4;
		var scrubValue   = parseFloat(heading.getAttribute('data-gradient-wave-scrub'));
		if (isNaN(scrubValue)) scrubValue = 0.1;
		var endColor     = getComputedStyle(heading).color;

		var record = { split: null, ctx: null };

		var split = new SplitText(heading, {
			type: 'words, chars',
			autoSplit: true,
			onSplit: function (self) {
				var chars       = self.chars;
				var activeChars = new Set();
				var progress    = { value: 0 };
				var isReady     = false;

				var syncChars = function () {
					var activeCount = Math.round(progress.value * chars.length);
					chars.forEach(function (char, index) {
						var isActive = index < activeCount;
						gsap.killTweensOf(char);
						gsap.set(char, { color: isActive ? endColor : startColor });
						if (isActive) activeChars.add(char);
						else activeChars.delete(char);
					});
				};

				var ctx = gsap.context(function () {
					gsap.set(chars, { color: startColor });

					gsap.to(progress, {
						value: 1,
						ease:  'none',
						scrollTrigger: {
							trigger:   heading,
							start:     scrollStart,
							end:       scrollEnd,
							scrub:     scrubValue,
							onRefresh: function () {
								isReady = false;
								syncChars();
								requestAnimationFrame(function () { isReady = true; });
							},
						},
						onUpdate: function () {
							if (!isReady) return;
							var activeCount = Math.round(progress.value * chars.length);
							chars.forEach(function (char, index) {
								var isActive = index < activeCount;
								if (isActive && !activeChars.has(char)) {
									activeChars.add(char);
									gsap.killTweensOf(char);
									gsap.timeline()
										.to(char, {
											color:    waveColor,
											duration: waveDuration * 0.3,
											ease:     'power2.out',
										})
										.to(char, {
											color:    endColor,
											duration: waveDuration * 0.7,
											ease:     'power2.in',
										});
								}
								if (!isActive && activeChars.has(char)) {
									activeChars.delete(char);
									gsap.killTweensOf(char);
									gsap.to(char, {
										color:    startColor,
										duration: waveDuration * 0.5,
										ease:     'none',
									});
								}
							});
						},
					});
				}, heading);

				record.ctx = ctx;
				return ctx;
			},
		});

		record.split = split;
		instances.set(heading, record);
	}

	function initAll(scope) {
		cleanupStale();
		(scope || document).querySelectorAll('[data-gradient-wave-text]').forEach(initOne);
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
