(function () {
	'use strict';

	var customEaseRegistered = false;
	var pluginsRegistered    = false;

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
			if (window.Draggable)     plugins.push(Draggable);
			if (window.InertiaPlugin) plugins.push(InertiaPlugin);
			if (window.CustomEase)    plugins.push(CustomEase);
			if (plugins.length) gsap.registerPlugin.apply(gsap, plugins);
			if (window.CustomEase && !customEaseRegistered) {
				if (typeof CustomEase.get !== 'function' || !CustomEase.get('radial')) {
					CustomEase.create('radial', '0.25, 0.1, 0, 1');
				}
				customEaseRegistered = true;
			}
			pluginsRegistered = true;
			return true;
		} catch (_) {
			return false;
		}
	}

	function initRadialCardsSlider(root) {
		var scope = root || document;

		scope.querySelectorAll('[data-radial-slider-init]').forEach(function (container) {
			if (isEditorPreview()) {
				return; // Skip animasi di editor — CSS override akan stack cards
			}

			if (typeof window.gsap === 'undefined' || typeof window.Draggable === 'undefined') {
				console.warn('GSAP + Draggable belum dimuat untuk Radial Cards Slider.');
				return;
			}
			ensurePlugins();
			var gsap = window.gsap;

			if (container._radialSliderDraggable) container._radialSliderDraggable.kill();
			if (container._radialSliderProxy)     gsap.killTweensOf(container._radialSliderProxy);
			if (container._radialSliderProxyEl)   container._radialSliderProxyEl.remove();

			var collection = container.querySelector('[data-radial-slider-collection]');
			var track      = container.querySelector('[data-radial-slider-list]');
			if (!collection || !track) return;

			container.querySelectorAll('[data-radial-slider-clone]').forEach(function (el) { el.remove(); });

			var originalItems = Array.prototype.slice.call(
				container.querySelectorAll('[data-radial-slider-item]:not([data-radial-slider-clone])')
			);
			if (!originalItems.length) return;

			var slideDuration = parseFloat(container.dataset.radialSlideDuration);
			if (isNaN(slideDuration) || slideDuration <= 0) slideDuration = 1;
			var clickEase = 'radial';

			container.setAttribute('role', 'region');
			container.setAttribute('aria-roledescription', 'carousel');
			container.setAttribute('aria-label', container.getAttribute('aria-label') || 'Radial Cards Slider');

			track.setAttribute('role', 'group');
			track.setAttribute('aria-label', 'Slides');

			var dotsWrap = container.querySelector('[data-radial-slider-generate-dots]');
			if (dotsWrap) {
				var dots = Array.prototype.slice.call(dotsWrap.querySelectorAll('[data-radial-slider-control]'));
				if (dots.length) {
					var firstDot = dots[0];
					dots.slice(1).forEach(function (dot) { dot.remove(); });
					firstDot.setAttribute('data-radial-slider-control', '1');
					firstDot.setAttribute('data-radial-slider-control-status', 'not-active');
					for (var i = 2; i <= originalItems.length; i++) {
						var dot = firstDot.cloneNode(true);
						dot.setAttribute('data-radial-slider-control', String(i));
						dot.setAttribute('data-radial-slider-control-status', 'not-active');
						dotsWrap.appendChild(dot);
					}
				}
			}

			var controls   = Array.prototype.slice.call(container.querySelectorAll('[data-radial-slider-control]'));
			var totalEl    = container.querySelector('[data-radial-slider-total-slide]');
			var indicators = Array.prototype.slice.call(container.querySelectorAll('[data-radial-slider-active-slide]'));

			originalItems.forEach(function (item, index) {
				item.removeAttribute('data-radial-slider-item-status');
				item.removeAttribute('aria-hidden');
				item.setAttribute('role', 'group');
				item.setAttribute('aria-label', 'Slide ' + (index + 1) + ' of ' + originalItems.length);
			});

			controls.forEach(function (btn) {
				var value = btn.getAttribute('data-radial-slider-control');
				if (value === 'prev') btn.setAttribute('aria-label', 'Previous slide');
				if (value === 'next') btn.setAttribute('aria-label', 'Next slide');
				if (/^\d+$/.test(value)) {
					btn.setAttribute('aria-label', 'Go to slide ' + value);
					btn.setAttribute('aria-current', 'false');
				}
			});

			track.style.height = '';

			var setNumber = function (el, value) {
				if (!el) return;
				el.textContent = value < 10 ? '0' + value : String(value);
			};
			var mod = function (value, total) { return ((value % total) + total) % total; };

			setNumber(totalEl, originalItems.length);

			var containerStyles = getComputedStyle(container);
			var rotateStep      = Math.abs(parseFloat(containerStyles.getPropertyValue('--slider-rotate'))) || 18;
			var maxLoopItems    = Math.max(1, Math.floor(360 / rotateStep));

			var firstRect  = originalItems[0].getBoundingClientRect();
			var itemWidth  = firstRect.width;
			var itemHeight = firstRect.height;

			var originParts = getComputedStyle(originalItems[0]).transformOrigin.split(' ');
			var originY     = parseFloat(originParts[1]) || itemHeight * 3.75;
			var wheelRadius = Math.max(0, originY - itemHeight / 2);
			var proxyRadius = wheelRadius + Math.max(itemWidth, itemHeight) * 0.525;

			var getBoundsAtAngle = function (angle) {
				var rad = angle * Math.PI / 180;
				return {
					x:          Math.sin(rad) * wheelRadius,
					y:          originY - Math.cos(rad) * wheelRadius,
					halfWidth:  Math.abs(Math.cos(rad)) * itemWidth / 2 + Math.abs(Math.sin(rad)) * itemHeight / 2,
					halfHeight: Math.abs(Math.sin(rad)) * itemWidth / 2 + Math.abs(Math.cos(rad)) * itemHeight / 2,
				};
			};

			var isOffsetInsideContainer = function (offset) {
				var containerRect = container.getBoundingClientRect();
				var trackRect     = track.getBoundingClientRect();
				var originX       = trackRect.left + trackRect.width / 2;
				var originYTop    = trackRect.top;
				var leftLimit     = containerRect.left  - originX;
				var rightLimit    = containerRect.right - originX;
				var topLimit      = containerRect.top    - originYTop;
				var bottomLimit   = containerRect.bottom - originYTop;
				var bounds = getBoundsAtAngle(offset * rotateStep);
				var cardLeft   = bounds.x - bounds.halfWidth;
				var cardRight  = bounds.x + bounds.halfWidth;
				var cardTop    = bounds.y - bounds.halfHeight;
				var cardBottom = bounds.y + bounds.halfHeight;
				return cardRight >= leftLimit && cardLeft <= rightLimit && cardBottom >= topLimit && cardTop <= bottomLimit;
			};

			var getVisibleOffsets = function () {
				var offsets  = [0];
				var maxSide  = Math.ceil(maxLoopItems / 2);
				var leftEdge = 0, rightEdge = 0;
				for (var i = 1; i <= maxSide; i++) {
					if (!isOffsetInsideContainer(i)) break;
					offsets.push(i);
					rightEdge = i;
				}
				for (var j = 1; j <= maxSide; j++) {
					if (!isOffsetInsideContainer(-j)) break;
					offsets.unshift(-j);
					leftEdge = -j;
				}
				var nextLeft  = leftEdge - 1;
				var nextRight = rightEdge + 1;
				if (Math.abs(nextLeft)  <= maxSide) offsets.unshift(nextLeft);
				if (Math.abs(nextRight) <= maxSide) offsets.push(nextRight);
				return offsets;
			};

			var visibleOffsets  = getVisibleOffsets();
			var minItemsNeeded  = Math.min(maxLoopItems, Math.max(originalItems.length, visibleOffsets.length));
			var neededItems     = Math.ceil(minItemsNeeded / originalItems.length) * originalItems.length;

			var currentItems = Array.prototype.slice.call(
				container.querySelectorAll('[data-radial-slider-item]:not([data-radial-slider-clone])')
			);

			for (var k = currentItems.length; k < neededItems; k++) {
				var clone = currentItems[k % currentItems.length].cloneNode(true);
				clone.setAttribute('data-radial-slider-clone', '');
				clone.setAttribute('aria-hidden', 'true');
				track.appendChild(clone);
			}

			var items = Array.prototype.slice.call(track.querySelectorAll(':scope > [data-radial-slider-item]'));
			var totalItems = items.length;

			track.style.height = itemHeight + 'px';

			items.forEach(function (item) {
				item.setAttribute('data-radial-slider-item-status', 'not-active');
			});

			container.setAttribute('data-radial-slider-drag-status', 'grab');

			var containerRect  = container.getBoundingClientRect();
			var collectionRect = collection.getBoundingClientRect();
			var trackRect      = track.getBoundingClientRect();

			var proxyWrap = document.createElement('div');
			proxyWrap.setAttribute('data-radial-slider-proxy-wrap', '');
			Object.assign(proxyWrap.style, {
				position:      'absolute',
				left:          containerRect.left - collectionRect.left + 'px',
				top:           containerRect.top  - collectionRect.top  + 'px',
				width:         containerRect.width + 'px',
				height:        containerRect.height + 'px',
				overflow:      'hidden',
				pointerEvents: 'none',
			});

			var proxy = document.createElement('div');
			proxy.setAttribute('data-radial-slider-proxy', '');
			Object.assign(proxy.style, {
				position:      'absolute',
				width:         (proxyRadius * 2) + 'px',
				height:        (proxyRadius * 2) + 'px',
				left:          trackRect.left + trackRect.width / 2 - containerRect.left + 'px',
				top:           trackRect.top - containerRect.top + originY - proxyRadius + 'px',
				transform:     'translateX(-50%)',
				borderRadius:  '50%',
				pointerEvents: 'auto',
				opacity:       '0',
			});

			proxyWrap.appendChild(proxy);
			collection.appendChild(proxyWrap);

			container._radialSliderProxy   = proxy;
			container._radialSliderProxyEl = proxyWrap;

			var setRotation = items.map(function (item) { return gsap.quickSetter(item, 'rotation', 'deg'); });

			gsap.set(proxy, { rotation: 0 });

			var getIndexFromProxy = function () { return -gsap.getProperty(proxy, 'rotation') / rotateStep; };

			var nearestDelta = function (index, realIndex, total) {
				var loop = Math.round((realIndex - index) / total);
				return index - (realIndex - loop * total);
			};

			var nearestDeltaToSlideNumber = function (targetNumber, realIndex) {
				var bestDelta    = 0;
				var bestDistance = Infinity;
				items.forEach(function (item, index) {
					var slideNumber = index % originalItems.length;
					if (slideNumber !== targetNumber) return;
					var delta    = nearestDelta(index, realIndex, totalItems);
					var distance = Math.abs(delta);
					if (distance < bestDistance) {
						bestDistance = distance;
						bestDelta    = delta;
					}
				});
				return bestDelta;
			};

			var lastActiveIndex = null;

			var setIndicator = function (index) {
				var value = index + 1;
				var text  = value < 10 ? '0' + value : String(value);
				indicators.forEach(function (el) { el.textContent = text; });
			};

			var updateControlStatus = function (activeIndex) {
				controls.forEach(function (btn) {
					var value = btn.getAttribute('data-radial-slider-control');
					if (!/^\d+$/.test(value)) return;
					var index    = Math.max(0, Math.min(originalItems.length - 1, parseInt(value, 10) - 1));
					var isActive = index === activeIndex;
					btn.setAttribute('data-radial-slider-control-status', isActive ? 'active' : 'not-active');
					btn.setAttribute('aria-current', isActive ? 'true' : 'false');
				});
			};

			var updateActiveUI = function (activeIndex) {
				if (activeIndex === lastActiveIndex) return;
				setIndicator(activeIndex);
				updateControlStatus(activeIndex);
				lastActiveIndex = activeIndex;
			};

			var render = function () {
				var realIndex        = getIndexFromProxy();
				var activeIndex      = mod(Math.round(realIndex), totalItems);
				var activeSlideIndex = activeIndex % originalItems.length;
				items.forEach(function (item, index) {
					var rotation = nearestDelta(index, realIndex, totalItems) * rotateStep;
					item.setAttribute('data-radial-slider-item-status', index === activeIndex ? 'active' : 'inview');
					setRotation[index](rotation);
				});
				updateActiveUI(activeSlideIndex);
			};

			controls.forEach(function (btn) {
				btn.disabled = false;
				var value = btn.getAttribute('data-radial-slider-control');

				if (value === 'next' || value === 'prev') {
					btn.onclick = function () {
						gsap.killTweensOf(proxy);
						var currentIndex = getIndexFromProxy();
						var targetIndex  = Math.round(currentIndex) + (value === 'next' ? 1 : -1);
						gsap.to(proxy, {
							rotation: -targetIndex * rotateStep,
							duration: slideDuration,
							ease:     clickEase,
							onUpdate: render,
						});
					};
				}

				if (/^\d+$/.test(value)) {
					var targetSlideNumber = Math.max(0, Math.min(originalItems.length - 1, parseInt(value, 10) - 1));
					btn.onclick = function () {
						gsap.killTweensOf(proxy);
						var currentIndex = getIndexFromProxy();
						var delta        = nearestDeltaToSlideNumber(targetSlideNumber, currentIndex);
						gsap.to(proxy, {
							rotation: -(currentIndex + delta) * rotateStep,
							duration: slideDuration,
							ease:     clickEase,
							onUpdate: render,
						});
					};
				}
			});

			container._radialSliderDraggable = Draggable.create(proxy, {
				type:              'rotation',
				trigger:           [ proxy ].concat(items),
				inertia:           true,
				throwResistance:   2000,
				dragResistance:    0.05,
				maxDuration:       1,
				minDuration:       0.5,
				edgeResistance:    0.75,
				overshootTolerance: 0,
				snap: function (value) { return Math.round(value / rotateStep) * rotateStep; },
				onDrag:           render,
				onThrowUpdate:    render,
				onThrowComplete:  function () {
					container.setAttribute('data-radial-slider-drag-status', 'grab');
					render();
				},
				onPress:      function () { container.setAttribute('data-radial-slider-drag-status', 'grabbing'); },
				onDragStart:  function () { container.setAttribute('data-radial-slider-drag-status', 'grabbing'); },
				onRelease:    function () { container.setAttribute('data-radial-slider-drag-status', 'grab'); },
			})[0];

			render();
		});
	}

	function debounceOnWidthChange(fn, ms) {
		var lastWidth = window.innerWidth;
		var timer;
		return function () {
			var args = arguments;
			var self = this;
			clearTimeout(timer);
			timer = setTimeout(function () {
				if (window.innerWidth === lastWidth) return;
				lastWidth = window.innerWidth;
				fn.apply(self, args);
			}, ms);
		};
	}

	function initAll() {
		initRadialCardsSlider(document);
		if (initAll._resize) window.removeEventListener('resize', initAll._resize);
		initAll._resize = debounceOnWidthChange(function () { initRadialCardsSlider(document); }, 200);
		window.addEventListener('resize', initAll._resize);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () { initAll(); });
	} else {
		initAll();
	}

	if (window.elementorFrontend && window.elementorFrontend.hooks) {
		window.elementorFrontend.hooks.addAction('frontend/init', function () { initAll(); });
	}
})();
