(function () {
	'use strict';

	function isEditorPreview() {
		return !!(window.elementorFrontend
			&& typeof window.elementorFrontend.isEditMode === 'function'
			&& window.elementorFrontend.isEditMode());
	}

	function initDroppingCardsStack(stackEl) {
		if (!stackEl) return;
		if (stackEl.dataset.egsapDcsInit === '1') return;
		stackEl.dataset.egsapDcsInit = '1';

		if (isEditorPreview()) return;

		if (typeof window.gsap === 'undefined'
			|| typeof window.Draggable === 'undefined'
			|| typeof window.CustomEase === 'undefined') {
			console.warn('GSAP / Draggable / CustomEase belum dimuat untuk Dropping Cards Stack.');
			return;
		}
		var gsap = window.gsap;
		var Draggable = window.Draggable;
		var CustomEase = window.CustomEase;

		gsap.registerPlugin(Draggable, CustomEase);
		if (!gsap.parseEase('osmo')) {
			CustomEase.create('osmo', '0.625, 0.05, 0, 1');
		}

		var visibleCount        = parseInt(stackEl.dataset.egsapDcsVisible, 10) || 4;
		var minTotalForLoop     = 5;
		var duration            = parseFloat(stackEl.dataset.egsapDcsDuration) || 0.75;
		var mainEase            = 'osmo';
		var dragThresholdPercent = parseFloat(stackEl.dataset.egsapDcsThreshold) || 20;

		function getUnitValue(val, depth) {
			var num  = parseFloat(val) || 0;
			var unit = String(val).replace(/[0-9.-]/g, '') || 'px';
			return (num * depth) + unit;
		}

		var nextBtn = stackEl.querySelector('[data-egsap-dcs-next]');
		var prevBtn = stackEl.querySelector('[data-egsap-dcs-prev]');
		var list    = stackEl.querySelector('.egsap-dcs__list');
		if (!list) return;

		var cards = Array.prototype.slice.call(list.querySelectorAll('[data-egsap-dcs-item]'));
		if (cards.length < 3) return;

		var originalCount = cards.length;
		if (cards.length < minTotalForLoop) {
			var setsNeeded  = Math.ceil(minTotalForLoop / originalCount);
			var clonesToAdd = (setsNeeded * originalCount) - originalCount;
			for (var i = 0; i < clonesToAdd; i++) {
				var clone = cards[i % originalCount].cloneNode(true);
				clone.setAttribute('aria-hidden', 'true');
				list.appendChild(clone);
			}
			cards = Array.prototype.slice.call(list.querySelectorAll('[data-egsap-dcs-item]'));
		}

		var total = cards.length;
		var activeIndex = 0;
		var isAnimating = false;

		var dragCard = null;
		var draggableInstance = null;

		var limitX = 1, limitY = 1;
		var offsetX = '0em', offsetY = '0em';

		var isActive = false;

		function mod(n, m) { return ((n % m) + m) % m; }
		function cardAt(offset) { return cards[mod(activeIndex + offset, total)]; }

		function updateOffsetsFromPadding() {
			var collectionEl = stackEl.querySelector('[data-egsap-dcs-collection]');
			if (!collectionEl) return;
			var styles = getComputedStyle(collectionEl);
			var padRight  = parseFloat(styles.paddingRight)  || 0;
			var padLeft   = parseFloat(styles.paddingLeft)   || 0;
			var padBottom = parseFloat(styles.paddingBottom) || 0;
			var padTop    = parseFloat(styles.paddingTop)    || 0;

			var steps = Math.max(1, visibleCount - 1);
			var usePadX = Math.max(padRight, padLeft);
			var usePadY = Math.max(padBottom, padTop);
			var signX = padLeft > padRight ? -1 : 1;
			var signY = padTop  > padBottom ? -1 : 1;

			var xStep = (usePadX / steps) * signX;
			var yStep = (usePadY / steps) * signY;
			offsetX = xStep + 'px';
			offsetY = yStep + 'px';
		}

		function updateDragLimits() {
			if (!dragCard) return;
			var r = dragCard.getBoundingClientRect();
			limitX = r.width  || 1;
			limitY = r.height || 1;
		}

		function applyState() {
			updateOffsetsFromPadding();

			cards.forEach(function (card) {
				gsap.set(card, { opacity: 0, pointerEvents: 'none', zIndex: 0, x: 0, y: 0, xPercent: 0, yPercent: 0 });
			});

			for (var depth = 0; depth < visibleCount; depth++) {
				var card = cardAt(depth);
				var xVal = getUnitValue(offsetX, depth);
				var yVal = getUnitValue(offsetY, depth);

				var state = { opacity: 1, zIndex: 999 - depth, pointerEvents: depth === 0 ? 'auto' : 'none' };
				if (offsetX.indexOf('%') >= 0) state.xPercent = parseFloat(xVal); else state.x = xVal;
				if (offsetY.indexOf('%') >= 0) state.yPercent = parseFloat(yVal); else state.y = yVal;

				gsap.set(card, state);
			}

			dragCard = cardAt(0);
			gsap.set(dragCard, { touchAction: 'none' });

			updateDragLimits();

			if (draggableInstance) {
				draggableInstance.kill();
				draggableInstance = null;
			}

			function magnetize(raw, limit) {
				var sign = Math.sign(raw) || 1;
				var abs = Math.abs(raw);
				var out = limit * Math.tanh(abs / limit);
				return sign * out;
			}

			draggableInstance = Draggable.create(dragCard, {
				type: 'x,y',
				inertia: false,
				onPress: function () {
					if (isAnimating) return;
					gsap.killTweensOf(dragCard);
					gsap.set(dragCard, { zIndex: 2000, opacity: 1 });
				},
				onDrag: function () {
					if (isAnimating) return;
					var x = magnetize(this.x, limitX);
					var y = magnetize(this.y, limitY);
					gsap.set(dragCard, { x: x, y: y, opacity: 1 });
				},
				onRelease: function () {
					if (isAnimating) return;
					var currentX = gsap.getProperty(dragCard, 'x');
					var currentY = gsap.getProperty(dragCard, 'y');
					var movedXPercent = Math.abs(currentX) / limitX * 100;
					var movedYPercent = Math.abs(currentY) / limitY * 100;
					var movedPercent  = Math.max(movedXPercent, movedYPercent);

					if (movedPercent >= dragThresholdPercent) {
						animateNext(true, currentX, currentY);
						return;
					}
					gsap.to(dragCard, {
						x: 0, y: 0, opacity: 1,
						duration: 1,
						ease: 'elastic.out(1, 0.7)',
						onComplete: applyState,
					});
				},
			})[0];
		}

		function animateNext(fromDrag, releaseX, releaseY) {
			if (isAnimating) return;
			isAnimating = true;

			var outgoing = cardAt(0);
			var incomingBack = cardAt(visibleCount);
			var tl = gsap.timeline({
				defaults: { duration: duration, ease: mainEase },
				onComplete: function () {
					activeIndex = mod(activeIndex + 1, total);
					applyState();
					isAnimating = false;
				},
			});

			gsap.set(outgoing, { zIndex: 2000, opacity: 1 });
			if (fromDrag) gsap.set(outgoing, { x: releaseX, y: releaseY });

			tl.to(outgoing, { yPercent: 200 }, 0);
			tl.to(outgoing, { opacity: 0, duration: duration * 0.2, ease: 'none' }, duration * 0.4);

			for (var depth = 1; depth < visibleCount; depth++) {
				var xVal = getUnitValue(offsetX, depth - 1);
				var yVal = getUnitValue(offsetY, depth - 1);
				var move = { zIndex: 999 - (depth - 1) };
				if (offsetX.indexOf('%') >= 0) move.xPercent = parseFloat(xVal); else move.x = xVal;
				if (offsetY.indexOf('%') >= 0) move.yPercent = parseFloat(yVal); else move.y = yVal;
				tl.to(cardAt(depth), move, 0);
			}

			var backX  = getUnitValue(offsetX, visibleCount);
			var backY  = getUnitValue(offsetY, visibleCount);
			var startX = getUnitValue(offsetX, visibleCount - 1);
			var startY = getUnitValue(offsetY, visibleCount - 1);

			var incomingSet = { opacity: 0, zIndex: 999 - visibleCount };
			if (offsetX.indexOf('%') >= 0) incomingSet.xPercent = parseFloat(backX); else incomingSet.x = backX;
			if (offsetY.indexOf('%') >= 0) incomingSet.yPercent = parseFloat(backY); else incomingSet.y = backY;
			gsap.set(incomingBack, incomingSet);

			var incomingTo = { opacity: 1 };
			if (offsetX.indexOf('%') >= 0) incomingTo.xPercent = parseFloat(startX); else incomingTo.x = startX;
			if (offsetY.indexOf('%') >= 0) incomingTo.yPercent = parseFloat(startY); else incomingTo.y = startY;
			tl.to(incomingBack, incomingTo, 0);
		}

		function animatePrev() {
			if (isAnimating) return;
			isAnimating = true;

			var incomingTop = cardAt(-1);
			var leavingBack = cardAt(visibleCount - 1);
			var tl = gsap.timeline({
				defaults: { duration: duration, ease: mainEase },
				onComplete: function () {
					activeIndex = mod(activeIndex - 1, total);
					applyState();
					isAnimating = false;
				},
			});

			gsap.set(leavingBack, { zIndex: 1 });

			gsap.set(incomingTop, { opacity: 0, x: 0, xPercent: 0, yPercent: -200, zIndex: 2000 });
			tl.to(incomingTop, { yPercent: 0 }, 0);
			tl.to(incomingTop, { opacity: 1, duration: duration * 0.2, ease: 'none' }, duration * 0.3);

			for (var depth = 0; depth < visibleCount - 1; depth++) {
				var xVal = getUnitValue(offsetX, depth + 1);
				var yVal = getUnitValue(offsetY, depth + 1);
				var move = { zIndex: 999 - (depth + 1) };
				if (offsetX.indexOf('%') >= 0) move.xPercent = parseFloat(xVal); else move.x = xVal;
				if (offsetY.indexOf('%') >= 0) move.yPercent = parseFloat(yVal); else move.y = yVal;
				tl.to(cardAt(depth), move, 0);
			}

			var backX = getUnitValue(offsetX, visibleCount);
			var backY = getUnitValue(offsetY, visibleCount);
			var hideBack = { opacity: 0 };
			if (offsetX.indexOf('%') >= 0) hideBack.xPercent = parseFloat(backX); else hideBack.x = backX;
			if (offsetY.indexOf('%') >= 0) hideBack.yPercent = parseFloat(backY); else hideBack.y = backY;
			tl.to(leavingBack, hideBack, 0);
		}

		var observer = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				isActive = entry.isIntersecting && entry.intersectionRatio >= 0.6;
			});
		}, { threshold: [ 0, 0.6, 1 ] });
		observer.observe(stackEl);

		function onKeyDown(e) {
			if (!isActive) return;
			if (isAnimating) return;
			var tag = (e.target && e.target.tagName) ? e.target.tagName.toLowerCase() : '';
			var isTyping = tag === 'input' || tag === 'textarea' || tag === 'select' || (e.target && e.target.isContentEditable);
			if (isTyping) return;
			if (e.key === 'ArrowRight') { e.preventDefault(); animateNext(false); }
			if (e.key === 'ArrowLeft')  { e.preventDefault(); animatePrev(); }
		}
		window.addEventListener('keydown', onKeyDown);

		applyState();

		if (nextBtn) nextBtn.addEventListener('click', function () { animateNext(false); });
		if (prevBtn) prevBtn.addEventListener('click', animatePrev);

		window.addEventListener('resize', applyState);
	}

	function initAll(scope) {
		(scope || document).querySelectorAll('[data-egsap-dcs-init]').forEach(initDroppingCardsStack);
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
