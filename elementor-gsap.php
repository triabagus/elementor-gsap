<?php
/**
 * Plugin Name: Elementor GSAP
 * Description: Elementor GSAP styled by Osmo brings premium-grade animations to the Elementor page builder. Inspired by the design language of [Osmo](https://www.osmo.supply/) and powered by [GSAP](https://gsap.com/) (GreenSock Animation Platform), this plugin gives designers and developers a curated set of preloaders, page transitions, and animated widgets — all configurable directly from the Elementor panel.
 * Version: 1.0.0
 * Author: creativetria
 * Requires Plugins: elementor
 * Elementor tested up to: 4.1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ELEMENTOR_GSAP_VERSION', '1.2.2' );
/**
 * Asset revision — bump this string to force asset fingerprint to change and
 * trigger a one-time cache purge across editor preview, browser, and Elementor
 * CSS files, WITHOUT bumping the user-facing plugin version. Use it after
 * non-version-worthy fixes (CSS tweaks, JS micro-fixes, etc.) when stale
 * caches make the change invisible.
 */
define( 'ELEMENTOR_GSAP_ASSET_REVISION', '2' );
define( 'ELEMENTOR_GSAP_PATH', plugin_dir_path( __FILE__ ) );
define( 'ELEMENTOR_GSAP_URL', plugin_dir_url( __FILE__ ) );
define( 'ELEMENTOR_GSAP_GSAP_VER', '3.15.0' );
define( 'ELEMENTOR_GSAP_HLS_VER', '1.6.11' );

/**
 * Resolve URL+versi untuk GSAP/HLS — default pakai vendor lokal di
 * `assets/vendor/`. Bisa di-override jadi CDN dengan filter:
 *   add_filter( 'elementor_gsap_use_local_vendor', '__return_false' );
 *
 * @param string $kind 'gsap'|'splittext'|'customease'|'scrolltrigger'|'draggable'|'inertia'|'drawsvg'|'hls'
 * @return array{url:string, ver:string}
 */
function elementor_gsap_resolve_vendor( $kind ) {
	$use_local = (bool) apply_filters( 'elementor_gsap_use_local_vendor', true );

	$gsap_files = [
		'gsap'          => 'gsap/gsap.min.js',
		'splittext'     => 'gsap/SplitText.min.js',
		'customease'    => 'gsap/CustomEase.min.js',
		'scrolltrigger' => 'gsap/ScrollTrigger.min.js',
		'draggable'     => 'gsap/Draggable.min.js',
		'inertia'       => 'gsap/InertiaPlugin.min.js',
		'drawsvg'       => 'gsap/DrawSVGPlugin.min.js',
	];

	$cdn_files = [
		'gsap'          => 'https://cdn.jsdelivr.net/npm/gsap@' . ELEMENTOR_GSAP_GSAP_VER . '/dist/gsap.min.js',
		'splittext'     => 'https://cdn.jsdelivr.net/npm/gsap@' . ELEMENTOR_GSAP_GSAP_VER . '/dist/SplitText.min.js',
		'customease'    => 'https://cdn.jsdelivr.net/npm/gsap@' . ELEMENTOR_GSAP_GSAP_VER . '/dist/CustomEase.min.js',
		'scrolltrigger' => 'https://cdn.jsdelivr.net/npm/gsap@' . ELEMENTOR_GSAP_GSAP_VER . '/dist/ScrollTrigger.min.js',
		'draggable'     => 'https://cdn.jsdelivr.net/npm/gsap@' . ELEMENTOR_GSAP_GSAP_VER . '/dist/Draggable.min.js',
		'inertia'       => 'https://cdn.jsdelivr.net/npm/gsap@' . ELEMENTOR_GSAP_GSAP_VER . '/dist/InertiaPlugin.min.js',
		'drawsvg'       => 'https://cdn.jsdelivr.net/npm/gsap@' . ELEMENTOR_GSAP_GSAP_VER . '/dist/DrawSVGPlugin.min.js',
	];

	if ( 'hls' === $kind ) {
		$rel = 'hls/hls.min.js';
		$abs = ELEMENTOR_GSAP_PATH . 'assets/vendor/' . $rel;
		if ( $use_local && file_exists( $abs ) ) {
			return [
				'url' => ELEMENTOR_GSAP_URL . 'assets/vendor/' . $rel,
				'ver' => ELEMENTOR_GSAP_HLS_VER . '.' . filemtime( $abs ),
			];
		}
		return [
			'url' => 'https://cdn.jsdelivr.net/npm/hls.js@' . ELEMENTOR_GSAP_HLS_VER . '/dist/hls.min.js',
			'ver' => ELEMENTOR_GSAP_HLS_VER,
		];
	}

	if ( ! isset( $gsap_files[ $kind ] ) ) {
		return [ 'url' => '', 'ver' => ELEMENTOR_GSAP_GSAP_VER ];
	}

	$rel = $gsap_files[ $kind ];
	$abs = ELEMENTOR_GSAP_PATH . 'assets/vendor/' . $rel;
	if ( $use_local && file_exists( $abs ) ) {
		return [
			'url' => ELEMENTOR_GSAP_URL . 'assets/vendor/' . $rel,
			'ver' => ELEMENTOR_GSAP_GSAP_VER . '.' . filemtime( $abs ),
		];
	}
	return [
		'url' => $cdn_files[ $kind ],
		'ver' => ELEMENTOR_GSAP_GSAP_VER,
	];
}

