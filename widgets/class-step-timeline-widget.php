<?php
namespace Elementor_GSAP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Step_Timeline_Widget extends Widget_Base {

	public function get_name() {
		return 'step_timeline';
	}

	public function get_title() {
		return __( 'Step-by-step Timeline', 'elementor-gsap' );
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_categories() {
		return [ 'elementor-gsap-scroll' ];
	}

	public function get_keywords() {
		return [ 'timeline', 'steps', 'process', 'scroll', 'scrolltrigger', 'gsap', 'osmo' ];
	}

	public function get_script_depends() {
		return [ 'gsap', 'gsap-scrolltrigger', 'elementor-step-timeline' ];
	}

	public function get_style_depends() {
		return [ 'elementor-step-timeline' ];
	}

	private function default_steps() {
		return [
			[ 'marker' => '1', 'heading' => 'Discovery', 'paragraph' => "We start by getting to know your brand, your goals, and the people you're trying to reach. One focused kickoff sets the direction for everything that follows." ],
			[ 'marker' => '2', 'heading' => 'Strategy',  'paragraph' => "With the groundwork laid, we shape the plan — the story, the structure, and the decisions that turn a loose idea into something we can actually build." ],
			[ 'marker' => '3', 'heading' => 'Design',    'paragraph' => "This is where it takes shape. We craft the visual language, the layouts, and the small details that make the work feel unmistakably yours." ],
			[ 'marker' => '4', 'heading' => 'Build',     'paragraph' => "Designs come to life in the browser — animated, responsive, and tested across devices so nothing breaks between the mockup and the real thing." ],
			[ 'marker' => '5', 'heading' => 'Launch',    'paragraph' => "We ship. Final checks, a clean handover, and your project goes live to the world exactly as it was meant to." ],
			[ 'marker' => '6', 'heading' => 'Support',   'paragraph' => "After launch we stick around — tuning, iterating, and helping you get the most out of what we made together." ],
		];
	}

	protected function register_controls() {

		/* === CONTENT: HEADER === */
		$this->start_controls_section( 'content_header', [
			'label' => __( 'Header', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'show_header', [
			'label'        => __( 'Show Header', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'eyebrow', [
			'label'     => __( 'Eyebrow', 'elementor-gsap' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => 'our process',
			'dynamic'   => [ 'active' => true ],
			'condition' => [ 'show_header' => 'yes' ],
		] );

		$this->add_control( 'heading', [
			'label'     => __( 'Heading', 'elementor-gsap' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => 'How it works',
			'dynamic'   => [ 'active' => true ],
			'condition' => [ 'show_header' => 'yes' ],
		] );

		$this->end_controls_section();

		/* === CONTENT: STEPS === */
		$this->start_controls_section( 'content_steps', [
			'label' => __( 'Steps', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$rep = new Repeater();
		$rep->add_control( 'marker', [
			'label'       => __( 'Marker Label', 'elementor-gsap' ),
			'description' => __( 'Teks di dalam bulatan marker (mis. 1, 2, ✓). Kosongkan untuk auto-number.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '',
			'dynamic'     => [ 'active' => true ],
		] );
		$rep->add_control( 'heading', [
			'label'   => __( 'Heading', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Step Title',
			'dynamic' => [ 'active' => true ],
		] );
		$rep->add_control( 'paragraph', [
			'label'   => __( 'Description', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => 4,
			'default' => 'Short description of this step.',
			'dynamic' => [ 'active' => true ],
		] );

		$this->add_control( 'steps', [
			'label'       => __( 'Steps', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ heading }}}',
			'default'     => $this->default_steps(),
		] );

		$this->end_controls_section();

		/* === CONTENT: ANIMATION === */
		$this->start_controls_section( 'content_anim', [
			'label' => __( 'Animation', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'activation', [
			'label'       => __( 'Activation Point (0–1)', 'elementor-gsap' ),
			'description' => __( 'Posisi di viewport dimana step ter-activate. 0 = paling atas, 0.5 = tengah (default), 1 = paling bawah.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0,
			'max'         => 1,
			'step'        => 0.05,
			'default'     => 0.5,
		] );

		$this->end_controls_section();

		/* === STYLE: CONTAINER === */
		$this->start_controls_section( 'style_container', [
			'label' => __( 'Container', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'max_width', [
			'label'      => __( 'Max Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 20, 'max' => 100, 'step' => 0.5 ],
				'px' => [ 'min' => 320, 'max' => 1600 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 50 ],
			'selectors'  => [
				'{{WRAPPER}} .step-timeline' => '--stl-max-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		/* === STYLE: HEADER === */
		$this->start_controls_section( 'style_header', [
			'label'     => __( 'Header', 'elementor-gsap' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'show_header' => 'yes' ],
		] );

		$this->add_control( 'eyebrow_color', [
			'label'     => __( 'Eyebrow Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#6b7c51',
			'selectors' => [
				'{{WRAPPER}} .step-timeline' => '--stl-eyebrow-color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'eyebrow_typography',
			'label'    => __( 'Eyebrow Typography', 'elementor-gsap' ),
			'selector' => '{{WRAPPER}} .step-timeline__eyebrow',
		] );

		$this->add_control( 'heading_color', [
			'label'     => __( 'Heading Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .step-timeline' => '--stl-heading-color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'heading_typography',
			'label'    => __( 'Heading Typography', 'elementor-gsap' ),
			'selector' => '{{WRAPPER}} .step-timeline__h2',
		] );

		$this->add_responsive_control( 'header_mb', [
			'label'      => __( 'Header Bottom Margin', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 12, 'step' => 0.25 ],
				'px' => [ 'min' => 0, 'max' => 200 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 6 ],
			'selectors'  => [
				'{{WRAPPER}} .step-timeline' => '--stl-header-mb: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'header_gap', [
			'label'      => __( 'Header Gap (Eyebrow ↔ Heading)', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 3, 'step' => 0.05 ],
				'px' => [ 'min' => 0, 'max' => 40 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 1 ],
			'selectors'  => [
				'{{WRAPPER}} .step-timeline' => '--stl-header-gap: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		/* === STYLE: LINE === */
		$this->start_controls_section( 'style_line', [
			'label' => __( 'Timeline Line', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'line_color', [
			'label'     => __( 'Track Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(34, 32, 28, 0.14)',
			'selectors' => [
				'{{WRAPPER}} .step-timeline' => '--stl-line-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'fill_color', [
			'label'     => __( 'Fill Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#7d8a69',
			'selectors' => [
				'{{WRAPPER}} .step-timeline' => '--stl-fill-color: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'line_width', [
			'label'      => __( 'Line Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em' ],
			'range'      => [
				'px' => [ 'min' => 1, 'max' => 12 ],
				'em' => [ 'min' => 0.05, 'max' => 1, 'step' => 0.01 ],
			],
			'default'    => [ 'unit' => 'px', 'size' => 2 ],
			'selectors'  => [
				'{{WRAPPER}} .step-timeline' => '--stl-line-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		/* === STYLE: MARKER === */
		$this->start_controls_section( 'style_marker', [
			'label' => __( 'Marker', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'marker_size', [
			'label'      => __( 'Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 1.5, 'max' => 6, 'step' => 0.05 ],
				'px' => [ 'min' => 20, 'max' => 120 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 3 ],
			'selectors'  => [
				'{{WRAPPER}} .step-timeline' => '--stl-marker-size: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'marker_radius', [
			'label'      => __( 'Border Radius', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px', '%' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 100, 'step' => 0.25 ],
				'px' => [ 'min' => 0, 'max' => 100 ],
				'%'  => [ 'min' => 0, 'max' => 50 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 100 ],
			'selectors'  => [
				'{{WRAPPER}} .step-timeline' => '--stl-marker-radius: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'marker_border', [
			'label'     => __( 'Border Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(0, 0, 0, 0.2)',
			'selectors' => [
				'{{WRAPPER}} .step-timeline' => '--stl-marker-border: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'marker_border_w', [
			'label'      => __( 'Border Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 0, 'max' => 6 ] ],
			'default'    => [ 'unit' => 'px', 'size' => 1 ],
			'selectors'  => [
				'{{WRAPPER}} .step-timeline' => '--stl-marker-border-w: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->start_controls_tabs( 'marker_state_tabs' );

		/* Idle (belum di-scroll melewatinya) */
		$this->start_controls_tab( 'marker_state_idle', [
			'label' => __( 'Idle', 'elementor-gsap' ),
		] );

		$this->add_control( 'marker_bg', [
			'label'     => __( 'Background', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ebe8dd',
			'selectors' => [
				'{{WRAPPER}} .step-timeline' => '--stl-marker-bg: {{VALUE}};',
			],
		] );

		$this->add_control( 'marker_color', [
			'label'     => __( 'Text Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#22201c',
			'selectors' => [
				'{{WRAPPER}} .step-timeline' => '--stl-marker-color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		/* Active (sudah dilewati fill line) */
		$this->start_controls_tab( 'marker_state_active', [
			'label' => __( 'Active', 'elementor-gsap' ),
		] );

		$this->add_control( 'marker_active_bg', [
			'label'     => __( 'Background', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#7d8a69',
			'selectors' => [
				'{{WRAPPER}} .step-timeline' => '--stl-marker-active-bg: {{VALUE}};',
			],
		] );

		$this->add_control( 'marker_active_color', [
			'label'     => __( 'Text Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#f2efe6',
			'selectors' => [
				'{{WRAPPER}} .step-timeline' => '--stl-marker-active-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'marker_active_border', [
			'label'     => __( 'Border Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#7d8a69',
			'selectors' => [
				'{{WRAPPER}} .step-timeline' => '--stl-marker-active-border: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		/* Current (marker yang sedang di viewport activation) */
		$this->start_controls_tab( 'marker_state_current', [
			'label' => __( 'Current', 'elementor-gsap' ),
		] );

		$this->add_control( 'current_ring_color', [
			'label'       => __( 'Ring / Glow Color', 'elementor-gsap' ),
			'description' => __( 'Warna box-shadow (ring) di marker current.', 'elementor-gsap' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => 'rgba(125, 138, 105, 0.16)',
			'selectors'   => [
				'{{WRAPPER}} .step-timeline' => '--stl-current-ring-color: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'current_ring_size', [
			'label'      => __( 'Ring Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 2, 'step' => 0.025 ],
				'px' => [ 'min' => 0, 'max' => 30 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 0.5 ],
			'selectors'  => [
				'{{WRAPPER}} .step-timeline' => '--stl-current-ring-size: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'marker_typography',
			'label'    => __( 'Marker Typography', 'elementor-gsap' ),
			'selector' => '{{WRAPPER}} .step-timeline__marker',
		] );

		$this->end_controls_section();

		/* === STYLE: CONTENT === */
		$this->start_controls_section( 'style_content', [
			'label' => __( 'Content', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'content_max', [
			'label'      => __( 'Max Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 10, 'max' => 60, 'step' => 0.5 ],
				'px' => [ 'min' => 150, 'max' => 900 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 28 ],
			'selectors'  => [
				'{{WRAPPER}} .step-timeline' => '--stl-content-max: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'content_gap', [
			'label'      => __( 'Content Gap (Heading ↔ P)', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 3, 'step' => 0.05 ],
				'px' => [ 'min' => 0, 'max' => 40 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 1 ],
			'selectors'  => [
				'{{WRAPPER}} .step-timeline' => '--stl-content-gap: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'content_h_color', [
			'label'     => __( 'Heading Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .step-timeline' => '--stl-content-h-color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'content_h_typography',
			'label'    => __( 'Heading Typography', 'elementor-gsap' ),
			'selector' => '{{WRAPPER}} .step-timeline__content-h',
		] );

		$this->add_control( 'content_p_color', [
			'label'     => __( 'Description Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .step-timeline' => '--stl-content-p-color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'content_p_typography',
			'label'    => __( 'Description Typography', 'elementor-gsap' ),
			'selector' => '{{WRAPPER}} .step-timeline__p',
		] );

		$this->end_controls_section();

		/* === STYLE: STATES / OPACITY === */
		$this->start_controls_section( 'style_states', [
			'label' => __( 'Content Opacity (per state)', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'opacity_inactive', [
			'label'   => __( 'Inactive Opacity', 'elementor-gsap' ),
			'type'    => Controls_Manager::SLIDER,
			'range'   => [ 'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.05 ] ],
			'default' => [ 'unit' => 'px', 'size' => 0.25 ],
			'selectors' => [
				'{{WRAPPER}} .step-timeline' => '--stl-opacity-inactive: {{SIZE}};',
			],
		] );

		$this->add_control( 'opacity_active', [
			'label'   => __( 'Active Opacity', 'elementor-gsap' ),
			'type'    => Controls_Manager::SLIDER,
			'range'   => [ 'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.05 ] ],
			'default' => [ 'unit' => 'px', 'size' => 0.5 ],
			'selectors' => [
				'{{WRAPPER}} .step-timeline' => '--stl-opacity-active: {{SIZE}};',
			],
		] );

		$this->add_control( 'opacity_current', [
			'label'   => __( 'Current Opacity', 'elementor-gsap' ),
			'type'    => Controls_Manager::SLIDER,
			'range'   => [ 'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.05 ] ],
			'default' => [ 'unit' => 'px', 'size' => 1 ],
			'selectors' => [
				'{{WRAPPER}} .step-timeline' => '--stl-opacity-current: {{SIZE}};',
			],
		] );

		$this->end_controls_section();

		/* === STYLE: LAYOUT / SPACING === */
		$this->start_controls_section( 'style_spacing', [
			'label' => __( 'Spacing', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'item_gap_y', [
			'label'      => __( 'Vertical Gap Between Steps', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 1, 'max' => 20, 'step' => 0.25 ],
				'px' => [ 'min' => 16, 'max' => 300 ],
			],
			'default'        => [ 'unit' => 'em', 'size' => 8 ],
			'mobile_default' => [ 'unit' => 'em', 'size' => 6 ],
			'selectors'  => [
				'{{WRAPPER}} .step-timeline' => '--stl-item-gap-y: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'item_gap_x', [
			'label'      => __( 'Horizontal Gap (Marker ↔ Content)', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0.5, 'max' => 8, 'step' => 0.1 ],
				'px' => [ 'min' => 8, 'max' => 120 ],
			],
			'default'        => [ 'unit' => 'em', 'size' => 3 ],
			'mobile_default' => [ 'unit' => 'em', 'size' => 1.5 ],
			'selectors'  => [
				'{{WRAPPER}} .step-timeline' => '--stl-item-gap-x: {{SIZE}}{{UNIT}};',
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
		$s     = $this->get_settings_for_display();
		$steps = ! empty( $s['steps'] ) ? $s['steps'] : [];
		if ( empty( $steps ) ) {
			return;
		}

		$show_header = ! empty( $s['show_header'] ) && 'yes' === $s['show_header'];
		$eyebrow     = isset( $s['eyebrow'] ) ? $s['eyebrow'] : '';
		$heading     = isset( $s['heading'] ) ? $s['heading'] : '';
		$activation  = isset( $s['activation'] ) && '' !== $s['activation'] ? floatval( $s['activation'] ) : 0.5;
		if ( $activation < 0 ) $activation = 0;
		if ( $activation > 1 ) $activation = 1;

		$root_classes = 'step-timeline' . ( $this->is_edit_mode() ? ' egsap-edit-mode' : '' );
		?>
		<div
			data-step-timeline-init
			data-step-timeline-activation="<?php echo esc_attr( $activation ); ?>"
			class="<?php echo esc_attr( $root_classes ); ?>"
		>
			<?php if ( $show_header && ( '' !== $eyebrow || '' !== $heading ) ) : ?>
				<div class="step-timeline__header">
					<?php if ( '' !== $eyebrow ) : ?>
						<span class="step-timeline__eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
					<?php endif; ?>
					<?php if ( '' !== $heading ) : ?>
						<h2 class="step-timeline__h2"><?php echo esc_html( $heading ); ?></h2>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="step-timeline__wrapper">
				<div data-step-timeline-line class="step-timeline__line">
					<div data-step-timeline-fill class="step-timeline__fill"></div>
				</div>
				<div class="step-timeline__collection">
					<div class="step-timeline__list">
						<?php foreach ( $steps as $i => $step ) :
							$marker    = isset( $step['marker'] ) && '' !== $step['marker'] ? $step['marker'] : (string) ( $i + 1 );
							$s_head    = isset( $step['heading'] ) ? $step['heading'] : '';
							$s_para    = isset( $step['paragraph'] ) ? $step['paragraph'] : '';
							?>
							<div data-step-timeline-item class="step-timeline__item">
								<div data-step-timeline-marker class="step-timeline__marker">
									<span><?php echo esc_html( $marker ); ?></span>
								</div>
								<div class="step-timeline__content">
									<?php if ( '' !== $s_head ) : ?>
										<h3 class="step-timeline__content-h"><?php echo esc_html( $s_head ); ?></h3>
									<?php endif; ?>
									<?php if ( '' !== $s_para ) : ?>
										<p class="step-timeline__p"><?php echo esc_html( $s_para ); ?></p>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
