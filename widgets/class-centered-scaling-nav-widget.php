<?php
namespace Elementor_GSAP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Centered_Scaling_Nav_Widget extends Widget_Base {

	public function get_name() {
		return 'centered_scaling_nav';
	}

	public function get_title() {
		return __( 'Centered Scaling Navigation Bar', 'elementor-gsap' );
	}

	public function get_icon() {
		return 'eicon-nav-menu';
	}

	public function get_categories() {
		return [ 'elementor-gsap-nav' ];
	}

	public function get_keywords() {
		return [ 'nav', 'menu', 'centered', 'scaling', 'pill', 'marquee', 'osmo' ];
	}

	public function get_script_depends() {
		return [ 'elementor-centered-scaling-nav' ];
	}

	public function get_style_depends() {
		return [ 'elementor-centered-scaling-nav' ];
	}

	public function sanitize_custom_svg( $svg ) {
		if ( empty( $svg ) ) return '';
		$svg = trim( (string) $svg );
		$svg = preg_replace( '#<\s*script[^>]*>.*?<\s*/\s*script\s*>#is', '', $svg );
		$svg = preg_replace( '#<\s*script[^>]*/?>#i', '', $svg );
		$svg = preg_replace( '#<\s*foreignObject[^>]*>.*?<\s*/\s*foreignObject\s*>#is', '', $svg );
		$svg = preg_replace( '#\s+on[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)#i', '', $svg );
		$svg = preg_replace( '#\s+(xlink:href|href)\s*=\s*("|\')?\s*javascript:[^"\'>\s]*("|\')?#i', '', $svg );
		return $svg;
	}

	public function default_logo_svg() {
		return '<svg xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 120 28" fill="none" class="egsap-csn__logo-svg"><path d="M109.059 9.6298C108.636 10.051 107.912 9.7527 107.912 9.15698V0.626465H105.226V10.6566C105.226 11.7645 104.324 12.6626 103.211 12.6626H93.1377V15.3373H101.705C102.303 15.3373 102.603 16.0575 102.18 16.4788L96.2174 22.4157L98.1169 24.307L104.08 18.3701C104.501 17.9501 105.222 18.2454 105.226 18.8376V27.3734L107.912 27.3735L107.912 17.3433C107.912 16.2354 108.814 15.3373 109.927 15.3373H120.001V12.6626H111.428C110.835 12.6583 110.538 11.9453 110.954 11.5248L110.958 11.5211L116.921 5.58416L115.021 3.69288L109.059 9.6298Z" fill="currentColor"></path><path d="M10.8432 25.4864C4.23224 25.4864 0 20.6572 0 14.044C0 7.43085 4.23224 2.63245 10.8432 2.63245C17.4541 2.63245 21.6863 7.43085 21.6863 14.044C21.6863 20.6572 17.4541 25.4864 10.8432 25.4864ZM3.55261 14.044C3.55261 18.5656 5.62239 22.5643 10.8432 22.5643C16.064 22.5643 18.1337 18.5656 18.1337 14.044C18.1337 9.52246 16.064 5.55455 10.8432 5.55455C5.62239 5.55455 3.55261 9.52246 3.55261 14.044Z" fill="currentColor"></path><path d="M30.6593 25.4864C25.871 25.4864 23.4923 23.0257 23.3997 19.8267H26.458C26.5816 21.6415 27.6937 23.0564 30.6284 23.0564C33.2852 23.0564 33.9957 21.8876 33.9957 20.7495C33.9957 18.7809 31.895 18.5656 29.8561 18.135C27.1067 17.489 23.9557 16.6893 23.9557 13.4289C23.9557 10.7221 26.1491 8.90728 29.9488 8.90728C34.2737 8.90728 36.3435 11.2142 36.5597 13.921H33.5014C33.2852 12.7214 32.6364 11.3372 30.0106 11.3372C27.9717 11.3372 27.1067 12.137 27.1067 13.3058C27.1067 14.936 28.8676 15.0898 31.0918 15.582C33.9957 16.2587 37.1467 17.0892 37.1467 20.5957C37.1467 23.6408 34.7989 25.4864 30.6593 25.4864Z" fill="currentColor"></path><path d="M48.9871 14.9976C48.9871 12.906 48.5546 11.491 46.2377 11.491C43.9826 11.491 42.5615 13.0597 42.5615 15.4282V25.0558H39.5341V9.36867H42.5615V11.3372H42.6233C43.4574 10.1376 44.9093 8.90728 47.288 8.90728C49.4814 8.90728 50.8406 9.89157 51.4585 11.6448H51.5203C52.6633 10.1376 54.2697 8.90728 56.6793 8.90728C59.8612 8.90728 61.4676 10.8143 61.4676 14.1671V25.0558H58.4401V14.9976C58.4401 12.906 58.0076 11.491 55.6907 11.491C53.4356 11.491 52.0145 13.0597 52.0145 15.4282V25.0558H48.9871V14.9976Z" fill="currentColor"></path><path d="M71.8283 25.5171C66.9164 25.5171 63.9508 22.1337 63.9508 17.2122C63.9508 12.3215 66.9164 8.87652 71.8592 8.87652C76.7401 8.87652 79.7058 12.2908 79.7058 17.1815C79.7058 22.1029 76.7402 25.5171 71.8283 25.5171ZM67.1018 17.2122C67.1018 20.4727 68.5846 23.0257 71.8592 23.0257C75.072 23.0257 76.5548 20.4727 76.5548 17.2122C76.5548 13.921 75.072 11.3988 71.8592 11.3988C68.5846 11.3988 67.1018 13.921 67.1018 17.2122Z" fill="currentColor"></path></svg>';
	}

