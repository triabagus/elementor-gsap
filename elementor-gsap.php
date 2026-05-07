<?php
/**
 * Plugin Name: Elementor GSAP with Osmo
 * Description: Ekstensi Elementor bertenaga GSAP bergaya Osmo.
 * Version: 1.2.1
 * Author: Creativetria
 * Requires Plugins: elementor
 * Elementor tested up to: 4.0.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ELEMENTOR_GSAP_VERSION', '1.2.1' );
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
		return ELEMENTOR_GSAP_VERSION . '.' . filemtime( $abs );
	}
	return ELEMENTOR_GSAP_VERSION;
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
		'assets/css/pixelated-image-reveal.css',
		'assets/js/willem-loading-animation.js',
		'assets/js/crisp-loading-animation.js',
		'assets/js/bunny-hls-player.js',
		'assets/js/masked-text-reveal.js',
		'assets/js/draggable-infinite-slider.js',
		'assets/js/pixelated-transition.js',
		'assets/js/button-draw-underline.js',
		'assets/js/pixelated-image-reveal.js',
		'includes/class-willem-loading-animation-template.php',
		'includes/class-crisp-loading-animation-template.php',
		'includes/class-pixelated-transition-template.php',
		'widgets/class-bunny-hls-player-widget.php',
		'widgets/class-masked-text-reveal-widget.php',
		'widgets/class-draggable-infinite-slider-widget.php',
		'widgets/class-button-draw-underline-widget.php',
		'widgets/class-pixelated-image-reveal-widget.php',
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
	return md5( ELEMENTOR_GSAP_VERSION . '|' . implode( '|', $mtimes ) );
}

/**
 * Hapus cache CSS Elementor (per-post & global) — supaya next refresh user
 * langsung dapat style baru tanpa harus klik "Regenerate CSS".
 */
function elementor_gsap_purge_elementor_cache() {
	if ( ! class_exists( '\Elementor\Plugin' ) ) {
		return;
	}
	$plugin = \Elementor\Plugin::$instance;
	if ( $plugin && isset( $plugin->files_manager ) && method_exists( $plugin->files_manager, 'clear_cache' ) ) {
		$plugin->files_manager->clear_cache();
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

	\Elementor_GSAP\Willem_Loading_Animation_Extension::init();
	\Elementor_GSAP\Crisp_Loading_Animation_Extension::init();
	\Elementor_GSAP\Pixelated_Transition_Extension::init();

	add_action( 'elementor/elements/categories_registered', function ( $manager ) {
		$manager->add_category( 'elementor-gsap', [
			'title' => __( 'Elements GSAP', 'elementor-gsap' ),
			'icon'  => 'eicon-animation',
		] );
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

		require_once ELEMENTOR_GSAP_PATH . 'widgets/class-pixelated-image-reveal-widget.php';
		$widgets_manager->register( new \Elementor_GSAP\Widgets\Pixelated_Image_Reveal_Widget() );
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
			'elementor-pixelated-image-reveal',
			ELEMENTOR_GSAP_URL . 'assets/js/pixelated-image-reveal.js',
			[ 'gsap' ],
			elementor_gsap_asset_ver( 'assets/js/pixelated-image-reveal.js' ),
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
			'elementor-pixelated-image-reveal',
			ELEMENTOR_GSAP_URL . 'assets/css/pixelated-image-reveal.css',
			[],
			elementor_gsap_asset_ver( 'assets/css/pixelated-image-reveal.css' )
		);
	} );
} );
