<?php
namespace Elementor_GSAP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Button_Draw_Underline_Widget extends Widget_Base {

	public function get_name() {
		return 'button_draw_underline';
	}

	public function get_title() {
		return __( 'Button Draw Underline', 'elementor-gsap' );
	}

	public function get_icon() {
		return 'eicon-button';
	}

	public function get_categories() {
		return [ 'elementor-gsap' ];
	}

	public function get_keywords() {
		return [ 'gsap', 'button', 'draw', 'underline', 'svg', 'hover', 'drawsvg' ];
	}

	public function get_script_depends() {
		return [ 'gsap', 'gsap-drawsvg', 'elementor-button-draw-underline' ];
	}

	public function get_style_depends() {
		return [ 'elementor-button-draw-underline' ];
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
			'default' => 'Hover me',
			'dynamic' => [ 'active' => true ],
		] );

		$this->add_control( 'link', [
			'label'   => __( 'Link', 'elementor-gsap' ),
			'type'    => Controls_Manager::URL,
			'default' => [ 'url' => '#' ],
		] );

		$this->add_control( 'underline_style', [
			'label'       => __( 'Underline Style', 'elementor-gsap' ),
			'description' => __( 'Pilih bentuk garis underline. "Random" akan cycle melalui semua varian setiap hover.', 'elementor-gsap' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => [
				'random' => __( 'Random (cycle all)', 'elementor-gsap' ),
				'1'      => __( 'Style 1 — Wavy Curve', 'elementor-gsap' ),
				'2'      => __( 'Style 2 — Bowed Loop', 'elementor-gsap' ),
				'3'      => __( 'Style 3 — Zigzag', 'elementor-gsap' ),
				'4'      => __( 'Style 4 — Tight Loop', 'elementor-gsap' ),
				'5'      => __( 'Style 5 — Straight Sweep', 'elementor-gsap' ),
				'6'      => __( 'Style 6 — Mountain Peak', 'elementor-gsap' ),
			],
			'default'     => 'random',
		] );

		$this->add_control( 'duration', [
			'label'   => __( 'Animation Duration (s)', 'elementor-gsap' ),
			'type'    => Controls_Manager::NUMBER,
			'min'     => 0.1,
			'max'     => 3,
			'step'    => 0.05,
			'default' => 0.5,
		] );

		$this->add_control( 'easing', [
			'label'   => __( 'Easing', 'elementor-gsap' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'power2.inOut' => 'power2.inOut',
				'power3.inOut' => 'power3.inOut',
				'expo.inOut'   => 'expo.inOut',
				'sine.inOut'   => 'sine.inOut',
				'circ.inOut'   => 'circ.inOut',
				'none'         => 'none (linear)',
			],
			'default' => 'power2.inOut',
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
				'flex-start' => [ 'title' => __( 'Left', 'elementor-gsap' ), 'icon' => 'eicon-text-align-left' ],
				'center'     => [ 'title' => __( 'Center', 'elementor-gsap' ), 'icon' => 'eicon-text-align-center' ],
				'flex-end'   => [ 'title' => __( 'Right', 'elementor-gsap' ), 'icon' => 'eicon-text-align-right' ],
			],
			'default'   => 'flex-start',
			'selectors' => [
				'{{WRAPPER}} .elementor-widget-container' => 'display: flex; justify-content: {{VALUE}};',
			],
		] );

		$this->add_control( 'text_color', [
			'label'     => __( 'Text Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .text-draw'        => 'color: {{VALUE}};',
				'{{WRAPPER}} .text-draw__span'  => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'underline_color', [
			'label'     => __( 'Underline Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#e55050',
			'selectors' => [
				'{{WRAPPER}} .text-draw__box' => 'color: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'underline_thickness', [
			'label'      => __( 'Underline Thickness', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0.1, 'max' => 2, 'step' => 0.05 ],
				'px' => [ 'min' => 1, 'max' => 30 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 0.625 ],
			'selectors'  => [
				'{{WRAPPER}} .text-draw__box' => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'typography',
			'selector' => '{{WRAPPER}} .text-draw__span',
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$s        = $this->get_settings_for_display();
		$text     = ! empty( $s['text'] ) ? $s['text'] : 'Hover me';
		$url      = ! empty( $s['link']['url'] ) ? $s['link']['url'] : '#';
		$target   = ! empty( $s['link']['is_external'] ) ? '_blank' : '_self';
		$rel      = ! empty( $s['link']['nofollow'] ) ? 'nofollow' : '';
		$variant  = ! empty( $s['underline_style'] ) ? $s['underline_style'] : 'random';
		$duration = isset( $s['duration'] ) && '' !== $s['duration'] ? floatval( $s['duration'] ) : 0.5;
		$easing   = ! empty( $s['easing'] ) ? $s['easing'] : 'power2.inOut';
		?>
		<a data-draw-line href="<?php echo esc_url( $url ); ?>" target="<?php echo esc_attr( $target ); ?>"<?php echo $rel ? ' rel="' . esc_attr( $rel ) . '"' : ''; ?>
			data-draw-variant="<?php echo esc_attr( $variant ); ?>"
			data-draw-duration="<?php echo esc_attr( $duration ); ?>"
			data-draw-ease="<?php echo esc_attr( $easing ); ?>"
			class="text-draw">
			<span class="text-draw__span"><?php echo esc_html( $text ); ?></span>
			<div data-draw-line-box class="text-draw__box"></div>
		</a>
		<?php
	}
}
