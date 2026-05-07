(function () {
	'use strict';

	var splitConfig = {
		lines: { duration: 0.8, stagger: 0.08 },
		words: { duration: 0.6, stagger: 0.06 },
		chars: { duration: 0.4, stagger: 0.01 },
	};

	var instances = new Map();

	function isEditorPreview() {
		return !!(window.elementorFrontend
			&& typeof window.elementorFrontend.isEditMode === 'function'
			&& window.elementorFrontend.isEditMode());
	}

	function ensurePlugins() {
		if (typeof window.gsap === 'undefined') return false;
		if (typeof window.SplitText === 'undefined' || typeof window.ScrollTrigger === 'undefined') {
			return false;
		}
		gsap.registerPlugin(SplitText, ScrollTrigger);
		return true;
	}

	function destroyInstance(heading) {
		var inst = instances.get(heading);
		if (!inst) return;
		if (inst.tween) { try { inst.tween.kill(); } catch (_) {} }
		if (inst.scrollTrigger) { try { inst.scrollTrigger.kill(); } catch (_) {} }
		if (inst.split && typeof inst.split.revert === 'function') {
			try { inst.split.revert(); } catch (_) {}
		}
		if (window.gsap) { try { gsap.killTweensOf(heading); } catch (_) {} }
		instances.delete(heading);
		delete heading.dataset.emtInit;
	}

	function cleanupStale() {
		instances.forEach(function (_inst, heading) {
			if (!heading.isConnected) destroyInstance(heading);
		});
	}

	function initOne(heading) {
		if (heading.dataset.emtInit === '1') {
			destroyInstance(heading);
		}
		heading.dataset.emtInit = '1';

		// Editor: tampilkan teks utuh tanpa animasi (no SplitText, no ScrollTrigger).
		if (isEditorPreview()) {
			heading.style.visibility = 'visible';
			heading.style.opacity = '1';
			instances.set(heading, { editor: true });
			return;
		}

		if (!ensurePlugins()) {
			heading.style.visibility = 'visible';
			return;
		}

		// FOUC prevention: tampilkan element tepat sebelum di-animate
		gsap.set(heading, { autoAlpha: 1 });

		var type = heading.dataset.splitReveal || 'lines';
		if (['lines', 'words', 'chars'].indexOf(type) === -1) type = 'lines';

		var typesToSplit = type === 'lines'
			? ['lines']
			: type === 'words'
				? ['lines', 'words']
				: ['lines', 'words', 'chars'];

		var defaultConfig = splitConfig[type];

		var duration = parseFloat(heading.dataset.emtDuration);
		if (isNaN(duration)) duration = defaultConfig.duration;

		var stagger = parseFloat(heading.dataset.emtStagger);
		if (isNaN(stagger)) stagger = defaultConfig.stagger;

		var yPercent = parseFloat(heading.dataset.emtY);
		if (isNaN(yPercent)) yPercent = 110;

		var ease = heading.dataset.emtEase || 'expo.out';
		var startStr = heading.dataset.emtStart || 'clamp(top 80%)';
		var once = heading.dataset.emtOnce !== 'false';

		var inst = { split: null, tween: null, scrollTrigger: null };

		inst.split = SplitText.create(heading, {
			type: typesToSplit.join(','),
			mask: 'lines',
			autoSplit: true,
			linesClass: 'line',
			wordsClass: 'word',
			charsClass: 'letter',
			onSplit: function (instance) {
				var targets = instance[type];
				if (!targets || !targets.length) return null;

				inst.tween = gsap.from(targets, {
					yPercent: yPercent,
					duration: duration,
					stagger: stagger,
					ease: ease,
					scrollTrigger: {
						trigger: heading,
						start: startStr,
						once: once,
						toggleActions: once ? 'play none none none' : 'play none none reset',
					},
				});
				if (inst.tween && inst.tween.scrollTrigger) {
					inst.scrollTrigger = inst.tween.scrollTrigger;
				}
				return inst.tween;
			},
		});

		instances.set(heading, inst);
	}

	function initAll(scope) {
		cleanupStale();
		var root = scope || document;
		root.querySelectorAll('[data-split="heading"]').forEach(initOne);
		// Layout may have shifted (new widget added) — recompute trigger positions.
		if (window.ScrollTrigger && typeof ScrollTrigger.refresh === 'function') {
			try { ScrollTrigger.refresh(); } catch (_) {}
		}
	}

	function bootstrap() {
		if (document.fonts && document.fonts.ready && typeof document.fonts.ready.then === 'function') {
			document.fonts.ready.then(function () { initAll(); });
		} else {
			initAll();
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', bootstrap);
	} else {
		bootstrap();
	}

	if (window.elementorFrontend && window.elementorFrontend.hooks) {
		window.elementorFrontend.hooks.addAction(
			'frontend/element_ready/masked_text_reveal.default',
			function ($scope) { initAll($scope[0]); }
		);
	}
})();
