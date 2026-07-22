<?php
namespace Elementor_GSAP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Cascading_Slider_Widget extends Widget_Base {

	public function get_name() {
		return 'cascading_slider';
	}

	public function get_title() {
		return __( 'Cascading Slider', 'elementor-gsap' );
	}

	public function get_icon() {
		return 'eicon-slider-push';
	}

	public function get_categories() {
		return [ 'elementor-gsap-sliders' ];
	}

	public function get_keywords() {
		return [ 'slider', 'cascading', 'carousel', 'gsap', 'clip-path', 'osmo' ];
	}

	public function get_script_depends() {
		return [ 'gsap', 'elementor-cascading-slider' ];
	}

	public function get_style_depends() {
		return [ 'elementor-cascading-slider' ];
	}

	public function arrow_svg() {
		return '<svg xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 24 24" fill="none"><path d="M14 19L21 12L14 5" stroke="currentColor" stroke-miterlimit="10" stroke-width="1.5"/><path d="M21 12H2" stroke="currentColor" stroke-miterlimit="10" stroke-width="1.5"/></svg>';
	}

	protected function register_controls() {

		/* ========================================================= */
		/*                       CONTENT — SLIDES                     */
		/* ========================================================= */
		$this->start_controls_section( 'content_slides', [
			'label' => __( 'Slides', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'slides_help', [
			'type'    => Controls_Manager::RAW_HTML,
			'raw'     => __( '<strong>Tips:</strong> minimal 5 slides untuk cascading effect penuh. JS otomatis duplicate ke minimum 9 kalau kurang.', 'elementor-gsap' ),
			'content_classes' => 'elementor-descriptor',
		] );

		$rep = new Repeater();
		$rep->add_control( 'image', [
			'label'   => __( 'Slide Image', 'elementor-gsap' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
		] );
		$rep->add_control( 'title', [
			'label'   => __( 'Title', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Slide title',
		] );

		$this->add_control( 'slides', [
			'label'       => __( 'Slides', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ title }}}',
			'default'     => [
				[ 'title' => 'Annual overview',       'image' => [ 'url' => 'https://cdn.prod.website-files.com/699ecbb03f86e84bad7a74f3/699eea7d454cb9d5091ac8ce_cascading-carousel-3.avif' ] ],
				[ 'title' => 'Sustainability efforts','image' => [ 'url' => 'https://cdn.prod.website-files.com/699ecbb03f86e84bad7a74f3/699eec227ff9240c1e047cf3_cascading-carousel-2.avif' ] ],
				[ 'title' => 'Product development',   'image' => [ 'url' => 'https://cdn.prod.website-files.com/699ecbb03f86e84bad7a74f3/699eea7d6333786f72559958_cascading-carousel-5.avif' ] ],
				[ 'title' => 'Infrastructure',        'image' => [ 'url' => 'https://cdn.prod.website-files.com/699ecbb03f86e84bad7a74f3/699eea7d9bf91f87ca962997_cascading-carousel-1.avif' ] ],
				[ 'title' => 'Enterprises',           'image' => [ 'url' => 'https://cdn.prod.website-files.com/699ecbb03f86e84bad7a74f3/699eea7d882b31c7ce3a35be_cascading-carousel-4.avif' ] ],
			],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                     CONTENT — BEHAVIOR                     */
		/* ========================================================= */
		$this->start_controls_section( 'content_behavior', [
			'label' => __( 'Slider Behavior', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'nav_enable', [
			'label'        => __( 'Show Prev/Next Buttons', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'duration', [
			'label'       => __( 'Transition Duration (seconds)', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0.2,
			'max'         => 2,
			'step'        => 0.05,
			'default'     => 0.65,
		] );

		$this->add_control( 'ease', [
			'label'       => __( 'Ease', 'elementor-gsap' ),
			'description' => __( 'GSAP easing function.', 'elementor-gsap' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => [
				'power1.inOut' => 'power1.inOut',
				'power2.inOut' => 'power2.inOut',
				'power3.inOut' => 'power3.inOut',
				'power4.inOut' => 'power4.inOut',
				'expo.inOut'   => 'expo.inOut',
				'circ.inOut'   => 'circ.inOut',
				'sine.inOut'   => 'sine.inOut',
			],
			'default'     => 'power3.inOut',
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                CONTENT — SLIDE WIDTH RATIOS                */
		/* ========================================================= */
		$this->start_controls_section( 'content_widths', [
			'label' => __( 'Slide Width Ratios (per Breakpoint)', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'ratios_help', [
			'type'    => Controls_Manager::RAW_HTML,
			'raw'     => __( 'Semua nilai adalah <strong>fraction (0-1)</strong> dari viewport width. Active slide = fraction × viewport. Sibling = fraction × viewport.', 'elementor-gsap' ),
			'content_classes' => 'elementor-descriptor',
		] );

		$this->add_control( 'desktop_active', [
			'label'   => __( 'Desktop (>991px) — Active Width', 'elementor-gsap' ),
			'type'    => Controls_Manager::NUMBER,
			'min'     => 0.2, 'max' => 0.95, 'step' => 0.01,
			'default' => 0.60,
		] );

		$this->add_control( 'desktop_sibling', [
			'label'   => __( 'Desktop — Sibling Width', 'elementor-gsap' ),
			'type'    => Controls_Manager::NUMBER,
			'min'     => 0.02, 'max' => 0.3, 'step' => 0.01,
			'default' => 0.13,
		] );

		$this->add_control( 'laptop_active', [
			'label'     => __( 'Laptop (≤991px) — Active Width', 'elementor-gsap' ),
			'type'      => Controls_Manager::NUMBER,
			'min'       => 0.2, 'max' => 0.95, 'step' => 0.01,
			'default'   => 0.60,
			'separator' => 'before',
		] );

		$this->add_control( 'laptop_sibling', [
			'label'   => __( 'Laptop — Sibling Width', 'elementor-gsap' ),
			'type'    => Controls_Manager::NUMBER,
			'min'     => 0.02, 'max' => 0.3, 'step' => 0.01,
			'default' => 0.10,
		] );

		$this->add_control( 'tablet_active', [
			'label'     => __( 'Tablet (≤767px) — Active Width', 'elementor-gsap' ),
			'type'      => Controls_Manager::NUMBER,
			'min'       => 0.2, 'max' => 0.95, 'step' => 0.01,
			'default'   => 0.70,
			'separator' => 'before',
		] );

		$this->add_control( 'tablet_sibling', [
			'label'   => __( 'Tablet — Sibling Width', 'elementor-gsap' ),
			'type'    => Controls_Manager::NUMBER,
			'min'     => 0.02, 'max' => 0.3, 'step' => 0.01,
			'default' => 0.10,
		] );

		$this->add_control( 'mobile_active', [
			'label'     => __( 'Mobile (≤479px) — Active Width', 'elementor-gsap' ),
			'type'      => Controls_Manager::NUMBER,
			'min'       => 0.2, 'max' => 0.95, 'step' => 0.01,
			'default'   => 0.78,
			'separator' => 'before',
		] );

		$this->add_control( 'mobile_sibling', [
			'label'   => __( 'Mobile — Sibling Width', 'elementor-gsap' ),
			'type'    => Controls_Manager::NUMBER,
			'min'     => 0.02, 'max' => 0.3, 'step' => 0.01,
			'default' => 0.08,
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                       STYLE — LAYOUT                       */
		/* ========================================================= */
		$this->start_controls_section( 'style_layout', [
			'label' => __( 'Layout', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'max_width', [
			'label'      => __( 'Container Max Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px', 'rem', '%' ],
			'range'      => [
				'em' => [ 'min' => 30, 'max' => 120 ],
				'px' => [ 'min' => 480, 'max' => 1920 ],
				'%'  => [ 'min' => 50, 'max' => 100 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 90 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-csr' => '--csr-max-width: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'list_height', [
			'label'      => __( 'Slider Height', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px', 'vh', 'dvh' ],
			'range'      => [
				'em'  => [ 'min' => 15, 'max' => 60 ],
				'px'  => [ 'min' => 240, 'max' => 900 ],
				'vh'  => [ 'min' => 30, 'max' => 90 ],
				'dvh' => [ 'min' => 30, 'max' => 90 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 35 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-csr' => '--csr-list-height: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'slide_gap', [
			'label'      => __( 'Slide Gap', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 0, 'max' => 3, 'step' => 0.05 ], 'px' => [ 'min' => 0, 'max' => 40 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 0.5 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-csr' => '--csr-gap: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'slide_radius', [
			'label'      => __( 'Slide Border Radius', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 0, 'max' => 3, 'step' => 0.05 ], 'px' => [ 'min' => 0, 'max' => 48 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 0.75 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-csr' => '--csr-radius: {{SIZE}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                       STYLE — CONTENT                      */
		/* ========================================================= */
		$this->start_controls_section( 'style_content', [
			'label' => __( 'Slide Content (Title)', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'content_color', [
			'label'   => __( 'Title Color', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#FFFFFF',
			'selectors' => [ '{{WRAPPER}} .egsap-csr' => '--csr-content-color: {{VALUE}};' ],
		] );

		$this->add_control( 'content_gradient', [
			'label'       => __( 'Bottom Gradient (CSS)', 'elementor-gsap' ),
			'description' => __( 'CSS <code>linear-gradient(...)</code> untuk background di kartu bagian bawah.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => 'linear-gradient(0deg, rgba(0,0,0,0.6), rgba(0,0,0,0))',
			'selectors'   => [ '{{WRAPPER}} .egsap-csr' => '--csr-content-gradient: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'title_size', [
			'label'      => __( 'Title Font Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px', 'rem' ],
			'range'      => [
				'em' => [ 'min' => 1, 'max' => 5, 'step' => 0.05 ],
				'px' => [ 'min' => 16, 'max' => 80 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 2.75 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-csr' => '--csr-title-size: {{SIZE}}{{UNIT}};' ],
			'separator'  => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_typography',
			'label'    => __( 'Title Typography', 'elementor-gsap' ),
			'selector' => '{{WRAPPER}} .egsap-csr__h',
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                     STYLE — NAVIGATION                     */
		/* ========================================================= */
		$this->start_controls_section( 'style_nav', [
			'label' => __( 'Navigation Buttons', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
			'condition' => [ 'nav_enable' => 'yes' ],
		] );

		$this->add_control( 'btn_bg', [
			'label'   => __( 'Button Background', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#D7ECD7',
			'selectors' => [ '{{WRAPPER}} .egsap-csr' => '--csr-btn-bg: {{VALUE}};' ],
		] );

		$this->add_control( 'btn_color', [
			'label'   => __( 'Button Icon Color', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#323B32',
			'selectors' => [ '{{WRAPPER}} .egsap-csr' => '--csr-btn-color: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'btn_size', [
			'label'      => __( 'Button Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 2, 'max' => 5, 'step' => 0.1 ], 'px' => [ 'min' => 32, 'max' => 80 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 3 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-csr' => '--csr-btn-size: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'btn_pad', [
			'label'      => __( 'Button Padding (Icon Inset)', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 0, 'max' => 2, 'step' => 0.05 ], 'px' => [ 'min' => 0, 'max' => 32 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 0.75 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-csr' => '--csr-btn-pad: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'btn_radius', [
			'label'      => __( 'Button Border Radius', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px', '%' ],
			'range'      => [ 'em' => [ 'min' => 0, 'max' => 3, 'step' => 0.05 ], 'px' => [ 'min' => 0, 'max' => 40 ], '%' => [ 'min' => 0, 'max' => 50 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 0.25 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-csr' => '--csr-btn-radius: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'btn_gap', [
			'label'      => __( 'Gap Between Buttons', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 0, 'max' => 3, 'step' => 0.05 ], 'px' => [ 'min' => 0, 'max' => 40 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 1 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-csr' => '--csr-btn-gap: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'nav_mt', [
			'label'      => __( 'Nav Margin Top', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 0, 'max' => 8, 'step' => 0.1 ], 'px' => [ 'min' => 0, 'max' => 100 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 4 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-csr' => '--csr-nav-mt: {{SIZE}}{{UNIT}};' ],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$slides = ! empty( $s['slides'] ) && is_array( $s['slides'] ) ? $s['slides'] : [];

		$is_edit = false;
		if ( class_exists( '\Elementor\Plugin' ) ) {
			$plugin = \Elementor\Plugin::$instance;
			if ( isset( $plugin->editor ) && method_exists( $plugin->editor, 'is_edit_mode' ) && $plugin->editor->is_edit_mode() ) {
				$is_edit = true;
			}
			if ( isset( $plugin->preview ) && method_exists( $plugin->preview, 'is_preview_mode' ) && $plugin->preview->is_preview_mode() ) {
				$is_edit = true;
			}
		}

		$editor_flag = $is_edit ? ' data-egsap-csr-editor="1"' : '';

		$arrow = $this->arrow_svg();

		/* Data attributes for JS config */
		$data = [
			'egsap-csr-duration'         => floatval( $s['duration'] ?? 0.65 ),
			'egsap-csr-ease'             => $s['ease'] ?? 'power3.inOut',
			'egsap-csr-mobile-active'    => floatval( $s['mobile_active'] ?? 0.78 ),
			'egsap-csr-mobile-sibling'   => floatval( $s['mobile_sibling'] ?? 0.08 ),
			'egsap-csr-tablet-active'    => floatval( $s['tablet_active'] ?? 0.70 ),
			'egsap-csr-tablet-sibling'   => floatval( $s['tablet_sibling'] ?? 0.10 ),
			'egsap-csr-laptop-active'    => floatval( $s['laptop_active'] ?? 0.60 ),
			'egsap-csr-laptop-sibling'   => floatval( $s['laptop_sibling'] ?? 0.10 ),
			'egsap-csr-desktop-active'   => floatval( $s['desktop_active'] ?? 0.60 ),
			'egsap-csr-desktop-sibling'  => floatval( $s['desktop_sibling'] ?? 0.13 ),
		];
		$data_attrs = '';
		foreach ( $data as $k => $v ) {
			$data_attrs .= ' data-' . $k . '="' . esc_attr( $v ) . '"';
		}
		?>
		<div
			class="egsap-csr"
			data-egsap-csr-wrap
			aria-label="Featured content"
			aria-roledescription="carousel"
			<?php echo $editor_flag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php echo $data_attrs;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		>
			<div class="egsap-csr__collection">
				<div data-egsap-csr-viewport class="egsap-csr__list">
					<?php if ( ! empty( $slides ) ) : foreach ( $slides as $i => $slide ) :
						$img   = $slide['image']['url'] ?? '';
						$title = $slide['title'] ?? '';
						$loading = 0 === $i ? 'eager' : 'lazy';
						?>
						<div
							aria-roledescription="slide"
							data-egsap-csr-slide
							role="group"
							class="egsap-csr__item"
						>
							<div class="egsap-csr__item-inner">
								<div class="egsap-csr__item-bg">
									<?php if ( $img ) : ?>
										<img src="<?php echo esc_url( $img ); ?>" loading="<?php echo esc_attr( $loading ); ?>" draggable="false" alt="" class="egsap-csr__img" />
									<?php endif; ?>
								</div>
								<?php if ( '' !== $title ) : ?>
									<div class="egsap-csr__item-content">
										<h3 class="egsap-csr__h"><?php echo esc_html( $title ); ?></h3>
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; endif; ?>
				</div>
			</div>

			<?php if ( 'yes' === ( $s['nav_enable'] ?? '' ) ) : ?>
				<nav aria-label="slider navigation" class="egsap-csr__nav">
					<button type="button" data-egsap-csr-prev aria-label="previous slide" class="egsap-csr__button">
						<span class="egsap-csr__button-arrow is--prev"><?php echo $arrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</button>
					<button type="button" data-egsap-csr-next aria-label="next slide" class="egsap-csr__button">
						<span class="egsap-csr__button-arrow"><?php echo $arrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</button>
				</nav>
			<?php endif; ?>
		</div>
		<?php
	}
}
