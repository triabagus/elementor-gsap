<?php
namespace Elementor_GSAP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Radial_Cards_Slider_Widget extends Widget_Base {

	public function get_name() {
		return 'radial_cards_slider';
	}

	public function get_title() {
		return __( 'Radial Cards Slider (GSAP)', 'elementor-gsap' );
	}

	public function get_icon() {
		return 'eicon-carousel';
	}

	public function get_categories() {
		return [ 'elementor-gsap-sliders' ];
	}

	public function get_keywords() {
		return [ 'radial', 'slider', 'wheel', 'carousel', 'cards', 'draggable', 'gsap', 'osmo' ];
	}

	public function get_script_depends() {
		return [ 'gsap', 'gsap-draggable', 'gsap-inertia', 'gsap-customease', 'elementor-radial-cards-slider' ];
	}

	public function get_style_depends() {
		return [ 'elementor-radial-cards-slider' ];
	}

	private function default_slides() {
		$base = 'https://cdn.prod.website-files.com/6a466f2af326ef4f6fa96bea/';
		return [
			[ 'image' => [ 'url' => $base . '6a4d1c01a1f65fd36288d7ef_Serene%20Outdoor%20Portrait.avif' ],   'heading' => 'RÄDIAL',   'alt' => 'Serene outdoor portrait' ],
			[ 'image' => [ 'url' => $base . '6a4d45b12f3875c58d8221f4_Tropical%20Cocktail%20Scene.avif' ],  'heading' => 'TRØPICAL', 'alt' => 'Tropical cocktail scene' ],
			[ 'image' => [ 'url' => $base . '6a4d3b4bddf3fe861d763d39_Translucent%20Leaf%20Veins.avif' ],   'heading' => 'LEĀF',     'alt' => 'Translucent leaf veins' ],
			[ 'image' => [ 'url' => $base . '6a4d3b842ef017e090cc0d85_Cyclist%20in%20Dubai%20Scene.avif' ], 'heading' => 'DÜBAI',    'alt' => 'Cyclist in Dubai scene' ],
			[ 'image' => [ 'url' => $base . '6a4d3bea2f3875c58d7c3936_Yoga%20in%20Serene%20Setting.avif' ], 'heading' => 'YØGA',     'alt' => 'Yoga in serene setting' ],
		];
	}

