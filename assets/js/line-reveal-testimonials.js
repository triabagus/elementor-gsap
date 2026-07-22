(function () {
	'use strict';

	function isEditorPreview() {
		return !!(window.elementorFrontend
			&& typeof window.elementorFrontend.isEditMode === 'function'
			&& window.elementorFrontend.isEditMode());
	}

	var IMG_CLIP_HIDDEN  = 'circle(0% at 50% 50%)';
	var IMG_CLIP_VISIBLE = 'circle(50% at 50% 50%)';

	function initLineRevealTestimonials(wrap) {
		if (!wrap) return;
		if (wrap.dataset.egsapLrtInit === '1') return;
		wrap.dataset.egsapLrtInit = '1';

		if (isEditorPreview()) return;

		if (typeof window.gsap === 'undefined'
			|| typeof window.SplitText === 'undefined'
			|| typeof window.ScrollTrigger === 'undefined') {
			console.warn('GSAP / SplitText / ScrollTrigger belum dimuat untuk Line Reveal Testimonials.');
			return;
		}
		var gsap = window.gsap;
		var SplitText = window.SplitText;
		var ScrollTrigger = window.ScrollTrigger;

		gsap.registerPlugin(ScrollTrigger, SplitText);

		var list = wrap.querySelector('[data-egsap-lrt-list]');
		if (!list) return;

		var items = Array.prototype.slice.call(list.querySelectorAll('[data-egsap-lrt-item]'));
		if (!items.length) return;

		var btnPrev  = wrap.querySelector('[data-egsap-lrt-prev]');
		var btnNext  = wrap.querySelector('[data-egsap-lrt-next]');
		var elCurrent = wrap.querySelector('[data-egsap-lrt-current]');
		var elTotal   = wrap.querySelector('[data-egsap-lrt-total]');

		if (elTotal) elTotal.textContent = String(items.length);

		var activeIndex = items.findIndex(function (el) { return el.classList.contains('is--active'); });
		if (activeIndex < 0) activeIndex = 0;

		var isAnimating = false;
		var reduceMotion = false;

		var autoplayEnabled  = wrap.getAttribute('data-egsap-lrt-autoplay') === 'true';
		var autoplayDuration = parseInt(wrap.getAttribute('data-egsap-lrt-autoplay-duration'), 10) || 4000;

		var autoplayCall = null;
		var isInView = true;

		var slides = items.map(function (item) {
			var textEls = [];
			var mainText = item.querySelector('[data-egsap-lrt-text]');
			if (mainText) textEls.push(mainText);
			var splitEls = Array.prototype.slice.call(item.querySelectorAll('[data-egsap-lrt-split]'));
			splitEls.forEach(function (el) { textEls.push(el); });

			return {
				item:  item,
				image: item.querySelector('[data-egsap-lrt-img]'),
				splitTargets: textEls,
				splitInstances: [],
				getLines: function () {
					var out = [];
					this.splitInstances.forEach(function (inst) {
						if (inst && inst.lines) out = out.concat(inst.lines);
					});
					return out;
				},
			};
		});

		function setSlideState(slideIndex, isActive) {
			var item = slides[slideIndex].item;
			item.classList.toggle('is--active', isActive);
			item.setAttribute('aria-hidden', String(!isActive));
			gsap.set(item, {
				autoAlpha:     isActive ? 1 : 0,
				pointerEvents: isActive ? 'auto' : 'none',
			});
		}

		function updateCounter() {
			if (elCurrent) elCurrent.textContent = String(activeIndex + 1);
		}

		function startAutoplay() {
			if (!autoplayEnabled) return;
			if (autoplayCall) autoplayCall.kill();

			autoplayCall = gsap.delayedCall(autoplayDuration / 1000, function () {
				if (!isInView || isAnimating) {
					startAutoplay();
					return;
				}
				goTo((activeIndex + 1) % slides.length);
				startAutoplay();
			});
		}

		function pauseAutoplay()  { if (autoplayCall) autoplayCall.pause(); }
		function resumeAutoplay() {
			if (!autoplayEnabled) return;
			if (!autoplayCall) startAutoplay();
			else autoplayCall.resume();
		}
		function resetAutoplay() {
			if (!autoplayEnabled) return;
			startAutoplay();
		}

		/* Init all slide states */
		slides.forEach(function (_, i) { setSlideState(i, i === activeIndex); });
		updateCounter();

		/* Reduced motion detection */
		gsap.matchMedia().add(
			{ reduce: '(prefers-reduced-motion: reduce)' },
			function (context) {
				reduceMotion = !!(context.conditions && context.conditions.reduce);
			}
		);

		/* Create SplitText instances per slide */
		slides.forEach(function (slide, slideIndex) {
			slide.splitInstances = slide.splitTargets.map(function (el) {
				return SplitText.create(el, {
					type: 'lines',
					mask: 'lines',
					linesClass: 'text-line',
					autoSplit: true,
					onSplit: function (self) {
						if (reduceMotion) return;
						var isActive = slideIndex === activeIndex;
						gsap.set(self.lines, { yPercent: isActive ? 0 : 110 });
						if (slide.image) {
							gsap.set(slide.image, { clipPath: isActive ? IMG_CLIP_VISIBLE : IMG_CLIP_HIDDEN });
						}
					},
				});
			});
		});

		function goTo(nextIndex) {
			if (isAnimating || nextIndex === activeIndex) return;
			isAnimating = true;

			var outgoing = slides[activeIndex];
			var incoming = slides[nextIndex];

			var tl = gsap.timeline({
				onComplete: function () {
					setSlideState(activeIndex, false);
					setSlideState(nextIndex, true);
					activeIndex = nextIndex;
					updateCounter();
					isAnimating = false;
				},
			});

			if (reduceMotion) {
				tl.to(outgoing.item, { autoAlpha: 0, duration: 0.4, ease: 'power2' }, 0)
					.fromTo(incoming.item, { autoAlpha: 0 }, { autoAlpha: 1, duration: 0.4, ease: 'power2' }, 0);
				return;
			}

			var outLines = outgoing.getLines();
			var inLines  = incoming.getLines();

			gsap.set(incoming.item, { autoAlpha: 1, pointerEvents: 'auto' });
			gsap.set(inLines,       { yPercent: 110 });

			if (outgoing.image) gsap.set(outgoing.image, { clipPath: IMG_CLIP_VISIBLE });

			tl.to(outLines, {
				yPercent: -110,
				duration: 0.6,
				ease:     'power4.inOut',
				stagger:  { amount: 0.25 },
			}, 0);

			if (outgoing.image) {
				tl.to(outgoing.image, {
					clipPath: IMG_CLIP_HIDDEN,
					duration: 0.6,
					ease:     'power4.inOut',
				}, 0);
			}

			tl.to(inLines, {
				yPercent: 0,
				duration: 0.7,
				ease:     'power4.inOut',
				stagger:  { amount: 0.4 },
			}, '>-=0.3');

			if (incoming.image) {
				tl.fromTo(incoming.image,
					{ clipPath: IMG_CLIP_HIDDEN },
					{ clipPath: IMG_CLIP_VISIBLE, duration: 0.75, ease: 'power4.inOut' },
					'<'
				);
			}

			tl.set(outgoing.item, { autoAlpha: 0 }, '>');
		}

		startAutoplay();

		if (btnNext) btnNext.addEventListener('click', function () {
			resetAutoplay();
			goTo((activeIndex + 1) % slides.length);
		});
		if (btnPrev) btnPrev.addEventListener('click', function () {
			resetAutoplay();
			goTo((activeIndex - 1 + slides.length) % slides.length);
		});

		function onKeyDown(e) {
			if (!isInView) return;
			var t = e.target;
			var isTyping = t && (t.tagName === 'INPUT' || t.tagName === 'TEXTAREA' || t.isContentEditable);
			if (isTyping) return;
			if (e.key === 'ArrowRight') {
				e.preventDefault();
				resetAutoplay();
				goTo((activeIndex + 1) % slides.length);
			}
			if (e.key === 'ArrowLeft') {
				e.preventDefault();
				resetAutoplay();
				goTo((activeIndex - 1 + slides.length) % slides.length);
			}
		}
		window.addEventListener('keydown', onKeyDown);

		ScrollTrigger.create({
			trigger: wrap,
			start: 'top bottom',
			end: 'bottom top',
			onEnter:     function () { isInView = true;  resumeAutoplay(); },
			onEnterBack: function () { isInView = true;  resumeAutoplay(); },
			onLeave:     function () { isInView = false; pauseAutoplay();  },
			onLeaveBack: function () { isInView = false; pauseAutoplay();  },
		});
	}

	function initAll(scope) {
		(scope || document).querySelectorAll('[data-egsap-lrt-wrap]').forEach(initLineRevealTestimonials);
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
