<?php
namespace Elementor_GSAP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Expanding_Bottom_Nav_Widget extends Widget_Base {

	public function get_name() {
		return 'expanding_bottom_nav';
	}

	public function get_title() {
		return __( 'Expanding Bottom Navigation', 'elementor-gsap' );
	}

	public function get_icon() {
		return 'eicon-navigation-horizontal';
	}

	public function get_categories() {
		return [ 'elementor-gsap-nav' ];
	}

	public function get_keywords() {
		return [ 'nav', 'bottom', 'pill', 'menu', 'expanding', 'floating', 'gsap', 'osmo' ];
	}

	public function get_script_depends() {
		return [ 'gsap', 'gsap-customease', 'elementor-expanding-bottom-nav' ];
	}

	public function get_style_depends() {
		return [ 'elementor-expanding-bottom-nav' ];
	}

	/**
	 * Sanitize user-provided SVG. Strips <script>, <foreignObject>, on*
	 * event handlers, dan href/xlink:href javascript: schemes. Case attribut
	 * (viewBox, dll.) tetap dipertahankan supaya SVG render benar.
	 */
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

	public function default_osmo_svg() {
		return '<svg xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 54 16" fill="none"><path d="M7.81189 16C10.4864 16 12.846 14.625 14.2542 12.5295C15.0631 14.6634 17.1556 16 19.9261 16C21.9221 16 23.6057 15.3477 24.6874 14.2751L24.5315 15.6688H27.9112L28.7201 8.45255L30.5982 15.6688H33.9811L35.8592 8.45255L36.6676 15.6688H40.0473L39.6853 12.4383C41.0857 14.585 43.475 16 46.1881 16C50.5024 16 54 12.4233 54 8.01147C54 3.59959 50.5014 0.0229341 46.1871 0.0229341C42.8397 0.0229341 39.9852 2.17661 38.8733 5.20177L38.3272 0.330678H34.5923L32.2886 9.18271L29.9849 0.330678H26.25L25.7253 5.01083C25.6617 3.64919 25.1151 2.41555 24.1721 1.52218C23.1212 0.526417 21.653 0 19.9256 0C18.3072 0 16.9126 0.471482 15.8924 1.36378C15.1403 2.02193 14.6479 2.87156 14.4576 3.81399C13.0812 1.53872 10.6205 0.0229341 7.81189 0.0229341C3.49757 0.0229341 0 3.59959 0 8.01147C0 12.4233 3.49757 16 7.81189 16ZM46.1871 3.61772C48.5602 3.61772 50.4837 5.58472 50.4837 8.01147C50.4837 10.4382 48.5602 12.4052 46.1871 12.4052C43.814 12.4052 41.8905 10.4382 41.8905 8.01147C41.8905 5.58472 43.814 3.61772 46.1871 3.61772ZM19.9261 3.34838C21.3927 3.34838 22.2929 4.0428 22.3336 5.20604L22.3461 5.55965H25.6643L25.3222 8.61469C25.1521 8.33468 24.9503 8.076 24.7151 7.8408C23.9411 7.0685 22.8062 6.54262 21.3411 6.27808L19.366 5.91646C18.0574 5.67539 17.7893 5.25724 17.7893 4.70362C17.7893 4.56495 17.8493 3.34784 19.9261 3.34784V3.34838ZM18.508 9.27444L20.8352 9.72086C22.3378 10.0158 22.5402 10.6393 22.5402 11.2105C22.5402 12.1124 21.5383 12.6724 19.9251 12.6724C18.0094 12.6724 17.2855 11.5673 17.2474 10.6212L17.2333 10.2697H15.3046C15.5106 9.55339 15.6233 8.79603 15.6233 8.01147C15.6233 7.97413 15.6212 7.9368 15.6207 7.89946C16.3143 8.57309 17.2829 9.04297 18.508 9.27444H18.508ZM7.81189 3.61772C10.185 3.61772 12.1085 5.58472 12.1085 8.01147C12.1085 10.4382 10.185 12.4052 7.81189 12.4052C5.4388 12.4052 3.5153 10.4382 3.5153 8.01147C3.5153 5.58472 5.4388 3.61772 7.81189 3.61772Z" fill="currentColor"></path></svg>';
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

		/* === CONTENT: LAYOUT === */
		$this->start_controls_section( 'content_layout', [
			'label' => __( 'Layout', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'position', [
			'label'   => __( 'Position', 'elementor-gsap' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'bottom' => __( 'Bottom (expand up)', 'elementor-gsap' ),
				'top'    => __( 'Top (expand down)', 'elementor-gsap' ),
			],
			'default' => 'bottom',
		] );

		$this->add_responsive_control( 'offset', [
			'label'      => __( 'Vertical Offset', 'elementor-gsap' ),
			'description' => __( 'Jarak dari edge atas / bawah viewport.', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 8, 'step' => 0.1 ],
				'px' => [ 'min' => 0, 'max' => 120 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 2 ],
			'selectors'  => [
				'{{WRAPPER}} .bottom-nav' => '--ebn-offset: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'closed_width', [
			'label'       => __( 'Closed Width', 'elementor-gsap' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'em', 'px' ],
			'range'       => [
				'em' => [ 'min' => 8, 'max' => 30, 'step' => 0.5 ],
				'px' => [ 'min' => 120, 'max' => 500 ],
			],
			'default'     => [ 'unit' => 'em', 'size' => 15 ],
			'selectors'   => [
				'{{WRAPPER}} .bottom-nav' => '--ebn-closed-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'open_width', [
			'label'       => __( 'Open Width', 'elementor-gsap' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'em', 'px' ],
			'range'       => [
				'em' => [ 'min' => 15, 'max' => 40, 'step' => 0.5 ],
				'px' => [ 'min' => 240, 'max' => 800 ],
			],
			'default'     => [ 'unit' => 'em', 'size' => 25 ],
			'selectors'   => [
				'{{WRAPPER}} .bottom-nav' => '--ebn-open-width: {{SIZE}}{{UNIT}};',
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
				'{{WRAPPER}} .bottom-nav' => '--ebn-bar-height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'z_index', [
			'label'   => __( 'Z-Index', 'elementor-gsap' ),
			'type'    => Controls_Manager::NUMBER,
			'min'     => 1,
			'max'     => 9999,
			'default' => 99,
			'selectors' => [
				'{{WRAPPER}} .bottom-nav' => '--ebn-z: {{VALUE}};',
			],
		] );

		$this->end_controls_section();

		/* === CONTENT: LOGO === */
		$this->start_controls_section( 'content_logo', [
			'label' => __( 'Logo', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'logo_type', [
			'label'   => __( 'Logo Type', 'elementor-gsap' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'default' => __( 'Default SVG (Osmo)', 'elementor-gsap' ),
				'image'   => __( 'Image Upload', 'elementor-gsap' ),
				'svg'     => __( 'Custom SVG', 'elementor-gsap' ),
				'text'    => __( 'Text', 'elementor-gsap' ),
				'none'    => __( 'None', 'elementor-gsap' ),
			],
			'default' => 'default',
		] );

		$this->add_control( 'logo_image', [
			'label'     => __( 'Logo Image', 'elementor-gsap' ),
			'type'      => Controls_Manager::MEDIA,
			'default'   => [ 'url' => '' ],
			'condition' => [ 'logo_type' => 'image' ],
		] );

		$this->add_control( 'logo_alt', [
			'label'     => __( 'Logo Alt Text', 'elementor-gsap' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => 'Logo',
			'condition' => [ 'logo_type' => 'image' ],
		] );

		$this->add_control( 'logo_svg', [
			'label'       => __( 'Custom SVG Code', 'elementor-gsap' ),
			'description' => __( 'Paste kode <code>&lt;svg&gt;…&lt;/svg&gt;</code>. Gunakan <code>fill="currentColor"</code> pada path yang ingin ikut warna dari <strong>Logo Color</strong>.', 'elementor-gsap' ),
			'type'        => Controls_Manager::TEXTAREA,
			'rows'        => 10,
			'default'     => '',
			'condition'   => [ 'logo_type' => 'svg' ],
		] );

		$this->add_control( 'logo_svg_alt', [
			'label'     => __( 'SVG Aria Label', 'elementor-gsap' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => 'Logo',
			'condition' => [ 'logo_type' => 'svg' ],
		] );

		$this->add_control( 'logo_text', [
			'label'     => __( 'Logo Text', 'elementor-gsap' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => 'Brand',
			'condition' => [ 'logo_type' => 'text' ],
		] );

		$this->add_control( 'logo_link', [
			'label'     => __( 'Logo Link', 'elementor-gsap' ),
			'type'      => Controls_Manager::URL,
			'default'   => [ 'url' => '#' ],
			'condition' => [ 'logo_type!' => 'none' ],
		] );

		$this->end_controls_section();

		/* === CONTENT: MAIN LINKS === */
		$this->start_controls_section( 'content_links', [
			'label' => __( 'Main Menu', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$rep = new Repeater();
		$rep->add_control( 'label', [
			'label'   => __( 'Label', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Menu Item',
			'dynamic' => [ 'active' => true ],
		] );
		$rep->add_control( 'link', [
			'label'   => __( 'Link', 'elementor-gsap' ),
			'type'    => Controls_Manager::URL,
			'default' => [ 'url' => '#' ],
		] );
		$rep->add_control( 'is_cta', [
			'label'        => __( 'Show as CTA', 'elementor-gsap' ),
			'description'  => __( 'Warna berbeda dari link biasa (default biru). Cocok untuk "Contact ↗".', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => '',
		] );

		$this->add_control( 'menu_items', [
			'label'       => __( 'Items', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ label }}}',
			'default'     => [
				[ 'label' => 'Home',       'link' => [ 'url' => '#' ], 'is_cta' => '' ],
				[ 'label' => 'Work',       'link' => [ 'url' => '#' ], 'is_cta' => '' ],
				[ 'label' => 'Studio',     'link' => [ 'url' => '#' ], 'is_cta' => '' ],
				[ 'label' => 'Journal',    'link' => [ 'url' => '#' ], 'is_cta' => '' ],
				[ 'label' => 'Contact ↗',  'link' => [ 'url' => '#' ], 'is_cta' => 'yes' ],
			],
		] );

		$this->end_controls_section();

		/* === CONTENT: SECONDARY === */
		$this->start_controls_section( 'content_secondary', [
			'label' => __( 'Secondary Columns', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'show_secondary', [
			'label'        => __( 'Show Secondary Columns', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'show_divider', [
			'label'        => __( 'Show Divider', 'elementor-gsap' ),
			'description'  => __( 'Garis pemisah antara main menu & secondary columns.', 'elementor-gsap' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
			'condition'    => [ 'show_secondary' => 'yes' ],
		] );

		/* Column 1 */
		$this->add_control( 'col1_heading', [
			'label'     => __( 'Column 1', 'elementor-gsap' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [ 'show_secondary' => 'yes' ],
		] );

		$this->add_control( 'col1_label', [
			'label'     => __( 'Column 1 Label', 'elementor-gsap' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => 'socials',
			'condition' => [ 'show_secondary' => 'yes' ],
		] );

		$col1_rep = new Repeater();
		$col1_rep->add_control( 'label', [
			'label'   => __( 'Label', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Item',
			'dynamic' => [ 'active' => true ],
		] );
		$col1_rep->add_control( 'link', [
			'label'   => __( 'Link', 'elementor-gsap' ),
			'type'    => Controls_Manager::URL,
			'default' => [ 'url' => '#' ],
		] );

		$this->add_control( 'col1_items', [
			'label'       => __( 'Column 1 Items', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $col1_rep->get_controls(),
			'title_field' => '{{{ label }}}',
			'default'     => [
				[ 'label' => 'Instagram', 'link' => [ 'url' => '#' ] ],
				[ 'label' => 'LinkedIn',  'link' => [ 'url' => '#' ] ],
				[ 'label' => 'X/Twitter', 'link' => [ 'url' => '#' ] ],
			],
			'condition'   => [ 'show_secondary' => 'yes' ],
		] );

		/* Column 2 */
		$this->add_control( 'col2_heading', [
			'label'     => __( 'Column 2', 'elementor-gsap' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [ 'show_secondary' => 'yes' ],
		] );

		$this->add_control( 'col2_label', [
			'label'     => __( 'Column 2 Label', 'elementor-gsap' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => 'studio',
			'condition' => [ 'show_secondary' => 'yes' ],
		] );

		$col2_rep = new Repeater();
		$col2_rep->add_control( 'label', [
			'label'   => __( 'Label', 'elementor-gsap' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Item',
			'dynamic' => [ 'active' => true ],
		] );
		$col2_rep->add_control( 'link', [
			'label'   => __( 'Link', 'elementor-gsap' ),
			'type'    => Controls_Manager::URL,
			'default' => [ 'url' => '#' ],
		] );

		$this->add_control( 'col2_items', [
			'label'       => __( 'Column 2 Items', 'elementor-gsap' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $col2_rep->get_controls(),
			'title_field' => '{{{ label }}}',
			'default'     => [
				[ 'label' => '+123456789',       'link' => [ 'url' => 'tel:+123456789' ] ],
				[ 'label' => 'hello@osmo.supply', 'link' => [ 'url' => 'mailto:hello@osmo.supply' ] ],
			],
			'condition'   => [ 'show_secondary' => 'yes' ],
		] );

		$this->end_controls_section();

		/* === CONTENT: ANIMATION === */
		$this->start_controls_section( 'content_anim', [
			'label' => __( 'Animation', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'open_duration', [
			'label'       => __( 'Open Duration (s)', 'elementor-gsap' ),
			'description' => __( 'Durasi morph closed → open.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0.1,
			'max'         => 3,
			'step'        => 0.05,
			'default'     => 0.65,
		] );

		$this->add_control( 'reveal_stagger', [
			'label'       => __( 'Reveal Stagger (s)', 'elementor-gsap' ),
			'description' => __( 'Jeda antar-item saat panel reveal masuk.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0,
			'max'         => 0.3,
			'step'        => 0.005,
			'default'     => 0.03,
		] );

		$this->add_control( 'bar_duration', [
			'label'       => __( 'Toggle Bars Duration (s)', 'elementor-gsap' ),
			'description' => __( 'Durasi 2 bar toggle rotate jadi X.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0.05,
			'max'         => 1,
			'step'        => 0.05,
			'default'     => 0.4,
		] );

		$this->end_controls_section();

		/* === STYLE: CONTAINER === */
		$this->start_controls_section( 'style_container', [
			'label' => __( 'Container', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'bg_color', [
			'label'     => __( 'Background Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#fafafa',
			'selectors' => [
				'{{WRAPPER}} .bottom-nav' => '--ebn-bg: {{VALUE}};',
			],
		] );

		$this->add_control( 'border_color', [
			'label'     => __( 'Border Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(0, 0, 0, 0.2)',
			'selectors' => [
				'{{WRAPPER}} .bottom-nav' => '--ebn-border-color: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'border_width', [
			'label'      => __( 'Border Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 0, 'max' => 5, 'step' => 1 ] ],
			'default'    => [ 'unit' => 'px', 'size' => 1 ],
			'selectors'  => [
				'{{WRAPPER}} .bottom-nav' => '--ebn-border-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'radius', [
			'label'      => __( 'Border Radius', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 5, 'step' => 0.05 ],
				'px' => [ 'min' => 0, 'max' => 80 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 0.5 ],
			'selectors'  => [
				'{{WRAPPER}} .bottom-nav' => '--ebn-radius: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		/* === STYLE: LOGO === */
		$this->start_controls_section( 'style_logo', [
			'label' => __( 'Logo', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'logo_color', [
			'label'     => __( 'Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#201d1d',
			'selectors' => [
				'{{WRAPPER}} .bottom-nav' => '--ebn-logo-color: {{VALUE}};',
			],
			'condition' => [ 'logo_type!' => 'none' ],
		] );

		$this->add_responsive_control( 'logo_width', [
			'label'      => __( 'Width', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 1, 'max' => 12, 'step' => 0.1 ],
				'px' => [ 'min' => 16, 'max' => 200 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 4 ],
			'selectors'  => [
				'{{WRAPPER}} .bottom-nav' => '--ebn-logo-width: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [ 'logo_type!' => 'none' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'logo_text_typography',
			'label'     => __( 'Text Typography', 'elementor-gsap' ),
			'selector'  => '{{WRAPPER}} .bottom-nav__logo-text',
			'condition' => [ 'logo_type' => 'text' ],
		] );

		$this->end_controls_section();

		/* === STYLE: TOGGLE === */
		$this->start_controls_section( 'style_toggle', [
			'label' => __( 'Toggle Button', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'toggle_bg', [
			'label'     => __( 'Background', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#1e6fec',
			'selectors' => [
				'{{WRAPPER}} .bottom-nav' => '--ebn-toggle-bg: {{VALUE}};',
			],
		] );

		$this->add_control( 'toggle_color', [
			'label'     => __( 'Icon Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .bottom-nav' => '--ebn-toggle-color: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'toggle_size', [
			'label'      => __( 'Size', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 1.5, 'max' => 5, 'step' => 0.05 ],
				'px' => [ 'min' => 24, 'max' => 80 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 2.5 ],
			'selectors'  => [
				'{{WRAPPER}} .bottom-nav' => '--ebn-toggle-size: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'toggle_radius', [
			'label'      => __( 'Border Radius', 'elementor-gsap' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px', '%' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 2, 'step' => 0.025 ],
				'px' => [ 'min' => 0, 'max' => 40 ],
				'%'  => [ 'min' => 0, 'max' => 50 ],
			],
			'default'    => [ 'unit' => 'em', 'size' => 0.3125 ],
			'selectors'  => [
				'{{WRAPPER}} .bottom-nav' => '--ebn-toggle-radius: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		/* === STYLE: MAIN LINKS === */
		$this->start_controls_section( 'style_links', [
			'label' => __( 'Main Links', 'elementor-gsap' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->start_controls_tabs( 'links_state_tabs' );

		/* Normal state */
		$this->start_controls_tab( 'links_state_normal', [
			'label' => __( 'Normal', 'elementor-gsap' ),
		] );

		$this->add_control( 'link_color', [
			'label'     => __( 'Link Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#201d1d',
			'selectors' => [
				'{{WRAPPER}} .bottom-nav' => '--ebn-link-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'cta_color', [
			'label'     => __( 'CTA Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#1e6fec',
			'selectors' => [
				'{{WRAPPER}} .bottom-nav' => '--ebn-cta-color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		/* Hover state */
		$this->start_controls_tab( 'links_state_hover', [
			'label' => __( 'Hover', 'elementor-gsap' ),
		] );

		$this->add_control( 'link_hover_color', [
			'label'     => __( 'Link Hover Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#1e6fec',
			'selectors' => [
				'{{WRAPPER}} .bottom-nav' => '--ebn-link-hover-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'cta_hover_color', [
			'label'     => __( 'CTA Hover Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#201d1d',
			'selectors' => [
				'{{WRAPPER}} .bottom-nav' => '--ebn-cta-hover-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'link_transition', [
			'label'       => __( 'Transition Duration (s)', 'elementor-gsap' ),
			'description' => __( 'Durasi fade warna saat hover.', 'elementor-gsap' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0,
			'max'         => 2,
			'step'        => 0.05,
			'default'     => 0.25,
			'selectors'   => [
				'{{WRAPPER}} .bottom-nav' => '--ebn-link-transition: {{VALUE}}s ease;',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'link_typography',
			'selector' => '{{WRAPPER}} .bottom-nav__link',
		] );

		$this->end_controls_section();

		/* === STYLE: DIVIDER === */
		$this->start_controls_section( 'style_divider', [
			'label'     => __( 'Divider', 'elementor-gsap' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'show_secondary' => 'yes', 'show_divider' => 'yes' ],
		] );

		$this->add_control( 'divider_color', [
			'label'     => __( 'Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(0, 0, 0, 0.2)',
			'selectors' => [
				'{{WRAPPER}} .bottom-nav' => '--ebn-divider-color: {{VALUE}};',
			],
		] );

		$this->end_controls_section();

		/* === STYLE: COLUMN LABELS === */
		$this->start_controls_section( 'style_labels', [
			'label'     => __( 'Column Labels', 'elementor-gsap' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'show_secondary' => 'yes' ],
		] );

		$this->add_control( 'label_color', [
			'label'     => __( 'Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(32, 29, 29, 0.65)',
			'selectors' => [
				'{{WRAPPER}} .bottom-nav' => '--ebn-label-color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'label_typography',
			'selector' => '{{WRAPPER}} .bottom-nav__label',
		] );

		$this->end_controls_section();

		/* === STYLE: SUBLINKS === */
		$this->start_controls_section( 'style_sublinks', [
			'label'     => __( 'Column Links', 'elementor-gsap' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'show_secondary' => 'yes' ],
		] );

		$this->add_control( 'sublink_color', [
			'label'     => __( 'Color', 'elementor-gsap' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#201d1d',
			'selectors' => [
				'{{WRAPPER}} .bottom-nav' => '--ebn-sublink-color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'sublink_typography',
			'selector' => '{{WRAPPER}} .bottom-nav__sublink',
		] );

		$this->end_controls_section();
	}

	protected function is_edit_mode() {
		return class_exists( '\Elementor\Plugin' )
			&& \Elementor\Plugin::$instance->editor
			&& \Elementor\Plugin::$instance->editor->is_edit_mode();
	}

	/**
	 * Format slider-array value {size, unit} → CSS length ("2em", "16px").
	 * Return kosong kalau size tidak valid.
	 */
	private function fmt_size( $val, $default_unit = 'em' ) {
		if ( ! is_array( $val ) || ! isset( $val['size'] ) || '' === $val['size'] || null === $val['size'] ) {
			return '';
		}
		$unit = isset( $val['unit'] ) && '' !== $val['unit'] ? (string) $val['unit'] : $default_unit;
		return floatval( $val['size'] ) . $unit;
	}

	/**
	 * Bangun inline style attribute yang berisi SEMUA CSS variables --ebn-*
	 * dari nilai control saat ini. Dipakai sebagai safety net di atas
	 * Elementor post-CSS file (yang kadang stale / caching agresif) supaya
	 * frontend selalu reflect nilai terbaru — inline style specificity
	 * (1,0,0,0) menang dari selector apapun.
	 */
	private function build_style_attr( array $s ) {
		$map = [];

		/* dimensions */
		$vals = [
			'--ebn-offset'        => $this->fmt_size( $s['offset']       ?? null, 'em' ),
			'--ebn-closed-width'  => $this->fmt_size( $s['closed_width'] ?? null, 'em' ),
			'--ebn-open-width'    => $this->fmt_size( $s['open_width']   ?? null, 'em' ),
			'--ebn-bar-height'    => $this->fmt_size( $s['bar_height']   ?? null, 'em' ),
			'--ebn-radius'        => $this->fmt_size( $s['radius']       ?? null, 'em' ),
			'--ebn-border-width'  => $this->fmt_size( $s['border_width'] ?? null, 'px' ),
			'--ebn-logo-width'    => $this->fmt_size( $s['logo_width']   ?? null, 'em' ),
			'--ebn-toggle-size'   => $this->fmt_size( $s['toggle_size']  ?? null, 'em' ),
			'--ebn-toggle-radius' => $this->fmt_size( $s['toggle_radius'] ?? null, 'em' ),
		];
		foreach ( $vals as $var => $v ) {
			if ( '' !== $v ) {
				$map[ $var ] = $v;
			}
		}

		/* z-index */
		if ( isset( $s['z_index'] ) && '' !== $s['z_index'] ) {
			$map['--ebn-z'] = intval( $s['z_index'] );
		}

		/* colors — plain string values */
		$color_keys = [
			'--ebn-bg'              => 'bg_color',
			'--ebn-border-color'    => 'border_color',
			'--ebn-logo-color'      => 'logo_color',
			'--ebn-toggle-bg'       => 'toggle_bg',
			'--ebn-toggle-color'    => 'toggle_color',
			'--ebn-link-color'      => 'link_color',
			'--ebn-link-hover-color'=> 'link_hover_color',
			'--ebn-cta-color'       => 'cta_color',
			'--ebn-cta-hover-color' => 'cta_hover_color',
			'--ebn-divider-color'   => 'divider_color',
			'--ebn-label-color'     => 'label_color',
			'--ebn-sublink-color'   => 'sublink_color',
		];
		foreach ( $color_keys as $var => $key ) {
			if ( isset( $s[ $key ] ) && '' !== $s[ $key ] ) {
				$map[ $var ] = (string) $s[ $key ];
			}
		}

		/* transition (numeric → "0.25s ease") */
		if ( isset( $s['link_transition'] ) && '' !== $s['link_transition'] ) {
			$map['--ebn-link-transition'] = floatval( $s['link_transition'] ) . 's ease';
		}

		if ( empty( $map ) ) {
			return '';
		}
		$parts = [];
		foreach ( $map as $var => $val ) {
			$parts[] = $var . ': ' . $val;
		}
		return ' style="' . esc_attr( implode( '; ', $parts ) ) . '"';
	}

	private function render_logo( $s ) {
		$logo_type = ! empty( $s['logo_type'] ) ? $s['logo_type'] : 'default';
		if ( 'none' === $logo_type ) {
			return;
		}
		$link_attrs = $this->render_link_attrs( isset( $s['logo_link'] ) ? $s['logo_link'] : [] );
		?>
		<a<?php echo $link_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="bottom-nav__logo">
			<?php if ( 'image' === $logo_type && ! empty( $s['logo_image']['url'] ) ) :
				$alt = ! empty( $s['logo_alt'] ) ? $s['logo_alt'] : '';
				?>
				<img src="<?php echo esc_url( $s['logo_image']['url'] ); ?>" alt="<?php echo esc_attr( $alt ); ?>" />
			<?php elseif ( 'svg' === $logo_type ) :
				$svg_code  = isset( $s['logo_svg'] ) ? $this->sanitize_custom_svg( $s['logo_svg'] ) : '';
				$svg_label = isset( $s['logo_svg_alt'] ) ? $s['logo_svg_alt'] : '';
				if ( '' !== $svg_code ) :
					?>
					<span class="bottom-nav__logo-svg-custom" role="img"<?php echo '' !== $svg_label ? ' aria-label="' . esc_attr( $svg_label ) . '"' : ' aria-hidden="true"'; ?>><?php
						echo $svg_code; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?></span>
					<?php
				endif;
			elseif ( 'text' === $logo_type ) :
				$txt = ! empty( $s['logo_text'] ) ? $s['logo_text'] : '';
				?>
				<span class="bottom-nav__logo-text"><?php echo esc_html( $txt ); ?></span>
			<?php else :
				echo $this->default_osmo_svg(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			endif; ?>
		</a>
		<?php
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$position       = ! empty( $s['position'] ) && 'top' === $s['position'] ? 'top' : 'bottom';
		$menu_items     = ! empty( $s['menu_items'] ) ? $s['menu_items'] : [];
		$show_secondary = ! empty( $s['show_secondary'] ) && 'yes' === $s['show_secondary'];
		$show_divider   = ! empty( $s['show_divider'] )   && 'yes' === $s['show_divider'] && $show_secondary;

		$col1_label = isset( $s['col1_label'] ) ? $s['col1_label'] : '';
		$col1_items = ! empty( $s['col1_items'] ) ? $s['col1_items'] : [];
		$col2_label = isset( $s['col2_label'] ) ? $s['col2_label'] : '';
		$col2_items = ! empty( $s['col2_items'] ) ? $s['col2_items'] : [];

		$open_duration  = isset( $s['open_duration'] )  && '' !== $s['open_duration']  ? floatval( $s['open_duration'] )  : 0.65;
		$reveal_stagger = isset( $s['reveal_stagger'] ) && '' !== $s['reveal_stagger'] ? floatval( $s['reveal_stagger'] ) : 0.03;
		$bar_duration   = isset( $s['bar_duration'] )   && '' !== $s['bar_duration']   ? floatval( $s['bar_duration'] )   : 0.4;

		$is_edit      = $this->is_edit_mode();
		$root_classes = 'bottom-nav' . ( $is_edit ? ' egsap-edit-mode' : '' );
		// Inline style hanya untuk frontend — di editor kita percayakan ke
		// Elementor's live-preview <style> block (yang update real-time saat
		// user geser slider / ganti color). Inline attribute specificity 1000
		// akan meng-override live-preview → user pikir control-nya tidak
		// bekerja padahal cuma stuck di nilai render terakhir.
		$style_attr   = $is_edit ? '' : $this->build_style_attr( $s );
		?>
		<nav
			data-bottom-nav-init
			data-bottom-nav-open="false"
			data-bottom-nav-position="<?php echo esc_attr( $position ); ?>"
			data-bottom-nav-open-duration="<?php echo esc_attr( $open_duration ); ?>"
			data-bottom-nav-stagger="<?php echo esc_attr( $reveal_stagger ); ?>"
			data-bottom-nav-bar-duration="<?php echo esc_attr( $bar_duration ); ?>"
			class="<?php echo esc_attr( $root_classes ); ?>"
			<?php echo $style_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		>
			<div data-bottom-nav-inner class="bottom-nav__inner">
				<div data-bottom-nav-bar class="bottom-nav__bar">
					<?php $this->render_logo( $s ); ?>
					<button
						type="button"
						data-bottom-nav-toggle
						aria-expanded="false"
						aria-label="open menu"
						class="bottom-nav__toggle"
					>
						<span class="bottom-nav__toggle-bar is--top"></span>
						<span class="bottom-nav__toggle-bar is--btm"></span>
					</button>
				</div>
				<div aria-hidden="true" data-bottom-nav-panel class="bottom-nav__panel">
					<ul class="bottom-nav__list">
						<?php foreach ( $menu_items as $item ) :
							$label = ! empty( $item['label'] ) ? $item['label'] : '';
							$attrs = $this->render_link_attrs( isset( $item['link'] ) ? $item['link'] : [] );
							$cta   = ! empty( $item['is_cta'] ) && 'yes' === $item['is_cta'];
							$cls   = 'bottom-nav__link' . ( $cta ? ' is--cta' : '' );
							?>
							<li data-bottom-nav-reveal class="bottom-nav__list-item">
								<a<?php echo $attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="<?php echo esc_attr( $cls ); ?>">
									<?php echo esc_html( $label ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>

					<?php if ( $show_divider ) : ?>
						<div data-bottom-nav-divider class="bottom-nav__divider"></div>
					<?php endif; ?>

					<?php if ( $show_secondary ) : ?>
						<div class="bottom-nav__secondary">
							<?php if ( '' !== $col1_label || ! empty( $col1_items ) ) : ?>
								<div class="bottom-nav__col">
									<?php if ( '' !== $col1_label ) : ?>
										<span data-bottom-nav-reveal class="bottom-nav__label"><?php echo esc_html( $col1_label ); ?></span>
									<?php endif; ?>
									<?php if ( ! empty( $col1_items ) ) : ?>
										<ul class="bottom-nav__list">
											<?php foreach ( $col1_items as $item ) :
												$attrs = $this->render_link_attrs( isset( $item['link'] ) ? $item['link'] : [] );
												?>
												<li data-bottom-nav-reveal class="bottom-nav__list-item">
													<a<?php echo $attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="bottom-nav__sublink">
														<?php echo esc_html( ! empty( $item['label'] ) ? $item['label'] : '' ); ?>
													</a>
												</li>
											<?php endforeach; ?>
										</ul>
									<?php endif; ?>
								</div>
							<?php endif; ?>
							<?php if ( '' !== $col2_label || ! empty( $col2_items ) ) : ?>
								<div class="bottom-nav__col">
									<?php if ( '' !== $col2_label ) : ?>
										<span data-bottom-nav-reveal class="bottom-nav__label"><?php echo esc_html( $col2_label ); ?></span>
									<?php endif; ?>
									<?php if ( ! empty( $col2_items ) ) : ?>
										<ul class="bottom-nav__list">
											<?php foreach ( $col2_items as $item ) :
												$attrs = $this->render_link_attrs( isset( $item['link'] ) ? $item['link'] : [] );
												?>
												<li data-bottom-nav-reveal class="bottom-nav__list-item">
													<a<?php echo $attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="bottom-nav__sublink">
														<?php echo esc_html( ! empty( $item['label'] ) ? $item['label'] : '' ); ?>
													</a>
												</li>
											<?php endforeach; ?>
										</ul>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</nav>
		<?php
	}
}