/**
 * Asset version: combine plugin version + file mtime, so browser otomatis
 * ambil JS/CSS terbaru tiap kali file diubah (tidak perlu bump versi manual).
 */
function elementor_gsap_asset_ver( $rel_path ) {
	$abs = ELEMENTOR_GSAP_PATH . ltrim( $rel_path, '/' );
	if ( file_exists( $abs ) ) {
		return ELEMENTOR_GSAP_VERSION . '.r' . ELEMENTOR_GSAP_ASSET_REVISION . '.' . filemtime( $abs );
	}
	return ELEMENTOR_GSAP_VERSION . '.r' . ELEMENTOR_GSAP_ASSET_REVISION;
}

/**
 * Hash gabungan dari semua asset mtime — dipakai untuk auto-purge cache CSS Elementor
 * begitu ada perubahan file di plugin (PHP/CSS/JS).
 */
function elementor_gsap_assets_fingerprint() {
	$paths = [
		'elementor-gsap.php',
		'assets/css/willem-loading-animation.css',
		'assets/css/crisp-loading-animation.css',
		'assets/css/bunny-hls-player.css',
		'assets/css/masked-text-reveal.css',
		'assets/css/draggable-infinite-slider.css',
		'assets/css/pixelated-transition.css',
		'assets/css/button-draw-underline.css',
		'assets/css/button-character-stagger.css',
		'assets/css/looping-words-selector.css',
		'assets/css/image-scroll.css',
		'assets/css/sidenav-wipe.css',
		'assets/css/pixelated-image-reveal.css',
		'assets/css/fixed-underlay-navigation.css',
		'assets/css/welcoming-words-loader.css',
		'assets/css/sticky-steps.css',
		'assets/css/logo-wall-cycle.css',
		'assets/css/sticky-features.css',
		'assets/css/expanding-bottom-nav.css',
		'assets/css/radial-cards-slider.css',
		'assets/css/step-timeline.css',
		'assets/css/gradient-wave-text.css',
		'assets/css/dropping-cards-loader.css',
		'assets/css/logo-reveal-loader.css',
		'assets/css/number-loader.css',
		'assets/js/willem-loading-animation.js',
		'assets/js/crisp-loading-animation.js',
		'assets/js/bunny-hls-player.js',
		'assets/js/masked-text-reveal.js',
		'assets/js/draggable-infinite-slider.js',
		'assets/js/pixelated-transition.js',
		'assets/js/button-draw-underline.js',
		'assets/js/button-character-stagger.js',
		'assets/js/looping-words-selector.js',
		'assets/js/image-scroll.js',
		'assets/js/sidenav-wipe.js',
		'assets/js/pixelated-image-reveal.js',
		'assets/js/fixed-underlay-navigation.js',
		'assets/js/welcoming-words-loader.js',
		'assets/js/sticky-steps.js',
		'assets/js/logo-wall-cycle.js',
		'assets/js/sticky-features.js',
		'assets/js/expanding-bottom-nav.js',
		'assets/js/radial-cards-slider.js',
		'assets/js/step-timeline.js',
		'assets/js/gradient-wave-text.js',
		'assets/js/dropping-cards-loader.js',
		'assets/js/logo-reveal-loader.js',
		'assets/js/number-loader.js',
		'includes/class-willem-loading-animation-template.php',
		'includes/class-crisp-loading-animation-template.php',
		'includes/class-welcoming-words-loader-template.php',
		'includes/class-dropping-cards-loader-template.php',
		'includes/class-logo-reveal-loader-template.php',
		'includes/class-number-loader-template.php',
		'includes/class-pixelated-transition-template.php',
		'widgets/class-bunny-hls-player-widget.php',
		'widgets/class-masked-text-reveal-widget.php',
		'widgets/class-draggable-infinite-slider-widget.php',
		'widgets/class-button-draw-underline-widget.php',
		'widgets/class-button-character-stagger-widget.php',
		'widgets/class-looping-words-selector-widget.php',
		'widgets/class-image-scroll-widget.php',
		'widgets/class-sidenav-wipe-widget.php',
		'widgets/class-pixelated-image-reveal-widget.php',
		'widgets/class-fixed-underlay-navigation-widget.php',
		'widgets/class-sticky-steps-widget.php',
		'widgets/class-logo-wall-cycle-widget.php',
		'widgets/class-sticky-features-widget.php',
		'widgets/class-expanding-bottom-nav-widget.php',
		'widgets/class-radial-cards-slider-widget.php',
		'widgets/class-step-timeline-widget.php',
		'widgets/class-gradient-wave-text-widget.php',
		'assets/vendor/gsap/gsap.min.js',
		'assets/vendor/gsap/SplitText.min.js',
		'assets/vendor/gsap/CustomEase.min.js',
		'assets/vendor/gsap/ScrollTrigger.min.js',
		'assets/vendor/gsap/Draggable.min.js',
		'assets/vendor/gsap/InertiaPlugin.min.js',
		'assets/vendor/gsap/DrawSVGPlugin.min.js',
		'assets/vendor/hls/hls.min.js',
	];

	$mtimes = [];
	foreach ( $paths as $rel ) {
		$abs = ELEMENTOR_GSAP_PATH . $rel;
		$mtimes[] = file_exists( $abs ) ? filemtime( $abs ) : 0;
	}
	return md5( ELEMENTOR_GSAP_VERSION . '|r' . ELEMENTOR_GSAP_ASSET_REVISION . '|' . implode( '|', $mtimes ) );
}

