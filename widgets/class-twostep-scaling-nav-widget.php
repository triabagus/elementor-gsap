<?php
namespace Elementor_GSAP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Twostep_Scaling_Nav_Widget extends Widget_Base {

	public function get_name() {
		return 'twostep_scaling_nav';
	}

	public function get_title() {
		return __( 'Two-step Scaling Navigation', 'elementor-gsap' );
	}

	public function get_icon() {
		return 'eicon-nav-menu';
	}

	public function get_categories() {
		return [ 'elementor-gsap-nav' ];
	}

	public function get_keywords() {
		return [ 'nav', 'menu', 'twostep', 'two-step', 'scale', 'expanding', 'osmo' ];
	}

	public function get_script_depends() {
		return [ 'elementor-twostep-scaling-nav' ];
	}

	public function get_style_depends() {
		return [ 'elementor-twostep-scaling-nav' ];
	}

	public function sanitize_custom_svg( $svg ) {
		if ( empty( $svg ) ) {
			return '';
		}
		$svg = trim( (string) $svg );
		$svg = preg_replace( '#<\s*script[^>]*>.*?<\s*/\s*script\s*>#is', '', $svg );
		$svg = preg_replace( '#<\s*script[^>]*/?>#i', '', $svg );
		$svg = preg_replace( '#<\s*foreignObject[^>]*>.*?<\s*/\s*foreignObject\s*>#is', '', $svg );
		$svg = preg_replace( '#\s+on[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)#i', '', $svg );
		$svg = preg_replace( '#\s+(xlink:href|href)\s*=\s*("|\')?\s*javascript:[^"\'>\s]*("|\')?#i', '', $svg );
		return $svg;
	}

	public function default_logo_svg() {
		return '<svg xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 1457 320" fill="none" class="egsap-tsn__logo-svg"><path d="M511.765 320C566.464 320 614.72 292.501 643.52 250.592C660.064 293.269 702.859 320 759.52 320C800.341 320 834.773 306.955 856.896 285.504L853.707 313.376H922.827L939.371 169.056L977.781 313.376H1046.97L1085.38 169.056L1101.91 313.376H1171.03L1163.63 248.768C1192.27 291.701 1241.13 320 1296.62 320C1384.85 320 1456.38 248.469 1456.38 160.235C1456.38 72 1384.83 0.469287 1296.6 0.469287C1228.14 0.469287 1169.76 43.5413 1147.02 104.043L1135.85 6.62395H1059.47L1012.35 183.659L965.237 6.62395H888.853L878.123 100.224C876.821 72.9919 865.643 48.32 846.357 30.4533C824.864 10.5386 794.837 0.0106201 759.509 0.0106201C726.411 0.0106201 697.888 9.43995 677.024 27.2853C661.643 40.448 651.573 57.4399 647.68 76.2879C619.531 30.7839 569.205 0.469287 511.765 0.469287C423.531 0.469287 352 72 352 160.235C352 248.469 423.531 320 511.765 320ZM1296.6 72.3626C1345.13 72.3626 1384.47 111.701 1384.47 160.235C1384.47 208.768 1345.13 248.107 1296.6 248.107C1248.06 248.107 1208.73 208.768 1208.73 160.235C1208.73 111.701 1248.06 72.3626 1296.6 72.3626ZM759.52 66.976C789.515 66.976 807.925 80.864 808.757 104.128L809.013 111.2H876.875L869.877 172.299C866.4 166.699 862.272 161.525 857.461 156.821C841.632 141.376 818.421 130.859 788.459 125.568L748.064 118.336C721.301 113.515 715.819 105.152 715.819 94.0799C715.819 91.3066 717.045 66.9653 759.52 66.9653V66.976ZM730.517 185.493L778.112 194.421C808.843 200.32 812.981 212.789 812.981 224.213C812.981 242.251 792.491 253.451 759.499 253.451C720.32 253.451 705.515 231.349 704.736 212.427L704.448 205.397H665.003C669.216 191.072 671.52 175.925 671.52 160.235C671.52 159.488 671.477 158.741 671.467 157.995C685.653 171.467 705.461 180.864 730.507 185.493H730.517ZM511.765 72.3626C560.299 72.3626 599.637 111.701 599.637 160.235C599.637 208.768 560.299 248.107 511.765 248.107C463.232 248.107 423.893 208.768 423.893 160.235C423.893 111.701 463.232 72.3626 511.765 72.3626Z" fill="#201D1D"></path><path d="M216.48 131.808L287.285 61.0027L258.997 32.7147L188.192 103.52C185.173 106.549 180 104.405 180 100.128V0H140V120.8C140 131.403 131.403 140 120.8 140H0V180H100.128C104.405 180 106.549 185.173 103.52 188.192L32.7253 258.997L61.0133 287.285L131.819 216.48C134.837 213.461 140.011 215.595 140.011 219.872V320H180.011V199.2C180.011 188.597 188.608 180 199.211 180H320.011V140H219.883C215.605 140 213.461 134.827 216.491 131.808H216.48Z" fill="#6840FF"></path></svg>';
	}

