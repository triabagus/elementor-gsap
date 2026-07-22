(function () {
	'use strict';

	function isEditorPreview() {
		return !!(window.elementorFrontend
			&& typeof window.elementorFrontend.isEditMode === 'function'
			&& window.elementorFrontend.isEditMode());
	}

	function num(el, key, fallback) {
		var v = parseFloat(el.dataset[key]);
		return isNaN(v) ? fallback : v;
	}

	function initCascadingSlider(wrapper) {
		if (!wrapper) return;
		if (wrapper.dataset.egsapCsrInit === '1') return;
		wrapper.dataset.egsapCsrInit = '1';

		if (isEditorPreview()) return;

		if (typeof window.gsap === 'undefined') {
			console.warn('GSAP belum dimuat untuk Cascading Slider.');
			return;
		}
		var gsap = window.gsap;

		var duration = num(wrapper, 'egsapCsrDuration', 0.65);
		var ease     = wrapper.dataset.egsapCsrEase || 'power3.inOut';

		/* Breakpoint config dari data attributes (default: reference values). */
		var mobileActive  = num(wrapper, 'egsapCsrMobileActive',  0.78);
		var mobileSibling = num(wrapper, 'egsapCsrMobileSibling', 0.08);
		var tabletActive  = num(wrapper, 'egsapCsrTabletActive',  0.70);
		var tabletSibling = num(wrapper, 'egsapCsrTabletSibling', 0.10);
		var laptopActive  = num(wrapper, 'egsapCsrLaptopActive',  0.60);
		var laptopSibling = num(wrapper, 'egsapCsrLaptopSibling', 0.10);
		var desktopActive = num(wrapper, 'egsapCsrDesktopActive', 0.60);
		var desktopSibling= num(wrapper, 'egsapCsrDesktopSibling', 0.13);

		var breakpoints = [
			{ maxWidth: 479,      activeWidth: mobileActive,  siblingWidth: mobileSibling },
			{ maxWidth: 767,      activeWidth: tabletActive,  siblingWidth: tabletSibling },
			{ maxWidth: 991,      activeWidth: laptopActive,  siblingWidth: laptopSibling },
			{ maxWidth: Infinity, activeWidth: desktopActive, siblingWidth: desktopSibling },
		];

		var viewport   = wrapper.querySelector('[data-egsap-csr-viewport]');
		if (!viewport) return;
		var prevButton = wrapper.querySelector('[data-egsap-csr-prev]');
		var nextButton = wrapper.querySelector('[data-egsap-csr-next]');
		var slides     = Array.prototype.slice.call(viewport.querySelectorAll('[data-egsap-csr-slide]'));
		var totalSlides = slides.length;

		if (totalSlides === 0) return;

		/* Duplicate slides sampai >= 9 supaya cascading punya cukup slot. */
		if (totalSlides < 9) {
			var originalSlides = slides.slice();
			while (slides.length < 9) {
				originalSlides.forEach(function (original) {
					var clone = original.cloneNode(true);
					clone.setAttribute('data-egsap-csr-clone', '');
					viewport.appendChild(clone);
					slides.push(clone);
				});
			}
			totalSlides = slides.length;
		}

		var activeIndex = 0;
		var isAnimating = false;
		var slideWidth  = 0;
		var slotCenters = {};
		var slotWidths  = {};

		function readGap() {
			var raw = getComputedStyle(viewport).getPropertyValue('--gap').trim();
			if (!raw) return 0;
			var temp = document.createElement('div');
			temp.style.width = raw;
			temp.style.position = 'absolute';
			temp.style.visibility = 'hidden';
			viewport.appendChild(temp);
			var px = temp.offsetWidth;
			viewport.removeChild(temp);
			return px;
		}

		function getSettings() {
			var w = window.innerWidth;
			for (var i = 0; i < breakpoints.length; i++) {
				if (w <= breakpoints[i].maxWidth) return breakpoints[i];
			}
			return breakpoints[breakpoints.length - 1];
		}

		function getOffset(slideIndex, fromIndex) {
			if (fromIndex === undefined) fromIndex = activeIndex;
			var distance = slideIndex - fromIndex;
			var half = totalSlides / 2;
			if (distance > half)  distance -= totalSlides;
			if (distance < -half) distance += totalSlides;
			return distance;
		}

		function measure() {
			var settings = getSettings();
			var viewportWidth = viewport.offsetWidth;
			var gap = readGap();

			var activeSlideWidth  = viewportWidth * settings.activeWidth;
			var siblingSlideWidth = viewportWidth * settings.siblingWidth;
			var farSlideWidth     = Math.max(0, (viewportWidth - activeSlideWidth - 2 * siblingSlideWidth - 4 * gap) / 2);

			slideWidth = activeSlideWidth;

			var visibleSlots = [
				{ slot: -2, width: farSlideWidth },
				{ slot: -1, width: siblingSlideWidth },
				{ slot:  0, width: activeSlideWidth },
				{ slot:  1, width: siblingSlideWidth },
				{ slot:  2, width: farSlideWidth },
			];

			var x = 0;
			visibleSlots.forEach(function (def, i) {
				slotCenters[String(def.slot)] = x + def.width / 2;
				slotWidths[String(def.slot)]  = def.width;
				if (i < visibleSlots.length - 1) x += def.width + gap;
			});

			slotCenters['-3'] = slotCenters['-2'] - farSlideWidth / 2 - gap - farSlideWidth / 2;
			slotWidths['-3']  = farSlideWidth;
			slotCenters['3']  = slotCenters['2']  + farSlideWidth / 2 + gap + farSlideWidth / 2;
			slotWidths['3']   = farSlideWidth;

			slides.forEach(function (slide) {
				slide.style.width = slideWidth + 'px';
			});
		}

		function getSlideProps(offset) {
			var clamped = Math.max(-3, Math.min(3, offset));
			var slotWidth = slotWidths[String(clamped)];
			var clipAmount = Math.max(0, (slideWidth - slotWidth) / 2);
			var translateX = slotCenters[String(clamped)] - slideWidth / 2;

			return {
				x: translateX,
				'--clip': clipAmount,
				zIndex: 10 - Math.abs(clamped),
			};
		}

		function layout(animate, previousIndex) {
			slides.forEach(function (slide, index) {
				var offset = getOffset(index);

				if (offset < -3 || offset > 3) {
					if (animate && previousIndex !== undefined) {
						var previousOffset = getOffset(index, previousIndex);
						if (previousOffset >= -2 && previousOffset <= 2) {
							var exitSlot = previousOffset < 0 ? -3 : 3;
							gsap.to(slide, Object.assign({}, getSlideProps(exitSlot), {
								duration: duration,
								ease:     ease,
								overwrite: true,
							}));
							return;
						}
					}
					var parkSlot = offset < 0 ? -3 : 3;
					gsap.set(slide, getSlideProps(parkSlot));
					return;
				}

				var props = getSlideProps(offset);
				slide.setAttribute('data-status', offset === 0 ? 'active' : 'inactive');

				if (animate) {
					gsap.to(slide, Object.assign({}, props, {
						duration: duration,
						ease:     ease,
						overwrite: true,
					}));
				} else {
					gsap.set(slide, props);
				}
			});
		}

		function goTo(targetIndex) {
			var normalizedTarget = ((targetIndex % totalSlides) + totalSlides) % totalSlides;
			if (isAnimating || normalizedTarget === activeIndex) return;
			isAnimating = true;

			var previousIndex = activeIndex;
			var travelDirection = getOffset(normalizedTarget, previousIndex) > 0 ? 1 : -1;

			slides.forEach(function (slide, index) {
				var currentOffset = getOffset(index, previousIndex);
				var nextOffset    = getOffset(index, normalizedTarget);
				var wasInRange    = currentOffset >= -3 && currentOffset <= 3;
				var willBeVisible = nextOffset >= -2 && nextOffset <= 2;

				if (!wasInRange && willBeVisible) {
					var entrySlot = travelDirection > 0 ? 3 : -3;
					gsap.set(slide, getSlideProps(entrySlot));
				}

				var wasInvisible  = Math.abs(currentOffset) >= 3;
				var willBeStaging = Math.abs(nextOffset) === 3;
				var crossesSides  = currentOffset * nextOffset < 0;
				if (wasInvisible && willBeStaging && crossesSides) {
					gsap.set(slide, getSlideProps(nextOffset > 0 ? 3 : -3));
				}
			});

			activeIndex = normalizedTarget;
			layout(true, previousIndex);
			gsap.delayedCall(duration + 0.05, function () { isAnimating = false; });
		}

		if (prevButton) prevButton.addEventListener('click', function () { goTo(activeIndex - 1); });
		if (nextButton) nextButton.addEventListener('click', function () { goTo(activeIndex + 1); });

		slides.forEach(function (slide, index) {
			slide.addEventListener('click', function () {
				if (index !== activeIndex) goTo(index);
			});
		});

		document.addEventListener('keydown', function (event) {
			if (event.key === 'ArrowLeft')  goTo(activeIndex - 1);
			if (event.key === 'ArrowRight') goTo(activeIndex + 1);
		});

		var resizeTimer;
		window.addEventListener('resize', function () {
			clearTimeout(resizeTimer);
			resizeTimer = setTimeout(function () {
				measure();
				layout(false);
			}, 100);
		});

		measure();
		layout(false);
	}

	function initAll(scope) {
		(scope || document).querySelectorAll('[data-egsap-csr-wrap]').forEach(initCascadingSlider);
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
