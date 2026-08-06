(function () {
	'use strict';

	function isEditorPreview() {
		return !!(window.elementorFrontend
			&& typeof window.elementorFrontend.isEditMode === 'function'
			&& window.elementorFrontend.isEditMode());
	}

	function getNumberAttr(el, name, fallback) {
		var v = parseFloat(el.getAttribute(name));
		return Number.isFinite(v) ? v : fallback;
	}

	function initDraggableMarquee(wrapper) {
		if (!wrapper) return;
		if (wrapper.dataset.egsapDmqInit === 'initialized') return;

		if (isEditorPreview()) return;

		if (typeof window.gsap === 'undefined'
			|| typeof window.Observer === 'undefined'
			|| typeof window.ScrollTrigger === 'undefined') {
			console.warn('GSAP / Observer / ScrollTrigger belum dimuat untuk Draggable Marquee.');
			return;
		}
		var gsap = window.gsap;
		var Observer = window.Observer;
		var ScrollTrigger = window.ScrollTrigger;

		gsap.registerPlugin(Observer, ScrollTrigger);

		var collection = wrapper.querySelector('[data-egsap-dmq-collection]');
		var list       = wrapper.querySelector('[data-egsap-dmq-list]');
		if (!collection || !list) return;

		var duration    = getNumberAttr(wrapper, 'data-egsap-dmq-duration',    20);
		var multiplier  = getNumberAttr(wrapper, 'data-egsap-dmq-multiplier',  40);
		var sensitivity = getNumberAttr(wrapper, 'data-egsap-dmq-sensitivity', 0.01);

		var wrapperWidth = wrapper.getBoundingClientRect().width;
		var listWidth    = list.scrollWidth || list.getBoundingClientRect().width;
		if (!wrapperWidth || !listWidth) return;

		var minRequiredWidth = wrapperWidth + listWidth + 2;
		while (collection.scrollWidth < minRequiredWidth) {
			var listClone = list.cloneNode(true);
			listClone.setAttribute('data-egsap-dmq-clone', '');
			listClone.setAttribute('aria-hidden', 'true');
			collection.appendChild(listClone);
		}

		var wrapX = gsap.utils.wrap(-listWidth, 0);

		gsap.set(collection, { x: 0 });

		var marqueeLoop = gsap.to(collection, {
			x: -listWidth,
			duration: duration,
			ease: 'none',
			repeat: -1,
			onReverseComplete: function () { marqueeLoop.progress(1); },
			modifiers: {
				x: function (x) { return wrapX(parseFloat(x)) + 'px'; },
			},
		});

		var initialDirectionAttr = (wrapper.getAttribute('data-egsap-dmq-direction') || 'left').toLowerCase();
		var baseDirection = initialDirectionAttr === 'right' ? -1 : 1;

		var timeScale = { value: baseDirection };
		wrapper.setAttribute('data-egsap-dmq-direction', baseDirection < 0 ? 'right' : 'left');

		if (baseDirection < 0) marqueeLoop.progress(1);

		function applyTimeScale() {
			marqueeLoop.timeScale(timeScale.value);
			wrapper.setAttribute('data-egsap-dmq-direction', timeScale.value < 0 ? 'right' : 'left');
		}
		applyTimeScale();

		var marqueeObserver = Observer.create({
			target: wrapper,
			type: 'pointer,touch',
			preventDefault: true,
			debounce: false,
			onChangeX: function (observerEvent) {
				var velocityTimeScale = observerEvent.velocityX * -sensitivity;
				velocityTimeScale = gsap.utils.clamp(-multiplier, multiplier, velocityTimeScale);

				gsap.killTweensOf(timeScale);

				var restingDirection = velocityTimeScale < 0 ? -1 : 1;

				gsap.timeline({ onUpdate: applyTimeScale })
					.to(timeScale, { value: velocityTimeScale, duration: 0.1, overwrite: true })
					.to(timeScale, { value: restingDirection,   duration: 1.0 });
			},
		});

		ScrollTrigger.create({
			trigger: wrapper,
			start: 'top bottom',
			end:   'bottom top',
			onEnter:     function () { marqueeLoop.resume(); applyTimeScale(); marqueeObserver.enable();  },
			onEnterBack: function () { marqueeLoop.resume(); applyTimeScale(); marqueeObserver.enable();  },
			onLeave:     function () { marqueeLoop.pause();                    marqueeObserver.disable(); },
			onLeaveBack: function () { marqueeLoop.pause();                    marqueeObserver.disable(); },
		});

		wrapper.dataset.egsapDmqInit = 'initialized';
	}

	function initAll(scope) {
		(scope || document).querySelectorAll('[data-egsap-dmq-init]').forEach(initDraggableMarquee);
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
