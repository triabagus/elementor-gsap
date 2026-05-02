<?php
/**
 * Plugin Name: Elementor GSAP with Osmo
 * Description: Ekstensi Elementor bertenaga GSAP bergaya Osmo.
 * Version: 1.1.0
 * Author: Creativetria
 * Requires Plugins: elementor
 * Elementor tested up to: 4.0.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ELEMENTOR_GSAP_VERSION', '1.1.0' );
define( 'ELEMENTOR_GSAP_PATH', plugin_dir_path( __FILE__ ) );
define( 'ELEMENTOR_GSAP_URL', plugin_dir_url( __FILE__ ) );

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

	\Elementor_GSAP\Willem_Loading_Animation_Extension::init();
	\Elementor_GSAP\Crisp_Loading_Animation_Extension::init();

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
	} );

	add_action( 'elementor/frontend/after_register_scripts', function () {
		wp_register_script(
			'gsap',
			'https://cdn.jsdelivr.net/npm/gsap@3.15/dist/gsap.min.js',
			[],
			'3.15.0',
			true
		);
		wp_register_script(
			'gsap-splittext',
			'https://cdn.jsdelivr.net/npm/gsap@3.15/dist/SplitText.min.js',
			[ 'gsap' ],
			'3.15.0',
			true
		);
		wp_register_script(
			'gsap-customease',
			'https://cdn.jsdelivr.net/npm/gsap@3.15/dist/CustomEase.min.js',
			[ 'gsap' ],
			'3.15.0',
			true
		);
		wp_register_script(
			'gsap-scrolltrigger',
			'https://cdn.jsdelivr.net/npm/gsap@3.15/dist/ScrollTrigger.min.js',
			[ 'gsap' ],
			'3.15.0',
			true
		);
		wp_register_script(
			'hls-js',
			'https://cdn.jsdelivr.net/npm/hls.js@1.6.11',
			[],
			'1.6.11',
			true
		);
		wp_register_script(
			'elementor-willem-loading',
			ELEMENTOR_GSAP_URL . 'assets/js/willem-loading-animation.js',
			[ 'gsap' ],
			ELEMENTOR_GSAP_VERSION,
			true
		);
		wp_register_script(
			'elementor-crisp-loading',
			ELEMENTOR_GSAP_URL . 'assets/js/crisp-loading-animation.js',
			[ 'gsap', 'gsap-splittext', 'gsap-customease' ],
			ELEMENTOR_GSAP_VERSION,
			true
		);
		wp_register_script(
			'elementor-bunny-hls-player',
			ELEMENTOR_GSAP_URL . 'assets/js/bunny-hls-player.js',
			[ 'hls-js' ],
			ELEMENTOR_GSAP_VERSION,
			true
		);
		wp_register_script(
			'elementor-masked-text-reveal',
			ELEMENTOR_GSAP_URL . 'assets/js/masked-text-reveal.js',
			[ 'gsap', 'gsap-scrolltrigger', 'gsap-splittext' ],
			ELEMENTOR_GSAP_VERSION,
			true
		);
	} );

	add_action( 'elementor/frontend/after_register_styles', function () {
		wp_register_style(
			'elementor-willem-loading',
			ELEMENTOR_GSAP_URL . 'assets/css/willem-loading-animation.css',
			[],
			ELEMENTOR_GSAP_VERSION
		);
		wp_register_style(
			'elementor-crisp-loading',
			ELEMENTOR_GSAP_URL . 'assets/css/crisp-loading-animation.css',
			[],
			ELEMENTOR_GSAP_VERSION
		);
		wp_register_style(
			'elementor-bunny-hls-player',
			ELEMENTOR_GSAP_URL . 'assets/css/bunny-hls-player.css',
			[],
			ELEMENTOR_GSAP_VERSION
		);
		wp_register_style(
			'elementor-masked-text-reveal',
			ELEMENTOR_GSAP_URL . 'assets/css/masked-text-reveal.css',
			[],
			ELEMENTOR_GSAP_VERSION
		);
	} );
} );
