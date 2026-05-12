(function () {
	'use strict';

	var instances = new Map();

	function isEditorPreview() {
		return !!(window.elementorFrontend
			&& typeof window.elementorFrontend.isEditMode === 'function'
			&& window.elementorFrontend.isEditMode());
	}

	function ensureGsap() {
		return typeof window.gsap !== 'undefined';
	}

	function destroyInstance(root) {
		var inst = instances.get(root);
		if (!inst) return;
		if (inst.timeline) { try { inst.timeline.kill(); } catch (_) {} }
		if (inst.tween) { try { inst.tween.kill(); } catch (_) {} }
		if (inst.edgeTween) { try { inst.edgeTween.kill(); } catch (_) {} }
		if (window.gsap && inst.wordList) { try { gsap.killTweensOf(inst.wordList); } catch (_) {} }
		if (window.gsap && inst.selector) { try { gsap.killTweensOf(inst.selector); } catch (_) {} }
		if (inst.wordList) inst.wordList.style.transform = '';
		if (inst.selector) inst.selector.style.width = '';
		instances.delete(root);
		delete root.dataset.lwsInit;
	}

	function cleanupStale() {
		instances.forEach(function (_inst, root) {
			if (!root.isConnected) destroyInstance(root);
		});
	}

	function sizeSelectorToWord(selector, wordList, word) {
		if (!selector || !wordList || !word) return;
		var w = word.getBoundingClientRect().width;
		var lw = wordList.getBoundingClientRect().width;
		if (!lw) return;
		selector.style.width = ((w / lw) * 100) + '%';
	}

	function initOne(root) {
		if (root.dataset.lwsInit === '1') {
			destroyInstance(root);
		}
		root.dataset.lwsInit = '1';

		var wordList = root.querySelector('[data-looping-words-list]');
		var selector = root.querySelector('[data-looping-words-selector]');
		if (!wordList || !selector) return;

		var words = Array.from(wordList.children);
		var totalWords = words.length;
		if (totalWords === 0) return;

		var inst = {
			wordList: wordList,
			selector: selector,
			timeline: null,
			tween: null,
			edgeTween: null,
		};

		// Editor preview / GSAP missing: render static, size selector to the
		// naturally-centered word (index 1 when yPercent=0) so layout looks right.
		if (isEditorPreview() || !ensureGsap()) {
			wordList.style.transform = '';
			var staticCenter = words[1 % totalWords];
			// Defer to next frame so layout is settled.
			requestAnimationFrame(function () {
				sizeSelectorToWord(selector, wordList, staticCenter);
			});
			instances.set(root, inst);
			return;
		}

		var duration = parseFloat(root.dataset.lwsDuration);
		if (isNaN(duration) || duration <= 0) duration = 1.2;
		var pause = parseFloat(root.dataset.lwsPause);
		if (isNaN(pause) || pause < 0) pause = 2;
		var delay = parseFloat(root.dataset.lwsDelay);
		if (isNaN(delay) || delay < 0) delay = 1;
		var selectorDuration = parseFloat(root.dataset.lwsSelectorDuration);
		if (isNaN(selectorDuration) || selectorDuration <= 0) selectorDuration = 0.5;
		var ease = root.dataset.lwsEase || 'elastic.out(1, 0.85)';
		var selectorEase = root.dataset.lwsSelectorEase || 'expo.out';

		var wordHeight = 100 / totalWords;
		var currentIndex = 0;

		function updateEdgeWidth() {
			var centerIndex = (currentIndex + 1) % totalWords;
			var centerWord = words[centerIndex];
			if (!centerWord) return;
			var centerWordWidth = centerWord.getBoundingClientRect().width;
			var listWidth = wordList.getBoundingClientRect().width;
			if (!listWidth) return;
			var percentageWidth = (centerWordWidth / listWidth) * 100;
			inst.edgeTween = gsap.to(selector, {
				width: percentageWidth + '%',
				duration: selectorDuration,
				ease: selectorEase,
			});
		}

		function moveWords() {
			currentIndex++;
			inst.tween = gsap.to(wordList, {
				yPercent: -wordHeight * currentIndex,
				duration: duration,
				ease: ease,
				onStart: updateEdgeWidth,
				onComplete: function () {
					if (currentIndex >= totalWords - 3) {
						wordList.appendChild(wordList.children[0]);
						currentIndex--;
						gsap.set(wordList, { yPercent: -wordHeight * currentIndex });
						words.push(words.shift());
					}
				},
			});
		}

		updateEdgeWidth();

		inst.timeline = gsap.timeline({ repeat: -1, delay: delay })
			.call(moveWords)
			.to({}, { duration: pause });

		instances.set(root, inst);
	}

	function initAll(scope) {
		cleanupStale();
		var root = scope || document;
		if (typeof root.querySelectorAll !== 'function') return;
		root.querySelectorAll('[data-looping-words]').forEach(initOne);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () { initAll(); });
	} else {
		initAll();
	}

	if (window.elementorFrontend && window.elementorFrontend.hooks) {
		window.elementorFrontend.hooks.addAction(
			'frontend/element_ready/looping_words_selector.default',
			function ($scope) {
				var node = $scope && $scope[0];
				if (!node) return;
				initAll(node);
			}
		);
	}
})();
