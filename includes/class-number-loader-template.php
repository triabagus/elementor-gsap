<?php
namespace Elementor_GSAP;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Number_Loader_Template {

	const PREFIX = 'egsap_nl_';

	public static function key( $name ) {
		return self::PREFIX . $name;
	}

	public static function register_controls( Controls_Stack $element ) {
		$element->start_controls_section( self::key( 'section' ), [
			'label' => __( 'Loaders • Number 3 Steps', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$element->add_control( self::key( 'enable' ), [
			'label'        => __( 'Enable Loader', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'On', 'elementor-gsap' ),
			'label_off'    => __( 'Off', 'elementor-gsap' ),
			'return_value' => 'yes',
			'default'      => '',
		] );

		$cond = [ self::key( 'enable' ) => 'yes' ];

		/* === COLORS === */
		$element->add_control( self::key( 'colors_heading' ), [
			'label'     => __( 'Colors', 'elementor-gsap' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'bg_color' ), [
			'label'     => __( 'Background Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#E2E1DF',
			'selectors' => [
				'{{WRAPPER}} .egsap-nl-container' => '--nl-bg: {{VALUE}};',
			],
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'text_color' ), [
			'label'     => __( 'Text / Number Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#000000',
			'selectors' => [
				'{{WRAPPER}} .egsap-nl-container' => '--nl-text-color: {{VALUE}};',
			],
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'progress_color' ), [
			'label'     => __( 'Progress Bar Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ff4c24',
			'selectors' => [
				'{{WRAPPER}} .egsap-nl-container' => '--nl-progress-color: {{VALUE}};',
			],
			'condition' => $cond,
		] );

		/* === SIZING === */
		$element->add_control( self::key( 'sizing_heading' ), [
			'label'     => __( 'Sizing', 'elementor-gsap' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'progress_width' ), [
			'label'      => __( 'Progress Bar Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0.2, 'max' => 3, 'step' => 0.05 ],
				'px' => [ 'min' => 4, 'max' => 40 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 1 ],
			'selectors'  => [
				'{{WRAPPER}} .egsap-nl-container' => '--nl-progress-width: {{SIZE}}{{UNIT}};',
			],
			'condition'  => $cond,
		] );

		$element->add_control( self::key( 'number_size' ), [
			'label'       => __( 'Number Font Size', 'elementor-gsap' ),
			'description' => __( 'Boleh pakai <code>calc()</code>, <code>vw</code>, <code>vh</code>, <code>em</code>, atau <code>px</code>. Default: <code>calc(10vw + 10vh)</code> — auto scale mengikuti viewport.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => 'calc(10vw + 10vh)',
			'selectors'   => [
				'{{WRAPPER}} .egsap-nl-container' => '--nl-number-size: {{VALUE}};',
			],
			'condition'   => $cond,
		] );

		$element->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => self::key( 'number_typography' ),
			'label'     => __( 'Number Typography', 'elementor-gsap' ),
			'description' => __( 'Ganti font-family di sini. Font-size sudah diatur di atas (biar responsive).', 'elementor-gsap' ),
			'selector'  => '{{WRAPPER}} .egsap-nl-number, {{WRAPPER}} .egsap-nl-percentage',
			'condition' => $cond,
		] );

		/* === TIMING === */
		$element->add_control( self::key( 'timing_heading' ), [
			'label'     => __( 'Timing', 'elementor-gsap' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'duration' ), [
			'label'       => __( 'Step Duration (s)', 'elementor-gsap' ),
			'description' => __( 'Durasi tiap step animasi (3 step total). Default 1.2s.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0.3,
			'max'         => 5,
			'step'        => 0.1,
			'default'     => 1.2,
			'condition'   => $cond,
		] );

		$element->add_control( self::key( 'fade_out' ), [
			'label'       => __( 'Fade Out Duration (s)', 'elementor-gsap' ),
			'description' => __( 'Durasi loader fade-out setelah reach 100%. Set 0 kalau ingin loader disappear instant.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0,
			'max'         => 3,
			'step'        => 0.05,
			'default'     => 0.6,
			'condition'   => $cond,
		] );

		$element->end_controls_section();
	}

	public static function render( array $s, $element_id = '' ) {
		$duration = isset( $s[ self::key( 'duration' ) ] ) && '' !== $s[ self::key( 'duration' ) ] ? floatval( $s[ self::key( 'duration' ) ] ) : 1.2;
		$fade_out = isset( $s[ self::key( 'fade_out' ) ] ) && '' !== $s[ self::key( 'fade_out' ) ] ? floatval( $s[ self::key( 'fade_out' ) ] ) : 0.6;

		$id_attr = $element_id ? ' data-egsap-id="' . esc_attr( $element_id ) . '"' : '';
		?>
		<div
			class="egsap-nl-container"
			data-egsap-nl
			<?php echo $id_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			data-egsap-nl-duration="<?php echo esc_attr( $duration ); ?>"
			data-egsap-nl-fade-out="<?php echo esc_attr( $fade_out ); ?>"
		>
			<div class="egsap-nl-screen">
				<div class="egsap-nl-progress">
					<div class="egsap-nl-progress-inner"></div>
				</div>
				<div class="egsap-nl-numbers">
					<div class="egsap-nl-number-group is--first">
						<div class="egsap-nl-number-wrap"><span class="egsap-nl-number">1</span></div>
					</div>
					<div class="egsap-nl-number-group is--second">
						<div class="egsap-nl-number-wrap">
							<?php for ( $i = 1; $i <= 10; $i++ ) : ?>
								<span class="egsap-nl-number"><?php echo $i % 10; ?></span>
							<?php endfor; ?>
						</div>
					</div>
					<div class="egsap-nl-number-group is--third">
						<div class="egsap-nl-number-wrap">
							<?php for ( $i = 1; $i <= 10; $i++ ) : ?>
								<span class="egsap-nl-number"><?php echo $i % 10; ?></span>
							<?php endfor; ?>
						</div>
					</div>
					<div class="egsap-nl-percentage-wrap">
						<span class="egsap-nl-percentage">%</span>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
