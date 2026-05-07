(function () {
	'use strict';

	var instances = new Map();

	function isEditorPreview() {
		return !!(window.elementorFrontend
			&& typeof window.elementorFrontend.isEditMode === 'function'
			&& window.elementorFrontend.isEditMode());
	}

	function isTouchDevice() {
		return ('ontouchstart' in window)
			|| (navigator.maxTouchPoints > 0)
			|| (window.matchMedia && window.matchMedia('(pointer: coarse)').matches);
	}

	function destroyInstance(card) {
		var inst = instances.get(card);
		if (!inst) return;
		if (window.gsap && inst.pixels) {
			try { gsap.killTweensOf(inst.pixels); } catch (_) {}
		}
		if (inst.delayedCall) { try { inst.delayedCall.kill(); } catch (_) {} }
		if (inst.handlers) {
			inst.handlers.forEach(function (h) {
				try { card.removeEventListener(h.type, h.fn); } catch (_) {}
			});
		}
		if (inst.grid) inst.grid.innerHTML = '';
		instances.delete(card);
		delete card.dataset.pirInit;
	}

	function cleanupStale() {
		instances.forEach(function (_inst, card) {
			if (!card.isConnected) destroyInstance(card);
		});
	}

	function buildGrid(grid, gridSize) {
		grid.innerHTML = '';
		var pixelSize = 100 / gridSize;
		var frag = document.createDocumentFragment();
		for (var row = 0; row < gridSize; row++) {
			for (var col = 0; col < gridSize; col++) {
				var pixel = document.createElement('div');
				pixel.className = 'pixelated-image-card__pixel';
				pixel.style.width  = pixelSize + '%';
				pixel.style.height = pixelSize + '%';
				pixel.style.left   = (col * pixelSize) + '%';
				pixel.style.top    = (row * pixelSize) + '%';
				frag.appendChild(pixel);
			}
		}
		grid.appendChild(frag);
		return grid.querySelectorAll('.pixelated-image-card__pixel');
	}

	function initOne(card) {
		if (card.dataset.pirInit === '1') {
			destroyInstance(card);
		}
		card.dataset.pirInit = '1';

		var grid = card.querySelector('[data-pixelated-image-reveal-grid]');
		var activeCard = card.querySelector('[data-pixelated-image-reveal-active]');
		if (!grid || !activeCard) return;

		var gridSize = parseInt(card.dataset.pirGrid, 10);
		if (isNaN(gridSize) || gridSize < 2) gridSize = 7;
		if (gridSize > 30) gridSize = 30;

		var stepDuration = parseFloat(card.dataset.pirDuration);
		if (isNaN(stepDuration) || stepDuration <= 0) stepDuration = 0.3;

		var triggerMode = card.dataset.pirTrigger || 'auto'; // 'hover' | 'click' | 'auto'

		// Editor: render grid statis, jangan attach interaksi (biar Elementor tetap bisa
		// klik widget untuk select). Active image disembunyikan default.
		if (isEditorPreview()) {
			buildGrid(grid, gridSize);
			activeCard.style.display = 'none';
			instances.set(card, { editor: true, grid: grid });
			return;
		}

		if (typeof window.gsap === 'undefined') {
			console.warn('GSAP belum dimuat untuk Pixelated Image Reveal.');
			return;
		}

		var pixels = buildGrid(grid, gridSize);
		var totalPixels = pixels.length;
		var staggerDuration = totalPixels > 0 ? (stepDuration / totalPixels) : 0;
		var isActive = false;

		var inst = {
			grid: grid,
			pixels: pixels,
			delayedCall: null,
			handlers: [],
		};

		function animatePixels(activate) {
			isActive = activate;
			gsap.killTweensOf(pixels);
			if (inst.delayedCall) inst.delayedCall.kill();
			gsap.set(pixels, { display: 'none' });

			gsap.to(pixels, {
				display: 'block',
				duration: 0,
				stagger: { each: staggerDuration, from: 'random' },
			});

			inst.delayedCall = gsap.delayedCall(stepDuration, function () {
				activeCard.style.display = activate ? 'block' : 'none';
				activeCard.style.pointerEvents = activate ? 'none' : '';
			});

			gsap.to(pixels, {
				display: 'none',
				duration: 0,
				delay: stepDuration,
				stagger: { each: staggerDuration, from: 'random' },
			});
		}

		var useClick = (triggerMode === 'click') || (triggerMode === 'auto' && isTouchDevice());

		if (useClick) {
			var onClick = function () { animatePixels(!isActive); };
			card.addEventListener('click', onClick);
			inst.handlers.push({ type: 'click', fn: onClick });
		} else {
			var onEnter = function () { if (!isActive) animatePixels(true); };
			var onLeave = function () { if (isActive) animatePixels(false); };
			card.addEventListener('mouseenter', onEnter);
			card.addEventListener('mouseleave', onLeave);
			inst.handlers.push({ type: 'mouseenter', fn: onEnter });
			inst.handlers.push({ type: 'mouseleave', fn: onLeave });
		}

		instances.set(card, inst);
	}

	function initAll(scope) {
		cleanupStale();
		var root = scope || document;
		root.querySelectorAll('[data-pixelated-image-reveal]').forEach(initOne);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () { initAll(); });
	} else {
		initAll();
	}

	if (window.elementorFrontend && window.elementorFrontend.hooks) {
		window.elementorFrontend.hooks.addAction(
			'frontend/element_ready/pixelated_image_reveal.default',
			function ($scope) { initAll($scope[0]); }
		);
	}
})();