	protected function register_controls() {

		/* === CONTENT: SLIDES === */
		$this->start_controls_section( 'content_slides', [
			'label' => __( 'Slides', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$rep = new Repeater();
		$rep->add_control( 'image', [
			'label'   => __( 'Image', 'elementor-gsap' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [ 'url' => '' ],
			'dynamic' => [ 'active' => true ],
		] );
		$rep->add_control( 'alt', [
			'label'   => __( 'Alt Text', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
			'dynamic' => [ 'active' => true ],
		] );
		$rep->add_control( 'heading', [
			'label'   => __( 'Heading', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'SLIDE',
			'dynamic' => [ 'active' => true ],
		] );

		$this->add_control( 'slides', [
			'label'       => __( 'Slides', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ heading }}}',
			'default'     => $this->default_slides(),
		] );

		$this->end_controls_section();

		/* === CONTENT: LAYOUT === */
		$this->start_controls_section( 'content_layout', [
			'label' => __( 'Wheel Layout', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_responsive_control( 'rotate_step', [
			'label'       => __( 'Rotate Step (deg)', 'elementor-gsap' ),
			'description' => __( 'Sudut antar-card. Kecil = card berdekatan; besar = jarak besar. Default 18°.', 'elementor-gsap' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'deg' ],
			'range'       => [ 'deg' => [ 'min' => 5, 'max' => 45, 'step' => 0.5 ] ],
			'default'        => [ 'unit' => 'deg', 'size' => 18 ],
			'mobile_default' => [ 'unit' => 'deg', 'size' => 12 ],
			'selectors'   => [
				'{{WRAPPER}} .radial-gsap-slider' => '--slider-rotate: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'wheel_radius', [
			'label'       => __( 'Wheel Radius', 'elementor-gsap' ),
			'description' => __( 'Radius wheel (%). Besar = card melengkung sedikit; kecil = melengkung tajam. Default 375%.', 'elementor-gsap' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ '%' ],
			'range'       => [ '%' => [ 'min' => 100, 'max' => 800, 'step' => 5 ] ],
			'default'        => [ 'unit' => '%', 'size' => 375 ],
			'mobile_default' => [ 'unit' => '%', 'size' => 475 ],
			'selectors'   => [
				'{{WRAPPER}} .radial-gsap-slider' => '--slider-radius: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'item_width', [
			'label'      => __( 'Card Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 8, 'max' => 40, 'step' => 0.5 ],
				'px' => [ 'min' => 120, 'max' => 600 ],
			],
			'default'        => [ 'unit' => 'em', 'size' => 20 ],
			'mobile_default' => [ 'unit' => 'em', 'size' => 15 ],
			'selectors'  => [
				'{{WRAPPER}} .radial-gsap-slider' => '--rcs-item-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'slider_gap', [
			'label'      => __( 'Slider ↔ Controls Gap', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 12, 'step' => 0.25 ],
				'px' => [ 'min' => 0, 'max' => 200 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 5 ],
			'selectors'  => [
				'{{WRAPPER}} .radial-gsap-slider' => '--rcs-slider-gap: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'slider_pad_top', [
			'label'      => __( 'Slider Top Padding', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 12, 'step' => 0.25 ],
				'px' => [ 'min' => 0, 'max' => 200 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 5 ],
			'selectors'  => [
				'{{WRAPPER}} .radial-gsap-slider' => '--rcs-slider-pad-top: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		/* === CONTENT: CONTROLS === */
		$this->start_controls_section( 'content_controls', [
			'label' => __( 'Controls', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'show_arrows', [
			'label'        => __( 'Show Prev/Next Buttons', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'prev_label', [
			'label'     => __( 'Prev Button Label', 'elementor-gsap' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => 'Prev',
			'condition' => [ 'show_arrows' => 'yes' ],
		] );

		$this->add_control( 'next_label', [
			'label'     => __( 'Next Button Label', 'elementor-gsap' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => 'Next',
			'condition' => [ 'show_arrows' => 'yes' ],
		] );

		$this->add_control( 'show_dots', [
			'label'        => __( 'Show Dots Navigation', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'slide_duration', [
			'label'       => __( 'Slide Duration (s)', 'elementor-gsap' ),
			'description' => __( 'Durasi transisi saat klik prev/next atau dot.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0.2,
			'max'         => 3,
			'step'        => 0.05,
			'default'     => 1,
		] );

		$this->end_controls_section();

		/* === STYLE: CARD === */
		$this->start_controls_section( 'style_card', [
			'label' => __( 'Card', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'card_bg', [
			'label'     => __( 'Background Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#e8e8e2',
			'selectors' => [
				'{{WRAPPER}} .radial-gsap-slider' => '--rcs-card-bg: {{VALUE}};',
			],
		] );

		$this->add_control( 'card_color', [
			'label'     => __( 'Text Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#353d35',
			'selectors' => [
				'{{WRAPPER}} .radial-gsap-slider' => '--rcs-card-color: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'card_radius', [
			'label'      => __( 'Card Radius', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 4, 'step' => 0.025 ],
				'px' => [ 'min' => 0, 'max' => 60 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 1.375 ],
			'selectors'  => [
				'{{WRAPPER}} .radial-gsap-slider' => '--rcs-card-radius: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'card_pad', [
			'label'      => __( 'Card Padding', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 3, 'step' => 0.025 ],
				'px' => [ 'min' => 0, 'max' => 40 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 0.625 ],
			'selectors'  => [
				'{{WRAPPER}} .radial-gsap-slider' => '--rcs-card-pad: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		/* === STYLE: IMAGE === */
		$this->start_controls_section( 'style_image', [
			'label' => __( 'Card Image', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'image_aspect', [
			'label'       => __( 'Aspect Ratio', 'elementor-gsap' ),
			'description' => __( 'Format <code>width / height</code>. Contoh: <code>8 / 9</code>, <code>1 / 1</code>, <code>4 / 5</code>.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '8 / 9',
			'selectors'   => [
				'{{WRAPPER}} .radial-gsap-slider' => '--rcs-image-aspect: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'image_radius', [
			'label'      => __( 'Image Radius', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 4, 'step' => 0.025 ],
				'px' => [ 'min' => 0, 'max' => 60 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 0.75 ],
			'selectors'  => [
				'{{WRAPPER}} .radial-gsap-slider' => '--rcs-image-radius: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		/* === STYLE: CARD HEADING === */
		$this->start_controls_section( 'style_heading', [
			'label' => __( 'Card Heading', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'info_height', [
			'label'       => __( 'Info Area Height', 'elementor-gsap' ),
			'description' => __( 'Tinggi area di bawah image tempat heading berada.', 'elementor-gsap' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'em', 'px' ],
			'range'       => [
				'em' => [ 'min' => 2, 'max' => 10, 'step' => 0.1 ],
				'px' => [ 'min' => 30, 'max' => 150 ],
			],
			'default'     => [ 'unit' => 'em', 'size' => 4.5 ],
			'selectors'   => [
				'{{WRAPPER}} .radial-gsap-slider' => '--rcs-card-info-height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'heading_typography',
			'selector' => '{{WRAPPER}} .demo-card__h',
		] );

		$this->end_controls_section();

		/* === STYLE: PREV BUTTON === */
		$this->start_controls_section( 'style_prev', [
			'label'     => __( 'Prev Button', 'elementor-gsap' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'show_arrows' => 'yes' ],
		] );

		$this->add_control( 'prev_bg', [
			'label'     => __( 'Background', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#1f251f',
			'selectors' => [
				'{{WRAPPER}} .radial-gsap-slider' => '--rcs-prev-bg: {{VALUE}};',
			],
		] );

		$this->add_control( 'prev_color', [
			'label'     => __( 'Text Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#e8e8e2',
			'selectors' => [
				'{{WRAPPER}} .radial-gsap-slider' => '--rcs-prev-color: {{VALUE}};',
			],
		] );

		$this->end_controls_section();

		/* === STYLE: NEXT BUTTON === */
		$this->start_controls_section( 'style_next', [
			'label'     => __( 'Next Button', 'elementor-gsap' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'show_arrows' => 'yes' ],
		] );

		$this->add_control( 'next_bg', [
			'label'     => __( 'Background', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#e8e8e2',
			'selectors' => [
				'{{WRAPPER}} .radial-gsap-slider' => '--rcs-next-bg: {{VALUE}};',
			],
		] );

		$this->add_control( 'next_color', [
			'label'     => __( 'Text Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#353d35',
			'selectors' => [
				'{{WRAPPER}} .radial-gsap-slider' => '--rcs-next-color: {{VALUE}};',
			],
		] );

		$this->end_controls_section();

		/* === STYLE: BUTTON SHARED === */
		$this->start_controls_section( 'style_buttons_shared', [
			'label'     => __( 'Buttons Shared', 'elementor-gsap' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'show_arrows' => 'yes' ],
		] );

		$this->add_responsive_control( 'btn_height', [
			'label'      => __( 'Height', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 1.5, 'max' => 5, 'step' => 0.05 ],
				'px' => [ 'min' => 24, 'max' => 80 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 3 ],
			'selectors'  => [
				'{{WRAPPER}} .radial-gsap-slider' => '--rcs-btn-height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'btn_pad_x', [
			'label'      => __( 'Horizontal Padding', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0.5, 'max' => 4, 'step' => 0.05 ],
				'px' => [ 'min' => 8, 'max' => 60 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 1.5 ],
			'selectors'  => [
				'{{WRAPPER}} .radial-gsap-slider' => '--rcs-btn-pad-x: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'btn_radius', [
			'label'      => __( 'Border Radius', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px', '%' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 50, 'step' => 0.05 ],
				'px' => [ 'min' => 0, 'max' => 100 ],
				'%'  => [ 'min' => 0, 'max' => 50 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 50 ],
			'selectors'  => [
				'{{WRAPPER}} .radial-gsap-slider' => '--rcs-btn-radius: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		/* === STYLE: DOTS === */
		$this->start_controls_section( 'style_dots', [
			'label'     => __( 'Dots', 'elementor-gsap' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'show_dots' => 'yes' ],
		] );

		$this->add_control( 'dot_inactive', [
			'label'     => __( 'Inactive Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#4c554c',
			'selectors' => [
				'{{WRAPPER}} .radial-gsap-slider' => '--rcs-dot-color-inactive: {{VALUE}};',
			],
		] );

		$this->add_control( 'dot_active', [
			'label'     => __( 'Active Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ABA994',
			'selectors' => [
				'{{WRAPPER}} .radial-gsap-slider' => '--rcs-dot-color-active: {{VALUE}};',
			],
		] );

		$this->add_control( 'dot_border_color', [
			'label'     => __( 'Border Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#353d35',
			'selectors' => [
				'{{WRAPPER}} .radial-gsap-slider' => '--rcs-dot-border-color: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'dot_border_width', [
			'label'      => __( 'Border Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 0.5, 'step' => 0.005 ],
				'px' => [ 'min' => 0, 'max' => 8 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 0.1875 ],
			'selectors'  => [
				'{{WRAPPER}} .radial-gsap-slider' => '--rcs-dot-border-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'dot_size', [
			'label'      => __( 'Dot Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0.3, 'max' => 2, 'step' => 0.025 ],
				'px' => [ 'min' => 4, 'max' => 32 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 0.875 ],
			'selectors'  => [
				'{{WRAPPER}} .radial-gsap-slider' => '--rcs-dot-size: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'dot_gap', [
			'label'      => __( 'Gap Between Dots', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 2, 'step' => 0.05 ],
				'px' => [ 'min' => 0, 'max' => 32 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 0.25 ],
			'selectors'  => [
				'{{WRAPPER}} .radial-gsap-slider' => '--rcs-dot-gap: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		/* === STYLE: CONTROLS CONTAINER === */
		$this->start_controls_section( 'style_controls_container', [
			'label' => __( 'Controls Container', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'controls_pad', [
			'label'      => __( 'Padding', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 8, 'step' => 0.1 ],
				'px' => [ 'min' => 0, 'max' => 120 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 3 ],
			'selectors'  => [
				'{{WRAPPER}} .radial-gsap-slider' => '--rcs-controls-pad: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'controls_gap', [
			'label'      => __( 'Gap', 'elementor-gsap' ),
			'description' => __( 'Jarak antar Prev / Dots / Next.', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 4, 'step' => 0.05 ],
				'px' => [ 'min' => 0, 'max' => 60 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 1 ],
			'selectors'  => [
				'{{WRAPPER}} .radial-gsap-slider' => '--rcs-controls-gap: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function is_edit_mode() {
		return class_exists( '\Elementor\Plugin' )
			&& \Elementor\Plugin::$instance->editor
			&& \Elementor\Plugin::$instance->editor->is_edit_mode();
	}

	protected function render() {
		$s      = $this->get_settings_for_display();
		$slides = ! empty( $s['slides'] ) ? $s['slides'] : [];

		if ( empty( $slides ) ) {
			return;
		}

		$show_arrows    = ! empty( $s['show_arrows'] ) && 'yes' === $s['show_arrows'];
		$show_dots      = ! empty( $s['show_dots'] )   && 'yes' === $s['show_dots'];
		$prev_label     = ! empty( $s['prev_label'] ) ? $s['prev_label'] : 'Prev';
		$next_label     = ! empty( $s['next_label'] ) ? $s['next_label'] : 'Next';
		$slide_duration = isset( $s['slide_duration'] ) && '' !== $s['slide_duration'] ? floatval( $s['slide_duration'] ) : 1;

		$root_classes = 'radial-gsap-slider' . ( $this->is_edit_mode() ? ' egsap-edit-mode' : '' );
		?>
		<div
			data-radial-slider-init
			data-radial-slider-drag-status="grab"
			data-radial-slide-duration="<?php echo esc_attr( $slide_duration ); ?>"
			class="<?php echo esc_attr( $root_classes ); ?>"
		>
			<div data-radial-slider-collection class="radial-gsap-slider__collection">
				<div data-radial-slider-list class="radial-gsap-slider__list">
					<?php foreach ( $slides as $i => $slide ) :
						$img     = ! empty( $slide['image']['url'] ) ? $slide['image']['url'] : '';
						$alt     = isset( $slide['alt'] ) ? $slide['alt'] : '';
						$heading = isset( $slide['heading'] ) ? $slide['heading'] : '';
						$status  = 0 === $i ? 'active' : 'inview';
						?>
						<div data-radial-slider-item data-radial-slider-item-status="<?php echo esc_attr( $status ); ?>" class="radial-gsap-slider__item">
							<div class="demo-card">
								<?php if ( '' !== $img ) : ?>
									<div class="demo-card__media">
										<img src="<?php echo esc_url( $img ); ?>" loading="lazy" alt="<?php echo esc_attr( $alt ); ?>" class="cover-image">
									</div>
								<?php endif; ?>
								<?php if ( '' !== $heading ) : ?>
									<div class="demo-card__info">
										<h3 class="demo-card__h"><?php echo esc_html( $heading ); ?></h3>
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<?php if ( $show_arrows || $show_dots ) : ?>
				<div class="radial-gsap-slider__controls">
					<?php if ( $show_arrows ) : ?>
						<button data-radial-slider-control="prev" class="radial-gsap-slider__control-btn"><?php echo esc_html( $prev_label ); ?></button>
					<?php endif; ?>

					<?php if ( $show_dots ) : ?>
						<div data-radial-slider-generate-dots class="radial-gsap-slider__dots">
							<button data-radial-slider-control="1" data-radial-slider-control-status="active" class="radial-gsap-slider__control-dot"></button>
						</div>
					<?php endif; ?>

					<?php if ( $show_arrows ) : ?>
						<button data-radial-slider-control="next" class="radial-gsap-slider__control-btn is--next"><?php echo esc_html( $next_label ); ?></button>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
}
