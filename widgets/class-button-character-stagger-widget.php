<?php
namespace Elementor_GSAP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Button_Character_Stagger_Widget extends Widget_Base {

	public function get_name() {
		return 'button_character_stagger';
	}

	public function get_title() {
		return __( 'Button Character Stagger', 'elementor-gsap' );
	}

	public function get_icon() {
		return 'eicon-button';
	}

	public function get_categories() {
		return [ 'elementor-gsap' ];
	}

	public function get_keywords() {
		return [ 'button', 'stagger', 'character', 'hover', 'animation', 'osmo' ];
	}

	public function get_script_depends() {
		return [ 'elementor-button-character-stagger' ];
	}

	public function get_style_depends() {
		return [ 'elementor-button-character-stagger' ];
	}

	protected function register_controls() {
		/* === CONTENT === */
		$this->start_controls_section( 'content_section', [
			'label' => __( 'Content', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'text', [
			'label'   => __( 'Text', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Staggering Button',
			'dynamic' => [ 'active' => true ],
		] );

		$this->add_control( 'link', [
			'label'   => __( 'Link', 'elementor-gsap' ),
			'type'    => Controls_Manager::URL,
			'default' => [ 'url' => '#' ],
		] );

		$this->add_control( 'aria_label', [
			'label'       => __( 'ARIA Label', 'elementor-gsap' ),
			'description' => __( 'Aksesibilitas — biarkan kosong untuk pakai teks tombol.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '',
		] );

		$this->end_controls_section();

		/* === ANIMATION === */
		$this->start_controls_section( 'animation_section', [
			'label' => __( 'Animation', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'stagger_offset', [
			'label'       => __( 'Stagger Offset (s)', 'elementor-gsap' ),
			'description' => __( 'Jeda transisi antar karakter. Default 0.01.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0,
			'max'         => 0.5,
			'step'        => 0.005,
			'default'     => 0.01,
		] );

		$this->add_control( 'duration', [
			'label'   => __( 'Duration (s)', 'elementor-gsap' ),
			'type'    => Controls_Manager::SLIDER,
			'size_units' => [ 's' ],
			'range'   => [
				's' => [ 'min' => 0.1, 'max' => 3, 'step' => 0.05 ],
			],
			'default' => [ 'unit' => 's', 'size' => 0.6 ],
			'selectors' => [
				'{{WRAPPER}} .btn-animate-chars' => '--bcs-duration: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'easing', [
			'label'   => __( 'Easing', 'elementor-gsap' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'cubic-bezier(0.625, 0.05, 0, 1)' => 'Osmo (default)',
				'cubic-bezier(0.65, 0, 0.35, 1)'  => 'easeInOutCubic',
				'cubic-bezier(0.83, 0, 0.17, 1)'  => 'easeInOutQuint',
				'cubic-bezier(0.87, 0, 0.13, 1)'  => 'easeInOutExpo',
				'cubic-bezier(0.16, 1, 0.3, 1)'   => 'easeOutExpo',
				'ease'                            => 'ease',
				'ease-in-out'                     => 'ease-in-out',
				'linear'                          => 'linear',
			],
			'default' => 'cubic-bezier(0.625, 0.05, 0, 1)',
			'selectors' => [
				'{{WRAPPER}} .btn-animate-chars' => '--bcs-easing: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'travel', [
			'label'       => __( 'Character Travel', 'elementor-gsap' ),
			'description' => __( 'Jarak vertikal karakter saat hover (juga jadi line-height teks).', 'elementor-gsap' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'em', 'px' ],
			'range'       => [
				'em' => [ 'min' => 1, 'max' => 3, 'step' => 0.05 ],
				'px' => [ 'min' => 10, 'max' => 80 ],
			],
			'default'     => [ 'unit' => 'em', 'size' => 1.3 ],
			'selectors'   => [
				'{{WRAPPER}} .btn-animate-chars' => '--bcs-travel: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'hover_inset', [
			'label'       => __( 'Hover BG Inset', 'elementor-gsap' ),
			'description' => __( 'Seberapa jauh background menyusut ke dalam saat hover.', 'elementor-gsap' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'em', 'px' ],
			'range'       => [
				'em' => [ 'min' => 0, 'max' => 1, 'step' => 0.025 ],
				'px' => [ 'min' => 0, 'max' => 20 ],
			],
			'default'     => [ 'unit' => 'em', 'size' => 0.125 ],
			'selectors'   => [
				'{{WRAPPER}} .btn-animate-chars:hover .btn-animate-chars__bg, {{WRAPPER}} .btn-animate-chars:focus-visible .btn-animate-chars__bg' => 'inset: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		/* === STYLE: BUTTON === */
		$this->start_controls_section( 'style_button_section', [
			'label' => __( 'Button', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'alignment', [
			'label'     => __( 'Alignment', 'elementor-gsap' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'flex-start' => [ 'title' => __( 'Left', 'elementor-gsap' ), 'icon' => 'eicon-text-align-left' ],
				'center'     => [ 'title' => __( 'Center', 'elementor-gsap' ), 'icon' => 'eicon-text-align-center' ],
				'flex-end'   => [ 'title' => __( 'Right', 'elementor-gsap' ), 'icon' => 'eicon-text-align-right' ],
			],
			'default'   => 'flex-start',
			'selectors' => [
				'{{WRAPPER}} .elementor-widget-container' => 'display: flex; justify-content: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'typography',
			'selector' => '{{WRAPPER}} .btn-animate-chars',
		] );

		$this->add_control( 'text_color', [
			'label'     => __( 'Text Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#131313',
			'selectors' => [
				'{{WRAPPER}} .btn-animate-chars' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'bg_color', [
			'label'     => __( 'Background Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#efeeec',
			'selectors' => [
				'{{WRAPPER}} .btn-animate-chars__bg' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'padding', [
			'label'      => __( 'Padding', 'elementor-gsap' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'default'    => [
				'top'      => '1',
				'right'    => '1',
				'bottom'   => '1',
				'left'     => '1',
				'unit'     => 'em',
				'isLinked' => true,
			],
			'selectors'  => [
				'{{WRAPPER}} .btn-animate-chars' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'max_width', [
			'label'      => __( 'Max Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em', '%' ],
			'range'      => [
				'px' => [ 'min' => 60, 'max' => 800 ],
				'em' => [ 'min' => 4, 'max' => 30, 'step' => 0.5 ],
				'%'  => [ 'min' => 10, 'max' => 100 ],
			],
			'selectors'  => [
				'{{WRAPPER}} .btn-animate-chars' => 'max-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'border_radius', [
			'label'      => __( 'Border Radius', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em', '%' ],
			'range'      => [
				'px' => [ 'min' => 0, 'max' => 100 ],
				'em' => [ 'min' => 0, 'max' => 5, 'step' => 0.05 ],
				'%'  => [ 'min' => 0, 'max' => 50 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 0.25 ],
			'selectors'  => [
				'{{WRAPPER}} .btn-animate-chars' => 'border-radius: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'border',
			'selector' => '{{WRAPPER}} .btn-animate-chars',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box_shadow',
			'selector' => '{{WRAPPER}} .btn-animate-chars',
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$s        = $this->get_settings_for_display();
		$text     = ! empty( $s['text'] ) ? $s['text'] : 'Staggering Button';
		$url      = ! empty( $s['link']['url'] ) ? $s['link']['url'] : '#';
		$target   = ! empty( $s['link']['is_external'] ) ? '_blank' : '_self';
		$rel      = ! empty( $s['link']['nofollow'] ) ? 'nofollow' : '';
		$aria     = ! empty( $s['aria_label'] ) ? $s['aria_label'] : $text;
		$offset   = isset( $s['stagger_offset'] ) && '' !== $s['stagger_offset'] ? floatval( $s['stagger_offset'] ) : 0.01;
		if ( $offset < 0 ) { $offset = 0; }
		?>
		<a href="<?php echo esc_url( $url ); ?>"
			target="<?php echo esc_attr( $target ); ?>"<?php echo $rel ? ' rel="' . esc_attr( $rel ) . '"' : ''; ?>
			aria-label="<?php echo esc_attr( $aria ); ?>"
			class="btn-animate-chars">
			<div class="btn-animate-chars__bg" aria-hidden="true"></div>
			<span data-button-animate-chars
				data-stagger-offset="<?php echo esc_attr( $offset ); ?>"
				class="btn-animate-chars__text"><?php echo esc_html( $text ); ?></span>
		</a>
		<?php
	}
}
