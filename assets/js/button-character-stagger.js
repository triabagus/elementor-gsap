(function () {
	'use strict';

	function splitOne(node) {
		if (!node) return;
		if (node.dataset.bcsInit === '1') return;

		var offsetAttr = node.getAttribute('data-stagger-offset');
		var offset = parseFloat(offsetAttr);
		if (isNaN(offset) || offset < 0) offset = 0.01;

		var text = node.textContent;
		node.innerHTML = '';

		if (!text) {
			node.dataset.bcsInit = '1';
			return;
		}

		var chars = Array.from(text);
		var frag = document.createDocumentFragment();

		chars.forEach(function (char, index) {
			var span = document.createElement('span');
			span.textContent = char;
			span.style.transitionDelay = (index * offset).toFixed(4) + 's';
			if (char === ' ') {
				span.style.whiteSpace = 'pre';
			}
			frag.appendChild(span);
		});

		node.appendChild(frag);
		node.dataset.bcsInit = '1';
	}

	function initAll(scope) {
		var root = scope || document;
		if (typeof root.querySelectorAll !== 'function') return;
		root.querySelectorAll('[data-button-animate-chars]').forEach(splitOne);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () { initAll(); });
	} else {
		initAll();
	}

	if (window.elementorFrontend && window.elementorFrontend.hooks) {
		window.elementorFrontend.hooks.addAction(
			'frontend/element_ready/button_character_stagger.default',
			function ($scope) {
				var node = $scope && $scope[0];
				if (!node) return;
				initAll(node);
			}
		);
	}
})();
