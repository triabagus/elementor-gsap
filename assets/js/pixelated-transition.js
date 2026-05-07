(function () {
	'use strict';

	var instances = new Map();

	function isEditorPreview() {
		return !!(window.elementorFrontend
			&& typeof window.elementorFrontend.isEditMode === 'function'
			&& window.elementorFrontend.isEditMode());
	}

	function adjustGrid(transition) {
		return new Promise(function (resolve) {
			var computedStyle = window.getComputedStyle(transition);
			var gridTemplateColumns = computedStyle.getPropertyValue('grid-template-columns');
			var columns = gridTemplateColumns.split(' ').filter(Boolean).length;
			if (!columns) columns = 8;

			var blockSize = window.innerWidth / columns;
			var rowsNeeded = Math.ceil(window.innerHeight / blockSize);

			transition.style.gridTemplateRows = 'repeat(' + rowsNeeded + ', ' + blockSize + 'px)';

			var totalBlocks = columns * rowsNeeded;
			transition.innerHTML = '';

			for (var i = 0; i < totalBlocks; i++) {
				var block = document.createElement('div');
				block.classList.add('transition-block');
				transition.appendChild(block);
			}

			resolve();
		});
	}

	function destroyInstance(transition) {
		var inst = instances.get(transition);
		if (!inst) return;
		if (inst.onResize) {
			window.removeEventListener('resize', inst.onResize);
			clearTimeout(inst.resizeTimer);
		}
		(inst.linkHandlers || []).forEach(function (h) {
			if (h.el && h.fn) h.el.removeEventListener('click', h.fn);
		});
		if (window.gsap) {
			try { gsap.killTweensOf(transition.querySelectorAll('.transition-block')); } catch (_) {}
		}
		instances.delete(transition);
		delete transition.dataset.egsapInit;
	}

	function cleanupStale() {
		instances.forEach(function (_inst, t) {
			if (!t.isConnected) destroyInstance(t);
		});
	}

	function initOne(transition) {
		if (transition.dataset.egsapInit === '1') {
			destroyInstance(transition);
		}
		transition.dataset.egsapInit = '1';

		if (typeof window.gsap === 'undefined') {
			console.warn('GSAP belum dimuat untuk pixelated transition.');
			transition.style.display = 'none';
			return;
		}

		var staggerOut = parseFloat(transition.dataset.egsapStaggerOut);
		if (isNaN(staggerOut)) staggerOut = 0.75;
		var staggerIn = parseFloat(transition.dataset.egsapStaggerIn);
		if (isNaN(staggerIn)) staggerIn = 0.5;
		var blockDuration = parseFloat(transition.dataset.egsapBlockDuration);
		if (isNaN(blockDuration)) blockDuration = 0.1;

		var inst = {
			onResize: null,
			resizeTimer: null,
			linkHandlers: [],
		};

		adjustGrid(transition).then(function () {
			if (!transition.isConnected) return;
			gsap.set(transition, { display: 'grid' });

			var blocks = transition.querySelectorAll('.transition-block');

			var tl = gsap.timeline({
				onStart: function () {
					gsap.set(transition, { background: 'transparent' });
				},
				onComplete: function () {
					gsap.set(transition, { display: 'none' });
				},
				defaults: { ease: 'linear' },
			});

			tl.to(blocks, {
				opacity: 0,
				duration: blockDuration,
				stagger: { amount: staggerOut, from: 'random' },
			}, 0.5);
		});

		var validLinks = Array.prototype.filter.call(document.querySelectorAll('a'), function (link) {
			var href = link.getAttribute('href') || '';
			var hostname;
			try {
				hostname = new URL(link.href, window.location.origin).hostname;
			} catch (_) {
				return false;
			}
			return hostname === window.location.hostname
				&& !href.startsWith('#')
				&& link.getAttribute('target') !== '_blank'
				&& !link.hasAttribute('data-transition-prevent');
		});

		validLinks.forEach(function (link) {
			var fn = function (event) {
				event.preventDefault();
				var destination = link.href;

				gsap.set(transition, { display: 'grid', background: '' });

				gsap.fromTo(
					transition.querySelectorAll('.transition-block'),
					{ autoAlpha: 0 },
					{
						autoAlpha: 1,
						duration: 0.001,
						ease: 'linear',
						stagger: { amount: staggerIn, from: 'random' },
						onComplete: function () {
							window.location.href = destination;
						},
					}
				);
			};
			link.addEventListener('click', fn);
			inst.linkHandlers.push({ el: link, fn: fn });
		});

		inst.onResize = function () {
			clearTimeout(inst.resizeTimer);
			inst.resizeTimer = setTimeout(function () {
				if (transition.isConnected) adjustGrid(transition);
			}, 150);
		};
		window.addEventListener('resize', inst.onResize);

		instances.set(transition, inst);
	}

	function initAll(scope) {
		if (isEditorPreview()) return;
		cleanupStale();
		var root = scope || document;
		root.querySelectorAll('.transition[data-egsap-id]').forEach(initOne);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () { initAll(); });
	} else {
		initAll();
	}

	// Handle bfcache (Safari/Firefox back-forward cache)
	window.addEventListener('pageshow', function (event) {
		if (event.persisted) {
			window.location.reload();
		}
	});
})();
