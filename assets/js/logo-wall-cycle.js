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
		if (inst.timeline)     { try { inst.timeline.kill(); } catch (_) {} }
		if (inst.scrollTrigger) { try { inst.scrollTrigger.kill(); } catch (_) {} }
		if (inst.onVisibility) { document.removeEventListener('visibilitychange', inst.onVisibility); }
		instances.delete(root);
		delete root.dataset.egsapLwInit;
	}

	function cleanupStale() {
		instances.forEach(function (_inst, root) {
			if (!root.isConnected) destroyInstance(root);
		});
	}

	function initLogoWallCycle(root) {
		if (!root) return;
		if (root.dataset.egsapLwInit === '1') return;
		root.dataset.egsapLwInit = '1';

		// Editor preview: skip animation — layout statis biar user bisa edit
		// tanpa logo terus berubah.
		if (isEditorPreview()) {
			instances.set(root, { editor: true });
			return;
		}

		if (typeof window.gsap === 'undefined' || typeof window.ScrollTrigger === 'undefined') {
			console.warn('GSAP + ScrollTrigger belum dimuat untuk Logo Wall Cycle.');
			return;
		}
		var gsap          = window.gsap;
		var ScrollTrigger = window.ScrollTrigger;
		try { gsap.registerPlugin(ScrollTrigger); } catch (_) {}

		var loopDelay    = parseFloat(root.dataset.logoWallLoopDelay);
		if (isNaN(loopDelay) || loopDelay < 0) loopDelay = 1.5;
		var duration     = parseFloat(root.dataset.logoWallDuration);
		if (isNaN(duration) || duration <= 0) duration = 0.9;
		var shuffleFront = root.getAttribute('data-logo-wall-shuffle') !== 'false';

		var list = root.querySelector('[data-logo-wall-list]');
		if (!list) return;

		var items = Array.prototype.slice.call(list.querySelectorAll('[data-logo-wall-item]'));
		if (!items.length) return;

		var originalTargets = items
			.map(function (item) { return item.querySelector('[data-logo-wall-target]'); })
			.filter(Boolean);

		if (!originalTargets.length) return;

		var visibleItems = [];
		var visibleCount = 0;
		var pool         = [];
		var pattern      = [];
		var patternIndex = 0;
		var tl           = null;
		var st           = null;
		var onVisibility = null;

		function isVisible(el) {
			return window.getComputedStyle(el).display !== 'none';
		}

		function shuffleArray(arr) {
			var a = arr.slice();
			for (var i = a.length - 1; i > 0; i--) {
				var j = Math.floor(Math.random() * (i + 1));
				var t = a[i]; a[i] = a[j]; a[j] = t;
			}
			return a;
		}

		function setup() {
			if (tl) { try { tl.kill(); } catch (_) {} }

			visibleItems = items.filter(isVisible);
			visibleCount = visibleItems.length;

			if (!visibleCount) return;

			pattern = shuffleArray(
				Array.from({ length: visibleCount }, function (_, i) { return i; })
			);
			patternIndex = 0;

			// remove all injected targets
			items.forEach(function (item) {
				item.querySelectorAll('[data-logo-wall-target]').forEach(function (old) { old.remove(); });
			});

			pool = originalTargets.map(function (n) { return n.cloneNode(true); });

			var front, rest;
			if (shuffleFront) {
				var shuffledAll = shuffleArray(pool);
				front = shuffledAll.slice(0, visibleCount);
				rest  = shuffleArray(shuffledAll.slice(visibleCount));
			} else {
				front = pool.slice(0, visibleCount);
				rest  = shuffleArray(pool.slice(visibleCount));
			}
			pool = front.concat(rest);

			for (var i = 0; i < visibleCount; i++) {
				var parent = visibleItems[i].querySelector('[data-logo-wall-target-parent]') || visibleItems[i];
				parent.appendChild(pool.shift());
			}

			tl = gsap.timeline({ repeat: -1, repeatDelay: loopDelay });
			tl.call(swapNext);
			tl.play();

			instances.set(root, {
				timeline:      tl,
				scrollTrigger: st,
				onVisibility:  onVisibility,
			});
		}

		function swapNext() {
			var nowCount = items.filter(isVisible).length;
			if (nowCount !== visibleCount) {
				setup();
				return;
			}
			if (!pool.length) return;

			var idx = pattern[patternIndex % visibleCount];
			patternIndex++;

			var container = visibleItems[idx];
			var parent    = container.querySelector('[data-logo-wall-target-parent]') || container;
			var existing  = parent.querySelectorAll('[data-logo-wall-target]');
			if (existing.length > 1) return;

			var current  = parent.querySelector('[data-logo-wall-target]');
			var incoming = pool.shift();

			gsap.set(incoming, { yPercent: 50, autoAlpha: 0 });
			parent.appendChild(incoming);

			if (current) {
				gsap.to(current, {
					yPercent: -50,
					autoAlpha: 0,
					duration:  duration,
					ease:      'expo.inOut',
					onComplete: function () {
						current.remove();
						pool.push(current);
					},
				});
			}

			gsap.to(incoming, {
				yPercent:  0,
				autoAlpha: 1,
				duration:  duration,
				delay:     0.1,
				ease:      'expo.inOut',
			});
		}

		setup();

		st = ScrollTrigger.create({
			trigger:     root,
			start:       'top bottom',
			end:         'bottom top',
			onEnter:     function () { if (tl) tl.play(); },
			onLeave:     function () { if (tl) tl.pause(); },
			onEnterBack: function () { if (tl) tl.play(); },
			onLeaveBack: function () { if (tl) tl.pause(); },
		});

		onVisibility = function () {
			if (!tl) return;
			if (document.hidden) tl.pause(); else tl.play();
		};
		document.addEventListener('visibilitychange', onVisibility);

		// Update instance record with final ScrollTrigger + visibilitychange handler
		instances.set(root, { timeline: tl, scrollTrigger: st, onVisibility: onVisibility });

		// Re-setup ketika responsive breakpoint berubah (jumlah visible item beda).
		var onResize;
		var resizeTimer;
		onResize = function () {
			clearTimeout(resizeTimer);
			resizeTimer = setTimeout(function () {
				var nowCount = items.filter(isVisible).length;
				if (nowCount !== visibleCount) setup();
			}, 200);
		};
		window.addEventListener('resize', onResize);
	}

	function initAll(root) {
		cleanupStale();
		var scope = root || document;
		scope.querySelectorAll('[data-logo-wall-cycle-init]').forEach(initLogoWallCycle);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () { initAll(); });
	} else {
		initAll();
	}

	if (window.elementorFrontend && window.elementorFrontend.hooks) {
		window.elementorFrontend.hooks.addAction('frontend/init', function () {
			initAll(document);
		});
	}
})();