/**
 * Hapus cache CSS Elementor (per-post & global) + cache lain yang mungkin
 * menyimpan output stale, supaya next refresh user langsung dapat style baru
 * tanpa harus klik "Regenerate CSS" atau hard-refresh manual.
 */
function elementor_gsap_purge_elementor_cache() {
	if ( ! class_exists( '\Elementor\Plugin' ) ) {
		return;
	}
	$plugin = \Elementor\Plugin::$instance;

	// 1) Files manager: hapus per-post CSS files & global CSS.
	if ( $plugin && isset( $plugin->files_manager ) && method_exists( $plugin->files_manager, 'clear_cache' ) ) {
		$plugin->files_manager->clear_cache();
	}

	// 2) Frontend assets cache (Elementor 3.10+) — hapus transient yang menyimpan
	//    daftar widget per-post sehingga script/style dependency re-resolve.
	delete_option( '_elementor_assets_data' );
	delete_option( 'elementor_assets_data_version' );

	// 3) Object cache: flush group Elementor jika support per-group flush.
	if ( function_exists( 'wp_cache_flush_group' ) ) {
		wp_cache_flush_group( 'elementor' );
	}
}

/**
 * Auto-purge: bandingkan fingerprint asset; kalau berubah, purge sekali.
 * Dijalankan di admin & frontend supaya selalu sinkron.
 */
add_action( 'plugins_loaded', function () {
	if ( ! did_action( 'elementor/loaded' ) ) {
		return;
	}
	$current = elementor_gsap_assets_fingerprint();
	$stored  = get_option( 'elementor_gsap_assets_fp' );
	if ( $stored !== $current ) {
		elementor_gsap_purge_elementor_cache();
		update_option( 'elementor_gsap_assets_fp', $current, false );
	}
}, 20 );

/**
 * Activation/deactivation: pastikan cache fresh setelah update plugin.
 */
register_activation_hook( __FILE__, function () {
	delete_option( 'elementor_gsap_assets_fp' );
} );
register_deactivation_hook( __FILE__, function () {
	if ( class_exists( '\Elementor\Plugin' ) ) {
		elementor_gsap_purge_elementor_cache();
	}
} );

/**
 * Setiap kali user simpan dokumen Elementor, hapus cache CSS dokumen itu —
 * memastikan kontrol style (termasuk loading animation page-level) langsung
 * tampil di next refresh frontend tanpa manual regenerate.
 */
add_action( 'elementor/document/after_save', function ( $document ) {
	if ( ! $document ) {
		return;
	}
	$post_id = method_exists( $document, 'get_main_id' ) ? $document->get_main_id() : ( method_exists( $document, 'get_id' ) ? $document->get_id() : 0 );
	if ( ! $post_id ) {
		return;
	}
	if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
		try {
			$css = new \Elementor\Core\Files\CSS\Post( $post_id );
			$css->update();
		} catch ( \Throwable $e ) {
			elementor_gsap_purge_elementor_cache();
		}
	} else {
		elementor_gsap_purge_elementor_cache();
	}
}, 10, 1 );

