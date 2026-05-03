(function () {
	'use strict';

	function isEditorPreview() {
		return !!(window.elementorFrontend
			&& typeof window.elementorFrontend.isEditMode === 'function'
			&& window.elementorFrontend.isEditMode());
	}

	function ensurePlugins() {
		if (typeof window.gsap === 'undefined') return false;
		if (typeof window.Draggable === 'undefined') return false;
		gsap.registerPlugin(Draggable);
		if (typeof window.InertiaPlugin !== 'undefined') {
			gsap.registerPlugin(InertiaPlugin);
		}
		return true;
	}

	function initOne(root) {
		if (!root || root.dataset.dgInit === '1') return;
		root.dataset.dgInit = '1';

		// Editor: keep static, no draggable / loop
		if (isEditorPreview()) return;

		if (!ensurePlugins()) {
			console.warn('GSAP / Draggable belum dimuat untuk Draggable Infinite Slider.');
			return;
		}

		var wrapper = root.querySelector('[data-slider="list"]');
		if (!wrapper) return;

		var slides = gsap.utils.toArray(root.querySelectorAll('[data-slider="slide"]'));
		var nextButton = root.querySelector('[data-slider-button="next"]');
		var prevButton = root.querySelector('[data-slider-button="prev"]');
		var totalElement = root.querySelector('[data-slide-count="total"]');
		var stepElement = root.querySelector('[data-slide-count="step"]');
		var stepsParent = stepElement ? stepElement.parentElement : null;

		var totalSlides = slides.length;
		var activeElement;

		var useNextDefault = root.dataset.sliderUseNext === 'true';
		var centerMode = root.dataset.sliderCenter === 'true';
		var duration = parseFloat(root.dataset.sliderDuration) || 0.725;

		if (totalElement) {
			totalElement.textContent = totalSlides < 10 ? '0' + totalSlides : String(totalSlides);
		}

		if (stepsParent && stepElement) {
			stepsParent.innerHTML = '';
			slides.forEach(function (_, index) {
				var stepClone = stepElement.cloneNode(true);
				var n = index + 1;
				stepClone.textContent = n < 10 ? '0' + n : String(n);
				stepsParent.appendChild(stepClone);
			});
		}
		var allSteps = stepsParent ? stepsParent.querySelectorAll('[data-slide-count="step"]') : [];

		var mq = window.matchMedia('(min-width: 992px)');
		var useNextForActive = useNextDefault && mq.matches;

		var currentEl = null;
		var currentIndex = 0;

		function resolveActive(el) {
			return useNextForActive ? (el.nextElementSibling || slides[0]) : el;
		}

		function applyActive(el, index, animateNumbers) {
			if (typeof animateNumbers === 'undefined') animateNumbers = true;
			if (activeElement) activeElement.classList.remove('active');
			var target = resolveActive(el);
			target.classList.add('active');
			activeElement = target;
			if (allSteps.length) {
				if (animateNumbers) {
					gsap.to(allSteps, { y: (-100 * index) + '%', ease: 'power3', duration: 0.45 });
				} else {
					gsap.set(allSteps, { y: (-100 * index) + '%' });
				}
			}
		}

		function onMediaChange(e) {
			useNextForActive = useNextDefault && e.matches;
			if (currentEl) {
				applyActive(currentEl, currentIndex, false);
			}
		}
		if (typeof mq.addEventListener === 'function') {
			mq.addEventListener('change', onMediaChange);
		} else if (typeof mq.addListener === 'function') {
			mq.addListener(onMediaChange);
		}

		var loop = horizontalLoop(slides, {
			paused: true,
			draggable: true,
			center: centerMode,
			onChange: function (element, index) {
				currentEl = element;
				currentIndex = index;
				applyActive(element, index, true);
			},
		});

		function mapClickIndex(i) {
			return useNextForActive ? (i - 1) : i;
		}

		slides.forEach(function (slide, i) {
			slide.addEventListener('click', function () {
				if (slide.classList.contains('active')) return;
				loop.toIndex(mapClickIndex(i), { ease: 'power3', duration: duration });
			});
		});

		if (nextButton) {
			nextButton.addEventListener('click', function () {
				loop.next({ ease: 'power3', duration: duration });
			});
		}
		if (prevButton) {
			prevButton.addEventListener('click', function () {
				loop.previous({ ease: 'power3', duration: duration });
			});
		}

		if (!currentEl && slides[0]) {
			currentEl = slides[0];
			currentIndex = 0;
			applyActive(currentEl, currentIndex, false);
		}
	}

	function horizontalLoop(items, config) {
		var timeline;
		items = gsap.utils.toArray(items);
		config = config || {};
		gsap.context(function () {
			var onChange = config.onChange,
				lastIndex = 0,
				tl = gsap.timeline({
					repeat: config.repeat,
					onUpdate: onChange && function () {
						var i = tl.closestIndex();
						if (lastIndex !== i) {
							lastIndex = i;
							onChange(items[i], i);
						}
					},
					paused: config.paused,
					defaults: { ease: 'none' },
					onReverseComplete: function () { tl.totalTime(tl.rawTime() + tl.duration() * 100); },
				}),
				length = items.length,
				startX = items[0].offsetLeft,
				times = [],
				widths = [],
				spaceBefore = [],
				xPercents = [],
				curIndex = 0,
				indexIsDirty = false,
				center = config.center,
				pixelsPerSecond = (config.speed || 1) * 100,
				snap = config.snap === false ? function (v) { return v; } : gsap.utils.snap(config.snap || 1),
				timeOffset = 0,
				container = center === true ? items[0].parentNode : (gsap.utils.toArray(center)[0] || items[0].parentNode),
				totalWidth,
				getTotalWidth = function () {
					return items[length - 1].offsetLeft + xPercents[length - 1] / 100 * widths[length - 1] - startX + spaceBefore[0] + items[length - 1].offsetWidth * gsap.getProperty(items[length - 1], 'scaleX') + (parseFloat(config.paddingRight) || 0);
				},
				populateWidths = function () {
					var b1 = container.getBoundingClientRect(), b2;
					items.forEach(function (el, i) {
						widths[i] = parseFloat(gsap.getProperty(el, 'width', 'px'));
						xPercents[i] = snap(parseFloat(gsap.getProperty(el, 'x', 'px')) / widths[i] * 100 + gsap.getProperty(el, 'xPercent'));
						b2 = el.getBoundingClientRect();
						spaceBefore[i] = b2.left - (i ? b1.right : b1.left);
						b1 = b2;
					});
					gsap.set(items, { xPercent: function (i) { return xPercents[i]; } });
					totalWidth = getTotalWidth();
				},
				timeWrap,
				populateOffsets = function () {
					timeOffset = center ? tl.duration() * (container.offsetWidth / 2) / totalWidth : 0;
					if (center) times.forEach(function (t, i) {
						times[i] = timeWrap(tl.labels['label' + i] + tl.duration() * widths[i] / 2 / totalWidth - timeOffset);
					});
				},
				getClosest = function (values, value, wrap) {
					var i = values.length, closest = 1e10, index = 0, d;
					while (i--) {
						d = Math.abs(values[i] - value);
						if (d > wrap / 2) d = wrap - d;
						if (d < closest) { closest = d; index = i; }
					}
					return index;
				},
				populateTimeline = function () {
					var i, item, curX, distanceToStart, distanceToLoop;
					tl.clear();
					for (i = 0; i < length; i++) {
						item = items[i];
						curX = xPercents[i] / 100 * widths[i];
						distanceToStart = item.offsetLeft + curX - startX + spaceBefore[0];
						distanceToLoop = distanceToStart + widths[i] * gsap.getProperty(item, 'scaleX');
						tl.to(item, { xPercent: snap((curX - distanceToLoop) / widths[i] * 100), duration: distanceToLoop / pixelsPerSecond }, 0)
							.fromTo(item, { xPercent: snap((curX - distanceToLoop + totalWidth) / widths[i] * 100) }, { xPercent: xPercents[i], duration: (curX - distanceToLoop + totalWidth - curX) / pixelsPerSecond, immediateRender: false }, distanceToLoop / pixelsPerSecond)
							.add('label' + i, distanceToStart / pixelsPerSecond);
						times[i] = distanceToStart / pixelsPerSecond;
					}
					timeWrap = gsap.utils.wrap(0, tl.duration());
				},
				refresh = function (deep) {
					var progress = tl.progress();
					tl.progress(0, true);
					populateWidths();
					if (deep) populateTimeline();
					populateOffsets();
					if (deep && tl.draggable) tl.time(times[curIndex], true);
					else tl.progress(progress, true);
				},
				onResize = function () { refresh(true); },
				proxy;
			gsap.set(items, { x: 0 });
			populateWidths();
			populateTimeline();
			populateOffsets();
			window.addEventListener('resize', onResize);

			function toIndex(index, vars) {
				vars = vars || {};
				if (Math.abs(index - curIndex) > length / 2) index += index > curIndex ? -length : length;
				var newIndex = gsap.utils.wrap(0, length, index),
					time = times[newIndex];
				if (time > tl.time() !== index > curIndex && index !== curIndex) {
					time += tl.duration() * (index > curIndex ? 1 : -1);
				}
				if (time < 0 || time > tl.duration()) {
					vars.modifiers = { time: timeWrap };
				}
				curIndex = newIndex;
				vars.overwrite = true;
				gsap.killTweensOf(proxy);
				return vars.duration === 0 ? tl.time(timeWrap(time)) : tl.tweenTo(time, vars);
			}

			tl.toIndex = function (index, vars) { return toIndex(index, vars); };
			tl.closestIndex = function (setCurrent) {
				var index = getClosest(times, tl.time(), tl.duration());
				if (setCurrent) { curIndex = index; indexIsDirty = false; }
				return index;
			};
			tl.current = function () { return indexIsDirty ? tl.closestIndex(true) : curIndex; };
			tl.next = function (vars) { return toIndex(tl.current() + 1, vars); };
			tl.previous = function (vars) { return toIndex(tl.current() - 1, vars); };
			tl.times = times;
			tl.progress(1, true).progress(0, true);
			if (config.reversed) { tl.vars.onReverseComplete(); tl.reverse(); }

			if (config.draggable && typeof Draggable === 'function') {
				proxy = document.createElement('div');
				var wrap = gsap.utils.wrap(0, 1),
					ratio, startProgress, draggable, lastSnap, initChangeX, wasPlaying,
					align = function () { tl.progress(wrap(startProgress + (draggable.startX - draggable.x) * ratio)); },
					syncIndex = function () { tl.closestIndex(true); };
				if (typeof InertiaPlugin === 'undefined') {
					console.warn('InertiaPlugin required for momentum-based scrolling and snapping. https://greensock.com/club');
				}
				draggable = Draggable.create(proxy, {
					trigger: items[0].parentNode,
					type: 'x',
					onPressInit: function () {
						var x = this.x;
						gsap.killTweensOf(tl);
						wasPlaying = !tl.paused();
						tl.pause();
						startProgress = tl.progress();
						refresh();
						ratio = 1 / totalWidth;
						initChangeX = (startProgress / -ratio) - x;
						gsap.set(proxy, { x: startProgress / -ratio });
					},
					onDrag: align,
					onThrowUpdate: align,
					overshootTolerance: 0,
					inertia: true,
					snap: function (value) {
						if (Math.abs(startProgress / -ratio - this.x) < 10) return lastSnap + initChangeX;
						var time = -(value * ratio) * tl.duration(),
							wrappedTime = timeWrap(time),
							snapTime = times[getClosest(times, wrappedTime, tl.duration())],
							dif = snapTime - wrappedTime;
						if (Math.abs(dif) > tl.duration() / 2) dif += dif < 0 ? tl.duration() : -tl.duration();
						lastSnap = (time + dif) / tl.duration() / -ratio;
						return lastSnap;
					},
					onRelease: function () {
						syncIndex();
						if (draggable.isThrowing) indexIsDirty = true;
					},
					onThrowComplete: function () {
						syncIndex();
						if (wasPlaying) tl.play();
					},
				})[0];
				tl.draggable = draggable;
			}

			tl.closestIndex(true);
			lastIndex = curIndex;
			if (onChange) onChange(items[curIndex], curIndex);
			timeline = tl;
			return function () { window.removeEventListener('resize', onResize); };
		});
		return timeline;
	}

	function initAll(scope) {
		var root = scope || document;
		root.querySelectorAll('[data-egsap-slider]').forEach(initOne);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () { initAll(); });
	} else {
		initAll();
	}

	if (window.elementorFrontend && window.elementorFrontend.hooks) {
		window.elementorFrontend.hooks.addAction(
			'frontend/element_ready/draggable_infinite_slider.default',
			function ($scope) { initAll($scope[0]); }
		);
	}
})();