	private function render_link_attrs( $link ) {
		if ( empty( $link ) || ! is_array( $link ) ) {
			return ' href="#"';
		}
		$url    = ! empty( $link['url'] ) ? $link['url'] : '#';
		$target = ! empty( $link['is_external'] ) ? ' target="_blank"' : '';
		$rel    = ! empty( $link['nofollow'] ) ? ' rel="nofollow noopener"' : ( ! empty( $link['is_external'] ) ? ' rel="noopener"' : '' );
		return ' href="' . esc_url( $url ) . '"' . $target . $rel;
	}

	protected function register_controls() {

		/* ========================================================= */
		/*                       CONTENT — BAR                        */
		/* ========================================================= */
		$this->start_controls_section( 'content_bar', [
			'label' => __( 'Bar', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'logo_type', [
			'label'   => __( 'Logo Type', 'elementor-gsap' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'image' => __( 'Image', 'elementor-gsap' ),
				'svg'   => __( 'Custom SVG', 'elementor-gsap' ),
			],
			'default' => 'svg',
		] );

		$this->add_control( 'logo_image', [
			'label'     => __( 'Logo Image', 'elementor-gsap' ),
			'type'      => Controls_Manager::MEDIA,
			'default'   => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
			'condition' => [ 'logo_type' => 'image' ],
		] );

		$this->add_control( 'logo_svg', [
			'label'       => __( 'Logo SVG', 'elementor-gsap' ),
			'description' => __( 'Paste inline SVG. Kosongkan untuk pakai logo default.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXTAREA,
			'rows'        => 4,
			'default'     => '',
			'condition'   => [ 'logo_type' => 'svg' ],
		] );

		$this->add_control( 'logo_link', [
			'label'   => __( 'Logo Link', 'elementor-gsap' ),
			'type'    => Controls_Manager::URL,
			'default' => [ 'url' => '#' ],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                    CONTENT — MENU ITEMS                    */
		/* ========================================================= */
		$this->start_controls_section( 'content_menu', [
			'label' => __( 'Menu Items', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$menu_rep = new Repeater();
		$menu_rep->add_control( 'label', [
			'label'   => __( 'Label', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Home',
		] );
		$menu_rep->add_control( 'link', [
			'label'   => __( 'Link', 'elementor-gsap' ),
			'type'    => Controls_Manager::URL,
			'default' => [ 'url' => '#' ],
		] );

		$this->add_control( 'menu_items', [
			'label'       => __( 'Main Menu', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $menu_rep->get_controls(),
			'title_field' => '{{{ label }}}',
			'default'     => [
				[ 'label' => 'Home',      'link' => [ 'url' => '#' ] ],
				[ 'label' => 'Portfolio', 'link' => [ 'url' => '#' ] ],
				[ 'label' => 'About us',  'link' => [ 'url' => '#' ] ],
				[ 'label' => 'Services',  'link' => [ 'url' => '#' ] ],
			],
		] );

		$small_rep = new Repeater();
		$small_rep->add_control( 'label', [
			'label'   => __( 'Label', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Instagram',
		] );
		$small_rep->add_control( 'link', [
			'label'   => __( 'Link', 'elementor-gsap' ),
			'type'    => Controls_Manager::URL,
			'default' => [ 'url' => '#' ],
		] );

		$this->add_control( 'small_items', [
			'label'       => __( 'Small Links (Socials)', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $small_rep->get_controls(),
			'title_field' => '{{{ label }}}',
			'separator'   => 'before',
			'default'     => [
				[ 'label' => 'Instagram', 'link' => [ 'url' => '#' ] ],
				[ 'label' => 'LinkedIn',  'link' => [ 'url' => '#' ] ],
				[ 'label' => 'Twitter/X', 'link' => [ 'url' => '#' ] ],
			],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                      CONTENT — VISUAL                      */
		/* ========================================================= */
		$this->start_controls_section( 'content_visual', [
			'label' => __( 'Visual (Right Column)', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'visual_enable', [
			'label'        => __( 'Show Visual Column', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
			'description'  => __( 'Kolom visual otomatis di-hide pada mobile (≤767px).', 'elementor-gsap' ),
		] );

		$this->add_control( 'visual_image', [
			'label'     => __( 'Image', 'elementor-gsap' ),
			'type'      => Controls_Manager::MEDIA,
			'default'   => [ 'url' => 'https://cdn.prod.website-files.com/6970c1684e330d82d41ba734/6970d4c112ff725efd1230ca_osmo-twostep-nav-image.avif' ],
			'condition' => [ 'visual_enable' => 'yes' ],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                       STYLE — LAYOUT                       */
		/* ========================================================= */
		$this->start_controls_section( 'style_layout', [
			'label' => __( 'Layout', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'z_index', [
			'label'   => __( 'z-index', 'elementor-gsap' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 100,
			'min'     => 0,
			'max'     => 100000,
			'selectors' => [
				'{{WRAPPER}} .egsap-tsn' => '--tsn-z: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'top_offset', [
			'label'      => __( 'Top Offset', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 5, 'step' => 0.05 ],
				'px' => [ 'min' => 0, 'max' => 80 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 1.25 ],
			'selectors'  => [
				'{{WRAPPER}} .egsap-tsn' => '--tsn-top-offset: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'side_offset', [
			'label'      => __( 'Side Offset', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 5, 'step' => 0.05 ],
				'px' => [ 'min' => 0, 'max' => 80 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 1.25 ],
			'selectors'  => [
				'{{WRAPPER}} .egsap-tsn' => '--tsn-side-offset: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'container_max', [
			'label'       => __( 'Container Max Width (Expanded)', 'elementor-gsap' ),
			'description' => __( 'Batas lebar maksimum saat menu aktif (expand).', 'elementor-gsap' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'em', 'px' ],
			'range'       => [
				'em' => [ 'min' => 20, 'max' => 80 ],
				'px' => [ 'min' => 320, 'max' => 1280 ],
			],
			'default'     => [ 'unit' => 'em', 'size' => 48 ],
			'selectors'   => [
				'{{WRAPPER}} .egsap-tsn' => '--tsn-container-max: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'bar_collapsed', [
			'label'       => __( 'Bar Width (Collapsed)', 'elementor-gsap' ),
			'description' => __( 'Lebar pill bar saat menu belum aktif.', 'elementor-gsap' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'em', 'px' ],
			'range'       => [
				'em' => [ 'min' => 15, 'max' => 40 ],
				'px' => [ 'min' => 240, 'max' => 640 ],
			],
			'default'     => [ 'unit' => 'em', 'size' => 25 ],
			'selectors'   => [
				'{{WRAPPER}} .egsap-tsn' => '--tsn-bar-collapsed: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'bar_height', [
			'label'      => __( 'Bar Height', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 2.5, 'max' => 8, 'step' => 0.1 ],
				'px' => [ 'min' => 40, 'max' => 120 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 4 ],
			'selectors'  => [
				'{{WRAPPER}} .egsap-tsn' => '--tsn-bar-height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'bar_radius', [
			'label'      => __( 'Bar Border Radius', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 3, 'step' => 0.05 ],
				'px' => [ 'min' => 0, 'max' => 40 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 0.5 ],
			'selectors'  => [
				'{{WRAPPER}} .egsap-tsn' => '--tsn-bar-radius: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'logo_width', [
			'label'      => __( 'Logo Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 3, 'max' => 12, 'step' => 0.1 ],
				'px' => [ 'min' => 40, 'max' => 200 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 6 ],
			'selectors'  => [
				'{{WRAPPER}} .egsap-tsn' => '--tsn-logo-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                       STYLE — COLORS                       */
		/* ========================================================= */
		$this->start_controls_section( 'style_colors', [
			'label' => __( 'Colors', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'bar_bg', [
			'label'   => __( 'Bar Background', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#F2F2F2',
			'selectors' => [
				'{{WRAPPER}} .egsap-tsn' => '--tsn-bar-bg: {{VALUE}};',
			],
		] );

		$this->add_control( 'text_color', [
			'label'   => __( 'Text Color', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#201D1D',
			'selectors' => [
				'{{WRAPPER}} .egsap-tsn' => '--tsn-text: {{VALUE}};',
			],
		] );

		$this->add_control( 'toggle_color', [
			'label'   => __( 'Toggle Line Color', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#131313',
			'selectors' => [
				'{{WRAPPER}} .egsap-tsn' => '--tsn-toggle: {{VALUE}};',
			],
		] );

		$this->add_control( 'line_color', [
			'label'   => __( 'Divider Line Color', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => 'rgba(0,0,0,0.1)',
			'selectors' => [
				'{{WRAPPER}} .egsap-tsn' => '--tsn-line: {{VALUE}};',
			],
		] );

		$this->add_control( 'backdrop_color', [
			'label'   => __( 'Backdrop Color', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => 'rgba(0,0,0,0.3)',
			'selectors' => [
				'{{WRAPPER}} .egsap-tsn' => '--tsn-backdrop: {{VALUE}};',
			],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                        STYLE — HOVER                       */
		/* ========================================================= */
		$this->start_controls_section( 'style_hover', [
			'label' => __( 'Hover', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'menu_link_hover_color', [
			'label'   => __( 'Main Menu Hover Color', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#6840FF',
			'selectors' => [
				'{{WRAPPER}} .egsap-tsn' => '--tsn-link-hover: {{VALUE}};',
			],
		] );

		$this->add_control( 'small_link_hover_color', [
			'label'       => __( 'Small Link Hover Color', 'elementor-gsap' ),
			'description' => __( 'Opacity juga otomatis naik ke 100% saat hover.', 'elementor-gsap' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => '#6840FF',
			'selectors'   => [
				'{{WRAPPER}} .egsap-tsn' => '--tsn-eyebrow-hover: {{VALUE}};',
			],
		] );

		$this->add_control( 'hover_underline', [
			'label'        => __( 'Hover Underline', 'elementor-gsap' ),
			'description'  => __( 'Animated underline yang draw dari kiri ke kanan saat hover (aktif untuk main menu & small links).', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'On', 'elementor-gsap' ),
			'label_off'    => __( 'Off', 'elementor-gsap' ),
			'return_value' => 'yes',
			'default'      => '',
			'separator'    => 'before',
		] );

		$this->add_control( 'underline_color', [
			'label'     => __( 'Underline Color', 'elementor-gsap' ),
			'description' => __( 'Kosongkan untuk pakai warna text link (currentColor).', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .egsap-tsn' => '--tsn-underline-color: {{VALUE}};',
			],
			'condition' => [ 'hover_underline' => 'yes' ],
		] );

		$this->add_control( 'underline_thickness', [
			'label'      => __( 'Underline Thickness', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em' ],
			'range'      => [
				'px' => [ 'min' => 1, 'max' => 6 ],
				'em' => [ 'min' => 0.02, 'max' => 0.2, 'step' => 0.01 ],
			],
			'default'    => [ 'unit' => 'px', 'size' => 1 ],
			'selectors'  => [
				'{{WRAPPER}} .egsap-tsn' => '--tsn-underline-thickness: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [ 'hover_underline' => 'yes' ],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                     STYLE — TYPOGRAPHY                     */
		/* ========================================================= */
		$this->start_controls_section( 'style_typography', [
			'label' => __( 'Typography', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'menu_size', [
			'label'      => __( 'Menu Font Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px', 'rem' ],
			'range'      => [
				'em'  => [ 'min' => 1, 'max' => 4, 'step' => 0.05 ],
				'px'  => [ 'min' => 16, 'max' => 72 ],
				'rem' => [ 'min' => 1, 'max' => 4, 'step' => 0.05 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 2.125 ],
			'selectors'  => [
				'{{WRAPPER}} .egsap-tsn' => '--tsn-menu-size: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'menu_typography',
			'label'    => __( 'Menu Typography', 'elementor-gsap' ),
			'description' => __( 'Font-size sudah diatur di atas (biar responsive). Ganti font-family / weight di sini.', 'elementor-gsap' ),
			'selector' => '{{WRAPPER}} .egsap-tsn__link-span',
		] );

		$this->add_responsive_control( 'eyebrow_size', [
			'label'      => __( 'Small Link Font Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px', 'rem' ],
			'range'      => [
				'em' => [ 'min' => 0.75, 'max' => 1.5, 'step' => 0.05 ],
				'px' => [ 'min' => 12, 'max' => 20 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 1 ],
			'selectors'  => [
				'{{WRAPPER}} .egsap-tsn' => '--tsn-eyebrow-size: {{SIZE}}{{UNIT}};',
			],
			'separator'  => 'before',
		] );

		$this->add_control( 'eyebrow_opacity', [
			'label'   => __( 'Small Link Opacity', 'elementor-gsap' ),
			'type'    => Controls_Manager::NUMBER,
			'min'     => 0,
			'max'     => 1,
			'step'    => 0.05,
			'default' => 0.7,
			'selectors' => [
				'{{WRAPPER}} .egsap-tsn' => '--tsn-eyebrow-opacity: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'eyebrow_typography',
			'label'    => __( 'Small Link Typography', 'elementor-gsap' ),
			'selector' => '{{WRAPPER}} .egsap-tsn__link-eyebrow',
		] );

		$this->end_controls_section();
	}

	private function build_style_attr( $s ) {
		$m = [];
		$color_map = [
			'z_index'                => '--tsn-z',
			'bar_bg'                 => '--tsn-bar-bg',
			'text_color'             => '--tsn-text',
			'toggle_color'           => '--tsn-toggle',
			'line_color'             => '--tsn-line',
			'backdrop_color'         => '--tsn-backdrop',
			'eyebrow_opacity'        => '--tsn-eyebrow-opacity',
			'menu_link_hover_color'  => '--tsn-link-hover',
			'small_link_hover_color' => '--tsn-eyebrow-hover',
			'underline_color'        => '--tsn-underline-color',
		];
		foreach ( $color_map as $key => $var ) {
			if ( isset( $s[ $key ] ) && '' !== $s[ $key ] ) {
				$m[] = $var . ': ' . $s[ $key ] . ';';
			}
		}
		$slider_map = [
			'top_offset'          => '--tsn-top-offset',
			'side_offset'         => '--tsn-side-offset',
			'container_max'       => '--tsn-container-max',
			'bar_collapsed'       => '--tsn-bar-collapsed',
			'bar_height'          => '--tsn-bar-height',
			'bar_radius'          => '--tsn-bar-radius',
			'logo_width'          => '--tsn-logo-width',
			'menu_size'           => '--tsn-menu-size',
			'eyebrow_size'        => '--tsn-eyebrow-size',
			'underline_thickness' => '--tsn-underline-thickness',
		];
		foreach ( $slider_map as $key => $var ) {
			if ( isset( $s[ $key ]['size'], $s[ $key ]['unit'] ) && '' !== $s[ $key ]['size'] ) {
				$m[] = $var . ': ' . $s[ $key ]['size'] . $s[ $key ]['unit'] . ';';
			}
		}
		if ( empty( $m ) ) return '';
		return ' style="' . esc_attr( implode( ' ', $m ) ) . '"';
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$menu_items  = ! empty( $s['menu_items'] )  && is_array( $s['menu_items'] )  ? $s['menu_items']  : [];
		$small_items = ! empty( $s['small_items'] ) && is_array( $s['small_items'] ) ? $s['small_items'] : [];

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

		$style_attr     = $is_edit ? '' : $this->build_style_attr( $s );
		$editor_flag    = $is_edit ? ' data-egsap-tsn-editor="1"' : '';
		$underline_flag = ( 'yes' === ( $s['hover_underline'] ?? '' ) ) ? ' data-egsap-tsn-underline="yes"' : '';

		/* Logo HTML */
		$logo_html = '';
		if ( 'image' === ( $s['logo_type'] ?? 'svg' ) && ! empty( $s['logo_image']['url'] ) ) {
			$logo_html = '<img src="' . esc_url( $s['logo_image']['url'] ) . '" alt="" />';
		} else {
			$svg = ! empty( $s['logo_svg'] ) ? $this->sanitize_custom_svg( $s['logo_svg'] ) : $this->default_logo_svg();
			$logo_html = $svg;
		}

		$show_visual = ( 'yes' === ( $s['visual_enable'] ?? '' ) ) && ! empty( $s['visual_image']['url'] );
		?>
		<nav
			class="egsap-tsn"
			data-egsap-tsn
			data-egsap-tsn-status="not-active"
			<?php echo $editor_flag;    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php echo $underline_flag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php echo $style_attr;     // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		>
			<button type="button" data-egsap-tsn-toggle="close" aria-label="close menu" class="egsap-tsn__bg"></button>
			<div class="egsap-tsn__wrap">
				<div class="egsap-tsn__width">
					<div class="egsap-tsn__bar">
						<div class="egsap-tsn__back">
							<div class="egsap-tsn__back-bg"></div>
						</div>
						<div class="egsap-tsn__top">
							<a<?php echo $this->render_link_attrs( $s['logo_link'] ?? [] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="egsap-tsn__logo">
								<?php echo $logo_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</a>
							<button type="button" data-egsap-tsn-toggle="toggle" aria-label="toggle menu" class="egsap-tsn__toggle">
								<span class="egsap-tsn__toggle-bar"></span>
								<span class="egsap-tsn__toggle-bar"></span>
							</button>
							<div class="egsap-tsn__top-line"></div>
						</div>
						<div class="egsap-tsn__bottom">
							<div class="egsap-tsn__bottom-overflow">
								<div class="egsap-tsn__bottom-inner">
									<div class="egsap-tsn__bottom-row">
										<div class="egsap-tsn__bottom-col">
											<div class="egsap-tsn__info">
												<?php if ( ! empty( $menu_items ) ) : ?>
													<ul class="egsap-tsn__ul">
														<?php foreach ( $menu_items as $item ) : ?>
															<li class="egsap-tsn__li">
																<a<?php echo $this->render_link_attrs( $item['link'] ?? [] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="egsap-tsn__link">
																	<span class="egsap-tsn__link-span"><?php echo esc_html( $item['label'] ?? '' ); ?></span>
																</a>
															</li>
														<?php endforeach; ?>
													</ul>
												<?php endif; ?>

												<?php if ( ! empty( $small_items ) ) : ?>
													<ul class="egsap-tsn__ul is--small">
														<?php foreach ( $small_items as $item ) : ?>
															<li class="egsap-tsn__li">
																<a<?php echo $this->render_link_attrs( $item['link'] ?? [] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="egsap-tsn__link">
																	<span class="egsap-tsn__link-eyebrow"><?php echo esc_html( $item['label'] ?? '' ); ?></span>
																</a>
															</li>
														<?php endforeach; ?>
													</ul>
												<?php endif; ?>
											</div>
										</div>

										<?php if ( $show_visual ) : ?>
											<div class="egsap-tsn__bottom-col is--visual">
												<div class="egsap-tsn__visual">
													<img src="<?php echo esc_url( $s['visual_image']['url'] ); ?>" alt="" class="egsap-tsn__visual-img" />
												</div>
											</div>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</nav>
		<?php
	}
}
