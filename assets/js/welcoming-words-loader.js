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
		if (inst.timeline) { try { inst.timeline.kill(); } catch (_) {} }
		instances.delete(container);
		delete container.dataset.egsapWwlInit;
	}

	function cleanupStale() {
		instances.forEach(function (_inst, container) {
			if (!container.isConnected) destroyInstance(container);
		});
	}

	function initWelcomingWordsLoader(container) {
		if (!container) return;
		if (container.dataset.egsapWwlInit === '1') return;
		container.dataset.egsapWwlInit = '1';

		// Editor preview: JANGAN mainkan animation, biarkan render statik
		// via CSS override supaya user bisa styling & tidak keblok overlay.
		if (isEditorPreview()) {
			instances.set(container, { editor: true });
			return;
		}

		if (typeof window.gsap === 'undefined') {
			console.warn('GSAP belum dimuat untuk Welcoming Words Loader.');
			return;
		}
		var gsap = window.gsap;

		var loadingWords = container.querySelector('[data-loading-words]');
		if (!loadingWords) return;

		var wordsTarget = loadingWords.querySelector('[data-loading-words-target]');
		if (!wordsTarget) return;

		var raw   = loadingWords.getAttribute('data-loading-words') || '';
		var words = raw.split(',').map(function (w) { return w.trim(); }).filter(Boolean);
		if (!words.length) return;

		// Set kata pertama langsung supaya reveal awal tidak flash target kosong.
		wordsTarget.textContent = words[0];

		var stepDelay = parseFloat(container.dataset.egsapWwlStep);
		if (isNaN(stepDelay) || stepDelay <= 0) stepDelay = 0.15;

		var revealDuration = parseFloat(container.dataset.egsapWwlReveal);
		if (isNaN(revealDuration) || revealDuration <= 0) revealDuration = 1;

		var exitDuration = parseFloat(container.dataset.egsapWwlExit);
		if (isNaN(exitDuration) || exitDuration <= 0) exitDuration = 0.8;

		var fadeDuration = parseFloat(container.dataset.egsapWwlFade);
		if (isNaN(fadeDuration) || fadeDuration <= 0) fadeDuration = 0.6;

		var tl = gsap.timeline();

		tl.set(loadingWords, { yPercent: 50 });

		tl.to(loadingWords, {
			opacity:  1,
			yPercent: 0,
			duration: revealDuration,
			ease:     'expo.inOut',
		});

		words.forEach(function (word) {
			tl.call(function () { wordsTarget.textContent = word; }, null, '+=' + stepDelay);
		});

		tl.to(loadingWords, {
			opacity:  0,
			yPercent: -75,
			duration: exitDuration,
			ease:     'expo.in',
		});

		tl.to(container, {
			autoAlpha: 0,
			duration:  fadeDuration,
			ease:      'power1.inOut',
			onComplete: function () {
				// Setelah loader selesai, hilangkan dari stacking context
				// supaya z-index 500 tidak konflik dengan section/widget user
				// yang mungkin juga pakai z-index tinggi.
				container.style.display       = 'none';
				container.style.pointerEvents = 'none';
			},
		}, '-=0.2');

		instances.set(container, { timeline: tl });
	}

	function initAll(root) {
		cleanupStale();
		var scope = root || document;
		scope.querySelectorAll('[data-loading-container]').forEach(initWelcomingWordsLoader);
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
