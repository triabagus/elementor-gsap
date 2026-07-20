(function () {
	'use strict';

	var customEaseRegistered = false;
	var instances = new Map();

	function isEditorPreview() {
		return !!(window.elementorFrontend
			&& typeof window.elementorFrontend.isEditMode === 'function'
			&& window.elementorFrontend.isEditMode());
	}

	function ensureCustomEase() {
		if (customEaseRegistered) return true;
		if (typeof window.gsap === 'undefined' || typeof window.CustomEase === 'undefined') return false;
		try {
			gsap.registerPlugin(CustomEase);
			if (typeof CustomEase.get !== 'function' || !CustomEase.get('osmo')) {
				CustomEase.create('osmo', 'M0,0 C0.625,0.05 0,1 1,1');
			}
			customEaseRegistered = true;
			return true;
		} catch (_) {
			return false;
		}
	}

	function parsePattern(str, fallback) {
		if (!str) return fallback.slice();
		var parts = String(str).split(',').map(function (v) { return parseFloat(v); }).filter(function (v) { return !isNaN(v); });
		return parts.length ? parts : fallback.slice();
	}

	function initDroppingCardsLoader(container) {
		if (!container) return;
		if (container.dataset.egsapDclInit === '1') return;
		container.dataset.egsapDclInit = '1';

		// Editor: skip animation — loader tidak render di editor sama sekali
		// (extension render_loader() sudah skip via is_editor_preview di PHP).
		// Guard tambahan di sini kalau ternyata masuk juga.
		if (isEditorPreview()) {
			container.style.display = 'none';
			return;
		}

		if (typeof window.gsap === 'undefined') {
			console.warn('GSAP belum dimuat untuk Dropping Cards Loader.');
			return;
		}
		var gsap = window.gsap;
		ensureCustomEase();

		var cardsList  = gsap.utils.toArray(container.querySelectorAll('[data-loading-cards-list]'));
		var cards      = gsap.utils.toArray(container.querySelectorAll('[data-loading-card]'));
		var background = container.querySelectorAll('[data-loading-background]');
		var logo       = container.querySelectorAll('[data-loading-logo]');
		var header     = document.querySelectorAll('[data-loading-header]');

		var scaleDecrease     = parseFloat(container.dataset.egsapDclScaleDecrease);
		if (isNaN(scaleDecrease))     scaleDecrease = 0.1;
		var yOffset           = parseFloat(container.dataset.egsapDclYOffset);
		if (isNaN(yOffset))           yOffset = -7.5;
		var totalFallStagger  = parseFloat(container.dataset.egsapDclFallStagger);
		if (isNaN(totalFallStagger) || totalFallStagger < 0) totalFallStagger = 0.75;
		var deckMoveDuration  = parseFloat(container.dataset.egsapDclDeckDuration);
		if (isNaN(deckMoveDuration) || deckMoveDuration <= 0) deckMoveDuration = 1;

		var rotationPattern = parsePattern(container.dataset.egsapDclRotationPattern, [ -10, 10, -15, 10, 20 ]);
		var xPattern        = parsePattern(container.dataset.egsapDclXPattern,        [ -5, 7.5, 10, 5, -10 ]);

		var has          = function (items) { return items.length; };
		var patternValue = function (pattern, index) { return pattern[index % pattern.length]; };

		function getStack(index, total) {
			var reverseIndex = total - 1 - index;
			return {
				scale:    1 - ( reverseIndex * scaleDecrease ),
				yPercent: reverseIndex * yOffset,
			};
		}
		function stackProp(prop, total) {
			return function (index) { return getStack(index, total)[prop]; };
		}
		function getFallY(card) {
			var containerRect = container.getBoundingClientRect();
			var cardRect      = card.getBoundingClientRect();
			return ( containerRect.bottom - cardRect.top ) + cardRect.height;
		}

		document.body.setAttribute('data-egsap-dcl-status', 'loading');

		var tl = gsap.timeline({
			onComplete: function () {
				document.body.setAttribute('data-egsap-dcl-status', 'done');
				container.style.pointerEvents = 'none';
			},
		});

		if (has(cardsList)) {
			tl.fromTo(cardsList,
				{ opacity: 0 },
				{ opacity: 1, duration: 0.3 },
				0.5
			);
		}

		if (has(cards)) {
			tl.fromTo(cards,
				{ rotate: 0.001, scale: 0.5, yPercent: 0 },
				{
					rotate:   0.001,
					scale:    stackProp('scale', cards.length),
					yPercent: stackProp('yPercent', cards.length),
					stagger:  -0.05,
					duration: 1.5,
					ease:     'elastic.out(1,0.7)',
				},
				'<'
			);

			var fallCards   = cards.slice().reverse();
			var fallStagger = totalFallStagger / Math.max(cards.length - 1, 1);
			var fallStart   = tl.duration();

			fallCards.forEach(function (card, fallIndex) {
				var remainingCards = cards.slice(0, cards.indexOf(card));
				var fallTime       = fallStart + ( fallIndex * fallStagger );

				if (has(remainingCards)) {
					tl.to(remainingCards, {
						scale:    stackProp('scale', remainingCards.length),
						yPercent: stackProp('yPercent', remainingCards.length),
						duration: deckMoveDuration,
						ease:     'sine.inOut',
					}, fallTime);
				}

				tl.to(card, {
					y:        function () { return getFallY(card); },
					xPercent: patternValue(xPattern, fallIndex),
					rotate:   patternValue(rotationPattern, fallIndex),
					duration: 0.8,
					ease:     'power4.in',
				}, fallTime);
			});
		}

		if (has(background)) {
			tl.to(background, {
				rotate:   0.001,
				yPercent: 100,
				duration: 1.5,
				ease:     'osmo',
			}, '-=0.6');
		}

		if (has(header)) {
			tl.from(header, {
				rotate:   0.001,
				yPercent: -25,
				scale:    1.1,
				duration: 1.5,
				ease:     'osmo',
			}, '<');
		}

		if (has(logo)) {
			tl.to(logo, {
				rotate:   0.001,
				yPercent: 100,
				opacity:  0,
				duration: 0.8,
				ease:     'power4.in',
			}, '<-=1.5');
		}

		instances.set(container, { timeline: tl });
	}

	function initAll(scope) {
		(scope || document).querySelectorAll('[data-loading-container][data-egsap-dcl]').forEach(initDroppingCardsLoader);
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
