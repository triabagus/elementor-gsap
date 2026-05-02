<?php
namespace Elementor_GSAP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Masked_Text_Reveal_Widget extends Widget_Base {

	public function get_name() {
		return 'masked_text_reveal';
	}

	public function get_title() {
		return __( 'Masked Text Reveal', 'elementor-gsap' );
	}

	public function get_icon() {
		return 'eicon-animation-text';
	}

	public function get_categories() {
		return [ 'elementor-gsap' ];
	}

	public function get_keywords() {
		return [ 'gsap', 'splittext', 'reveal', 'animation', 'text', 'mask', 'scroll', 'scrolltrigger' ];
	}

	public function get_script_depends() {
		return [ 'gsap', 'gsap-scrolltrigger', 'gsap-splittext', 'elementor-masked-text-reveal' ];
	}

	public function get_style_depends() {
		return [ 'elementor-masked-text-reveal' ];
	}

	protected function register_controls() {

		/* === CONTENT === */
		$this->start_controls_section( 'content_section', [
			'label' => __( 'Content', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'text', [
			'label'       => __( 'Text', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => __( "We're using gsap split text to break this content into lines, words, and individual characters. Experiment with straggered tweens, custom ease function, and dynamic transforms to bring your headlines to life.", 'elementor-gsap' ),
			'rows'        => 4,
			'placeholder' => __( 'Tulis teks yang ingin di-reveal saat scroll...', 'elementor-gsap' ),
			'dynamic'     => [ 'active' => true ],
		] );

		$this->add_control( 'tag', [
			'label'   => __( 'HTML Tag', 'elementor-gsap' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'h1'   => 'H1',
				'h2'   => 'H2',
				'h3'   => 'H3',
				'h4'   => 'H4',
				'h5'   => 'H5',
				'h6'   => 'H6',
				'p'    => 'p',
				'div'  => 'div',
				'span' => 'span',
			],
			'default' => 'h2',
		] );

		$this->add_control( 'split_type', [
			'label'       => __( 'Split Type', 'elementor-gsap' ),
			'type'        => Controls_Manager::SELECT,
			'description' => __( 'Lines → split by lines only. Words → split by lines and words. Letters → split by lines, words and characters', 'elementor-gsap' ),
			'options'     => [
				'lines' => __( 'Lines', 'elementor-gsap' ),
				'words' => __( 'Words', 'elementor-gsap' ),
				'chars' => __( 'Letters', 'elementor-gsap' ),
			],
			'default'     => 'lines',
		] );

		$this->end_controls_section();

		/* === ANIMATION === */
		$this->start_controls_section( 'animation_section', [
			'label' => __( 'Animation', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'duration', [
			'label'       => __( 'Duration (s)', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0.1,
			'max'         => 3,
			'step'        => 0.05,
			'default'     => '',
			'placeholder' => __( 'Auto (lines: 0.8, words: 0.6, chars: 0.4)', 'elementor-gsap' ),
		] );

		$this->add_control( 'stagger', [
			'label'       => __( 'Stagger (s)', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0,
			'max'         => 1,
			'step'        => 0.005,
			'default'     => '',
			'placeholder' => __( 'Auto (lines: 0.08, words: 0.06, chars: 0.01)', 'elementor-gsap' ),
		] );

		$this->add_control( 'y_percent', [
			'label'   => __( 'Y Percent (start position)', 'elementor-gsap' ),
			'type'    => Controls_Manager::NUMBER,
			'min'     => -200,
			'max'     => 200,
			'default' => 110,
		] );

		$this->add_control( 'ease', [
			'label'   => __( 'Easing', 'elementor-gsap' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'expo.out'   => 'expo.out',
				'power2.out' => 'power2.out',
				'power3.out' => 'power3.out',
				'power4.out' => 'power4.out',
				'circ.out'   => 'circ.out',
				'back.out'   => 'back.out',
				'sine.out'   => 'sine.out',
				'none'       => 'none (linear)',
			],
			'default' => 'expo.out',
		] );

		$this->add_control( 'scroll_start', [
			'label'       => __( 'ScrollTrigger Start', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => 'clamp(top 80%)',
			'description' => sprintf(
				/* translators: %s: link to ScrollTrigger documentation */
				__( 'Contoh: "top 80%%", "clamp(top 80%%)", "top center". %s', 'elementor-gsap' ),
				'<a href="https://gsap.com/docs/v3/Plugins/ScrollTrigger/" target="_blank" rel="noopener noreferrer">' . __( 'Lihat docs ScrollTrigger', 'elementor-gsap' ) . '</a>'
			),
		] );

		$this->add_control( 'once', [
			'label'        => __( 'Animate Once Only', 'elementor-gsap' ),
			'description'  => __( 'Kalau off, animasi replay setiap kali element re-enter viewport.', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->end_controls_section();

		/* === STYLE === */
		$this->start_controls_section( 'style_section', [
			'label' => __( 'Style', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'alignment', [
			'label'     => __( 'Alignment', 'elementor-gsap' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'left'    => [ 'title' => __( 'Left', 'elementor-gsap' ), 'icon' => 'eicon-text-align-left' ],
				'center'  => [ 'title' => __( 'Center', 'elementor-gsap' ), 'icon' => 'eicon-text-align-center' ],
				'right'   => [ 'title' => __( 'Right', 'elementor-gsap' ), 'icon' => 'eicon-text-align-right' ],
				'justify' => [ 'title' => __( 'Justify', 'elementor-gsap' ), 'icon' => 'eicon-text-align-justify' ],
			],
			'selectors' => [
				'{{WRAPPER}} .emt-masked-text' => 'text-align: {{VALUE}};',
			],
		] );

		$this->add_control( 'text_color', [
			'label'     => __( 'Text Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .emt-masked-text' => 'color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'typography',
			'selector' => '{{WRAPPER}} .emt-masked-text',
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$s            = $this->get_settings_for_display();
		$text         = isset( $s['text'] ) ? $s['text'] : '';
		$tag          = in_array( $s['tag'], [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div', 'span' ], true ) ? $s['tag'] : 'h2';
		$split_type   = in_array( $s['split_type'], [ 'lines', 'words', 'chars' ], true ) ? $s['split_type'] : 'lines';
		$y_percent    = isset( $s['y_percent'] ) && '' !== $s['y_percent'] ? floatval( $s['y_percent'] ) : 110;
		$ease         = ! empty( $s['ease'] ) ? $s['ease'] : 'expo.out';
		$scroll_start = ! empty( $s['scroll_start'] ) ? $s['scroll_start'] : 'clamp(top 80%)';
		$once         = ( 'yes' === ( $s['once'] ?? 'yes' ) ) ? 'true' : 'false';

		$is_edit = class_exists( '\Elementor\Plugin' )
			&& \Elementor\Plugin::$instance->editor
			&& \Elementor\Plugin::$instance->editor->is_edit_mode();

		$attrs = [
			'class'             => 'emt-masked-text',
			'data-split'        => 'heading',
			'data-split-reveal' => $split_type,
			'data-emt-y'        => (string) $y_percent,
			'data-emt-ease'     => $ease,
			'data-emt-start'    => $scroll_start,
			'data-emt-once'     => $once,
		];

		if ( '' !== $s['duration'] && null !== $s['duration'] ) {
			$attrs['data-emt-duration'] = (string) floatval( $s['duration'] );
		}
		if ( '' !== $s['stagger'] && null !== $s['stagger'] ) {
			$attrs['data-emt-stagger'] = (string) floatval( $s['stagger'] );
		}

		// In editor mode, override the CSS visibility:hidden so user can see the text
		if ( $is_edit ) {
			$attrs['style'] = 'visibility: visible;';
		}

		$attr_str = '';
		foreach ( $attrs as $k => $v ) {
			$attr_str .= ' ' . $k . '="' . esc_attr( $v ) . '"';
		}

		printf(
			'<%1$s%2$s>%3$s</%1$s>',
			tag_escape( $tag ),
			$attr_str,
			esc_html( $text )
		);
	}
}
