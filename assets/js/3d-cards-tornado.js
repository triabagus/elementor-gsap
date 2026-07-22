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

	function init3DCardsTornado(container) {
		if (!container) return;
		if (container.dataset.egsapTorInit === '1') return;
		container.dataset.egsapTorInit = '1';

		var editorMode = isEditorPreview();

		if (typeof window.gsap === 'undefined') {
			console.warn('GSAP belum dimuat untuk 3D Cards Tornado.');
			return;
		}

		var gsap = window.gsap;
		var Observer = window.Observer;
		var ScrollTrigger = window.ScrollTrigger;

		/* Editor mode — cukup butuh GSAP core untuk build tornado static.
		   Observer/ScrollTrigger cuma dipakai di frontend runtime. */
		if (!editorMode) {
			if (typeof Observer === 'undefined' || typeof ScrollTrigger === 'undefined') {
				console.warn('GSAP Observer / ScrollTrigger belum dimuat untuk 3D Cards Tornado.');
				return;
			}
			gsap.registerPlugin(Observer, ScrollTrigger);
		}

		/* Config — dibaca dari data-egsap-tor-* attributes (yang di-set PHP
		   dari Elementor controls), dengan fallback ke default reference. */
		var rotationAngle  = num(container, 'egsapTorRotationAngle',  30);
		var cardYSpacing   = num(container, 'egsapTorCardYSpacing',   0.3);
		var edgeOffset     = num(container, 'egsapTorEdgeOffset',     2);
		var orbitDepth     = num(container, 'egsapTorOrbitDepth',     35);
		var autoSpeed      = num(container, 'egsapTorAutoSpeed',      0.00325);
		var scrollSpeed    = num(container, 'egsapTorScrollSpeed',    0.015);
		var dragMultiplier = num(container, 'egsapTorDragMultiplier', 5);
		var scrollEase     = num(container, 'egsapTorScrollEase',     0.1);
		var maxSpeed       = num(container, 'egsapTorMaxSpeed',       0.2);
		var edgeScale      = num(container, 'egsapTorEdgeScale',      0.5);
		var minScale       = num(container, 'egsapTorMinScale',       1);
		var backDarkness   = num(container, 'egsapTorBackDarkness',   0.75);
		var backBlur       = num(container, 'egsapTorBackBlur',       0.5);
		var edgeEase       = gsap.parseEase('power2.inOut');

		var list = container.querySelector('[data-egsap-tor-list]');
		if (!list) return;

		var originalCards = Array.prototype.slice.call(list.querySelectorAll('[data-egsap-tor-item]'))
			.map(function (c) { return c.cloneNode(true); });
		if (!originalCards.length) return;

		var inputObserver = null;
		var resizeTimer   = null;

		var state = {
			amount:     0,
			progress:   0,
			velocity:   autoSpeed,
			direction:  1,
			cardHeight: 0,
			cardGap:    0,
			em:         16,
			isActive:   false,
			cards:      [],
		};

		function getCardAmount() {
			var containerHalfHeight = container.offsetHeight * 0.5;
			var edgeOffsetDistance  = state.cardHeight * edgeOffset;
			var fadeDistance        = state.cardHeight * edgeScale;
			var neededDistance      = containerHalfHeight + edgeOffsetDistance + fadeDistance;
			var cardsPerSide        = Math.ceil(neededDistance / state.cardGap) + 1;
			var neededAmount        = cardsPerSide * 2 + 1;
			var batchCount          = Math.ceil(neededAmount / originalCards.length);
			return originalCards.length * batchCount;
		}

		function buildCards() {
			list.innerHTML = '';

			var measureCard = originalCards[0].cloneNode(true);
			list.appendChild(measureCard);
			state.cardHeight = measureCard.offsetHeight;
			state.cardGap    = state.cardHeight * cardYSpacing;
			state.em         = parseFloat(getComputedStyle(measureCard).fontSize);
			state.amount     = getCardAmount();
			list.innerHTML   = '';

			for (var i = 0; i < state.amount; i++) {
				var card = originalCards[i % originalCards.length].cloneNode(true);
				card.dataset.index = i;
				list.appendChild(card);
			}
			state.cards = Array.prototype.slice.call(list.querySelectorAll('[data-egsap-tor-item]'));
		}

		function getEdgeScale(y) {
			var containerHalfHeight = container.offsetHeight * 0.5;
			var edgeOffsetDistance  = state.cardHeight * edgeOffset;
			var fadeDistance        = state.cardHeight * edgeScale;
			var distanceFromCenter  = Math.abs(y);
			var fadeStart           = containerHalfHeight + edgeOffsetDistance;
			var progress            = gsap.utils.clamp(0, 1, (fadeStart - distanceFromCenter) / fadeDistance);
			return edgeEase(progress);
		}

		function render() {
			var radius = orbitDepth * state.em;

			state.cards.forEach(function (card) {
				var startIndex = parseFloat(card.dataset.index);
				var loopIndex  = ((startIndex + state.progress) % state.amount + state.amount) % state.amount;
				var index      = loopIndex > state.amount * 0.5 ? loopIndex - state.amount : loopIndex;
				var angleDeg   = index * rotationAngle;
				var angleRad   = angleDeg * Math.PI / 180;
				var center     = 1 - Math.min(Math.abs(index) / (state.amount * 0.5), 1);
				var y          = index * state.cardGap;
				var baseScale  = minScale + center * (1 - minScale);
				var scale      = baseScale * getEdgeScale(y);
				var backAmount = gsap.utils.clamp(0, 1, (1 - Math.cos(angleRad)) * 0.5);
				var brightness = 1 - backAmount * backDarkness;
				var blur       = backAmount * backBlur;

				gsap.set(card, {
					xPercent: -50,
					yPercent: -50,
					x:        Math.sin(angleRad) * radius,
					y:        y,
					z:        (Math.cos(angleRad) - 1) * radius,
					rotateY:  angleDeg,
					scale:    scale,
					filter:   'brightness(' + brightness + ') blur(' + blur + 'em)',
					autoAlpha: 1,
					zIndex:   Math.round(center * 1000),
				});
			});
		}

		function tick() {
			if (!state.isActive) return;
			var targetVelocity = autoSpeed * state.direction;
			state.velocity = gsap.utils.interpolate(state.velocity, targetVelocity, scrollEase);
			state.progress += state.velocity;
			render();
		}

		function handleInput(self) {
			if (!state.isActive) return;
			var delta;
			if (self.event.type === 'wheel') {
				delta = self.deltaY;
			} else {
				delta = Math.abs(self.deltaX) > Math.abs(self.deltaY)
					? self.deltaX * dragMultiplier
					: self.deltaY * dragMultiplier;
			}
			if (!delta) return;
			state.direction = delta > 0 ? 1 : -1;
			state.velocity += delta * scrollSpeed / 100;
			state.velocity = gsap.utils.clamp(-maxSpeed, maxSpeed, state.velocity);
		}

		function setActive(isActive) {
			state.isActive = isActive;
			if (!inputObserver) return;
			if (isActive) inputObserver.enable();
			else inputObserver.disable();
		}

		function rebuild() {
			buildCards();
			render();
		}

		rebuild();

		/* Editor mode: build + slow auto-rotate preview supaya user lihat
		   tornado geometry apa adanya. Skip Observer/ScrollTrigger yang
		   bermasalah di iframe editor. */
		if (editorMode) {
			state.isActive = true;
			var editorTick = function () {
				if (!container.isConnected) { gsap.ticker.remove(editorTick); return; }
				state.progress += autoSpeed;
				render();
			};
			gsap.ticker.add(editorTick);
			return;
		}

		/* Frontend runtime — full Observer + ScrollTrigger + ticker. */
		inputObserver = Observer.create({
			target: window,
			type: 'wheel,touch,pointer',
			preventDefault: false,
			lockAxis: true,
			onChange: handleInput,
			onPress: function () { container.style.cursor = 'grabbing'; },
			onRelease: function () { container.style.cursor = 'grab'; },
		});

		ScrollTrigger.create({
			trigger: container,
			start: 'top bottom',
			end: 'bottom top',
			onEnter: function () { setActive(true); },
			onEnterBack: function () { setActive(true); },
			onLeave: function () { setActive(false); },
			onLeaveBack: function () { setActive(false); },
		});

		setActive(ScrollTrigger.isInViewport(container));
		gsap.ticker.add(tick);

		window.addEventListener('resize', function () {
			clearTimeout(resizeTimer);
			resizeTimer = setTimeout(function () {
				rebuild();
				ScrollTrigger.refresh();
			}, 150);
		});
	}

	function initAll(scope) {
		(scope || document).querySelectorAll('[data-egsap-tor]').forEach(init3DCardsTornado);
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
