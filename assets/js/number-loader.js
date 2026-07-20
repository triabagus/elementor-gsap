(function () {
	'use strict';

	function isEditorPreview() {
		return !!(window.elementorFrontend
			&& typeof window.elementorFrontend.isEditMode === 'function'
			&& window.elementorFrontend.isEditMode());
	}

	function initNumberLoader(container) {
		if (!container) return;
		if (container.dataset.egsapNlInit === '1') return;
		container.dataset.egsapNlInit = '1';

		if (isEditorPreview()) {
			container.style.display = 'none';
			return;
		}

		if (typeof window.gsap === 'undefined') {
			console.warn('GSAP belum dimuat untuk Number Loader.');
			return;
		}
		var gsap = window.gsap;

		var duration = parseFloat(container.dataset.egsapNlDuration);
		if (isNaN(duration) || duration <= 0) duration = 1.2;

		// Scoped selector helper — semua tween di-scope ke container agar
		// multi-instance aman (walau umumnya loader page-level cuma 1).
		var q = function (sel) { return container.querySelectorAll(sel); };

		document.body.setAttribute('data-egsap-nl-status', 'loading');

		var tl = gsap.timeline({
			defaults: { ease: 'expo.inOut', duration: duration },
			onComplete: function () {
				document.body.setAttribute('data-egsap-nl-status', 'done');
				container.style.pointerEvents = 'none';
			},
		});

		// Random pairs untuk simulasi loading progress (fake but visual).
		var r1 = gsap.utils.random([ 2, 3, 4 ]);
		var r2 = gsap.utils.random([ 5, 6 ]);
		var r3 = gsap.utils.random([ 1, 5 ]);
		var r4 = gsap.utils.random([ 7, 8, 9 ]);

		tl.set(container.querySelector('.egsap-nl-screen'), { display: 'block' });
		tl.set(q('.egsap-nl-progress-inner'), { scaleY: 0 });
		tl.set(q('.egsap-nl-number-group.is--first .egsap-nl-number-wrap, .egsap-nl-percentage'), { yPercent: 100 });
		tl.set(q('.egsap-nl-number-group.is--second .egsap-nl-number-wrap, .egsap-nl-number-group.is--third .egsap-nl-number-wrap'), { yPercent: 10 });

		// Step 1
		tl.to(q('.egsap-nl-progress-inner'), { scaleY: ( '' + r1 + r3 ) / 100 });
		tl.to(q('.egsap-nl-percentage'), { yPercent: 0 }, '<');
		tl.to(q('.egsap-nl-number-group.is--second .egsap-nl-number-wrap'), { yPercent: ( r1 - 1 ) * -10 }, '<');
		tl.to(q('.egsap-nl-number-group.is--third .egsap-nl-number-wrap'),  { yPercent: ( r3 - 1 ) * -10 }, '<');

		// Step 2
		tl.to(q('.egsap-nl-progress-inner'), { scaleY: ( '' + r2 + r4 ) / 100 });
		tl.to(q('.egsap-nl-number-group.is--second .egsap-nl-number-wrap'), { yPercent: ( r2 - 1 ) * -10 }, '<');
		tl.to(q('.egsap-nl-number-group.is--third .egsap-nl-number-wrap'),  { yPercent: ( r4 - 1 ) * -10 }, '<');

		// Step 3 — reach 100
		tl.to(q('.egsap-nl-progress-inner'), { scaleY: 1 });
		tl.to(q('.egsap-nl-number-group.is--second .egsap-nl-number-wrap'), { yPercent: -90 }, '<');
		tl.to(q('.egsap-nl-number-group.is--third .egsap-nl-number-wrap'),  { yPercent: -90 }, '<');
		tl.to(q('.egsap-nl-number-group.is--first .egsap-nl-number-wrap'),  { yPercent: 0 },   '<');

		// Fade out overlay setelah reach 100 (biar page revealed)
		var fadeOut = parseFloat(container.dataset.egsapNlFadeOut);
		if (isNaN(fadeOut) || fadeOut < 0) fadeOut = 0.6;
		tl.to(container, {
			autoAlpha: 0,
			duration:  fadeOut,
			delay:     0.3,
			ease:      'power2.inOut',
		});
	}

	function initAll(scope) {
		(scope || document).querySelectorAll('[data-egsap-nl]').forEach(initNumberLoader);
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
