<?php
namespace Elementor_GSAP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Draggable_Infinite_Slider_Widget extends Widget_Base {

	public function get_name() {
		return 'draggable_infinite_slider';
	}

	public function get_title() {
		return __( 'Draggable Infinite Slider', 'elementor-gsap' );
	}

	public function get_icon() {
		return 'eicon-slider-album';
	}

	public function get_categories() {
		return [ 'elementor-gsap' ];
	}

	public function get_keywords() {
		return [ 'gsap', 'draggable', 'slider', 'infinite', 'carousel', 'inertia' ];
	}

	public function get_script_depends() {
		return [ 'gsap', 'gsap-draggable', 'gsap-inertia', 'elementor-draggable-slider' ];
	}

	public function get_style_depends() {
		return [ 'elementor-draggable-slider' ];
	}

	protected function register_controls() {
		$this->register_content_section();
		$this->register_settings_section();
		$this->register_general_style();
		$this->register_slide_style();
		$this->register_caption_style();
		$this->register_counter_style();
		$this->register_button_style();
	}

	private function register_content_section() {
		$this->start_controls_section( 'slides_section', [
			'label' => __( 'Slides', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$repeater = new Repeater();
		$repeater->add_control( 'image', [
			'label'   => __( 'Image', 'elementor-gsap' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [ 'url' => '' ],
		] );
		$repeater->add_control( 'caption_label', [
			'label'   => __( 'Caption', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Image nº001',
		] );

		$this->add_control( 'slides', [
			'label'       => __( 'Slides', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'default'     => [
				[ 'image' => [ 'url' => 'https://cdn.prod.website-files.com/67696d1cd4b1d776a63f0f94/690b581ba59c96e073460cd1_Cinematic%20Motion%20Portrait.avif' ], 'caption_label' => 'Image nº005' ],
				[ 'image' => [ 'url' => 'https://cdn.prod.website-files.com/67696d1cd4b1d776a63f0f94/690b581b4e66ce6d99185126_Child%20in%20Sunset%20Meadow.avif' ], 'caption_label' => 'Image nº001' ],
				[ 'image' => [ 'url' => 'https://cdn.prod.website-files.com/67696d1cd4b1d776a63f0f94/690b581b644385ab3c4845f8_Woman%20in%20Coastal%20Field.avif' ], 'caption_label' => 'Image nº002' ],
				[ 'image' => [ 'url' => 'https://cdn.prod.website-files.com/67696d1cd4b1d776a63f0f94/690b581bae1e27262dcfe889_Runner%20at%20Golden%20Hour.avif' ], 'caption_label' => 'Image nº003' ],
				[ 'image' => [ 'url' => 'https://cdn.prod.website-files.com/67696d1cd4b1d776a63f0f94/690b581b7c6e8ac0e1960406_Golden%20Hour%20Serenity.avif' ], 'caption_label' => 'Layout nº004' ],
			],
			'title_field' => '{{{ caption_label }}}',
		] );

		$this->end_controls_section();
	}

	private function register_settings_section() {
		$this->start_controls_section( 'settings_section', [
			'label' => __( 'Slider Settings', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'use_next_for_active', [
			'label'        => __( 'Active = Next Sibling', 'elementor-gsap' ),
			'description'  => __( 'On = pasang class active ke slide berikutnya (offset effect ala Osmo). Off = active langsung di slide tengah.', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'center_mode', [
			'label'        => __( 'Center Active Slide', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => '',
		] );

		$this->add_control( 'transition_duration', [
			'label'   => __( 'Transition Duration (s)', 'elementor-gsap' ),
			'type'    => Controls_Manager::NUMBER,
			'min'     => 0.1,
			'max'     => 3,
			'step'    => 0.025,
			'default' => 0.725,
		] );

		$this->end_controls_section();
	}

	private function register_general_style() {
		$this->start_controls_section( 'general_style', [
			'label' => __( 'General', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'section_bg', [
			'label'     => __( 'Background Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#20261b',
			'selectors' => [
				'{{WRAPPER}} .slider__section' => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .slider__overlay' => 'background-image: linear-gradient(90deg, {{VALUE}} 85%, transparent);',
			],
		] );

		$this->add_responsive_control( 'section_min_height', [
			'label'      => __( 'Min Height', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'vh', 'em' ],
			'range'      => [
				'px' => [ 'min' => 200, 'max' => 1200 ],
				'vh' => [ 'min' => 30, 'max' => 100 ],
				'em' => [ 'min' => 10, 'max' => 80 ],
			],
			'default'    => [ 'unit' => 'vh', 'size' => 100 ],
			'selectors'  => [
				'{{WRAPPER}} .slider__section' => 'min-height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	private function register_slide_style() {
		$this->start_controls_section( 'slide_style', [
			'label' => __( 'Slide', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'slide_width', [
			'label'          => __( 'Slide Width', 'elementor-gsap' ),
			'type'           => Controls_Manager::SLIDER,
			'size_units'     => [ 'vw', '%', 'px' ],
			'range'          => [
				'vw' => [ 'min' => 20, 'max' => 100 ],
				'%'  => [ 'min' => 20, 'max' => 100 ],
				'px' => [ 'min' => 200, 'max' => 1200 ],
			],
			'default'        => [ 'unit' => 'vw', 'size' => 36 ],
			'tablet_default' => [ 'unit' => 'vw', 'size' => 75 ],
			'mobile_default' => [ 'unit' => 'vw', 'size' => 90 ],
			'selectors'      => [
				'{{WRAPPER}} .slider__slide' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'slide_aspect_ratio', [
			'label'   => __( 'Aspect Ratio', 'elementor-gsap' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'3/2'  => '3:2',
				'4/3'  => '4:3',
				'1/1'  => '1:1',
				'16/9' => '16:9',
				'9/16' => '9:16 (portrait)',
				'2/3'  => '2:3 (portrait)',
			],
			'default' => '3/2',
			'selectors' => [
				'{{WRAPPER}} .slider__slide' => 'aspect-ratio: {{VALUE}};',
			],
		] );

		$this->add_control( 'slide_border_radius', [
			'label'      => __( 'Border Radius', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 3, 'step' => 0.05 ],
				'px' => [ 'min' => 0, 'max' => 50 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 0.5 ],
			'selectors'  => [
				'{{WRAPPER}} .slider__slide-inner' => 'border-radius: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'inactive_opacity', [
			'label'     => __( 'Inactive Slide Opacity', 'elementor-gsap' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [ 'min' => 0.05, 'max' => 1, 'step' => 0.05 ],
			],
			'default'   => [ 'size' => 0.2 ],
			'selectors' => [
				'{{WRAPPER}} [data-slider="slide"]'         => 'opacity: {{SIZE}};',
				'{{WRAPPER}} [data-slider="slide"].active'  => 'opacity: 1;',
			],
		] );

		$this->end_controls_section();
	}

	private function register_caption_style() {
		$this->start_controls_section( 'caption_style', [
			'label' => __( 'Slide Caption', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'caption_bg', [
			'label'     => __( 'Background', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(239, 238, 236, 0.15)',
			'selectors' => [
				'{{WRAPPER}} .slide__caption' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'caption_color', [
			'label'     => __( 'Text Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .slide__caption' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'caption_dot', [
			'label'     => __( 'Dot Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#a1ff62',
			'selectors' => [
				'{{WRAPPER}} .slide__caption-dot' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'caption_typography',
			'label'    => __( 'Typography', 'elementor-gsap' ),
			'selector' => '{{WRAPPER}} .slide__caption-label',
		] );

		$this->end_controls_section();
	}

	private function register_counter_style() {
		$this->start_controls_section( 'counter_style', [
			'label' => __( 'Counter', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'counter_color', [
			'label'     => __( 'Text Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .slider__overlay' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'counter_divider_color', [
			'label'     => __( 'Divider Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#efeeec',
			'selectors' => [
				'{{WRAPPER}} .slider__count-divider' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'counter_typography',
			'label'    => __( 'Typography', 'elementor-gsap' ),
			'selector' => '{{WRAPPER}} .slider__count-heading',
		] );

		$this->end_controls_section();
	}

	private function register_button_style() {
		$this->start_controls_section( 'button_style', [
			'label' => __( 'Navigation Buttons', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'btn_color', [
			'label'     => __( 'Arrow / Text Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .slider__btn' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'btn_border', [
			'label'     => __( 'Border Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(255, 255, 255, 0.2)',
			'selectors' => [
				'{{WRAPPER}} .slider__btn' => 'border-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'btn_corner', [
			'label'     => __( 'Hover Corner Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#efeeec',
			'selectors' => [
				'{{WRAPPER}} .slider__btn-overlay-corner' => 'border-color: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'btn_size', [
			'label'      => __( 'Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 2, 'max' => 8, 'step' => 0.1 ],
				'px' => [ 'min' => 32, 'max' => 100 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 4 ],
			'selectors'  => [
				'{{WRAPPER}} .slider__btn' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$s        = $this->get_settings_for_display();
		$slides   = ! empty( $s['slides'] ) ? $s['slides'] : [];
		$total    = count( $slides );
		$use_next = ( 'yes' === ( $s['use_next_for_active'] ?? 'yes' ) ) ? 'true' : 'false';
		$center   = ( 'yes' === ( $s['center_mode'] ?? '' ) ) ? 'true' : 'false';
		$duration = isset( $s['transition_duration'] ) && '' !== $s['transition_duration'] ? floatval( $s['transition_duration'] ) : 0.725;

		if ( 0 === $total ) {
			return;
		}

		$active_index = ( 'true' === $use_next && $total > 1 ) ? 1 : 0;
		?>
		<div class="slider__section" data-egsap-slider
			data-slider-use-next="<?php echo esc_attr( $use_next ); ?>"
			data-slider-center="<?php echo esc_attr( $center ); ?>"
			data-slider-duration="<?php echo esc_attr( $duration ); ?>">
			<div class="slider__overlay">
				<div class="slider__overlay-inner">
					<div class="slider__overlay-count">
						<div class="slider__count-col">
							<h2 data-slide-count="step" class="slider__count-heading">01</h2>
						</div>
						<div class="slider__count-divider"></div>
						<div class="slider__count-col">
							<h2 data-slide-count="total" class="slider__count-heading"><?php echo esc_html( str_pad( (string) $total, 2, '0', STR_PAD_LEFT ) ); ?></h2>
						</div>
					</div>
					<div class="slider__overlay-nav">
						<button aria-label="<?php esc_attr_e( 'Previous slide', 'elementor-gsap' ); ?>" data-slider-button="prev" class="slider__btn">
							<?php echo $this->arrow_svg( 'prev' ); ?>
							<?php echo $this->corners_html(); ?>
						</button>
						<button aria-label="<?php esc_attr_e( 'Next slide', 'elementor-gsap' ); ?>" data-slider-button="next" class="slider__btn">
							<?php echo $this->arrow_svg( 'next' ); ?>
							<?php echo $this->corners_html(); ?>
						</button>
					</div>
				</div>
			</div>
			<div class="slider__main">
				<div class="slider__wrap">
					<div data-slider="list" class="slider__list">
						<?php foreach ( $slides as $i => $slide ) :
							$img_url   = ! empty( $slide['image']['url'] ) ? $slide['image']['url'] : '';
							$label     = ! empty( $slide['caption_label'] ) ? $slide['caption_label'] : '';
							$is_active = ( $i === $active_index ) ? ' active' : '';
							?>
							<div data-slider="slide" class="slider__slide<?php echo esc_attr( $is_active ); ?>">
								<div class="slider__slide-inner">
									<?php if ( $img_url ) : ?>
										<img src="<?php echo esc_url( $img_url ); ?>" class="slide__img" alt="<?php echo esc_attr( $label ); ?>">
									<?php endif; ?>
									<?php if ( $label ) : ?>
										<div class="slide__caption">
											<div class="slide__caption-dot"></div>
											<p class="slide__caption-label"><?php echo esc_html( $label ); ?></p>
										</div>
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

	private function arrow_svg( $direction ) {
		$class = 'slider__btn-arrow' . ( 'next' === $direction ? ' next' : '' );
		return '<svg xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 17 12" fill="none" class="' . esc_attr( $class ) . '"><path d="M6.28871 12L7.53907 10.9111L3.48697 6.77778H16.5V5.22222H3.48697L7.53907 1.08889L6.28871 0L0.5 6L6.28871 12Z" fill="currentColor"></path></svg>';
	}

	private function corners_html() {
		return '<div class="slider__btn-overlay">'
			. '<div class="slider__btn-overlay-corner"></div>'
			. '<div class="slider__btn-overlay-corner top-right"></div>'
			. '<div class="slider__btn-overlay-corner bottom-left"></div>'
			. '<div class="slider__btn-overlay-corner bottom-right"></div>'
			. '</div>';
	}
}
