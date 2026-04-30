(function () {
	'use strict';

	function isEditorPreview() {
		return !!(window.elementorFrontend
			&& typeof window.elementorFrontend.isEditMode === 'function'
			&& window.elementorFrontend.isEditMode());
	}

	function ensureCustomEase() {
		if (typeof window.CustomEase === 'undefined' || typeof window.gsap === 'undefined') return;
		if (typeof window.SplitText !== 'undefined') {
			gsap.registerPlugin(SplitText, CustomEase);
		} else {
			gsap.registerPlugin(CustomEase);
		}
		if (!CustomEase.get || !CustomEase.get('slideshow-wipe')) {
			CustomEase.create('slideshow-wipe', '0.625, 0.05, 0, 1');
		}
	}

	function initCrispLoading(container) {
		if (!container || container.dataset.egsapInit === '1') return;
		container.dataset.egsapInit = '1';

		if (isEditorPreview()) return;

		if (typeof window.gsap === 'undefined') {
			console.warn('GSAP belum dimuat untuk crisp loading.');
			return;
		}

		ensureCustomEase();

		var heading = container.querySelectorAll('.crisp-header__h1');
		var revealImages = container.querySelectorAll('.crisp-loader__group > *');
		var isScaleUp = container.querySelectorAll('.crisp-loader__media');
		var isScaleDown = container.querySelectorAll('.crisp-loader__media .is--scale-down');
		var isRadius = container.querySelectorAll('.crisp-loader__media.is--scaling.is--radius');
		var smallElements = container.querySelectorAll('.crisp-header__top, .crisp-header__p');
		var sliderNav = container.querySelectorAll('.crisp-header__slider-nav > *');

		var tl = gsap.timeline({
			defaults: { ease: 'expo.inOut' },
			onStart: function () {
				container.classList.remove('is--hidden');
			},
		});

		var split = null;
		if (heading.length && typeof window.SplitText !== 'undefined') {
			split = new SplitText(heading, { type: 'words', mask: 'words' });
			gsap.set(split.words, { yPercent: 110 });
		}

		if (revealImages.length) {
			tl.fromTo(revealImages, { xPercent: 500 }, {
				xPercent: -500,
				duration: 2.5,
				stagger: 0.05,
			});
		}

		if (isScaleDown.length) {
			tl.to(isScaleDown, {
				scale: 0.5,
				duration: 2,
				stagger: { each: 0.05, from: 'edges', ease: 'none' },
				onComplete: function () {
					if (isRadius && isRadius.length) {
						isRadius.forEach(function (el) { el.classList.remove('is--radius'); });
					}
				},
			}, '-=0.1');
		}

		if (isScaleUp.length) {
			tl.fromTo(isScaleUp, { width: '10em', height: '10em' }, {
				width: '100vw',
				height: '100dvh',
				duration: 2,
			}, '< 0.5');
		}

		if (sliderNav.length) {
			tl.from(sliderNav, {
				yPercent: 150,
				stagger: 0.05,
				ease: 'expo.out',
				duration: 1,
			}, '-=0.9');
		}

		if (split && split.words.length) {
			tl.to(split.words, {
				yPercent: 0,
				stagger: 0.075,
				ease: 'expo.out',
				duration: 1,
			}, '< 0.1');
		}

		if (smallElements.length) {
			tl.from(smallElements, {
				opacity: 0,
				ease: 'power1.inOut',
				duration: 0.2,
			}, '< 0.15');
		}

		tl.call(function () {
			container.classList.remove('is--loading');
		}, null, '+=0.45');

		initSlideshow(container);
	}

	function initSlideshow(wrapper) {
		var slides = Array.prototype.slice.call(wrapper.querySelectorAll('[data-slideshow="slide"]'));
		var inner = Array.prototype.slice.call(wrapper.querySelectorAll('[data-slideshow="parallax"]'));
		var thumbs = Array.prototype.slice.call(wrapper.querySelectorAll('[data-slideshow="thumb"]'));

		if (!slides.length || !thumbs.length) return;

		var current = 0;
		var length = slides.length;
		var animating = false;
		var animationDuration = 1.5;

		slides.forEach(function (slide, index) { slide.setAttribute('data-index', index); });
		thumbs.forEach(function (thumb, index) { thumb.setAttribute('data-index', index); });

		function navigate(direction, targetIndex) {
			if (animating) return;
			animating = true;

			var previous = current;
			if (typeof targetIndex === 'number') {
				current = targetIndex;
			} else if (direction === 1) {
				current = current < length - 1 ? current + 1 : 0;
			} else {
				current = current > 0 ? current - 1 : length - 1;
			}

			var currentSlide = slides[previous];
			var currentInner = inner[previous];
			var upcomingSlide = slides[current];
			var upcomingInner = inner[current];

			gsap.timeline({
				defaults: { duration: animationDuration, ease: 'slideshow-wipe' },
				onStart: function () {
					upcomingSlide.classList.add('is--current');
					thumbs[previous].classList.remove('is--current');
					thumbs[current].classList.add('is--current');
				},
				onComplete: function () {
					currentSlide.classList.remove('is--current');
					animating = false;
				},
			})
				.to(currentSlide, { xPercent: -direction * 100 }, 0)
				.to(currentInner, { xPercent: direction * 75 }, 0)
				.fromTo(upcomingSlide, { xPercent: direction * 100 }, { xPercent: 0 }, 0)
				.fromTo(upcomingInner, { xPercent: -direction * 75 }, { xPercent: 0 }, 0);
		}

		thumbs.forEach(function (thumb) {
			thumb.addEventListener('click', function (event) {
				var targetIndex = parseInt(event.currentTarget.getAttribute('data-index'), 10);
				if (targetIndex === current || animating) return;
				var direction = targetIndex > current ? 1 : -1;
				navigate(direction, targetIndex);
			});
		});
	}

	function initAll(root) {
		var scope = root || document;
		scope.querySelectorAll('.crisp-header').forEach(initCrispLoading);
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
		var initFromDoc = function () { initAll(document); };
		window.elementorFrontend.hooks.addAction('frontend/element_ready/global', initFromDoc);
	}
})();
