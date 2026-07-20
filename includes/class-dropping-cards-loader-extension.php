<?php
namespace Elementor_GSAP;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Dropping_Cards_Loader_Extension {

	const DOCUMENT_TYPES = [ 'wp-page', 'wp-post', 'landing-page' ];

	public static function init() {
		foreach ( self::DOCUMENT_TYPES as $type ) {
			add_action(
				"elementor/element/{$type}/section_page_style/after_section_end",
				[ __CLASS__, 'register_controls' ]
			);
		}
		add_action( 'wp_body_open', [ __CLASS__, 'render_loader' ] );
	}

	public static function register_controls( $element ) {
		Dropping_Cards_Loader_Template::register_controls( $element );
	}

	public static function render_loader() {
		// Skip di editor preview iframe supaya overlay tidak menutupi editing.
		if ( self::is_editor_preview() ) {
			return;
		}

		$document = self::get_current_document();
		if ( ! $document ) {
			return;
		}

		$settings   = $document->get_settings_for_display();
		$enable_key = Dropping_Cards_Loader_Template::key( 'enable' );

		if ( empty( $settings[ $enable_key ] ) || 'yes' !== $settings[ $enable_key ] ) {
			return;
		}

		wp_enqueue_script( 'gsap' );
		wp_enqueue_script( 'gsap-customease' );
		wp_enqueue_script( 'elementor-dropping-cards-loader' );
		wp_enqueue_style( 'elementor-dropping-cards-loader' );

		Dropping_Cards_Loader_Template::render( $settings, $document->get_id() );
	}

	private static function is_editor_preview() {
		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			return false;
		}
		$plugin = \Elementor\Plugin::$instance;
		if ( isset( $plugin->preview ) && method_exists( $plugin->preview, 'is_preview_mode' ) && $plugin->preview->is_preview_mode() ) {
			return true;
		}
		if ( isset( $plugin->editor ) && method_exists( $plugin->editor, 'is_edit_mode' ) && $plugin->editor->is_edit_mode() ) {
			return true;
		}
		return false;
	}

	private static function get_current_document() {
		if ( ! is_singular() ) {
			return null;
		}
		$post_id = get_the_ID();
		if ( ! $post_id ) {
			return null;
		}
		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			return null;
		}
		return \Elementor\Plugin::$instance->documents->get( $post_id );
	}
}