	private function render_link_attrs( $link ) {
		if ( empty( $link ) || ! is_array( $link ) ) return ' href="#"';
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
			'label' => __( 'Bar (Logo + Toggle)', 'elementor-gsap' ),
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
			'description' => __( 'Paste inline SVG. Kosongkan untuk pakai default.', 'elementor-gsap' ),
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
			'default' => 'Menu',
		] );
		$menu_rep->add_control( 'link', [
			'label'   => __( 'Link', 'elementor-gsap' ),
			'type'    => Controls_Manager::URL,
			'default' => [ 'url' => '#' ],
		] );
		$menu_rep->add_control( 'is_current', [
			'label'        => __( 'Mark as Current', 'elementor-gsap' ),
			'description'  => __( 'Underline item fully opaque untuk halaman aktif.', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => '',
		] );

		$this->add_control( 'menu_items', [
			'label'       => __( 'Menu Items', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $menu_rep->get_controls(),
			'title_field' => '{{{ label }}}{{ is_current === "yes" ? " • current" : "" }}',
			'default'     => [
				[ 'label' => 'Home',         'link' => [ 'url' => '#' ] ],
				[ 'label' => 'Portfolio',    'link' => [ 'url' => '#' ] ],
				[ 'label' => 'About us',     'link' => [ 'url' => '#' ] ],
				[ 'label' => 'Our services', 'link' => [ 'url' => '#' ] ],
				[ 'label' => 'Approach',     'link' => [ 'url' => '#' ] ],
			],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                     CONTENT — BANNER                       */
		/* ========================================================= */
		$this->start_controls_section( 'content_banner', [
			'label' => __( 'Marquee Banner', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'banner_enable', [
			'label'        => __( 'Show Banner', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'banner_text', [
			'label'       => __( 'Banner Text', 'elementor-gsap' ),
			'description' => __( 'Text yang di-repeat di marquee (uppercase otomatis).', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => 'Contact us',
			'condition'   => [ 'banner_enable' => 'yes' ],
		] );

		$this->add_control( 'banner_link', [
			'label'     => __( 'Banner Link', 'elementor-gsap' ),
			'type'      => Controls_Manager::URL,
			'default'   => [ 'url' => '#' ],
			'condition' => [ 'banner_enable' => 'yes' ],
		] );

		$this->add_control( 'banner_repeat', [
			'label'       => __( 'Repeat Count Per List', 'elementor-gsap' ),
			'description' => __( 'Berapa kali text diulang per list (2 list rendered untuk seamless loop). Naikkan untuk viewport lebar.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 2,
			'max'         => 20,
			'default'     => 5,
			'condition'   => [ 'banner_enable' => 'yes' ],
		] );

		$this->add_control( 'marquee_speed', [
			'label'       => __( 'Marquee Speed (seconds)', 'elementor-gsap' ),
			'description' => __( 'Durasi 1 siklus animasi. Nilai kecil = lebih cepat.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 5,
			'max'         => 120,
			'step'        => 1,
			'default'     => 20,
			'selectors'   => [ '{{WRAPPER}} .egsap-csn' => '--csn-marquee-speed: {{VALUE}}s;' ],
			'condition'   => [ 'banner_enable' => 'yes' ],
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
			'default' => 500,
			'min'     => 0,
			'max'     => 100000,
			'selectors' => [ '{{WRAPPER}} .egsap-csn' => '--csn-z: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'top_offset', [
			'label'      => __( 'Top Offset (Desktop)', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 0, 'max' => 6, 'step' => 0.05 ], 'px' => [ 'min' => 0, 'max' => 100 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 2 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-csn' => '--csn-top: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'side_offset', [
			'label'       => __( 'Side/Top Offset (Mobile ≤767px)', 'elementor-gsap' ),
			'description' => __( 'Bar full-width di mobile — offset di kiri/kanan/atas.', 'elementor-gsap' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'em', 'px' ],
			'range'       => [ 'em' => [ 'min' => 0, 'max' => 3, 'step' => 0.05 ], 'px' => [ 'min' => 0, 'max' => 48 ] ],
			'default'     => [ 'unit' => 'em', 'size' => 1 ],
			'selectors'   => [ '{{WRAPPER}} .egsap-csn' => '--csn-side: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'bar_width', [
			'label'      => __( 'Bar Width (Collapsed & Expanded)', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 18, 'max' => 60 ], 'px' => [ 'min' => 240, 'max' => 900 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 30 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-csn' => '--csn-bar-width: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'bar_radius', [
			'label'      => __( 'Bar Radius', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 0, 'max' => 3, 'step' => 0.05 ], 'px' => [ 'min' => 0, 'max' => 40 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 0.75 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-csn' => '--csn-bar-radius: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'logo_width', [
			'label'      => __( 'Logo Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [ 'em' => [ 'min' => 3, 'max' => 15, 'step' => 0.1 ], 'px' => [ 'min' => 40, 'max' => 200 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 7.5 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-csn' => '--csn-logo-width: {{SIZE}}{{UNIT}};' ],
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
			'default' => '#FFFFFF',
			'selectors' => [ '{{WRAPPER}} .egsap-csn' => '--csn-bar-bg: {{VALUE}};' ],
		] );

		$this->add_control( 'text_color', [
			'label'   => __( 'Text Color (Logo + Menu)', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#131313',
			'selectors' => [ '{{WRAPPER}} .egsap-csn' => '--csn-text: {{VALUE}};' ],
		] );

		$this->add_control( 'toggle_color', [
			'label'   => __( 'Toggle Bar Color', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#131313',
			'selectors' => [ '{{WRAPPER}} .egsap-csn' => '--csn-toggle: {{VALUE}};' ],
		] );

		$this->add_control( 'link_underline_color', [
			'label'   => __( 'Menu Link Underline Color', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => 'rgba(19,19,19,0.2)',
			'selectors' => [ '{{WRAPPER}} .egsap-csn' => '--csn-link-underline: {{VALUE}};' ],
		] );

		$this->add_control( 'backdrop_color', [
			'label'   => __( 'Backdrop Color', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#000000',
			'selectors' => [ '{{WRAPPER}} .egsap-csn' => '--csn-backdrop: {{VALUE}};' ],
			'separator' => 'before',
		] );

		$this->add_control( 'backdrop_opacity', [
			'label'   => __( 'Backdrop Opacity', 'elementor-gsap' ),
			'type'    => Controls_Manager::NUMBER,
			'min'     => 0,
			'max'     => 1,
			'step'    => 0.05,
			'default' => 0.15,
			'selectors' => [ '{{WRAPPER}} .egsap-csn' => '--csn-backdrop-opacity: {{VALUE}};' ],
		] );

		$this->add_control( 'banner_bg', [
			'label'   => __( 'Banner Background', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#FF4C24',
			'selectors' => [ '{{WRAPPER}} .egsap-csn' => '--csn-banner-bg: {{VALUE}};' ],
			'separator' => 'before',
		] );

		$this->add_control( 'banner_bg_hover', [
			'label'   => __( 'Banner Background (Hover)', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => 'rgba(255,76,36,0.9)',
			'selectors' => [ '{{WRAPPER}} .egsap-csn' => '--csn-banner-bg-hover: {{VALUE}};' ],
		] );

		$this->add_control( 'banner_text_color', [
			'label'   => __( 'Banner Text Color', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#FFFFFF',
			'selectors' => [ '{{WRAPPER}} .egsap-csn' => '--csn-banner-text: {{VALUE}};' ],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                     STYLE — TYPOGRAPHY                     */
		/* ========================================================= */
		$this->start_controls_section( 'style_typography', [
			'label' => __( 'Typography', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'font_family', [
			'label'       => __( 'Base Font Family', 'elementor-gsap' ),
			'description' => __( 'Default <code>PP Neue Montreal, Arial, sans-serif</code>. Ganti kalau perlu.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => "'PP Neue Montreal', Arial, sans-serif",
			'selectors'   => [ '{{WRAPPER}} .egsap-csn' => '--csn-font: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'menu_size', [
			'label'      => __( 'Menu Font Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px', 'rem' ],
			'range'      => [
				'em'  => [ 'min' => 1, 'max' => 4, 'step' => 0.05 ],
				'px'  => [ 'min' => 14, 'max' => 60 ],
				'rem' => [ 'min' => 1, 'max' => 4, 'step' => 0.05 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 2 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-csn' => '--csn-menu-size: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'        => 'menu_typography',
			'label'       => __( 'Menu Typography', 'elementor-gsap' ),
			'description' => __( 'Font-size sudah diatur di atas. Ganti font-family / weight di sini.', 'elementor-gsap' ),
			'selector'    => '{{WRAPPER}} .egsap-csn__link-text',
		] );

		$this->add_responsive_control( 'banner_size', [
			'label'      => __( 'Banner Text Font Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px', 'rem' ],
			'range'      => [ 'em' => [ 'min' => 0.5, 'max' => 2, 'step' => 0.05 ], 'px' => [ 'min' => 10, 'max' => 32 ] ],
			'default'    => [ 'unit' => 'em', 'size' => 0.875 ],
			'selectors'  => [ '{{WRAPPER}} .egsap-csn' => '--csn-banner-size: {{SIZE}}{{UNIT}};' ],
			'separator'  => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'banner_typography',
			'label'    => __( 'Banner Typography', 'elementor-gsap' ),
			'selector' => '{{WRAPPER}} .egsap-csn__banner-text',
		] );

		$this->end_controls_section();
	}

	private function build_style_attr( $s ) {
		$m = [];
		$color_map = [
			'z_index'              => '--csn-z',
			'font_family'          => '--csn-font',
			'bar_bg'               => '--csn-bar-bg',
			'text_color'           => '--csn-text',
			'toggle_color'         => '--csn-toggle',
			'link_underline_color' => '--csn-link-underline',
			'backdrop_color'       => '--csn-backdrop',
			'backdrop_opacity'     => '--csn-backdrop-opacity',
			'banner_bg'            => '--csn-banner-bg',
			'banner_bg_hover'      => '--csn-banner-bg-hover',
			'banner_text_color'    => '--csn-banner-text',
		];
		foreach ( $color_map as $key => $var ) {
			if ( isset( $s[ $key ] ) && '' !== $s[ $key ] ) {
				$m[] = $var . ': ' . $s[ $key ] . ';';
			}
		}
		if ( isset( $s['marquee_speed'] ) && '' !== $s['marquee_speed'] ) {
			$m[] = '--csn-marquee-speed: ' . floatval( $s['marquee_speed'] ) . 's;';
		}
		$slider_map = [
			'top_offset'  => '--csn-top',
			'side_offset' => '--csn-side',
			'bar_width'   => '--csn-bar-width',
			'bar_radius'  => '--csn-bar-radius',
			'logo_width'  => '--csn-logo-width',
			'menu_size'   => '--csn-menu-size',
			'banner_size' => '--csn-banner-size',
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

		$menu_items = ! empty( $s['menu_items'] ) && is_array( $s['menu_items'] ) ? $s['menu_items'] : [];

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

		$style_attr  = $is_edit ? '' : $this->build_style_attr( $s );
		$editor_flag = $is_edit ? ' data-egsap-csn-editor="1"' : '';

		/* Logo HTML */
		$logo_html = '';
		if ( 'image' === ( $s['logo_type'] ?? 'svg' ) && ! empty( $s['logo_image']['url'] ) ) {
			$logo_html = '<img src="' . esc_url( $s['logo_image']['url'] ) . '" alt="" />';
		} else {
			$svg = ! empty( $s['logo_svg'] ) ? $this->sanitize_custom_svg( $s['logo_svg'] ) : $this->default_logo_svg();
			$logo_html = $svg;
		}

		$show_banner = ( 'yes' === ( $s['banner_enable'] ?? '' ) ) && ! empty( $s['banner_text'] );
		$banner_text = $s['banner_text'] ?? '';
		$banner_reps = max( 2, intval( $s['banner_repeat'] ?? 5 ) );
		?>
		<nav
			class="egsap-csn"
			data-egsap-csn
			data-egsap-csn-status="not-active"
			<?php echo $editor_flag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php echo $style_attr;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		>
			<button type="button" data-egsap-csn-toggle="close" aria-label="close menu" class="egsap-csn__backdrop"></button>
			<div class="egsap-csn__bar">
				<div class="egsap-csn__bar-bg"></div>
				<div class="egsap-csn__header">
					<a<?php echo $this->render_link_attrs( $s['logo_link'] ?? [] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="egsap-csn__logo">
						<?php echo $logo_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</a>
					<button
						type="button"
						data-egsap-csn-toggle="toggle"
						aria-label="toggle menu"
						class="egsap-csn__toggle"
					>
						<span class="egsap-csn__toggle-bar"></span>
						<span class="egsap-csn__toggle-bar"></span>
					</button>
				</div>
				<div class="egsap-csn__content">
					<div class="egsap-csn__inner">
						<?php if ( ! empty( $menu_items ) ) : ?>
							<ul class="egsap-csn__ul">
								<?php foreach ( $menu_items as $item ) :
									$label   = isset( $item['label'] ) ? $item['label'] : '';
									$link    = $this->render_link_attrs( $item['link'] ?? [] );
									$current = ( 'yes' === ( $item['is_current'] ?? '' ) ) ? ' aria-current="page"' : '';
									?>
									<div data-egsap-csn-item class="egsap-csn__li">
										<a<?php echo $link;    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo $current; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="egsap-csn__link">
											<p class="egsap-csn__link-text"><?php echo esc_html( $label ); ?></p>
										</a>
									</div>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>

						<?php if ( $show_banner ) : ?>
							<div data-egsap-csn-item class="egsap-csn__banner-wrap">
								<a<?php echo $this->render_link_attrs( $s['banner_link'] ?? [] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="egsap-csn__banner">
									<div class="egsap-csn__banner-row">
										<?php for ( $list = 0; $list < 2; $list++ ) : ?>
											<div data-egsap-csn-marquee class="egsap-csn__banner-item">
												<?php for ( $r = 0; $r < $banner_reps; $r++ ) : ?>
													<div class="egsap-csn__banner-inner">
														<p class="egsap-csn__banner-text"><?php echo esc_html( $banner_text ); ?></p>
													</div>
												<?php endfor; ?>
											</div>
										<?php endfor; ?>
									</div>
								</a>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</nav>
		<?php
	}
}
