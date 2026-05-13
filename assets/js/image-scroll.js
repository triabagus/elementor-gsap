(function () {
	'use strict';

	var instances = new Map();

	function isEditorPreview() {
		return !!(window.elementorFrontend
			&& typeof window.elementorFrontend.isEditMode === 'function'
			&& window.elementorFrontend.isEditMode());
	}

	function computeScroll(root, img, direction) {
		if (!root || !img) return 0;
		if (direction === 'horizontal') {
			var fw = root.getBoundingClientRect().width;
			var iw = img.getBoundingClientRect().width;
			return Math.min(0, fw - iw);
		}
		var fh = root.getBoundingClientRect().height;
		var ih = img.getBoundingClientRect().height;
		return Math.min(0, fh - ih);
	}

	function updateScrollVar(root, img, direction) {
		var scroll = computeScroll(root, img, direction);
		root.style.setProperty('--is-scroll', scroll + 'px');
		return scroll;
	}

	function destroyInstance(root) {
		var inst = instances.get(root);
		if (!inst) return;
		if (inst.img && inst.onLoad) inst.img.removeEventListener('load', inst.onLoad);
		if (inst.resizeObs) { try { inst.resizeObs.disconnect(); } catch (_) {} }
		if (inst.onResize) window.removeEventListener('resize', inst.onResize);
		if (inst.onMouseMove) root.removeEventListener('mousemove', inst.onMouseMove);
		if (inst.onMouseLeave) root.removeEventListener('mouseleave', inst.onMouseLeave);
		if (inst.raf) cancelAnimationFrame(inst.raf);
		if (inst.img) inst.img.style.transform = '';
		root.style.removeProperty('--is-scroll');
		instances.delete(root);
		delete root.dataset.isInit;
	}

	function cleanupStale() {
		instances.forEach(function (_inst, root) {
			if (!root.isConnected) destroyInstance(root);
		});
	}

	function initOne(root) {
		if (root.dataset.isInit === '1') {
			destroyInstance(root);
		}
		root.dataset.isInit = '1';

		var img = root.querySelector('.image-scroll__image');
		if (!img) return;

		var direction = root.dataset.direction === 'horizontal' ? 'horizontal' : 'vertical';
		var trigger   = root.dataset.trigger === 'mouse-track' ? 'mouse-track' : 'hover';
		var reverse   = root.dataset.reverse === '1';

		var inst = {
			img: img,
			direction: direction,
			trigger: trigger,
			scroll: 0,
			raf: null,
		};

		inst.onLoad = function () {
			inst.scroll = updateScrollVar(root, img, direction);
		};

		if (img.complete && img.naturalWidth > 0) {
			inst.onLoad();
		} else {
			img.addEventListener('load', inst.onLoad);
		}

		// Re-compute when frame or image size changes (responsive, font-size, etc.).
		if (typeof ResizeObserver !== 'undefined') {
			inst.resizeObs = new ResizeObserver(function () {
				inst.scroll = updateScrollVar(root, img, direction);
			});
			inst.resizeObs.observe(root);
			inst.resizeObs.observe(img);
		} else {
			inst.onResize = function () {
				inst.scroll = updateScrollVar(root, img, direction);
			};
			window.addEventListener('resize', inst.onResize);
		}

		// Mouse-track mode — only on frontend, never in editor.
		if (trigger === 'mouse-track' && !isEditorPreview()) {
			inst.onMouseMove = function (e) {
				if (inst.raf) cancelAnimationFrame(inst.raf);
				inst.raf = requestAnimationFrame(function () {
					var rect = root.getBoundingClientRect();
					var pct, translate;
					if (direction === 'horizontal') {
						pct = Math.max(0, Math.min(1, (e.clientX - rect.left) / rect.width));
						if (reverse) pct = 1 - pct;
						translate = pct * inst.scroll;
						img.style.transform = 'translate3d(' + translate + 'px, 0, 0)';
					} else {
						pct = Math.max(0, Math.min(1, (e.clientY - rect.top) / rect.height));
						if (reverse) pct = 1 - pct;
						translate = pct * inst.scroll;
						img.style.transform = 'translate3d(0, ' + translate + 'px, 0)';
					}
				});
			};
			inst.onMouseLeave = function () {
				if (inst.raf) cancelAnimationFrame(inst.raf);
				img.style.transform = reverse
					? (direction === 'horizontal'
						? 'translate3d(' + inst.scroll + 'px, 0, 0)'
						: 'translate3d(0, ' + inst.scroll + 'px, 0)')
					: '';
			};
			// Apply reverse starting position immediately.
			if (reverse) inst.onMouseLeave();
			root.addEventListener('mousemove', inst.onMouseMove);
			root.addEventListener('mouseleave', inst.onMouseLeave);
		}

		instances.set(root, inst);
	}

	function initAll(scope) {
		cleanupStale();
		var root = scope || document;
		if (typeof root.querySelectorAll !== 'function') return;
		root.querySelectorAll('[data-image-scroll]').forEach(initOne);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () { initAll(); });
	} else {
		initAll();
	}

	if (window.elementorFrontend && window.elementorFrontend.hooks) {
		window.elementorFrontend.hooks.addAction(
			'frontend/element_ready/image_scroll.default',
			function ($scope) {
				var node = $scope && $scope[0];
				if (!node) return;
				initAll(node);
			}
		);
	}
})();
