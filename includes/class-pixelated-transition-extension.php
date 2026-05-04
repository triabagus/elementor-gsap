<?php
namespace Elementor_GSAP;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Pixelated_Transition_Extension {

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
		Pixelated_Transition_Template::register_controls( $element );
	}

	public static function render_loader() {
		// Skip in editor preview — would cover the canvas
		if ( class_exists( '\Elementor\Plugin' )
			&& \Elementor\Plugin::$instance->editor
			&& \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			return;
		}

		$document = self::get_current_document();
		if ( ! $document ) {
			return;
		}

		$settings   = $document->get_settings_for_display();
		$enable_key = Pixelated_Transition_Template::key( 'enable' );

		if ( empty( $settings[ $enable_key ] ) || 'yes' !== $settings[ $enable_key ] ) {
			return;
		}

		wp_enqueue_script( 'gsap' );
		wp_enqueue_script( 'elementor-pixelated-transition' );
		wp_enqueue_style( 'elementor-pixelated-transition' );

		Pixelated_Transition_Template::render( $settings, $document->get_id() );
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