add_action( 'plugins_loaded', function () {
	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', function () {
			echo '<div class="notice notice-warning"><p><strong>Elementor GSAP</strong> membutuhkan plugin Elementor aktif.</p></div>';
		} );
		return;
	}

	require_once ELEMENTOR_GSAP_PATH . 'includes/class-willem-loading-animation-template.php';
	require_once ELEMENTOR_GSAP_PATH . 'includes/class-willem-loading-animation-extension.php';
	require_once ELEMENTOR_GSAP_PATH . 'includes/class-crisp-loading-animation-template.php';
	require_once ELEMENTOR_GSAP_PATH . 'includes/class-crisp-loading-animation-extension.php';
	require_once ELEMENTOR_GSAP_PATH . 'includes/class-pixelated-transition-template.php';
	require_once ELEMENTOR_GSAP_PATH . 'includes/class-pixelated-transition-extension.php';
	require_once ELEMENTOR_GSAP_PATH . 'includes/class-welcoming-words-loader-template.php';
	require_once ELEMENTOR_GSAP_PATH . 'includes/class-welcoming-words-loader-extension.php';
	require_once ELEMENTOR_GSAP_PATH . 'includes/class-dropping-cards-loader-template.php';
	require_once ELEMENTOR_GSAP_PATH . 'includes/class-dropping-cards-loader-extension.php';
	require_once ELEMENTOR_GSAP_PATH . 'includes/class-logo-reveal-loader-template.php';
	require_once ELEMENTOR_GSAP_PATH . 'includes/class-logo-reveal-loader-extension.php';
	require_once ELEMENTOR_GSAP_PATH . 'includes/class-number-loader-template.php';
	require_once ELEMENTOR_GSAP_PATH . 'includes/class-number-loader-extension.php';

	// Urutan init menentukan urutan section di Page Settings > Style.
	// Kelompokkan berdasarkan prefix label ("Loaders •" & "Page Transitions •")
	// supaya section-nya berdekatan di panel Elementor.
	\Elementor_GSAP\Willem_Loading_Animation_Extension::init();
	\Elementor_GSAP\Crisp_Loading_Animation_Extension::init();
	\Elementor_GSAP\Welcoming_Words_Loader_Extension::init();
	\Elementor_GSAP\Dropping_Cards_Loader_Extension::init();
	\Elementor_GSAP\Logo_Reveal_Loader_Extension::init();
	\Elementor_GSAP\Number_Loader_Extension::init();
	\Elementor_GSAP\Pixelated_Transition_Extension::init();

	add_action( 'elementor/elements/categories_registered', function ( $manager ) {
		$categories = [
			'elementor-gsap-video'    => [ __( 'GSAP • Video & Audio', 'elementor-gsap' ),      'eicon-video-camera' ],
			'elementor-gsap-text'     => [ __( 'GSAP • Text Animations', 'elementor-gsap' ),    'eicon-t-letter' ],
			'elementor-gsap-sliders'  => [ __( 'GSAP • Sliders & Marquees', 'elementor-gsap' ), 'eicon-slider-push' ],
			'elementor-gsap-buttons'  => [ __( 'GSAP • Buttons', 'elementor-gsap' ),            'eicon-button' ],
			'elementor-gsap-hover'    => [ __( 'GSAP • Hover Interactions', 'elementor-gsap' ), 'eicon-image-rollover' ],
			'elementor-gsap-nav'      => [ __( 'GSAP • Navigation', 'elementor-gsap' ),         'eicon-nav-menu' ],
			'elementor-gsap-sections' => [ __( 'GSAP • Sections & Layouts', 'elementor-gsap' ), 'eicon-section' ],
			'elementor-gsap-scroll'   => [ __( 'GSAP • Scroll Animations', 'elementor-gsap' ), 'eicon-parallax' ],
		];
		foreach ( $categories as $slug => $meta ) {
			$manager->add_category( $slug, [
				'title' => $meta[0],
				'icon'  => $meta[1],
			] );
		}
	} );

	add_action( 'elementor/widgets/register', function ( $widgets_manager ) {
		require_once ELEMENTOR_GSAP_PATH . 'widgets/class-bunny-hls-player-widget.php';
		$widgets_manager->register( new \Elementor_GSAP\Widgets\Bunny_HLS_Player_Widget() );

		require_once ELEMENTOR_GSAP_PATH . 'widgets/class-masked-text-reveal-widget.php';
		$widgets_manager->register( new \Elementor_GSAP\Widgets\Masked_Text_Reveal_Widget() );

		require_once ELEMENTOR_GSAP_PATH . 'widgets/class-draggable-infinite-slider-widget.php';
		$widgets_manager->register( new \Elementor_GSAP\Widgets\Draggable_Infinite_Slider_Widget() );

		require_once ELEMENTOR_GSAP_PATH . 'widgets/class-button-draw-underline-widget.php';
		$widgets_manager->register( new \Elementor_GSAP\Widgets\Button_Draw_Underline_Widget() );

		require_once ELEMENTOR_GSAP_PATH . 'widgets/class-button-character-stagger-widget.php';
		$widgets_manager->register( new \Elementor_GSAP\Widgets\Button_Character_Stagger_Widget() );

		require_once ELEMENTOR_GSAP_PATH . 'widgets/class-looping-words-selector-widget.php';
		$widgets_manager->register( new \Elementor_GSAP\Widgets\Looping_Words_Selector_Widget() );

		require_once ELEMENTOR_GSAP_PATH . 'widgets/class-image-scroll-widget.php';
		$widgets_manager->register( new \Elementor_GSAP\Widgets\Image_Scroll_Widget() );

		require_once ELEMENTOR_GSAP_PATH . 'widgets/class-sidenav-wipe-widget.php';
		$widgets_manager->register( new \Elementor_GSAP\Widgets\Sidenav_Wipe_Widget() );

		require_once ELEMENTOR_GSAP_PATH . 'widgets/class-pixelated-image-reveal-widget.php';
		$widgets_manager->register( new \Elementor_GSAP\Widgets\Pixelated_Image_Reveal_Widget() );

		require_once ELEMENTOR_GSAP_PATH . 'widgets/class-fixed-underlay-navigation-widget.php';
		$widgets_manager->register( new \Elementor_GSAP\Widgets\Fixed_Underlay_Navigation_Widget() );

		require_once ELEMENTOR_GSAP_PATH . 'widgets/class-sticky-steps-widget.php';
		$widgets_manager->register( new \Elementor_GSAP\Widgets\Sticky_Steps_Widget() );

		require_once ELEMENTOR_GSAP_PATH . 'widgets/class-logo-wall-cycle-widget.php';
		$widgets_manager->register( new \Elementor_GSAP\Widgets\Logo_Wall_Cycle_Widget() );

		require_once ELEMENTOR_GSAP_PATH . 'widgets/class-sticky-features-widget.php';
		$widgets_manager->register( new \Elementor_GSAP\Widgets\Sticky_Features_Widget() );

		require_once ELEMENTOR_GSAP_PATH . 'widgets/class-expanding-bottom-nav-widget.php';
		$widgets_manager->register( new \Elementor_GSAP\Widgets\Expanding_Bottom_Nav_Widget() );

		require_once ELEMENTOR_GSAP_PATH . 'widgets/class-radial-cards-slider-widget.php';
		$widgets_manager->register( new \Elementor_GSAP\Widgets\Radial_Cards_Slider_Widget() );

		require_once ELEMENTOR_GSAP_PATH . 'widgets/class-step-timeline-widget.php';
		$widgets_manager->register( new \Elementor_GSAP\Widgets\Step_Timeline_Widget() );

		require_once ELEMENTOR_GSAP_PATH . 'widgets/class-gradient-wave-text-widget.php';
		$widgets_manager->register( new \Elementor_GSAP\Widgets\Gradient_Wave_Text_Widget() );
	} );

	add_action( 'elementor/frontend/after_register_scripts', function () {
		$gsap          = elementor_gsap_resolve_vendor( 'gsap' );
		$splittext     = elementor_gsap_resolve_vendor( 'splittext' );
		$customease    = elementor_gsap_resolve_vendor( 'customease' );
		$scrolltrigger = elementor_gsap_resolve_vendor( 'scrolltrigger' );
		$draggable     = elementor_gsap_resolve_vendor( 'draggable' );
		$inertia       = elementor_gsap_resolve_vendor( 'inertia' );
		$drawsvg       = elementor_gsap_resolve_vendor( 'drawsvg' );
		$hls           = elementor_gsap_resolve_vendor( 'hls' );

		wp_register_script( 'gsap',                $gsap['url'],          [],         $gsap['ver'],          true );
		wp_register_script( 'gsap-splittext',      $splittext['url'],     [ 'gsap' ], $splittext['ver'],     true );
		wp_register_script( 'gsap-customease',     $customease['url'],    [ 'gsap' ], $customease['ver'],    true );
		wp_register_script( 'gsap-scrolltrigger',  $scrolltrigger['url'], [ 'gsap' ], $scrolltrigger['ver'], true );
		wp_register_script( 'gsap-draggable',      $draggable['url'],     [ 'gsap' ], $draggable['ver'],     true );
		wp_register_script( 'gsap-inertia',        $inertia['url'],       [ 'gsap' ], $inertia['ver'],       true );
		wp_register_script( 'gsap-drawsvg',        $drawsvg['url'],       [ 'gsap' ], $drawsvg['ver'],       true );
		wp_register_script( 'hls-js',              $hls['url'],           [],         $hls['ver'],           true );
		wp_register_script(
			'elementor-willem-loading',
			ELEMENTOR_GSAP_URL . 'assets/js/willem-loading-animation.js',
			[ 'gsap' ],
			elementor_gsap_asset_ver( 'assets/js/willem-loading-animation.js' ),
			true
		);
		wp_register_script(
			'elementor-crisp-loading',
			ELEMENTOR_GSAP_URL . 'assets/js/crisp-loading-animation.js',
			[ 'gsap', 'gsap-splittext', 'gsap-customease' ],
			elementor_gsap_asset_ver( 'assets/js/crisp-loading-animation.js' ),
			true
		);
		wp_register_script(
			'elementor-bunny-hls-player',
			ELEMENTOR_GSAP_URL . 'assets/js/bunny-hls-player.js',
			[ 'hls-js' ],
			elementor_gsap_asset_ver( 'assets/js/bunny-hls-player.js' ),
			true
		);
		wp_register_script(
			'elementor-masked-text-reveal',
			ELEMENTOR_GSAP_URL . 'assets/js/masked-text-reveal.js',
			[ 'gsap', 'gsap-scrolltrigger', 'gsap-splittext' ],
			elementor_gsap_asset_ver( 'assets/js/masked-text-reveal.js' ),
			true
		);
		wp_register_script(
			'elementor-draggable-slider',
			ELEMENTOR_GSAP_URL . 'assets/js/draggable-infinite-slider.js',
			[ 'gsap', 'gsap-draggable', 'gsap-inertia' ],
			elementor_gsap_asset_ver( 'assets/js/draggable-infinite-slider.js' ),
			true
		);
		wp_register_script(
			'elementor-pixelated-transition',
			ELEMENTOR_GSAP_URL . 'assets/js/pixelated-transition.js',
			[ 'gsap' ],
			elementor_gsap_asset_ver( 'assets/js/pixelated-transition.js' ),
			true
		);
		wp_register_script(
			'elementor-button-draw-underline',
			ELEMENTOR_GSAP_URL . 'assets/js/button-draw-underline.js',
			[ 'gsap', 'gsap-drawsvg' ],
			elementor_gsap_asset_ver( 'assets/js/button-draw-underline.js' ),
			true
		);
		wp_register_script(
			'elementor-button-character-stagger',
			ELEMENTOR_GSAP_URL . 'assets/js/button-character-stagger.js',
			[],
			elementor_gsap_asset_ver( 'assets/js/button-character-stagger.js' ),
			true
		);
		wp_register_script(
			'elementor-looping-words-selector',
			ELEMENTOR_GSAP_URL . 'assets/js/looping-words-selector.js',
			[ 'gsap' ],
			elementor_gsap_asset_ver( 'assets/js/looping-words-selector.js' ),
			true
		);
		wp_register_script(
			'elementor-image-scroll',
			ELEMENTOR_GSAP_URL . 'assets/js/image-scroll.js',
			[],
			elementor_gsap_asset_ver( 'assets/js/image-scroll.js' ),
			true
		);
		wp_register_script(
			'elementor-sidenav-wipe',
			ELEMENTOR_GSAP_URL . 'assets/js/sidenav-wipe.js',
			[ 'gsap', 'gsap-customease' ],
			elementor_gsap_asset_ver( 'assets/js/sidenav-wipe.js' ),
			true
		);
		wp_register_script(
			'elementor-pixelated-image-reveal',
			ELEMENTOR_GSAP_URL . 'assets/js/pixelated-image-reveal.js',
			[ 'gsap' ],
			elementor_gsap_asset_ver( 'assets/js/pixelated-image-reveal.js' ),
			true
		);
		wp_register_script(
			'elementor-fixed-underlay-navigation',
			ELEMENTOR_GSAP_URL . 'assets/js/fixed-underlay-navigation.js',
			[ 'gsap', 'gsap-customease' ],
			elementor_gsap_asset_ver( 'assets/js/fixed-underlay-navigation.js' ),
			true
		);
		wp_register_script(
			'elementor-welcoming-words-loader',
			ELEMENTOR_GSAP_URL . 'assets/js/welcoming-words-loader.js',
			[ 'gsap' ],
			elementor_gsap_asset_ver( 'assets/js/welcoming-words-loader.js' ),
			true
		);
		wp_register_script(
			'elementor-sticky-steps',
			ELEMENTOR_GSAP_URL . 'assets/js/sticky-steps.js',
			[ 'gsap', 'gsap-scrolltrigger' ],
			elementor_gsap_asset_ver( 'assets/js/sticky-steps.js' ),
			true
		);
		wp_register_script(
			'elementor-logo-wall-cycle',
			ELEMENTOR_GSAP_URL . 'assets/js/logo-wall-cycle.js',
			[ 'gsap', 'gsap-scrolltrigger' ],
			elementor_gsap_asset_ver( 'assets/js/logo-wall-cycle.js' ),
			true
		);
		wp_register_script(
			'elementor-sticky-features',
			ELEMENTOR_GSAP_URL . 'assets/js/sticky-features.js',
			[ 'gsap', 'gsap-scrolltrigger' ],
			elementor_gsap_asset_ver( 'assets/js/sticky-features.js' ),
			true
		);
		wp_register_script(
			'elementor-expanding-bottom-nav',
			ELEMENTOR_GSAP_URL . 'assets/js/expanding-bottom-nav.js',
			[ 'gsap', 'gsap-customease' ],
			elementor_gsap_asset_ver( 'assets/js/expanding-bottom-nav.js' ),
			true
		);
		wp_register_script(
			'elementor-radial-cards-slider',
			ELEMENTOR_GSAP_URL . 'assets/js/radial-cards-slider.js',
			[ 'gsap', 'gsap-draggable', 'gsap-inertia', 'gsap-customease' ],
			elementor_gsap_asset_ver( 'assets/js/radial-cards-slider.js' ),
			true
		);
		wp_register_script(
			'elementor-step-timeline',
			ELEMENTOR_GSAP_URL . 'assets/js/step-timeline.js',
			[ 'gsap', 'gsap-scrolltrigger' ],
			elementor_gsap_asset_ver( 'assets/js/step-timeline.js' ),
			true
		);
		wp_register_script(
			'elementor-gradient-wave-text',
			ELEMENTOR_GSAP_URL . 'assets/js/gradient-wave-text.js',
			[ 'gsap', 'gsap-scrolltrigger', 'gsap-splittext' ],
			elementor_gsap_asset_ver( 'assets/js/gradient-wave-text.js' ),
			true
		);
		wp_register_script(
			'elementor-dropping-cards-loader',
			ELEMENTOR_GSAP_URL . 'assets/js/dropping-cards-loader.js',
			[ 'gsap', 'gsap-customease' ],
			elementor_gsap_asset_ver( 'assets/js/dropping-cards-loader.js' ),
			true
		);
		wp_register_script(
			'elementor-logo-reveal-loader',
			ELEMENTOR_GSAP_URL . 'assets/js/logo-reveal-loader.js',
			[ 'gsap', 'gsap-customease', 'gsap-splittext' ],
			elementor_gsap_asset_ver( 'assets/js/logo-reveal-loader.js' ),
			true
		);
		wp_register_script(
			'elementor-number-loader',
			ELEMENTOR_GSAP_URL . 'assets/js/number-loader.js',
			[ 'gsap' ],
			elementor_gsap_asset_ver( 'assets/js/number-loader.js' ),
			true
		);
	} );

	add_action( 'elementor/frontend/after_register_styles', function () {
		wp_register_style(
			'elementor-willem-loading',
			ELEMENTOR_GSAP_URL . 'assets/css/willem-loading-animation.css',
			[],
			elementor_gsap_asset_ver( 'assets/css/willem-loading-animation.css' )
		);
		wp_register_style(
			'elementor-crisp-loading',
			ELEMENTOR_GSAP_URL . 'assets/css/crisp-loading-animation.css',
			[],
			elementor_gsap_asset_ver( 'assets/css/crisp-loading-animation.css' )
		);
		wp_register_style(
			'elementor-bunny-hls-player',
			ELEMENTOR_GSAP_URL . 'assets/css/bunny-hls-player.css',
			[],
			elementor_gsap_asset_ver( 'assets/css/bunny-hls-player.css' )
		);
		wp_register_style(
			'elementor-masked-text-reveal',
			ELEMENTOR_GSAP_URL . 'assets/css/masked-text-reveal.css',
			[],
			elementor_gsap_asset_ver( 'assets/css/masked-text-reveal.css' )
		);
		wp_register_style(
			'elementor-draggable-slider',
			ELEMENTOR_GSAP_URL . 'assets/css/draggable-infinite-slider.css',
			[],
			elementor_gsap_asset_ver( 'assets/css/draggable-infinite-slider.css' )
		);
		wp_register_style(
			'elementor-pixelated-transition',
			ELEMENTOR_GSAP_URL . 'assets/css/pixelated-transition.css',
			[],
			elementor_gsap_asset_ver( 'assets/css/pixelated-transition.css' )
		);
		wp_register_style(
			'elementor-button-draw-underline',
			ELEMENTOR_GSAP_URL . 'assets/css/button-draw-underline.css',
			[],
			elementor_gsap_asset_ver( 'assets/css/button-draw-underline.css' )
		);
		wp_register_style(
			'elementor-button-character-stagger',
			ELEMENTOR_GSAP_URL . 'assets/css/button-character-stagger.css',
			[],
			elementor_gsap_asset_ver( 'assets/css/button-character-stagger.css' )
		);
		wp_register_style(
			'elementor-looping-words-selector',
			ELEMENTOR_GSAP_URL . 'assets/css/looping-words-selector.css',
			[],
			elementor_gsap_asset_ver( 'assets/css/looping-words-selector.css' )
		);
		wp_register_style(
			'elementor-image-scroll',
			ELEMENTOR_GSAP_URL . 'assets/css/image-scroll.css',
			[],
			elementor_gsap_asset_ver( 'assets/css/image-scroll.css' )
		);
		wp_register_style(
			'elementor-sidenav-wipe',
			ELEMENTOR_GSAP_URL . 'assets/css/sidenav-wipe.css',
			[],
			elementor_gsap_asset_ver( 'assets/css/sidenav-wipe.css' )
		);
		wp_register_style(
			'elementor-pixelated-image-reveal',
			ELEMENTOR_GSAP_URL . 'assets/css/pixelated-image-reveal.css',
			[],
			elementor_gsap_asset_ver( 'assets/css/pixelated-image-reveal.css' )
		);
		wp_register_style(
			'elementor-fixed-underlay-navigation',
			ELEMENTOR_GSAP_URL . 'assets/css/fixed-underlay-navigation.css',
			[],
			elementor_gsap_asset_ver( 'assets/css/fixed-underlay-navigation.css' )
		);
		wp_register_style(
			'elementor-welcoming-words-loader',
			ELEMENTOR_GSAP_URL . 'assets/css/welcoming-words-loader.css',
			[],
			elementor_gsap_asset_ver( 'assets/css/welcoming-words-loader.css' )
		);
		wp_register_style(
			'elementor-sticky-steps',
			ELEMENTOR_GSAP_URL . 'assets/css/sticky-steps.css',
			[],
			elementor_gsap_asset_ver( 'assets/css/sticky-steps.css' )
		);
		wp_register_style(
			'elementor-logo-wall-cycle',
			ELEMENTOR_GSAP_URL . 'assets/css/logo-wall-cycle.css',
			[],
			elementor_gsap_asset_ver( 'assets/css/logo-wall-cycle.css' )
		);
		wp_register_style(
			'elementor-sticky-features',
			ELEMENTOR_GSAP_URL . 'assets/css/sticky-features.css',
			[],
			elementor_gsap_asset_ver( 'assets/css/sticky-features.css' )
		);
		wp_register_style(
			'elementor-expanding-bottom-nav',
			ELEMENTOR_GSAP_URL . 'assets/css/expanding-bottom-nav.css',
			[],
			elementor_gsap_asset_ver( 'assets/css/expanding-bottom-nav.css' )
		);
		wp_register_style(
			'elementor-radial-cards-slider',
			ELEMENTOR_GSAP_URL . 'assets/css/radial-cards-slider.css',
			[],
			elementor_gsap_asset_ver( 'assets/css/radial-cards-slider.css' )
		);
		wp_register_style(
			'elementor-step-timeline',
			ELEMENTOR_GSAP_URL . 'assets/css/step-timeline.css',
			[],
			elementor_gsap_asset_ver( 'assets/css/step-timeline.css' )
		);
		wp_register_style(
			'elementor-gradient-wave-text',
			ELEMENTOR_GSAP_URL . 'assets/css/gradient-wave-text.css',
			[],
			elementor_gsap_asset_ver( 'assets/css/gradient-wave-text.css' )
		);
		wp_register_style(
			'elementor-dropping-cards-loader',
			ELEMENTOR_GSAP_URL . 'assets/css/dropping-cards-loader.css',
			[],
			elementor_gsap_asset_ver( 'assets/css/dropping-cards-loader.css' )
		);
		wp_register_style(
			'elementor-logo-reveal-loader',
			ELEMENTOR_GSAP_URL . 'assets/css/logo-reveal-loader.css',
			[],
			elementor_gsap_asset_ver( 'assets/css/logo-reveal-loader.css' )
		);
		wp_register_style(
			'elementor-number-loader',
			ELEMENTOR_GSAP_URL . 'assets/css/number-loader.css',
			[],
			elementor_gsap_asset_ver( 'assets/css/number-loader.css' )
		);
	} );
} );
