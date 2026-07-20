(function () {
	'use strict';

	var instances = new Map();

	function isEditorPreview() {
		return !!(window.elementorFrontend
			&& typeof window.elementorFrontend.isEditMode === 'function'
			&& window.elementorFrontend.isEditMode());
	}

	function destroyInstance(root) {
		var inst = instances.get(root);
		if (!inst) return;
		if (inst.media) { try { inst.media.revert(); } catch (_) {} }
		instances.delete(root);
		delete root.dataset.egsapStlInit;
	}

	function cleanupStale() {
		instances.forEach(function (_inst, root) {
			if (!root.isConnected) destroyInstance(root);
		});
	}

	function initStepByStepTimeline(root) {
		if (!root) return;
		if (root.dataset.egsapStlInit === '1') return;
		root.dataset.egsapStlInit = '1';

		// Editor: CSS override sudah handle static "all active" preview.
		if (isEditorPreview()) {
			instances.set(root, { editor: true });
			return;
		}

		if (typeof window.gsap === 'undefined' || typeof window.ScrollTrigger === 'undefined') {
			console.warn('GSAP + ScrollTrigger belum dimuat untuk Step-by-step Timeline.');
			return;
		}
		var gsap          = window.gsap;
		var ScrollTrigger = window.ScrollTrigger;
		try { gsap.registerPlugin(ScrollTrigger); } catch (_) {}

		var line  = root.querySelector('[data-step-timeline-line]');
		var fill  = root.querySelector('[data-step-timeline-fill]');
		var items = Array.prototype.slice.call(root.querySelectorAll('[data-step-timeline-item]'));
		if (!line || !fill || !items.length) return;

		var anchors = items.map(function (item) {
			return item.querySelector('[data-step-timeline-marker]') || item;
		});

		var activationInput  = parseFloat(root.dataset.stepTimelineActivation);
		var activation       = isNaN(activationInput) ? 0.5 : Math.min(Math.max(activationInput, 0), 1);
		var activationPercent = activation * 100;
		var lastIndex        = items.length - 1;

		var anchorFractions = [ 0 ];

		function measureLine() {
			if (items.length < 2) {
				line.style.height = '0px';
				anchorFractions = [ 0 ];
				return;
			}
			var base    = line.parentElement.getBoundingClientRect().top;
			var centers = anchors.map(function (anchor) {
				var box = anchor.getBoundingClientRect();
				return box.top + box.height / 2 - base;
			});
			var firstCenter = centers[0];
			var span        = centers[lastIndex] - firstCenter;
			line.style.top    = firstCenter + 'px';
			line.style.height = span + 'px';
			anchorFractions = centers.map(function (center) {
				return span > 0 ? (center - firstCenter) / span : 0;
			});
		}

		var currentIndex = -2;

		function setCurrentIndex(index) {
			if (index === currentIndex) return;
			currentIndex = index;
			items.forEach(function (item, i) {
				var status = index >= 0 && i <= index ? 'active' : 'inactive';
				if (item.getAttribute('data-status') !== status) {
					item.setAttribute('data-status', status);
				}
				item.toggleAttribute('data-current',  i === index);
				item.toggleAttribute('data-previous', i === index - 1);
				item.toggleAttribute('data-next',     i === index + 1);
			});
		}

		function indexForProgress(reached, progress) {
			if (!reached) return -1;
			var index = 0;
			for (var i = 0; i < anchorFractions.length; i++) {
				if (progress + 0.0001 >= anchorFractions[i]) index = i;
			}
			return index;
		}

		function updateFromScroll(self) {
			var reached = self.isActive || self.progress >= 1;
			setCurrentIndex(indexForProgress(reached, self.progress));
		}

		setCurrentIndex(-1);
		gsap.set(fill, { transformOrigin: 'top', scaleY: 0 });

		var media = gsap.matchMedia();

		media.add('(prefers-reduced-motion: no-preference)', function () {
			measureLine();
			ScrollTrigger.addEventListener('refreshInit', measureLine);

			if (items.length > 1) {
				gsap.fromTo(
					fill,
					{ scaleY: 0 },
					{
						scaleY: 1,
						ease:   'none',
						scrollTrigger: {
							trigger:     line,
							start:       'top ' + activationPercent + '%',
							end:         'bottom ' + activationPercent + '%',
							scrub:       true,
							onUpdate:    updateFromScroll,
							onToggle:    updateFromScroll,
							onRefresh:   updateFromScroll,
						},
					}
				);
			} else {
				setCurrentIndex(0);
			}

			var refresh = function () { ScrollTrigger.refresh(); };
			window.addEventListener('load', refresh);
			if (document.fonts && document.fonts.ready) document.fonts.ready.then(refresh);

			ScrollTrigger.refresh();

			return function () {
				window.removeEventListener('load', refresh);
				ScrollTrigger.removeEventListener('refreshInit', measureLine);
			};
		});

		media.add('(prefers-reduced-motion: reduce)', function () {
			measureLine();
			gsap.set(fill, { scaleY: 1 });
			setCurrentIndex(lastIndex);
		});

		instances.set(root, { media: media });
	}

	function initAll(scope) {
		cleanupStale();
		(scope || document).querySelectorAll('[data-step-timeline-init]').forEach(initStepByStepTimeline);
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
