<?php
namespace Elementor_GSAP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Gradient_Wave_Text_Widget extends Widget_Base {

	public function get_name() {
		return 'gradient_wave_text';
	}

	public function get_title() {
		return __( 'Gradient Wave Text on Scroll', 'elementor-gsap' );
	}

	public function get_icon() {
		return 'eicon-typography';
	}

	public function get_categories() {
		return [ 'elementor-gsap-text' ];
	}

	public function get_keywords() {
		return [ 'gradient', 'wave', 'text', 'scroll', 'scrolltrigger', 'splittext', 'gsap', 'osmo' ];
	}

	public function get_script_depends() {
		return [ 'gsap', 'gsap-scrolltrigger', 'gsap-splittext', 'elementor-gradient-wave-text' ];
	}

	public function get_style_depends() {
		return [ 'elementor-gradient-wave-text' ];
	}

	protected function register_controls() {

		/* === CONTENT: TEXT === */
		$this->start_controls_section( 'content_text', [
			'label' => __( 'Text', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'text', [
			'label'   => __( 'Text', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => 4,
			'default' => 'This heading reveals itself while you scroll in a gradient wave, fading from an inactive color to the base color.',
			'dynamic' => [ 'active' => true ],
		] );

		$this->add_control( 'html_tag', [
			'label'   => __( 'HTML Tag', 'elementor-gsap' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'h1'   => 'H1',
				'h2'   => 'H2',
				'h3'   => 'H3',
				'h4'   => 'H4',
				'h5'   => 'H5',
				'h6'   => 'H6',
				'p'    => 'P',
				'div'  => 'DIV',
				'span' => 'SPAN',
			],
			'default' => 'h2',
		] );

		$this->add_responsive_control( 'align', [
			'label'     => __( 'Alignment', 'elementor-gsap' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'left'    => [ 'title' => __( 'Left', 'elementor-gsap' ),    'icon' => 'eicon-text-align-left' ],
				'center'  => [ 'title' => __( 'Center', 'elementor-gsap' ),  'icon' => 'eicon-text-align-center' ],
				'right'   => [ 'title' => __( 'Right', 'elementor-gsap' ),   'icon' => 'eicon-text-align-right' ],
				'justify' => [ 'title' => __( 'Justify', 'elementor-gsap' ), 'icon' => 'eicon-text-align-justify' ],
			],
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .egsap-gradient-wave-text' => 'text-align: {{VALUE}};',
			],
		] );

		$this->end_controls_section();

		/* === CONTENT: ANIMATION === */
		$this->start_controls_section( 'content_anim', [
			'label' => __( 'Animation', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'scroll_start', [
			'label'       => __( 'Scroll Start', 'elementor-gsap' ),
			'description' => __( 'ScrollTrigger <code>start</code> position. Format: <code>trigger viewport</code>. Contoh: <code>top 90%</code>, <code>top center</code>.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => 'top 90%',
		] );

		$this->add_control( 'scroll_end', [
			'label'       => __( 'Scroll End', 'elementor-gsap' ),
			'description' => __( 'ScrollTrigger <code>end</code> position. Contoh: <code>center 40%</code>, <code>bottom top</code>.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => 'center 40%',
		] );

		$this->add_control( 'scrub', [
			'label'       => __( 'Scrub Value', 'elementor-gsap' ),
			'description' => __( 'Smoothing lag saat scroll. 0 = instan mengikuti scroll; 1 = smooth 1s. Default 0.1.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0,
			'max'         => 3,
			'step'        => 0.05,
			'default'     => 0.1,
		] );

		$this->add_control( 'wave_duration', [
			'label'       => __( 'Wave Duration (s)', 'elementor-gsap' ),
			'description' => __( 'Durasi flash tiap char lewat waveColor sebelum settle ke baseColor.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0.05,
			'max'         => 3,
			'step'        => 0.05,
			'default'     => 0.4,
		] );

		$this->end_controls_section();

		/* === STYLE: COLORS === */
		$this->start_controls_section( 'style_colors', [
			'label' => __( 'Wave Colors', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'base_color', [
			'label'       => __( 'Base Color (Final)', 'elementor-gsap' ),
			'description' => __( 'Warna teks setelah selesai reveal. JS baca dari CSS <code>color</code> element ini.', 'elementor-gsap' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => '#ffffff',
			'selectors'   => [
				'{{WRAPPER}} .egsap-gradient-wave-text' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'start_color', [
			'label'       => __( 'Start Color (Inactive)', 'elementor-gsap' ),
			'description' => __( 'Warna teks sebelum char di-reveal. Biasanya semi-transparent.', 'elementor-gsap' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => 'rgba(255, 255, 255, 0.2)',
		] );

		$this->add_control( 'wave_color', [
			'label'       => __( 'Wave Color (Flash)', 'elementor-gsap' ),
			'description' => __( 'Warna sekilas saat char di-activate — flash sebelum ke Base Color.', 'elementor-gsap' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => '#F84131',
		] );

		$this->end_controls_section();

		/* === STYLE: TYPOGRAPHY === */
		$this->start_controls_section( 'style_typography', [
			'label' => __( 'Typography', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'text_typography',
			'selector' => '{{WRAPPER}} .egsap-gradient-wave-text',
		] );

		$this->end_controls_section();
	}

	protected function is_edit_mode() {
		return class_exists( '\Elementor\Plugin' )
			&& \Elementor\Plugin::$instance->editor
			&& \Elementor\Plugin::$instance->editor->is_edit_mode();
	}

	private function safe_tag( $tag ) {
		$allowed = [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div', 'span' ];
		return in_array( $tag, $allowed, true ) ? $tag : 'h2';
	}

	protected function render() {
		$s    = $this->get_settings_for_display();
		$text = isset( $s['text'] ) ? $s['text'] : '';
		if ( '' === $text ) {
			return;
		}

		$tag           = $this->safe_tag( isset( $s['html_tag'] ) ? $s['html_tag'] : 'h2' );
		$scroll_start  = ! empty( $s['scroll_start'] )  ? $s['scroll_start']  : 'top 90%';
		$scroll_end    = ! empty( $s['scroll_end'] )    ? $s['scroll_end']    : 'center 40%';
		$scrub         = isset( $s['scrub'] )         && '' !== $s['scrub']         ? floatval( $s['scrub'] )         : 0.1;
		$wave_duration = isset( $s['wave_duration'] ) && '' !== $s['wave_duration'] ? floatval( $s['wave_duration'] ) : 0.4;
		$start_color   = ! empty( $s['start_color'] ) ? $s['start_color'] : 'rgba(255, 255, 255, 0.2)';
		$wave_color    = ! empty( $s['wave_color'] )  ? $s['wave_color']  : '#F84131';

		$classes = 'egsap-gradient-wave-text' . ( $this->is_edit_mode() ? ' egsap-edit-mode' : '' );
		?>
		<<?php echo esc_html( $tag ); ?>
			data-gradient-wave-text
			data-gradient-wave-scroll-start="<?php echo esc_attr( $scroll_start ); ?>"
			data-gradient-wave-scroll-end="<?php echo esc_attr( $scroll_end ); ?>"
			data-gradient-wave-color-start="<?php echo esc_attr( $start_color ); ?>"
			data-gradient-wave-color-wave="<?php echo esc_attr( $wave_color ); ?>"
			data-gradient-wave-duration="<?php echo esc_attr( $wave_duration ); ?>"
			data-gradient-wave-scrub="<?php echo esc_attr( $scrub ); ?>"
			class="<?php echo esc_attr( $classes ); ?>"
		><?php echo esc_html( $text ); ?></<?php echo esc_html( $tag ); ?>>
		<?php
	}
}
