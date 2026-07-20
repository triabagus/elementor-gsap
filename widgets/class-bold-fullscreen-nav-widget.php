<?php
namespace Elementor_GSAP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Bold_Fullscreen_Nav_Widget extends Widget_Base {

	public function get_name() {
		return 'bold_fullscreen_nav';
	}

	public function get_title() {
		return __( 'Bold Full Screen Navigation', 'elementor-gsap' );
	}

	public function get_icon() {
		return 'eicon-nav-menu';
	}

	public function get_categories() {
		return [ 'elementor-gsap-nav' ];
	}

	public function get_keywords() {
		return [ 'nav', 'menu', 'fullscreen', 'bold', 'overlay', 'clip-path', 'osmo' ];
	}

	public function get_script_depends() {
		return [ 'elementor-bold-fullscreen-nav' ];
	}

	public function get_style_depends() {
		return [ 'elementor-bold-fullscreen-nav' ];
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
		return '<svg xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 110 25" fill="none"><path d="M38.6535 24.1686C42.7849 24.1686 46.4296 22.0917 48.6049 18.9263C49.8544 22.1497 53.0867 24.1686 57.3663 24.1686C60.4495 24.1686 63.0501 23.1833 64.721 21.5632L64.4802 23.6683H69.7007L70.9503 12.7679L73.8514 23.6683H79.0769L81.978 12.7679L83.2268 23.6683H88.4473L87.8882 18.7885C90.0514 22.0313 93.7421 24.1686 97.933 24.1686C104.597 24.1686 110 18.766 110 12.1016C110 5.43732 104.596 0.0346429 97.9314 0.0346429C92.7608 0.0346429 88.3515 3.28785 86.6338 7.85749L85.7903 0.499502H80.0211L76.4625 13.8708L72.904 0.499502H67.1348L66.3243 7.56906C66.226 5.51224 65.3817 3.64878 63.9251 2.29932C62.3017 0.795175 60.0338 0 57.3655 0C54.8656 0 52.7113 0.712193 51.1354 2.06004C49.9737 3.05421 49.2131 4.33761 48.9191 5.76119C46.793 2.32429 42.9919 0.0346429 38.6535 0.0346429C31.9892 0.0346429 26.5865 5.43732 26.5865 12.1016C26.5865 18.766 31.9892 24.1686 38.6535 24.1686ZM97.9314 5.46471C101.597 5.46471 104.568 8.43594 104.568 12.1016C104.568 15.7673 101.597 18.7386 97.9314 18.7386C94.2657 18.7386 91.2945 15.7673 91.2945 12.1016C91.2945 8.43594 94.2657 5.46471 97.9314 5.46471ZM57.3663 5.05786C59.6318 5.05786 61.0223 6.10681 61.0852 7.86393L61.1045 8.39808H66.23L65.7015 13.0128C65.4389 12.5899 65.1271 12.1991 64.7637 11.8438C63.5682 10.6773 61.8151 9.88289 59.552 9.48328L56.501 8.93706C54.4797 8.5729 54.0656 7.94127 54.0656 7.10501C54.0656 6.89554 54.1582 5.05705 57.3663 5.05705V5.05786ZM55.1757 14.0094L58.7705 14.6837C61.0916 15.1293 61.4042 16.0711 61.4042 16.9339C61.4042 18.2963 59.8565 19.1422 57.3647 19.1422C54.4055 19.1422 53.2873 17.4729 53.2285 16.0437L53.2067 15.5128H50.2275C50.5457 14.4308 50.7197 13.2868 50.7197 12.1016C50.7197 12.0452 50.7165 11.9889 50.7157 11.9325C51.7872 12.95 53.2833 13.6598 55.1749 14.0094H55.1757ZM38.6535 5.46471C42.3192 5.46471 45.2904 8.43594 45.2904 12.1016C45.2904 15.7673 42.3192 18.7386 38.6535 18.7386C34.9878 18.7386 32.0166 15.7673 32.0166 12.1016C32.0166 8.43594 34.9878 5.46471 38.6535 5.46471Z" fill="currentColor"></path><path d="M16.3506 9.9554L21.6985 4.6075L19.5619 2.47092L14.214 7.81882C13.986 8.04762 13.5953 7.88569 13.5953 7.56262V0H10.5741V9.12397C10.5741 9.92478 9.92476 10.5741 9.12395 10.5741H0V13.5953H7.56261C7.88567 13.5953 8.04761 13.9861 7.8188 14.2141L2.47172 19.5619L4.6083 21.6985L9.95618 16.3506C10.1842 16.1226 10.5749 16.2838 10.5749 16.6068V24.1694H13.5961V15.0455C13.5961 14.2447 14.2454 13.5953 15.0463 13.5953H24.1702V10.5741H16.6076C16.2845 10.5741 16.1226 10.1834 16.3514 9.9554H16.3506Z" fill="#D0FF00"></path></svg>';
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
			'label' => __( 'Bar (Logo + Hamburger)', 'elementor-gsap' ),
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
		$menu_rep->add_control( 'is_current', [
			'label'        => __( 'Mark as Current', 'elementor-gsap' ),
			'description'  => __( 'Pakai warna accent (Current Link Color) untuk highlight halaman aktif.', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => '',
		] );

		$this->add_control( 'menu_items', [
			'label'       => __( 'Menu Items', 'elementor-gsap' ),
			'description' => __( 'Maks. 7 item didukung stagger animation-nya. Lebih dari 7 masih render, tapi tanpa delay tambahan.', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $menu_rep->get_controls(),
			'title_field' => '{{{ label }}}{{ is_current === "yes" ? " • current" : "" }}',
			'default'     => [
				[ 'label' => 'Home',    'link' => [ 'url' => '#' ] ],
				[ 'label' => 'Work',    'link' => [ 'url' => '#' ], 'is_current' => 'yes' ],
				[ 'label' => 'Company', 'link' => [ 'url' => '#' ] ],
				[ 'label' => 'Blog',    'link' => [ 'url' => '#' ] ],
				[ 'label' => 'Contact', 'link' => [ 'url' => '#' ] ],
			],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                      CONTENT — BOTTOM                      */
		/* ========================================================= */
		$this->start_controls_section( 'content_bottom', [
			'label' => __( 'Bottom Row', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'bottom_enable', [
			'label'        => __( 'Show Bottom Row', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$bottom_rep = new Repeater();
		$bottom_rep->add_control( 'text', [
			'label'   => __( 'Text', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Global Community',
		] );
		$bottom_rep->add_control( 'link', [
			'label'       => __( 'Link (Optional)', 'elementor-gsap' ),
			'description' => __( 'Kosongkan kalau ini text saja (bukan link).', 'elementor-gsap' ),
			'type'        => Controls_Manager::URL,
			'default'     => [ 'url' => '' ],
		] );

		$this->add_control( 'bottom_items', [
			'label'       => __( 'Bottom Items', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $bottom_rep->get_controls(),
			'title_field' => '{{{ text }}}',
			'default'     => [
				[ 'text' => 'Global Community', 'link' => [ 'url' => '' ] ],
				[ 'text' => 'hello@osmo.supply', 'link' => [ 'url' => 'mailto:hello@osmo.supply' ] ],
			],
			'condition'   => [ 'bottom_enable' => 'yes' ],
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
				'{{WRAPPER}} .egsap-bfn' => '--bfn-z: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'bar_padding', [
			'label'      => __( 'Bar Padding', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 6, 'step' => 0.05 ],
				'px' => [ 'min' => 0, 'max' => 100 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 2.5 ],
			'selectors'  => [
				'{{WRAPPER}} .egsap-bfn' => '--bfn-bar-padding: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'bottom_padding_y', [
			'label'      => __( 'Bottom Padding (Vertical)', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 5, 'step' => 0.05 ],
				'px' => [ 'min' => 0, 'max' => 80 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 2.25 ],
			'selectors'  => [
				'{{WRAPPER}} .egsap-bfn' => '--bfn-bottom-padding-y: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'bottom_padding_x', [
			'label'      => __( 'Bottom Padding (Horizontal)', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 6, 'step' => 0.05 ],
				'px' => [ 'min' => 0, 'max' => 100 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 2.5 ],
			'selectors'  => [
				'{{WRAPPER}} .egsap-bfn' => '--bfn-bottom-padding-x: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'logo_width', [
			'label'      => __( 'Logo Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 3, 'max' => 20, 'step' => 0.1 ],
				'px' => [ 'min' => 40, 'max' => 320 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 8 ],
			'selectors'  => [
				'{{WRAPPER}} .egsap-bfn' => '--bfn-logo-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'logo_height', [
			'label'      => __( 'Logo Height', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 1, 'max' => 6, 'step' => 0.05 ],
				'px' => [ 'min' => 16, 'max' => 100 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 2 ],
			'selectors'  => [
				'{{WRAPPER}} .egsap-bfn' => '--bfn-logo-height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'hamburger_size', [
			'label'      => __( 'Hamburger Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 2, 'max' => 5, 'step' => 0.05 ],
				'px' => [ 'min' => 32, 'max' => 80 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 3 ],
			'selectors'  => [
				'{{WRAPPER}} .egsap-bfn' => '--bfn-hamburger-size: {{SIZE}}{{UNIT}};',
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

		$this->add_control( 'tile_bg', [
			'label'   => __( 'Tile Background', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#1B372E',
			'selectors' => [
				'{{WRAPPER}} .egsap-bfn' => '--bfn-tile-bg: {{VALUE}};',
			],
		] );

		$this->add_control( 'logo_color', [
			'label'   => __( 'Logo Color', 'elementor-gsap' ),
			'description' => __( 'Berpengaruh pada SVG yang pakai <code>fill="currentColor"</code>.', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#F4F4F4',
			'selectors' => [
				'{{WRAPPER}} .egsap-bfn' => '--bfn-logo-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'hamburger_color', [
			'label'   => __( 'Hamburger Color', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#F4F4F4',
			'selectors' => [
				'{{WRAPPER}} .egsap-bfn' => '--bfn-hamburger-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'link_color', [
			'label'   => __( 'Menu Link Color', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#F4F4F4',
			'selectors' => [
				'{{WRAPPER}} .egsap-bfn' => '--bfn-link-color: {{VALUE}};',
			],
			'separator' => 'before',
		] );

		$this->add_control( 'link_current_color', [
			'label'       => __( 'Current Link Color', 'elementor-gsap' ),
			'description' => __( 'Warna untuk item yang ditandai "Mark as Current".', 'elementor-gsap' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => '#D0FF00',
			'selectors'   => [
				'{{WRAPPER}} .egsap-bfn' => '--bfn-link-current: {{VALUE}};',
			],
		] );

		$this->add_control( 'hover_dim', [
			'label'       => __( 'Hover Dim Opacity', 'elementor-gsap' ),
			'description' => __( 'Opacity untuk item lain saat satu item di-hover (0 = invisible, 1 = normal).', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0,
			'max'         => 1,
			'step'        => 0.05,
			'default'     => 0.15,
			'selectors'   => [
				'{{WRAPPER}} .egsap-bfn' => '--bfn-hover-dim: {{VALUE}};',
			],
		] );

		$this->add_control( 'word_color', [
			'label'   => __( 'Bottom Text Color', 'elementor-gsap' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#F4F4F4',
			'selectors' => [
				'{{WRAPPER}} .egsap-bfn' => '--bfn-word-color: {{VALUE}};',
			],
			'separator' => 'before',
		] );

		$this->add_control( 'word_opacity', [
			'label'   => __( 'Bottom Text Opacity', 'elementor-gsap' ),
			'type'    => Controls_Manager::NUMBER,
			'min'     => 0,
			'max'     => 1,
			'step'    => 0.05,
			'default' => 0.5,
			'selectors' => [
				'{{WRAPPER}} .egsap-bfn' => '--bfn-word-opacity: {{VALUE}};',
			],
		] );

		$this->end_controls_section();

		/* ========================================================= */
		/*                     STYLE — TYPOGRAPHY                     */
		/* ========================================================= */
		$this->start_controls_section( 'style_typography', [
			'label' => __( 'Typography', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'menu_size', [
			'label'       => __( 'Menu Font Size', 'elementor-gsap' ),
			'description' => __( 'Boleh pakai <code>calc()</code>, <code>vw</code>, <code>vh</code>, <code>em</code>, <code>px</code>, atau <code>rem</code>. Default <code>calc(4vw + 4vh)</code> auto-scale mengikuti viewport.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => 'calc(4vw + 4vh)',
			'selectors'   => [
				'{{WRAPPER}} .egsap-bfn' => '--bfn-menu-size: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'        => 'menu_typography',
			'label'       => __( 'Menu Typography', 'elementor-gsap' ),
			'description' => __( 'Font-size sudah diatur di atas — ganti font-family / weight di sini.', 'elementor-gsap' ),
			'selector'    => '{{WRAPPER}} .egsap-bfn__link',
		] );

		$this->add_responsive_control( 'word_size', [
			'label'      => __( 'Bottom Text Font Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px', 'rem' ],
			'range'      => [
				'em' => [ 'min' => 0.75, 'max' => 2, 'step' => 0.05 ],
				'px' => [ 'min' => 12, 'max' => 32 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 1.125 ],
			'selectors'  => [
				'{{WRAPPER}} .egsap-bfn' => '--bfn-word-size: {{SIZE}}{{UNIT}};',
			],
			'separator'  => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'word_typography',
			'label'    => __( 'Bottom Text Typography', 'elementor-gsap' ),
			'selector' => '{{WRAPPER}} .egsap-bfn__word',
		] );

		$this->end_controls_section();
	}

	private function build_style_attr( $s ) {
		$m = [];
		$color_map = [
			'z_index'            => '--bfn-z',
			'tile_bg'            => '--bfn-tile-bg',
			'logo_color'         => '--bfn-logo-color',
			'hamburger_color'    => '--bfn-hamburger-color',
			'link_color'         => '--bfn-link-color',
			'link_current_color' => '--bfn-link-current',
			'hover_dim'          => '--bfn-hover-dim',
			'word_color'         => '--bfn-word-color',
			'word_opacity'       => '--bfn-word-opacity',
			'menu_size'          => '--bfn-menu-size',
		];
		foreach ( $color_map as $key => $var ) {
			if ( isset( $s[ $key ] ) && '' !== $s[ $key ] ) {
				$m[] = $var . ': ' . $s[ $key ] . ';';
			}
		}
		$slider_map = [
			'bar_padding'      => '--bfn-bar-padding',
			'bottom_padding_y' => '--bfn-bottom-padding-y',
			'bottom_padding_x' => '--bfn-bottom-padding-x',
			'logo_width'       => '--bfn-logo-width',
			'logo_height'      => '--bfn-logo-height',
			'hamburger_size'   => '--bfn-hamburger-size',
			'word_size'        => '--bfn-word-size',
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

		$menu_items   = ! empty( $s['menu_items'] )   && is_array( $s['menu_items'] )   ? $s['menu_items']   : [];
		$bottom_items = ! empty( $s['bottom_items'] ) && is_array( $s['bottom_items'] ) ? $s['bottom_items'] : [];
		$show_bottom  = ( 'yes' === ( $s['bottom_enable'] ?? '' ) ) && ! empty( $bottom_items );

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
		$editor_flag = $is_edit ? ' data-egsap-bfn-editor="1"' : '';

		/* Logo HTML */
		$logo_html = '';
		if ( 'image' === ( $s['logo_type'] ?? 'svg' ) && ! empty( $s['logo_image']['url'] ) ) {
			$logo_html = '<img src="' . esc_url( $s['logo_image']['url'] ) . '" alt="" />';
		} else {
			$svg = ! empty( $s['logo_svg'] ) ? $this->sanitize_custom_svg( $s['logo_svg'] ) : $this->default_logo_svg();
			$logo_html = $svg;
		}
		?>
		<nav
			class="egsap-bfn"
			data-egsap-bfn
			data-egsap-bfn-status="not-active"
			<?php echo $editor_flag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php echo $style_attr;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		>
			<div class="egsap-bfn__bar">
				<a<?php echo $this->render_link_attrs( $s['logo_link'] ?? [] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="egsap-bfn__logo">
					<?php echo $logo_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>
				<button
					type="button"
					data-egsap-bfn-toggle="toggle"
					aria-label="toggle navigation"
					class="egsap-bfn__hamburger"
				>
					<span class="egsap-bfn__hamburger-bar"></span>
					<span class="egsap-bfn__hamburger-bar"></span>
					<span class="egsap-bfn__hamburger-bar"></span>
				</button>
			</div>

			<div class="egsap-bfn__tile">
				<?php if ( ! empty( $menu_items ) ) : ?>
					<ul class="egsap-bfn__ul">
						<?php foreach ( $menu_items as $item ) :
							$label   = isset( $item['label'] ) ? $item['label'] : '';
							$link    = $this->render_link_attrs( $item['link'] ?? [] );
							$current = ( 'yes' === ( $item['is_current'] ?? '' ) ) ? ' is--current' : '';
							?>
							<li class="egsap-bfn__li">
								<a<?php echo $link; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="egsap-bfn__link<?php echo esc_attr( $current ); ?>">
									<span class="egsap-bfn__link-text"><?php echo esc_html( $label ); ?></span>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<?php if ( $show_bottom ) : ?>
					<div class="egsap-bfn__bottom">
						<?php foreach ( $bottom_items as $bi ) :
							$text = isset( $bi['text'] ) ? $bi['text'] : '';
							$url  = ! empty( $bi['link']['url'] ) ? $bi['link']['url'] : '';
							if ( $url ) {
								$link_attrs = $this->render_link_attrs( $bi['link'] );
								?>
								<a<?php echo $link_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="egsap-bfn__word"><?php echo esc_html( $text ); ?></a>
								<?php
							} else {
								?>
								<p class="egsap-bfn__word"><?php echo esc_html( $text ); ?></p>
								<?php
							}
						endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</nav>
		<?php
	}
}
