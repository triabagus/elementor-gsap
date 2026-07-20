(function () {
	'use strict';

	var instances = new Map();

	function isEditorPreview() {
		return !!(window.elementorFrontend
			&& typeof window.elementorFrontend.isEditMode === 'function'
			&& window.elementorFrontend.isEditMode());
	}

	function destroyInstance(wrap) {
		var inst = instances.get(wrap);
		if (!inst) return;
		if (inst.trigger) { try { inst.trigger.kill(); } catch (_) {} }
		instances.delete(wrap);
		delete wrap.dataset.egsapSfInit;
	}

	function cleanupStale() {
		instances.forEach(function (_inst, wrap) {
			if (!wrap.isConnected) destroyInstance(wrap);
		});
	}

	function initStickyFeatures(wrap) {
		if (!wrap) return;
		if (wrap.dataset.egsapSfInit === '1') return;
		wrap.dataset.egsapSfInit = '1';

		// Editor preview: skip animation — CSS override sudah stack semua
		// item vertikal & visible untuk editing.
		if (isEditorPreview()) {
			instances.set(wrap, { editor: true });
			return;
		}

		if (typeof window.gsap === 'undefined' || typeof window.ScrollTrigger === 'undefined') {
			console.warn('GSAP + ScrollTrigger belum dimuat untuk Sticky Features.');
			return;
		}
		var gsap          = window.gsap;
		var ScrollTrigger = window.ScrollTrigger;
		try { gsap.registerPlugin(ScrollTrigger); } catch (_) {}

		var visualWraps = Array.prototype.slice.call(wrap.querySelectorAll('[data-sticky-feature-visual-wrap]'));
		var items       = Array.prototype.slice.call(wrap.querySelectorAll('[data-sticky-feature-item]'));
		var progressBar = wrap.querySelector('[data-sticky-feature-progress]');

		if (visualWraps.length !== items.length) {
			console.warn('[Sticky Features] visualWraps and items count do not match:', {
				visualWraps: visualWraps.length,
				items: items.length,
				wrap: wrap,
			});
		}

		var count = Math.min(visualWraps.length, items.length);
		if (count < 1) return;

		var rm       = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
		var respect  = wrap.getAttribute('data-sticky-feature-respect-rm') !== 'false';
		var duration = parseFloat(wrap.dataset.stickyFeatureDuration);
		if (isNaN(duration) || duration <= 0) duration = 0.75;
		if (respect && rm) duration = 0.01;

		var scrollAmount = parseFloat(wrap.dataset.stickyFeatureScrollAmount);
		if (isNaN(scrollAmount) || scrollAmount <= 0 || scrollAmount > 1) scrollAmount = 0.9;

		var radius = wrap.dataset.stickyFeatureRadius || '0.75em';

		var EASE = 'power4.inOut';

		function getTexts(el) {
			return Array.prototype.slice.call(el.querySelectorAll('[data-sticky-feature-text]'));
		}

		if (visualWraps[0]) gsap.set(visualWraps[0], { clipPath: 'inset(0% round ' + radius + ')' });
		gsap.set(items[0], { autoAlpha: 1 });

		var currentIndex = 0;

		function transition(fromIndex, toIndex) {
			if (fromIndex === toIndex) return;
			var tl = gsap.timeline({ defaults: { overwrite: 'auto' } });

			if (fromIndex < toIndex) {
				tl.to(visualWraps[toIndex], {
					clipPath: 'inset(0% round ' + radius + ')',
					duration: duration,
					ease:     EASE,
				}, 0);
			} else {
				tl.to(visualWraps[fromIndex], {
					clipPath: 'inset(50% round ' + radius + ')',
					duration: duration,
					ease:     EASE,
				}, 0);
			}
			animateOut(items[fromIndex]);
			animateIn(items[toIndex]);
		}

		function animateOut(itemEl) {
			var texts = getTexts(itemEl);
			gsap.to(texts, {
				autoAlpha:  0,
				y:          -30,
				ease:       'power4.out',
				duration:   0.4,
				onComplete: function () { gsap.set(itemEl, { autoAlpha: 0 }); },
			});
		}

		function animateIn(itemEl) {
			var texts = getTexts(itemEl);
			gsap.set(itemEl, { autoAlpha: 1 });
			gsap.fromTo(texts, {
				autoAlpha: 0,
				y:         30,
			}, {
				autoAlpha: 1,
				y:         0,
				ease:      'power4.out',
				duration:  duration,
				stagger:   0.1,
			});
		}

		var steps = Math.max(1, count - 1);

		var st = ScrollTrigger.create({
			trigger:              wrap,
			start:                'center center',
			end:                  function () { return '+=' + ( steps * 100 ) + '%'; },
			pin:                  true,
			scrub:                true,
			invalidateOnRefresh:  true,
			onUpdate: function (self) {
				var p   = Math.min(self.progress, scrollAmount) / scrollAmount;
				var idx = Math.floor(p * steps + 1e-6);
				idx     = Math.max(0, Math.min(steps, idx));

				if (progressBar) {
					gsap.to(progressBar, { scaleX: p, ease: 'none' });
				}

				if (idx !== currentIndex) {
					transition(currentIndex, idx);
					currentIndex = idx;
				}
			},
		});

		instances.set(wrap, { trigger: st });

		// Elementor kadang render section/container SETELAH DOMContentLoaded
		// (mis. lazy container, hero image loading). ScrollTrigger.refresh()
		// memaksa recalculation start/end position setelah layout stabil.
		// Delay 100ms + refresh saat image cover load = defensive dual-trigger.
		setTimeout(function () {
			try { ScrollTrigger.refresh(); } catch (_) {}
		}, 100);

		var firstImg = wrap.querySelector('.sticky-features__img');
		if (firstImg && !firstImg.complete) {
			firstImg.addEventListener('load', function () {
				try { ScrollTrigger.refresh(); } catch (_) {}
			}, { once: true });
		}
	}

	function initAll(root) {
		cleanupStale();
		var scope = root || document;
		scope.querySelectorAll('[data-sticky-feature-wrap]').forEach(initStickyFeatures);
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
