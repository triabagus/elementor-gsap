(function () {
	'use strict';

	function isEditorPreview() {
		return !!(window.elementorFrontend
			&& typeof window.elementorFrontend.isEditMode === 'function'
			&& window.elementorFrontend.isEditMode());
	}

	function initWillemLoadingAnimation(container) {
		if (!container || container.dataset.egsapInit === '1') return;
		container.dataset.egsapInit = '1';

		if (isEditorPreview()) return;

		if (typeof window.gsap === 'undefined') {
			console.warn('GSAP belum dimuat untuk elementor-gsap.');
			return;
		}
		var gsap = window.gsap;

		var loadingLetter = container.querySelectorAll('.willem__letter');
		var box = container.querySelectorAll('.willem-loader__box');
		var growingImage = container.querySelectorAll('.willem__growing-image');
		var headingStart = container.querySelectorAll('.willem__h1-start');
		var headingEnd = container.querySelectorAll('.willem__h1-end');
		var coverImageExtra = container.querySelectorAll('.willem__cover-image-extra');
		var headerLetter = container.querySelectorAll('.willem__letter-white');
		var navLinks = container.querySelectorAll('.willen-nav a');

		var tl = gsap.timeline({
			defaults: { ease: 'expo.inOut' },
			onStart: function () {
				container.classList.remove('is--hidden');
			},
		});

		if (loadingLetter.length) {
			tl.from(loadingLetter, {
				yPercent: 100,
				stagger: 0.025,
				duration: 1.25,
			});
		}

		if (box.length) {
			tl.fromTo(box, { width: '0em' }, { width: '1em', duration: 1.25 }, '< 1.25');
		}

		if (growingImage.length) {
			tl.fromTo(growingImage, { width: '0%' }, { width: '100%', duration: 1.25 }, '<');
		}

		if (headingStart.length) {
			tl.fromTo(headingStart, { x: '0em' }, { x: '-0.05em', duration: 1.25 }, '<');
		}

		if (headingEnd.length) {
			tl.fromTo(headingEnd, { x: '0em' }, { x: '0.05em', duration: 1.25 }, '<');
		}

		if (coverImageExtra.length) {
			tl.fromTo(coverImageExtra,
				{ opacity: 1 },
				{ opacity: 0, duration: 0.05, ease: 'none', stagger: 0.5 },
				'-=0.05'
			);
		}

		if (growingImage.length) {
			tl.to(growingImage, {
				width: '100vw',
				height: '100dvh',
				duration: 2,
			}, '< 1.25');
		}

		if (box.length) {
			tl.to(box, { width: '110vw', duration: 2 }, '<');
		}

		if (headerLetter.length) {
			tl.from(headerLetter, {
				yPercent: 100,
				duration: 1.25,
				ease: 'expo.out',
				stagger: 0.025,
			}, '< 1.2');
		}

		if (navLinks.length) {
			tl.from(navLinks, {
				yPercent: 100,
				duration: 1.25,
				ease: 'expo.out',
				stagger: 0.1,
			}, '<');
		}
	}

	function initAll(root) {
		var scope = root || document;
		scope.querySelectorAll('.willem-header').forEach(initWillemLoadingAnimation);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () { initAll(); });
	} else {
		initAll();
	}

	if (window.elementorFrontend && window.elementorFrontend.hooks) {
		var initFromDoc = function () { initAll(document); };
		window.elementorFrontend.hooks.addAction('frontend/element_ready/container', initFromDoc);
		window.elementorFrontend.hooks.addAction('frontend/element_ready/section', initFromDoc);
	}
})();
