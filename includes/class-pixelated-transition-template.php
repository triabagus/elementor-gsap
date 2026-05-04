<?php
namespace Elementor_GSAP;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Pixelated_Transition_Template {

	const PREFIX = 'egsap_pixel_';

	public static function key( $name ) {
		return self::PREFIX . $name;
	}

	public static function register_controls( Controls_Stack $element ) {
		$element->start_controls_section( self::key( 'section' ), [
			'label' => __( 'Pixelated Page Transition', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$element->add_control( self::key( 'enable' ), [
			'label'        => __( 'Enable Pixelated Transition', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'On', 'elementor-gsap' ),
			'label_off'    => __( 'Off', 'elementor-gsap' ),
			'return_value' => 'yes',
			'default'      => '',
		] );

		$cond  = [ self::key( 'enable' ) => 'yes' ];
		$scope = '.transition[data-egsap-id="' . esc_attr( $element->get_id() ) . '"]';

		$element->add_control( self::key( 'color_heading' ), [
			'label'     => __( 'Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'bg_color' ), [
			'label'     => __( 'Pixel Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ff4c24',
			'selectors' => [
				$scope                          => 'background-color: {{VALUE}};',
				$scope . ' .transition-block'   => 'background-color: {{VALUE}};',
			],
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'grid_heading' ), [
			'label'     => __( 'Grid Density', 'elementor-gsap' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $cond,
		] );

		$element->add_responsive_control( self::key( 'columns' ), [
			'label'          => __( 'Grid Columns', 'elementor-gsap' ),
			'description'    => __( 'Jumlah kolom = kerapatan pixel. Lebih banyak = lebih halus tapi DOM nodes bertambah.', 'elementor-gsap' ),
			'type'           => Controls_Manager::NUMBER,
			'min'            => 2,
			'max'            => 30,
			'default'        => 8,
			'tablet_default' => 6,
			'mobile_default' => 4,
			'selectors'      => [
				$scope => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
			],
			'condition'      => $cond,
		] );

		$element->add_control( self::key( 'animation_heading' ), [
			'label'     => __( 'Animation Timing', 'elementor-gsap' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'stagger_out' ), [
			'label'       => __( 'Page Load Stagger Amount (s)', 'elementor-gsap' ),
			'description' => __( 'Total durasi penyebaran fade-out saat page load.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0.1,
			'max'         => 5,
			'step'        => 0.05,
			'default'     => 0.75,
			'condition'   => $cond,
		] );

		$element->add_control( self::key( 'stagger_in' ), [
			'label'       => __( 'Click Stagger Amount (s)', 'elementor-gsap' ),
			'description' => __( 'Total durasi penyebaran fade-in saat user klik link internal.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0.1,
			'max'         => 5,
			'step'        => 0.05,
			'default'     => 0.5,
			'condition'   => $cond,
		] );

		$element->add_control( self::key( 'block_duration' ), [
			'label'     => __( 'Per-Block Duration (s)', 'elementor-gsap' ),
			'type'      => Controls_Manager::NUMBER,
			'min'       => 0.001,
			'max'       => 1,
			'step'      => 0.005,
			'default'   => 0.1,
			'condition' => $cond,
		] );

		$element->add_control( self::key( 'transition_note' ), [
			'type'        => Controls_Manager::RAW_HTML,
			'raw'         => __( '<small>Tip: tambahkan attribute <code>data-transition-prevent</code> pada link mana pun yang ingin di-skip dari animasi transisi.</small>', 'elementor-gsap' ),
			'condition'   => $cond,
		] );

		$element->end_controls_section();
	}

	public static function render( array $s, $element_id = '' ) {
		$stagger_out    = isset( $s[ self::key( 'stagger_out' ) ] ) && '' !== $s[ self::key( 'stagger_out' ) ] ? floatval( $s[ self::key( 'stagger_out' ) ] ) : 0.75;
		$stagger_in     = isset( $s[ self::key( 'stagger_in' ) ] ) && '' !== $s[ self::key( 'stagger_in' ) ] ? floatval( $s[ self::key( 'stagger_in' ) ] ) : 0.5;
		$block_duration = isset( $s[ self::key( 'block_duration' ) ] ) && '' !== $s[ self::key( 'block_duration' ) ] ? floatval( $s[ self::key( 'block_duration' ) ] ) : 0.1;
		?>
		<div class="transition"
			data-egsap-id="<?php echo esc_attr( $element_id ); ?>"
			data-egsap-stagger-out="<?php echo esc_attr( $stagger_out ); ?>"
			data-egsap-stagger-in="<?php echo esc_attr( $stagger_in ); ?>"
			data-egsap-block-duration="<?php echo esc_attr( $block_duration ); ?>">
			<div class="transition-block"></div>
		</div>
		<?php
	}
}
