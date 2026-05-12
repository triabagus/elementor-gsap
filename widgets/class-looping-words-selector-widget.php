<?php
namespace Elementor_GSAP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Looping_Words_Selector_Widget extends Widget_Base {

	public function get_name() {
		return 'looping_words_selector';
	}

	public function get_title() {
		return __( 'Looping Words with Selector', 'elementor-gsap' );
	}

	public function get_icon() {
		return 'eicon-animation-text';
	}

	public function get_categories() {
		return [ 'elementor-gsap' ];
	}

	public function get_keywords() {
		return [ 'looping', 'words', 'selector', 'gsap', 'cycle', 'rotate', 'osmo' ];
	}

	public function get_script_depends() {
		return [ 'gsap', 'elementor-looping-words-selector' ];
	}

	public function get_style_depends() {
		return [ 'elementor-looping-words-selector' ];
	}

	protected function register_controls() {
		/* === CONTENT: WORDS === */
		$this->start_controls_section( 'content_section', [
			'label' => __( 'Words', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$repeater = new Repeater();
		$repeater->add_control( 'word', [
			'label'   => __( 'Word', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Word',
			'dynamic' => [ 'active' => true ],
		] );

		$this->add_control( 'words', [
			'label'       => __( 'Words List', 'elementor-gsap' ),
			'description' => __( 'Disarankan minimal 4 kata untuk loop yang halus.', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'title_field' => '{{{ word }}}',
			'default'     => [
				[ 'word' => 'GSAP' ],
				[ 'word' => 'Looping' ],
				[ 'word' => 'Words' ],
				[ 'word' => 'Selector' ],
				[ 'word' => 'Made with' ],
			],
		] );

		$this->end_controls_section();

		/* === CONTENT: ANIMATION === */
		$this->start_controls_section( 'animation_section', [
			'label' => __( 'Animation', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'duration', [
			'label'       => __( 'Word Duration (s)', 'elementor-gsap' ),
			'description' => __( 'Durasi perpindahan setiap kata. Default 1.2.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0.1,
			'max'         => 5,
			'step'        => 0.05,
			'default'     => 1.2,
		] );

		$this->add_control( 'pause', [
			'label'       => __( 'Pause Between Words (s)', 'elementor-gsap' ),
			'description' => __( 'Jeda statis sebelum pindah ke kata berikutnya.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0,
			'max'         => 10,
			'step'        => 0.1,
			'default'     => 2,
		] );

		$this->add_control( 'initial_delay', [
			'label'   => __( 'Initial Delay (s)', 'elementor-gsap' ),
			'type'    => Controls_Manager::NUMBER,
			'min'     => 0,
			'max'     => 10,
			'step'    => 0.1,
			'default' => 1,
		] );

		$this->add_control( 'easing', [
			'label'   => __( 'Word Easing', 'elementor-gsap' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'elastic.out(1, 0.85)' => 'Elastic (default)',
				'elastic.out(1, 0.5)'  => 'Elastic (tight)',
				'bounce.out'           => 'Bounce',
				'expo.out'             => 'Expo',
				'power3.out'           => 'Power3',
				'back.out(1.7)'        => 'Back',
				'sine.inOut'           => 'Sine',
				'none'                 => 'Linear',
			],
			'default' => 'elastic.out(1, 0.85)',
		] );

		$this->add_control( 'selector_duration', [
			'label'   => __( 'Selector Duration (s)', 'elementor-gsap' ),
			'type'    => Controls_Manager::NUMBER,
			'min'     => 0.1,
			'max'     => 3,
			'step'    => 0.05,
			'default' => 0.5,
		] );

		$this->add_control( 'selector_easing', [
			'label'   => __( 'Selector Easing', 'elementor-gsap' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'expo.out'      => 'Expo (default)',
				'power3.out'    => 'Power3',
				'power2.out'    => 'Power2',
				'back.out(1.7)' => 'Back',
				'sine.out'      => 'Sine',
				'none'          => 'Linear',
			],
			'default' => 'expo.out',
		] );

		$this->end_controls_section();

		/* === STYLE: CONTAINER === */
		$this->start_controls_section( 'style_container_section', [
			'label' => __( 'Container', 'elementor-gsap' ),
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
			'default'   => 'center',
			'selectors' => [
				'{{WRAPPER}} .elementor-widget-container' => 'display: flex; justify-content: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'font_size', [
			'label'      => __( 'Font Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em', 'rem', 'vw' ],
			'range'      => [
				'px'  => [ 'min' => 20, 'max' => 400 ],
				'em'  => [ 'min' => 2, 'max' => 20, 'step' => 0.5 ],
				'rem' => [ 'min' => 2, 'max' => 20, 'step' => 0.5 ],
				'vw'  => [ 'min' => 2, 'max' => 30, 'step' => 0.5 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 10 ],
			'selectors'  => [
				'{{WRAPPER}} .looping-words' => 'font-size: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'fade_color', [
			'label'       => __( 'Fade Background Color', 'elementor-gsap' ),
			'description' => __( 'Warna gradient fade atas/bawah. Set sama dengan background section agar efek hilang menyatu.', 'elementor-gsap' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => '#e6e2f7',
			'selectors'   => [
				'{{WRAPPER}} .looping-words' => '--lws-bg: {{VALUE}};',
			],
		] );

		$this->add_control( 'wrap_bg', [
			'label'       => __( 'Widget Background Color', 'elementor-gsap' ),
			'description' => __( 'Background widget — biasanya sama dengan Fade Background.', 'elementor-gsap' ),
			'type'        => Controls_Manager::COLOR,
			'selectors'   => [
				'{{WRAPPER}} .looping-words' => 'background-color: {{VALUE}};',
			],
		] );

		$this->end_controls_section();

		/* === STYLE: WORDS === */
		$this->start_controls_section( 'style_words_section', [
			'label' => __( 'Words', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'        => 'typography',
			'selector'    => '{{WRAPPER}} .looping-words',
			'description' => __( 'Font-size dari Typography ikut menskala seluruh widget (tinggi container, edge, padding) karena geometri pakai unit em. Untuk ukuran cepat & responsif gunakan Container → Font Size.', 'elementor-gsap' ),
		] );

		$this->add_control( 'text_color', [
			'label'     => __( 'Text Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .looping-words' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_section();

		/* === STYLE: SELECTOR === */
		$this->start_controls_section( 'style_selector_section', [
			'label' => __( 'Selector', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'edge_color', [
			'label'     => __( 'Edge Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#2600ff',
			'selectors' => [
				'{{WRAPPER}} .looping-words' => '--lws-edge-color: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'edge_thickness', [
			'label'      => __( 'Edge Thickness', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0.005, 'max' => 0.2, 'step' => 0.005 ],
				'px' => [ 'min' => 1, 'max' => 12 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 0.035 ],
			'selectors'  => [
				'{{WRAPPER}} .looping-words' => '--lws-edge-size: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'edge_length', [
			'label'      => __( 'Edge Length', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0.05, 'max' => 0.5, 'step' => 0.005 ],
				'px' => [ 'min' => 4, 'max' => 60 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 0.125 ],
			'selectors'  => [
				'{{WRAPPER}} .looping-words' => '--lws-edge-length: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'selector_height', [
			'label'       => __( 'Selector Height', 'elementor-gsap' ),
			'description' => __( 'Tinggi kotak selector — biasanya sedikit lebih tinggi dari line-height kata.', 'elementor-gsap' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'em', 'px' ],
			'range'       => [
				'em' => [ 'min' => 0.3, 'max' => 2, 'step' => 0.05 ],
				'px' => [ 'min' => 30, 'max' => 300 ],
			],
			'default'     => [ 'unit' => 'em', 'size' => 0.9 ],
			'selectors'   => [
				'{{WRAPPER}} .looping-words' => '--lws-selector-height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$items = ! empty( $s['words'] ) ? $s['words'] : [];
		if ( empty( $items ) ) {
			$items = [
				[ 'word' => 'GSAP' ],
				[ 'word' => 'Looping' ],
				[ 'word' => 'Words' ],
				[ 'word' => 'Selector' ],
				[ 'word' => 'Made with' ],
			];
		}

		$duration          = isset( $s['duration'] ) && '' !== $s['duration'] ? floatval( $s['duration'] ) : 1.2;
		$pause             = isset( $s['pause'] ) && '' !== $s['pause'] ? floatval( $s['pause'] ) : 2;
		$delay             = isset( $s['initial_delay'] ) && '' !== $s['initial_delay'] ? floatval( $s['initial_delay'] ) : 1;
		$ease              = ! empty( $s['easing'] ) ? $s['easing'] : 'elastic.out(1, 0.85)';
		$selector_duration = isset( $s['selector_duration'] ) && '' !== $s['selector_duration'] ? floatval( $s['selector_duration'] ) : 0.5;
		$selector_ease     = ! empty( $s['selector_easing'] ) ? $s['selector_easing'] : 'expo.out';
		?>
		<div data-looping-words class="looping-words"
			data-lws-duration="<?php echo esc_attr( $duration ); ?>"
			data-lws-pause="<?php echo esc_attr( $pause ); ?>"
			data-lws-delay="<?php echo esc_attr( $delay ); ?>"
			data-lws-ease="<?php echo esc_attr( $ease ); ?>"
			data-lws-selector-duration="<?php echo esc_attr( $selector_duration ); ?>"
			data-lws-selector-ease="<?php echo esc_attr( $selector_ease ); ?>">
			<div class="looping-words__containers">
				<ul data-looping-words-list class="looping-words__list">
					<?php foreach ( $items as $item ) :
						$word = ! empty( $item['word'] ) ? $item['word'] : '';
						?>
						<li class="looping-words__item">
							<p class="looping-words__p"><?php echo esc_html( $word ); ?></p>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="looping-words__fade" aria-hidden="true"></div>
			<div data-looping-words-selector class="looping-words__selector" aria-hidden="true">
				<div class="looping-words__edge"></div>
				<div class="looping-words__edge is--2"></div>
				<div class="looping-words__edge is--3"></div>
				<div class="looping-words__edge is--4"></div>
			</div>
		</div>
		<?php
	}
}
