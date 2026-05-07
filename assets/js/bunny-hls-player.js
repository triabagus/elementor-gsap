(function () {
	'use strict';

	var instances = new Map();

	function isEditorPreview() {
		return !!(window.elementorFrontend
			&& typeof window.elementorFrontend.isEditMode === 'function'
			&& window.elementorFrontend.isEditMode());
	}

	function readyIfIdle(player, pendingPlay) {
		if (!pendingPlay
			&& player.getAttribute('data-player-activated') !== 'true'
			&& player.getAttribute('data-player-status') === 'idle') {
			player.setAttribute('data-player-status', 'ready');
		}
	}

	function setBeforeRatio(player, updateSize, w, h) {
		if (updateSize !== 'true' || !w || !h) return;
		var before = player.querySelector('[data-player-before]');
		if (!before) return;
		before.style.paddingTop = (h / w * 100) + '%';
	}

	function bestLevel(levels) {
		if (!levels || !levels.length) return null;
		return levels.reduce(function (a, b) {
			return ((b.width || 0) > (a.width || 0)) ? b : a;
		}, levels[0]);
	}

	function safePlay(video) {
		var p = video.play();
		if (p && typeof p.then === 'function') p.catch(function () {});
	}

	function resolveUrl(base, rel) {
		try { return new URL(rel, base).toString(); } catch (_) { return rel; }
	}

	function getSourceMeta(src, useHlsJs) {
		return new Promise(function (resolve) {
			if (useHlsJs && window.Hls && Hls.isSupported()) {
				try {
					var tmp = new Hls();
					var out = { width: 0, height: 0, duration: NaN };

					tmp.on(Hls.Events.MANIFEST_PARSED, function (e, data) {
						var lvls = (data && data.levels) || tmp.levels || [];
						var best = bestLevel(lvls);
						if (best && best.width && best.height) {
							out.width = best.width;
							out.height = best.height;
						}
					});
					tmp.on(Hls.Events.LEVEL_LOADED, function (e, data) {
						if (data && data.details && isFinite(data.details.totalduration)) {
							out.duration = data.details.totalduration;
						}
					});
					tmp.on(Hls.Events.ERROR, function () {
						try { tmp.destroy(); } catch (_) {}
						resolve(out);
					});
					tmp.on(Hls.Events.LEVEL_LOADED, function () {
						try { tmp.destroy(); } catch (_) {}
						resolve(out);
					});

					tmp.loadSource(src);
					return;
				} catch (_) {
					resolve({ width: 0, height: 0, duration: NaN });
					return;
				}
			}

			function parseMaster(masterText) {
				var lines = masterText.split(/\r?\n/);
				var bestW = 0, bestH = 0, firstMedia = null, lastInf = null;
				for (var i = 0; i < lines.length; i++) {
					var line = lines[i];
					if (line.indexOf('#EXT-X-STREAM-INF:') === 0) {
						lastInf = line;
					} else if (lastInf && line && line[0] !== '#') {
						if (!firstMedia) firstMedia = line.trim();
						var m = /RESOLUTION=(\d+)x(\d+)/.exec(lastInf);
						if (m) {
							var w = parseInt(m[1], 10), h = parseInt(m[2], 10);
							if (w > bestW) { bestW = w; bestH = h; }
						}
						lastInf = null;
					}
				}
				return { bestW: bestW, bestH: bestH, media: firstMedia };
			}

			function sumDuration(mediaText) {
				var dur = 0, re = /#EXTINF:([\d.]+)/g, m;
				while ((m = re.exec(mediaText))) dur += parseFloat(m[1]);
				return dur;
			}

			fetch(src, { credentials: 'omit', cache: 'no-store' }).then(function (r) {
				if (!r.ok) throw new Error('master');
				return r.text();
			}).then(function (master) {
				var info = parseMaster(master);
				if (!info.media) {
					resolve({ width: info.bestW || 0, height: info.bestH || 0, duration: NaN });
					return;
				}
				var mediaUrl = resolveUrl(src, info.media);
				return fetch(mediaUrl, { credentials: 'omit', cache: 'no-store' }).then(function (r) {
					if (!r.ok) throw new Error('media');
					return r.text();
				}).then(function (mediaText) {
					resolve({
						width: info.bestW || 0,
						height: info.bestH || 0,
						duration: sumDuration(mediaText),
					});
				});
			}).catch(function () {
				resolve({ width: 0, height: 0, duration: NaN });
			});
		});
	}

	function destroyInstance(player) {
		var inst = instances.get(player);
		if (!inst) return;

		if (inst.io) {
			try { inst.io.disconnect(); } catch (_) {}
		}
		if (inst.video) {
			(inst.videoListeners || []).forEach(function (l) {
				try { inst.video.removeEventListener(l.type, l.fn); } catch (_) {}
			});
			try { inst.video.pause(); } catch (_) {}
			try { inst.video.removeAttribute('src'); inst.video.load(); } catch (_) {}
		}
		if (inst.clickHandler) {
			try { player.removeEventListener('click', inst.clickHandler); } catch (_) {}
		}
		if (inst.pointerEnter) {
			try { player.removeEventListener('pointerenter', inst.pointerEnter); } catch (_) {}
		}
		if (inst.pointerLeave) {
			try { player.removeEventListener('pointerleave', inst.pointerLeave); } catch (_) {}
		}
		if (inst.hls) {
			try { inst.hls.destroy(); } catch (_) {}
		}
		player._hls = null;
		instances.delete(player);
		delete player.dataset.bunnyInit;
	}

	function cleanupStale() {
		instances.forEach(function (_inst, player) {
			if (!player.isConnected) destroyInstance(player);
		});
	}

	function initPlayer(player) {
		if (player.dataset.bunnyInit === '1') {
			destroyInstance(player);
		}
		player.dataset.bunnyInit = '1';

		var src = player.getAttribute('data-player-src');
		if (!src) return;

		var video = player.querySelector('video');
		if (!video) return;

		// Editor preview: tampilkan placeholder + ratio default, jangan load HLS / autoplay.
		if (isEditorPreview()) {
			try { video.pause(); } catch (_) {}
			try { video.removeAttribute('src'); video.load(); } catch (_) {}
			player.setAttribute('data-player-status', 'ready');
			player.setAttribute('data-player-activated', 'false');
			instances.set(player, { editor: true });
			return;
		}

		try { video.pause(); } catch (_) {}
		try { video.removeAttribute('src'); video.load(); } catch (_) {}

		function setStatus(s) {
			if (player.getAttribute('data-player-status') !== s) {
				player.setAttribute('data-player-status', s);
			}
		}
		function setActivated(v) {
			player.setAttribute('data-player-activated', v ? 'true' : 'false');
		}
		if (!player.hasAttribute('data-player-activated')) setActivated(false);

		var updateSize = player.getAttribute('data-player-update-size');
		var lazyMode   = player.getAttribute('data-player-lazy');
		var isLazyTrue = lazyMode === 'true';
		var isLazyMeta = lazyMode === 'meta';
		var autoplay   = player.getAttribute('data-player-autoplay') === 'true';

		var pendingPlay = false;

		var inst = {
			video: video,
			videoListeners: [],
			clickHandler: null,
			pointerEnter: null,
			pointerLeave: null,
			hls: null,
			io: null,
		};

		function bindVideo(type, fn) {
			video.addEventListener(type, fn);
			inst.videoListeners.push({ type: type, fn: fn });
		}

		video.muted = !!autoplay;
		if (autoplay) video.loop = true;

		video.setAttribute('muted', '');
		video.setAttribute('playsinline', '');
		video.setAttribute('webkit-playsinline', '');
		video.playsInline = true;
		if (typeof video.disableRemotePlayback !== 'undefined') video.disableRemotePlayback = true;
		if (autoplay) video.autoplay = false;

		var isSafariNative = !!video.canPlayType('application/vnd.apple.mpegurl');
		var canUseHlsJs    = !!(window.Hls && Hls.isSupported()) && !isSafariNative;

		if (updateSize === 'true' && !isLazyMeta) {
			if (!isLazyTrue) {
				var prev = video.preload;
				video.preload = 'metadata';
				var onMeta2 = function () {
					setBeforeRatio(player, updateSize, video.videoWidth, video.videoHeight);
					video.removeEventListener('loadedmetadata', onMeta2);
					video.preload = prev || '';
				};
				video.addEventListener('loadedmetadata', onMeta2, { once: true });
				inst.videoListeners.push({ type: 'loadedmetadata', fn: onMeta2 });
				video.src = src;
			}
		}

		function fetchMetaOnce() {
			getSourceMeta(src, canUseHlsJs).then(function (meta) {
				if (!player.isConnected) return;
				if (meta.width && meta.height) setBeforeRatio(player, updateSize, meta.width, meta.height);
				readyIfIdle(player, pendingPlay);
			});
		}

		var isAttached = false;
		var lastPauseBy = '';

		function attachMediaOnce() {
			if (isAttached) return;
			isAttached = true;

			if (inst.hls) {
				try { inst.hls.destroy(); } catch (_) {}
				inst.hls = null;
			}

			if (isSafariNative) {
				video.preload = (isLazyTrue || isLazyMeta) ? 'auto' : video.preload;
				video.src = src;
				var onSafariMeta = function () {
					readyIfIdle(player, pendingPlay);
					if (updateSize === 'true') {
						setBeforeRatio(player, updateSize, video.videoWidth, video.videoHeight);
					}
				};
				video.addEventListener('loadedmetadata', onSafariMeta, { once: true });
				inst.videoListeners.push({ type: 'loadedmetadata', fn: onSafariMeta });
			} else if (canUseHlsJs) {
				var hls = new Hls({ maxBufferLength: 10 });
				hls.attachMedia(video);
				hls.on(Hls.Events.MEDIA_ATTACHED, function () { hls.loadSource(src); });
				hls.on(Hls.Events.MANIFEST_PARSED, function () {
					readyIfIdle(player, pendingPlay);
					if (updateSize === 'true') {
						var lvls = hls.levels || [];
						var best = bestLevel(lvls);
						if (best && best.width && best.height) {
							setBeforeRatio(player, updateSize, best.width, best.height);
						}
					}
				});
				inst.hls = hls;
				player._hls = hls;
			} else {
				video.src = src;
			}
		}

		if (isLazyMeta) {
			if (updateSize === 'true') fetchMetaOnce();
			video.preload = 'none';
		} else if (isLazyTrue) {
			video.preload = 'none';
		} else {
			attachMediaOnce();
		}

		function togglePlay() {
			if (video.paused || video.ended) {
				if ((isLazyTrue || isLazyMeta) && !isAttached) attachMediaOnce();
				pendingPlay = true;
				lastPauseBy = '';
				setStatus('loading');
				safePlay(video);
			} else {
				lastPauseBy = 'manual';
				video.pause();
			}
		}

		function toggleMute() {
			video.muted = !video.muted;
			player.setAttribute('data-player-muted', video.muted ? 'true' : 'false');
		}

		inst.clickHandler = function (e) {
			var btn = e.target.closest('[data-player-control]');
			if (!btn || !player.contains(btn)) return;
			var type = btn.getAttribute('data-player-control');
			if (type === 'play' || type === 'pause' || type === 'playpause') togglePlay();
			else if (type === 'mute') toggleMute();
		};
		player.addEventListener('click', inst.clickHandler);

		bindVideo('play',    function () { setActivated(true); setStatus('playing'); });
		bindVideo('playing', function () { pendingPlay = false; setStatus('playing'); });
		bindVideo('pause',   function () { pendingPlay = false; setStatus('paused'); });
		bindVideo('waiting', function () { setStatus('loading'); });
		bindVideo('canplay', function () { readyIfIdle(player, pendingPlay); });
		bindVideo('ended',   function () { pendingPlay = false; setStatus('paused'); setActivated(false); });

		var ratioSet = false;
		function maybeSetRatioOnce() {
			if (ratioSet || updateSize !== 'true') return;
			var before = player.querySelector('[data-player-before]');
			if (!before) return;
			if (video.videoWidth && video.videoHeight) {
				before.style.paddingTop = (video.videoHeight / video.videoWidth * 100) + '%';
				ratioSet = true;
			}
		}
		bindVideo('loadedmetadata', maybeSetRatioOnce);
		bindVideo('loadeddata',     maybeSetRatioOnce);
		bindVideo('playing',        maybeSetRatioOnce);

		function setHover(state) {
			if (player.getAttribute('data-player-hover') !== state) {
				player.setAttribute('data-player-hover', state);
			}
		}
		inst.pointerEnter = function () { setHover('active'); };
		inst.pointerLeave = function () { setHover('idle'); };
		player.addEventListener('pointerenter', inst.pointerEnter);
		player.addEventListener('pointerleave', inst.pointerLeave);

		if (autoplay && typeof IntersectionObserver !== 'undefined') {
			inst.io = new IntersectionObserver(function (entries) {
				entries.forEach(function (entry) {
					var inView = entry.isIntersecting && entry.intersectionRatio > 0;
					if (inView) {
						if ((isLazyTrue || isLazyMeta) && !isAttached) attachMediaOnce();
						if ((lastPauseBy === 'io') || (video.paused && lastPauseBy !== 'manual')) {
							setStatus('loading');
							if (video.paused) togglePlay();
							lastPauseBy = '';
						}
					} else {
						if (!video.paused && !video.ended) {
							lastPauseBy = 'io';
							video.pause();
						}
					}
				});
			}, { threshold: 0.1 });
			inst.io.observe(player);
		}

		instances.set(player, inst);
	}

	function initAll(scope) {
		cleanupStale();
		var root = scope || document;
		root.querySelectorAll('[data-bunny-player-init]').forEach(initPlayer);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () { initAll(); });
	} else {
		initAll();
	}

	if (window.elementorFrontend && window.elementorFrontend.hooks) {
		window.elementorFrontend.hooks.addAction(
			'frontend/element_ready/bunny_hls_player_basic.default',
			function ($scope) { initAll($scope[0]); }
		);
	}
})();
